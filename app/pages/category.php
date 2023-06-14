<body class="bg-light">
  
  <?php include '../app/pages/includes/header.php'; ?>

<div class="mx-auto col-md-10">
    <?php include '../app/pages/includes/child-nav.php'; ?>

    <div>
        <h4 class="text-uppercase font-weight-bold d-flex justify-content-center">category</h4>
    </div>

    <div class="container-fluid p-5">
      <div class="row">

        <?php
          $limit = 10;
          $offset = ($PAGE['page_number'] - 1) * $limit;

          $category_slug = $url[1] ?? null;

          if($category_slug)
          {
            $query = "SELECT posts.*,categories.category FROM posts JOIN categories on posts.category_id = categories.id WHERE posts.category_id in (select id from categories where slug = :category_slug && disabled = 0) ORDER BY id DESC limit $limit offset $offset";
            $rows = query($query,['category_slug'=>$category_slug]);
          }
          if(!empty($rows))
          {
            foreach ($rows as $row) {
              include '../app/pages/includes/post-card.php';
            }

          }else{
            echo "No items found";
          }
        ?>
        
      </div> 
    </div>

    <div class="col-md-12 mb-5">
        <a href="<?=$PAGE['first_link']?>">
            <button class="btn btn-secondary mr-5">First Page</button>
        </a>

        <a href="<?=$PAGE['prev_link']?>">
            <button class="btn btn-secondary mr-5">Prev Page</button>
        </a>

        <a href="<?=$PAGE['next_link']?>">
            <button class="btn btn-secondary float-end">Next Page</button>
        </a>
    </div>
</div>

<?php include '../app/pages/includes/footer.php'; ?>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"
></script>
