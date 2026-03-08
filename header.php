<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?page=home">Carousel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" 
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'home' ? 'active' : ''; ?>" href="?page=home">
                            <i class="fas fa-home me-1"></i>Главная
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Ссылки для авторизованного пользователя -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'profile' ? 'active' : ''; ?>" href="?page=profile">
                                <i class="fas fa-user-circle me-1"></i>Личный кабинет
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $basePath; ?>/public/action.php?controller=user&action=logout">
                                <i class="fas fa-sign-out-alt me-1"></i>Выйти
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Ссылки для неавторизованного пользователя -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'login' ? 'active' : ''; ?>" href="?page=login">
                                <i class="fas fa-sign-in-alt me-1"></i>Войти
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $page === 'registration' ? 'active' : ''; ?>" href="?page=registration">
                                <i class="fas fa-user-plus me-1"></i>Регистрация
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Дополнительная информация о пользователе (справа), после корректной авторизации -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <span class="nav-link text-light">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь'); ?>
                            </span>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Отступ для фиксированной навигации -->
    <style>
        body {
            padding-top: 60px;
        }
        .navbar-nav .nav-link {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        .navbar-nav .nav-link i {
            margin-right: 5px;
        }
    </style>
</header>