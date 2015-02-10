<?php
//we call the config.php file for db connection and session checking
include 'config.php';

$page_title = "Product Categories : ";
$categories_page_active = "active";

//call the header file for css
include 'header.php';
?>

<div class="container">
	<?php include 'navigation_menu.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Product Categories</h4>
				</div>
				<div class="panel-body">
					<?php
					//QUERY THE CATEGORIES FROM THE categories TABLE
					$qry = mysql_query("SELECT * FROM categories ORDER BY name") or die(mysql_error());
					$number_of_found_categories = mysql_num_rows($qry);
					if($number_of_found_categories>0){
						//we found some rows
						?>
						<div class="row">
						<?php
						while($category = mysql_fetch_assoc($qry)){
							?>
							<div class="col-md-3" style="text-align:center;">
								<div class="well">
									<a href="products.php?category_id=<?=$category['id']?>&name=<?=urlencode($category['name'])?>"> <h5><?=$category['name']?></h5> </a>
								</div>
								
							</div>
							<?php
						}
						?></div><?php
					}else{
						//found no categories
						?>
						<div class="alert alert-danger">No categories were found!</div>
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