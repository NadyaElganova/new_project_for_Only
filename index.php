<?php

session_start();

//Определяем базовый путь
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath == '/' || $basePath == '\\') {
  $basePath = '';
}

//Переход на страницы
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$pages = ['home', 'login', 'registration', 'profile'];
if (!in_array($page, $pages)) {
  $page = 'home';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./public/assets/main.css" rel="stylesheet">
  <link href="./includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Наш сайт</title>
</head>

<body class="index-page">
  <?php include './header.php'; ?>
  
  <main>
    <?php include './app/views/partials/messages.php';?> <!--вывод сообщений об успехе или ошибке-->
    <?php include './app/views/' . $page . '.php'; ?> <!--подгружается нужный контент-->
  </main>

  <footer class="pt-3 mt-4 text-muted border-top">
    &copy; 2026
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
