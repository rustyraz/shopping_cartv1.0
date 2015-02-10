<?php
//we call the config.php file for db connection and session checking
include 'config.php';

$page_title = "Chekout Page : ";
$checkout_page_active = "active";

//call the header file for css
include 'header.php';
?>

<div class="container">
	<?php include 'navigation_menu.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Shopping Cart items</h4>
				</div>
				<div class="panel-body" id="checkout_cart_section">
					<?php include 'shopping_cart.php';?>
				</div>
			</div>
			
		</div>
	</div>
</div><!--//end of container-->

<?php
//call the footer file to end everything
include 'footer.php';
?>