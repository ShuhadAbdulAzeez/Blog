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
</head>
<style>
  .error {
    color: red;
  }
</style>
<body>
  <div class="container">
    <div class="form-container">
      <form method="POST">
        <h2>Login</h2>
        <?php if (!empty($errors['email'])):?>
          <p class="error"><?=$errors['email']?></p>
        <?php endif;?>

        <input value="<?=old_value('email')?>" type="email" name="email" placeholder="Email">
        <input value="<?=old_value('password')?>" type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
        <p class="form-message">Don't have an account? <a href="register">Register</a></p>
      </form>
    </div>
  </div>
</body>
</html>
