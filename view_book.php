<?php
include 'config/database.php';
include 'templates/header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$book_id = $_GET['id'];

$sql = "SELECT * FROM books WHERE id='$book_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "Invalid book ID.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $user_id = $_SESSION['user_id'];

    $sql_review = "INSERT INTO reviews (book_id, user_id, review, rating) VALUES ('$book_id', '$user_id', '$review', '$rating')";
    if ($conn->query($sql_review) === TRUE) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $sql_review . "<br>" . $conn->error;
    }
}

// 책 정보 출력
$title = htmlspecialchars($book['title']);
$author = htmlspecialchars($book['author']);
$status = isset($book['status']) ? htmlspecialchars($book['status']) : '';
?>

<main>
    <div class="container">
        <h2><?php echo $title; ?></h2>
        <p><strong>Author:</strong> <?php echo $author; ?></p>
        <a href="edit_book.php?id=<?php echo $book_id; ?>">Edit</a>

        <h3>Write a Review</h3>
        <form method="post" action="">
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="5"></textarea>
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star10" name="rating" value="10"><label for="star10">★</label>
                <input type="radio" id="star9" name="rating" value="9"><label for="star9">★</label>
                <input type="radio" id="star8" name="rating" value="8"><label for="star8">★</label>
                <input type="radio" id="star7" name="rating" value="7"><label for="star7">★</label>
                <input type="radio" id="star6" name="rating" value="6"><label for="star6">★</label>
                <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
            </div>
            <button type="submit">Submit Review</button>
        </form>

        <h3>Reviews</h3>
        <?php
        $sql_reviews = "SELECT reviews.review, reviews.rating, reviews.created_at, users.username FROM reviews JOIN users ON reviews.user_id = users.id WHERE reviews.book_id='$book_id'";
        $result_reviews = $conn->query($sql_reviews);

        if ($result_reviews->num_rows > 0) {
            while ($review = $result_reviews->fetch_assoc()) {
                $username = htmlspecialchars($review['username']);
                $review_text = htmlspecialchars($review['review']);
                $rating = htmlspecialchars($review['rating']);
                $created_at = htmlspecialchars($review['created_at']);
                
                echo "<div class='review'>";
                echo "<p><strong>$username</strong> ($created_at):</p>";
                echo "<p>Rating: $rating/10</p>";
                echo "<p>$review_text</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
