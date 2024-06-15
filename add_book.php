<?php
include 'config/database.php';
include 'templates/header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $review = $conn->real_escape_string($_POST['review']);
    $rating = (int) $_POST['rating'];

    // 동일한 책이 있는지 확인
    $sql_check = "SELECT id FROM books WHERE title='$title' AND author='$author'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // 책이 이미 존재하는 경우 리뷰 추가
        $book = $result_check->fetch_assoc();
        $book_id = $book['id'];

        $sql_review = "INSERT INTO reviews (book_id, user_id, review, rating, created_at, updated_at) 
                       VALUES ('$book_id', '$user_id', '$review', '$rating', NOW(), NOW())";
        if ($conn->query($sql_review) === TRUE) {
            echo "Review added successfully";
        } else {
            echo "Error: " . $sql_review . "<br>" . $conn->error;
        }
    } else {
        // 책이 존재하지 않는 경우 책과 리뷰 추가
        $sql = "INSERT INTO books (user_id, title, author, created_at, updated_at) 
                VALUES ('$user_id', '$title', '$author', NOW(), NOW())";
        if ($conn->query($sql) === TRUE) {
            $book_id = $conn->insert_id;
            $sql_review = "INSERT INTO reviews (book_id, user_id, review, rating, created_at, updated_at) 
                           VALUES ('$book_id', '$user_id', '$review', '$rating', NOW(), NOW())";
            if ($conn->query($sql_review) === TRUE) {
                echo "New book and review added successfully";
            } else {
                echo "Error: " . $sql_review . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<main>
    <div class="container">
        <h2>Add a New Book</h2>
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
            <label for="review">Review:</label>
            <textarea id="review" name="review" required></textarea>
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star10" name="rating" value="10"><label for="star10" title="10 stars">★</label>
                <input type="radio" id="star9" name="rating" value="9"><label for="star9" title="9 stars">★</label>
                <input type="radio" id="star8" name="rating" value="8"><label for="star8" title="8 stars">★</label>
                <input type="radio" id="star7" name="rating" value="7"><label for="star7" title="7 stars">★</label>
                <input type="radio" id="star6" name="rating" value="6"><label for="star6" title="6 stars">★</label>
                <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">★</label>
            </div>
            <button type="submit">Add Book</button>
        </form>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
