<?php
session_start();
require_once("config/paytm_config.php");
require_once("db_connect.php");
require_once("lib/paytm/PaytmChecksum.php");
require_once("lib/send_email.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = false;

// Get all parameters in an array
$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
unset($paramList["CHECKSUMHASH"]);

$isValidChecksum = PaytmChecksum::verifySignature($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);

$orderId = $_POST["ORDERID"];
$transactionId = isset($_POST["TXNID"]) ? $_POST["TXNID"] : "";
$status = $_POST["STATUS"];
$amount = $_POST["TXNAMOUNT"];
$paymentMode = isset($_POST["PAYMENTMODE"]) ? $_POST["PAYMENTMODE"] : "";
$txnDate = isset($_POST["TXNDATE"]) ? $_POST["TXNDATE"] : "";
$respCode = $_POST["RESPCODE"];
$respMsg = $_POST["RESPMSG"];

// Update order status in database
$sql = "UPDATE orders SET 
        transaction_id = ?, 
        status = ?, 
        payment_mode = ?,
        payment_response = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$paymentResponse = json_encode($_POST);
$stmt->bind_param("sssss", $transactionId, $status, $paymentMode, $paymentResponse, $orderId);
$stmt->execute();

// Function to save order items
function saveOrderItems($orderId, $cart) {
    global $conn;
    foreach ($cart as $item) {
        $sql = "INSERT INTO order_items (order_id, product_name, quantity, price, type) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssids", $orderId, $item['name'], $item['quantity'], $item['priceUSD'], $item['type']);
        $stmt->execute();
    }
}

// Send order confirmation email if payment successful
if ($status === "TXN_SUCCESS" && $isValidChecksum) {
    // Get customer email from session
    $customerEmail = $_SESSION['order_details']['email'];
    
    // Prepare order details for email
    $emailOrderDetails = [
        'order_id' => $orderId,
        'transaction_id' => $transactionId,
        'amount' => $amount,
        'payment_mode' => $paymentMode,
        'date' => $txnDate
    ];
    
    // Get cart items from session
    $cartItems = isset($_SESSION['cart']) ? json_decode($_SESSION['cart'], true) : [];
    
    // Send confirmation email
    sendOrderConfirmation($customerEmail, $emailOrderDetails, $cartItems);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status | NeoTech Forge</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-status {
            text-align: center;
            max-width: 600px;
            margin: 50px auto;
            padding: 2rem;
            background: var(--surface);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .success { color: #2cb67d; }
        .failure { color: #ef4565; }
        .payment-details {
            text-align: left;
            margin: 2rem 0;
            padding: 1rem;
            background: var(--surface-light);
            border-radius: 4px;
        }
        .payment-details p {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
    <div class="payment-status">
        <?php if ($status === "TXN_SUCCESS" && $isValidChecksum) { 
            // Save order items on successful payment
            if(isset($_SESSION['cart'])) {
                saveOrderItems($orderId, json_decode($_SESSION['cart'], true));
            }
        ?>
            <i class="fas fa-check-circle status-icon success"></i>
            <h2>Payment Successful!</h2>
            <div class="payment-details">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderId); ?></p>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transactionId); ?></p>
                <p><strong>Amount:</strong> ₹<?php echo htmlspecialchars($amount); ?></p>
                <p><strong>Payment Mode:</strong> <?php echo htmlspecialchars($paymentMode); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($txnDate); ?></p>
            </div>
            <p class="success-message">Your order has been confirmed and will be processed shortly.</p>
            <script>
                // Clear cart after successful payment
                localStorage.removeItem('cart');
            </script>
        <?php } else { ?>
            <i class="fas fa-times-circle status-icon failure"></i>
            <h2>Payment Failed</h2>
            <div class="payment-details">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderId); ?></p>
                <p><strong>Error Code:</strong> <?php echo htmlspecialchars($respCode); ?></p>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($respMsg); ?></p>
            </div>
            <p class="error-message">Something went wrong with your payment. Please try again.</p>
        <?php } ?>
        <div class="action-buttons">
            <a href="index.html" class="btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <?php if ($status !== "TXN_SUCCESS") { ?>
                <a href="cart.html" class="btn-primary" style="margin-left: 1rem;">
                    <i class="fas fa-shopping-cart"></i> Return to Cart
                </a>
            <?php } ?>
        </div>
    </div>
</body>
</html>