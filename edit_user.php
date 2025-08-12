<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/User.php';
$db = new DB();
$user = new User($db);
$id = $_GET['id'] ?? '';
$message = '';
// Получаем список пользователей
$users = $db->conn->query('SELECT id, name FROM users');
?>
<h2 style="text-align: center;">Редактировать пользователя</h2>
<form method="get">
    <select name="id" onchange="this.form.submit()">
        <option value="">Выберите пользователя</option>
        <?php while ($row = $users->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($id == $row['id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
        <?php endwhile; ?>
    </select>
</form>
<?php
if ($id) {
    $userData = $db->conn->query("SELECT * FROM users WHERE id = " . intval($id))->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        if ($user->edit($id, $name, $email)) {
            $message = 'Данные пользователя обновлены!';
        } else {
            $message = 'Ошибка обновления.';
        }
    }
?>
<form method="post">
    <input type="text" name="name" value="<?php echo $userData['name'] ?? ''; ?>" placeholder="Имя" required>
    <input type="email" name="email" value="<?php echo $userData['email'] ?? ''; ?>" placeholder="Email" required>
    <button type="submit">Сохранить</button>
</form>
<?php } ?>
<p><?php echo $message; ?></p>
