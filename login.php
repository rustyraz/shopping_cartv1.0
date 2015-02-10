<?php
//we call the config.php file for db connection and session checking
include 'config.php';

//example of how to insert a customer into the table
//mysql_query("INSERT INTO customers(first_name,last_name,email,password) VALUES('Peter','Ngesh','pngesh@gmail.com','".md5("test123")."')");

$page_title = "Login page : ";
$login_page_active = "active";

//try to login the customer if he tries to login
//check js/custom.js file
if(isset($_POST['login_email'])){
	$email = mysql_real_escape_string(trim($_POST['login_email']));
	$password = mysql_real_escape_string(trim($_POST['login_password']));
	$password = md5($password);///use md5 when registering the customer
	

	//check if the customer exists
	$qry = mysql_query("SELECT * FROM customers WHERE email='$email' AND password='$password' LIMIT 1 ") or die(mysql_error());
	$number_of_found_rows = mysql_num_rows($qry);
	if($number_of_found_rows>0){
		//found customer
		$get_row = mysql_fetch_assoc($qry);
		$_SESSION['customer_id'] = $get_row['id'];
		$_SESSION['customer_first_name'] = $get_row['first_name'];
		$_SESSION['customer_last_name'] = $get_row['last_name'];
		$_SESSION['customer_email'] = $get_row['email'];
		$_SESSION['customer_is_logged'] = true;
		echo 'Login successful!';
	}else{
		//not found
		echo "Wrong email or password";
	}
	exit();//use this to stop the rest of the code below from been executed
}

//call the header file for css
include 'header.php';
?>

<div class="container">
	<?php include 'navigation_menu.php';?>
	<div class="row">

		<div class="col-md-3"></div><!--//use this to center the login form-->

		<div class="col-md-6">
			<div class="panel panel-info">
			<div class="panel-heading">
				<h4>Login Form</h4>				
			</div>
			<div class="panel-body">
			<form id="login_form" action="login.php" method="POST">
				
			
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Email:</label>
							<input class="form-control input-md" required name="login_email" id="login_email" type="email" value=""/>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label>Password:</label>
							<input class="form-control input-md" required name="login_password" id="login_password" type="password" value=""/>
						</div>
					</div>
					
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input class="btn btn-lg btn-success btn-block" type="submit" value="Login" />
						</div>
						
						<div class="alert" style="display:none;" id="login_notification"></div>
					</div>
				</div>
			</form>			
			</div>
		</div>

		<div class="col-md-3"></div><!--//use this to center the login form-->
			
		</div>
	</div>
</div><!--//end of container-->

<?php
//call the footer file to end everything
include 'footer.php';
?>