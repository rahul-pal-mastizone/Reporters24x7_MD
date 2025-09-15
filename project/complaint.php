<?php
include("config.php");
$msg = "";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO complaints (name,email,phone,message) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name,$email,$phone,$message);
    if($stmt->execute()){
        $msg = "✅ आपकी शिकायत सफलतापूर्वक दर्ज कर ली गई है!";
    }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>शिकायत करें</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-pen-to-square text-danger"></i> शिकायत दर्ज करें</h2>
        <hr class="border-info">

        <?php if($msg) echo "<p class='alert alert-success'>$msg</p>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">नाम</label>
                <input class="form-control" type="text" name="name" placeholder="आपका नाम" required>
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
                <label class="form-label">शिकायत</label>
                <textarea class="form-control" name="message" rows="4" placeholder="अपनी शिकायत यहाँ लिखें..." required></textarea>
            </div>
            <button class="btn btn-danger w-100"><i class="fa fa-paper-plane"></i> शिकायत दर्ज करें</button>
        </form>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
