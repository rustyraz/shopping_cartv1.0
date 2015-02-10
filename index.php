<?php
//we call the config.php file for db connection and session checking
include 'config.php';

$page_title = "Home : ";
$home_page_active = "active";

//call the header file for css
include 'header.php';
?>

<div class="container">
	<?php include 'navigation_menu.php';?>
	<div class="row">
		<div class="col-md-12">
			<h2>Hello <?php
			if($the_customer_has_logged_in==true){
				echo $_SESSION['customer_first_name'];
			}else{
				echo 'Guest';
			}
			?></h2>
			
		</div>
	</div>
</div><!--//end of container-->

<?php
//call the footer file to end everything
include 'footer.php';
?>