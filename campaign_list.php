<?php if(isset($_POST['postdel'])){
	echo $_POST['postdel'];
	exit;
} ?>


<?php
if(isset($_GET['c_id']) )
{
	
	 $meta_key_values = get_post_meta( $_GET['c_id']); 

	 //print_r($meta_key_values); die();
	 $campaign = array(
			  'post_title'    => wp_strip_all_tags( get_the_title($_GET['c_id']).' copy' ),
			  'post_content'  => "",
			  'post_status'   => 'publish',
			  'post_author'   => get_current_user_id(),
			  'post_type'	  => $this->CampaignPostType	
			);
	 $campaign_id = wp_insert_post( $campaign );

	 foreach ( $meta_key_values as $key=>$value)
	 {
	 	if(is_array($value) && count($value)>0)
	 	{
	 		add_post_meta( $campaign_id, $key, $value[0] );
	 	}else
	 	{
	 		add_post_meta( $campaign_id, $key, $value );
	 	}
	 	
	 }

	 wp_redirect( "?page=page_expiration_robot", 301 );
}

?>
<div class="per-wrapper pageExp">
<div class="header">
		<div class="logo"><img src="<?php echo $this->PluginURL;?>/images/PER_logo.png" ></div><h2>All Campaigns </h2> <a href="?page=page_expiration_robot_new" class="add-new-h2">Add New</a>
	</div>
<table class="wp-list-table widefat fixed posts" cellspacing="0" style="margin-top: 40px;">
<tr style="border-bo">
		<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
			<label class="screen-reader-text" for="cb-select-all-1"></label>
		</th>
		<th scope="col" id="title" class="manage-column column-title sortable desc" style="width: 30%;">
			<a><span>Title</span></a>
		</th>
		<th style="width: 30%;">Short Code</th>
		<th style="width: 30%;">Actions</th>
		<th></th>
	</tr>
<tr>

<?php
$args = array(
			  'post_type'	  => $this->CampaignPostType	
			);
// The Query
query_posts( $args );
// The Loop
while ( have_posts() ) : the_post();
$id=get_the_ID();
?>
<form method="post" action="">
<tr id="post-289" class="post-289 type-per_campaign status-publish hentry alternate iedit author-self" valign="top">
				<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-289"></label>
				
				<input type="hidden"  name="postdel" value="<?php the_ID();?>">
							</th>
						<td class="post-title page-title column-title"><strong><a class="row-title" ><?php the_title();?></a></strong>
<!-- <div class="row-actions"><span class="edit"><a href="<?php echo $_SERVER['REQUEST_URI'];?>&pid=<?php the_ID();?>" title="Edit this item">Edit</a> |<a href="<?php echo get_delete_post_link( $id ); ?>"  title="Trash this item">Trash</a> </span></div> -->
</td> 			
<td class="post-title page-title column-title"><strong><a class="row-title" style="color:#555555;" ><?php echo '[COUNTDOWN id="'.get_the_ID().'"]' ?></a></strong>
<td class="post-title page-title column-title"><strong><a class="row-title" title="Edit Settings" href="<?php echo $_SERVER['REQUEST_URI'];?>&pid=<?php the_ID();?>" style="margin-right: 10px;" ><img src="<?php echo $this->PluginURL;?>/images/settings.png" ></a> <a class="row-title" title="Create Duplicate" href="<?php echo $_SERVER['REQUEST_URI'];?>&c_id=<?php the_ID();?>" style="margin-right: 10px;" ><img src="<?php echo $this->PluginURL;?>/images/copy.png" ></a> <a href="<?php echo get_delete_post_link( $id ); ?>" onclick="if(!(confirm('Are you sure ?')))return false;" title="Trash this item"><img src="<?php echo $this->PluginURL;?>/images/delete.png" ></a> </strong>

</td> 			
<td class="author column-author"></td><td class="author column-author"></td><td class="author column-author"></td>
</tr>



</form><?php
endwhile;
echo "</table></div>";


