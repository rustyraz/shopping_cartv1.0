//HERE IS THE FUNCTION FOR ADDING ITEM TO CART
function add_to_cart(number,product_id,from_checkoutpage){
	//we post to the shopping_cart.php add_item_to_cart function
	$.ajax({
        type:"POST",
        url:'shopping_cart.php',
        data:{
        	add_item_to_cart:product_id,
        	number_of_items:number,
        	from_checkoutpage:from_checkoutpage
        },
        beforeSend:function (request){
        	if(from_checkoutpage=="no"){
        		$('#item_'+product_id).html('Adding item....');
        	}
        },
        success:function(result){            
            if(from_checkoutpage=="yes"){
            	$('#checkout_cart_section').html(result);
            	get_the_menu_cart_summary();            	
            }else{
            	$('#item_'+product_id).html('Adding to cart');
            	$('#menu_cart_preview').html(result);
            }
            //console.log(result);
        },
        error:function (xhr, ajaxOptions, thrownError){
        	console.log('error trying to add item');
        }
    });

}
//END OF FUCNTION THAT ADDS THE ITEM TO CART

//HERE IS THE FUNCTION FOR ADDING ITEM TO CART
function remove_from_cart(number,product_id){
	//we post to the shopping_cart.php remove_item_from_cart function
	$.ajax({
        type:"POST",
        url:'shopping_cart.php',
        data:{
        	remove_item_from_cart:product_id,
        	number_of_items:number
        },
        beforeSend:function (request){
        	$('#checkout_cart_section').html('<div class="alert alert-info">Reloading the cart items. Please wait.....</div>');
        },
        success:function(result){
            $('#checkout_cart_section').html(result);
            get_the_menu_cart_summary(); 
            //console.log(result);
        },
        error:function (xhr, ajaxOptions, thrownError){
        	console.log('error trying to rempve item');
        }
    });

}
//END OF FUCNTION THAT ADDS THE ITEM TO CART

function get_the_menu_cart_summary(){
	$.ajax({
        type:"get",
        url:'shopping_cart.php',
        data:{ get_cart_summary:"get" },
        beforeSend:function (request){ },
        success:function(result){
        	$('#menu_cart_preview').html(result);
        },
        error:function (xhr, ajaxOptions, thrownError){
        	console.log('error trying to add item');
        }
    });
	//end of getting the cart summary
}

( function($) { 

	//function to validate an email
	function isValidEmailAddress(emailAddress) {
	    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	    return pattern.test(emailAddress);
	};

	

	$(document).ready(function(){

		//let get the summary of the items in the cart
		get_the_menu_cart_summary();
		

		//LOGIN FORM SUBMITTING
		$('#login_form').submit(function(e){
			e.preventDefault();

			var found_empty_field = false; //it is used to validate....always true unless proven otherwise

			var email = $.trim($('#login_email').val());
			var password = $.trim($('#login_password').val());
			if( !isValidEmailAddress( email ) ) {
				//invalid email
				$('#login_notification').removeClass('alert-info').addClass('alert-danger').html('Invalid email address entered!').show();
				return false;
                found_empty_field = true; 
			}

			if(password == ""){
				found_empty_field = true;
			}

			if(found_empty_field==true){
				$('#login_notification').removeClass('alert-info').addClass('alert-danger').html('Missing data in the form!').show();
				return false;
			}
						
			var datastring = $(this).serializeArray();//putting the data from the form into a datastring that will be sent to the login file
            var formURL = $(this).attr("action");//getting the file or url the form is posting to
            var formAction = $(this).attr('method');//could be post or get
			$.ajax({
                type:"POST",
                url:formURL,
                data:datastring,
                beforeSend:function (request){
                    $('#login_notification').addClass('alert-info').removeClass('alert-danger').html('Submiting data. Please wait.').show();
                },
                success:function(result){;
                    if(result=="Login successful!"){ 
                    	//customer was logged in....redirect page
                    	$('#login_notification').removeClass('alert-info').addClass('alert-success').html('<span class="glyphicon glyphicon-ok"></span> Data submitted successfully!');
                    	window.location.href = 'index.php';
                    }else{
                    	//customer was not able to login
                    	$('#login_notification').removeClass('alert-info').addClass('alert-danger').html(result).show();
                    }          

                },
                error:function (xhr, ajaxOptions, thrownError){
                	$('#login_notification').removeClass('alert-info').addClass('alert-danger').html('<span class="glyphicon glyphicon-warning-sign"></span> An Internal error occured when submitting the data').show();
                    
                }
            });
		    // business logic...
		    //$btn.button('reset');
		});

		
	});

} ) ( jQuery );