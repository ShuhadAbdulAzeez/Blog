<?php if ($action == 'add'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-7 mx-auto">Create Category</h2>

        <div class="mb-3">
          <input value="<?= isset($category) ? htmlspecialchars($category) : '' ?>" type="text" class="form-control" name="category" placeholder="Category">
          <?php if (!empty($errors['category'])) : ?>
            <div class="text-danger"><?= $errors['category'] ?></div>
          <?php endif; ?>
        </div>

        <div class="form-floating mb-3 my-3">
          <select name="disabled" class="form-select">
            <option value="0">Yes</option>
            <option value="1">No</option>
          </select>
            <label for="floatingInput">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>

        <a href="<?=ROOT?>/admin/categories">
          <button type="button" class="btn btn-success">Back</button>
        </a>
      </form>
    </div>

<?php elseif ($action == 'edit'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-5 mx-auto">Edit Category</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
          <input value="<?= isset($row['category']) ? htmlspecialchars($row['category']) : '' ?>" type="text" class="form-control" name="category" placeholder="category">
          <?php if (!empty($errors['category'])) : ?>
            <div class="text-danger"><?= $errors['category'] ?></div>
          <?php endif; ?>
        </div>

        <div class="form-floating mb-3 my-3">
          <select name="disabled" class="form-select">
            <option <?=old_select('disabled','0', $row['disabled'])?>  value="0">Yes</option>
            <option <?=old_select('disabled','1', $row['disabled'])?> value="1">No</option>
          </select>
          <label for="floatingInput">Active</label>
        </div>
        
        <button type="submit" class="btn btn-warning">Save</button>

        <a href="<?=ROOT?>/admin/categories">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>


<?php elseif ($action == 'delete'):?>

  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-6 mx-auto">Delete Category</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['category']) ? htmlspecialchars($row['category']) : '' ?></div>
          <?php if (!empty($errors['category'])) : ?>
            <div class="text-danger"><?= $errors['category'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['slug']) ? htmlspecialchars($row['slug']) : '' ?></div>
          <?php if (!empty($errors['slug'])) : ?>
            <div class="text-danger"><?= $errors['slug'] ?></div>
          <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-danger">Delete</button>

        <a href="<?=ROOT?>/admin/categories">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>
 
<?php else: ?>
  <h2>Categories<a href="<?=ROOT?>/admin/categories/add"><button class="btn btn-primary float-end">Add New</button></a></h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Category</th>
          <th>Slug</th>
          <th>Active</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $limit = 10;
          $offset = ($PAGE['page_number'] - 1) * $limit;

          $query = "SELECT * FROM categories ORDER BY id DESC limit $limit offset $offset";
          $rows = query($query);
        ?>

        <?php if (!empty($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><?= $row['slug'] ?></td>
              <td><?= $row['disabled'] ? 'NO':'YES' ?></td>
              <td>
                <a href="<?=ROOT?>/admin/categories/edit/<?=$row['id']?>">
                  <button class="btn btn-warning btn-sm">EDIT</button>
                </a>

                <a href="<?=ROOT?>/admin/categories/delete/<?=$row['id']?>">
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