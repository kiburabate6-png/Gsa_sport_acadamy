<?php
// Get data from the URL
$tx_ref = isset($_GET['tx_ref']) ? $_GET['tx_ref'] : 'N/A';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '500';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSA Academy | Secure Checkout</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .checkout-card { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; }
        .header { margin-bottom: 20px; }
        .amount-display { font-size: 32px; font-weight: bold; color: #1e3799; margin: 10px 0; }
        .ref-text { font-size: 12px; color: #777; margin-bottom: 20px; }
        
        .method-btn {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; padding: 15px; margin-bottom: 12px; border: 2px solid #eee;
            border-radius: 10px; cursor: pointer; transition: 0.3s; background: white;
            font-weight: bold; font-size: 16px;
        }
        .method-btn:hover { border-color: #27ae60; background: #f9fff9; }
        
        /* Specific Colors */
        .telebirr { color: #e91e63; } /* Telebirr Pink */
        .cbe { color: #1e3799; }      /* CBE Blue */
        
        /* Loading Overlay */
        #loader { display: none; margin-top: 20px; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #27ae60; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div class="checkout-card" id="mainCard">
        <div class="header">
            <img src="https://chapa.co/favicon.ico" width="40" alt="GSA">
            <h3>Secure Payment</h3>
            <p style="color: #666; font-size: 14px;">Select your preferred method to pay</p>
        </div>

        <div class="amount-display"><?php echo $amount; ?> ETB</div>
        <div class="ref-text">Reference: <?php echo $tx_ref; ?></div>

        <!-- ACTIVE BUTTONS -->
        <button class="method-btn telebirr" onclick="processPayment('Telebirr')">
            <span>Pay with Telebirr</span>
            <span>➔</span>
        </button>

        <button class="method-btn cbe" onclick="processPayment('CBE Birr')">
            <span>Pay with CBE Birr</span>
            <span>➔</span>
        </button>

        <div id="loader">
            <div class="spinner"></div>
            <p id="loadingText">Processing with Telebirr...</p>
        </div>

        <p style="font-size: 11px; color: #aaa; margin-top: 20px;">Secure SSL Encrypted Connection</p>
    </div>

    <script>
        function processPayment(method) {
            // Disable buttons
            document.querySelectorAll('.method-btn').forEach(btn => btn.style.display = 'none');
            
            // Show loader
            document.getElementById('loader').style.display = 'block';
            document.getElementById('loadingText').innerText = "Connecting to " + method + "...";

            // Wait 2 seconds (simulate bank processing) then redirect to verify
            setTimeout(function() {
                window.location.href = "verify.php?tx_ref=<?php echo $tx_ref; ?>&method=" + method;
            }, 2500);
        }
    </script>

</body>
</html>