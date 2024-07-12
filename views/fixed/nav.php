<?php $navs = getNavLinks();?>
<header class="header_area sticky-header">
      <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <a class="navbar-brand logo_h" href="index.php"
              ><img src="assets/img/logo.png" alt=""
            /></a>
            <button
              class="navbar-toggler"
              type="button"
              data-toggle="collapse"
              data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div
              class="collapse navbar-collapse offset"
              id="navbarSupportedContent"
            >
              <ul class="nav navbar-nav menu_nav ml-auto">
                <?php foreach($navs as $nav):?>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?page=<?=$nav['path']?>"><?=$nav['text']?></a>
                </li>
                <?php endforeach;?>
                
                <?php if(!isset($user)):?>
                <li class="nav-item">
                      <a class="nav-link" href="index.php?page=login">Login</a>
                </li>
                <?php else:?>
                    <?php if($user['role_id']==2):?>
                    <li class="nav-item">
                      <a href="views/adminPanel/admin.php" class="nav-link">Admin Panel</a>
                    </li>
                  <?php endif;?>
                  <li class="nav-item">
                      <a class="nav-link" href="models/RegistrationLogic/logout.php">Log out</a>
                  </li>
                  <li class="nav-item nav-tag">
                    <a class="nav-link user_tag" data-id="<?= $user['user_id']?>" href="index.php?page=edit-user">My profile</a>
                    <div class="profileImg">  
                      <?php if(isset($user['profile_img'])):?>
                        <img class="image" src="assets/userImages/<?=$user['profile_img']?>" alt="">
                        
                      <?php else:?>
                        <img class="image" src="assets/userImages/man.png" alt="">
                      <?php endif; ?>
                    </div>  
                      <!-- <span class="nav-tag user_tag" data-id="<?= $user['user_id']?>">My profile</span> -->
                  </li>
                
                
                <?php endif;?>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                  <a href="index.php?page=cart" class="cart"><span class="ti-bag"></span></a>
                </li>
                <li class="nav-item">
                  <button class="search">
                    <span class="lnr lnr-magnifier" id="search"></span>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
      <div class="search_input" id="search_input_box">
        <div class="container">
          <form class="d-flex justify-content-between">
            <input
              type="text"
              class="form-control"
              id="search_input"
              placeholder="Search Here"
            />
            <button type="submit" class="btn"></button>
            <span
              class="lnr lnr-cross"
              id="close_search"
              title="Close Search"
            ></span>
          </form>
        </div>
      </div>
    </header>