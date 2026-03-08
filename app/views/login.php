<?php
// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Вы уже вошли!";
    header("Location: " . $basePath . "/index.php?page=profile");
    exit();
}

$formData = $_SESSION['form_data'] ?? [];

$config = require __DIR__ . '/../config/config.php';
$siteKey = $config['recaptcha']['site_key'];
?>

<!-- Подключаем скрипт reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="container-fluid">
    <div class="col-md-4 offset-md-4">
        <div class="form-container">
            <div class="form-icon"><i class="fa fa-user"></i></div>
            <h3 class="title">Войти</h3>
            <form class="form-horizontal" method="POST" action="<?php echo $basePath; ?>/public/action.php?controller=user&action=login">
                <div class="form-group">
                    <input class="form-control" type="text" name="login"
                        placeholder="Email или телефон"
                        value="<?php echo htmlspecialchars($formData['login'] ?? ''); ?>"
                        required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password"
                        placeholder="Пароль"
                        required>
                </div>
                <div class="form-group">
                    <div class="g-recaptcha"
                        data-sitekey="<?php echo $siteKey; ?>">
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Войти</button>
                </div>
            </form>
            <p style="text-align: center;">
                <a class="nav-link" href="?page=registration" style="text-decoration: underline;">Нет аккаунта? Зарегистрироваться</a>
            </p>
        </div>
    </div>
</div>

<?php
unset($_SESSION['form_data']);
?>