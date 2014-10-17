<?php

add_action( 'admin_enqueue_scripts', 'custom_admin_pointers_header' );

function custom_admin_pointers_header() {
   if ( custom_admin_pointers_check() ) {
      add_action( 'admin_print_footer_scripts', 'custom_admin_pointers_footer' );
      ?>
	  <style>
.pointerOuter{
	width:100%;
	height:100%;
	overflow:hidden;
	max-width:300px;
	
	-webkit-border-radius: 6px;
	border-radius: 6px;
	
	
}
.po-head{
	background:#46a0fc;
	color:#fff;
	padding:10px ;
	text-align:center;
	font-size:17px;
	font-weight: normal;
	font-family: Arial, sans-serif;
}
.po-innerpart{
	padding:15px 20px 25px;
	background:#fff;
}
.po-innerpart p{
	padding:0;
	margin:0 0 15px;
	font-size:14px;
	line-height:20px;
	font-weight: normal;
	font-family: Arial, sans-serif;
	color:#313131;
}
.po-iconImg{
	display:block;
	width:100%;
	height:auto;
	margin:0 auto 15px;
}
.po-email{
	width:100%;
	padding:8px 15px;
	border:solid 1px #d4d4d4;
	background:#f1f1f1;
	color:#333;
	font-size:15px;
	line-height:21px;
	font-family: Arial, sans-serif;
	margin-bottom:10px;
	outline:none;
	
	-webkit-border-radius: 3px;
	border-radius: 3px;
	
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
	-moz-box-sizing: border-box;    /* Firefox, other Gecko */
	box-sizing: border-box;         /* Opera/IE 8+ */
}
.po-subbnt{
	font-size:13px;
	line-height:17px;
	height:33px;
	font-weight:bold;
	text-transform:uppercase;
	font-family: Arial, sans-serif;
	background:#17cc46;
	color:#fff;
	border:none;
	padding:9px 17px 6px;
	margin-right:6px;
	cursor:pointer;
	
	-webkit-border-radius: 3px;
	border-radius: 3px;
}
.po-subbnt:hover{
	background:#0bab35;
}
.nobnt{
	font-size:13px;
	line-height:20px;
	height:20px;
	font-weight:bold;
	text-transform:uppercase;
	font-family: Arial, sans-serif;
	background:#343434;
	text-decoration:none;
	color:#fff;
	border:none;
	padding:9px 10px 7px;
	
	-webkit-border-radius: 3px;
	border-radius: 3px;
}
.nobnt:hover{
	background:#0c0c0c;
}
</style>  
	  <?php
      wp_enqueue_script( 'wp-pointer' );
      wp_enqueue_style( 'wp-pointer' );
   }
}

function custom_admin_pointers_check() {
   $admin_pointers = custom_admin_pointers();
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] )
         return true;
   }
}

function custom_admin_pointers_footer() {
   $admin_pointers = custom_admin_pointers();
    $pager = $_GET['page'];
	if($pager == 'page_expiration_robot_new')
	   {
   ?>
<script type="text/javascript">
/* <![CDATA[ */
( function($) {
   <?php
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] ) {
         ?>
		 		 
         $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
            content: '<?php echo $array['content']; ?>',
            position: {
            edge: '<?php echo $array['edge']; ?>',
            align: '<?php echo $array['align']; ?>'
         },
            close: function() {
               $.post( ajaxurl, {
                  pointer: '<?php echo $pointer; ?>',
                  action: 'dismiss-wp-pointer'
               } );
            }
         } ).pointer( 'open' );
         <?php
      }
   }
   ?>
   jQuery('.close').hide();
   jQuery('.nobnt').click(function(){
     $.post( ajaxurl, {
                  pointer: '<?php echo $pointer; ?>',
                  action: 'dismiss-wp-pointer'
               } );
     jQuery('.wp-pointer-content').fadeOut();			   
   })
   
   jQuery('#submitter').click(function(){       
      if(CheckForm6())
         {
		        var email = jQuery('#email').val();
				$.post( ajaxurl, {
                  pointer: '<?php echo $pointer; ?>',
                  action: 'dismiss-wp-pointer'
                } );
                jQuery.ajax({
							type:"post",
							//dataType: 'JSON',
							url:"<?php echo admin_url();?>/admin-ajax.php",
							data : {"action":"capt_test","email":email},
							success:function(result)
							{
								   //console.log(result);
                                    jQuery('.wp-pointer-content').fadeOut();	
                                    jQuery('.wp-pointer-arrow').fadeOut();									
                                   //location.href = "<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons";								   
							},
							error: function(errorThrown){
							alert('error');
							console.log(errorThrown);
				           }
				});
         }		 
   })
   console.log('<?php echo dirname(__FILE__); ?>');
   
} )(jQuery);
/* ]]> */

	      if (!Application) var Application = {};

	if (!Application.Page) Application.Page = {};

	if (!Application.Page.ClientCAPTCHA) {

		Application.Page.ClientCAPTCHA = {

			sessionIDString: '',

			captchaURL: [],

			getRandomLetter: function () { return String.fromCharCode(Application.Page.ClientCAPTCHA.getRandom(65,90)); },

			getRandom: function(lowerBound, upperBound) { return Math.floor((upperBound - lowerBound + 1) * Math.random() + lowerBound); },

			getSID: function() {

				if (Application.Page.ClientCAPTCHA.sessionIDString.length <= 0) {

					var tempSessionIDString = '';

					for (var i = 0; i < 32; ++i) tempSessionIDString += Application.Page.ClientCAPTCHA.getRandomLetter();

					Application.Page.ClientCAPTCHA.sessionIDString.length = tempSessionIDString;

				}

				return Application.Page.ClientCAPTCHA.sessionIDString;

			},

			getURL: function() {

				if (Application.Page.ClientCAPTCHA.captchaURL.length <= 0) {

					var tempURL = 'http://www.imwenterprises.com/iem5/admin/resources/form_designs/captcha/index.php?c=';

					

											tempURL += Application.Page.ClientCAPTCHA.getRandom(1,1000);

													tempURL += '&ss=' + Application.Page.ClientCAPTCHA.getSID();

												Application.Page.ClientCAPTCHA.captchaURL.push(tempURL);

									}

				return Application.Page.ClientCAPTCHA.captchaURL;

			}

		}

	}



	var temp = Application.Page.ClientCAPTCHA.getURL();
     //document.getElementById('capt_url').value=temp;
	for (var i = 0, j = temp.length; i < j; i++) 
	   var Cptimg = '<img src="' + temp[i] + '" alt="img' + i + '" />';
    //document.getElementById('captcha_img').innerHTML= Cptimg;
	 
    function CheckForm6() {

			var email_re = /[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i;

			if (!email_re.test(jQuery('#email').val())) {

				alert("Please enter your email address.");

				jQuery('#email').focus();

				return false;

			}

		

				if (jQuery('#captcha').val() == "") {

					alert("Please enter the security code shown");

					jQuery('#captcha').focus();

					return false;

				}

			

				return true;

			}

</script>
   <?php
     }
}

function custom_admin_pointers() {
   $user_id = get_current_user_id();
   $usser = get_userdata($user_id);
   $user_email = $usser->user_email;
   $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
   $version = '1_0'; // replace all periods in 1.0 with an underscore
   $prefix = 'custom_admin_pointers' . $version . '_';

   $new_pointer_content = '<h3>' . __( '8 Georgeous Countdown Designs' ) . '</h3>';
   $new_pointer_content .= '<div class="pointerOuter">' .'<div class="po-innerpart">'. '<p>'. __( 'Get this free add-on to extend Page Expiration Robot and get 8 beautiful high-converting countdown styles' ) .'</p><img src="'.trailingslashit(plugins_url()).'page-expiration-robot/images/icon-pic.jpg"  alt="" class="po-iconImg"/><input type="text" id="email" name="email" value="'.$user_email.'" class="po-email" placeholder="Enter your email address"><!--<div id="captcha_img"></div><input type="text" id="captcha" name="captcha" class="po-email" placeholder="Enter captcha"><input type="hidden" id="format" name="format" value="h" /><input type="hidden" id="capt_url" value="" />--><input type="submit" id="submitter" class="po-subbnt" value="Download Now"><a href="#" class="nobnt">No, thanks</a></div>'. '</div>';
   //$new_pointer_content .= '<div id="captcha"></div>';
   
   
   return array(
      $prefix . 'new_items' => array(
         'content' => $new_pointer_content,
         'anchor_id' => '#wp-admin-bar-new-content',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . 'new_items', $dismissed ) )
      ),
   );
}



add_action('wp_ajax_capt_test','capt_test');
add_action('wp_ajax_nopriv__capt_test','capt_test');

function capt_test()
{
    extract($_POST);
	$response = wp_remote_post( 'http://www.imwenterprises.com/iem5/form.php?form=6', array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => array( 'email' => $email, 'format' => 'h' ),
	'cookies' => array()
    )
);
	var_dump($response);	
		 /*$user_mail='per@imwenterprises.com';
	     $subject="New Subcriber Add from [PER Plugin]";
	     $message="Hi,<br/>A new User is added to the contact list with mail address $email";
	     $headers  = 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

         // Additional headers
         $headers .= 'From: Admin <per@imwenterprises.com>'. "\r\n";
	     wp_mail($user_mail,$subject,$message,$headers); 
		 echo 'success';*/
    die();
}

?>