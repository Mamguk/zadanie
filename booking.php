<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/Booking.php';
$db = new DB();
$booking = new Booking($db);
$message = '';
// Получаем список пользователей
$users = $db->conn->query('SELECT id, name FROM users');
// Получаем список доступных книг
$books = $db->conn->query('SELECT id, title FROM books WHERE is_available = 1');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $book_id = $_POST['book_id'] ?? '';
    if ($user_id && $book_id) {
        if ($booking->reserve($user_id, $book_id)) {
            $message = 'Книга успешно забронирована!';
        } else {
            $message = 'Ошибка при бронировании.';
        }
    } else {
        $message = 'Выберите пользователя и книгу.';
    }
}
?>
<h2 style="text-align: center;">Бронирование</h2>
<form method="post">
    <select name="user_id">
        <option value="">Выберите пользователя</option>
        <?php while ($row = $users->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <select name="book_id">
        <option value="">Выберите книгу</option>
        <?php while ($row = $books->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Забронировать</button>
</form>
<p><?php echo $message; ?></p>
