function get_flip_style(id){
	var floatdiv=jQuery( "input[type='radio'][name='alignment']:checked" ).val();
			if(floatdiv=="0"){
				floatdiv="left";
			}
			if(floatdiv=="1"){			
				floatdiv="none";
			}
			if(floatdiv=="2"){
				floatdiv="right";
			}

		var days_label_hide="block";
		var hrs_label_hide="block";
		var mins_label_hide="block";
		var secs_label_hide="block";
		if(jQuery('#hide_day_label').is(':checked')){
			var days_label_hide="none";
		}
		if(jQuery('#hide_hrs_label').is(':checked')){
			var hrs_label_hide="none";
		}
		if(jQuery('#hide_mins_label').is(':checked')){
			var mins_label_hide="none";
		}
		if(jQuery('#hide_sec_label').is(':checked')){
		var secs_label_hide="none";
		}

		var myhexcode=jQuery("#myhexcode").val();
		var myhexcode1=jQuery("#myhexcode1").val();
		//var sizeClass=jQuery( "input[type='radio'][name='counter_size']:checked" ).val();
		var sizeClass=jQuery('#counter_size').val();
		
			// if(sizeClass=="0"){
			// 	var sizeClass="small";
			// }
			// if(sizeClass=="1"){
			// 	var sizeClass="medium";
			// }
			// if(sizeClass=="2"){
			// 	var sizeClass="large";
			// }
		var cssClass=jQuery( "input[type='radio'][name='alignment']:checked" ).val();
		var daylabel=jQuery( "#daylabel" ).val();
		var hourslabel=jQuery( "#hourslabel" ).val();
		var minlabel=jQuery( "#minlabel" ).val();
		var seclabel=jQuery( "#seclabel" ).val();
		var labelcolor=jQuery("#labelcolor").val();
		var shadowcolor=jQuery("#shadowcolor").val();
		var ringcolor=jQuery("#ringcolor").val();
		flip_counter(id,sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide,ringcolor,floatdiv);	
}

function flip_counter(id,sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide,ringcolor,floatdiv)
{
	jQuery( "#tablecolor tr" ).eq(0).hide();
	jQuery( "#tablecolor tr" ).eq(1).hide();
	jQuery( "#tablecolor tr" ).eq(2).hide();
	jQuery( "#tablecolor tr" ).eq(3).hide();
	jQuery('#countercust_bottom tr').eq(5).hide();
	jQuery( ".ringcolor" ).hide();
	var circular=jQuery( "input[type='radio'][name='counter_style']:checked" ).val();
		var themestyle=jQuery( "input[type='radio'][name='flip_theme']:checked" ).val();
		var flipstyle=jQuery( "input[type='radio'][name='flip_style']:checked" ).val();
		if(flipstyle===undefined)
		{
			flipstyle='slide';
		}
		
		
			var stylesheet=jQuery("#counter_style_flip").data('val');
			jQuery('#wpfooter').append('<link class="circular_css" rel="stylesheet" id="circular_css-css"  href="'+stylesheet+'" type="text/css" media="all" />');
			// if(sizeClass=="large"){size=500;}
			// if(sizeClass=="medium"){size=350;}
			// if(sizeClass=="small"){size=250;}
			size =jQuery('#counter_size').val();
			var i=0;
				if(days_label_hide=="none")i++;
				if(hrs_label_hide=="none")i++;
				if(mins_label_hide=="none")i++;
				if(secs_label_hide=="none")i++;
				size=size-(69*i)+"%";
			jQuery('#countdown_dashboard_1').html("");
			jQuery('#countdown_dashboard_1').append('<div id="CountDownTimerl" style="margin: auto;"></div>');
			var sec_label=true;var hours_label=true;var min_label=true;var days_label=true;
			if(days_label_hide=="none"){days_label=false; }
			if(hrs_label_hide=="none"){hours_label=false; }
			if(mins_label_hide=="none"){min_label=false; }
			if(secs_label_hide=="none"){sec_label=false; }
			jQuery('#CountDownTimerl').jCountdown({
			timeText:'2040/01/01 00:00:00',
			timeZone:8,
			style:flipstyle,
			color:themestyle,
			width:size,
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
				alert('finish');
			}
		});
		var jCountdownContainer=jQuery('.jCountdownContainer').width();
		jQuery('#CountDownTimerl').width(jCountdownContainer);
		jQuery('#CountDownTimerl').height("76px");
		jQuery('#CountDownTimerl').css('float',floatdiv);
		jQuery('.day .label').html(daylabel);
		jQuery('.hour .label').html(hourslabel);
		jQuery('.minute .label').html(minlabel);
		jQuery('.second .label').html(seclabel);
		jQuery('.label').addClass('labelnew');
		jQuery('.labelnew').css({'color':labelcolor});
		jQuery('.labelnew').css({'text-shadow':'1px 1px  '+shadowcolor});
		jQuery('.label').removeClass('label');
}
 jQuery( "#counter_customization" ).click(function() {
	var counter=jQuery( "input[type='radio'][name='counter_style']:checked" ).val();
	if(counter=='flip'){
		get_flip_style();
	}
});
jQuery( "#counter_cust_style input" ).click(function() {
	var counter=jQuery( "input[type='radio'][name='counter_style']:checked" ).val();
	if(counter=='flip'){
		jQuery( "#tablecolor tr" ).eq(0).hide();
		jQuery( "#tablecolor tr" ).eq(1).hide();
		jQuery( "#tablecolor tr" ).eq(2).hide();
		jQuery( "#tablecolor tr" ).eq(3).hide();
		jQuery( ".ringcolor" ).hide();
	}
	else{
		jQuery( "#tablecolor tr" ).eq(0).show();
		jQuery( "#tablecolor tr" ).eq(1).show();
		jQuery( "#tablecolor tr" ).eq(2).show();
		jQuery( "#tablecolor tr" ).eq(3).show();
		jQuery( ".ringcolor" ).show();
	}
});
jQuery( "input[type='radio'][name='flip_style']" ).click(function() {

	get_flip_style();

});
jQuery( "input[type='radio'][name='flip_theme']" ).click(function() {
	get_flip_style();
});
jQuery( "input[type='text'][name='counter_size']" ).click(function() {

	get_flip_style();
});
jQuery( "input[type='radio'][name='alignment']" ).click(function() {

	get_flip_style();
});

jQuery( "#countercust_bottom input[type='checkbox']" ).click(function() {
	get_flip_style();
});
// jQuery('#obsolate_size_holders').hide();

	

jQuery(  "input[type='radio'][name='counter_style']").click(function() {
var m =jQuery( "input[type='radio'][name='counter_style']:checked" ).val();
	if(m=='flip')jQuery('#flip_field').slideDown();
	else jQuery('#flip_field').slideUp();
});

jQuery(document).ready(function(){
	jQuery( "#counter_customization input[type='text']" ).keyup(function() {
get_flip_style();
	});
jQuery('.color').bind('colorpicked', function () {
	get_flip_style();
		
	});

});