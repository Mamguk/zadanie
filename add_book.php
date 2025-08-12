<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/Book.php';
$db = new DB();
$book = new Book($db);
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    if ($title && $author) {
        if ($book->add($title, $author)) {
            $message = 'Книга успешно добавлена!';
        } else {
            $message = 'Ошибка при добавлении.';
        }
    } else {
        $message = 'Заполните все поля.';
    }
}
?>
<h2 style="text-align: center;">Добавить книгу</h2>
<form method="post">
    <input type="text" name="title" placeholder="Название книги">
    <input type="text" name="author" placeholder="Автор">
    <button type="submit">Добавить книгу</button>
</form>
<p><?php echo $message; ?></p>

