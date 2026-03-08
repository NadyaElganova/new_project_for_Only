<?php
require_once __DIR__ . '/../models/User.php';

require_once __DIR__ . '/../../composer/vendor/autoload.php';

use ReCaptcha\ReCaptcha;

class UserController
{
    private $userModel;
    private $recaptchaSecret;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);

        $config = require __DIR__ . '/../config/config.php';
        $this->recaptchaSecret = $config['recaptcha']['secret_key'];
    }

    //Регистрация
    public function register($user_name, $email, $telephone, $password, $repeat_password)
    {
        // Валидация
        $errors = [];
        if (empty($user_name)) {
            $errors[] = "Имя обязательно";
        }
        if (empty($email)) {
            $errors[] = "Email обязателен";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Неверный формат email. Пример: example@mail.ru";
        }
        if (empty($telephone)) {
            $errors[] = "Телефон обязателен";
        } else {
            $phoneValidation = $this->validatePhone($telephone);
            if ($phoneValidation !== true) {
                $errors[] = $phoneValidation;
            }
        }
        if (empty($password)) {
            $errors[] = "Пароль обязателен";
        } elseif (strlen($password) < 6) {
            $errors[] = "Пароль должен быть не менее 6 символов";
        }
        if ($password !== $repeat_password) {
            $errors[] = "Пароли не совпадают";
        }

        // Если есть ошибки валидации
        if (!empty($errors)) {
            $errorMessage = implode("<br>", $errors);
            $this->redirectWithError($errorMessage, [
                'user_name' => $user_name,
                'email' => $email,
                'telephone' => $telephone
            ], 'registration');
        }

        // Форматируем телефон для БД, оставляем только цифры
        $formattedPhone = $this->formatPhoneForDb($telephone);

        // Проверка уникальности
        $uniqueErrors = [];
        if ($this->userModel->getUserByEmail($email)) {
            $uniqueErrors[] = "Пользователь с таким email уже существует";
        }
        if ($this->userModel->getUserByUsername($user_name)) {
            $uniqueErrors[] = "Пользователь с таким именем уже существует";
        }
        if ($this->userModel->getUserByTelephone($formattedPhone)) {
            $uniqueErrors[] = "Пользователь с таким телефоном уже существует";
        }

        // Если есть ошибки уникальности
        if (!empty($uniqueErrors)) {
            $errorMessage = implode("<br>", $uniqueErrors);
            $this->redirectWithError($errorMessage, [
                'user_name' => $user_name,
                'email' => $email,
                'telephone' => $telephone
            ], 'registration');
        }

        // Хешируем пароль
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Регистрируем пользователя
        if ($this->userModel->create($user_name, $email, $formattedPhone, $hashedPassword)) {
            $_SESSION['success'] = "Регистрация прошла успешно! Теперь вы можете войти.";
            unset($_SESSION['form_data']);
            header("Location: ../index.php?page=login");
            exit();
        } else {
            $this->redirectWithError("Ошибка при регистрации. Попробуйте позже.", [
                'user_name' => $user_name,
                'email' => $email,
                'telephone' => $telephone
            ], 'registration');
        }
    }

    //Валидация телефона
    private function validatePhone($telephone)
    {
        $cleanPhone = preg_replace('/[\s\-\(\)\+]/', '', $telephone);

        if (!preg_match('/^[0-9]+$/', $cleanPhone)) {
            return "Телефон может содержать только цифры.";
        }

        $length = strlen($cleanPhone);
        if ($length < 10 || $length > 11) {
            return "Телефон должен содержать 10-11 цифр";
        }

        if ($length === 11 && !in_array($cleanPhone[0], ['7', '8'])) {
            return "Номер должен начинаться с 7 или 8";
        }

        return true;
    }

    //форматируем телефон для БД
    private function formatPhoneForDb($telephone)
    {
        return preg_replace('/[^0-9]/', '', $telephone);
    }

    //Вход
    public function login($login, $password)
    {
        // Валидация
        $errors = [];
        if (empty($login)) {
            $errors[] = "Введите email или телефон";
        }
        if (empty($password)) {
            $errors[] = "Введите пароль";
        }
        // Проверка reCAPTCHA
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (empty($recaptchaResponse)) {
            $errors[] = "Подтвердите, что вы не робот";
        } else {
            // Создаем экземпляр ReCaptcha с вашим секретным ключом
            $recaptcha = new ReCaptcha($this->recaptchaSecret);
            // Верифицируем токен
            $resp = $recaptcha->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $errors[] = "Проверка reCAPTCHA не пройдена. Попробуйте еще раз.";
            }
        }

        if (!empty($errors)) {
            $this->redirectWithError(implode("<br>", $errors), ['login' => $login], 'login');
        }

        // Пытаемся найти пользователя по email или телефону
        $user = $this->userModel->getUserByLogin($login);

        // Проверяем пароль
        if (!$user) {
            $this->redirectWithError("Пользователь с таким email/телефоном не найден", ['login' => $login], 'login');
        }

        if (!password_verify($password, $user['password'])) {
            $this->redirectWithError("Неверный пароль", ['login' => $login], 'login');
        }

        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_telephone'] = $user['telephone'];
        $_SESSION['success'] = "Добро пожаловать, " . $user['user_name'] . "!";

        // Очищаем данные формы
        unset($_SESSION['form_data']);

        // Перенаправляем на профиль
        header("Location: ../index.php?page=profile");
        exit();
    }

    //Выход
    public function logout()
    {
        // Очищаем все сессионные данные
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ../index.php?page=login");
        exit();
    }

    //Перенеправление на страницу, вывод сообщений об ошибки
    private function redirectWithError($error, $formData = [], $page = 'registration')
    {
        $_SESSION['error'] = $error;
        if (!empty($formData)) {
            $_SESSION['form_data'] = $formData;
        }
        header("Location: ../index.php?page=" . $page);
        exit();
    }

    //Обновление профиля (имя, телефон, почта)
    public function updateProfile($user_name, $email, $telephone)
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "Необходимо авторизоваться";
            header("Location: ../index.php?page=login");
            exit();
        }

        $errors = [];

        // Валидация
        if (empty($user_name)) {
            $errors[] = "Имя обязательно";
        }

        if (empty($email)) {
            $errors[] = "Email обязателен";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Неверный формат email";
        }

        if (empty($telephone)) {
            $errors[] = "Телефон обязателен";
        } else {
            $phoneValidation = $this->validatePhone($telephone);
            if ($phoneValidation !== true) {
                $errors[] = $phoneValidation;
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            header("Location: ../index.php?page=profile");
            exit();
        }

        // Форматируем телефон для БД
        $formattedPhone = $this->formatPhoneForDb($telephone);

        // Проверка уникальности email
        $existingEmail = $this->userModel->getUserByEmail($email);
        if ($existingEmail && $existingEmail['id'] != $userId) {
            $_SESSION['error'] = "Пользователь с таким email уже существует";
            header("Location: ../index.php?page=profile");
            exit();
        }

        // Проверка уникальности телефона (по отформатированному)
        $existingTelephone = $this->userModel->getUserByTelephone($formattedPhone);
        if ($existingTelephone && $existingTelephone['id'] != $userId) {
            $_SESSION['error'] = "Пользователь с таким телефоном уже существует";
            header("Location: ../index.php?page=profile");
            exit();
        }

        // Обновление данных (сохраняем отформатированный телефон)
        if ($this->userModel->updateUser($userId, $user_name, $email, $formattedPhone)) {
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_telephone'] = $telephone; // Для отображения оставляем как ввел пользователь

            $_SESSION['success'] = "Профиль успешно обновлен";
        } else {
            $_SESSION['error'] = "Ошибка при обновлении профиля";
        }

        header("Location: ../index.php?page=profile");
        exit();
    }
    
    //Изменение пароля
    public function changePassword($current_password, $new_password, $confirm_password)
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "Необходимо авторизоваться";
            header("Location: ../index.php?page=login");
            exit();
        }

        $errors = [];

        // Валидация
        if (empty($current_password)) {
            $errors[] = "Введите текущий пароль";
        }

        if (empty($new_password)) {
            $errors[] = "Введите новый пароль";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "Новый пароль должен быть не менее 6 символов";
        }

        if ($new_password !== $confirm_password) {
            $errors[] = "Новые пароли не совпадают";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            header("Location: ../index.php?page=profile");
            exit();
        }

        // Получаем пользователя из БД для проверки текущего пароля
        $user = $this->userModel->getUserById($userId);

        if (!$user || !password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Текущий пароль неверен";
            header("Location: ../index.php?page=profile");
            exit();
        }

        // Хешируем новый пароль
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

        // Обновляем пароль
        if ($this->userModel->updatePassword($userId, $hashedPassword)) {
            $_SESSION['success'] = "Пароль успешно изменен";
        } else {
            $_SESSION['error'] = "Ошибка при изменении пароля";
        }

        header("Location: ../index.php?page=profile");
        exit();
    }
}
