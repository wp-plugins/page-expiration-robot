<?php
ob_start();
error_reporting(1);
/*
Plugin Name: Page Expiration Robot
Plugin URI: http://www.PageExpirationRobot.com
Description: The official #1 most powerful, scarcity free countdown plugin ever created for WordPress to create evergreen campaigns to expire posts AND pages on a visitor-by-visitor basis!
Version: 3.1.4
Author: IMW Enterprises
Author URI: http://www.IMWenterprises.com/
License: GPLv2 or later
*/
?>
<?php
/*
***** Filters available *****
per_print_expiry_date_select_css_class	-	to print css class name for expiry date selection box to show this box on expiry method													selection
per_print_expiry_time_select_css_class	-	to print css class name for expiry date selection box to show this box on expiry method													selection
per_get_counter_expire_addon			-	to print javascript code for action to be performed on counter finish like show splash image												or time contents
per_get_counter							-	to print html and javascript of counter (default counter html must be returned in filter												hooked function if method condition does not match.)
per_print_counter_finish_js				-to add action after counter expire- pass values- $NoOfShortcode and												$campaign_id,	Default return -Blank.Return-js code.
get_counter_text						- to add text or html before counter.dafault:blank
per_get_expiry_action					- filter to change after counter expiry action (javascript)
										arguments :$counter_expire,$day,$hrs,$mins,$secs,$campaign_id.
										dafault $counter_expire
'per_expire_visiters_addon'				-to get new time setting argument array($day,$hrs,$mins,$secs), $campaign_id,											"", $info,
										default: $day,$hrs,$mins,$secs.
get_redirect_link						-to change redirect link arguments $link,$campaign_id,
										default $link
'per_onexpirejscode						-filter to do action before counter load if time left is zero .,
										arguments -$onexpirejscode,$campaign_id,$link
										default:onexpirejscode. return js code .
 per_get_counter_position_addon			-return css class like top_fixed arguments:$cssClass,$info['position']
										dafault $cssClass.
 v
***** Filters available *****
per_save_expiry_method_data				- Action to save  data coming from add-ons.-pass value -campaign id 
per_before_action						-Action to do bafore counter starts. -pass value:campaign id.
per_get_counterHtml_addon'				-action to get html iffor new  expiry event argument :$info['event']
campaign form  actions:
per_print_expiry_visiters_options:		-action to print expiry visiters options like expire by ip.
per_print_expiry_method_options			-action to print expiry method options like for action reach prind additional											fields.
per_print_expiry_event_select_opt		-action to print expiry  event options like stay on same page.
per_print_counter_position_select_opt	-print counter position options like header/footer
print_customization_option				-print counter customization options like ring color.
print_counter_style_options				-option to print counter style options like flip,slidedown.
get_counter_demo						-dispaly counter demo of selected counter admin
*/
?>
<?php
/* Remember to develop a utility to migrate from old version to this new version. Specially related to database tables. */
//---Main class for Page Expiration Robot---
/**/
require_once(ABSPATH .'wp-includes/pluggable.php');
include("timezones.php");
if(!class_exists('PageExpirationRobot'))
{
	class PageExpirationRobot
	{
		static $PluginBase;
		static $PluginName;
		static $PluginDir;
		static $PluginURL;
		static $UploadAddonPath;
		static $Namespace = "page_expiration_robot";
		static $CampaignPostType = "per_campaign";
		static $MetaPrefix = "per_";
		static $AddOnFolder = "addons";
		static $DBTableIP = "wp_page_expiry_ip";
		static $ShortCode = "COUNTDOWN";
		var $NoOfShortcodes;
		var $InstalledAddOns;
		static $NoOfShortcode;
		static $DefaultCOunter;
		static $atts;
		function __construct() {
			global $wpdb;
			$this->PluginBase =  plugin_basename( __FILE__ );
			$this->PluginName = trim( dirname( $this->PluginBase ), '/' );
			$this->PluginDir = WP_PLUGIN_DIR . '/' . $this->PluginName;
			PageExpirationRobot::$PluginURL = $this->PluginURL = WP_PLUGIN_URL . '/' . $this->PluginName;
			$this->NoOfShortcodes = 0;
			$this->Namespace = "page_expiration_robot";
			$this->CampaignPostType = "per_campaign";
			$this->ShortCode = "COUNTDOWN";
			$this->MetaPrefix = "per_";
			$this->AddOnFolder = "addons";
			$this->InstalledAddOns = unserialize(get_option($this->MetaPrefix."_addons"));
			$this->DBTableIP = $wpdb->prefix."page_expiry_ip";
			PageExpirationRobot::$DefaultCOunter=get_option($this->MetaPrefix."_default_counter");
            ///// Upload folder related customization starts from Here ///////
            $per_upload_dir = wp_upload_dir(); 
            $per_addon_main_path = $per_upload_dir['basedir'];
            $total_addons_path = $per_addon_main_path.'/'.'per_addons';
            $this->UploadAddonPath = $total_addons_path;
            if(!is_dir($total_addons_path))
            {
            	mkdir($total_addons_path, 0777);
            }
            else
            {
            	
            	if($this->checkFolderIsEmptyOrNot($total_addons_path))  // check if uploads folder not empty
            	{            	
            	    if(!$this->checkFolderIsEmptyOrNot($this->PluginDir."/".$this->AddOnFolder))
            	    {
		            	    $this->recurs_copy($total_addons_path,$this->PluginDir."/".$this->AddOnFolder); // Copy all the Addons from temporary cache to Addons folder
			                if ( $handle = opendir ( $this->PluginDir."/".$this->AddOnFolder ) ) 
			                   {
						        while ( false !== ( $file = readdir ( $handle ) ) ) {
						            if ( $file != "." && $file != ".." ) {
						                $files [] = $file;
						            }
						        }
						        closedir ( $handle );
						       }
						    foreach($files as $f) 
						    {  
									$this->unzip($f, $this->PluginDir."/".$this->AddOnFolder);
						            @unlink($this->PluginDir."/".$this->AddOnFolder."/".$f);
                                    
                                    $addOnSplitter = explode('.',$f);
					                $addonMainname = $addOnSplitter[0];
					                $this->InstalledAddOns[$addonMainname]['install'] = 1;
									$this->InstalledAddOns[$addonMainname]['act'] = 1;
                            }
							update_option($this->MetaPrefix."_addons", serialize($this->InstalledAddOns));
                    
                    }
                }
            }
            ///// Upload folder related customization ends at Here ///////
			// Hooks
			add_action('admin_menu', array($this,'admin_menu'));
			add_shortcode($this->ShortCode,array($this,"set_contdown"));		
			add_action('init', array($this,'init'));
			$this->loadAddOns();
		}
		function recurs_copy($src,$dst) 
        {
			    $dir = opendir($src);
			    
			    while(false !== ( $file = readdir($dir)) ) {
			        if (( $file != '.' ) && ( $file != '..' )) {
			            if ( is_dir($src . '/' . $file) ) {
			                $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
			            }
			            else {
			                copy($src . '/' . $file,$dst . '/' . $file);
			            }
			        }
			    }
			    closedir($dir);
	    }
        function checkFolderIsEmptyOrNot ( $folderName )
        {
			    $files = array ();
			    if ( $handle = opendir ( $folderName ) ) {
			        while ( false !== ( $file = readdir ( $handle ) ) ) {
			            if ( $file != "." && $file != ".." ) {
			                $files [] = $file;
			            }
			        }
			        closedir ( $handle );
			    }
			    return ( count ( $files ) > 0 ) ?  TRUE: FALSE;
		}
		
		function loadAddOns()
		{
			if (is_array($this->InstalledAddOns))
			{
				foreach ($this->InstalledAddOns as $AddOnName => $AddOn)
				{
					if ($this->getAddOnProperty($AddOnName,'act'))
					{
						if (file_exists($this->PluginDir."/".$this->AddOnFolder.'/'.$AddOnName.'/'.$AddOnName.'.php'))
						{
							include($this->AddOnFolder.'/'.$AddOnName.'/'.$AddOnName.'.php');
							$AddOnName = ucwords(str_replace("_", " ",$AddOnName));
							$AddOnName = str_replace(" ", "",$AddOnName);
							$AddOnName = "PER".$AddOnName;
							$actionReach = new $AddOnName();
						}
						else
						{
							$this->InstalledAddOns[$AddOnName]['install'] = 0;
							$this->InstalledAddOns[$AddOnName]['act'] = 0;
							update_option($this->MetaPrefix."_addons", serialize($this->InstalledAddOns));
						}
					}
				}
			}
		}
		function getAddOnProperty($addOn, $Property)
		{
			if (isset($this->InstalledAddOns[$addOn]))
			{
				if (isset($this->InstalledAddOns[$addOn][$Property]))
					return $this->InstalledAddOns[$addOn][$Property];
				else
					return 0;
			}
			else
				return 0;
		}
		function set_default_values($AddOnName){
			if (file_exists($this->PluginDir."/".$this->AddOnFolder.'/'.$AddOnName.'/'.$AddOnName.'.php'))
			{
				include('./'.$this->AddOnFolder.'/'.$AddOnName.'/'.$AddOnName.'.php');
				$AddOnName = ucwords(str_replace("_", " ",$AddOnName));
				$AddOnName = str_replace(" ", "",$AddOnName);
				$AddOnName = "PER".$AddOnName;
				$actionReach = new $AddOnName();
				if(is_callable(array($actionReach,"defaultvalues"))) {
					$arr=$actionReach->defaultvalues();
					//print_r($arr);
					if(!empty($arr)){
						foreach($arr as $arr){
							$args = array(
								'post_type'	  => $this->CampaignPostType,
								'meta_key'=>$this->MetaPrefix.$arr['meta'],
								'meta_value'=>$arr['value']
							);
							query_posts( $args );
							while ( have_posts() ) : the_post();
								echo $id=get_the_ID();
								update_post_meta( $id,$this->MetaPrefix.$arr['meta'],$arr['default']);
							endwhile;
						}
					}
				}
			}
		}
		function init()
		{
			/* Add Stylesheet in admin and in fornend */
			
			add_action('wp_enqueue_scripts',array($this,'front_scripts'));
			wp_enqueue_script('jquery');
			wp_register_script( 'jquery-migrate', plugins_url( '/jcountdown/jquery-migrate-1.2.1.min.js', __FILE__ ), array('jquery'), 1,true);
		
			add_action( 'admin_enqueue_scripts', array($this,'front_scripts')); 
			wp_register_script( 'per_admin_js', plugins_url( '/js/admin.js', __FILE__ ), array('jquery'), 1, true);
			wp_register_script( 'per_js', plugins_url( '/js/page_expiration_robot.js', __FILE__ ), array('jquery'));
			wp_register_script( 'per_Colorpicker_js', plugins_url( '/js/cogmColorPicker.js', __FILE__ ), array('jquery'), 1, true);
			wp_register_script( 'per_datepicker_js', plugins_url( '/js/jquery-ui-cog-timepicker-addon.js', __FILE__ ), array('jquery'), 1, true);
			//wp_register_script( 'per_datetimepicker_js', plugins_url( '/js/jquery-ui-datepicker-addon.js', __FILE__ ), array('jquery'), 1, false);
			wp_register_style( 'per_Colorpicker_css', plugins_url('css/colorpicker.css', __FILE__) );
			wp_register_style( 'per_clock_timer_css', plugins_url('css/main.css', __FILE__) );
			wp_register_script( 'per_custom_min', plugins_url( '/js/jquery-ui-1.9.2.custom.min.js', __FILE__ ), array('jquery'), 1, false);
			/* ajax action to save and list whitelist ip's */
			add_action('wp_ajax_per_edit_ip', array($this,'expirer_edit_ip_list'));
			wp_enqueue_script("jquery-migrate");
			
			/* 
			Register campaign as custom post type 
			Title will be post title and other settings will be meta of the post
			*/
			$labels = array(
				'name'               => 'Campaigns',
				'singular_name'      => 'Campaigns',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Campaign',
				'edit_item'          => 'Edit Campaign',
				'new_item'           => 'New Campaign',
				'all_items'          => 'Campaigns',
				'view_item'          => 'View Campaign',
				'search_items'       => 'Search Campaigns',
				'not_found'          => 'No Campaign found',
				'not_found_in_trash' => 'No Campaign found in Trash',
				'parent_item_colon'  => '',
				'menu_name'          => 'campaigns'
			  );
			$args = array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'campaign' ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'exclude_from_search'=>	true,
				'supports'			 => array('title','author','custom-fields')
			  );
			  if(get_option( 'PER_30day_remind_notice') == ''){
				add_option( 'PER_30day_remind_notice', current_time('timestamp').'/first', '', 'no' );
			  }
			register_post_type( $this->CampaignPostType, $args );
			//add create new notification on admin load
			
		}
		
		/* Function to add PER Menu in admin */
		function admin_menu() 
		{
			add_menu_page( __( 'Page Expiration Robot', $this->Namespace), __( 'Page Expiration Robot', $this->Namespace ), 'edit_others_posts', $this->Namespace, array($this,'campaigns'),$this->PluginURL."/images/per_icon.png");/*,$this->PluginURL."/images/icon.jpg"*/
			add_submenu_page($this->Namespace, __( 'Campaigns', $this->Namespace ), __( 'Campaigns', $this->Namespace ), 'edit_others_posts', $this->Namespace, array($this,'campaigns'));
			add_submenu_page($this->Namespace, __( 'Add New Campaign', $this->Namespace ), __( 'Add New Campaign', $this->Namespace ), 'edit_others_posts', $this->Namespace."_new", array($this,'new_campaign'));
			add_submenu_page($this->Namespace, __( 'Browse Add ons', $this->Namespace ), __( 'Browse Add ons', $this->Namespace ), 'edit_others_posts', $this->Namespace."_addons", array($this,'add_ons'));
			add_submenu_page($this->Namespace, __( 'Settings', $this->Namespace ), __( 'Settings', $this->Namespace ), 'edit_others_posts', $this->Namespace."_settings", array($this,'settings')); 
			add_submenu_page($this->Namespace, __( 'Help/Support', $this->Namespace ), __( 'Help', $this->Namespace ), 'edit_others_posts', $this->Namespace."_help", array($this,'help_func')); 
			add_action('admin_print_scripts', array($this, 'admin_scripts'));
		}
		/* Add Stylesheet and js file in front view */
		function front_scripts()
		{
			wp_enqueue_style("per_main_css",$this->PluginURL."/css/style.css");
			wp_enqueue_style( 'per_clock_timer_css');
			wp_register_script( 'per_default_js', plugins_url( '/js/jquery.lwtCountdown-1.0.js', __FILE__ ), array('jquery'), 1, true);
			wp_enqueue_script("per_default_js");
			//for defoult jquery coundown
			wp_register_script( 'per_default_countdown_style_js_custom', plugins_url( '/js/jcountdown/custom.js', __FILE__ ), array('jquery'), 1, true);
			wp_enqueue_script("per_default_countdown_style_js_custom");
			wp_register_script( 'per_default_countdown_js_min', plugins_url( '/js/jcountdown/jquery.jcountdown.min.js', __FILE__ ), array('jquery'), 1, true);
			wp_enqueue_script("per_default_countdown_js_min");
			wp_enqueue_style( 'default_countdown_css', plugins_url('/js/jcountdown/jcountdown.css', __FILE__) );
		}
		/* function  to delete uninstall addon */
		function rrmdir($dir) { 
			if (is_dir($dir)) { 
				$objects = scandir($dir); 
				foreach ($objects as $object) { 
					if ($object != "." && $object != "..") { 
						if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
					} 
				} 
				reset($objects); 
				rmdir($dir); 
			} 
		} 
		/* Get PER settting i.e Whitelist ip's*/
		function settings(){	
			include("settings.php");
		}
		function help_func(){
			header('Location:http://www.pageexpirationrobot.com/v2/plugin-support/
');
		}
		/* Load Add-ons list from XML File*/
		function add_ons()
		{
			include('addons.php');
		}
		function Campaigns(){
			if (isset($_GET['pid'])){
				/* Edit Campaign */
				$this->edit_campaign($_GET['pid']);
			}
			else{
				/*Get Campaign list */
			include('campaign_list.php');
			}
		}
		/* function ti Edit Campaign */
		function edit_campaign($posstid){
			include('campaign_edit_form.php');
		}
		/* Function to attach stylesheet and Js file in admin */
		function admin_scripts()
		{
			wp_enqueue_style($this->Namespace."_css",$this->PluginURL."/css/style.css");
			wp_enqueue_style( 'per_Colorpicker_css' );
			if (!wp_style_is( "jquery-ui", 'enqueued' ))
				wp_enqueue_style("jquery-ui",$this->PluginURL."/css/jquery-ui.css");
			//if (!wp_script_is( "jquery-ui-datepicker", 'enqueued' ))
			//	wp_enqueue_script("jquery-ui-datepicker");
			wp_enqueue_script("per_admin_js");
			wp_enqueue_script("per_Colorpicker_js");
			wp_enqueue_script("per_datepicker_js");
			//wp_enqueue_script("per_datetimepicker_js");
			//wp_enqueue_script("per_cogmColorpicker_js");
			wp_enqueue_script("per_custom_min");
			// if(!wp_style_is( "per_counter_flip_js", 'enqueued' ))
			// {
			// 	//wp_enqueue_style( 'per_clock_timer_css');
			// }
			
			wp_localize_script('per_admin_js', 'per_plugin_url', $this->PluginURL);
            wp_enqueue_script( 'custom-scripthandle-', plugins_url( '<span class="skimlinks-unlinked">custom-script.js</span>', __FILE__ ), array( 'wp-color-picker' ), false, true );  
			//for defoult jquery coundown
			wp_register_script( 'per_default_countdown_style_js_custom', plugins_url( '/js/jcountdown/custom.js', __FILE__ ), array('jquery'), 1, true);
			wp_enqueue_script("per_default_countdown_style_js_custom");
			wp_register_script( 'per_default_countdown_js_min', plugins_url( '/js/jcountdown/jquery.jcountdown.min.js', __FILE__ ), array('jquery'), 1, true);
			wp_enqueue_script("per_default_countdown_js_min");
			wp_enqueue_style( 'default_countdown_css', plugins_url('/js/jcountdown/jcountdown.css', __FILE__) );
		}
		static function getCampaignCounterStyle($PostId)
		{
			global $post;
			if (!isset($PostId) || $PostId == "")
				$PostId = $post->ID;
			/*if ( PageExpirationRobot::$CampaignPostType == $_POST['post_type'] ) {
				return;
			}*/
			/*if ( wp_is_post_revision( $PostId ) )
				return;*/
			$Pattern = get_shortcode_regex();
			if (   preg_match_all( '/'. $Pattern.'/s', $post->post_content, $Matches ) && array_key_exists( 2, $Matches ) && in_array( PageExpirationRobot::$ShortCode, $Matches[2] ) )
			{
				// shortcode is being used
				preg_match('#id="(.*)"#',$Matches[3][0],$IdMatches);
				if (array_key_exists(1, $IdMatches))
				{	
					$CampaignId = $IdMatches[1];
					$CounterStyle = get_post_meta($CampaignId, PageExpirationRobot::$MetaPrefix.'counter_style', true);
					return $CounterStyle;
				}
			}
		}
		/* Function TO create new campaign*/
		function new_campaign()
		{
			if (isset($_POST['campaign_name'])){
				$this->save_campaign();
				exit();
			}
			/* Declare blank variable for new campaign */
			$expiry_method= "";
			$expiry_date="";
			$expiry_date_time_days="";
			$expiry_date_time_hrs="";
			$expiry_date_time_mins="";
			$expiry_date_time_secs="";
			$method="";
			$position="";
			$event="";
			$redirection_url="";
			$splash_url="";
			//Newly Added
			$color_num = "";
			$back_color = "";
			$counter_size = "";
			$alignment = "";
			$myhexcode = "";	
			$myhexcode1= "";
			$days_label = "";
			$hours_label = "";
			$min_label = "";
			$sec_label = "";
			$label_color = "";
			$shadow_color = "";
			$hide_day_label = "";
			$hide_hrs_label = "";
			$hide_mins_label = "";
			$hide_sec_label = "";
			
			$AllowAdd = true;
			$count_campaign = wp_count_posts($this->CampaignPostType)->publish;
			if($count_campaign >= 1 && $this->getAddOnProperty('unlimited','act') == 0){
				$AllowAdd = false;
			}
			include('campaign_form.php');	
			
			
		}
		/* function to save campaign */
		function save_campaign()
		{	/* Save  New campaign  as post in campaign post type */
			$campaign = array(
			  'post_title'    => wp_strip_all_tags( $_POST['campaign_name'] ),
			  'post_content'  => "",
			  'post_status'   => 'publish',
			  'post_author'   => get_current_user_id(),
			  'post_type'	  => $this->CampaignPostType	
			);
			/* Update Post */
			if (isset($_GET['pid']) && $_GET['pid'] > 0)
			{
				$campaign['ID'] = $_GET['pid'];
				wp_update_post( $campaign  );
				$campaign_id = $_GET['pid'];
			}
			else
			{
				$campaign_id = wp_insert_post( $campaign );
			}
			/*Save Campaign details as Post Meta*/
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_method', $_POST['expiry_method']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_date', $_POST['expiry_date']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'time_zone', $_POST["time_zone"]);
			if($_POST['expiry_date_time_days']=="")$_POST['expiry_date_time_days']=0;
			if($_POST['expiry_date_time_hrs']=="")$_POST['expiry_date_time_hrs']=0;
			if($_POST['expiry_date_time_mins']=="")$_POST['expiry_date_time_mins']=0;
			if($_POST['expiry_date_time_secs']=="")$_POST['expiry_date_time_secs']=0;
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_date_time_days', $_POST['expiry_date_time_days']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_date_time_hrs', $_POST['expiry_date_time_hrs']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_date_time_mins', $_POST['expiry_date_time_mins']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'expiry_date_time_secs', $_POST['expiry_date_time_secs']);
			if(isset($_POST['method'])){
			update_post_meta( $campaign_id, $this->MetaPrefix.'method', $_POST['method']);
			}
			update_post_meta( $campaign_id, $this->MetaPrefix.'position', $_POST['position']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'event', $_POST['event']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'redirection_url', $_POST['redirection_url']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'redirect_m_url', $_POST['redirect_m_url']);
			update_post_meta( $campaign_id, $this->MetaPrefix.'splash_url', $_POST['splash_url']);
			update_post_meta($campaign_id, $this->MetaPrefix.'color_num',$_POST['color_num']);
			update_post_meta($campaign_id, $this->MetaPrefix.'back_color',$_POST['back_color']);
			update_post_meta($campaign_id, $this->MetaPrefix.'counter_size',$_POST['counter_size']);
			update_post_meta($campaign_id, $this->MetaPrefix.'alignment',$_POST['alignment']);
			update_post_meta($campaign_id, $this->MetaPrefix.'myhexcode',$_POST['myhexcode']);	
			update_post_meta($campaign_id, $this->MetaPrefix.'myhexcode1',$_POST['myhexcode1']);
			update_post_meta($campaign_id, $this->MetaPrefix.'days_label',$_POST['days_label']);
			update_post_meta($campaign_id, $this->MetaPrefix.'hours_label',$_POST['hours_label']);
			update_post_meta($campaign_id, $this->MetaPrefix.'min_label',$_POST['min_label']);
			update_post_meta($campaign_id, $this->MetaPrefix.'sec_label',$_POST['sec_label']);
			update_post_meta($campaign_id, $this->MetaPrefix.'label_color',$_POST['label_color']);
			update_post_meta($campaign_id, $this->MetaPrefix.'shadow_color',$_POST['shadow_color']);
			update_post_meta($campaign_id,$this->MetaPrefix.'hide_day_label',0);
			update_post_meta($campaign_id,$this->MetaPrefix.'hide_hrs_label',0);
			update_post_meta($campaign_id,$this->MetaPrefix.'hide_mins_label',0);
			update_post_meta($campaign_id,$this->MetaPrefix.'hide_sec_label',0);
			update_post_meta($campaign_id,$this->MetaPrefix.'counter_style',$_POST['counter_style']);
			
            //***** For Banner content to Save ****///////
            if(!isset($_GET['pid']))
            {
		            $banner_conts = $_SESSION['banner_conts'];
		            $banner_color = $_SESSION["banner_color"];
		            $banner_text = $_SESSION["banner_text"];
					
				if( empty($banner_conts)){
						$banner_conts = '<div class="bannerBox ui-sortable" id="sortable">
							<div class="dragbox">
							  <div class="timer"> <span class="dragblue-text editText mce-content-body" id="mce_0" contenteditable="true" spellcheck="false">This offer will expire in:</span><input type="hidden" name="mce_0"> </div>
							  <div class="dragbox-upper ui-sortable-handle"> Drag<a href="#">X</a> </div>
							</div>
							<div class="dragbox timerBox">
							  <div class="timer">
								<div class="timer-day"> <span class="timerday-text">00 </span>
								  <div class="timer-label">days </div>
								</div>
								<div class="timer-day"> <span class="timerday-text">00 </span>
								  <div class="timer-label">hours </div>
								</div>
								<div class="timer-day"> <span class="timerday-text">00 </span>
								  <div class="timer-label">mins </div>
								</div>
								<div class="timer-day"> <span class="timerday-text">00 </span>
								  <div class="timer-label">secs </div>
								</div>
							  </div>
							  <div class="dragbox-upper ui-sortable-handle"> Drag </div>
							</div>
							<div class="dragbox dragboxText">
							  <div class="timer"> <span class="dragblue-text editText mce-content-body" id="mce_1" contenteditable="true" spellcheck="false">Text</span><input type="hidden" name="mce_1"> </div>
							  <div class="dragbox-upper ui-sortable-handle"> Drag <a href="#">X</a> </div>
							</div>
						   
							<div class="dragbox dragboxText">
							  <div class="timer"> <span class="dragyellow-text editText mce-content-body" id="mce_2" contenteditable="true" spellcheck="false">Text</span><input type="hidden" name="mce_2"> </div>
							  <div class="dragbox-upper ui-sortable-handle"> Drag<a href="#">X</a> </div>
							</div>
							
						  </div>';

					}
		            add_post_meta( $campaign_id, 'per_banner', $banner_conts );
		            add_post_meta( $campaign_id, 'per_banner_color', $banner_color );
		            add_post_meta( $campaign_id, 'per_banner_text', $banner_text );
		            unset($_SESSION["banner_conts"]);
		            unset($_SESSION["banner_color"]);
		            unset($_SESSION["banner_text"]);
            }
			/* Action TO Save data from Add-ons */
			do_action('per_save_expiry_method_data',$campaign_id);		
			/* If Hide label is checkedsave hide label data */
			if (isset ($_POST['hid_label']))
			foreach($_POST['hid_label'] as $key=>$value){
				update_post_meta($campaign_id, $this->MetaPrefix.$key,$value);
			}
			/* if after expiry event is show own Image then save Image file */
			if($_POST['event']==2) {
				$filename = basename($_FILES['splash-image']['name']);
				$upload_dir = $this->PluginDir;				
				$ext = substr($filename, strrpos($filename, '.') + 1);
				$temp = explode(".", $_FILES["splash-image"]["name"]);
				$extension = end($temp);
				$extension = end($temp);
				//Determine the path to which we want to save this file
				$newname = $upload_dir.'/images/'.$filename ;
				//Check if the file with the same name is already exists on the server
				if (!file_exists($newname)) {
					//Attempt to move the uploaded file to it's new place
					if ((move_uploaded_file($_FILES['splash-image']['tmp_name'],$newname ))) {
					}
					else {
						echo "Error: A problem occurred during file upload!";
					}
				}
				else {
					move_uploaded_file($_FILES['splash-image']['tmp_name'],$newname );
				} 
			} 
			if (!isset ($_GET['pid'])){
			wp_redirect( "admin.php?page=page_expiration_robot&pid=".$campaign_id."&message=6", 301 );
			 exit;
			}
			if (isset ($_GET['pid'])){
			wp_redirect( "admin.php?page=page_expiration_robot&pid=".$campaign_id."&updated=1", 301 );
			 exit;
			}	
						
		}
		/* function to show blank counter if method is one time expire*/
		function get_counter_style_none($DefaultCounter, $day, $hrs, $mins, $secs, $link, $image, $cssClass, $alignCss, $display_counter, $sizeClass, $info,$campaign_id)
		{ 
			return "";
		}
		
		/* Function for expire by cookie */
		function expire_by_cookie($campaign_id, $onetime="", $info=array())
		{
			$day=isset($info['day'])?$info['day']:get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_days',true);
			$hrs=isset($info['hrs'])?$info['hrs']:get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_hrs',true);
			$mins=isset($info['mins'])?$info['mins']:get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_mins',true);
			$secs=isset($info['secs'])?$info['secs']:get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_secs',true);
			$current_time =$mtr=time();
			
			/* For first time save cookie expirer_timestamp_campid with current time value */
			if(!isset($_COOKIE['expirer_timestamp_'.$campaign_id])){
				/*echo "<script type='text/javascript' >var expiration_date = new Date();
var cookie_string = '';
expiration_date.setFullYear(expiration_date.getFullYear() + 1);
c_name='expirer_timestamp_".$campaign_id."';value='".$mtr."';document.cookie=c_name + '=' + escape(value);'expires='+expiration_date;</script>";*/
				setcookie("expirer_timestamp_".$campaign_id, $mtr,time()+(86400*365));
				if($onetime=="onetime"){ /* For one time return null*/
				return "";
				}
				else{/*Return time remaining */
					return array($day,$hrs,$mins,$secs);
				}
			}
			else{
				if($onetime=="onetime"){ /* if expiry method is for one time then execute after expiry event */				
					$onetime_html="<script>jQuery(window).load(function(){showExpireAction".$campaign_id."(".PageExpirationRobot::$NoOfShortcode.");});</script>";
					return $onetime_html;
				}
				else{	/* calculate remainig time and return time */				
					$time_stamp_cookie =$_COOKIE['expirer_timestamp_'.$campaign_id];
					$expire_date= strtotime("+$day day +$hrs hours + $mins minute +$secs secs",$time_stamp_cookie);	
					$time=$expire_date-$current_time;
					if ($time > 0){
						$day=floor($time/(24*60*60));
						$hours=floor($time/(60*60));
						$minutes=floor($time/60);
						$seconds=floor($time);
						$hrs=$hours-=($day*24);
						$mins=$minutes-=(($day*24*60)+($hours*60));
						$secs=$seconds-=(($day*24*60*60)+($hours*60*60)+($minutes*60));
						return array($day,$hrs,$mins,$secs);
					}
					else {
						list($day,$hrs,$mins,$secs)=array(0,0,0,0);			
						return array($day,$hrs,$mins,$secs);
					}
				}
			}
		}
		/* Function for expire by cookie End Here */
		
		/* Action After counter expire (js code)*/
		function after_counter_expire($link,$info,$campaign_id)
		{	
			
			/*Apply Filter to get new event settings from addon */
			
			$html="<script language='javascript' type='text/javascript'>function showExpireAction".$campaign_id."(counter){document.cookie='refresh_per_reset_".$campaign_id."=".$campaign_id."';";
			$html .="
		
					jQuery.ajax({  
					  type: 'POST',
					  url: '". trailingslashit(admin_url()) ."admin-ajax.php',
					  data: { action:'expired_once',campid:". $campaign_id."}
					})
					  .done(function( msg ) {
						console.log( 'Test from IP First' );
						
					  });  
					
					";
			$html.=apply_filters('per_print_counter_finish_js','',PageExpirationRobot::$NoOfShortcode,$campaign_id);
			
			if($info['expiry_method'] != 1)
			{
				if ($link!="" && $info['event']==0){
					 $html.= 'window.location="'.$link.'";';
				}
			} 
		if($info['expiry_method'] == 0){
			
			$html .= "document.cookie='campaign_id_rev_date_".$campaign_id."=".$campaign_id."';";
			
	  	  if($_COOKIE['campaign_id_rev_date_'.$campaign_id] == $campaign_id)
			{ 
			
                if ($link!="" && ($info['event']==0 || $info['event']==1 || $info['event']==2 || $info['expiry_method'] == 1 || $info['expiry_method'] == 2)){
					$html.= 'window.location="'.$link.'";'; 
				}
				
			}
			
		}
		
		$reset_counter=get_post_meta( $campaign_id, $this->MetaPrefix.'reset_counter', true);
		//if counter expired and redirect option is on redirect to set href
		if($reset_counter == "reset_counter" && $_COOKIE['refresh_per_reset_'.$campaign_id] == $campaign_id && $info['event'] == 0){
			
			$html.= 'window.location="'.$link.'";'; 
			
		}
		
		$no_of_times=get_post_meta($campaign_id, $this->MetaPrefix.'no_of_times', true);
		$reset=true;

		/* << Check no of time remaining if 0 then make reset false */
		if($no_of_times>-2){
			$no_of_times_executed=get_post_meta($campaign_id, $this->MetaPrefix.'no_of_times_executed', true);
			if($no_of_times_executed=="")$no_of_times_executed=0;
			$no_of_time1=$no_of_times-$no_of_times_executed;
			if($no_of_time1<=-1)$reset=false;
		}
	  if($info['expiry_method'] == 1 || $info['expiry_method'] == 2)
	  {
	  	  $method = $info['method'];
	  	  if($method == 'ip')
	  	  { 
			 if( $reset_counter=="reset_counter" && $reset==true){
			  
				  $html .= "";
				  
			  }else{
		 		  $html .="
		
					jQuery.ajax({
					  type: 'POST',
					  url: '". trailingslashit(admin_url()) ."admin-ajax.php',
					  data: { action:'firstvisit',campid:". $campaign_id."}
					})
					  .done(function( msg ) {
						console.log( 'Test from IP First' );
						
					  });  
					
					";
			  }
              $ip = $_SERVER['REMOTE_ADDR'];
              $ip_chk = get_post_meta($campaign_id,'first_visit_ip',true);
              $ip_arr = explode(',',$ip_chk);
              if(in_array($ip,$ip_arr) && $info['event'] != 3 && $info['event'] != 5)
              {
				  
				$html.= 'window.location="'.$link.'";'; 
			
              }
	  	  }
	  	  else
	  	  	  { 
		  if( $reset_counter=="reset_counter" && $reset==true){
			  
				  $html .= "";
				  
			  }else{
				$html .= "document.cookie='campaign_id_rev".$campaign_id."=".$campaign_id."';";
			  }
	  	  if($_COOKIE['campaign_id_rev'.$campaign_id] == $campaign_id && $info['event'] != 3 && $info['event'] != 5)
			{ 			
              
				$html.= 'window.location="'.$link.'";'; 
				
			}
		}

	  }
	  
	   if($info['expiry_method'] == 1)
		  {
			  
			$html.= 'window.location="'.$link.'";'; 
		
		  }
			if ($info['event']!=""){
				if($info['expiry_method'] != 3)
				{
				   $html.= "jQuery('#complete_info_message_'+counter).slideDown();";
			    }
			}
			if(($info['event'] != 3) && ($info['event'] != 5))
			{
      			$html.="new_imager(".$campaign_id.");";
            }
			$html.= "}</script>";
            if(isset($_SESSION['id_sett']) && $_SESSON['id_sett'] == $campaign_id)
			{
				
                $html .= '<script type="text/javascript">window.location="'.$_SESSION['redirect_m_url'].'";</script>';
				
			}
			$events = $info['event'];
			$expiry_method = $info['expiry_method'];
			
			return $html;
		}		
		/* function to display  Short code i.e countdown */			
		function set_contdown( $atts,$hideCounter = false )
		{
			global $wpdb;
			/* Increase NoOfShortcodes value */
			$this->NoOfShortcodes++;
			PageExpirationRobot::$NoOfShortcode++;
			extract( shortcode_atts( array('id' => 'Blank value',), $atts ) );
			PageExpirationRobot::$atts=$atts;
			$campaign_id = $id;
			/*If campaign is deleted return false */
			if( get_post_status( $campaign_id )=="trash")
			return false;
			$info['expiry_method'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_method',true);
			/*First Action */
			do_action('per_before_action',$campaign_id);
			if ($info['expiry_method']!= "")
			{	/* Get Related Values of Campaign */
				$info['expiry_time'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date',true);
				$info['redirection_url'] = get_post_meta($campaign_id, $this->MetaPrefix.'redirection_url',true);	
				$info['redirect_m_url'] = get_post_meta( $campaign_id, $this->MetaPrefix.'redirect_m_url', true);
				$info['event'] = get_post_meta($campaign_id, $this->MetaPrefix.'event',true);	
				$info['position'] = get_post_meta($campaign_id, $this->MetaPrefix.'position',true);		
				$info['splash_url'] = get_post_meta($campaign_id, $this->MetaPrefix.'splash_url',true);	
				$day=$info['day'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_days',true);
				$hrs=$info['hrs'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_hrs',true);
				$mins=$info['mins'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_mins',true);
				$secs=$info['secs'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time_secs',true);
				$info['expiry_date_time'] = get_post_meta($campaign_id, $this->MetaPrefix.'expiry_date_time',true);
				$info['expiry_method'] = get_post_meta( $campaign_id, $this->MetaPrefix.'expiry_method',true);
				$info['method'] = get_post_meta( $campaign_id, $this->MetaPrefix.'method',true);
				
				$info['color_num'] = get_post_meta($campaign_id, $this->MetaPrefix.'color_num',true);
				$info['back_color'] = get_post_meta($campaign_id, $this->MetaPrefix.'back_color',true);
				$info['counter_size']= get_post_meta($campaign_id, $this->MetaPrefix.'counter_size',true);
				$info['alignment'] = get_post_meta($campaign_id, $this->MetaPrefix.'alignment',true);
				$info['myhexcode'] = get_post_meta($campaign_id, $this->MetaPrefix.'myhexcode',true);	
				$info['myhexcode1']= get_post_meta($campaign_id, $this->MetaPrefix.'myhexcode1',true);
				$info['days_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'days_label',true);
				$info['hours_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'hours_label',true);
				$info['min_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'min_label',true);
				$info['sec_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'sec_label',true);
				$info['label_color'] = get_post_meta($campaign_id, $this->MetaPrefix.'label_color',true);
				$info['shadow_color'] = get_post_meta($campaign_id, $this->MetaPrefix.'shadow_color',true);
				$info['hide_day_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'hide_day_label',true);
				$info['hide_hrs_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'hide_hrs_label',true);
				$info['hide_mins_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'hide_mins_label',true);
				$info['hide_sec_label'] = get_post_meta($campaign_id, $this->MetaPrefix.'hide_sec_label',true);
				/* Check user ip is not in whitelist */
				$ip=addslashes($_SERVER['REMOTE_ADDR']);
				$whitelist = unserialize(get_option("per_white_list"));
				$whitelisted = false;
				if (isset($whitelist) && is_array($whitelist))
				{
					if (in_array($ip, $whitelist))
					{
						$whitelisted = true; 
					}
				}
				$link="";
				$image = "";
				$counterHtml="";
				$display_counter=true;
				
				switch($info['expiry_method']){ /* Get time depending upon expiry method */
					case 2:  /* << for specific amount of time */
						if (!$whitelisted )
						{
							
							list($day,$hrs,$mins,$secs)=$this->expire_by_cookie($campaign_id,"",$info);
							list($day,$hrs,$mins,$secs)=apply_filters('per_expire_visiters_addon',array($day,$hrs,$mins,$secs),$campaign_id,"",$info);
						}
						else{
							
							list($day,$hrs,$mins,$secs)=array($day,$hrs,$mins,$secs);
						}
					break;				
					case 0:	/* << for specific date calculete time remaining */
						$info['time_zone'] = get_post_meta($campaign_id, $this->MetaPrefix.'time_zone',true);
						$expires_on = str_replace("/",":",$info['expiry_time']);
						//$newyear = substr($info['expiry_time'], -4);
						//$newdate = substr($info['expiry_time'], -7,-5);
						//$newmonth = substr($info['expiry_time'], -10,-8);
						//$new_date= "$newyear-$newmonth-$newdate";
						$time = strtotime("$expires_on") - gettime_timezone($info["time_zone"]);
						$day = floor($time / 86400);
						$time %= 86400;
						$hrs = floor($time / 3600);
						$time %= 3600;
						$mins = floor($time / 60);
						$time %= 60;
						$secs=$time;
						if($day<0 || $hrs<0 || $mins<0 ||$secs<0){
							list($day,$hrs,$mins,$secs)=array(0,0,0,0);
						}
					break;
					case 1:	/* For first time visit */
						$display_counter= false;
						$onetime_html=$this->expire_by_cookie($campaign_id,"onetime",$info);
						$onetime_html=apply_filters('per_expire_visiters_addon',$onetime_html,$campaign_id,"onetime",$info);						
						if($info['method'] == 'ip')
						{
						?>
                        <script type="text/javascript">console.log("1");
						jQuery.ajax({
						      type: 'POST',
						      url: '<?php echo trailingslashit(admin_url()); ?>admin-ajax.php',
						      data: { action:'firstvisit',campid:<?php echo $campaign_id; ?>}
						    })
						      .done(function( msg ) {
						        console.log( 'Test from IP First' );
						        
						      });  
                        </script>
                        <?php
                         }
                         else
                         	 {
                        ?>
                        <script type="text/javascript">
                          document.cookie="campaign_id=<?php echo $campaign_id; ?>;"
                        </script>
						<?php
						     }
						echo $onetime_html;
						add_filter('per_get_counter', array($this,'get_counter_style_none'), 20, 13);
					break;
					default:
						apply_filters('per_get_counter_addon',$info['expiry_method']); //for Addons
						/* Temporary */
						$info['hide_day_label'] = 1;
						$info['hide_hrs_label'] =1;
						$info['hide_mins_label'] =1;
						$info['hide_sec_label'] =1;
				}
				/* filter to get time setting from addons */
				/* for evant after counter reaches zero */
				
				if($info['redirection_url'] != ""){
					
					$link=$info['redirection_url'];
					
				}else{
					
					$link=$info['redirect_m_url'];
					
				}				
				
				switch($info['event']){ /* Get html depending upon expiry event */
					case 0:		/* if event is Redirectional url */
						$link=$info['redirection_url'];
						$counterHtml="";
					break;
					case 1:/* if event is Show Default image */
					    if(($info['position'] == 'h') || ($info['position'] == 'f'))
					    {
					    	$image=$this->PluginURL."/images/expired-notice.png";
					    	$counterHtml = "<script type='text/javascript'>
                           function new_imager(per_id){
                             jQuery('.per_'+per_id).hide();
                             //jQuery('#countdown_dashboard_1').hide();
                             jQuery('#CountDownTimer1').hide();
                             console.log('Test banner');
                             jQuery('#hid').html(\"<img src='".$image."'>\");
                             jQuery.ajax({
			      type: 'POST',
			      url: '".trailingslashit(admin_url())."admin-ajax.php',
			      data: { action:'sess_set',campid:per_id,exp:".$info['expiry_method'].",url:'".$info['redirect_m_url']."' }
			    })
			      .done(function( msg ) {
			        console.log( 'Test from sess' );
			        
			      });  
                           }
						</script>";
					    }
					    else
					    {
						$counterHtml = "<script type='text/javascript'>
                           function new_imager(per_id){
                             jQuery('.per_'+per_id).hide();
                             jQuery('#countdown_dashboard_1').hide();
                             jQuery.ajax({
			      type: 'POST',
			      url: '".trailingslashit(admin_url())."admin-ajax.php',
			      data: { action:'sess_set',campid:per_id,exp:".$info['expiry_method'].",url:'".$info['redirect_m_url']."' }
			    })
			      .done(function( msg ) {
			        console.log( 'Test from sess'+msg );
			        
			      });  
                           }
						</script>";
						$image=$this->PluginURL."/images/expired-notice.png";
						$counterHtml .= "<img src='".$image."'>";
					  }
					break;
					case 2:		/* if event is Show Own Image */
					    if(($info['position'] == 'h') || ($info['position'] == 'f'))
					    {
					    	$image=$this->PluginURL."/images/".$info['splash_url'];
					    	$counterHtml = "<script type='text/javascript'>
                           function new_imager(per_id){
                             jQuery('.per_'+per_id).hide();
                             //jQuery('#countdown_dashboard_1').hide();
                             jQuery('#CountDownTimer1').hide();
                             console.log('Test banner');
                             jQuery('#hid').html(\"<img width='100' height='50' src='".$image."'>\");
                             jQuery.ajax({
			      type: 'POST',
			      url: '".trailingslashit(admin_url())."admin-ajax.php',
			      data: { action:'sess_set',campid:per_id,exp:".$info['expiry_method'].",url:'".$info['redirect_m_url']."' }
			    })
			      .done(function( msg ) {
			        console.log( 'Test from sess' );
			        
			      }); 
                           }
						</script>";
					    }
					    else
					    {
					    $counterHtml = "<script type='text/javascript'>
                           function new_imager(per_id){
                             jQuery('.per_'+per_id).hide();
                             jQuery('#countdown_dashboard_1').hide();
                             jQuery.ajax({
			      type: 'POST',
			      url: '".trailingslashit(admin_url())."admin-ajax.php',
			      data: { action:'sess_set',campid:per_id,exp:".$info['expiry_method'].",url:'".$info['redirect_m_url']."' }
			    })
			      .done(function( msg ) {
			        console.log( 'Test from sess' );
			        
			      }); 
                           }
						</script>";
						$image=$this->PluginURL."/images/".$info['splash_url'];
						$counterHtml .= "<img src='".$image."'>";
					   }
					break;
					case 4:	global $post;	/* if event is Show Own Image */
						echo "<script type='text/javascript'> jQuery(document).ready(function(){
                              jQuery.ajax({
								type:'post',
								//dataType: 'JSON',
								url:'".admin_url()."/admin-ajax.php',
								data : {'action':'convert_to_draft','post_id':".$post->ID."},
								success:function(result)
								{
									  console.log(result);                                 
								    
								},
								error: function(errorThrown){
								alert('error');
								console.log(errorThrown);
				           }
				}); 
                   })
						</script>";
					break;
					/*case "":		/* if event is Do nothing (Stay on same page) 
						$counterHtml="";
					break;*/
					default:
						$counterHtml=do_action('per_get_counterHtml_addon',$info['event']); //for Addons for more events form addon
				}
				/* TIme is zero and redirect link true then redirect */
				if($whitelisted){/* if user ip in whitelist dont do anything */
					$link="";
					$counterHtml="";
				}
				$onexpirejscode="";
				$link=apply_filters('get_redirect_link',$link,$campaign_id);
				if($day <= 0 && $hrs<=0 && $mins<=0 && $secs<=0 && $link !="" ){
						//$onexpirejscode='<script type="text/javascript">window.location="'.$link.'";</script>';
				}
				$onexpirejscode=apply_filters('per_onexpirejscode',$onexpirejscode,$campaign_id,$link);
				echo $onexpirejscode;
				
				/* display position of counter */
				$cssClass = "";
				switch($info['position']){
					case 'c':	/* display counter in content */
						break;
					case 'invisible':	/* do not display counter */
						$cssClass = " hidden";
						break;
					default:
						$cssClass=apply_filters('per_get_counter_position_addon',$cssClass,$info['position']); //for Addons for positiom form ad
				}
				
			 
				// switch($info['counter_size']){/* define counter size */
				// 	case '0':
				// 			$sizeClass="small";
				// 	break;
				// 	case '1':
				// 		$sizeClass="medium";
				// 	break;
				// 	case '2':
				// 			$sizeClass="large";
				// 	break;
				// }
				$sizeClass=$info['counter_size'];
				switch($info['alignment']){/* define counter Alignment */
					case '0':
							$alignCss="left";
					break;
					case '1':
						$alignCss="none";
					break;
					case '2':
							$alignCss="right";
					break;
				}
				$info['days_label_hide']=$info['hours_label_hide']=$info['min_label_hide']=$info['sec_label_hide']="block";
				if($info['hide_day_label']==1){
					$info['days_label_hide']="none";
				}
				if($info['hide_hrs_label']==1){
					$info['hours_label_hide']="none";
				}
				if($info['hide_mins_label']==1){
					$info['min_label_hide']="none";
				}
				if($info['hide_sec_label']==1){
					$info['sec_label_hide']="none";
				}
			}
		
			//echo apply_filters('login_enqueue_scripts','');
			switch($info['color_num']){
				case '0':
					$info['myhexcode']="#fff";
				break;	
			}
			switch($info['back_color']){
				case '0':
					$info['myhexcode1']="#000";
				break;	
			}
			$html='<div class="per_counter_wrapper'.$cssClass.'">';
			$counter_style_name= get_post_meta($campaign_id, $this->MetaPrefix.'counter_style',true);
			/* filter to add text before counter */
			$html.=apply_filters('get_counter_text','',$campaign_id,$alignCss,$sizeClass);
			$wdth=100;
			if($info['position']=='h' ||$info['position']=='f')$wdth=100;
			$html.='<div id="countdown_dashboard_'.PageExpirationRobot::$NoOfShortcode.'" style="width:'.$wdth.'%;margin:0px auto;float:'.$alignCss.';';
			if($display_counter==false){
				$html.="display:none;";
			}
			else{
				$html .="display:block;";
			}
			/* code to show counter message*/
			
			if($alignCss == "left"){
				
				$alignPER = "left";
				
			}else if($alignCss == "right"){
				
				$alignPER = "right";
				
			}else{
				
				$alignPER = "center";
				
			}
			$html.='text-align:left;float:'.$alignCss.';" class="counter_'.$sizeClass.'"><div style="margin:0px auto;" class="main_counter_wrap main_counter_wrap_'.$alignCss.'" id="'.$counter_style_name.'">';
			$DefaultCounter="";
			/* filter to get different counter styles  */
			$html.=apply_filters('per_get_counter',$DefaultCounter,$day,$hrs,$mins,$secs,$link,$image,$cssClass,$alignCss,$display_counter,$sizeClass,$info,$campaign_id);
            $per_banner = get_post_meta($campaign_id, 'per_banner',true);

            if($info['position']=='f')
            {
               $html .= '<div id="per_banner_id" class="per_footer_banner" data-width="'.$size.'">'.$per_banner.'</div>';
            }
            if($info['position']=='h')
            {
               
               $html .= '<div id="per_banner_id" class="per_header_banner" data-width="'.$size.'">'.$per_banner.'</div>';
            }
			//code for default counter
			
			if (!wp_style_is( "flip_css", 'enqueued' ))
			{
			
			$flip_style = 'slide';
			$flip_theme = 'black';
			$no_of_shortcodes=PageExpirationRobot::$NoOfShortcode;
			if($day==0 && $hrs==0 && $mins==0 && $secs==0){$time=1; }
			// if($sizeClass=="large"){$size=350;}
			// if($sizeClass=="medium"){$size=300;}
			// if($sizeClass=="small"){$size=250;}
			$size=$sizeClass;
			$i=0;
			if($sizeClass<=350 && $sizeClass<=450)
			{
				$tsize=50;
			}elseif($sizeClass>=450 && $sizeClass<=550)
			{
				$tsize=60;
			}elseif($sizeClass>=450 && $sizeClass<=550)
			{
				$tsize=80;
			}else
			{
			}	
			// if($sizeClass=="small")$tsize=50;
			// if($sizeClass=="medium")$tsize=60;
			// if($sizeClass=="large")$tsize=80;
		
			if($info['days_label_hide']=="none")$i++;
			if($info['hours_label_hide']=="none")$i++;
			if($info['min_label_hide']=="none")$i++;
			if($info['sec_label_hide']=="none")$i++;
			$s=$tsize-(10*$i);
			$size=$size-(66*$i)."%";
			if($cssClass!='')
			 {
			 	if($cssClass=='bottom_fixed')
			 	{
			 		 $per_banner = get_post_meta( $campaign_id, 'per_banner', true );
			 		 if($per_banner!='')
			 		 {
			 		 	echo $per_banner ='<div id="per_banner_id" class="per_footer_banner" data-width="'.$size.'">'.$per_banner.'</div>';
			 		 }
			 	}
			 	if($cssClass=='top_fixed')
			 	{
			 		
			 		$per_banner = get_post_meta( $campaign_id, 'per_banner', true );
			 		if($per_banner!='')
			 		 {
			 		 	echo $per_banner ='<div id="per_banner_id" class="per_header_banner" data-width="'.$size.'">'.$per_banner.'</div>';
			 		 }
			 	}
			 }
			$current_time =$mtr=time();
			$nextdate= strtotime("+$day days $hrs hours $mins minute $secs seconds");
			$nextdate= $nextdate - 4;
			
			//echo $nextdate;
			$nextdate= date('Y/m/d G:i:s',$nextdate);
            $styler = "";
			if($info['position'] == 'invisible')
              $styler = "display:none;";
           
            $blkk = "";
            if($info['expiry_method'] == '1')
            {
            	$blkk = "display:none !important;";
            }
			$html="<div id='CountDownTimer".PageExpirationRobot::$NoOfShortcode."' style='".$blkk."float:".$alignCss.";margin:auto;".$styler."' class='".$alignCss."flipcounter per_".$campaign_id."'></div>";
			$html.="<script>jQuery.noConflict();
				jQuery(window).load(function(){
					
				var sec_label=true;var hours_label=true;var min_label=true;var days_label=true;
				var days_label_hide='".$info['days_label_hide']."';
				var min_label_hide='".$info['min_label_hide']."';
				var hours_label_hide='". $info['hours_label_hide']."';
				var sec_label_hide='". $info['sec_label_hide']."';
				if(days_label_hide=='none'){days_label=false; }
				if(min_label_hide=='none'){min_label=false; }
				if(hours_label_hide=='none'){hours_label=false; }
				if(sec_label_hide=='none'){sec_label=false; }
				console.log(sec_label);
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode."').jCountdown({
					timeText:'".$nextdate."',
					style:'".$flip_style."',
					color:'".$flip_theme."',
					width:'$size',
					textGroupSpace:15,
					textSpace:0,
					reflection:false,
					reflectionOpacity:10,
					reflectionBlur:0,
					dayTextNumber:3,
					displayDay:days_label,
					displayHour:hours_label,
					displayMinute:min_label,
					displaySecond:sec_label,
					displayLabel:true,
					onFinish:function(){
						showExpireAction".$campaign_id."(".PageExpirationRobot::$NoOfShortcode.");
						
					}
				});
				var jCountdownContainer=jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .jCountdownContainer').width();
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode."').width(jCountdownContainer);
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .day .label').html('". $info['days_label']."');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .hour .label').html('". $info['hours_label']."');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .minute .label').html('". $info['min_label']."');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .second .label').html('". $info['sec_label']."');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .label').addClass('labelnew');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .label').removeClass('label');
				jQuery('#CountDownTimer".PageExpirationRobot::$NoOfShortcode." .labelnew').css({'color': '".$info['label_color']."','text-shadow':'1px 1px  ".$info['shadow_color']."'});
			});
			</script>";
			if($day<=0 && $hrs<=0 && $mins<=0 && $secs<=0){
				$html.="<script>
				jQuery(document).ready(function(){
					showExpireAction".$campaign_id."(".PageExpirationRobot::$NoOfShortcode.");
			});
			</script>";
			}
          
            $html.=apply_filters('per_get_counter',$DefaultCounter,$day,$hrs,$mins,$secs,$link,$image,$cssClass,$alignCss,$display_counter,$sizeClass,$info,$campaign_id);
            /*$qry="SELECT * FROM {$wpdb->prefix}page_expiry_action_reach WHERE P_Id=$campaign_id  LIMIT 1";			
			$actioninfo = $wpdb->get_row($qry, ARRAY_A);
			$balnce_reach = $actioninfo['balance_reach'];
			$balnce_reach++;
			$upd_ssql="UPDATE {$wpdb->prefix}page_expiry_action_reach SET balance_reach = $balnce_reach WHERE P_Id=".$campaign_id." AND balance_reach > 0";							
			$wpdb->query($upd_ssql);*/
		} 
			$counter_expire=$this->after_counter_expire($link,$info,$campaign_id);
			$html.=apply_filters('per_get_expiry_action',$counter_expire,$day,$hrs,$mins,$secs,$campaign_id);
			$html.="</div></div></div><div style='display:none; margin:0px auto;' id='complete_info_message_".PageExpirationRobot::$NoOfShortcode."' class='info_message' >".$counterHtml."</div>";
			$html=apply_filters('per_counter_html',$html,$day,$hrs,$mins,$secs,$campaign_id,$info);
			return $html;
			/* code to show counter */
		}
		/* Add White list Ajax function */
	function expirer_edit_ip_list()
	{
		if (!isset($_REQUEST['act']) || $_REQUEST['act'] == 'add')
		{
			$listtype = $_REQUEST["list"];
			$list = unserialize(get_option("per_".$listtype."_list"));
			if (!isset($list) || !is_array($list))
				$list = array();
			if (!in_array($_REQUEST["ip"], $list))
			{
				$list[] = $_REQUEST["ip"];
				update_option("per_".$_REQUEST["list"]."_list", serialize($list));
				echo $_REQUEST["ip"]."~".$_REQUEST["list"];
			}
			else
			{
				echo "0"."~".$_REQUEST["list"];
			}
		}
		if (isset($_REQUEST['act']) && $_REQUEST['act'] == 'del')
		{
			$listtype = $_REQUEST["list"];
			$sip = str_replace(".","",$_REQUEST["ip"]);
			$listtype = str_replace($sip,"",$listtype);
			$list = unserialize(get_option("per_".$listtype."_list"));
			if (!isset($list) || !is_array($list))
				$list = array();
			$key = array_search($_REQUEST["ip"], $list);
			unset($list[$key]);
			update_option("per_".$listtype."_list", serialize($list));
			echo $_REQUEST["list"];
		}
		exit();
	}
	/* unzip uploaded addon folder */
  	function unzip($ZipName, $Folder)
	{
		$zip_folder_path_org = $Folder."/";
		$zip_folder_path = $zip_folder_path_org.$ZipName;
		$flname_no_ext = basename($zip_folder_path, ".zip"); 
		$zip = new ZipArchive;
		$res = $zip->open($zip_folder_path);
		if ($res === TRUE) {
			@mkdir($zip_folder_path_org.$flname_no_ext);
			// extract it to the path we determined above
			$extracted = $zip->extractTo($zip_folder_path_org."/");
			$zip->close();
		}
	}
	/* function to delete addon if allready exist or upload wrong file */
	function deleteDir($path)
	{
		chmod($path,0777);
		return is_file($path) ?
		@unlink($path) :
		array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
	}
	
  }
/* Class End */
}

function PER_uninstall_plugin(){
		
	delete_option( 'PER_main_notice_remove');
	delete_option( 'PER_addon_notice_remove');
	delete_option( 'PER_addon_notice_later');
	delete_option( 'PER_30day_remind_notice');
	delete_option( 'PER_30day_clear_notice');
	delete_option( 'PER_addon_notice_show');
	
}

register_uninstall_hook(__FILE__,'PER_uninstall_plugin');

/* timymce button */
class PerEditorButton
{
	function __construct() {
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
	}
	
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		// add a separation before our button, here our button's id is "myshortcode_button"
		array_push( $buttons, '|', 'per_editor_button' );
		return $buttons;
	}
	
	function filter_mce_plugin( $plugins ) {
		
		$plugins['per_editor_button'] = plugin_dir_url( __FILE__ ).'shortcode_js.js';
		return $plugins;
	}	
}
if (!isset($PerEditorButton))
	$PerEditorButton = new PerEditorButton();
/* timymce button */
if(!isset($PER)){
	$PER = new PageExpirationRobot();
}
/*$promotional_settings = get_option('per__promotional_check');
if($promotional_settings == '')
{
	function per_admin_bar_menu(){
            global $wp_admin_bar;
            
                $wp_admin_bar->add_menu( array(
                    'id'     => 'per-upgrade-bar',
                    'href' => 'http://www.pageexpirationrobot.com/v2/special',
                    'parent' => 'top-secondary',
					'title' => __('<img src="'.plugin_dir_url( __FILE__ ).'images/PER_logo.png"  style="vertical-align:middle;margin-right:5px;width:113px;height:24px;" alt="Upgrade Now!" title="Upgrade Now!" /><strong><b>Premium Features</b><strong>', 'per' ),
                    'meta'   => array('class' => 'per-upgrade-to-pro', 'target' => '_blank' ),
                ) );
		}
add_action( 'admin_bar_menu','per_admin_bar_menu', 1000);
}*/
//////***** Adding Custom Pointer *****///////
add_action('init','pointer_code');
function pointer_code()
{
     include("pointer.php");
     if(!session_start())
     	session_start();
}
add_action('wp_ajax_sess_set','sess_set');
add_action('wp_ajax_nopriv_sess_set','sess_set');
function sess_set()
{
   $expiry_method = $_POST['exp'];
   $campaign_id = $_POST['campid'];
   $url = $_POST['url'];
   if(($expiry_method != 3) && ($expiry_method != 1))
			{
					
						$_SESSION['id_sett'] = $campaign_id;
					    $_SESSION['redirect_m_url'] = $url;
				
            }
   die();
}
function PER_admin_notice() {
    global $wpdb;
    $camp_data = get_posts(array('post_type'=>'per_campaign'));
    $c = count($camp_data);
    if($c == 0)
      {	
    ?>
    <div class="updated" style="background-color:#fee;">
        <p><a href="<?php echo trailingslashit(site_url()).'wp-admin/admin.php?page=page_expiration_robot_new'?>">Create a scarcity campaign</a> using Page Expiration Robot</p>
    </div>
    <?php
      }
}
add_action( 'admin_notices', 'PER_admin_notice' );

function firstvisit()
{
   $campaign_id = $_POST['campid'];
   $ip = $_SERVER['REMOTE_ADDR'];
   $first_visit_ip = get_post_meta($campaign_id,'first_visit_ip',true);
	if($first_visit_ip != '')
	{
	    $first_visit_ip .= ','.$ip;
		update_post_meta($campaign_id,'first_visit_ip',$first_visit_ip);
	}
	else
	{
		add_post_meta($campaign_id,'first_visit_ip',$ip);
	}
}
add_action('wp_ajax_firstvisit','firstvisit');
add_action('wp_ajax_nopriv_firstvisit','firstvisit');

function expired_once($campaign_id)
{
	
	$check = get_post_meta($_POST['campid'], 'per_reseted_alr', true);
	
	if($check == ""){
		add_post_meta($_POST['campid'],'per_reseted_alr','1');
		echo $_POST['campid'];
	}
 
}
add_action('wp_ajax_get_my_form',  'get_my_form');
function get_my_form(){
	
	$form='<div id="per_editor-form"><table id="per_editor-table" class="form-table"><div class="per-wrapper"><div class="header"><div class="logo"><img src="'.WP_PLUGIN_URL.'/page-expiration-robot/images/PER_logo.png" style="height:34px;"></div><h2>Add Shortcode</h2></div><table style="width: 520px;" cellspacing="2" cellpadding="0">';
	$form.='<tr style="height:20px;" class="" id="camp_row'.get_the_ID().'"><td style="width:5px;"><select id="counter_selector" style="float: left;">';
	$args = array(
				  'post_type'	  => 'per_campaign'	
				);
	query_posts( $args );

	while ( have_posts() ) : the_post();
		$id=get_the_ID();
		$form.='<option value="'.get_the_ID().'">'.get_the_title().'</option>';
		//$form.='<tr style="height:20px;" class="hide_all" id="camp_row'.get_the_ID().'"><td style="width:5px;"><input type="radio" name="orderby" id="per_editor-orderby"  value="'.get_the_ID().'">&nbsp&nbsp&nbsp<strong><a class="row-title" >'.get_the_title().'</a></strong></td></tr>';
		

	endwhile;
	$form.='</select><input type="button" id="per_editor-submit" class="button-primary per-green-button" value="Insert Shortcode" name="submit" style="float: left; margin-left: 10px" /></td></tr>';
	$form.='<tr><td ></td </tr><tr><td ></td></tr></table></div></div>';

	echo $form;
	exit();
	
}

add_action('wp_ajax_expired_once','expired_once');
add_action('wp_ajax_nopriv_expired_once','expired_once');
add_action( 'admin_notices', 'PER_plugin_setup_notice' );
add_action( 'admin_notices', 'PER_plugin_setup_notice' );
add_action( 'admin_notices', 'PER_addOns_notice_show' );
add_action( 'admin_notices', 'PER_30days_notice_show' );
add_action ('wp_login' , 'PER_addOns_notice');
if ( isset( $_GET['clear_noti_main'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_main_notice_remove');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_main_notice_remove', 'yes', '', 'no' );
	}
	
	if ( sanitize_text_field( $_GET['clear_noti_main'] ) == 'yes' && isset( $_SERVER['HTTP_REFERER'] ) ) {

		wp_redirect( $_SERVER['HTTP_REFERER'], '302' );

	} else {

		wp_redirect( 'admin.php?page=page_expiration_robot', '302' );

	}
	
}

if ( isset( $_GET['clear_noti_main_ltBtn'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_main_notice_remove');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_main_notice_remove', 'yes', '', 'no' );
	}

	wp_redirect( 'admin.php?page=page_expiration_robot_new', '302' );
	
}

if ( isset( $_GET['clear_noti_addOn'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_addon_notice_remove');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_addon_notice_remove', 'yes', '', 'no' );
	}
	
	if ( sanitize_text_field( $_GET['clear_noti_addOn'] ) == 'yes' && isset( $_SERVER['HTTP_REFERER'] ) ) {

		wp_redirect( $_SERVER['HTTP_REFERER'], '302' );

	} else {

		wp_redirect( 'admin.php?page=page_expiration_robot', '302' );

	}
	
}

if ( isset( $_GET['later_noti_addOn'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_addon_notice_later');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_addon_notice_later', current_time( 'timestamp' ), '', 'no' );
	}else{
		
		update_option( 'PER_addon_notice_later', current_time( 'timestamp' ), '', 'no' );
		
	}
	 
	if ( sanitize_text_field( $_GET['later_noti_addOn'] ) == 'yes' && isset( $_SERVER['HTTP_REFERER'] ) ) {

		wp_redirect( $_SERVER['HTTP_REFERER'], '302' );

	} else {

		wp_redirect( 'admin.php?page=page_expiration_robot', '302' );

	}
	
}

if ( isset( $_GET['later_noti_3oD'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_30day_remind_notice');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_30day_remind_notice', current_time( 'timestamp' ), '', 'no' );
	}else{
		
		update_option( 'PER_30day_remind_notice', current_time( 'timestamp' ), '', 'no' );
		
	}
	  
	if ( sanitize_text_field( $_GET['later_noti_3oD'] ) == 'yes' && isset( $_SERVER['HTTP_REFERER'] ) ) {

		wp_redirect( $_SERVER['HTTP_REFERER'], '302' );

	} else {

		wp_redirect( 'admin.php?page=page_expiration_robot', '302' );

	}
	
}

if ( isset( $_GET['clear_noti_30D'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'PER_not_call' ) ) {
	
	$removeMainNotice = get_option('PER_30day_clear_notice');
	
	if(!$removeMainNotice){
	
		add_option( 'PER_30day_clear_notice', 'yes', '', 'no' );
	}
	 
	if ( sanitize_text_field( $_GET['clear_noti_30D'] ) == 'yes' && isset( $_SERVER['HTTP_REFERER'] ) ) {

		wp_redirect( $_SERVER['HTTP_REFERER'], '302' );

	} else {

		wp_redirect( 'admin.php?page=page_expiration_robot', '302' );

	}
	
}

function PER_plugin_setup_notice() {
	
	$removeMainNotice = get_option('PER_main_notice_remove');
	
	if($removeMainNotice != 'yes'){

		echo '<div class="updated" id="PER_setup_notice"><img src="' . WP_PLUGIN_URL . '/page-expiration-robot/' . 'css/images/logo_per1.png" class="PER_noticeImg" alt="logo PER" />' . __( 'Great! Now let\'s create your first scarcity campaign', 'PER_BTN' ) . '<a href="#" onclick="document.location.href=\'?clear_noti_main_ltBtn=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';" class="PER-notice-button" >' . __( 'Let\'s Do It!', 'PER_BTN' ) . '</a><a href="#" class="PER-notice-hide" onclick="document.location.href=\'?clear_noti_main=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';">&times;</a>
			</div>';
			
	}

}

function PER_addOns_notice_show() {
	
	$removeAddOnNotice = get_option('PER_addon_notice_remove');
	$showAddOnNotice = get_option('PER_addon_notice_show');;
	$laterAddOnNotice = floor((current_time('timestamp') - get_option('PER_addon_notice_later'))/60/60/24);

	if($showAddOnNotice == 'yes' && $removeAddOnNotice != "yes" && $laterAddOnNotice >= 7){

		echo '<div class="updated" id="PER_setup_notice"><img src="' . WP_PLUGIN_URL . '/page-expiration-robot/' . 'css/images/logo_per1.png" class="PER_noticeImg" alt="logo PER" />' . __( '<span style="font-weight:bold;color:red;">NEW!</span> Unlock All The Powerful Features Of Page Expiration Robot for 70% discount!', 'PER_BTN' ) . '<a href="https://imwenterprises.net/get-ultimate" target="_blank" class="PER-notice-button_gr" >' . __( 'Learn More', 'PER_BTN' ) . '</a><a href="#" class="PER-notice-button" onclick="document.location.href=\'?later_noti_addOn=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';">' . __( 'Remind Me Later', 'PER_BTN' ) . '</a><a href="#" class="PER-notice-button" onclick="document.location.href=\'?clear_noti_addOn=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';">' . __( 'Never Show Again', 'PER_BTN' ) . '</a>
			</div>';
			
	}

}

function PER_30days_notice_show() {
	
	$thirtydaysRemNot = get_option('PER_30day_remind_notice');
	
	$days = explode('/', $thirtydaysRemNot);
	
	$days = $days[0];
	
	if(strpos($thirtydaysRemNot,'/first') !== false){
		
		$difRate = 30;
		
	}else{
		
		$difRate = 7;
		
	}
	
	$laterAddOnNotice = floor((current_time('timestamp') - $days)/60/60/24);
	
	if(get_option('PER_30day_clear_notice') != "yes" && $laterAddOnNotice >= $difRate){

		echo '<div class="updated" id="PER_setup_notice"><img src="' . WP_PLUGIN_URL . '/page-expiration-robot/' . 'css/images/logo_per1.png" class="PER_noticeImg" alt="logo PER" />' . __( 'Looks like you\'ve used Page Expiration Robot for a while. Can you please vote for it so we can continue making it awesome?', 'PER_BTN' ) . '<a href="https://wordpress.org/support/view/plugin-reviews/page-expiration-robot " class="PER-notice-button" >' . __( 'I\'d Like To Help!', 'PER_BTN' ) . '</a><a href="#" class="PER-notice-button" onclick="document.location.href=\'?later_noti_3oD=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';">' . __( 'Remind Me Later', 'PER_BTN' ) . '</a><a href="#" class="PER-notice-button" onclick="document.location.href=\'?clear_noti_30D=yes&_wpnonce=' . wp_create_nonce( 'PER_not_call' ) . '\';">' . __( 'Never Show Again', 'PER_BTN' ) . '</a>
			</div>';
			
	}

}

function PER_addOns_notice(){
	
	$removeAddOnNotice = get_option('PER_addon_notice_show');
	
	if($removeAddOnNotice != "yes"){
	
		add_option( 'PER_addon_notice_show', 'yes', '', 'no' );
	}
	
}

function PER_admin_notice_addon_update() {
	$raw_addons = wp_remote_get( 'http://pageexpirationrobot.com/v2/latest_addons.php' );      
	
	$chr = $raw_addons['body'];
	$obj = json_decode($chr);

	$addOns = $obj;
	$vers_data = wp_remote_get( 'http://pageexpirationrobot.com/v2/addons_version.php' );
	$bbdy = $vers_data['body'];

	$vers_obj = json_decode($bbdy);
	$available = 0;
	$InstalledAddOns = unserialize(get_option("per__addons"));
	//print_r($InstalledAddOns);
	$expired = 0;
	foreach ($addOns as $addOn)
	{
		$PluginBase1 =  plugin_basename( __FILE__ );
		$PluginName1 = trim( dirname( $PluginBase1 ), '/' );
		$PluginDir1 = WP_PLUGIN_DIR . '/' . $PluginName1;
		$bdy = file_get_contents($PluginDir1."/addons/".$addOn->code."/readme.txt");		
		$addonm_code = $addOn->code;        
		preg_match("/Version:(.*)/",$bdy, $converted);
		$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
		if($vers_obj->$addonm_code != '')
		{
				if($vers_obj->$addonm_code != $converted)
				{
					if($InstalledAddOns[$addOn->code]['act'] == 1)
					{
						$expired++;
					}		        	
				}
		}
	 }  
	if($expired > 0)
	{
		?>
		<div class="updated" style="background-color:#fee;">
			<p><span><span id="spanner" class="numbtn"><?php echo $expired; ?></span> Addon Updates Available <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons">Update Now</a></span></p>
		</div>
		<?php
	}
}

?>