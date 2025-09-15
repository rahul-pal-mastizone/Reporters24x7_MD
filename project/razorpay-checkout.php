<?php
include("config.php");
$id = intval($_GET['id']);
$contribution = $conn->query("SELECT * FROM contributions WHERE id=$id")->fetch_assoc();

// Razorpay API keys (replace with client’s keys)
$key_id = "rzp_test_xxxxx";
$key_secret = "xxxxx";
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Processing Payment...</h2>
    <script>
        var options = {
            "key": "<?=$key_id?>",
            "amount": "<?=$contribution['amount']*100?>", 
            "currency": "INR",
            "name": "अखिल भारतीय शासक संघ",
            "description": "Contribution Payment",
            "handler": function (response){
                window.location.href = "payment-success.php?id=<?=$id?>&payid=" + response.razorpay_payment_id;
            },
            "prefill": {
                "name": "<?=$contribution['name']?>",
                "email": "<?=$contribution['email']?>",
                "contact": "<?=$contribution['phone']?>"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    </script>
</body>
</html>
