<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/Book.php';
$db = new DB();
$bookObj = new Book($db);
$books = $db->conn->query("SELECT * FROM books")->fetch_all(MYSQLI_ASSOC);
$selectedBook = null;
if (isset($_POST['book_id'])) {
    $stmt = $db->conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $_POST['book_id']);
    $stmt->execute();
    $selectedBook = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
if (isset($_POST['edit_book'])) {
    $id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $result = $bookObj->edit($id, $title, $author, $is_available);
    $message = $result ? "Книга успешно обновлена!" : "Ошибка обновления книги.";
}
?>
<h2 style="text-align: center;">Редактировать книгу</h2>
<form method="post">
    <label>Выберите книгу:</label>
    <select name="book_id" onchange="this.form.submit()">
        <option value="">--Выберите--</option>
        <?php foreach ($books as $book): ?>
            <option value="<?php echo $book['id']; ?>" <?php if ($selectedBook && $selectedBook['id'] == $book['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($book['title']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
<?php if ($selectedBook): ?>
<form method="post">
    <input type="hidden" name="book_id" value="<?php echo $selectedBook['id']; ?>">
    <label>Название:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($selectedBook['title']); ?>"><br>
    <label>Автор:</label>
    <input type="text" name="author" value="<?php echo htmlspecialchars($selectedBook['author']); ?>"><br>
    <label>Доступна:</label>
    <input type="checkbox" name="is_available" <?php if ($selectedBook['is_available']) echo 'checked'; ?>><br>
    <button type="submit" name="edit_book">Сохранить изменения</button>
</form>
<?php endif; ?>
<?php if (isset($message)) echo "<p>$message</p>"; ?>