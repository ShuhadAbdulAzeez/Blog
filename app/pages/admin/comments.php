<?php if ($action == 'edit'): ?>
  <div class="col-md-6 mx-auto">
    <form method="POST">
      <h2 class="col-md-5 mx-auto">Permission</h2>

      <?php if (!empty($row)) : ?>

        <div class="form-floating mb-3 my-3">
          <select name="disabled" class="form-select">
            <option value="0">Yes</option>
            <option value="1">No</option>
          </select>
            <label for="floatingInput">Active</label>
        </div>

        <button type="submit" class="btn btn-warning">Save</button>

        <a href="<?= ROOT ?>/admin/comments">
          <button type="button" class="btn btn-success">Back</button>
        </a>
      <?php endif; ?>

    </form>
  </div>


<?php elseif ($action == 'delete'): ?>

  <div class="col-md-6 mx-auto">
    <form method="POST">
      <h2 class="col-md-6 mx-auto">Delete Comment</h2>

      <?php if (!empty($row)) : ?>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['comment']) ? htmlspecialchars($row['comment']) : '' ?></div>
          <?php if (!empty($errors['comment'])) : ?>
            <div class="text-danger"><?= $errors['comment'] ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <div class="form-control mb-2"><?= isset($row['title']) ? htmlspecialchars($row['title']) : '' ?></div>
          <?php if (!empty($errors['post_id'])) : ?>
            <div class="text-danger"><?= $errors['post_id'] ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-danger">Delete</button>

        <a href="<?= ROOT ?>/admin/comments">
          <button type="button" class="btn btn-success">Back</button>
        </a>
      <?php endif; ?>

    </form>
  </div>

<?php else: ?>
  <h2>Comments</h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Comment</th>
          <th>Post Title</th>
          <th>Active</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $limit = 10;
        $offset = ($PAGE['page_number'] - 1) * $limit;

        $query = "SELECT comments.id, comments.comment, comments.name, posts.title as post_id, comments.approved as active FROM comments JOIN posts ON comments.post_id = posts.id ORDER BY comments.id DESC LIMIT $limit OFFSET $offset";
        $rows = query($query);
        ?>

        <?php if (!empty($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['comment']) ?></td>
              <td><?= $row['post_id'] ?></td>
              <td><?= $row['active'] ? 'YES' : 'NO' ?></td>
              <td>
                <a href="<?= ROOT ?>/admin/comments/edit/<?= $row['id'] ?>">
                  <button class="btn btn-warning btn-sm">APPROVAL</button>
                </a>

                <a href="<?= ROOT ?>/admin/comments/delete/<?= $row['id'] ?>">
                  <button class="btn btn-danger btn-sm">DELETE</button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="col-md-12 mb-4">
      <a href="<?= $PAGE['first_link'] ?>">
        <button class="btn btn-secondary">First Page</button>
      </a>

      <a href="<?= $PAGE['prev_link'] ?>">
        <button class="btn btn-secondary">Prev Page</button>
      </a>

      <a href="<?= $PAGE['next_link'] ?>">
        <button class="btn btn-secondary float-end">Next Page</button>
      </a>
    </div>
  </div>
<?php endif; ?>
