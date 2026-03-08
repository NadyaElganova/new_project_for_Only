<?php
// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Вы уже вошли!";
    header("Location: " . $basePath . "/index.php?page=profile");
    exit();
}
$formData = $_SESSION['form_data'] ?? [];
?>

<div class="container-fluid">
    <div class="col-md-4 offset-md-4">
        <div class="form-container">
            <div class="form-icon"><i class="fa fa-user"></i></div>
            <h3 class="title">Регистрация</h3>
            <form class="form-horizontal" method="POST" action="<?php echo $basePath; ?>/public/action.php?controller=user&action=register">
                <div class="form-group">
                    <input class="form-control" type="text" name="user_name"
                        placeholder="Имя"
                        value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>"
                        required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" name="email"
                        placeholder="Адрес эл. почты"
                        value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                        required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="tel" name="telephone"
                        placeholder="Телефон"
                        value="<?php echo htmlspecialchars($formData['telephone'] ?? ''); ?>"
                        required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password"
                        placeholder="Пароль"
                        required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="repeat_password"
                        placeholder="Повторите пароль"
                        required>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </div>

            </form>
            <p style="text-align: center;">
                <a class="nav-link" href="?page=login" style="text-decoration: underline;">Есть аккаунт? Войти</a>
            </p>
        </div>
    </div>
</div>

<?php
//очищаем форму после отображения
unset($_SESSION['form_data']);
?>