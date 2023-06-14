<?php if ($action == 'add'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-6 mx-auto">Create Account</h2>

        <div class="mb-3">
          <input value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" type="text" class="form-control" name="username" placeholder="Username">
          <?php if (!empty($errors['username'])) : ?>
            <div class="text-danger"><?= $errors['username'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" type="email" class="form-control" name="email" placeholder="Email">
          <?php if (!empty($errors['email'])) : ?>
            <div class="text-danger"><?= $errors['email'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3 my-3">
          <select name="role" class="form-select">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
          <?php if (!empty($errors['role'])) : ?>
            <div class="text-danger"><?= $errors['role'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="<?= isset($password) ? htmlspecialchars($password) : '' ?>" type="password" class="form-control" name="password" placeholder="Password">
          <?php if (!empty($errors['password'])) : ?>
            <div class="text-danger"><?= $errors['password'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="<?= isset($confirmPassword) ? htmlspecialchars($confirmPassword) : '' ?>" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
          <?php if (!empty($errors['confirm_password'])) : ?>
            <div class="text-danger"><?= $errors['confirm_password'] ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>

        <a href="<?=ROOT?>/admin/users">
          <button type="button" class="btn btn-success">Back</button>
        </a>
      </form>
    </div>

<?php elseif ($action == 'edit'):?>
  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-5 mx-auto">Edit Account</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
          <input value="<?= isset($row['username']) ? htmlspecialchars($row['username']) : '' ?>" type="text" class="form-control" name="username" placeholder="Username">
          <?php if (!empty($errors['username'])) : ?>
            <div class="text-danger"><?= $errors['username'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="<?= isset($row['email']) ? htmlspecialchars($row['email']) : '' ?>" type="email" class="form-control" name="email" placeholder="Email">
          <?php if (!empty($errors['email'])) : ?>
            <div class="text-danger"><?= $errors['email'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3 my-3">
          <select name="role" class="form-select">
            <option <?=old_select('role','user', $row['role'])?>  value="user">User</option>
            <option <?=old_select('role','admin', $row['role'])?> value="admin">Admin</option>
          </select>
          <?php if (!empty($errors['role'])) : ?>
            <div class="text-danger"><?= $errors['role'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="" type="password" class="form-control" name="new_password" placeholder="Password: Leave Empty to keep old one">
          <?php if (!empty($errors['new_password'])) : ?>
            <div class="text-danger"><?= $errors['new_password'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <input value="" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password: Leave Empty to keep old one">
          <?php if (!empty($errors['confirm_password'])) : ?>
            <div class="text-danger"><?= $errors['confirm_password'] ?></div>
          <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-warning">Save</button>

        <a href="<?=ROOT?>/admin/users">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>


<?php elseif ($action == 'delete'):?>

  <div class="col-md-6 mx-auto">
      <form method="POST">
        <h2 class="col-md-6 mx-auto">Delete Account</h2>

      <?php if(!empty($row)) :?>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['username']) ? htmlspecialchars($row['username']) : '' ?></div>
          <?php if (!empty($errors['username'])) : ?>
            <div class="text-danger"><?= $errors['username'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['email']) ? htmlspecialchars($row['email']) : '' ?></div>
          <?php if (!empty($errors['email'])) : ?>
            <div class="text-danger"><?= $errors['email'] ?></div>
          <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-danger">Delete</button>

        <a href="<?=ROOT?>/admin/users">
          <button type="button" class="btn btn-success">Back</button>
        </a>
        <?php endif; ?>
        
    </form>
  </div>
 
<?php else: ?>
  <h2>Users <a href="<?=ROOT?>/admin/users/add"><button class="btn btn-primary float-end">Add New</button></a></h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $limit = 10;
          $offset = ($PAGE['page_number'] - 1) * $limit;

          $query = "SELECT * FROM users ORDER BY id DESC limit $limit offset $offset";
          $rows = query($query);
        ?>

        <?php if (!empty($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['role'] ?></td>
              <td><?= date("jS M, Y", strtotime($row['date'])) ?></td>
              <td>
                <a href="<?=ROOT?>/admin/users/edit/<?=$row['id']?>">
                  <button class="btn btn-warning btn-sm">EDIT</button>
                </a>

                <a href="<?=ROOT?>/admin/users/delete/<?=$row['id']?>">
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


