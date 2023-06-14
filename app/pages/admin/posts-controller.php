<?php

if ($action == 'add') {
    class PostAdds
    {
        private $title;
        private $content;
        private $category_id;
        private $slug;
        private $user_id;

        public function __construct($title, $content, $category_id, $slug, $user_id)
        {
            $this->title = $title;
            $this->content = $content;
            $this->category_id = $category_id;
            $this->slug = $slug;
            $this->user_id = $user_id;
        }

        public function validate()
        {
            $errors = [];

            if (empty($this->title)) {
                $errors['title'] = 'Title is required';
            }

            if (empty($this->category_id)) {
                $errors['category_id'] = 'Category is required';
            }

            if (!$this->validateImage()) {
                $errors['image'] = 'Featured Image is required and must be in a supported format (JPEG, PNG, or WebP)';
            }

            return [
                'errors' => $errors,
                'destination' => $this->getDestination(),
            ];

            $slug = str_to_url($this->title);

            $query = "SELECT id FROM posts WHERE slug = :slug LIMIT 1";
            $slug_row = query($query, ['slug' => $slug]);

            if ($slug_row) {
                $slug .= rand(1000, 9999);
            }

            return $slug;

        }

        private function validateImage()
        {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            $max_size = 1024; // Maximum size in kilobytes

            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileMimeType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);

                if (!in_array($fileMimeType, $allowed)) {
                    return false;
                }

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $destination = $folder . time() . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $destination);
                resize_image($destination, $max_size);

                return true;
            }

            return false;
        }

        private function getDestination()
        {
            return isset($_FILES['image']['name']) ? "uploads/" . time() . $_FILES['image']['name'] : '';
        }
    }

    // Usage:
    if (!empty($_POST)) {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $slug = str_to_url($title);
        $category_id = $_POST['category_id'] ?? '';
        $user_id = user('id');

        $blogPost = new PostAdds($title, $content, $slug, $category_id, $user_id);
        $result = $blogPost->validate();
        $errors = $result['errors'];
        $destination = $result['destination'];

        if (!empty($errors)) {
            
        }else{
            $query = "INSERT INTO posts (title, content, slug, category_id, user_id, image) VALUES (:title, :content, :slug, :category_id, :user_id, :image)";
            $params = [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'category_id' => $category_id,
                'user_id' => $user_id,
                'image' => $destination,
            ];
            query($query, $params);

            redirect('admin/posts');
        }
    }
} elseif ($action == 'edit') {
    $query = "SELECT * FROM posts WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class PostEdits
        {
            private $title;
            private $content;
            private $category_id;

            public function __construct($title, $content, $category_id)
            {
                $this->title = $title;
                $this->content = $content;
                $this->category_id = $category_id;
            }

            public function validate()
            {
                $errors = [];

                if (empty($this->title)) {
                    $errors['title'] = 'Title is required';
                }

                if (empty($this->category_id)) {
                    $errors['category_id'] = 'Category is required';
                }

                return [
                    'errors' => $errors,
                    'destination' => $this->getDestination(),
                ];
            }

            private function validateImage()
            {
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                $max_size = 1024; // Maximum size in kilobytes

                if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $fileMimeType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);

                    if (!in_array($fileMimeType, $allowed)) {
                        return false;
                    }

                    $folder = "uploads/";
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    $destination = $folder . time() . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $destination);
                    resize_image($destination, $max_size);

                    return true;
                }

                return false;
            }

            private function getDestination()
            {
                return isset($_FILES['image']['name']) ? "uploads/" . time() . $_FILES['image']['name'] : '';
            }
        }

        // Usage:
        if (!empty($_POST)) {
            $id = $row['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $category_id = $_POST['category_id'];

            $blogPost = new PostEdits($id, $title, $content, $category_id);
            $result = $blogPost->validate();
            $errors = $result['errors'];
            $destination = $result['destination'];
    
            
            if (!empty($errors)) {

            }else{
                $query = "UPDATE posts SET title = :title, content = :content, image =:image, category_id = :category_id WHERE id = :id";
                $params = [
                    'title' => $title,
                    'content' => $content,
                    'category_id' => $category_id,
                    'image' => $destination,
                ];
                query($query, $params);

                redirect('admin/posts');
            }
        }
    } else {
        redirect('admin/posts');
    }
} elseif ($action == 'delete') {
    $query = "SELECT * FROM posts WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class PostDeletes
        {
            private $id;

            public function __construct($id)
            {
                $this->id = $id;
            }
        }

        // Usage:
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $row['id'];

            $blogPost = new PostDeletes($id);

            $query = "DELETE from posts WHERE id = :id limit 1";
            $params = [
                'id' => $id,
            ];
            query($query, $params);

            if(file_exists($row['image'])){
                unlink($row['image']);
            }

            redirect('admin/posts');
        }
    } else {
        redirect('admin/posts');
    }
}
