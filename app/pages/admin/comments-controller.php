<?php

if ($action == 'edit') {
    $query = "SELECT * FROM comments WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {
        class CommentsEdit
        {
            private $active;
            private $id;

            public function __construct($active, $id)
            {
                $this->active = $active;
                $this->id = $id;
            }

            public function validate()
            {
                $errors = [];

                // Validate active field (optional)
                if (!in_array($this->active, ['0', '1'])) {
                    $errors['active'] = 'Invalid active value';
                }

                return $errors;
            }
        }

        if (!empty($_POST)) {
            $active = $_POST['active'];

            $commentsEdit = new CommentsEdit($active, $id);
            $errors = $commentsEdit->validate();

            if (empty($errors)) {
                // Proceed with updating the comment
                $query = "UPDATE comments SET active = :active WHERE id = :id";
                $params = [
                    'active' => $active,
                    'id' => $id
                ];
                query($query, $params);

                redirect('admin/comments');
            }
        }
    } else {
        redirect('admin/comments');
    }
} elseif ($action == 'delete') {
    $query = "SELECT * FROM comments WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($row) {

        class CommentDeletes
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

            $commentDelete = new CommentDeletes($id);

            if (empty($errors)) {
                $query = "DELETE from comments WHERE id = :id LIMIT 1";
                $params = [
                    'id' => $id,
                ];
                query($query, $params);

                redirect('admin/comments');
            }
        }
    } else {
        redirect('admin/comments');
    }
}

?>

