<?php
require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/admin.php');

do_action('admin_init');
$form='<div id="per_editor-form"><table id="per_editor-table" class="form-table"><div class="per-wrapper"><div class="header"><div class="logo"><img src="'.WP_PLUGIN_URL.'/page-expiration-robot.3.0.6/images/PER_logo.png" style="height:34px;"></div><h2>Add Shortcode</h2></div><table style="width: 520px;" cellspacing="2" cellpadding="0">';
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
?>
(function(){
	// creates the plugin
	var PluginUrl;
	tinymce.create('tinymce.plugins.per_editor_button', {
		init : function(ed, url) {
			PluginUrl = url;
			 ed.addButton( 'per_editor_button', {
                    title : 'Insert shortcode',
                    image : PluginUrl + '/images/hourglass_plus.png',
                    onclick : function() {
                        jQuery(".hide_all").hide();
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Select Campaign', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=per_editor-form' );
							var content = tinyMCE.get('content').getContent();
							var found_content=findShortcodeid(content);
							if (found_content ==''){
								jQuery(".hide_all").show();
							}
							else{
								jQuery(".hide_all").hide();
								jQuery("#camp_row"+found_content).show();
							}
                    }
               });
			},
			createControl : function(n, cm) {
               return null;
          },
		
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('per_editor_button', tinymce.plugins.per_editor_button);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<?php echo $form; ?>');
		
		var table = form.find('table');
		form.appendTo('body').hide();

		
		// handles the click event of the submit button
		form.find('#per_editor-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'id'    : '',
				};
			var shortcode = '[COUNTDOWN';
			
			for( var index in options) {
				var value = table.find('#counter_selector').val();

				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor


			
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()
function findShortcodeid(content){
	var str = 'MyLongString:StringIWant;';
	var str2 = "[COUNTDOWN";
	if(content.indexOf(str2) != -1){
	var String=content.substring(content.lastIndexOf('[COUNTDOWN id="')+15,content.lastIndexOf('"]')-0);
	//alert(String);
	return String;
}
else{

return "";
}
	//var str2 = "[COUNTDOWN";
	//if(content.indexOf(str2) != -1){
	//	return false;
	//}
	//else{
	//return true;
	//}
}