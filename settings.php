<div id="wrapper">
	<div class="wrap ar_wrap">
		<div id='icon-options-general' class='icon32'><br />
		</div>
		<h2><?php _e('Page Expiration Robot PRO', "per"); ?></h2>
	</div>
	<div id="poststuff">
		<div class="postbox">
			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="hndle"><?php _e('White Listed IP Addresses', "per"); ?></h3>
			<div class="inside">
			<form id="whitelist" class="ip_form" name="whitelist" method="post">
			*<?php _e('Your current IP Address');?>: <?php echo $_SERVER['REMOTE_ADDR'];?><br/>White list IP: <input type="text" name="ip" class="list_input">&nbsp;<input type="submit" value="White List" class="button-primary"><input type="hidden" name="list" value="white"><img src="<?php echo $this->PluginURL;?>/images/loading.gif" class="loading">
			</form>
			<div class="listed_ips">
			<?php
			$listedips = unserialize(get_option("per_white_list"));
			if (is_array($listedips))
			{
				foreach($listedips as $ip)
				{
					$sip = str_replace(".","",$ip);
					echo "<a href='#' class='listed_ip' id='white".$sip."'>".$ip."</a>";
				}
			}
			?>
			</div>
			</div>
		</div>
		<!-- <div class="postbox">
			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="hndle"><?php _e('Black Listed IP Addresses', "per"); ?></h3>
			<div class="inside">
			<form id="blacklist" class="ip_form" name="black_list" method="post">
			Black list IP: <input type="text" name="ip" class="list_input">&nbsp;<input type="submit" value="Black List" class="button-primary"><input type="hidden" name="list" value="black"><img src="<?php echo $pluginpath;?>images/loading.gif" class="loading">
			</form>
			<div class="listed_ips">
			<?php
			$listedips = unserialize(get_option("per_black_list"));
			if (is_array($listedips))
			{
				foreach($listedips as $ip)
				{
					$sip = str_replace(".","",$ip);
					echo "<a href='#' class='listed_ip' id='black".$sip."'>".$ip."</a>";
				}
			}
			?>
			</div>
			</div>
		</div> -->
	</div>
</div>
<script language="javascript">
	jQuery('.ip_form').submit(function() { // catch the form's submit event
		jQuery(this).find(".loading").show();
		var action = jQuery(this).attr('action');
		if (!action || action == "")
		{
			action = ajaxurl+"?action=per_edit_ip";
		}
		jQuery.ajax({ // create an AJAX call...
			data: jQuery(this).serialize(), // get the form data
			type: jQuery(this).attr('method'), // GET or POST
			url: action, // the file to call
			success: function(response) { // on success..
				var ip = response.substring(0,response.indexOf('~'));
				var list = response.substring(response.indexOf('~')+1);
				var form = jQuery("#"+list+"list");
				jQuery(form).find(".loading").hide();
				if (ip != "0")
				{
					var sip = ip.replace(/\./g,"");
					jQuery(form).next(".listed_ips").append('<a href="#" class="listed_ip" id="'+list+sip+'" >');
					jQuery(form).find('.list_input').val('');
					var latestIp= jQuery(form).next(".listed_ips").find("a:last");
					jQuery(latestIp).html(ip);
					jQuery(latestIp).click(delact);
					jQuery(latestIp).css("class","listed_ip");
				}
			}
		});
		return false; // cancel original event to prevent form submitting
	});
	var delact = function(){
		var ip = jQuery(this).text();
		var sip = ip.replace(/\./g,"");
		var list = jQuery(this).attr('id');
		var listtype = list.replace(sip,"");
		jQuery("#"+listtype+"list").find(".loading").show();
		action = ajaxurl+"?action=per_edit_ip";
		jQuery.ajax({ // create an AJAX call...
			data: {'ip':ip,'act':'del','list':list}, // get the form data
			type: 'POST', // GET or POST
			url: action, // the file to call
			success: function(response) { // on success..
				jQuery("#"+response).remove();
				var listtype = response.replace(sip,"");
				jQuery("#"+listtype+"list").find(".loading").hide();
			}
		});
		return false;
	};
	jQuery('.listed_ip').click(delact);
</script>