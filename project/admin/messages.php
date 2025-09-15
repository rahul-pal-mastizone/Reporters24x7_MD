<?php
require_once("includes/auth.php");   // FIRST! No output before this.
require_once("../config.php");
include("includes/header.php");
include("includes/topbar.php");      // ok to output now
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../assets/style.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->
</head>
<body class="bg-dark text-light">

<div class="container mt-4">
    <div class="card bg-secondary p-4">
        <h2><i class="fa fa-envelope text-info"></i> Contact Messages</h2>
        <hr class="border-info">

        <?php
        // Delete message if requested
        if(isset($_GET['delete'])){
            $id = intval($_GET['delete']);
            $conn->query("DELETE FROM contact_messages WHERE id=$id");
            echo "<p class='alert alert-success'>✅ संदेश डिलीट कर दिया गया है।</p>";
        }

        $messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        if($messages->num_rows > 0){
            echo "<table class='table table-dark table-striped'>";
            echo "<thead><tr>
                    <th>ID</th>
                    <th>नाम</th>
                    <th>ईमेल</th>
                    <th>विषय</th>
                    <th>संदेश</th>
                    <th>तारीख</th>
                    <th>Action</th>
                  </tr></thead><tbody>";
            while($row = $messages->fetch_assoc()){
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['subject']}</td>
                        <td>".mb_substr($row['message'],0,50)."...</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a href='view_message.php?id={$row['id']}' class='btn btn-sm btn-info'><i class='fa fa-eye'></i></a>
                            <a href='messages.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"क्या आप सच में डिलीट करना चाहते हैं?\")'><i class='fa fa-trash'></i></a>
                        </td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>❌ अभी कोई संदेश उपलब्ध नहीं है।</p>";
        }
        ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
</body>
</html>
