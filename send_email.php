<?php
function sendOrderConfirmation($to, $orderDetails, $items) {
    $subject = "Order Confirmation - NeoTech Forge #" . $orderDetails['order_id'];
    
    $message = "<html><body>";
    $message .= "<h2>Thank you for your order!</h2>";
    $message .= "<p>Your order has been successfully placed and will be processed shortly.</p>";
    
    $message .= "<h3>Order Details</h3>";
    $message .= "<p><strong>Order ID:</strong> " . $orderDetails['order_id'] . "</p>";
    $message .= "<p><strong>Transaction ID:</strong> " . $orderDetails['transaction_id'] . "</p>";
    $message .= "<p><strong>Amount:</strong> ₹" . $orderDetails['amount'] . "</p>";
    $message .= "<p><strong>Payment Method:</strong> " . $orderDetails['payment_mode'] . "</p>";
    $message .= "<p><strong>Date:</strong> " . $orderDetails['date'] . "</p>";
    
    $message .= "<h3>Items Ordered</h3>";
    $message .= "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    $message .= "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
    
    foreach($items as $item) {
        $message .= "<tr>";
        $message .= "<td>" . $item['name'] . "</td>";
        $message .= "<td>" . $item['quantity'] . "</td>";
        $message .= "<td>₹" . number_format($item['priceUSD'] * 82.5, 2) . "</td>";
        $message .= "</tr>";
    }
    $message .= "</table>";
    
    $message .= "<p>You can track your order at: <a href='http://localhost/Trial%20WP/track_order.php?id=" . $orderDetails['order_id'] . "'>Track Order</a></p>";
    
    $message .= "<p>Thank you for shopping with NeoTech Forge!</p>";
    $message .= "</body></html>";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: NeoTech Forge <support@neotechforge.com>' . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}