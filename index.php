<?php
include 'config/database.php';
include 'templates/header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM books WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<main>
    <div class="container">
        <h2>Welcome to Your Virtual Library</h2>
        <p>This is a place where you can manage your books, reviews, and recommendations.</p>
        <a href="add_book.php" class="button">Add a New Book</a>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
