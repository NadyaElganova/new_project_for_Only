1. Создайте базу данных и таблицу users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

2. Настройте конфигурационный файл:
   - Создать конфигурационный файл config.php, файл config.php необходимо разместить в папке app\config
   - Пример файла config.php приведен в папке app\config\config.example.php
   - В конфигурационном файле необходимо указать данные для подключения БД и ключи reCAPTCHA

4. Установите зависимости через Composer, composer.json размещен в папке \composer
