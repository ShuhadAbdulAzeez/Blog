
  <style>
    .user-image {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    .navbar-custom {
        margin: 0 300px;
        height: 90px;
    }
    .navbar-custom .login-link {
        margin-left: 45px;
    }
  </style>

<nav class="navbar navbar-expand-sm navbar-light navbar-custom">
    <div class="container-fluid justify-content-between">
        <div>
            <a href="#" class="navbar-brand text-uppercase">journey</a>
        </div>

        <div class="d-flex align-items-center">
            <ul class="navbar-nav mr-3">
                <li class="nav-item active">
                    <a href="<?=ROOT?>" class="nav-link text-uppercase">Home</a>
                </li>
                <li class="nav-item">
                    <a href="<?=ROOT?>/blog" class="nav-link text-uppercase">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="<?=ROOT?>/contact" class="nav-link text-uppercase">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <?php if(logged_in()): ?>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?=get_image(user('image'))?>" alt="User Image" class="user-image" style="object-fit: cover;" width="32" height="32">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="<?=ROOT?>/admin">Admin</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?=ROOT?>/logout">Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <div class="nav-item login-link">
                  <a class="nav-link" href="<?=ROOT?>/login" style="color: #333;">Login</a>
                </div>
                <?php endif; ?>  
            </div>
        </div>
    </div>
</nav>

  <?php
  
  if($url[0] == 'home'){
    include '../app/pages/includes/slider.php'; 
  }
  ?>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"
></script>