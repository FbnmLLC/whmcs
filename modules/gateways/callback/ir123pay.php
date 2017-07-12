<?php
if ( file_exists( __DIR__ . '/../../../init.php' ) ) {
	require_once __DIR__ . '/../../../init.php';
} else {
	require_once __DIR__ . '/../../../dbconnect.php';
}
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';
require_once __DIR__ . '/../../../includes/ir123pay.php';

$GATEWAY = getGatewayVariables( 'ir123pay' );
if ( ! $GATEWAY['type'] ) {
	die( 'Module Not Activated' );
}

$invoiceid = checkCbInvoiceID( $_GET['invoiceid'], $GATEWAY['name'] );

$merchant_id = $GATEWAY['merchant_id'];
$State       = $_REQUEST['State'];
$RefNum      = $_REQUEST['RefNum'];

if ( $State == 'OK' ) {
	$response = verify( $merchant_id, $RefNum );
	$result   = json_decode( $response );
	if ( $result->status ) {
		$checkAmountData = mysql_fetch_array( select_query( "tblinvoices", "", array( "id" => $invoiceid ) ) );
		$amount          = strtok( $checkAmountData['total'], '.' );
		$verifiedAmount  = ( $GATEWAY['Currencies'] == 'Toman' ) ? $result->amount / 10 : $result->amount;

		if ( $verifiedAmount == $amount ) {
			checkCbTransID( $RefNum );
			addInvoicePayment( $invoiceid, $RefNum, $verifiedAmount, 0, 'ir123pay' );
			logTransaction( $GATEWAY["name"], array_merge( $_REQUEST, array(
				'status'  => $result->status,
				'message' => $result->message,
				'amount'  => $result->amount
			) ), "Successful" );
		} else {
			logTransaction( $GATEWAY["name"], array_merge( $_REQUEST, array(
				'status'  => $result->status,
				'message' => $result->message,
				'amount'  => $result->amount
			) ), "Unsuccessful" );
		}
	}
} else {
	logTransaction( $GATEWAY["name"], $_REQUEST, "Unsuccessful" );
}

header( 'location: ' . $CONFIG['SystemURL'] . '/viewinvoice.php?id=' . $invoiceid );
?>