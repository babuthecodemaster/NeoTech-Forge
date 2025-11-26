<?php
/**
 * Paytm Payment Gateway Configuration Template
 * 
 * Instructions:
 * 1. Copy this file and rename it to 'paytm_config.php'
 * 2. Sign up for a Paytm merchant account at https://business.paytm.com/
 * 3. Get your credentials from the Paytm dashboard
 * 4. Update the values below with your actual Paytm credentials
 * 5. Never commit paytm_config.php to version control
 * 
 * For Testing:
 * - Use PAYTM_TEST_URL for staging/testing environment
 * - Use test credentials provided by Paytm
 * 
 * For Production:
 * - Use PAYTM_PROD_URL for live transactions
 * - Use your production credentials
 */

// Merchant Key - Get this from Paytm Dashboard
define('PAYTM_MERCHANT_KEY', 'your_merchant_key_here');

// Merchant ID - Get this from Paytm Dashboard
define('PAYTM_MERCHANT_MID', 'your_merchant_id_here');

// Website Name - Get this from Paytm Dashboard (e.g., WEBSTAGING for test, your website name for production)
define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING');

// Channel ID - Usually 'WEB' for web applications
define('PAYTM_CHANNEL_ID', 'WEB');

// Industry Type - Get this from Paytm Dashboard (e.g., Retail, Entertainment, etc.)
define('PAYTM_INDUSTRY_TYPE_ID', 'Retail');

// Callback URL - Update with your actual domain and path
// This is where Paytm will redirect after payment
define('PAYTM_CALLBACK_URL', 'http://yourdomain.com/payment_callback.php');

// Paytm URLs
define('PAYTM_TEST_URL', 'https://securegw-stage.paytm.in/order/process'); // For testing
define('PAYTM_PROD_URL', 'https://securegw.paytm.in/order/process'); // For production

// Note: Make sure to update PAYTM_CALLBACK_URL with your actual domain before going live
?>
