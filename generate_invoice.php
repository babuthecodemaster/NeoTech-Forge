<?php
session_start();
require_once("db_connect.php");

if (!isset($_GET['id'])) {
    die('Order ID not provided');
}

$orderId = $_GET['id'];

// Get order details
$sql = "SELECT o.*, u.email, u.name FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$orderDetails = $stmt->get_result()->fetch_assoc();

// Get order items
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo htmlspecialchars($orderId); ?> - NeoTech Forge</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @media print {
            body {
                padding: 20px;
                background: white;
            }
            .no-print {
                display: none;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
        }
        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .company-details {
            flex: 1;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .invoice-table th, .invoice-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .invoice-table th {
            background: #f5f5f5;
        }
        .total-row {
            font-weight: bold;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .btn-print {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-details">
                <h1>NeoTech Forge</h1>
                <p>Pillai College of Engineering and Technology</p>
                <p>Navi Mumbai</p>
                <p>Phone: +91 9028611069</p>
                <p>Email: support@neotechforge.com</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>Invoice #:</strong> <?php echo htmlspecialchars($orderId); ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($orderDetails['created_at'])); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($orderDetails['payment_mode']); ?></p>
            </div>
        </div>

        <div class="customer-details">
            <h3>Bill To:</h3>
            <p><?php echo htmlspecialchars($orderDetails['name']); ?></p>
            <p><?php echo htmlspecialchars($orderDetails['email']); ?></p>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($orderItems as $item):
                    $itemTotal = $item['price'] * $item['quantity'] * 82.5;
                    $total += $itemTotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>₹<?php echo number_format($item['price'] * 82.5, 2); ?></td>
                    <td>₹<?php echo number_format($itemTotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td>₹<?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer">
            <p>Thank you for shopping with NeoTech Forge!</p>
            <p>If you have any questions about this invoice, please contact our customer support.</p>
        </div>

        <div class="action-buttons no-print">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <button onclick="window.close()" class="btn-print">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <script>
        // Auto-print if opened in a new window
        if (window.opener) {
            window.print();
        }
    </script>
</body>
</html>