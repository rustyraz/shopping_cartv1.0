<?php
//we call the config.php file for db connection and session checking
include 'config.php';

$page_title = "Products : ";
$products_page_active = "active";

//check for the category id
if(isset($_GET['category_id'])){
	$category_id = mysql_real_escape_string(trim($_GET['category_id']));
	$category_name = trim(urldecode($_GET['name']));
}else{
	//the category id was not set in the url
	header("Location: categories.php");
	exit();
}


//call the header file for css
include 'header.php';
?>

<div class="container">
	<?php include 'navigation_menu.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Products under <?=$category_name?></h4>
				</div>
				<div class="panel-body">
					<?php
					//QUERY THE CATEGORIES FROM THE categories TABLE
					$qry = mysql_query("SELECT * FROM products WHERE category_id='$category_id' ORDER BY name") or die(mysql_error());
					$number_of_found_products = mysql_num_rows($qry);
					if($number_of_found_products>0){
						//we found some rows
						?>
						<div class="row">
						<?php
						while($product = mysql_fetch_assoc($qry)){
							?>
							<div class="col-md-3" style="text-align:center;">
								<div class="well">
									<div class="thumbnail">									
										<img src="images/product_images/<?=$product['image']?>">
									</div>
									<h5><?=$product['name']?></h5>
									<label><b>Price : $<?=number_format($product['price'],2,'.',',')?></b></label>
									<a href="#" id="item_<?=$product['id']?>" onclick="add_to_cart(1,<?=$product['id']?>,'no')" class="btn btn-info btn-block">Add to cart</a>
								</div>
								
							</div>
							<?php
						}
						?></div><?php
					}else{
						//found no products
						?>
						<div class="alert alert-danger">No products were found!</div>
						<?php
					}
					?>
				</div>
			</div>
			
		</div>
	</div>
</div><!--//end of container-->

<?php
//call the footer file to end everything
include 'footer.php';
?>