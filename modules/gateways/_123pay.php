<?php
if ( ! defined( "WHMCS" ) ) {
	die( "This file cannot be accessed directly" );
}

function _123pay_MetaData() {
	return array(
		'DisplayName'                => 'سامانه پرداخت اینترنتی یک دو سه پی',
		'APIVersion'                 => '1.0',
		'DisableLocalCredtCardInput' => false,
		'TokenisedStorage'           => false
	);
}

function _123pay_config() {
	$configarray = array(
		"FriendlyName" => array(
			"Type"  => "System",
			"Value" => "سامانه پرداخت اینترنتی یک دو سه پی"
		),
		"merchant_id"  => array(
			"FriendlyName" => "merchant_id",
			"Type"         => "text",
			"size"         => "36"
		),
		"Currencies"   => array(
			"FriendlyName" => "Currencies",
			"Type"         => "dropdown",
			"Options"      => "Rial,Toman"
		)
	);

	return $configarray;
}

function _123pay_link( $params ) {
	$merchant_id = trim( $params['merchant_id'] );
	$amount      = $params['amount'];
	$systemurl   = $params['systemurl'];
	$currencies  = $params['Currencies'];
	$invoiceid   = $params['invoiceid'];
	$currencies  = $params['Currencies'];
	$code        = '
			<form action="./123pay.php" method="POST">
				<input type="hidden" name="merchant_id" value="' . $merchant_id . '" />
				<input type="hidden" name="amount" value="' . $amount . '" />
				<input type="hidden" name="systemurl" value="' . $systemurl . '" />
				<input type="hidden" name="invoiceid" value="' . $invoiceid . '" />
				<input type="hidden" name="currencies" value="' . $currencies . '" />
				<input type="submit" name="pay" value="  پرداخت آنلاین  " />
			</form>
			';

	return $code;
}

?>