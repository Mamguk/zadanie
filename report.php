<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/Booking.php';
$db = new DB();
$booking = new Booking($db);
// Получаем список пользователей
$users = $db->conn->query('SELECT id, name FROM users');
// Получаем список книг
$books = $db->conn->query('SELECT id, title FROM books');
$user_id = $_GET['user_id'] ?? '';
$book_id = $_GET['book_id'] ?? '';
$status = $_GET['status'] ?? '';
$where = [];
if ($status) {
    // Преобразуем русский статус в английский для SQL
    if ($status == 'Забронировано') $status_db = 'reserved';
    elseif ($status == 'Отменено') $status_db = 'canceled';
    else $status_db = $status;
    $where[] = "booking.status = '" . $db->conn->real_escape_string($status_db) . "'";
}
if ($user_id) {
    $where[] = "booking.user_id = '" . intval($user_id) . "'";
}
if ($book_id) {
    $where[] = "booking.book_id = '" . intval($book_id) . "'";
}
$sql = "SELECT booking.*, users.name AS user_name, books.title AS book_title FROM booking
        JOIN users ON booking.user_id = users.id
        JOIN books ON booking.book_id = books.id";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY booking_date DESC";
$result = $db->conn->query($sql);
$bookings = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<h2 style="text-align: center;">Отчет</h2>
<form method="get">
    <select name="user_id">
        <option value="">Все пользователи</option>
        <?php while ($row = $users->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($user_id == $row['id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <select name="book_id">
        <option value="">Все книги</option>
        <?php while ($row = $books->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($book_id == $row['id']) echo 'selected'; ?>><?php echo $row['title']; ?></option>
        <?php endwhile; ?>
    </select>
    <select name="status">
        <option value="">Любой статус</option>
        <option value="reserved" <?php if ($status == 'reserved') echo 'selected'; ?>>Забронировано</option>
        <option value="canceled" <?php if ($status == 'canceled') echo 'selected'; ?>>Отменено</option>
    </select>
    <button type="submit">Фильтровать</button>
</form>
<table border="1">
    <tr>
        <th>Пользователь</th>
        <th>Книга</th>
        <th>Статус</th>
        <th>Дата бронирования</th>
        <th>Снять бронирование</th>
    </tr>
    <?php
    if (!isset($bookings) || !is_array($bookings)) {
        $bookings = [];
    }
    foreach ($bookings as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
            <td><?php echo $row['status'] == 'reserved' ? 'Забронировано' : 'Отменено'; ?></td>
            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
            <td>
                <?php if ($row['status'] == 'reserved'): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="cancel_booking_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Снять бронь</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
if (isset($_POST['cancel_booking_id'])) {
    $booking_id = intval($_POST['cancel_booking_id']);
    // Отменяем бронь
    $db->conn->query("UPDATE booking SET status = 'canceled' WHERE id = $booking_id");
    // Делаем книгу снова доступной
    $booking_row = $db->conn->query("SELECT book_id FROM booking WHERE id = $booking_id")->fetch_assoc();
    if ($booking_row) {
        $db->conn->query("UPDATE books SET is_available = 1 WHERE id = " . intval($booking_row['book_id']));
    }
    header('Location: report.php');
    exit;
}
?>
