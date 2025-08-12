<link rel="stylesheet" href="assets/style.css">
<div class="menu">
<a href="index.php">На главную</a>
</div>
<?php
require_once 'classes/DB.php';
require_once 'classes/User.php';
$db = new DB();
$user = new User($db);
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    if ($name && $email) {
        if ($user->add($name, $email)) {
            $message = 'Пользователь добавлен!';
        } else {
            $message = 'Ошибка добавления.';
        }
    } else {
        $message = 'Заполните все поля.';
    }
}
?>
<h2 style="text-align: center;">Добавить пользователя</h2>
<form method="post">
    <input type="text" name="name" placeholder="ФИО">
    <input type="email" name="email" placeholder="Email">
    <button type="submit">Добавить</button>
</form>
<p><?php echo $message; ?></p>
