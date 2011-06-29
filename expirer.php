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

$pluginName = 'page-expiration-robot';
function add_js_files(){

global $pluginName;

  $path = get_option('siteurl')."/wp-content/plugins/$pluginName/jquery.js";
  $path2 = get_option('siteurl')."/wp-content/plugins/$pluginName/jquery_002.js";
  $path4= get_option('siteurl')."/wp-content/plugins/$pluginName/style.css";
  wp_enqueue_style("expirer",$path4);
  
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
 if($res['expiry_time']==0){
 $expiry_method_1=" checked='true' ";
 }
 else{
 $expiry_method_0=" checked='true' ";
 $time=$res['expiry_time'];
 $days=floor($time/(24*60*60));
$hours=floor($time/(60*60));
$minutes=floor($time/60);
$seconds=floor($time);
$hours-=($days*24);
$minutes-=(($days*24*60)+($hours*60));
$seconds-=(($days*24*60*60)+($hours*60*60)+($minutes*60));

if($days<1){
$default_day="";
}
else{
$default_day="<option value='$days' selected>$days</option>";
}
if($hours<1){
$default_hour="";
}
else{
$default_hour="<option value='$hours' selected>$hours</option>";
}
if($minutes<1){
$default_minute="";
}
else{
$default_minute="<option value='$minutes' selected>$minutes</option>";
}
if($seconds<1){
$default_second="";
}
else{
$default_second="<option value='$seconds' selected>$seconds</option>";
}
}
}
?>
	<p>
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
		<select name='expiry_day' style='width:70px' onchange="checkDefault();">
		<option value='0'>day</option>
		<?php echo $default_day; ?>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		<option value='11'>11</option>
		<option value='12'>12</option>
		<option value='13'>13</option>
		<option value='14'>14</option>
		<option value='15'>15</option>
		<option value='16'>16</option>
		<option value='17'>17</option>
		<option value='18'>18</option>
		<option value='19'>19</option>
		<option value='20'>20</option>
		<option value='21'>21</option>
		<option value='22'>22</option>
		<option value='23'>23</option>
		<option value='24'>24</option>
		<option value='25'>25</option>
		<option value='26'>26</option>
		<option value='27'>27</option>
		<option value='28'>28</option>
		<option value='29'>29</option>
		<option value='30'>30</option>
		<option value='31'>31</option>
		</select>
		<select name='expiry_hour' style='width:70px' onchange="checkDefault();">
		<option value='0'>hour</option>
		<?php echo $default_hour; ?>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		<option value='11'>11</option>
		<option value='12'>12</option>
		<option value='13'>13</option>
		<option value='14'>14</option>
		<option value='15'>15</option>
		<option value='16'>16</option>
		<option value='17'>17</option>
		<option value='18'>18</option>
		<option value='19'>19</option>
		<option value='20'>20</option>
		<option value='21'>21</option>
		<option value='22'>22</option>
		<option value='23'>23</option>
		</select>
		<select name='expiry_minute' style='width:70px' onchange="checkDefault();">
		<option value='0'>minute</option>
		<?php echo $default_minute; ?>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		<option value='11'>11</option>
		<option value='12'>12</option>
		<option value='13'>13</option>
		<option value='14'>14</option>
		<option value='15'>15</option>
		<option value='16'>16</option>
		<option value='17'>17</option>
		<option value='18'>18</option>
		<option value='19'>19</option>
		<option value='20'>20</option>
		<option value='21'>21</option>
		<option value='22'>22</option>
		<option value='23'>23</option>
		<option value='24'>24</option>
		<option value='25'>25</option>
		<option value='26'>26</option>
		<option value='27'>27</option>
		<option value='28'>28</option>
		<option value='29'>29</option>
		<option value='30'>30</option>
		<option value='31'>31</option>
		<option value='32'>32</option>
		<option value='33'>33</option>
		<option value='34'>34</option>
		<option value='35'>35</option>
		<option value='36'>36</option>
		<option value='37'>37</option>
		<option value='38'>38</option>
		<option value='39'>39</option>
		<option value='40'>40</option>
		<option value='41'>41</option>
		<option value='42'>42</option>
		<option value='43'>43</option>
		<option value='44'>44</option>
		<option value='45'>45</option>
		<option value='46'>46</option>
		<option value='47'>47</option>
		<option value='48'>48</option>
		<option value='49'>49</option>
		<option value='50'>50</option>
		<option value='51'>51</option>
		<option value='52'>52</option>
		<option value='53'>53</option>
		<option value='54'>54</option>
		<option value='55'>55</option>
		<option value='56'>56</option>
		<option value='57'>57</option>
		<option value='58'>58</option>
		<option value='59'>59</option>
		</select>
		<select name='expiry_second' style='width:70px' onchange="checkDefault();">
		<option value='0'>second</option>
		<?php echo $default_second; ?>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		<option value='11'>11</option>
		<option value='12'>12</option>
		<option value='13'>13</option>
		<option value='14'>14</option>
		<option value='15'>15</option>
		<option value='16'>16</option>
		<option value='17'>17</option>
		<option value='18'>18</option>
		<option value='19'>19</option>
		<option value='20'>20</option>
		<option value='21'>21</option>
		<option value='22'>22</option>
		<option value='23'>23</option>
		<option value='24'>24</option>
		<option value='25'>25</option>
		<option value='26'>26</option>
		<option value='27'>27</option>
		<option value='28'>28</option>
		<option value='29'>29</option>
		<option value='30'>30</option>
		<option value='31'>31</option>
		<option value='32'>32</option>
		<option value='33'>33</option>
		<option value='34'>34</option>
		<option value='35'>35</option>
		<option value='36'>36</option>
		<option value='37'>37</option>
		<option value='38'>38</option>
		<option value='39'>39</option>
		<option value='40'>40</option>
		<option value='41'>41</option>
		<option value='42'>42</option>
		<option value='43'>43</option>
		<option value='44'>44</option>
		<option value='45'>45</option>
		<option value='46'>46</option>
		<option value='47'>47</option>
		<option value='48'>48</option>
		<option value='49'>49</option>
		<option value='50'>50</option>
		<option value='51'>51</option>
		<option value='52'>52</option>
		<option value='53'>53</option>
		<option value='54'>54</option>
		<option value='55'>55</option>
		<option value='56'>56</option>
		<option value='57'>57</option>
		<option value='58'>58</option>
		<option value='59'>59</option>
		</select>
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
		<label for="method">Expire visitors by: </label></td><td><label for="method" selected>IP</label>&nbsp;<input type='radio' name='method' value='0' <?php echo $method_0; ?> />&nbsp;&nbsp;<label for="method">Cookie</label>&nbsp;<input type='radio' name='method' value='1' <?php echo $method_1; ?>  />&nbsp;&nbsp;<label for="method">Fixed for all</label>&nbsp;<input type='radio' name='method' value='2' <?php echo $method_2; ?>  />
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
if($_POST['expiry_method']>0){
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
	if(!preg_match($pattern, $_POST['redirection_url'])){
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
	}
	
	global $blog_id;
	 $blog_id;
	 global $wpdb;
	global $current_blog;
	
//print_r($current_blog->domain);
	/** modified by webmask */
	//echo "INSERT INTO wp_page_expiry_info (expiry_time,redirection_url,post_id,method,timestamp,blog_id) VALUES ($time,'$url',$pid,$method,$timestamp,$blog_id)";
	global $wpdb;
	//$query3="ALTER TABLE wp_page_expiry_info ADD blog_id BIGINT NOT NULL";
	
	$query="INSERT INTO wp_page_expiry_info (expiry_time,redirection_url,post_id,method,timestamp,blog_id) VALUES ($time,'$url',$pid,$method,$timestamp,$blog_id)";
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
	
print_r($current_blog->domain);}


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
//print_r($info);die;
if(isset($info['timestamp'])){

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

$time=$info['expiry_time'];
if($info['method']==2){
$time=$timeleft;
}
if($info['method']==0){
$id=$post->ID;
$ip=addslashes($_SERVER['REMOTE_ADDR']);
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

jQuery(document).ready(function($){
  $('#counter').countDown({
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