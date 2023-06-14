<?php

class UserRegistration
{
    private $username;
    private $email;
    private $password;
    private $confirmPassword;
    private $terms;

    public function __construct($username, $email, $password, $confirmPassword, $terms)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->terms = $terms;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->username)) {
            $errors['username'] = 'Username is required';
        } elseif (!preg_match("/^[a-zA-Z]+$/", $this->username)) {
            $errors['username'] = "Username can only have letters and no spaces";
        }

        // Assuming the 'query' function is defined elsewhere
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $email = query($query, ['email' => $this->email]);

        if (empty($this->email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is not valid';
        } elseif ($email) {
            $errors['email'] = "That email is already in use";
        }

        if (empty($this->password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($this->password) < 8) {
            $errors['password'] = "Password must be 8 characters or more";
        } elseif ($this->password !== $this->confirmPassword) {
            $errors['password'] = "Passwords do not match";
        }

        if (empty($this->terms)) {
            $errors['terms'] = 'Please accept the terms';
        }

        return $errors;
    }
}

// Usage:
if (!empty($_POST)) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $terms = isset($_POST['terms']);

  $registration = new UserRegistration($username, $email, $password, $confirmPassword, $terms);
  $errors = $registration->validate();

  if (empty($errors)) {
      // Insert user data into the database
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
      $params = [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => 'user' // Replace 'user' with the desired default role
      ];
      query($query, $params);

      redirect('login');
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="<?= ROOT ?>./assets/css/login.css">
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
        <h2>Register</h2>

        <input value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" type="text" name="username" placeholder="Username">
        <?php if (!empty($errors['username'])) : ?>
          <p class="error"><?= $errors['username'] ?></p>
        <?php endif; ?>

        <input value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" type="email" name="email" placeholder="Email">
        <?php if (!empty($errors['email'])) : ?>
          <p class="error"><?= $errors['email'] ?></p>
        <?php endif; ?>

        <input value="<?= isset($password) ? htmlspecialchars($password) : '' ?>" type="password" name="password" placeholder="Password">
        <?php if (!empty($errors['password'])) : ?>
          <p class="error"><?= $errors['password'] ?></p>
        <?php endif; ?>

        <input value="<?= isset($confirmPassword) ? htmlspecialchars($confirmPassword) : '' ?>" type="password" name="confirm_password" placeholder="Confirm Password">
        <?php if (!empty($errors['confirm_password'])) : ?>
          <p class="error"><?= $errors['confirm_password'] ?></p>
        <?php endif; ?>

        <label for="terms">I agree to the terms and conditions:</label>
        <input <?= isset($terms) && $terms ? 'checked' : '' ?> type="checkbox" id="terms" name="terms">
        <?php if (!empty($errors['terms'])) : ?>
          <p class="error"><?= $errors['terms'] ?></p>
        <?php endif; ?>

        <button type="submit">Register</button>
        <p class="form-message">Already have an account? <a href="login">Login</a></p>
      </form>
    </div>
  </div>
</body>
</html>


