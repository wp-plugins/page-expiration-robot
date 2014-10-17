var PER = PER || {};

/* function to hide or show object with or without animation depending on animation function available or not */

/* 

	Indenifier : identifier of object (s) to show / hide 

	ShowHide   : Show - 1

				 Hide - 0

*/

PER.ShowHide = function (Identifier, ShowHide)

{

	

	switch (ShowHide)

	{

		case 0:

			if (jQuery(Identifier).slideUp)

			{

				jQuery(Identifier).slideUp();

			}	

			else

			{

				jQuery(Identifier).hide();

			}

			break;

		case 1:

			if (jQuery(Identifier).slideDown)

			{

				jQuery(Identifier).slideDown();

			}	

			else

			{

				jQuery(Identifier).show();

			}

			break;

	}

}



PER.dateDiff = function (currentdate,dates) {

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



PER.resetCampaignForm = function ()

{

	jQuery("#campaign_name").val("");

	jQuery("#expiry_method").val("0");

	jQuery("#expiry_date").val("");

	jQuery("#expiry_date_time_days").val("");

	jQuery("#expiry_date_time_hrs").val("");

	jQuery("#expiry_date_time_mins").val("");

	jQuery("#expiry_date_time_secs").val("");

	jQuery("#method_cookie").attr("checked","checked");

	jQuery("#position_content").attr("checked","checked");

	jQuery("#event_default_image").attr("checked","checked");



	jQuery("#redirection_url").val("");

	jQuery("#splash_url").val("");

	jQuery(".sub-wrapper").hide();

	jQuery("#redirection_url_wrap").show();
	
    jQuery('#shw_imgg').attr("checked","checked");
	

	jQuery("#color_num_black").attr("checked","checked");

	jQuery("#back_color_black").attr("checked","checked");

	jQuery("#medium_counter").attr("checked","checked");

	jQuery("#centered_align").attr("checked","checked");

	

	PER.ShowHide("#expiry_date_wrap",1);

}

PER.checkRequiredCampaignData = function()

{

	var no_error = true;

	jQuery('.required_err').removeClass('required_err');

	if (jQuery.trim(jQuery("#campaign_name").val()) == "")

	{

		jQuery("#campaign_name").addClass('required_err');

		no_error = false;

	}



	if (jQuery.trim(jQuery("#expiry_method").val()) == "0" && jQuery.trim(jQuery("#expiry_date").val()) == "")

	{

		jQuery("#expiry_date").addClass('required_err');

		no_error = false;

	}



	if (jQuery.trim(jQuery("#expiry_method").val()) == "2" )

	{

		if (jQuery.trim(jQuery("#expiry_date_time_days").val()) == "" && jQuery.trim(jQuery("#expiry_date_time_hrs").val()) == "" && jQuery.trim(jQuery("#expiry_date_time_mins").val()) == "" && jQuery.trim(jQuery("#expiry_date_time_secs").val()) == "")

		{

			jQuery(".time").addClass('required_err');

			no_error = false;

		}

		

		

	}



	

	if (jQuery(".event:checked").val() == "0" && jQuery.trim(jQuery("#redirection_url").val()) == "")

	{

		jQuery("#redirection_url").addClass('required_err');

		no_error = false;

	}

	if (jQuery(".event:checked").val() == "2" && jQuery.trim(jQuery("#splash_url").val()) == "")

	{

		jQuery("#splash_url").addClass('required_err');

		no_error = false;

	}

	if (jQuery(".event:checked").val() == "2" && jQuery.trim(jQuery("#redd_url").val()) == "")

	{

		jQuery("#redd_url").addClass('required_err');

		no_error = false;

	}

	if (jQuery(".event:checked").val() == "1" && jQuery.trim(jQuery("#redd_url").val()) == "")

	{

		jQuery("#redd_url").addClass('required_err');

		no_error = false;

	}

	jQuery('.requiredfield').each(function(i, obj) {

		if (jQuery(this).val()==""){

			jQuery(this).addClass('required_err');

			no_error = false;

		}

	});

	if(no_error==false){

		jQuery("#errormsg").show();

	}

	return no_error;

}



PER.CustomFile = function(){

	var attachEvnt = function(){

		jQuery(".btn-ch-file").on('click', function(){

			jQuery(this).parent().find('input[type="file"]').click();

			return false;

		});



		jQuery(".file-upload").on('change', function(){

			var fileName = jQuery(this).val();

			fileName = fileName.substr(fileName.lastIndexOf("\\")+1);

			jQuery("#splash_url").val(fileName);

		});

	};

	attachEvnt();

};



PER.OnCustomChange = function (str,flag,id)

{

	if(id=="myhexcode"){

		jQuery('.digit').css('color',str);

	}

	var counter_style=jQuery( "input[type='radio'][name='counter_style']:checked" ).val();

	

	if(id=="myhexcode1"){

		if(counter_style=="default"){

			jQuery('.digit').css('background',str);

		}

		else{

			jQuery('.dash').css('background',str);

		}

	}

	if(flag=="labelcolor"){

		jQuery('.dash_title').css('color',str);

	}

	if(flag=="shadowcolor"){

		jQuery('.dash_title').css('text-shadow','1px 1px '+str);

	}		

}



jQuery(document).ready(function(){

	PER.CustomFile();

	/* show / hide options related to selected expiry method */

	jQuery("#expiry_method").change(function(){

		PER.ShowHide(".expiry_method",0);

		PER.ShowHide(".expiry_method_"+jQuery("#expiry_method").val(),1);

	});

	/* show / hide options related to selected expiry method */

	/* show / hide options related to selected expiry event */

	jQuery(".event").change(function(){

		PER.ShowHide(".event_opts",0);

		PER.ShowHide(".event_"+jQuery(this).val(),1);

	});

	/* show / hide options related to selected expiry event */

	/* apply datepicker */

	jQuery('#expiry_date').cogdatetimepicker({

		//showOn: "button",

		//buttonImage: per_plugin_url+"/images/datetime.png",

		//buttonImageOnly: true,

		showSecond: true,

		showTime: false,

		showTimepicker: true,

		dateFormat: 'yy/mm/dd',

		timeFormat: 'hh:mm:ss',

		timeText: 'Expires On'

	});

	jQuery("#expiry_date").change(function(){

		

		var d=new Date();

		var dat=d.getDate();

		var months = new Array('01','02','03','04','05','06','07','08','09','10','11','12');

		var year=d.getFullYear();



		var currentdate = months[d.getMonth()]+"/"+dat+"/"+year;



		var date = jQuery("#expiry_date").val();

		dates = date.substring(0, 10);

		day = date.substring(3, 5);

		hour = 23;

		minute = 59;

		second = 59;

		jQuery("#expiry_hour").val(hour);

		jQuery("#expiry_minute").val(minute);

		jQuery("#expiry_second").val(second);	

		returndays = PER.dateDiff(currentdate,dates);



		var days = returndays;



		jQuery("#expiry_day").val(days);



	});

	/* apply datepicker */

	/* color picker */

	jQuery('.color').cogmColorPicker({

	  imageFolder1:per_plugin_url+'/images/'

	});

	jQuery('.color').bind('colorpicked', function () {

		jQuery(this).click();

		if (jQuery(this).attr("id") == "myhexcode1")

		{ 

		  jQuery("input:radio[name=back_color]").each(function(){

			  if (jQuery(this).val() == 1)

			  {

				  jQuery(this).attr("checked",true);

			  }

		  });

		}

		else if (jQuery(this).attr("id") == "myhexcode")

		{

		  jQuery("input:radio[name=color_num]").each(function(){

			  if (jQuery(this).val() == 1)

			  {

				  jQuery(this).attr("checked",true);

			  }

		  });

		}

		

	});

	/* color picker */

	jQuery(".required").change(function(){

		if (jQuery.trim(jQuery(this).val()) !="")

		{

			jQuery(this).removeClass("required_err");

		}

		else

		{

			jQuery(this).val('');

		}

	});



	jQuery("#btn_save").click(function(){

		if (PER.checkRequiredCampaignData())

		{

			jQuery(this).closest("form").submit();

		}

		

		return false;

	});

	PER.resetCampaignForm();

});

jQuery( "#counter_customization input[type='text']" ).change(function() {

			var id=jQuery( this ).attr('id');

			if(id=="daylabel" && !jQuery('#hide_day_label').is(":checked")){

				jQuery('.days_dash .dash_title').html(jQuery( this ).val());

			}

			if(id=="hourslabel" && !jQuery('#hide_hrs_label').is(":checked")){

				jQuery('.hours_dash .dash_title').html(jQuery( this ).val());

			}

			if(id=="minlabel" && !jQuery('#hide_mins_label').is(":checked")){

				jQuery('.minutes_dash .dash_title').html(jQuery( this ).val());

			}

			if(id=="seclabel" && !jQuery('#hide_sec_label').is(":checked")){

				jQuery('.seconds_dash .dash_title').html(jQuery( this ).val());

			}

			

		});

		jQuery( "#counter_customization input[type='checkbox']" ).click(function() {

			var id=jQuery( this ).attr('id');

			if(id=='hide_day_label'){

				if (jQuery(this).is(":checked")){

					jQuery('.days_dash').css('display','none');

				}

				else{

					jQuery('.days_dash').css('display','block');

				}

			}

			if(id=='hide_hrs_label'){

				if (jQuery(this).is(":checked")){

					jQuery('.hours_dash').css('display','none');

				}

				else{

					jQuery('.hours_dash').css('display','block');

				}

			}

			if(id=='hide_mins_label'){

				if (jQuery(this).is(":checked")){

					jQuery('.minutes_dash').css('display','none');

				}

				else{

					jQuery('.minutes_dash').css('display','block');

				}

			}

			if(id=='hide_sec_label'){

				if (jQuery(this).is(":checked")){

					jQuery('.seconds_dash').css('display','none');

				}

				else{

					jQuery('.seconds_dash').css('display','block');

				}

			}



		});



		jQuery( "#counter_customization input[type='radio'][name='counter_size']" ).click(function() {

			var id=jQuery( this ).val();

			var size_class=jQuery('#countdown_dashboard_1').attr('class');

			var digit_class=jQuery('.dash').attr('class');

			 

			if(id=="0"){

				jQuery('#countdown_dashboard_1').removeClass(size_class).addClass('counter_small');

				jQuery('.dash ').removeClass('medium').removeClass('large').addClass('small');	

			}

			if(id=="1"){

				jQuery('#countdown_dashboard_1').removeClass(size_class).addClass('counter_medium');

				jQuery('.dash ').removeClass('large').removeClass('small').addClass('medium');

				

			}

			if(id=="2"){

				jQuery('#countdown_dashboard_1').removeClass(size_class).addClass('counter_large');

				jQuery('.dash ').removeClass('medium').removeClass('small').addClass('large');			

			}

		});

		jQuery( "#counter_customization #alignment" ).change(function() {

			

			var id=jQuery( this ).val();

			if(id=="0"){

				jQuery('#countdown_dashboard_1').css('float','left');

			}

			if(id=="1"){			

				jQuery('#countdown_dashboard_1').css('float','none');

			}

			if(id=="2"){

				jQuery('#countdown_dashboard_1').css('float','right');

			}

		});

		function get_counter_demo(id) {

			

				//var id=jQuery( this ).val();

				

				var counter=get_counter_style(id);



				jQuery('#countdown_dashboard_1').html(counter);

			}



function get_counter_style(id){

		var stylesheet=jQuery('#counter_style_'+id).data('val');

		jQuery('#per_main_css-css').remove();

		jQuery('head').append('<link id="per_main_css-css" href="'+stylesheet+'" rel="stylesheet" id="per_main_css-css" />');

			

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

		var ncolor=jQuery( "input[type='radio'][name='color_num']:checked" ).val();

		if(ncolor==0){

			myhexcode="#fff";

		}



		var myhexcode1=jQuery("#myhexcode1").val();

		var ncolor=jQuery( "input[type='radio'][name='back_color']:checked" ).val();

			if(ncolor==0){

				myhexcode1="#000";

			}

		var sizeClass=jQuery( "input[type='radio'][name='counter_size']:checked" ).val();

			if(sizeClass=="0"){

				var sizeClass="small";

			}

			if(sizeClass=="1"){

				var sizeClass="medium";

			}

			if(sizeClass=="2"){

				var sizeClass="large";

			}

		var cssClass=jQuery( "#counter_customization #alignment" ).val();

		var daylabel=jQuery( "#daylabel" ).val();

		var hourslabel=jQuery( "#hourslabel" ).val();

		var minlabel=jQuery( "#minlabel" ).val();

		var seclabel=jQuery( "#seclabel" ).val();

		var labelcolor=jQuery("#labelcolor").val();

		var shadowcolor=jQuery("#shadowcolor").val();

		

		var counter_default=pass_counter_value(id,sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide);

		return counter_default;

}

function pass_counter_value(id,sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide)

{

	var counter_default="";

	if(id=="default"){

		var counter_default=get_counter_default(sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide);

	}

	if(id=="slidedown"){

		

		var counter_default=get_counter_slidedown(sizeClass,cssClass,myhexcode,myhexcode1,daylabel,hourslabel,minlabel,seclabel,labelcolor,shadowcolor,days_label_hide,hrs_label_hide,mins_label_hide,secs_label_hide);

	}

	return counter_default;

}

function removejscssfile(filename, filetype){

 var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from

 var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for

 var allsuspects=document.getElementsByTagName(targetelement)

 for (var i=allsuspects.length; i>=0; i--){ //search backwards within nodelist for matching elements to remove

  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)

   allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()

 }

}



