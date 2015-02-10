<?php
//this file will be used to add, update, read and delete items in the shopping cart

include 'config.php';

if(!isset($_SESSION)){
	session_start();
}

//BELOW IS THE FUNCTION TO CREATE A UNIQUE code for each shopping cart
function shopping_cart_unique_code() { 
    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $code = '' ; 

    while ($i <= 10) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $code = $code . $tmp; 
        $i++; 
    } 

    return $code; 
} 
//END OF THE random code function

//function to get the summary of items in the cart
function get_cart_summary($order_id){
	$qry_order = mysql_query("SELECT product_qty,product_price FROM order_items WHERE order_id='".mysql_real_escape_string(trim($order_id))."'") or die(mysql_error());
	$qry_order_num = mysql_num_rows($qry_order);
	if($qry_order_num>0){
		$total_price = 0;
		$total_qty = 0;
		while($item_row = mysql_fetch_assoc($qry_order)){
			$total_price += ($item_row['product_qty']*$item_row['product_price']);//updating the calculated total
			$total_qty += $item_row['product_qty'];
		}
		if($qry_order_num==1){$many = "";}else{$many="s";};
		return $total_qty." item".$many." : $".number_format($total_price,2,'.',',');

	}else{
		//cart has nothing so lets unset the cart code
		mysql_query("DELETE FROM orders WHERE `unique_code`='".mysql_real_escape_string($_SESSION['cart_unique_code'])."'");
		unset($_SESSION['cart_unique_code']);
		return '0 items : $0.00';
	}
}

//We will only initialize a cart when a use clicks on the add to cart button
function initialize_cart(){
	//every order or active cart has to have a unique code stored in a session variable
	if(!isset($_SESSION['cart_unique_code'])){
		//looks like we do not have an active cart code so let create one
		$_SESSION['cart_unique_code'] = shopping_cart_unique_code();
	}


	//check if there is an order with that code in the orders table
	$qry_order = mysql_query("SELECT * FROM orders WHERE `unique_code`='".mysql_real_escape_string($_SESSION['cart_unique_code'])."' LIMIT 1") or die(mysql_error());
	$number_of_qry_order = mysql_num_rows($qry_order);

	if($number_of_qry_order>0){
		//we have found an order
		$qry_order_row = mysql_fetch_assoc($qry_order);
		$order_id = $qry_order_row['id'];
	}else{
		//found no order with that code....so we create a new one
		$qry = mysql_query("INSERT INTO orders(`unique_code`) VALUES('".mysql_real_escape_string($_SESSION['cart_unique_code'])."')") or die(mysql_error());
		$last_inserted_id = mysql_insert_id();
		$order_id = $last_inserted_id;
	}

	return $order_id;

} //end of function that initializes the cart	



//IF THE CUSTOMER IS LOGGED IN LETS UPDATE THE ORDERS TABLE
if(isset($_SESSION['customer_id'])){
	$order_id = initialize_cart();
	mysql_query("UPDATE orders SET customer_id='".mysql_real_escape_string(trim($_SESSION['customer_id']))."' WHERE id='$order_id'");
}

//when getting the summary if the items in the cart
if(isset($_POST['get_cart_summary']) || isset($_GET['get_cart_summary'])){
	//this is if we want to get the sumary of the cart for the menu part only
	if(!isset($_SESSION['cart_unique_code'])){
		//we do not have a cart initiated so return 0
		echo '0 items : $0.00';
	}else{
		//we have a cart session
		$order_id = initialize_cart();
		echo get_cart_summary($order_id);
	}	
	exit();
}

//when adding an item into the cart by clicking the "Add to cart" button
if(isset($_POST['add_item_to_cart'])){
	//we get an order id from the initiating function
	$order_id = initialize_cart();

	//adding an item into cart after clicking the ADD TO CART buttom
	$item_id = mysql_real_escape_string(trim($_POST['add_item_to_cart']));
	//check if item exists in cart
	$qry = mysql_query("SELECT * FROM order_items WHERE order_id = '$order_id' AND product_id='$item_id' LIMIT 1");
	$qry_number = mysql_num_rows($qry);
	if($qry_number>0){
		//found an item in that cart so we will update the number of items
		$qry_row = mysql_fetch_assoc($qry);
		$current_number_of_items = $qry_row['product_qty'];
		$update_qty_to = ($current_number_of_items+1);//since we are adding one item at a time
		mysql_query("UPDATE order_items SET `product_qty`='$update_qty_to' WHERE id = '".$qry_row['id']."'") or die(mysql_error());
	}else{
		//this item is not in that cart so we insert it into the order_items table
		$qry_item = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1") or die(mysql_error());
		$item_row = mysql_fetch_assoc($qry_item);
		$stmt = "INSERT INTO order_items(order_id,product_id,product_name,product_qty,product_price) 
		VALUES('".mysql_real_escape_string($order_id)."','".mysql_real_escape_string($item_row['id'])."','".mysql_real_escape_string($item_row['name'])."','1','".mysql_real_escape_string($item_row['price'])."')";
		mysql_query($stmt) or die(mysql_error());
	}
	if($_POST['from_checkoutpage']=='no'){
		//we return the summary of the cart only
		if(!isset($_SESSION['cart_unique_code'])){
		//we do not have a cart initiated so return 0
			echo '0 items : $0.00';
		}else{
			//we have a cart session
			echo get_cart_summary($order_id);
		}	
		exit();
	}else{
		//the request is from the checkout page
	}
	
}//end of adding item ro cart

//removeing item from cart
if(isset($_POST['remove_item_from_cart'])){
	//get the order id
	$order_id = initialize_cart();

	$item_id = mysql_real_escape_string(trim($_POST['remove_item_from_cart']));
	//check if item exists in cart
	$qry = mysql_query("SELECT * FROM order_items WHERE order_id = '$order_id' AND product_id='$item_id' LIMIT 1");
	$qry_number = mysql_num_rows($qry);
	if($qry_number>0){
		//found an item in that cart so we will update the number of items
		$qry_row = mysql_fetch_assoc($qry);
		$current_number_of_items = $qry_row['product_qty'];
		$update_qty_to = ($current_number_of_items-1);//since we are adding one item at a time
		if($current_number_of_items==1){
			//we delete the item from cart since we only have one last one 
			mysql_query("DELETE FROM order_items WHERE id = '".$qry_row['id']."'") or die(mysql_error());
		}else{
			mysql_query("UPDATE order_items SET `product_qty`='$update_qty_to' WHERE id = '".$qry_row['id']."'") or die(mysql_error());
		}
		
	}
}

if(isset($_SESSION['cart_unique_code'])){
	//WE HAVE A CART CODE SO THIS MEANS WE CAN DO AHEAD AN GET THE ITEMS FROM THE DB
	$order_id = initialize_cart();	

	//GET THE ITEMS IN THE CART
	$stmt = "SELECT * FROM order_items WHERE order_id='$order_id'" ;
	$qry_items = mysql_query($stmt) or die(mysql_error());
	$number_of_items = mysql_num_rows($qry_items);
	if($number_of_items>0){
		//found some items
		?>
		<table class="table">
			<thead>
			<tr>
				<th>Quantity</th>
				<th>Item Name</th>
				<th style="text-align:right;">Price</th>
				<th style="text-align:right;">Total</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				while ($item_row = mysql_fetch_assoc($qry_items)) {
					# code...
					$total += ($item_row['product_qty']*$item_row['product_price']);
					?>
					<tr>
						<td >
							<a class="btn btn-success" onclick="remove_from_cart(1,<?=$item_row['product_id']?>,'yes')" > <span class="glyphicon glyphicon-minus"></span></a>
							<b style="font-size:1.3em;"><?=$item_row['product_qty']?></b>
							<a class="btn btn-success" onclick="add_to_cart(1,<?=$item_row['product_id']?>,'yes')" > <span class="glyphicon glyphicon-plus"></span></a>
						</td>
						<td><?=$item_row['product_name']?></td>
						<td align="right">$<?=$item_row['product_price']?></td>
						<td align="right">$<?=($item_row['product_price']*$item_row['product_qty'])?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="3" align="right"><h5><b>TOTAL : </b></h5></td>
					<td align="right"><h5><b>$<?=number_format($total,2,'.',',')?></b></h5></td>
				</tr>
			</tbody>
		</table>
		<?php
	}else{
		//
		mysql_query("DELETE FROM orders WHERE id='$order_id'");
		unset($_SESSION['cart_unique_code']);
		?>
		<table class="table">
			<tbody>
				<tr>
					<td>
						<div class="alert alert-danger"><h4 align="center"><span class="glyphicon glyphicon-shopping-cart"></span><br/>No items found in the cart!</h4></div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}else{
	//NO INITIATED CART SO NO ITEMS CAN BE FOUND
	?>
	<table class="table">
		<tbody>
			<tr>
				<td>
					<div class="alert alert-danger"><h4 align="center"><span class="glyphicon glyphicon-shopping-cart"></span><br/>No items found in the cart!</h4></div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}
?>