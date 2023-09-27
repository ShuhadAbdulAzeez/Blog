<?php
if (!empty($_POST)) {
    // validate
    $errors = [];

    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    }

    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $row = query($query, ['email' => $_POST['email']]);

        if ($row) {
            if (password_verify($_POST['password'], $row[0]['password'])) {
                // grant access
                authenticate($row[0]);
                redirect('home');
            } else {
                $errors['email'] = 'Wrong email or password';
            }
        } else {
            $errors['email'] = 'Wrong email or password';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/login.css">
  <style>
    body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 20px;
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    .form-container h2 {
      font-size: 24px;
      color: #333;
    }

    .error {
      color: red;
      margin-top: 10px;
    }

    input[type="email"],
    input[type="password"] {
      width: 90%;
      padding: 10px;
      margin: 10px auto; /* Center the input fields horizontally */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button[type="submit"] {
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 10px 20px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #0056b3;
    }

    .form-message {
      margin-top: 10px;
      font-size: 14px;
    }

    .form-message a {
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <form method="POST">
        <h2>LOGIN</h2>
        <?php if (!empty($errors['email'])): ?>
          <p class="error"><?= $errors['email'] ?></p>
        <?php endif; ?>

        <input value="<?= old_value('email') ?>" type="email" name="email" placeholder="Email">
        <input value="<?= old_value('password') ?>" type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
        <p class="form-message">Don't have an account? <a href="register">Register</a></p>
      </form>
    </div>
  </div>
</body>
</html>

