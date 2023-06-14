<?php

if ($action == 'add') {
    class CategoryAdds
    {
        private $category;
        private $slug;
        private $disabled;

        public function __construct($category, $slug, $disabled)
        {
            $this->category = $category;
            $this->slug = $slug;
            $this->disabled = $disabled;
        }

        public function validate() 
        {
            $errors = [];

            if (empty($this->category)) {
                $errors['category'] = 'Category is required';
            } elseif (!preg_match("/^[a-zA-Z0-9 \-\_\&]+$/", $this->category)) {
                $errors['category'] = "Category can only have letters, numbers, spaces, hyphens, underscores, and ampersands";
            }

            // Assuming the 'query' function is defined elsewhere
            $slug = str_to_url($this->category);

            $query = "SELECT id FROM categories WHERE slug = :slug LIMIT 1";
            $slug_row = query($query, ['slug' => $slug]);

            if ($slug_row) {
                $slug .= rand(1000, 9999);
            }

            return $slug;
        }
    }

    // Usage:
    if (!empty($_POST)) {
        $category = $_POST['category'];
        $slug = str_to_url($category);
        $disabled = $_POST['disabled'];
    
        $categories = new CategoryAdds($category, $slug, $disabled);
        $slug = $categories->validate();

        if (empty($errors)) {
            $query = "INSERT INTO categories (category, slug, disabled) VALUES (:category, :slug, :disabled)";
            $params = [
                'category' => $category,
                'slug' => $slug,
                'disabled' => $disabled 
            ];
            query($query, $params);

            redirect('admin/categories');
        }
    }
} elseif ($action == 'edit') {
    $query = "SELECT * FROM categories WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {
        class CategoryEdits
        {
            private $category;
            private $slug;
            private $id;

            public function __construct($category, $slug, $id)
            {
                $this->category = $category;
                $this->slug = $slug;
                $this->id = $id;
            }

            public function validate()
            {
                $errors = [];

                if (empty($this->category)) {
                    $errors['category'] = 'Category is required';
                } elseif (!preg_match("/^[a-zA-Z0-9 \-\_\&]+$/", $this->category)) {
                    $errors['category'] = "Category can only have letters, numbers, spaces, hyphens, underscores, and ampersands";
                }

                $query = "SELECT id FROM categories WHERE slug = :slug AND id != :id LIMIT 1";
                $slugResult = query_row($query, ['slug' => $this->slug, 'id' => $this->id]);

                if ($slugResult) {
                    $errors['slug'] = "That slug is already in use";
                }

                return $errors;
            }
        }

        if (!empty($_POST)) {
            $category = $_POST['category'];
            $slug = str_to_url($category);
            $disabled = $_POST['disabled'];

            $categories = new CategoryEdits($category, $slug, $id);
            $errors = $categories->validate();

            if (empty($errors)) {
                $query = "UPDATE categories SET category = :category, slug = :slug, disabled = :disabled WHERE id = :id";
                $params = [
                    'category' => $category,
                    'slug' => $slug,
                    'disabled' => $disabled,
                    'id' => $id
                ];
                query($query, $params);

                redirect('admin/categories');
            }
        }
    } else {
        redirect('admin/categories');
    }
} elseif ($action == 'delete') {
    $query = "SELECT * FROM categories WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class CategoryDeletes
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

            $registration = new CategoryDeletes($id);

            if (empty($errors)) {
                $query = "DELETE from categories WHERE id = :id limit 1";
                $params = [
                    'id' => $id,
                ];
                query($query, $params);

                redirect('admin/categories');
            }
        }
    } else {
        redirect('admin/categories');
    }
}
?>