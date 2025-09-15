<?php include("config.php"); ?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>गैलरी</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-dark text-light">

<?php include("partials/header.php"); ?>

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-image text-info"></i> गैलरी</h2>
        <hr class="border-info">

        <div class="row">
            <?php
            $gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
            if($gallery->num_rows > 0){
                while($row = $gallery->fetch_assoc()){
                    echo "
                    <div class='col-md-3 mb-4'>
                        <div class='card h-100 bg-dark text-light'>
                            <img src='uploads/{$row['image']}' class='card-img-top rounded' alt='{$row['title']}'>
                            <div class='card-body'>
                                <h6 class='card-title'>{$row['title']}</h6>
                                <p class='small text-muted'>{$row['category']}</p>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p>गैलरी में अभी कोई चित्र उपलब्ध नहीं है।</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include("partials/footer.php"); ?>
</body>
</html>
