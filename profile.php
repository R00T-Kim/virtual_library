<?php
include 'config/database.php';
include 'templates/header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$limit = 10;  // 페이지당 표시할 리뷰 수

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql_update = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";
    if ($conn->query($sql_update) === TRUE) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

// 사용자 정보 가져오기
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// 리뷰 가져오기
$sql_reviews = "SELECT books.id as book_id, books.title, reviews.review, reviews.rating 
                FROM reviews 
                JOIN books ON reviews.book_id = books.id 
                WHERE reviews.user_id='$user_id' 
                LIMIT $start, $limit";
$result_reviews = $conn->query($sql_reviews);

?>

<main>
    <div class="container">
        <h2>Profile</h2>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <button type="submit">Update Profile</button>
        </form>

        <h3>My Reviews</h3>
        <?php
        if ($result_reviews->num_rows > 0) {
            while ($review = $result_reviews->fetch_assoc()) {
                $book_id = htmlspecialchars($review['book_id']);
                $title = htmlspecialchars($review['title']);
                $review_text = htmlspecialchars($review['review']);
                $rating = htmlspecialchars($review['rating']);

                echo "<p><a href='view_book.php?id=$book_id'>$title</a> - $review_text (Rating: $rating/10)</p>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }

        // 페이지네이션 링크 생성
        $total_sql = "SELECT COUNT(*) FROM reviews WHERE user_id='$user_id'";
        $total_result = $conn->query($total_sql);
        $total = $total_result->fetch_row()[0];
        $pages = ceil($total / $limit);

        if ($pages > 1) {
            echo '<nav class="pagination">';
            for ($i = 1; $i <= $pages; $i++) {
                $active = ($i == $page) ? 'button active' : 'button';
                echo '<a class="' . $active . '" href="profile.php?page=' . $i . '">' . $i . '</a>';
            }
            echo '</nav>';
        }
        ?>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
