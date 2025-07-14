<?php
session_start();
require_once("config/paytm_config.php");
require_once("db_connect.php");
require_once("lib/paytm/PaytmChecksum.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['amount'])) {
    header('Location: login.html');
    exit();
}

// Get order details from POST data
$amount = $_POST['amount'];
$userId = $_SESSION['user_id'];
$orderId = "NTFPC" . time();
$custId = "CUST" . $userId;

// Store order details in database
$sql = "INSERT INTO orders (order_id, user_id, amount, status) VALUES (?, ?, ?, 'PENDING')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sid", $orderId, $userId, $amount);
$stmt->execute();

// Store customer details in session for later use
$_SESSION['order_details'] = [
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'phone' => $_POST['phone'],
    'address' => $_POST['address']
];

// Prepare parameters for Paytm
$paytmParams = array(
    "MID" => PAYTM_MERCHANT_MID,
    "ORDER_ID" => $orderId,
    "CUST_ID" => $custId,
    "INDUSTRY_TYPE_ID" => PAYTM_INDUSTRY_TYPE_ID,
    "CHANNEL_ID" => PAYTM_CHANNEL_ID,
    "TXN_AMOUNT" => $amount,
    "WEBSITE" => PAYTM_MERCHANT_WEBSITE,
    "CALLBACK_URL" => PAYTM_CALLBACK_URL,
    "EMAIL" => $_POST['email'],
    "MOBILE_NO" => $_POST['phone']
);

// Generate checksum
$checksum = PaytmChecksum::generateSignature($paytmParams, PAYTM_MERCHANT_KEY);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment... | NeoTech Forge</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-top: 16px solid #7f5af0;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: 50px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .container {
            text-align: center;
            margin-top: 100px;
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            padding: 2rem;
            background: var(--surface);
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Redirecting to Payment Gateway...</h2>
        <div class="loader"></div>
        <p>Please do not refresh this page. You will be automatically redirected to Paytm.</p>
        <form method="post" action="<?php echo PAYTM_TEST_URL; ?>" name="paytm_form" id="paytm_form">
            <?php
            foreach($paytmParams as $name => $value) {
                echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
            }
            ?>
            <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checksum ?>">
        </form>
        <script type="text/javascript">
            document.getElementById("paytm_form").submit();
        </script>
    </div>
</body>
</html>