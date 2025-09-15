<?php
include("config.php");
$msg = "";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO contributions (name,email,phone,amount,payment_status) VALUES (?,?,?,?,?)");
    $status = "pending"; 
    $stmt->bind_param("ssssd", $name,$email,$phone,$amount,$status);
    if($stmt->execute()){
        $msg = "✅ धन्यवाद! आपका सहयोग दर्ज हो गया है।";
    }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>सहयोग करें</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-hand-holding-dollar text-success"></i> सहयोग करें</h2>
        <hr class="border-info">

        <?php if($msg) echo "<p class='alert alert-success'>$msg</p>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">नाम</label>
                <input class="form-control" type="text" name="name" placeholder="नाम" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ईमेल</label>
                <input class="form-control" type="email" name="email" placeholder="ईमेल" required>
            </div>
            <div class="mb-3">
                <label class="form-label">फ़ोन</label>
                <input class="form-control" type="text" name="phone" placeholder="फ़ोन">
            </div>
            <div class="mb-3">
                <label class="form-label">राशि (₹)</label>
                <input class="form-control" type="number" name="amount" placeholder="₹ सहयोग राशि" required>
            </div>
            <button class="btn btn-success w-100"><i class="fa fa-donate"></i> सहयोग करें</button>
        </form>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
