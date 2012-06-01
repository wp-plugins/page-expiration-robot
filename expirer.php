<?php
/**
 * @package Akismet
 */
/*
Plugin Name: Page Expiration Robot
Plugin URI: http://www.PageExpirationRobot.com
Description: Page Expiration Robot is a free plugin for internet marketers who want to setup one-time offers and schedule their pages and posts to expire after certain amount of time to create real urgency to visitors!
Author: Internet Marketing Wizard
Author URI: http://www.InternetMarketingWizard.com/
Version: 1.2
License: GPLv2 or later

*/
//error_reporting(E_ALL);
global $pluginpath;
$pluginName = 'page-expiration-robot';
$pluginpath = get_option('siteurl')."/wp-content/plugins/$pluginName/";
function add_js_files(){

global $pluginName;

  $path = get_option('siteurl')."/wp-content/plugins/$pluginName/jquery.js";
  $path2 = get_option('siteurl')."/wp-content/plugins/$pluginName/jquery_002.js";
  $path4= get_option('siteurl')."/wp-content/plugins/$pluginName/style.css";
  wp_enqueue_style("expirer",$path4);
  $path = get_option('siteurl')."/wp-content/plugins/$pluginName/js/jquery.lwtCountdown-1.0.js";
  wp_enqueue_script("jquery_conter", $path,array('jquery'),'1.0',true);
  
  
 
 }
 
add_action("init",'add_js_files');

global $wpdb;
	
add_action( 'admin_menu', 'my_create_post_meta_box' );
add_action( 'save_post', 'my_save_post_meta_box', 10, 2 );
add_filter('the_content','add_countdown_now');
$default_url="http://www.pageexpirationrobot.com";
$methods=array(0=>"IP",1=>"Cookie",2=>"Fixed for all");
$qry= <<<DOM
CREATE TABLE IF NOT EXISTS wp_page_expiry_info (
P_Id BIGINT NOT NULL AUTO_INCREMENT,
post_id BIGINT NOT NULL,
expiry_time BIGINT NOT NULL,
method INT NOT NULL,
timestamp BIGINT,
redirection_url TEXT NOT NULL,
blog_id BIGINT NOT NULL,
expiry_date_time DATETIME NOT NULL,
PRIMARY KEY (P_Id)
);
DOM;
$qry2= <<<DUN
CREATE TABLE IF NOT EXISTS wp_page_expiry_ip (
P_Id BIGINT NOT NULL AUTO_INCREMENT,
post_id BIGINT NOT NULL,
timestamp BIGINT,
ip TEXT,
blog_id BIGINT NOT NULL,
PRIMARY KEY (P_Id)
);
DUN;
$wpdb->query($qry);
$wpdb->query($qry2);

	$query3="ALTER TABLE wp_page_expiry_info ADD blog_id BIGINT NOT NULL";
	$wpdb->query($query3);
function my_create_post_meta_box() {
	add_meta_box( 'my-meta-box', 'Page Expiration Robot', 'my_post_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'my-meta-box', 'Page Expiration Robot', 'my_post_meta_box', 'page', 'normal', 'high' );
}

function my_post_meta_box( $object, $box ) {
global $post;
$activation="";
$link="";
$expiry_method_0="";
$expiry_method_1="";
$expiry_method_2="";
$default_day="";
$default_hour="";
$default_minute="";
$default_second="";
$method_0="";
$method_1="";
$method_2="";
$id=$post->ID;
global $wpdb;
global $blog_id;
$blog_id;
global $current_blog;
$qry="SELECT * FROM wp_page_expiry_info WHERE post_id=$id && blog_id=$blog_id LIMIT 1";
$res = $wpdb->get_row($qry, ARRAY_A);
if(!empty($res)){
if((isset($res['redirection_url'])) && (strlen($res['redirection_url']) >= 1)){
$link=$res['redirection_url'];
}
 $activation=" checked='true' ";
 
 if($res['method']==0){
 $method_0=" checked='true' ";
 }
 if($res['method']==1){
 $method_1=" checked='true' ";
 }
 if($res['method']==2){
 $method_2=" checked='true' ";
 }
 $expiry_method = "";
 if($res['expiry_time']==0){
 $expiry_method_1=" checked='true' ";
 $expiry_method = 1;
 }
 else{
	 if($res['expiry_time']> 0 && $res['expiry_date_time']=="0000-00-00 00:00:00"){
	 $expiry_method_2=" checked='true' ";
	 $expiry_method = 2;
	 }
	 else
	 {
	 $expiry_method_0=" checked='true' ";
	 $expiry_method = 0;
	 }
 $time=$res['expiry_time'];
 $expires_on=$res['expiry_date_time'];
 $expires_on = str_replace(" ","-",$expires_on);
 $expires_on = str_replace(":","-",$expires_on);
 
 $days=floor($time/(24*60*60));
$hours=floor($time/(60*60));
$minutes=floor($time/60);
$seconds=floor($time);
$hours-=($days*24);
$minutes-=(($days*24*60)+($hours*60));
$seconds-=(($days*24*60*60)+($hours*60*60)+($minutes*60));

/* << By COG IT */
$exptime = $days."-".$hours."-".$minutes."-".$seconds;
/* By COG IT >> */

if($days<1){
$default_day="";
}
else{
$default_day=$days;
}
if($hours<1){
$default_hour="";
}
else{
$default_hour=$hours;
}
if($minutes<1){
$default_minute="";
}
else{
$default_minute=$minutes;
}
if($seconds<1){
$default_second="";
}
else{
$default_second=$seconds;
}
}
}

global $pluginpath;

?>
	<p>
	<script type="text/javascript" src="<?php echo $pluginpath; ?>js/jquery-ui-1.8.16.custom.min.js"></script>
 <script type="text/javascript" src="<?php echo $pluginpath; ?>js/jquery-ui-sliderAccess.js"></script>
 <script type="text/javascript" src="<?php echo $pluginpath; ?>js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo $pluginpath; ?>style/jquery-ui-1.css">
		<script type='text/javascript'>
		function numeric_entry(e)
			{
				var keynum
				var keychar
				var numcheck

				if(window.event) // IE
					{
					keynum = e.keyCode
					}
				else if(e.which) // Netscape/Firefox/Opera
					{
					keynum = e.which
					}
				keychar = String.fromCharCode(keynum)
				numcheck = /[\d\b123456789]/
				return numcheck.test(keychar)
			}
			
			function checkDefault()
				{
				document.getElementById("expiry_method").checked="checked";
				}
				
				
				
				jQuery(document).ready(function(){
					
					//COG IT TEAM FOR DATE AND TIME
		jQuery('#expiry_date_time').datetimepicker({
			showOn: "button",
   		buttonImage: "<?php echo $pluginpath; ?>images/datetime.png",
   		buttonImageOnly: true,
			showSecond: true,
			showTime: false,
				showTimepicker: false,
			dateFormat: 'mm/dd/yy',
			timeFormat: 'hh:mm:ss'
		});
   
      jQuery('#expiry_date_time1').datetimepicker({
			showOn: "button",
   		buttonImage: "<?php echo $pluginpath; ?>images/datetime.png",
   		buttonImageOnly: true,
			showSecond: true,
			showMillisec: true,
			timeOnly: true,
			hourText: 'Days',
			minuteText: 'Hour',
			secondText: 'Minute',
			millisecText: 'Second',
			timeOnlyTitle: 'Choose Period',
			hourMax: 30,
			minuteMax: 23,
			secondMax: 59,
			millisecMax: 59,
			dateFormat: 'mm/dd/yy',
			timeFormat: 'hh:mm:ss:l'
		});
					
					
					});
		</script>
		<script type="text/javascript" >
jQuery(document).ready(function(){
jQuery("#expiry_date_time").change(function(){
		
			var d=new Date();
			var dat=d.getDate();
			var months = new Array('01','02','03','04','05','06','07','08','09','10','11','12');
			var year=d.getFullYear();

			var currentdate = months[d.getMonth()]+"/"+dat+"/"+year;

			var date = jQuery("#expiry_date_time").val();
			dates = date.substring(0, 10);
			day = date.substring(3, 5);
			/*hour = date.substring(11, 13);
			minute = date.substring(14, 16);
			second = date.substring(17, 19);*/
			hour = 23;
			minute = 59;
			second = 59;
			jQuery("#expiry_hour").val(hour);
			jQuery("#expiry_minute").val(minute);
			jQuery("#expiry_second").val(second);	
			returndays = dateDiff(currentdate,dates);

			var days = returndays;

			jQuery("#expiry_day").val(days);

		});
		
	jQuery("#expiry_date_time1").change(function(){

			var period = jQuery("#expiry_date_time1").val();
			day = period.substring(0, 2);
			hour = period.substring(3, 5);
			minute = period.substring(6, 8);
			second = period.substring(9, 11);
			jQuery("#expiry_hour").val(hour);
			jQuery("#expiry_minute").val(minute);
			jQuery("#expiry_second").val(second);	
			jQuery("#expiry_day").val(day);

		});
		
		jQuery("input[name='expiry_method']").change(function(){
		
		if (jQuery("input[name='expiry_method']:checked").val() == '1') {
			
			jQuery("input.method").attr("disabled",true);
		}
		else
		{	
		jQuery("input.method").attr("disabled",false);
		  }
			jQuery('input#methodvalue').val('2');
		});
		
		jQuery('input.method').click(function(){
			jQuery('input#methodvalue').val(jQuery(this).val());
			});
			
			/*jQuery("input[name='method']").change(function(){
			
			if (jQuery("input[name='method']:checked").val() == '0' || jQuery("input[name='method']:checked").val() == '1' ) {
			
				jQuery('input#methodvalue').hide();
				}else
				{
					jQuery('input#methodvalue').show();
				}
	});*/
		
	});

function dateDiff(currentdate,dates) {
date1 = new Date();
date2 = new Date();
diff  = new Date();

date1temp = new Date(dates);
date1.setTime(date1temp.getTime());

date2temp = new Date(currentdate);
date2.setTime(date2temp.getTime());
diff.setTime(Math.abs(date1.getTime() - date2.getTime()));

timediff = diff.getTime();
days = Math.floor(timediff / (1000 * 60 * 60 * 24)); 

return days; 
}

function checkDefault()
				{
				document.getElementById("expiry_method").checked="checked";
				}
				
			function checkDefaultPeriod()
				{
				document.getElementById("expiry_method_period").checked="checked";
				}





</script>
		<input type='hidden' name='second-excerpt' id='second-excerpt' />
		<table><tr><td>
		<label for="activation">Enable plugin for this post?</label>
		</td><td><input type='checkbox' name='activation' value='1' <?php echo $activation; ?> />
		</td></tr>
		</table>
		<Br />
		<table border='0'>
		<tr><td>
		<label for="expiry_time">Expire After: </label>
		</td>
		<td>
		<input type='radio' name='expiry_method' id='expiry_method' value='0' <?php echo $expiry_method_0; ?> />
		</td>
		<td>
		<!-- cog it team -->
		<?php 
		
					$expiry_date_time=$res['expiry_date_time'];
					$ye = substr($expiry_date_time, 0, 4);
					$mo = substr($expiry_date_time, 5, 2);
					$da = substr($expiry_date_time, 8, 2);
					$ti =substr($expiry_date_time, 11, 8);
					
					if($expiry_date_time != "" && $expiry_method==0)		
					{
						$expiry_dateandtime = $mo."/".$da."/".$ye;//." ".$ti;		
					}		
					else {
						$expiry_dateandtime="";
					}
					$expiry_period = "";
					if ($expiry_method == 2)
					{
						if (strlen($days) == 1)
							$expiry_period .= "0";
						$expiry_period.= $days.":";
						if (strlen($hours) == 1)
							$expiry_period .= "0";
						$expiry_period.= $hours.":";
						if (strlen($minutes) == 1)
							$expiry_period .= "0";
						$expiry_period.= $minutes.":";
						if (strlen($seconds) == 1)
							$expiry_period .= "0";
						$expiry_period.= $seconds;
					}	
					/*
					$h = substr($ti, 0, 2);
					$m = substr($ti, 3, 2);
					$s = substr($ti, 6, 2);*/

		?>
		
		<script type="text/javascript" >
		
jQuery(document).ready(function(){
			var expiry_method = "<?php echo $expiry_method_1; ?>";
		if(expiry_method!=''){ 
		jQuery("input.method").attr("disabled",true); jQuery('input#methodvalue').val('2'); 
		}
		
	});
</script>
		
		A Specific DATE <input type="text" name="expiry_date_time"  id="expiry_date_time" value="<?php echo $expiry_dateandtime; ?>" onchange="checkDefault();"/>
		
		<input type="hidden" name='expiry_day' id="expiry_day" value="<?php echo ($default_day=="")?'0':$default_day; ?>"></input>
		<input type="hidden" name='expiry_hour' id="expiry_hour" value="<?php echo ($default_hour=="")?'0':$default_hour; ?>" ></input>
		<input type="hidden" name='expiry_minute' id="expiry_minute" value="<?php echo ($default_minute=="")?'0':$default_minute; ?>" ></input>
		<input type="hidden" name='expiry_second' id="expiry_second" value="<?php echo ($default_second=="")?'0':$default_second;?>"></input>			
		</td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input type='radio' name='expiry_method' value='2' id='expiry_method_period' <?php echo $expiry_method_2; ?> />
		</td>
		<td colspan="2">
		A Specific TIME&nbsp; <input type="text" name="expiry_date_time1"  id="expiry_date_time1" value="<?php echo $expiry_period; ?>" onchange="checkDefaultPeriod();"/>
			
		</td>
		</tr>
		<tr>
		<td></td>
		<td>
		<input type='radio' name='expiry_method' value='1' <?php echo $expiry_method_1; ?> />
		</td>
		<td>
		First visit only (show the offer once!)
		</td>
		</tr>
		</table>
		<Br />
		<Table border='0'><tr><td>
		<label for="redirection_url">Redirect URL: </label></td><td><input type='text' style='width: 300px' name='redirection_url' value='<?php echo $link; ?>' /></td>
		</tr></table><Br />
		<Table border='0'><tr><td>
		<label for="method">Expire visitors by: </label></td><td><input type='radio' class='method' readonly="readonly" name='method' value='0' <?php echo $method_0; ?> />&nbsp;<label for="method" selected>IP</label>&nbsp;&nbsp;<input type='radio' class='method' name='method' value='1' <?php echo $method_1; ?>  />&nbsp;<label for="method">Cookie</label>&nbsp;&nbsp;<input type='radio' class='method' name='method' value='2' <?php echo $method_2; ?>  />&nbsp;<label for="method">Fixed for all</label>
		<input type="hidden" id="methodvalue" name="method" value="1">
		</td></tr></table>
		<input type="hidden" name="my_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		<Br /><br /><Br />
		<div style='width:450px;'>
		OPTIONAL: Want to create 'real' urgency? <a href='http://www.pageexpirationrobot.com'>Add Integrated LIVE Counters!</a><Br />
		</div>
	</p>
<?php
}
  
function my_save_post_meta_box( $post_id, $post ) { 


	if ( !wp_verify_nonce( $_POST['my_meta_box_nonce'], plugin_basename( __FILE__ ) ) )
		return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	if(!isset($_POST['activation'])){
	global $wpdb;
	$wpdb->query("DELETE FROM wp_page_expiry_info WHERE post_id=$post_id");
	return $post_id;
	}
if(!isset($_POST['expiry_method'])){
return $post_id;
}
if(!is_numeric($_POST['expiry_method'])){
return $post_id;
}
if($_POST['expiry_method']==1){
$_POST['expiry_day']=0;
$_POST['expiry_hour']=0;
$_POST['expiry_minute']=0;
$_POST['expiry_second']=0;
}
	  
	if(!is_numeric($_POST['expiry_day']))
	  return $post_id;	
	if(!is_numeric($_POST['expiry_hour']))
	  return $post_id;	
	if(!is_numeric($_POST['expiry_minute']))
	  return $post_id;
	  	if(!is_numeric($_POST['expiry_second']))
	  return $post_id;	
	  
	if(!isset($_POST['method'])){
	$method=0;
	}
	elseif(!is_numeric($_POST['method'])){
	$method=0;
	}
	else{
	$method=addslashes($_POST['method']);
	}
	
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	/*if(!preg_match($pattern, $_POST['redirection_url'])){
		$url="";
	}
	else{
	$url=addslashes($_POST['redirection_url']);
	}
	$time=addslashes(($_POST['expiry_day']*24*60*60)+($_POST['expiry_hour']*60*60)+($_POST['expiry_minute']*60)+($_POST['expiry_second']));
	$pid=addslashes($post_id);
	if($method==2){
	$timestamp=time();
	}
	else{
	$timestamp=0;
	}*/
	
	if(!preg_match('/^(?:[;\/?:@&=+$,]|(?:[^\W_]|[-_.!~*\()\[\] ])|(?:%[\da-fA-F]{2}))*$/', $_POST['redirection_url'])){
		$url="";
	}
	else{
	$url=addslashes($_POST['redirection_url']);
	}

	if($method==1){
		$timestamp=time();
	}
	elseif($method==2){
		$timestamp=time();
	}
	else{
		$timestamp=0;
	}
	$timestamp=time();
	$expiry_date_time = $_POST["expiry_date_time"];
	
	//echo $expiry_date_time."<br />";
	$m = substr($expiry_date_time, 0, 2);
	$d = substr($expiry_date_time, 3, 2);
	$y = substr($expiry_date_time, 6, 4);
	$t =substr($expiry_date_time, 11, 8);

	//echo $month."---".$day."--".$year."<br />";
	//echo $time."<br />";
	$expiry_dateandtime = $y."-".$m."-".$d." ".$t;

	/* << By COG IT */
	if($_POST['expiry_method']==0){
		$timestamp=time();
		$endtime = strtotime($_POST["expiry_date_time"]." 23:59:59");
		$exptime = $endtime - $timestamp;
		$expiry_dateandtime = $y."-".$m."-".$d." 23:59:59";
	}
	else if($_POST['expiry_method']==2){
		$exptime = 0;
		$exptime = $_POST['expiry_day'] * 24 * 60 * 60;
		$exptime+= $_POST['expiry_hour'] * 60 * 60;
		$exptime+= $_POST['expiry_minute'] * 60;
		$exptime+= $_POST['expiry_second'];
		$expiry_dateandtime = "";
		
		
	}
	//echo date("Y-m-d H:i:s",$endtime)."====".date("Y-m-d H:i:s",$timestamp)."===".$timestamp."=======".$method;exit();
	/*$_POST['expiry_day'] = $deduct = floor($exptime/(24*60*60));
	$exptime = $exptime - ($deduct*24*60*60);
	$_POST['expiry_hour'] = $deduct = floor($exptime/(60*60));
	$exptime = $exptime - ($deduct*60*60);
	$_POST['expiry_minute'] = $deduct = floor($exptime/(60));
	$exptime = $exptime - ($deduct*60);
	$_POST['expiry_second'] = $deduct = floor($exptime);
	$time=addslashes(($_POST['expiry_day']*24*60*60)+($_POST['expiry_hour']*60*60)+($_POST['expiry_minute']*60)+($_POST['expiry_second']));*/
	$time = $exptime;


	/* By COG IT >> */	
	$pid=addslashes($post_id);
	
	global $blog_id;
	 $blog_id;
	 global $wpdb;
	global $current_blog;
	
//print_r($current_blog->domain);
	/** modified by webmask */
	//echo "INSERT INTO wp_page_expiry_info (expiry_time,redirection_url,post_id,method,timestamp,blog_id) VALUES ($time,'$url',$pid,$method,$timestamp,$blog_id)";
	global $wpdb;
	//$query3="ALTER TABLE wp_page_expiry_info ADD blog_id BIGINT NOT NULL";
	
	$query="INSERT INTO wp_page_expiry_info (expiry_time,redirection_url,post_id,method,timestamp,blog_id,expiry_date_time) VALUES ('$time','$url','$pid','$method','$timestamp','$blog_id','$expiry_dateandtime')";
	

	
	$query2="DELETE FROM wp_page_expiry_info WHERE post_id=$pid";
	//$wpdb->query($query3);
	$wpdb->query($query2);
	$wpdb->query($query);
	return $post_id;
	 $sqltest= "SELECT wp_posts.ID FROM wp_posts UNION SELECT wp_'".$blog_id ."'_posts.ID FROM wp_'".$blog_id ."'_posts WHERE `ID` = $pid AND post_status='publish'";
	//echo "SELECT *  FROM 'wp_posts.ID' ,'wp_'" . $blog_id . "'_posts.ID' WHERE `ID` = $pid AND post_status='publish'" ;
	$query_published  = mysql_query($sqltest);
	
	if(mysql_num_rows($query_published) <= 0) { return "";}
	/** modified by webmask */

		global $blog_id;
     $blog_id;
	global $current_blog;
	
//print_r($current_blog->domain);
}


$dys = ""; $hys = ""; $mys = ""; $sys ="";$link="";

function add_countdown_now($poster){
global $default_url, $pluginName;
if(is_home()){
return $poster;
}
global $post;
global $dys,$hys,$mys,$sys,$link;
global $wpdb;
$id=$post->ID;
$qry="SELECT * FROM wp_page_expiry_info WHERE post_id=$id LIMIT 1";
$info = $wpdb->get_row($qry, ARRAY_A);
if(!$info){
return $poster;
}
if(!is_array($info)){
return $poster;
}
if(!isset($info['P_Id'])){
return $poster;
}
if(!is_numeric($info['P_Id'])){
return $poster;
}
//print_r($info);
//echo $left_time=($info['timestamp']+$info['expiry_time'])-time();
//die;
/*if(isset($info['timestamp'])){

if($info['timestamp']>0){
$expiry_timestamp=$info['timestamp']+$info['expiry_time'];
$timeleft=$expiry_timestamp-time();
if($timeleft<=0){
if((!isset($info['redirection_url'])) || (strlen($info['redirection_url']) < 1)){
$link=$default_url;
}
else{
$link=$info['redirection_url'];
}
$scr= <<<DOM
<script type='text/javascript'>
window.location="$link";
</script>
DOM;
return $scr;
}
}
}
*/
$time=$info['expiry_time'];
$expirer_date_time=$info['expiry_date_time'];/* new add*/


if($info['method']==2){
	
	$left_time=($info['timestamp']+$info['expiry_time'])-time();
	if($left_time<=0){
		if((!isset($info['redirection_url'])) || (strlen($info['redirection_url']) < 1)){
		$link=$default_url;
		}
		else{
			$link=$info['redirection_url'];
		}
$scr= <<<DOM
<script type='text/javascript'>
window.location="$link";
</script>
DOM;
		return $scr;
	}
	$time=$left_time;
}


//for ip  

if($info['method']==0){
$id=$post->ID;

//$_SERVER['REMOTE_ADDR']
$ip=addslashes($_SERVER["REMOTE_ADDR"]);

$qry="SELECT * FROM wp_page_expiry_ip WHERE post_id=$id AND ip='$ip' LIMIT 1";
$res = $wpdb->get_row($qry, ARRAY_A);
if(empty($res)){
$post_id=$post->ID;
$mtr=time();
$qry="INSERT INTO wp_page_expiry_ip (post_id,ip,timestamp) VALUES ($post_id,'$ip',$mtr)";
$wpdb->query($qry);
}
else{
 $left_time=($res['timestamp']+$info['expiry_time'])-time();
if($left_time<=0){
if((!isset($info['redirection_url'])) || (strlen($info['redirection_url']) < 1)){
$link=$default_url;
}
else{
$link=$info['redirection_url'];
}
$scr= <<<DOM
<script type='text/javascript'>
window.location="$link";
</script>
DOM;
return $scr;

}
else{
$time=$left_time;
}
}
}

if($info['method']==1){
$id=$post->ID;

if(!isset($_COOKIE['expirer_timestamp_'.$id])){
$tme=time();
$setcookie= <<<DOM
<script type='text/javascript'>
c_name="expirer_timestamp_$id";
value="$tme";
exdays=50;
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
</script>
DOM;
$poster.=$setcookie;
}
elseif(!is_numeric($_COOKIE['expirer_timestamp_'.$id])){
$tme=time();
$setcookie= <<<DOM
<script type='text/javascript'>
c_name="expirer_timestamp_$id";
value="$tme";
exdays=50;
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
</script>
DOM;
$poster.=$setcookie;
}
else{
$timestamp=$_COOKIE['expirer_timestamp_'.$id];
$expiry_timestamp=$timestamp+$info['expiry_time'];
$time=$expiry_timestamp-time();
if($expiry_timestamp<=time()){
if((!isset($info['redirection_url'])) || (strlen($info['redirection_url']) < 1)){
$link=$default_url;
}
else{
$link=$info['redirection_url'];
}
$scr= <<<DOM
<script type='text/javascript'>
window.location="$link";
</script>
DOM;
return $scr;
}
}
}

$days=floor($time/(24*60*60));
$hours=floor($time/(60*60));
$minutes=floor($time/60);
$seconds=floor($time);
$hours-=($days*24);
$minutes-=(($days*24*60)+($hours*60));
$seconds-=(($days*24*60*60)+($hours*60*60)+($minutes*60));
if($days<1){
$dy="";
}
else{
$dy="$days day(s), ";
}
if($hours<1){
$hr="";
}
else{
$hr="$hours hour(s), ";
}
if($minutes<1){
$min="";
}
else{
$min="$minutes minute(s), ";
}
if($seconds<1){
$sec="0 second";
}
else{
$sec="$seconds second(s)";
}
if((!isset($info['redirection_url'])) || (strlen($info['redirection_url']) < 1)){
$link=$default_url;
}
else{
$link=$info['redirection_url'];
}
$micro_sec=$time*1000;
if($info['expiry_time']>0){
$dys=$days."";
$hys=$hours."";
$mys=$minutes."";
$sys=$seconds."";
if($days<=9){
$dys="0".$days;
}
if($hours<=9){
$hys="0".$hours;
}
if($minutes<=9){
$mys="0".$minutes;
}
if($seconds<=9){
$sys="0".$seconds;
}
$poster.= <<<DOM
<Br />
<!-- changes made by webmask on 16th May 2011--->
<script type='text/javascript'>
jQuery.noConflict();
jQuery(document).ready(function(){
  jQuery(document).countDown({
    stepTime: 60,
    format: "dd:hh:mm:ss",
    startTime: "$dys:$hys:$mys:$sys",
    digitWidth: 53,
    digitHeight: 77,
    timerEnd: function() { alert("Sorry but this offer has just expired!"); window.location='$link'; },
    image: "wp-content/plugins/$pluginName/digits.png"
  });
  });
  
  
</script>
DOM;
}
else{
$poster.="<Br /><Br />Offer will expire after your exit this page or refresh your browser";
}

return $poster;
}