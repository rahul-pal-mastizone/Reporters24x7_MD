<?php
include("config.php");

$msg = "";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name,$email,$subject,$message);
    if($stmt->execute()){
        $msg = "✅ आपका संदेश सफलतापूर्वक भेजा गया है!";
    }
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>संपर्क करें</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-envelope text-info"></i> संपर्क करें</h2>
        <hr class="border-info">

        <?php if($msg) echo "<p class='alert alert-success'>$msg</p>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">नाम</label>
                <input type="text" class="form-control" name="name" placeholder="आपका नाम" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ईमेल</label>
                <input type="email" class="form-control" name="email" placeholder="आपका ईमेल" required>
            </div>
            <div class="mb-3">
                <label class="form-label">विषय</label>
                <input type="text" class="form-control" name="subject" placeholder="संदेश का विषय" required>
            </div>
            <div class="mb-3">
                <label class="form-label">संदेश</label>
                <textarea class="form-control" name="message" rows="4" placeholder="अपना संदेश यहाँ लिखें..." required></textarea>
            </div>
            <button class="btn btn-info w-100"><i class="fa fa-paper-plane"></i> संदेश भेजें</button>
        </form>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
