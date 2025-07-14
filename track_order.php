<?php
session_start();
require_once("db_connect.php");

$orderId = isset($_GET['id']) ? $_GET['id'] : '';
$orderDetails = null;
$orderItems = [];

if ($orderId) {
    // Get order details
    $sql = "SELECT o.*, u.email FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderDetails = $result->fetch_assoc();

    // Get order items
    if ($orderDetails) {
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $orderItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order | NeoTech Forge</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .order-tracking {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--surface);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .order-status {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0;
            position: relative;
        }
        .status-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .status-step i {
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            background: var(--surface-light);
            margin-bottom: 0.5rem;
        }
        .status-step.active i {
            background: var(--primary);
            color: white;
        }
        .status-line {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
            z-index: 0;
        }
        .order-items {
            margin: 2rem 0;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 1rem;
        }
        .download-invoice {
            margin-top: 2rem;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Include your header here -->
    
    <main class="container">
        <div class="order-tracking">
            <?php if ($orderDetails): ?>
                <h1>Order #<?php echo htmlspecialchars($orderId); ?></h1>
                
                <div class="order-status">
                    <div class="status-line"></div>
                    <?php
                    $steps = [
                        ['icon' => 'fa-check', 'label' => 'Order Placed'],
                        ['icon' => 'fa-box', 'label' => 'Processing'],
                        ['icon' => 'fa-truck', 'label' => 'Shipped'],
                        ['icon' => 'fa-home', 'label' => 'Delivered']
                    ];
                    $currentStep = 0;
                    if ($orderDetails['status'] === 'TXN_SUCCESS') $currentStep = 1;
                    if ($orderDetails['status'] === 'SHIPPED') $currentStep = 2;
                    if ($orderDetails['status'] === 'DELIVERED') $currentStep = 3;

                    foreach ($steps as $index => $step):
                    ?>
                    <div class="status-step <?php echo $index <= $currentStep ? 'active' : ''; ?>">
                        <i class="fas <?php echo $step['icon']; ?>"></i>
                        <div><?php echo $step['label']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-details">
                    <h2>Order Details</h2>
                    <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($orderDetails['created_at'])); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($orderDetails['status']); ?></p>
                    <p><strong>Total Amount:</strong> ₹<?php echo number_format($orderDetails['amount'], 2); ?></p>
                </div>

                <div class="order-items">
                    <h2>Items</h2>
                    <?php foreach ($orderItems as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo htmlspecialchars($item['type'] === 'component' ? $item['product_name'] . '.png' : 'placeholder.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                            <p>Price: ₹<?php echo number_format($item['price'] * 82.5, 2); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="download-invoice">
                    <a href="generate_invoice.php?id=<?php echo htmlspecialchars($orderId); ?>" class="btn-primary">
                        <i class="fas fa-download"></i> Download Invoice
                    </a>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <h2>Order Not Found</h2>
                    <p>The order you're looking for doesn't exist or you don't have permission to view it.</p>
                    <a href="index.html" class="btn-primary">Return to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Include your footer here -->
</body>
</html>