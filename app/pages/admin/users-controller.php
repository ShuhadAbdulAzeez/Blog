<?php

if ($action == 'add') {
    class UserAdds
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
    if (!empty($_POST)) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        $registration = new UserAdds($username, $email, $password, $confirmPassword);
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

            redirect('admin/users');
        }
    }
} elseif ($action == 'edit') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class UserEdits
        {
            private $username;
            private $email;
            private $password;
            private $confirmPassword;

            public function __construct($id, $username, $email, $password, $confirmPassword)
            {
                $this->id = $id;
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

                if (empty($this->email)) {
                    $errors['email'] = 'Email is required';
                } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Email is not valid';
                }

                // Assuming the 'query' function is defined elsewhere
                $query = "SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1";
                $email = query($query, ['email' => $this->email, 'id' => $this->id]);

                if ($email) {
                    $errors['email'] = "That email is already in use";
                }

                if (!empty($this->password)) {
                    if (strlen($this->password) < 8) {
                        $errors['password'] = "Password must be 8 characters or more";
                    } elseif ($this->password !== $this->confirmPassword) {
                        $errors['password'] = "Passwords do not match";
                    }
                }

                return $errors;
            }
        }

        // Usage:
        if (!empty($_POST)) {
            $id = $row['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            $role = $_POST['role'];

            $registration = new UserEdits($id, $username, $email, $password, $confirmPassword);
            $errors = $registration->validate();

            if (empty($errors)) {
                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $row['password'];

                $query = "UPDATE users SET username = :username, email = :email, password = :password, role = :role WHERE id = :id";
                $params = [
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => $role
                ];
                query($query, $params);

                redirect('admin/users');
            }
        }
    } else {
        redirect('admin/users');
    }
}elseif ($action == 'delete') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class UserDeletes
        {
            public function __construct($id)
            {
                $this->id = $id;
            }

        }

        // Usage:
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          $id = $row['id'];

            $registration = new UserDeletes($id);

            if (empty($errors)) {
                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $row['password'];

                $query = "DELETE from users WHERE id = :id limit 1";
                $params = [
                    'id' => $id,
                ];
                query($query, $params);

                redirect('admin/users');
            }
        }
    } else {
        redirect('admin/users');
    }
}