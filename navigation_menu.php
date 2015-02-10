<div class="masthead">
	<h2 class="text-muted"></h2>
	<!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Simple shopping Cart</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="<?php echo @$home_page_active; ?>"><a href="index.php">Home</a></li>
               <li class="<?php echo @$categories_page_active; ?>"><a href="categories.php">Product Categories</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="<?php echo @$checkout_page_active; ?>"><a style="color:#5bc0de !important; font-weight:bold;" href="checkout.php" id="menu_cart_preview">(0) item(s) : $0.00</a></li>
              <?php
              if($the_customer_has_logged_in==true){
              	?>
              	<li><a href="logout.php">Logout</a></li>
              	<?php
              }else{
              	//customer is not logged in
              	?>
              	<li><a href="register.php">Register</a></li>
				        <li class="<?php echo @$login_page_active;?>"><a href="login.php">Login</a></li>
              	<?php
              }
              ?>
              
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
</div>