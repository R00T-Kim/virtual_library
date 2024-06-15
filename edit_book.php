<?php
session_start();
include 'config/database.php';
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['id'];

// 책 정보 가져오기
$sql = "SELECT * FROM books WHERE id='$book_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();

    // 책을 등록한 사용자인지 확인
    if ($book['user_id'] != $user_id) {
        echo "You are not authorized to edit this book.";
        exit();
    }
} else {
    echo "Invalid book ID.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    $sql_update = "UPDATE books SET title='$title', author='$author' WHERE id='$book_id'";
    if ($conn->query($sql_update) === TRUE) {
        $sql_review_update = "UPDATE reviews SET review='$review', rating='$rating' WHERE book_id='$book_id' AND user_id='$user_id'";
        if ($conn->query($sql_review_update) === TRUE) {
            echo "Book updated successfully!";
        } else {
            echo "Error: " . $sql_review_update . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

// 책 정보 출력
$title = isset($book['title']) ? $book['title'] : '';
$author = isset($book['author']) ? $book['author'] : '';

// 리뷰와 평점 가져오기
$sql_review = "SELECT * FROM reviews WHERE book_id='$book_id' AND user_id='$user_id'";
$result_review = $conn->query($sql_review);
if ($result_review->num_rows > 0) {
    $review_data = $result_review->fetch_assoc();
    $review = isset($review_data['review']) ? $review_data['review'] : '';
    $rating = isset($review_data['rating']) ? $review_data['rating'] : '';
} else {
    $review = '';
    $rating = '';
}
?>

<main>
    <div class="container">
        <h2>Edit Book</h2>
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>">
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="5"><?php echo htmlspecialchars($review); ?></textarea>
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star10" name="rating" value="10" <?php if ($rating == 10) echo 'checked'; ?>><label for="star10">★</label>
                <input type="radio" id="star9" name="rating" value="9" <?php if ($rating == 9) echo 'checked'; ?>><label for="star9">★</label>
                <input type="radio" id="star8" name="rating" value="8" <?php if ($rating == 8) echo 'checked'; ?>><label for="star8">★</label>
                <input type="radio" id="star7" name="rating" value="7" <?php if ($rating == 7) echo 'checked'; ?>><label for="star7">★</label>
                <input type="radio" id="star6" name="rating" value="6" <?php if ($rating == 6) echo 'checked'; ?>><label for="star6">★</label>
                <input type="radio" id="star5" name="rating" value="5" <?php if ($rating == 5) echo 'checked'; ?>><label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4" <?php if ($rating == 4) echo 'checked'; ?>><label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3" <?php if ($rating == 3) echo 'checked'; ?>><label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2" <?php if ($rating == 2) echo 'checked'; ?>><label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1" <?php if ($rating == 1) echo 'checked'; ?>><label for="star1">★</label>
            </div>
            <button type="submit">Save</button>
        </form>
    </div>
</main>

<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .star-rating {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        padding: 0 5px;
    }

    .star-rating input[type="radio"]:checked ~ label {
        color: #f5b301;
    }

    button[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

<?php include 'templates/footer.php'; ?>
