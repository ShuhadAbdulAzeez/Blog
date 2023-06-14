<style>
  .comment-section {
  margin-top: 25px;
}

.comment-input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 100%;
  font-size: 16px;
}

.comment-button {
  padding: 10px 30px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  margin-top: 25px;
  cursor: pointer;
  font-size: 16px;
}

.comment-button:hover {
  background-color: #45a049;
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

          $slug = $url[1] ?? null;

          if($slug) 
          {
            $query = "SELECT posts.*,categories.category FROM posts JOIN categories on posts.category_id = categories.id WHERE posts.slug = :slug limit 1";
            $row = query_row($query, ['slug'=>$slug]);
          }
          
          if(!empty($row))
          { ?>

            <div class="col-md-12 mb-4 mx-auto">
                <div class="card">
                    <img src="<?=get_image($row['image'])?>" class="card-img-top fixed-size-image" alt="Card image">
                    <div class="card-body text-center">
                        <strong class="d-inline-block mb-2 text-primary text-uppercase"><?=esc($row['category'] ?? 'Unknown')?></strong>
                        <h2 class="card-title text-uppercase"><?=esc($row['title'])?></h2>
                        <div class="mb-1 text-muted"><b><?=date("jS M, Y", strtotime($row['date']))?></b></div>
                        <p class="card-text">
                            <?=nl2br(esc($row['content']))?>
                        </p>

                        <!-- Comment input and button -->
                    </div>
                </div>
                <!-- Demo Comment -->
                        <div class="comment-section text-center">
                            <p>This is a demo comment.</p>
                        </div>
                        <!-- End of Demo Comment -->
                <div class="comment-section text-center">
                    <input type="text" id="comment" name="comment" placeholder="Enter your comment" class="comment-input">
                    <button type="submit" class="btn btn-success m-2">Add Comment</button>
                    <a href="<?=ROOT?>/">
                        <button type="submit" class="btn btn-primary">Back</button>
                    </a>
                </div>
            </div>

          <?php
          }else{
            echo "No items found";
          }
        ?>
        
      </div> 
    </div>
</div>

<?php include '../app/pages/includes/footer.php'; ?>

    