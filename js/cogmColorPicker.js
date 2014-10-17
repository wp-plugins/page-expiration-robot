/*
  cogmColorPicker
  Version: 1.0 r35
  
  Copyright (c) 2010 Meta100 LLC.
  http://www.meta100.com/
  
  Licensed under the MIT license 
  http://www.opensource.org/licenses/mit-license.php 
*/

// After this script loads set:
// $.fn.cogmColorPicker.init.replace = '.myclass'
// to have this script apply to input.myclass,
// instead of the default input[type=color]
// To turn of automatic operation and run manually set:
// $.fn.cogmColorPicker.init.replace = false
// To use manually call like any other jQuery plugin
// $('input.foo').cogmColorPicker({options})
// options:
// imageFolder1 - Change to move image location.
// swatches - Initial colors in the swatch, must an array of 10 colors.
// init:
// $.fn.cogmColorPicker.init.enhancedSwatches - Turn of saving and loading of swatch to cookies.
// $.fn.cogmColorPicker.init.allowTransparency - Turn off transperancy as a color option.
// $.fn.cogmColorPicker.init.showLogo - Turn on/off the meta100 logo (You don't really want to turn it off, do you?).

(function($){
if (typeof $.fn.cogmColorPicker == "undefined")
{
  var $o;
 
  $.fn.cogmColorPicker = function(options) {

    $o = $.extend($.fn.cogmColorPicker.defaults, options);
	
		
	
    if ($o.swatches.length < 10) $o.swatches = $.fn.cogmColorPicker.defaults.swatches
    if ($("div#cogmColorPicker").length < 1) $.fn.cogmColorPicker.drawPicker();

    if ($('#css_disabled_color_picker').length < 1) $('head').prepend('<style id="css_disabled_color_picker" type="text/css">.cogmColorPicker[disabled] + span, .cogmColorPicker[disabled="disabled"] + span, .cogmColorPicker[disabled="true"] + span {filter:alpha(opacity=50);-moz-opacity:0.5;-webkit-opacity:0.5;-khtml-opacity: 0.5;opacity: 0.5;}</style>');

    $('.cogmColorPicker').live('keyup', function () {

      try {
  
        $(this).css({
          'background-color': $(this).val()
        }).css({
          'color': $.fn.cogmColorPicker.textColor($(this).css('background-color'))
        }).trigger('change');
      } catch (r) {}
    });

    $('.cogmColorPickerTrigger').live('click', function () {

      $.fn.cogmColorPicker.colorShow($(this).attr('id').replace('icp_', ''));
    });

    this.each(function () {

      $.fn.cogmColorPicker.drawPickerTriggers($(this));
    });

    return this;
  };

  $.fn.cogmColorPicker.currentColor = false;
  $.fn.cogmColorPicker.currentValue = false;
  $.fn.cogmColorPicker.color = false;

  $.fn.cogmColorPicker.init = {
    replace: '[type=color]',
    index: 0,
    enhancedSwatches: true,
    allowTransparency: true,
  	checkRedraw: 'DOMUpdated', // Change to 'ajaxSuccess' for ajax only or false if not needed
  	liveEvents: false,
    showLogo: true
  };

  $.fn.cogmColorPicker.defaults = {
    imageFolder1: '../wp-content/plugins/page-expiration-robot/images/',
    swatches: [
      "#ffffff",
      "#ffff00",
      "#00ff00",
      "#00ffff",
      "#0000ff",
      "#ff00ff",
      "#ff0000",
      "#4c2b11",
      "#3b3b3b",
      "#000000"
    ]
  };

  $.fn.cogmColorPicker.liveEvents = function() {

    $.fn.cogmColorPicker.init.liveEvents = true;

    if ($.fn.cogmColorPicker.init.checkRedraw && $.fn.cogmColorPicker.init.replace) {

      $(document).bind($.fn.cogmColorPicker.init.checkRedraw + '.cogmColorPicker', function () {

        $('input[data-cogmColorPicker!="true"]').filter(function() {
    
          return ($.fn.cogmColorPicker.init.replace == '[type=color]')? this.getAttribute("type") == 'color': $(this).is($.fn.cogmColorPicker.init.replace);
        }).cogmColorPicker();
      });
    }
  };
   

  $.fn.cogmColorPicker.drawPickerTriggers = function ($t) {
	  
	  
    if ($t[0].nodeName.toLowerCase() != 'input') return false;

    var id = $t.attr('id') || 'color_' + $.fn.cogmColorPicker.init.index++,
        hidden = false;

    $t.attr('id', id);
  
    if ($t.attr('text') == 'hidden' || $t.attr('data-text') == 'hidden') hidden = true;

    var color = $t.val(),
        width = ($t.width() > 0)? $t.width(): parseInt($t.css('width'), 10),
        height = ($t.height())? $t.height(): parseInt($t.css('height'), 10),
        flt = $t.css('float'),
        image = (color == 'transparent')? "url('" + $o.imageFolder1 + "/grid.gif')": '',
        colorPicker = '';

    $('body').append('<span id="color_work_area"></span>');
    $('span#color_work_area').append($t.clone(true));
    colorPicker = $('span#color_work_area').html().replace(/type="color"/gi, '').replace(/input /gi, (hidden)? 'input type="hidden"': 'input type="text"');
    $('span#color_work_area').html('').remove();
    $t.after(
      (hidden)? '<span style="cursor:pointer;border:1px solid black;float:' + flt + ';width:' + width + 'px;height:' + height + 'px;" id="icp_' + id + '">&nbsp;</span>': ''
    ).after(colorPicker).remove();   

    $("#" + id).val(color);

    if (hidden) {

      $('#icp_' + id).css({
        'background-color': color,
        'background-image': image,
        'display': 'inline-block'
      }).attr(
        'class', ($('#' + id).attr('class') || '') + ' cogmColorPickerTrigger'
      );
    } else {
   
      $('#' + id).css({
        'background-color': color,
        'background-image': image
      }).css({
        'color': $.fn.cogmColorPicker.textColor($('#' + id).css('background-color'))
      }).after(
	          '<span style="cursor:pointer;" id="icp_' + id + '" class="cogmColorPickerTrigger"><img src="' + $o.imageFolder1 + 'color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>'
      ).addClass('cogmColorPickerInput');
    }

    $('#icp_' + id).attr('data-cogmColorPicker', 'true');

    $('#' + id).addClass('cogmColorPicker');

    return $('#' + id);
  };

  $.fn.cogmColorPicker.drawPicker = function () {

    $(document.createElement("div")).attr(
      "id","cogmColorPicker"
    ).css(
      'display','none'
    ).html(
      '<div id="cogmColorPickerWrapper"><div id="cogmColorPickerImg" class="mColor"></div><div id="cogmColorPickerImgGray" class="mColor"></div><div id="cogmColorPickerSwatches"><div class="mClear"></div></div><div id="cogmColorPickerFooter"><input type="text" size="8" id="cogmColorPickerInput"/></div></div>'
    ).appendTo("body");

    $(document.createElement("div")).attr("id","cogmColorPickerBg").css({
      'display': 'none'
    }).appendTo("body");

    for (n = 9; n > -1; n--) {

      $(document.createElement("div")).attr({
        'id': 'cell' + n,
        'class': "mPastColor" + ((n > 0)? ' mNoLeftBorder': '')
      }).html(
        '&nbsp;'
      ).prependTo("#cogmColorPickerSwatches");
    }

    $('#cogmColorPicker').css({
      'border':'1px solid #ccc',
      'color':'#fff',
      'z-index':999998,
      'width':'194px',
      'height':'184px',
      'font-size':'12px',
      'font-family':'times'
    });

    $('.mPastColor').css({
      'height':'18px',
      'width':'18px',
      'border':'1px solid #000',
      'float':'left'
    });

    $('#colorPreview').css({
      'height':'50px'
    });

    $('.mNoLeftBorder').css({
      'border-left':0
    });

    $('.mClear').css({
      'clear':'both'
    });

    $('#cogmColorPickerWrapper').css({
      'position':'relative',
      'border':'solid 1px gray',
      'z-index':999999
    });
    
    $('#cogmColorPickerImg').css({
      'height':'128px',
      'width':'192px',
      'border':0,
      'cursor':'crosshair',
	'background-image':"url('" + $o.imageFolder1 + "colorpicker.png')"
      //'background-image':"url('" + $o.imageFolder1 + "colorpicker.png')"
    });
    
    $('#cogmColorPickerImgGray').css({
      'height':'8px',
      'width':'192px',
      'border':0,
      'cursor':'crosshair',
      'background-image':"url('" + $o.imageFolder1 + "graybar.jpg')"
    });
    
    $('#cogmColorPickerInput').css({
      'border':'solid 1px gray',
      'font-size':'10pt',
      'margin':'3px',
      'width':'80px'
    });
    
    $('#cogmColorPickerImgGrid').css({
      'border':0,
      'height':'20px',
      'width':'20px',
      'vertical-align':'text-bottom'
    });
    
    $('#cogmColorPickerSwatches').css({
      'border-right':'1px solid #000'
    });
    
    $('#cogmColorPickerFooter').css({
      'background-image':"url('" + $o.imageFolder1 + "grid.gif')",
      'position': 'relative',
      'height':'26px'
    });

    if ($.fn.cogmColorPicker.init.allowTransparency) $('#cogmColorPickerFooter').prepend('<span id="cogmColorPickerTransparent" class="mColor" style="font-size:16px;color:#000;padding-right:30px;padding-top:3px;cursor:pointer;overflow:hidden;float:right;">transparent</span>');
    if ($.fn.cogmColorPicker.init.showLogo) $('#cogmColorPickerFooter').prepend('<a href="http://meta100.com/" title="Meta100 - Designing Fun" alt="Meta100 - Designing Fun" style="float:right;" target="_blank"><img src="' +  $o.imageFolder1 + 'meta100.png" title="Meta100 - Designing Fun" alt="Meta100 - Designing Fun" style="border:0;border-left:1px solid #aaa;right:0;position:absolute;"/></a>');

    $("#cogmColorPickerBg").click($.fn.cogmColorPicker.closePicker);
  
    var swatch = $.fn.cogmColorPicker.getCookie('swatches'),
        i = 0;

    if (typeof swatch == 'string') swatch = swatch.split('||');
    if (swatch == null || $.fn.cogmColorPicker.init.enhancedSwatches || swatch.length < 10) swatch = $o.swatches;

    $(".mPastColor").each(function() {
		if (swatch[i])
		{
	      $(this).css('background-color', swatch[i++].toLowerCase());
		}

    });
  };

  $.fn.cogmColorPicker.closePicker = function () {

    $(".mColor, .mPastColor, #cogmColorPickerInput, #cogmColorPickerWrapper").unbind();
    $("#cogmColorPickerBg").hide();
    $("#cogmColorPicker").fadeOut()
  };

  $.fn.cogmColorPicker.colorShow = function (id) {

    var $e = $("#icp_" + id);
        pos = $e.offset(),
        $i = $("#" + id);
        hex = $i.attr('data-hex') || $i.attr('hex'),
        pickerTop = pos.top + $e.outerHeight(),
        pickerLeft = pos.left,
        $d = $(document),
        $m = $("#cogmColorPicker");

    if ($i.attr('disabled')) return false;

                // KEEP COLOR PICKER IN VIEWPORT
                if (pickerTop + $m.height() > $d.height()) pickerTop = pos.top - $m.height();
                if (pickerLeft + $m.width() > $d.width()) pickerLeft = pos.left - $m.width() + $e.outerWidth();
  
    $m.css({
      'top':(pickerTop) + "px",
      'left':(pickerLeft) + "px",
      'position':'absolute'
    }).fadeIn("fast");
  
    $("#cogmColorPickerBg").css({
      'z-index':999990,
      'background':'black',
      'opacity': .01,
      'position':'absolute',
      'top':0,
      'left':0,
      'width': parseInt($d.width(), 10) + 'px',
      'height': parseInt($d.height(), 10) + 'px'
    }).show();
  
    var def = $i.val();
  
    $('#colorPreview span').text(def);
    $('#colorPreview').css('background', def);
    $('#color').val(def);
  
    if ($('#' + id).attr('data-text')) $.fn.cogmColorPicker.currentColor = $e.css('background-color');
    else $.fn.cogmColorPicker.currentColor = $i.css('background-color');

    if (hex == 'true') $.fn.cogmColorPicker.currentColor = $.fn.cogmColorPicker.RGBtoHex($.fn.cogmColorPicker.currentColor);

    $("#cogmColorPickerInput").val($.fn.cogmColorPicker.currentColor);
  
    $('.mColor, .mPastColor').bind('mousemove', function(e) {
  
      var offset = $(this).offset();

      $.fn.cogmColorPicker.color = $(this).css("background-color");

      if ($(this).hasClass('mPastColor') && hex == 'true') $.fn.cogmColorPicker.color = $.fn.cogmColorPicker.RGBtoHex($.fn.cogmColorPicker.color);
      else if ($(this).hasClass('mPastColor') && hex != 'true') $.fn.cogmColorPicker.color = $.fn.cogmColorPicker.hexToRGB($.fn.cogmColorPicker.color);
      else if ($(this).attr('id') == 'cogmColorPickerTransparent') $.fn.cogmColorPicker.color = 'transparent';
      else if (!$(this).hasClass('mPastColor')) $.fn.cogmColorPicker.color = $.fn.cogmColorPicker.whichColor(e.pageX - offset.left, e.pageY - offset.top + (($(this).attr('id') == 'cogmColorPickerImgGray')? 128: 0), hex);

      $.fn.cogmColorPicker.setInputColor(id, $.fn.cogmColorPicker.color);
    }).click(function() {
  
      $.fn.cogmColorPicker.colorPicked(id);
    });
  
    $('#cogmColorPickerInput').bind('keyup', function (e) {
  
      try {
  
        $.fn.cogmColorPicker.color = $('#cogmColorPickerInput').val();
        $.fn.cogmColorPicker.setInputColor(id, $.fn.cogmColorPicker.color);
    
        if (e.which == 13) $.fn.cogmColorPicker.colorPicked(id);
      } catch (r) {}

    }).bind('blur', function () {
  
      $.fn.cogmColorPicker.setInputColor(id, $.fn.cogmColorPicker.currentColor);
    });
  
    $('#cogmColorPickerWrapper').bind('mouseleave', function () {
  
      $.fn.cogmColorPicker.setInputColor(id, $.fn.cogmColorPicker.currentColor);
    });
  };

  $.fn.cogmColorPicker.setInputColor = function (id, color) {
  
    var image = (color == 'transparent')? "url('" + $o.imageFolder11 + "grid.gif')": '',
        textColor = $.fn.cogmColorPicker.textColor(color);
  
    if ($('#' + id).attr('data-text') || $('#' + id).attr('text')) $("#icp_" + id).css({'background-color': color, 'background-image': image});
    $("#" + id).val(color).css({'background-color': color, 'background-image': image, 'color' : textColor}).trigger('change');
    $("#cogmColorPickerInput").val(color);
  };

  $.fn.cogmColorPicker.textColor = function (val) {
  
    if (typeof val == 'undefined' || val == 'transparent') return "black";
    val = $.fn.cogmColorPicker.RGBtoHex(val);
    return (parseInt(val.substr(1, 2), 16) + parseInt(val.substr(3, 2), 16) + parseInt(val.substr(5, 2), 16) < 400)? 'white': 'black';
  };

  $.fn.cogmColorPicker.setCookie = function (name, value, days) {
  
    var cookie_string = name + "=" + escape(value),
      expires = new Date();
      expires.setDate(expires.getDate() + days);
    cookie_string += "; expires=" + expires.toGMTString();
   
    document.cookie = cookie_string;
  };

  $.fn.cogmColorPicker.getCookie = function (name) {
  
    var results = document.cookie.match ( '(^|;) ?' + name + '=([^;]*)(;|$)' );
  
    if (results) return (unescape(results[2]));
    else return null;
  };

  $.fn.cogmColorPicker.colorPicked = function (id) {
  
    $.fn.cogmColorPicker.closePicker();
  
    if ($.fn.cogmColorPicker.init.enhancedSwatches) $.fn.cogmColorPicker.addToSwatch();
  
    $("#" + id).trigger('colorpicked');
  };

  $.fn.cogmColorPicker.addToSwatch = function (color) {
  
    var swatch = []
        i = 0;
 
    if (typeof color == 'string') $.fn.cogmColorPicker.color = color.toLowerCase();
  
    $.fn.cogmColorPicker.currentValue = $.fn.cogmColorPicker.currentColor = $.fn.cogmColorPicker.color;
  
    if ($.fn.cogmColorPicker.color != 'transparent') swatch[0] = $.fn.cogmColorPicker.color.toLowerCase();
  
    $('.mPastColor').each(function() {
  
      $.fn.cogmColorPicker.color = $(this).css('background-color').toLowerCase();

      if ($.fn.cogmColorPicker.color != swatch[0] && $.fn.cogmColorPicker.RGBtoHex($.fn.cogmColorPicker.color) != swatch[0] && $.fn.cogmColorPicker.hexToRGB($.fn.cogmColorPicker.color) != swatch[0] && swatch.length < 10) swatch[swatch.length] = $.fn.cogmColorPicker.color;
	  if (swatch[i+1] || typeof swatch[i+1] != 'undefined')
	  {
		  $(this).css('background-color', swatch[i++])
	  }	
      
    });

    if ($.fn.cogmColorPicker.init.enhancedSwatches) $.fn.cogmColorPicker.setCookie('swatches', swatch.join('||'), 365);
  };

  $.fn.cogmColorPicker.whichColor = function (x, y, hex) {
  
    var colorR = colorG = colorB = 255;
    
    if (x < 32) {
  
      colorG = x * 8;
      colorB = 0;
    } else if (x < 64) {
  
      colorR = 256 - (x - 32 ) * 8;
      colorB = 0;
    } else if (x < 96) {
  
      colorR = 0;
      colorB = (x - 64) * 8;
    } else if (x < 128) {
  
      colorR = 0;
      colorG = 256 - (x - 96) * 8;
    } else if (x < 160) {
  
      colorR = (x - 128) * 8;
      colorG = 0;
    } else {
  
      colorG = 0;
      colorB = 256 - (x - 160) * 8;
    }
  
    if (y < 64) {
  
      colorR += (256 - colorR) * (64 - y) / 64;
      colorG += (256 - colorG) * (64 - y) / 64;
      colorB += (256 - colorB) * (64 - y) / 64;
    } else if (y <= 128) {
  
      colorR -= colorR * (y - 64) / 64;
      colorG -= colorG * (y - 64) / 64;
      colorB -= colorB * (y - 64) / 64;
    } else if (y > 128) {
  
      colorR = colorG = colorB = 256 - ( x / 192 * 256 );
    }

    colorR = Math.round(Math.min(colorR, 255));
    colorG = Math.round(Math.min(colorG, 255));
    colorB = Math.round(Math.min(colorB, 255));

    if (hex == 'true') {

      colorR = colorR.toString(16);
      colorG = colorG.toString(16);
      colorB = colorB.toString(16);
      
      if (colorR.length < 2) colorR = 0 + colorR;
      if (colorG.length < 2) colorG = 0 + colorG;
      if (colorB.length < 2) colorB = 0 + colorB;

      return "#" + colorR + colorG + colorB;
    }
    
    return "rgb(" + colorR + ', ' + colorG + ', ' + colorB + ')';
  };

  $.fn.cogmColorPicker.RGBtoHex = function (color) {

    color = color.toLowerCase();

    if (typeof color == 'undefined') return '';
    if (color.indexOf('#') > -1 && color.length > 6) return color;
    if (color.indexOf('rgb') < 0) return color;

    if (color.indexOf('#') > -1) {

      return '#' + color.substr(1, 1) + color.substr(1, 1) + color.substr(2, 1) + color.substr(2, 1) + color.substr(3, 1) + color.substr(3, 1);
    }

    var hexArray = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"],
        decToHex = "#",
        code1 = 0;
  
    color = color.replace(/[^0-9,]/g, '').split(",");

    for (var n = 0; n < color.length; n++) {

      code1 = Math.floor(color[n] / 16);
      decToHex += hexArray[code1] + hexArray[color[n] - code1 * 16];
    }
  
    return decToHex;
  };

  $.fn.cogmColorPicker.hexToRGB = function (color) {

    color = color.toLowerCase();
  
    if (typeof color == 'undefined') return '';
    if (color.indexOf('rgb') > -1) return color;
    if (color.indexOf('#') < 0) return color;

    var c = color.replace('#', '');

    if (c.length < 6) c = c.substr(0, 1) + c.substr(0, 1) + c.substr(1, 1) + c.substr(1, 1) + c.substr(2, 1) + c.substr(2, 1);

    return 'rgb(' + parseInt(c.substr(0, 2), 16) + ', ' + parseInt(c.substr(2, 2), 16) + ', ' + parseInt(c.substr(4, 2), 16) + ')';
  };

  $(document).ready(function () {

    if ($.fn.cogmColorPicker.init.replace) {

      $('input[data-cogmColorPicker!="true"]').filter(function() {
    
        return ($.fn.cogmColorPicker.init.replace == '[type=color]')? this.getAttribute("type") == 'color': $(this).is($.fn.cogmColorPicker.init.replace);
      }).cogmColorPicker();

      $.fn.cogmColorPicker.liveEvents();
    }
  });
}
})(jQuery);
