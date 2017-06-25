<html lang="fa" dir="rtl">
<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8"/>
</head>
<body style="text-align: center; color: #ff0000; margin-top: 10%">
<?php
require_once __DIR__ . '/includes/123pay.php';

$merchant_id = $_POST['merchant_id'];
$amount = ($_POST['currencies'] == 'Toman') ? ((int)($_POST['amount'])) * 10 : ((int)($_POST['amount']));
$callback_url = $_POST['systemurl'] . 'modules/gateways/callback/123pay.php?invoiceid=' . $_POST['invoiceid'];

if (!extension_loaded('curl')) {
    echo 'خطا : curl فعال نیست';
    exit();
}

$response = create($merchant_id, $amount, $callback_url);
$result = json_decode($response);
if ($result->status) {
    header('Location:' . $result->payment_url);
} else {
    echo 'خطا : ' . $result->message;
}
?>
</body>
</html>