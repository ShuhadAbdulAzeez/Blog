<div class="container-fluid p-5">
      <div class="d-flex align-items-center justify-content-around">
      <div class="dropdown">
        <button
            class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownMenuButton1"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            Catagories
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <?php

                $query = "SELECT * FROM categories ORDER BY id DESC ";
                $categories = query($query);
            ?>  

            <?php if (!empty($categories)) : ?>
            <?php foreach ($categories as $cat) : ?>
                <li><a class="dropdown-item" href="<?=ROOT?>/category/<?=$cat['slug']?>"><?=$cat['category']?></a></li>
            <?php endforeach; ?>
            <?php endif; ?>

          </ul>
        </div>

        <form action="<?=ROOT?>/search" class="input-group" role="search">
        <input name="find" type="search" class="form-control" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" id="button-addon2">
              Search
            </button>
          </div>
        </form>

      </div>
    </div>