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
		
		jQuery.ajax({
		  type: 'GET',
		  url: 'admin-ajax.php',
		  data: { action: 'get_my_form' },
		  success: function(response){
			  
			var form1 = response;
			var form = jQuery(response);
		
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
		
		 }
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