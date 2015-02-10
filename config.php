<?php
//check for session
if(!isset($_SESSION)){
	session_start();
}

//get the db connection settings
//Make changes where needed to connect to database
$server_name = "127.0.0.1";//localhost
$user = "root";
$password = "";//premier_231
$database_name = "shopping_cart_db";

$db_connection = mysql_connect($server_name,$user,$password) or die("Failed to connect to the database!");

if(!$db_connection){
	die("Could not connect to the database!");
} 
	
if(!mysql_select_db($database_name,$db_connection)){
 	die("No database selected.");
}


//CHECK FOR CUSTOMER LOGIN SESSION
if(isset($_SESSION['customer_is_logged'])){
	//we check if ths variable is set to true else the customer seems not to be logged in
	if($_SESSION['customer_is_logged'] == true){
		$the_customer_has_logged_in = true;
	}else{
		$the_customer_has_logged_in = false;
	}
}else{
	//the customer is not logged in
	$the_customer_has_logged_in = false;
}


?>