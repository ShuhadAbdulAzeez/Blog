<?php

if (!logged_in()) {
    redirect('login');
}

$section = $url[1] ?? 'dashboard';
$action = $url[2] ?? 'view';
$id = $url[3] ?? 0;

$filename = "../app/pages/admin/" . $section . ".php";
if (!file_exists($filename)) {
    $filename = "../app/pages/admin/404.php";
}

if($section == 'users')
{
    require_once "../app/pages/admin/users-controller.php";
}elseif
  ($section == 'categories')
{
    require_once "../app/pages/admin/categories-controller.php";
}elseif
  ($section == 'posts')
{
    require_once "../app/pages/admin/posts-controller.php";
}

//add new 
if ($action == 'add') {
    class UserAdd
    {
        private $username;
        private $email;
        private $password;
        private $confirmPassword;

        public function __construct($username, $email, $password, $confirmPassword)
        {
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->confirmPassword = $confirmPassword;
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

            return $errors;
        }
    }

    // Usage:
    // if (!empty($_POST)) {
    //     $username = $_POST['username'];
    //     $email = $_POST['email'];
    //     $password = $_POST['password'];
    //     $confirmPassword = $_POST['confirm_password'];

    //     $registration = new UserAdd($username, $email, $password, $confirmPassword);
    //     $errors = $registration->validate();

    //     if (empty($errors)) {
    //         // Insert user data into the database
    //         $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    //         $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    //         $params = [
    //             'username' => $username,
    //             'email' => $email,
    //             'password' => $hashedPassword,
    //             'role' => 'user' // Replace 'user' with the desired default role
    //         ];
    //         query($query, $params);

    //         redirect('admin/users');
    //     }
    // }
// } elseif ($action == 'edit') {
//     $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
//     $row = query_row($query, ['id' => $id]);

//     if ($row) {

//         class UserEdit
//         {
//             private $username;
//             private $email;
//             private $password;
//             private $confirmPassword;

//             public function __construct($id, $username, $email, $password, $confirmPassword)
//             {
//                 $this->id = $id;
//                 $this->username = $username;
//                 $this->email = $email;
//                 $this->password = $password;
//                 $this->confirmPassword = $confirmPassword;
//             }

//             public function validate()
//             {
//                 $errors = [];

//                 if (empty($this->username)) {
//                     $errors['username'] = 'Username is required';
//                 } elseif (!preg_match("/^[a-zA-Z]+$/", $this->username)) {
//                     $errors['username'] = "Username can only have letters and no spaces";
//                 }

//                 if (empty($this->email)) {
//                     $errors['email'] = 'Email is required';
//                 } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
//                     $errors['email'] = 'Email is not valid';
//                 }

//                 // Assuming the 'query' function is defined elsewhere
//                 $query = "SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1";
//                 $email = query($query, ['email' => $this->email, 'id' => $this->id]);

//                 if ($email) {
//                     $errors['email'] = "That email is already in use";
//                 }

//                 if (!empty($this->password)) {
//                     if (strlen($this->password) < 8) {
//                         $errors['password'] = "Password must be 8 characters or more";
//                     } elseif ($this->password !== $this->confirmPassword) {
//                         $errors['password'] = "Passwords do not match";
//                     }
//                 }

//                 return $errors;
//             }
//         }

//         // Usage:
//         if (!empty($_POST)) {
//           $id = $row['id'];
//           $username = $_POST['username'];
//           $email = $_POST['email'];
//           $password = isset($_POST['password']) ? $_POST['password'] : '';
//           $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

//             $registration = new UserEdit($id, $username, $email, $password, $confirmPassword);
//             $errors = $registration->validate();

//             if (empty($errors)) {
//                 $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $row['password'];

//                 $query = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
//                 $params = [
//                     'id' => $id,
//                     'username' => $username,
//                     'email' => $email,
//                     'password' => $hashedPassword
//                 ];
//                 query($query, $params);

//                 redirect('admin/users');
//             }
//         }
//     } else {
//         redirect('admin/users');
//     }
// }elseif ($action == 'delete') {
//     $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
//     $row = query_row($query, ['id' => $id]);

//     if ($row) {

//         class UserDelete
//         {
//             public function __construct($id)
//             {
//                 $this->id = $id;
//             }

//         }

//         // Usage:
//         if ($_SERVER['REQUEST_METHOD'] == "POST") {
//           $id = $row['id'];

//             $registration = new UserDelete($id);

//             if (empty($errors)) {
//                 $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $row['password'];

//                 $query = "DELETE from users WHERE id = :id limit 1";
//                 $params = [
//                     'id' => $id,
//                 ];
//                 query($query, $params);

//                 redirect('admin/users');
//             }
//         }
//     } else {
//         redirect('admin/users');
//     }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Section</title>
  <link rel="stylesheet" href="<?= ROOT ?>./assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <script
      src="https://kit.fontawesome.com/5788b38031.js"
      crossorigin="anonymous"
    ></script>
  <style>
    body {
      background-color: #f0f0f0;
    }
    .content {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <span class="navbar-brand">Admin Section</span>
      <a href="<?= ROOT ?>/home" class="navbar-brand text-uppercase">journey</a>
      <?php
      // Check if the user is logged in
      if (logged_in()) {
          // Retrieve the username from the session and display it
          $username = $_SESSION['USER']['username'];
          echo '<span class="navbar-text">Welcome, ' . $username . '</span>';
      }
      ?>
    </div>
    <a class="navbar-brand" href="<?= ROOT ?>/logout">Signout</a>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="sidebar">
          <ul class="list-group">
            <li class="list-group-item <?=$section =='dashboard' ? 'active': ''?>"><a href="<?= ROOT ?>/admin/dashboard">Dashboard</a></li>
            <li class="list-group-item <?=$section =='posts' ? 'active': ''?>"><a href="<?= ROOT ?>/admin/posts">Posts</a></li>
            <li class="list-group-item <?=$section =='categories' ? 'active': ''?>"><a href="<?= ROOT ?>/admin/categories">Categories</a></li>
            <li class="list-group-item <?=$section =='comments' ? 'active': ''?>"><a href="<?= ROOT ?>/admin/comments">Comments</a></li>
            <li class="list-group-item <?=$section =='users' ? 'active': ''?>"><a href="<?= ROOT ?>/admin/users">Users</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-9">
        <div class="content">
          <h2>Dashboard</h2>
          <hr>
          <?php 

            require_once $filename;

          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

