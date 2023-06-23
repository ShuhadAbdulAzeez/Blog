
<style>
  .comment_input_box {
    background-color: #f2f2f2;
    padding: 50px;
}

.title {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
}

.form-control {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    resize: vertical;
}

.post {
    background-color: #337ab7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    background-color: #337ab7;
    border-radius: 4px;
    cursor: pointer;
}

.post:hover {
    background-color: #23527c;
}

.post_comment {
    margin-top: 20px;
}

.comment_box {
    background-color: #f9f9f9;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.comment {
    color: #555;
    font-size: 16px;
    margin-bottom: 10px;
}

.comment_by {
    color: #777;
    font-style: italic;
}


</style>

<body class="bg-light">
    <?php include '../app/pages/includes/header.php'; ?>

    <div class="mx-auto col-md-10">
        <div class="container-fluid p-5">
            <div class="d-flex align-items-center justify-content-around">
            </div>
        </div>

        <div class="container-fluid p-5">
            <div class="row">
                <?php
                // Create a PDO connection using your database connection details
                $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $slug = $url[1] ?? null;

                if ($slug) {
                    $query = "SELECT posts.*, categories.category FROM posts JOIN categories ON posts.category_id = categories.id WHERE posts.slug = :slug LIMIT 1";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (isset($_POST['comment']) && $_POST['comment'] != '') {
                        $post_id = $row['id'];
                        post_comment($post_id);
                    }

                    if (!empty($row) && isset($row['id'])) {
                        $post_id = $row['id'];
                        ?>
                        <div class="col-md-12 mb-4 mx-auto">
                            <div class="card">
                                <img src="<?= get_image($row['image']) ?>" class="card-img-top fixed-size-image" alt="Card image">
                                <div class="card-body text-center">
                                    <strong class="d-inline-block mb-2 text-primary text-uppercase"><?= esc($row['category'] ?? 'Unknown') ?></strong>
                                    <h2 class="card-title text-uppercase"><?= esc($row['title']) ?></h2>
                                    <div class="mb-1 text-muted"><b><?= date("jS M, Y", strtotime($row['date'])) ?></b></div>
                                    <p class="card-text">
                                        <?= nl2br(esc($row['content'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5 comment_input_box">
                                <h4 class="title">Post Your Comments</h4>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea name="comment" class="form-control" rows="6" placeholder="Write your Comment...."></textarea>
                                    </div>
                                    <button class="post float-end" type="submit">Post Comment</button>
                                </form>
                            </div>
                            <div class="col-md-12 post_comment">
                                <?php
                                $post_id = $row['id'];
                                $query = "SELECT * FROM comments WHERE post_id = :post_id";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                                $stmt->execute();
                                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($comments as $row) {
                                    ?>
                                    <div class="comment_box mt-3">
                                        <div class="comment">
                                            <?= esc($row['comment']) ?>
                                        </div>
                                        <div class="comment_by">
                                             <b>By: <?= esc($row['name']) ?></b>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "No items found";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php include '../app/pages/includes/footer.php'; ?>
</body>    

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"
></script>