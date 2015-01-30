<?php
if ( current_user_can( 'manage_options' ) ) {
    ?>
    <script>(function() { 
var _fbq = window._fbq || (window._fbq = []); 
if (!_fbq.loaded) { 
var fbds = document.createElement('script'); 
fbds.async = true; 
fbds.src = '//connect.facebook.net/en_US/fbds.js'; 
var s = document.getElementsByTagName('script')[0]; 
s.parentNode.insertBefore(fbds, s); 
_fbq.loaded = true; 
} 
_fbq.push(['addPixelId', '695751367199747']); 
})(); 
window._fbq = window._fbq || []; 
window._fbq.push(['track', 'PixelInitialized', {}]); 
</script> 
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=695751367199747&amp;ev=PixelInitialized" /></noscript>

    <?php
} 
?>
<div class="per-wrapper pageExp">
  <div class="header">
    <div class="logo"><img src="<?php echo $this->PluginURL;?>/images/PER_logo.png" ></div>
    <h2>
      <?php if(isset($_GET['pid'])){ echo "Edit";}else {echo "Add New";}?>
      Campaign</h2>
    <a href="?page=page_expiration_robot_new" class="add-new-h2">Add New</a>
    <?php
    if (!$AllowAdd && (!isset($editCampaign) || $editCampaign != 1))
    {
    ?>
    <h3 class="bottom_message">Oops! You have exceeded the number of campaigns allowed for this version. <br/>
      Please <a href="http://www.pageexpirationrobot.com/v2/add-ons/unlimited-campaigns">click here</a> to unlock the "Unlimited Campaigns" add-on!.</h3>
    <?php 
    }
    ?>
    <?php
        if(isset($_GET['pid']))
        {
            
        ?>
            

    <button class="per-green-button" onclick="jQuery('#btn_save').click();" >
    <?php if (isset($_GET['pid']) && $_GET['pid'] > 0)
    {echo "Update";}else echo "Create";?>
    </button>
    <?php
    }
        ?>
  </div>
  <?php if (isset ($_GET['message'])){
  echo '<div id="message" class="updated below-h2"><p>Campaign published. </p></div>';
} ?>
<?php if (isset ($_GET['updated'])){
  echo '<div id="message" class="updated below-h2"><p>Campaign updated. </p></div>';
} ?>
  <div class="clearfix"></div>
  <div class="wrapper">
    <form method="post" enctype="multipart/form-data">
      <div class="step-box-wrapper paddRight">
        <div class="step-box">
          <div class="headPart"> <span class="step">1</span>
            <h3>Campaign Details</h3>
          </div>
          <span class="errorText" id="errormsg"> * Highlighted fields must not be blank </span>
          <div class="row">
            <h3>What should the campaign be called? </h3>
            <input type="text" name="campaign_name" id="campaign_name" class="fullwidth">
          </div>
          <div class="row">
            <h3>When would you like your offer to expire?</h3>
            <select name="expiry_method" id="expiry_method" class="fullwidth">
              <option value="0">A Specific Date</option>
              <option value="2">A Specific amout of time (Evergreen)</option>
              <option value="1">First visit only (show the offer once per visitor)</option>
              <option id="after_event_sales_offer"  value="3_0ffer">After Specific # of Actions or Sales</option>
              <?php do_action('per_print_expiry_method_select_opt');?>
            </select>
            <div id="expiry_date_wrap" class="sub-wrapper expiry_method expiry_method_0 <?php //echo apply_filters('per_print_expiry_date_select_css_class','');?>">
              <input type="text" name="expiry_date" id="expiry_date" readonly class="midwidth" placeholder="Select Date">
              <select name="time_zone" id="time_zone" class="midwidth rightmarNo">
                <?php global $timeZones;
              foreach ($timeZones as $timeZone=>$city)
              {
                $selected = "";
                if ($timeZone == $selected_timezone)
                  $selected = " selected ";
                echo "<option value='".$timeZone."'".$selected.">".$city."</option>";
              }
              ?>
              </select>
            </div>
            <div id="expiry_time_wrap" class="sub-wrapper expiry_method expiry_method_2 <?php echo apply_filters('per_print_expiry_time_select_css_class','');?>">
              <select name="expiry_date_time_days" id="expiry_date_time_days"  class="time smallwidth">
                <option value=""  selected="selected">Days</option>
                <?php for($i=0;$i<31;$i++){if($expiry_date_time_days != "" && $expiry_date_time_days == $i){$selected = "selected";}else{$selected = "";} echo '<option value="'.$i.'" '.$selected.'>'.(strlen($i)==1?"0".$i:$i).' Days</option>';}?>
              </select>
              <select name="expiry_date_time_hrs" id="expiry_date_time_hrs"  class="time smallwidth">
                <option value=""  selected="selected">Hrs</option>
                <?php for($i=0;$i<24;$i++){if($expiry_date_time_hrs != "" && $expiry_date_time_hrs == $i){$selected = "selected";}else{$selected = "";}echo '<option value="'.$i.'" '.$selected.'>'.(strlen($i)==1?"0".$i:$i).' Hrs</option>';}?>
              </select>
              <select name="expiry_date_time_mins" id="expiry_date_time_mins" class="time smallwidth">
                <option value=""  selected="selected">Mins</option>
                <?php for($i=0;$i<60;$i++){if($expiry_date_time_mins != "" && $expiry_date_time_mins == $i){$selected = "selected";}else{$selected = "";} echo '<option value="'.$i.'" '.$selected.'>'.(strlen($i)==1?"0".$i:$i).' Mins</option>';}?>
              </select>
              <select name="expiry_date_time_secs" id="expiry_date_time_secs"  class="time smallwidth rightmarNo">
                <option value=""  selected="selected">Secs</option>
                <?php for($i=0;$i<60;$i++){if($expiry_date_time_secs != "" && $expiry_date_time_secs == $i){$selected = "selected";}else{$selected = "";} echo '<option value="'.$i.'" '.$selected.'>'.(strlen($i)==1?"0".$i:$i).' Secs</option>';}?>
              </select>
            </div>
          </div>
          <div class="row sub-wrapper expiry_method expiry_method_2 expiry_method_1">
            <h3>How would like your visitors to get expired?</h3>
            <input type="radio" name="method" id="method_cookie" value="cookie">
            <label>Expire visitors by Cookie</label>
            <br clear="all" />
            <span id="method_ip_offer_holder">
            <input type="radio" name="method" id="method_ip_offer" value="cookie" disabled="disabled">
            <label>Expire visitors by IP <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons">Enable</a></span></label>
            <br clear="all" />
            <?php do_action('per_print_expiry_visiters_options');?>
          </div>
          <?php do_action('per_print_expiry_method_options');?>
        </div>
        <div  class="step-box">
          <div class="headPart"> <span class="step">2</span>
            <h3>On Finish Event</h3>
          </div>
          <div class="row" id="event_expiry_options">
            <h3>What should happen when your timer reaches zero?</h3>
            <input type="radio" name="event" id="event_redirect" value="0" class="event">
            <label>Redirect to URL</label>
            <br clear="all" />
            <div id="redirection_url_wrap" class="">
              <label>Redirect to URL</label>
              <br/>
              <input type="text" name="redirection_url" id="redirection_url" class="medium_input">
              <br clear="all" />
            </div>
            <input type="radio" name="eventf" id="shw_imgg" value="1" class="event">
            <label>Show image and redirect when re-visiting</label>
            <br clear="all" />
            <div class="revisit" style="padding-left:20px;display:none;">
            <input type="radio" name="event" id="event_default_image" value="1" class="event">
            <label>Show default image</label>
            <a href="<?php echo $this->PluginURL."/images/expired-notice.png";?>" target="_blank">preview</a> <br clear="all" />
            <input type="radio" name="event" id="event_show_own_image" value="2" class="event">
            <label>Show own image</label>
            <br clear="all" />
            <div id="splash_wrap" class="sub-wrapper event_opts event_2">
              <input type="text" name="splash_url" id="splash_url" class="medium_input">
              <a href="" class="btn-ch-file">Upload</a>
              <input type="file" name="splash-image" id="splash-image" class="file-upload required" accept="image/*" data-file-name-length="22">
            </div>
            <input type="text" name="redirect_m_url" id="redd_url" class="medium_input" placeholder="Redirecting to URL...">
            </div>
            <span id="timed_event_offer">
            <input type="radio" class="event" value="3" disabled="disabled" >
            <label>Show timed event(content) <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons"> Enable</a></label>
            <br clear="all" />
            </span> 

            <span id="save_as_draft_offer">
            <div style="display: block;" class="expiry_method expiry_method_0 expiry_method_3">
              <input type="radio" class="event" value="4" id="" disabled="disabled" >
              <label>Save Page / Post as Draft <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons"> Enable</a></label>
              <br clear="all" />
            </div>
            </span>

            <span id="staty_on_same_page_offer_teaser">
            <div style="display: block;" >
              <input type="radio" class="event" value="4" id="" disabled="disabled" >
              <label>Do nothing (Stay on same page) <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons"> Enable</a></label>
              <br clear="all" />
            </div>
            </span>

            <?php do_action('per_print_expiry_event_select_opt');?>
          
          </div>
        </div>
        <div class="step-box">
          <div class="headPart"> <span class="step">3</span>
            <h3>Counter Location</h3>
          </div>
          <div class="row">
            <h3>Where would you like to place your timer?</h3>
            <input type="radio" name="position" id="position_content" value="c">
            <label>In the content</label>
            <br clear="all" />
            <input type="radio" name="position" id="position_invisible" value="invisible">
            <label>Nowhere (invisible)</label>
            <br clear="all" />
            <span id="in_the_headerr">
            <input type="radio" name="position" id="position_headerr" value="" disabled="disabled">
            <label>In the header <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons"> Enable</a></label>
            <br clear="all" />
            </span> 
            <span id="in_the_footerr">
            <input type="radio" name="position" id="position_footerr" value="" disabled="disabled">
            <label>In the footer <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons"> Enable</a></label>
            <br clear="all" />
            </span> 
            <?php do_action('per_print_counter_position_select_opt');?>
          </div>
        </div>
      </div>
      <div class="step-box-wrapper paddLeft" id="counter_customization"> 
        <!--Box For Counter customization -->
        <div class="step-box">
          <div class="headPart">
            <h3>Counter Customization </h3>
          </div>
          <div class="row">
            <?php 
            $id="";
            if (!isset($_GET['pid'])){
              $days_label = "DAYS";
              $hours_label = "HOURS";
              $min_label = "MINUTES";
              $sec_label = "SECONDS";
              $label_color = "#000";
              $shadow_color = "#fff";
              $myhexcode ="#fff"; 
              $myhexcode1="#000";
            } 
            ?>
            <table id="counter_customization" width="100%">
              <tr>
                <td style="width:56%;" valign="top">
                  <table border='0' id="tablecolor" width="100%">
                    <tr>
                      <td colspan="3"></td>
                    </tr>
                    <tr>
                      <td ></td>
                      <td ></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td ></td>
                      <td ></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="2" valign="top">
                        <h3>Counter Size</h3>
                        <div id="slider-range-min"></div>
                        <input type='text' class="notfirst notaction" name='counter_size' id='counter_size' readonly  /> px
                      </td>
                      <td colspan="1" valign="top" >
                        <h3>Alignment</h3>
                      <select name="alignment" id="alignment"  class="fullwidth">
                          <option value="0" id="left_align">Left</option>
                          <option value="1" id="centered_align"  >Centered</option>
                          <option value="2" id="right_align">Right</option>
                      </select>
                      </td>
                    </tr>
                    
                    <!--<tr>
                      <td>&nbsp;
                        <input type='radio' class="notfirst notaction" id="left_align" name='alignment' value='0' />
                        &nbsp;
                        <label for="left " >left </label></td>
                      <td ><input type='radio' class="notfirst notaction" name='alignment' value='1'  id="centered_align"/>
                        &nbsp;
                        <label for="Centered  ">Centered </label></td>
                      <td ><input type='radio' class="notfirst notaction" name='alignment' value='2'  id="right_align"/>
                        &nbsp;
                        <label for="Right ">Right</label></td>
                    </tr>-->
                  </table>
                </td>
              </tr>
              <tr>
                <td valign="top"><?php /* << CUSTOM LABEL */ ?>
                  <table id="countercust_bottom" width="100%">
                    <tr>
                      <td colspan="3"><h3>Hide & Rename Label</h3></td>
                    </tr>
                    <tr>
                      <td width="25%">
                        <input type='checkbox' class="notfirst notaction" name='hid_label[hide_day_label]' id='hide_day_label'  value='1'/>
                        <input type='text' name='days_label' id='daylabel' value='<?php echo $days_label;?>' style="width:70%;" class="notfirst notaction" />
                      </td>
                      <td width="25%">
                        <input type='checkbox'class="notfirst notaction"  name='hid_label[hide_hrs_label]' id="hide_hrs_label" value='1' />
                        <input type='text' name='hours_label' id="hourslabel" value='<?php echo $hours_label;?>' style="width:70%;" class="notfirst notaction" />
                      </td>
                      <td width="25%">
                        <input type='checkbox' class="notfirst notaction" name='hid_label[hide_mins_label]' id="hide_mins_label" value='1'/>
                        <input type='text' name='min_label' id='minlabel' value='<?php echo $min_label;?>' style="width:70%;" class="notfirst notaction" />
                      </td>
                      <td width="25%">
                        <input type='checkbox' class="notfirst notaction" name='hid_label[hide_sec_label]' id="hide_sec_label"  value='1'/>
                        <input type='text' name='sec_label' id="seclabel" value='<?php echo $sec_label;?>' style="width:70%;" class="notfirst notaction" />
                      </td>
                    </tr>
                    
                   <!-- <tr>
                      <td colspan="2">Label Text Color</td>
                      <td colspan="2">&nbsp;
                        <input type='color' name='label_color' id='labelcolor' value='<?php echo str_replace("0x","#",$label_color);?>' style="width:70px;" class="color notfirst notaction" data-hex="true" onclick="PER.OnCustomChange(this.value,'labelcolor','<?php echo $id ?>');"/></td>
                    </tr>
                    <tr>
                      <td colspan="2">Label Shadow Color</td>
                      <td colspan="2">&nbsp;
                        <input type='color' name='shadow_color' data-hex="true" id="shadowcolor" value='<?php echo str_replace("0x","#",$shadow_color);?>' style="width:70px;" class="color notfirst notaction" onclick="PER.OnCustomChange(this.value,'shadowcolor','<?php echo $id ?>');"/></td>
                    </tr>-->
                    <?php do_action('print_customization_option');?>
                  </table>
                 </td>
              </tr>
              <tr>
                <td>
                  <h3>Style</h3>
                    <div class="row" ID="counter_cust_style">
                  <div class="clock_time_offer"> Need more countdown styles? <a href="<?php echo trailingslashit(site_url())?>wp-admin/admin.php?page=page_expiration_robot_addons">Click here</a></div>
            <?php do_action('print_counter_style_options');?>
                  </div>
                </td>
              </tr>
              <tr>
                <td style="padding-top:15px;">
                  <h3>Counter preview</h3>
                  <!-- counter Start -->
                    <div class="per_counter_wrapper">
                      <div id="countdown_dashboard_1" style="margin:0px auto; width:502px;display:block; height:255px; text-align:left;float:none;" class="counter_medium">
                        <div style="margin:0px auto;float:none;width:300px;" class="main_counter_wrap"> 
                          <script language="javascript" type="text/javascript">
                                        jQuery.noConflict();jQuery(document).ready(function($) {
                    
                                            jQuery('#countdown_dashboard_1').countDown({targetOffset: 
                                                {'day':11==''?0:11,'month':0,'year':0,'hour':11==''?0:11,'min':11==''?0:11,'sec':11==''?0:11},
                                                omitWeeks:true,
                                                onComplete:function(){
                                                    perCounterFinished=true;setTimeout('showExpireImage(1)',0);
                                                     }
                                                    });
                                        });
                                </script>
                          <?php
                                if (!isset($_GET['pid'])){
                                        $counter_style=PageExpirationRobot::$DefaultCOunter;
                                }
                                do_action('get_counter_demo',$counter_style); ?>
                        </div>
                      </div>
                      <!--Counter End -->
                      <div class="clearfix"></div>
                      <div> </div>
                    </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <!--Box For Counter customization END-->
        <div class="clearfix"></div>
        
      </div>
      <div class="row bottomPart">
        <?php
          if (!$AllowAdd && (!isset($editCampaign) || $editCampaign != 1))
          {
          ?>
        <h3 class="bottom_message">Oops! You have exceeded the number of campaigns allowed for this version.<br/>
          Please <a href="http://www.pageexpirationrobot.com/v2/add-ons/unlimited-campaigns">click here</a> to unlock the "Unlimited Campaigns" add-on!.</h3>
        <?php
          }
          else
          {
          ?>
        <button class="per-green-button" id="btn_save">
        <?php if (isset($_GET['pid']) && $_GET['pid'] > 0)
      {echo "Update";}else echo "Create";?>
        </button>
        <?php
          }
          ?>
      </div>
      <div class="clearfix"></div>
    </form>
  </div>
</div>
<script>
jQuery(function($) {
$( "#slider-range-min" ).slider({
range: "min",
min: 300,
max: 544,
slide: function( event, ui ) {
$( "#counter_size" ).val(ui.value );
get_flip_style();
}
});
$( "#counter_size" ).val( $( "#slider-range-min" ).slider( "value" ) );
});
</script> 
<script>


jQuery(window).load(function(){
  setTimeout(function () { get_flip_style();}, 500);
});

jQuery(document).ready(function(){

var style_count =jQuery('#counter_cust_style input[type=radio]').length;
if(style_count>0)
{
  jQuery('.clock_time_offer').remove();
}

  jQuery('#expiry_method').on('change',function(){

    if(jQuery(this).val()=='3_0ffer')
    {
      //alert(jQuery(this).val());
      jQuery('.expiry_method').hide();
      jQuery('.expiry_method_offer_custom_messege').remove();
      var html ='<div class="expiry_method_offer_custom_messege"><span style="color:red;">Oops! you do not have access to this feature </span> <a href="http://www.pageexpirationrobot.com/addons/action_reach" > Click to unlock this add-on! </a> </div>'
      jQuery(this).after(html);
    }
    else if(jQuery(this).val()=='3'){
        console.log('Action Reacher');
        jQuery('.paddLeft .row').eq(0).addClass('noOfAction');
    }
    else
    {
      jQuery('.paddLeft .row').removeClass('noOfAction');
      jQuery('.expiry_method_offer_custom_messege').remove();
    }


  });

  jQuery('#event_expiry_options input[type=radio]').each(function(){




      jQuery(this).on('click',function(){

        if(jQuery(this).attr('id')=='event_redirect')
        {
          jQuery('#redirection_url_wrap').show()
                          .css('display','block');
        }else
        {
          jQuery('#redirection_url_wrap').hide()
                  .css('display','none');
        }
        
      });

      jQuery(this).on('click',function(){
        console.log(jQuery(this).attr('id'));
        if(jQuery(this).attr('id')=='shw_imgg')
        {
            jQuery('#event_default_image').attr('checked','checked');
        }
        if((jQuery(this).attr('id')=='shw_imgg') || (jQuery(this).attr('id')=='event_show_own_image') || (jQuery(this).attr('id')=='event_default_image'))
        {
          jQuery('.revisit').show();
        }else
        {
          jQuery('#shw_imgg').removeAttr('checked');
          jQuery('.revisit').hide();
        }
        
      });
          
  });

        jQuery("#shw_imgg").click(function(){
          jQuery('#event_expiry_options input[type=radio]').each(function(){
              if((jQuery(this).attr('id') != 'event_default_image') && (jQuery(this).attr('id') != 'event_show_own_image') && (jQuery(this).attr('id') != 'shw_imgg'))
              {
                  jQuery(this).removeAttr('checked');
              }

           })
      })




  setInterval(function() {
    //console.log('fired');
      if(jQuery('#event_redirect').is(':checked'))
   {
    
    jQuery('#redirection_url_wrap').show().css('display','block');
   }else
   {
    
    jQuery('#redirection_url_wrap').hide()
                  .css('display','none');
   }
}, 100);


  setInterval(function() {
    if((jQuery("#event_show_own_image").prop("checked")) || (jQuery("#event_default_image").prop("checked")))
                  {
                      jQuery("#shw_imgg").attr("checked","checked");
                  }
}, 100);



  setInterval(function() {
    //console.log('fired11');
      if((jQuery('#shw_imgg').is(':checked')) || (jQuery('#event_show_own_image').is(':checked')) || (jQuery('#event_default_image').is(':checked')))
   {
    
    jQuery('.revisit').show().css('display','block');
   }else
   {
    
    jQuery('.revisit').hide()
                  .css('display','none');
   }
}, 100);   

    
  });


jQuery(document).ready(function($) {

    // Hide the toTop button when the page loads.
     $("#toTop").css("display", "none");
     
     // This function runs every time the user scrolls the page.
     //$(window).scroll(function(){
     
    // // Check weather the user has scrolled down (if "scrollTop()"" is more than 0)
    //  if($(window).scrollTop() > 300){
     
    // // If it's more than or equal to 0, show the toTop button.
    //  console.log("is more");
    //  $("#toTop").fadeIn("slow");
    //  $('#counter_customization').addClass('fixed');
    //  }
    //  else {
    //  // If it's less than 0 (at the top), hide the toTop button.
    //  console.log("is less");
    //  $("#toTop").fadeOut("slow");
    //    $('#counter_customization').removeClass('fixed');
    // }
    //  });

     $(window).on('scroll', function() {
 $('#counter_customization').removeClass('fixed');

        if($(window).scrollTop() >= $('#counter_customization').offset().top) {
          $('#counter_customization').addClass('fixed');
        } else {
          $('#counter_customization').removeClass('fixed');
        }
     })
});
 

</script> 