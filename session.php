<?php
session_start();
$db = new PDO('sqlite:messaging.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL, username TEXT)");
$db->exec("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT NOT NULL UNIQUE, message TEXT NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP)");

$error_message = '';

if (isset($_POST['login'])) {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $error_message = 'Please enter email and password';
  } else {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['username'] = $user['username'];
      header('Location: /');
      exit;
    } else {
      $error_message = 'Invalid email or password';
    }
  }
} elseif (isset($_POST['register'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  if (empty($email) || empty($password) || empty($username)) {
    $error_message = 'Please enter username, email, and password';
  } else {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      $error_message = 'Email already taken';
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $query = "INSERT INTO users (email, password, username) VALUES (:email, :password, :username)";
      $stmt = $db->prepare($query);
      $stmt->execute([':email' => $email, ':password' => $hashed_password, ':username' => $username]);

      $_SESSION['user_id'] = $db->lastInsertId();
      $_SESSION['email'] = $email;
      $_SESSION['username'] = $username;
      header('Location: /');
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container">
      <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="p-0" style="max-width: 300px;">
          <?php if (!isset($_GET['page']) || $_GET['page'] == 'login'): ?>
            <h1 class="fw-bold mb-0 fs-2 text-center mb-5">Login</h1>
            <form method="post">
              <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control rounded-3" id="floatingInputEmail" placeholder="name@example.com" required>
                <label for="floatingInputEmail">Email</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control rounded-3" id="floatingPassword" placeholder="password" required>
                <label for="floatingPassword">Password</label>
              </div>
              <div class="btn-group w-100 gap-3 mb-3">
                <button class="btn btn-primary fw-bold rounded w-50" type="submit" name="login">Login</button>
              </div>
              <a class="text-decoration-none" href="?page=register">Don't have an account?</a>
            </form>
          <?php elseif ($_GET['page'] == 'register'): ?>
            <h1 class="fw-bold mb-0 fs-2 text-center mb-5">Register</h1>
            <form method="post">
              <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control rounded-3" id="floatingInputusername" placeholder="username name" required>
                <label for="floatingInputusername">username</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control rounded-3" id="floatingInputEmail" placeholder="name@example.com" required>
                <label for="floatingInputEmail">Email</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control rounded-3" id="floatingPassword" placeholder="password" required>
                <label for="floatingPassword">Password</label>
              </div>
              <div class="btn-group w-100 gap-3 mb-3">
                <button class="btn btn-primary fw-bold rounded w-50" type="submit" name="register">Register</button>
              </div>
              <a class="text-decoration-none" href="?page=login">Already have an account?</a>
            </form>
          <?php else: ?>
            <p>Invalid page request.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </body>
</html>