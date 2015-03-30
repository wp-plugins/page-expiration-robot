<?php
//----Form process after submit
if (isset($_POST['campaign_name'])){
	$this->save_campaign();
}
//--------DISPLAY EDIT FORM
$args = array(
			  'post_type'	  => $this->CampaignPostType,
				'post__in' => array($_GET['pid'])
			);
query_posts( $args );
$editCampaign = 1;
while (have_posts()) : the_post();

	$expiry_method= get_post_meta( $_GET['pid'],$this->MetaPrefix.'expiry_method', true);
	$expiry_date=get_post_meta( $_GET['pid'], $this->MetaPrefix.'expiry_date', true);
	$expiry_date_time_days=get_post_meta( $_GET['pid'], $this->MetaPrefix.'expiry_date_time_days', true);
	$expiry_date_time_hrs=get_post_meta( $_GET['pid'], $this->MetaPrefix.'expiry_date_time_hrs', true);
	$expiry_date_time_mins=get_post_meta( $_GET['pid'], $this->MetaPrefix.'expiry_date_time_mins', true);
	$expiry_date_time_secs=get_post_meta( $_GET['pid'], $this->MetaPrefix.'expiry_date_time_secs', true);
	$method=get_post_meta( $_GET['pid'], $this->MetaPrefix.'method', true);
	$position=get_post_meta( $_GET['pid'], $this->MetaPrefix.'position', true);
	$event=get_post_meta( $_GET['pid'], $this->MetaPrefix.'event', true);
	$redirection_url=get_post_meta( $_GET['pid'], $this->MetaPrefix.'redirection_url', true);
	$redirect_m_url = get_post_meta( $_GET['pid'], $this->MetaPrefix.'redirect_m_url', true);
	$splash_url=get_post_meta( $_GET['pid'], $this->MetaPrefix.'splash_url', true);

	//Newly Added
	$color_num = get_post_meta($_GET['pid'], $this->MetaPrefix.'color_num',true);
	$back_color = get_post_meta($_GET['pid'], $this->MetaPrefix.'back_color',true);
	$counter_size = get_post_meta($_GET['pid'], $this->MetaPrefix.'counter_size',true);

	$alignment = get_post_meta($_GET['pid'], $this->MetaPrefix.'alignment',true);

	$info['myhexcode']=$myhexcode = get_post_meta($_GET['pid'], $this->MetaPrefix.'myhexcode',true);	
	$info['myhexcode1']=$myhexcode1= get_post_meta($_GET['pid'], $this->MetaPrefix.'myhexcode1',true);

	$info['days_label']=$days_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'days_label',true);
	$info['hours_label']=$hours_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'hours_label',true);
	$info['min_label']=$min_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'min_label',true);
	$info['sec_label']=$sec_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'sec_label',true);
	$info['label_color']=$label_color = get_post_meta($_GET['pid'], $this->MetaPrefix.'label_color',true);
	$info['shadow_color']=$shadow_color = get_post_meta($_GET['pid'], $this->MetaPrefix.'shadow_color',true);

	$hide_day_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'hide_day_label',true);
	$hide_hrs_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'hide_hrs_label',true);
	$hide_mins_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'hide_mins_label',true);
	$hide_sec_label = get_post_meta($_GET['pid'], $this->MetaPrefix.'hide_sec_label',true);
	$counter_style = get_post_meta($_GET['pid'], $this->MetaPrefix.'counter_style',true);
	$timeZone=get_post_meta($_GET['pid'], $this->MetaPrefix.'time_zone',true);
	
	//<<<
?>
	<script>
	//------JQUERY to set past values related with post----
	jQuery(window).load(function(){ 
		
		jQuery('#counter_size').val('<?php echo $counter_size;?>');
		jQuery(document).ready(function(){
			jQuery( "#slider-range-min" ).slider( "value", <?php echo $counter_size;?> );

		});
		var hide_day_label="<?php echo $hide_day_label;?>";
		if(hide_day_label==1){
			jQuery("#hide_day_label").attr("checked","checked");
			jQuery('.days_dash').css('display','none');
			
		}
		var hide_hrs_label="<?php echo $hide_hrs_label;?>";
		if(hide_hrs_label==1){
		jQuery("#hide_hrs_label").attr("checked","checked");
		jQuery('.hours_dash').css('display','none');
		}
		var hide_mins_label="<?php echo $hide_mins_label;?>";
		if(hide_mins_label==1){
		jQuery("#hide_mins_label").attr("checked","checked");
		jQuery('.minutes_dash').css('display','none');
		}
		var hide_sec_label="<?php echo $hide_sec_label;?>";
		if(hide_sec_label==1){
		jQuery("#hide_sec_label").attr("checked","checked");
		jQuery('.seconds_dash').css('display','none');
		}
		
	jQuery("#campaign_name").val("<?php echo the_title();?>");
	jQuery("input:radio").removeAttr("checked");
	jQuery("#expiry_method").val("<?php echo $expiry_method;?>");
	jQuery(".expiry_method").css("display","none");
	jQuery(".expiry_method_<?php echo $expiry_method;?>").css("display","block");
	var date_option="<?php echo $expiry_method;?>";
	//-----date values
	switch(date_option){
		  case '2':
			jQuery("#expiry_date_time_days").val("<?php echo $expiry_date_time_days;?>");
			jQuery("#expiry_date_time_hrs").val("<?php echo $expiry_date_time_hrs;?>");
			jQuery("#expiry_date_time_mins").val("<?php echo $expiry_date_time_mins;?>");
			jQuery("#expiry_date_time_secs").val("<?php echo $expiry_date_time_secs;?>");
			//---<<date values
			var method ="<?php echo $method;?>";
				if(method=="ip"){
						jQuery("#method_ip").attr("checked","checked");
					}
				else{
						jQuery("#method_cookie").attr("checked","checked");
					}
		   break;
		   case '0':
			jQuery("#expiry_date").val("<?php echo $expiry_date;?>");
			jQuery("#method_cookie").attr("checked","checked");
			jQuery('option[value="<?php echo $timeZone;?>"]').attr('selected', 'selected');
		   break;
		   case '1':
			 jQuery("#method_cookie").attr("checked","checked");
			var method ="<?php echo $method;?>";
				if(method=="ip"){
						jQuery(".expiry_method_1 #method_ip").attr("checked","checked");
					}
				else{
						jQuery(".expiry_method_1 #method_cookie").attr("checked","checked");
					}
		   break;
	 }
	jQuery(':radio[value="<?php echo $event;?>"]').attr('checked', 'checked');
	jQuery(':radio[value="<?php echo $position;?>"]').attr('checked', 'checked');
	var event="<?php echo $event;?>";
		if(event=="0"){
			jQuery("#redirection_url").val("<?php echo $redirection_url;?>");
				jQuery(".event_0").css("display","block");
		}
		if(event=="2"){
			jQuery('.revisit').css("display","block");
			jQuery("#splash_url").val("<?php echo $splash_url;?>");
			jQuery(".event_2").css("display","block");
			jQuery('#redd_url').val("<?php echo $redirect_m_url;?>");
		}
		if(event=="1"){
			jQuery('.revisit').css("display","block");
			jQuery('#redd_url').val("<?php echo $redirect_m_url;?>");
		}
		if(event!=""){
				jQuery("#redirection_url").val("<?php echo $redirection_url;?>");
		}
		var color_num="<?php echo $color_num;?>";
		if(color_num=="0"){
			jQuery("#color_num_black").attr("checked","checked");
		}
		if(color_num=="1"){
			jQuery("#color_num_custom").attr("checked","checked");
		}
		var back_color="<?php echo $back_color;?>";
		if(back_color=="0"){
			jQuery("#back_color_black").attr("checked","checked");
		}
		if(back_color=="1"){
			jQuery("#back_color_custom").attr("checked","checked");
		}
		var counter_size="<?php echo $counter_size;?>";
		var size_class=jQuery('#countdown_dashboard_1').attr('class');
		if(counter_size=="1"){
			jQuery("#medium_counter").attr("checked","checked");

		}
		if(counter_size=="0"){
			jQuery("#small_counter").attr("checked","checked");
			jQuery('#countdown_dashboard_1').removeClass(size_class).addClass('counter_small');
			jQuery('.dash ').removeClass('medium').removeClass('large').addClass('small');
		}
		if(counter_size=="2"){
			jQuery("#large_counter").attr("checked","checked");
			jQuery('#countdown_dashboard_1').removeClass(size_class).addClass('counter_large');
			jQuery('.dash ').removeClass('medium').removeClass('small').addClass('large');
		}
		var alignment="<?php echo $alignment;?>";
		if(alignment=="0"){
			jQuery("#left_align").attr("selected","selected");
			jQuery('#countdown_dashboard_1').css('float','left');
		}
		if(alignment=="1"){
			jQuery("#centered_align").attr("selected","selected");
			jQuery('#countdown_dashboard_1').css('float','none');
		}
		if(alignment=="2"){
			jQuery("#right_align").attr("selected","selected");
			jQuery('#countdown_dashboard_1').css('float','right');
		}
		jQuery("#<?php echo PageExpirationRobot::$DefaultCOunter;?>").attr("checked","checked");
		
		jQuery("#counter_style_<?php echo $counter_style;?>").attr("checked","checked");
		
		
});

	</script>
<?php
	include('campaign_form.php');
endwhile; ?>