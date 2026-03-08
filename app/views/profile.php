<?php
// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Для доступа к профилю необходимо войти";
    header("Location: " . $basePath . "/index.php?page=login");
    exit();
}

// Получаем данные пользователя из сессии
$userData = [
    'user_name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? '',
    'telephone' => $_SESSION['user_telephone'] ?? ''
];
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Личный кабинет</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Форма изменения личных данных -->
                    <form method="POST" action="<?php echo $basePath; ?>/public/action.php?controller=user&action=updateProfile">
                        <h5 class="mb-3">Основная информация</h5>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" 
                                   value="<?php echo htmlspecialchars($userData['user_name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" 
                                   value="<?php echo htmlspecialchars($userData['telephone']); ?>" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mb-4">
                            <i class="fas fa-save me-2"></i>Обновить данные
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <!-- Форма изменения пароля -->
                    <form method="POST" action="<?php echo $basePath; ?>/public/action.php?controller=user&action=changePassword">
                        <h5 class="mb-3">Изменить пароль</h5>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Текущий пароль</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   minlength="6" required>
                            <div class="form-text">Минимум 6 символов</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Подтвердите новый пароль</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Изменить пароль
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                </div>
            </div>
        </div>
    </div>
</div>