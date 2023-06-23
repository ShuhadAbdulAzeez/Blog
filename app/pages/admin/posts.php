<?php if ($action == 'add'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST" enctype="multipart/form-data">
        <h2 class="col-md-6 mx-auto">Create Post</h2>

        <div class="mb-3">
            Featured Image:<br>
            <label class="d-block">
                <img class="mx-auto d-block image-preview-edit" src="<?=get_image('')?>" style="cursor: pointer; width: 150px; height: 150px; object-fit: cover;">
                <input onchange="display_image_edit(this.files[0])" type="file" name="image" class="d-none">
            </label>
            <?php if (!empty($errors['image'])) : ?>
                <div class="text-danger"><?= $errors['image'] ?></div>
            <?php endif; ?>

            <script>

                function display_image_edit(file) 
                {
                    document.querySelector(".image-preview-edit").src = URL.createObjectURL(file);
                }
            </script>

        </div>

        <div class="mb-3">
          <input value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" type="text" class="form-control" name="title" placeholder="title">
          <?php if (!empty($errors['title'])) : ?>
            <div class="text-danger"><?= $errors['title'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <textarea name="content" id="floatingInput" type="content" class="form-control" placeholder="Post Content" cols="30" rows="10"><?= isset($content) ? htmlspecialchars($content) : '' ?></textarea>
          <?php if (!empty($errors['content'])) : ?>
            <div class="text-danger"><?= $errors['content'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3 my-3">
          <select name="category_id" class="form-select">

            <?php

                $query = "SELECT * FROM categories ORDER BY id DESC ";
                $categories = query($query);
            ?>  

            <option value="">--Select</option>
            <?php if (!empty($categories)) : ?>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?=$cat['id']?>"><?=$cat['category']?></option>
                <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <?php if (!empty($errors['category'])) : ?>
            <div class="text-danger"><?= $errors['category'] ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>

        <a href="<?=ROOT?>/admin/posts">
          <button type="button" class="btn btn-success">Back</button>
        </a>
      </form>
    </div>

<?php elseif ($action == 'edit'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST" enctype="multipart/form-data">
        <h2 class="col-md-5 mx-auto">Edit Post</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
            Featured Image:<br>
            <label class="d-block">
                <img class="mx-auto d-block image-preview-edit" src="<?=get_image($row['image'])?>" style="cursor: pointer; width: 150px; height: 150px; object-fit: cover;">
                <input onchange="display_image_edit(this.files[0])" type="file" name="image" class="d-none">
            </label>
            <?php if (!empty($errors['image'])) : ?>
                <div class="text-danger"><?= $errors['image'] ?></div>
            <?php endif; ?>

            <script>

                function display_image_edit(file) 
                {
                    document.querySelector(".image-preview-edit").src = URL.createObjectURL(file);
                }
            </script>

        </div>

        <div class="mb-3">
          <input value="<?= isset($row['title']) ? htmlspecialchars($row['title']) : '' ?>" type="text" class="form-control" name="title" placeholder="title">
          <?php if (!empty($errors['title'])) : ?>
            <div class="text-danger"><?= $errors['title'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <textarea name="content" id="floatingInput" type="content" class="form-control" placeholder="Post Content" cols="30" rows="10"><?= isset($row['content']) ? htmlspecialchars($row['content']) : '' ?></textarea>
          <?php if (!empty($errors['content'])) : ?>
            <div class="text-danger"><?= $errors['content'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3 my-3">
          <select name="category_id" class="form-select">

            <?php

                $query = "SELECT * FROM categories ORDER BY id DESC ";
                $categories = query($query);
            ?>  

            <option value="">--Select</option>
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $cat) : ?>
                        <option <?=old_select('category_id',$cat['id'],$row['category_id'])?> value="<?=$cat['id']?>"><?=$cat['category']?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (!empty($errors['category'])) : ?>
                <div class="text-danger"><?= $errors['category'] ?></div>
              <?php endif; ?>
            </div>
        
        <button type="submit" class="btn btn-warning">Save</button>

        <a href="<?=ROOT?>/admin/posts">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>


<?php elseif ($action == 'delete'):?>

  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-6 mx-auto">Delete Post</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['title']) ? htmlspecialchars($row['title']) : '' ?></div>
          <?php if (!empty($errors['title'])) : ?>
            <div class="text-danger"><?= $errors['title'] ?></div>
          <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-danger">Delete</button>

        <a href="<?=ROOT?>/admin/posts">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>
 
<?php else: ?>
  <h2>Posts <a href="<?=ROOT?>/admin/posts/add"><button class="btn btn-primary float-end">Add New</button></a></h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Title</th>
          <th>Slug</th>
          <th>Image</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $limit = 10;
          $offset = ($PAGE['page_number'] - 1) * $limit;

          $query = "SELECT * FROM posts ORDER BY id DESC limit $limit offset $offset";
          $rows = query($query);
        ?>

        <?php if (!empty($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= $row['slug'] ?></td>
              <td>
                <img src="<?=get_image($row['image'])?>" style="width: 100px; height: 100px; object-fit: cover;">
              </td>
              <td><?= date("jS M, Y", strtotime($row['date'])) ?></td>
              <td>
                <a href="<?=ROOT?>/admin/posts/edit/<?=$row['id']?>">
                  <button class="btn btn-warning btn-sm">EDIT</button>
                </a>

                <a href="<?=ROOT?>/admin/posts/delete/<?=$row['id']?>">
                  <button class="btn btn-danger btn-sm">DELETE</button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="col-md-12 mb-4">
      <a href="<?=$PAGE['first_link']?>">
        <button class="btn btn-secondary">First Page</button>
      </a>

      <a href="<?=$PAGE['prev_link']?>">
        <button class="btn btn-secondary">Prev Page</button>
      </a>

      <a href="<?=$PAGE['next_link']?>">
        <button class="btn btn-secondary float-end">Next Page</button>
      </a>
    </div>
  </div>
<?php endif;?>

