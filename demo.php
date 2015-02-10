<?php
//header("Content-type: text/javascript");

$reply = [
	"success"=>false,
	"reason"=>"validation",
	"errors"=>[
		"first_name"=>"First name is required.",
		"last_name"=>"Last name is required.",
		"email"=>"Email is required.",
		"region"=>"Region is required.",
		"software"=>"Software is required."
		]
];

//echo json_encode($reply);exit();

if(isset($_POST)){
	echo '<pre>',print_r($_POST),'</pre>';
}