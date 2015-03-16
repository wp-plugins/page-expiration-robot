<?php 

/*
$url_path = PageExpirationRobot::$PluginURL;
$url_path .= '/copier/'.basename($src);
$dest = $url_path;
//file_put_contents($dest, file_get_contents($src));

$source = $src;
$destination = dirname(__FILE__).'/copier/'.basename($src);

$data = wp_remote_get($source);

$handle = fopen($destination, "w") or die("cant");
fwrite($handle, $data['body']);
fclose($handle);*/

if(isset($_POST['update']))
{
    $a_name = $_POST['update'];
    unlink($this->UploadAddonPath."/".$a_name.'.zip');  // Delete it from Upload Folder as Temporary Cache
    $this->rrmdir($this->PluginDir."/".$this->AddOnFolder."/".$a_name);
    $src = wp_remote_get('http://pageexpirationrobot.com/v2/addon_secured.php?addon='.$a_name);
    $src = $src['body'];
    //$src = "http://pageexpirationrobot.com/v2/wp-content/premium-addons/Actions-Reach4321/action_reach.zip"; //http://pageexpirationrobot.com/v2/wp-content/premium-addons/Actions-Reach4321/action_reach.zip
$url_path = PageExpirationRobot::$PluginURL;
$url_path .= '/addons/'.basename($src);
$dest = $url_path;
//file_put_contents($dest, file_get_contents($src));

$source = $src;
$destination = dirname(__FILE__).'/addons/'.basename($src);

$data = wp_remote_get($source);

$handle = fopen($destination, "w") or die("cant");
fwrite($handle, $data['body']);
fclose($handle);

copy($this->PluginDir."/".$this->AddOnFolder."/".$a_name.'.zip',$this->UploadAddonPath."/".$a_name.'.zip');
$this->unzip($a_name.'.zip', $this->PluginDir."/".$this->AddOnFolder);

    @unlink($this->PluginDir."/".$this->AddOnFolder."/".$a_name.'.zip');
	
    echo "Updated";



}



if (!is_array($this->InstalledAddOns))

	$this->InstalledAddOns = array();

if (isset($_GET['act']))

{

	$addon = $_GET['addon'];

	switch ($_GET['act'])

	{

		case "dac":

			if (isset($this->InstalledAddOns[$addon]))

			{

				$this->InstalledAddOns[$addon]['act'] = 0;

				$this->set_default_values($addon);

			}

			break;

		case "ac":

			if (isset($this->InstalledAddOns[$addon]))

			{

				$this->InstalledAddOns[$addon]['act'] = 1;

			}

			break;

		case "uins":

			if (isset($this->InstalledAddOns[$addon]))

			{

				$this->InstalledAddOns[$addon]['install'] = 0;

				$this->set_default_values($addon);

				/*delete add on folder */

				$this->rrmdir($this->PluginDir."/".$this->AddOnFolder."/".$addon);
				@unlink($this->UploadAddonPath."/".$addon.'.zip');
                $for_expirer[$addon] = 1;
			}

			break;

		case "ins":

			if (isset($_FILES['addon']))

			{

				$tmp_name = $_FILES["addon"]["tmp_name"];

				$name = $_FILES["addon"]["name"];

				move_uploaded_file($tmp_name, $this->PluginDir."/".$this->AddOnFolder."/".$name);
                copy($this->PluginDir."/".$this->AddOnFolder."/".$name,$this->UploadAddonPath."/".$name);
               
				$this->unzip($name, $this->PluginDir."/".$this->AddOnFolder);

				@unlink($this->PluginDir."/".$this->AddOnFolder."/".$name);

				//echo $addon;
				include($this->PluginDir."/".$this->AddOnFolder."/".$addon."/".$addon.".php");

				$AddOnName = ucwords(str_replace("_", " ",$addon));

				$AddOnName = str_replace(" ", "",$AddOnName);

				$AddOnName = "PER".$AddOnName;

				if(class_exists($AddOnName))

				{

					echo "valid";

					$this->InstalledAddOns[$addon]['install'] = 1;

					$this->InstalledAddOns[$addon]['act'] = 1;

				}

				else

				{

					$this->deleteDir($this->PluginDir."/".$this->AddOnFolder."/".$addon);

					echo "invalid";

				}

			}

		

			break;

            case "topins":

			if (isset($_FILES['addon']))

			{

				$tmp_name = $_FILES["addon"]["tmp_name"];

				$name = $_FILES["addon"]["name"];

				move_uploaded_file($tmp_name, $this->PluginDir."/".$this->AddOnFolder."/".$name);
                copy($this->PluginDir."/".$this->AddOnFolder."/".$name,$this->UploadAddonPath."/".$name);

				$this->unzip($name, $this->PluginDir."/".$this->AddOnFolder);

				@unlink($this->PluginDir."/".$this->AddOnFolder."/".$name);

				//echo $addon;
                $nme_splitter = explode('\\',$addon);
                //print_r($nme_splitter);
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
					    $agent = $_SERVER['HTTP_USER_AGENT'];
					}
			    if (strlen(strstr($agent, 'Firefox')) > 0) {
				    $browser = 'firefox';
				    $addon = $nme_splitter[0];
				}		
                else{
                $addon = $nme_splitter[4]; }
                
				include($this->PluginDir."/".$this->AddOnFolder."/".$addon."/".$addon.".php");
                                
				$AddOnName = ucwords(str_replace("_", " ",$addon));

				$AddOnName = str_replace(" ", "",$AddOnName);

				$AddOnName = "PER".$AddOnName;

				if(class_exists($AddOnName))

				{

					echo "valid";

					$this->InstalledAddOns[$addon]['install'] = 1;

					$this->InstalledAddOns[$addon]['act'] = 1;

				}

				else

				{

					$this->deleteDir($this->PluginDir."/".$this->AddOnFolder."/".$addon);

					echo "invalid";

				}

			}

		

			break;
  



	}

	update_option($this->MetaPrefix."_addons", serialize($this->InstalledAddOns));

}

?>
<?php

/**

 * Addons Page

 */

 /*Get Add-ons list from Remote Server */

//$raw_addons = wp_remote_get( 'http://183.177.126.17/john_wp/add_on_xml.xml' );



//if ( ! is_wp_error( $raw_addons ) ) 
{



	//$raw_addons= wp_remote_retrieve_body( $raw_addons );

	//$dom = new DOMDocument();

	//libxml_use_internal_errors(true);

	//$dom->loadXML( $raw_addons);

	//$xpath  = new DOMXPath( $dom );

	//$addOns = $dom->getElementsByTagName("addon");

	//$json = file_get_contents('http://pageexpirationrobot.com/v2/latest_addons.php',true);
        $raw_addons = wp_remote_get( 'http://pageexpirationrobot.com/v2/latest_addons.php' );
       
        
        $chr = $raw_addons['body'];
        $obj = json_decode($chr);

        $addOns = $obj;
?>

<div class="per-wrapper wrap per_addon_manager per_addon_manager_addons_wrap pageExp">
<div class="header">
  <div class="logo"><img src="<?php echo $this->PluginURL;?>/images/PER_logo.png"></div>
  <h2>Addons</h2> <span id="resolver"></span>
  <div class="addonsAdd">
  	<p>Purchased on add-on? Install it in .zip format</p>


  	<form method='post' id="mass_uploader" enctype="multipart/form-data" action="admin.php?page=<?php echo $this->Namespace;?>_addons&act=topins&addon=">
        
    <span class="uploadBnt">
    	Upload File
    	 <input type="file" name="addon" style="width:88px;" class="fileOpt" >
   
    </span>
     <span id="file_holder" style="margin-right:5px;">No file chosen</span>  
    <input name="" type="submit" value="Install Now" class="submitBnt" style="float:right" />
  </div>
</form>
</div>

<style>
div.updated
{
display:none !important;
}
</style>

<script>
jQuery(document).ready(function($){
$('.fileOpt').on("change", function(){ 
//alert($(this).val());
var filename=$(this).val();
var file_arr = filename.split('.');
var extension = file_arr[1];
var code= file_arr[0];
console.log(code);
		if(extension!='zip')
		{
			alert('please select Zip file');
		}else
		{
			var action_obj = "admin.php?page=<?php echo $this->Namespace;?>_addons&act=topins&addon="+code;
			$('#mass_uploader').attr('action',action_obj);
			$('#file_holder').text(filename);
		}
 });
});
</script>
  
<ul class="wp-list-table widefat fixed posts items" >
	<script>
							function myFunction(frst,scnd){
							//alert(document.getElementById("file").value);
							    document.getElementById(frst).value = document.getElementById(scnd).value
							    console.log('frst:'+frst+'scnd:'+scnd);
							}
						</script>
<?php

	/* Loop Through Each Addon from addon list Xml*/
    $c = 0;
    
    $vers_data = wp_remote_get( 'http://pageexpirationrobot.com/v2/addons_version.php' );
    $bbdy = $vers_data['body'];
    
    $vers_obj = json_decode($bbdy);
    //print_r($vers_obj);
    ?>
    <script type="text/javascript">
     function submm(id)
     {
     	jQuery('#'+id).submit();
     }
    </script>
    <?php
        $available = 0;
	foreach ($addOns as $addOn)

	{
        $bdy = file_get_contents($this->PluginDir."/".$this->AddOnFolder."/".$addOn->code."/readme.txt");
		$expired = 0;
		//print_r($addOn); die();
		$addonm_code = $addOn->code;
        
	    preg_match("/Version:(.*)/",$bdy, $converted);
	    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        if($vers_obj->$addonm_code != '')
        {
		        if($vers_obj->$addonm_code != $converted)
		        {
		        	$expired = 1;
		        }
        }
        $c++;
		foreach ($addOn->childNodes as $childnode)

		{

			 if ($childnode->nodeType == 1)

				 $AddOnData[$childnode->nodeName]=$childnode->nodeValue;

		}

	   

?>
<li class="post-2080 project type-project status-publish hentry first 
<?php if($this->getAddOnProperty($addOn->code,'act')==1)
        {
        	if($expired == 1)
        	{
        		if($for_expirer[$addOn->code] != 1)
        		{
        		echo "expired";
        		
                        $available++;
                }        
        	}
        	else
        	{
        		echo "active";
        	}
        }

?>" id="post-2080">
	<h2>
      <span class="cost"></span>
      <a href="<?php echo $addOn->buylink;?>"><?php echo $addOn->title;?></a><?php if($this->getAddOnProperty($addOn->code,'act')==1)
        {if($vers_obj->$addonm_code != '')
        {if($expired == 1)
        	{ if($for_expirer[$addOn->code] != 1){?><span class="updd" style="float:right"><a class="width:100px; height:30px; line-height:30px; text-align:center; text-transform:uppercase; font-size:14px; color:#fff; border-radius:3px; background:#1d96dc; position:absolute; left:50%; top:50%;" href="javascript:void(0)" onclick="submm('upd_<?php echo $addOn->code; ?>')">Update</a></span><?php } }}} ?>
    </h2>
    <a class="item" href="<?php echo $addOn->buylink;?>">
        <span class="action ">
        	<img src="<?php echo $addOn->image_url;?>" class="attachment-product-image wp-post-image iconImg" alt="<?php echo $addOn->title;?>" title="" />
        </span>
    </a>
    <form id="upd_<?php echo $addOn->code; ?>" action="" method="post">
     <input type="hidden" name="update" value="<?php echo $addOn->code; ?>" />
    </form>
    <p><?php echo $addOn->desc;;?></p>
  
  <table style="width:100%;" id="per_addon_link">
    <?php

				$avail=true;

				$dep="";

				foreach ($addOn->childNodes as $dependencies)

				{

					if($dependencies->nodeName=='dependencies'){

					$dep.= "<div style='margin:6px;' id='per_addon_link'>To work this add-on. Please install below listed add-on and activate it, if not installed</div><tr><td style='padding: 1px 9px;'><span style='font-weight: 400;color: black;'>Add-on Name</span></td><td style='padding: 1px 9px;'><span style='font-weight: 400;color: black;'>Status</span></td></tr>";

						foreach($dependencies->childNodes as $subNodes)

						 {

							if($subNodes->nodeName=='addondep'){

								$dep.= "<tr>";

								foreach($subNodes->childNodes as $addon_subNodes){

									//$dependencies[$subNodesw->nodeName]=$subNodesw->nodeValue;

									$dep.= '<td style="padding: 1px 9px;">';

									if($addon_subNodes->nodeName=="codedep"){

										if($this->getAddOnProperty($addon_subNodes->nodeValue,'act')==1) {

											$dep.= "<a>Activated</a>";

										}

										else{

											$dep.= "<a>Not Activated</a>";

											$avail=false;

										}

									}

									else

									$dep.= $addon_subNodes->nodeValue;	

									$dep.= '</td>';



								}

								$dep.= "</tr>";

							}

						 }

					}

				} 

	 ?>
    <tr>
      <?php
            
			if($avail==true){
				if ($this->getAddOnProperty( $addOn->code,'install')==0)
			      {

				
            ?>
              <form method='post' id='frmer_<?php echo $c; ?>' enctype="multipart/form-data" action="admin.php?page=<?php echo $this->Namespace;?>_addons&act=ins&addon=<?php echo $addOn->code;?>">
              	
              	<tr>
              		<td colspan="2"><input type="text" id="text<?php echo $c; ?>" style="width: 98%; border: none; box-shadow: none; background: none;" readonly></td>
              	</tr>
              	<tr id="install_form_<?php echo $addOn->code;?>" style='display:none;'>
              		
              		<td>
              			
              			<span class='fl_span'>Choose File
              				<input class='fl_uploader' type="file" name="addon" style="width:88px;" onChange="myFunction('text<?php echo $c; ?>','file_<?php echo $c; ?>')" id="file_<?php echo $c; ?>">
                 		</span>

              		</td>
              		<td><a href="#" onclick="jQuery('#install_form_<?php echo $addOn->code;?>').hide('slow');jQuery('#mainerr_<?php echo $addOn->code;?>').css('display','none');jQuery('#install_<?php echo $addOn->code;?>').show('slow','linear');return false;" class="cancel_button">Cancel</a></td></tr>
            <?php
			
				echo '<td style="text-align:left;width:82px;"><a href="'.$addOn->buylink.'" target="_new" class="buynow_button">Buy Now  '.$AddOnData['price'].'</a></td>';

			?>
      <td style="text-align:right;"><div id='mainerr_<?php echo $addOn->code;?>' style="display:none;">
          
            
             <a href="#" onclick="jQuery('#frmer_<?php echo $c; ?>').submit();return false;" class="install_button">Install</a>
            
            <br>
            
          </form>
        </div>
        <div  id="install_<?php echo $addOn->code;?>"><a href="#" onclick="jQuery('#install_form_<?php echo $addOn->code;?>').show('slow','linear');jQuery('#mainerr_<?php echo $addOn->code;?>').show('slow','linear');jQuery('#install_<?php echo $addOn->code;?>').hide('slow');return false;" class="install_button">Install</a></div></td>
      <?php

			}

			else

			{

			?>
      <td><a href='admin.php?page=<?php echo $this->Namespace."_addons&act=".($this->getAddOnProperty($addOn->code,'act')==1?'dac':'ac')."&addon=".$addOn->code;?>' title="Click to <?php echo $this->getAddOnProperty($addOn->code,'act')==1?'Deactivate':'Activate'?>" class="<?php echo $this->getAddOnProperty($addOn->code,'act')==1?'deactivate_button':'activate_button'?>" alt="Click to <?php echo $this->getAddOnProperty($addOn->code,'act')==1?'Deactivate':'Activate'?>"><?php echo $this->getAddOnProperty($addOn->code,'act')==1?'Deactivate':'Activate'?></a></td>
      <td style="text-align:right;"><a href="admin.php?page=<?php echo $this->Namespace."_addons&act=uins&addon=".$addOn->code;?>" title="Click to Uninstall" alt="Click to Uninstall" class="uninstall_button">UnInstall</a></td>
      <?php

			}

			}

			else{

				echo $dep;

			}

			?>
    </tr>
  </table>
  
</li>
<?php

	}
	//print_r($version_arr);

}

echo "</ul>";

/* << Acivate Default Counter */
if($available > 0)
{
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#resolver').html('<span id="spannern" class="numbtn"><?php echo $available; ?></span> Addon Updates Available');
})
</script>
<?php
}
$addon=$AddOnData['default'];

update_option($this->MetaPrefix."_default_counter",$addon);

if ($this->getAddOnProperty($addon,'install')==0)

{

	//echo "<script>alert('Please Install default COunter');</script>";

}

else{

if ($this->InstalledAddOns[$addon]['act']==0)

	{

		wp_redirect("admin.php?page=".$this->Namespace."_addons&act=ac&addon=".$addon."");

	}

}

?>