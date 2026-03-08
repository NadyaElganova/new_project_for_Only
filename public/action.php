<?php

session_start();
require_once '../app/config/db.php';

$action = $_GET['action'] ?? null;
$controller = $_GET['controller'] ?? 'user';

switch ($controller) {
    case 'user':
        require_once '../app/controllers/UserController.php';
        $userController = new UserController($pdo);
        switch ($action) {
            case 'register':
                $user_name = $_POST["user_name"] ?? '';
                $email = $_POST["email"] ?? '';
                $telephone = $_POST["telephone"] ?? '';
                $password = $_POST["password"] ?? '';
                $repeat_password = $_POST["repeat_password"] ?? '';
                $userController->register($user_name, $email, $telephone, $password, $repeat_password);
                break;
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userController->login(
                        $_POST['login'] ?? '',
                        $_POST['password'] ?? ''
                    );
                }
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'updateProfile':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userController->updateProfile(
                        $_POST['user_name'] ?? '',
                        $_POST['email'] ?? '',
                        $_POST['telephone'] ?? ''
                    );
                }
                break;
            case 'changePassword':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userController->changePassword(
                        $_POST['current_password'] ?? '',
                        $_POST['new_password'] ?? '',
                        $_POST['confirm_password'] ?? ''
                    );
                }
                break;
            default:
                header("Location: index.php");
                break;
        }
        break;
    default:
        echo "Контроллер не найден";
        break;
}
