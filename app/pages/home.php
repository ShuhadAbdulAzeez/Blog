<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Travel Blog</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <script
      src="https://kit.fontawesome.com/5788b38031.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <style>
    .user-image {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
  </style>
  <body class="bg-light mx-auto">
  
  <?php include '../app/pages/includes/header.php'; ?>


    <div class="container-fluid p-5">
      <div class="d-flex align-items-center justify-content-around">

        <div>
          <h4 class="text-uppercase font-weight-bold">Home</h4>
        </div>

      </div>
    </div>

    <div class="container-fluid p-5">
      <div class="row">

        <?php

          $query = "SELECT posts.*,categories.category FROM posts JOIN categories on posts.category_id = categories.id limit 8 ";
          $rows = query($query);
          if($rows)
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

    <?php include '../app/pages/includes/footer.php'; ?>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
