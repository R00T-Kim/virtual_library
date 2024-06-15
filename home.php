<?php
include 'config/database.php';
include 'templates/header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$limit = 10; // 한 페이지에 표시할 책의 수
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM books LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// 전체 책의 수를 구합니다.
$total_sql = "SELECT COUNT(*) FROM books";
$total_result = $conn->query($total_sql);
$total_books = $total_result->fetch_row()[0];
$total_pages = ceil($total_books / $limit);
?>

<main>
    <div class="container">
        <h2>Welcome to Your Virtual Library</h2>
        <div style="text-align: center;">
            <a href="add_book.php" class="button">Add a new book</a>
        </div>
        <ul style="list-style-type: none; padding: 0; text-align: center;">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li style='margin-bottom: 10px;'><a href='view_book.php?id=" . $row["id"] . "'>" . $row["title"] . "</a> by " . $row["author"] . "</li>";
                }
            } else {
                echo "<p>No books found.</p>";
            }
            ?>
        </ul>
        <!-- 페이지네이션 링크 -->
        <div class="pagination" style="text-align: center;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="button"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</main>

<?php include 'templates/footer.php'; ?>
