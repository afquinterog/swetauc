<?php

//init variables
$id 				= get_the_ID();
$content = get_post($my_postid);
$content = apply_filters('the_content', get_post_field('post_content', $id));
$content = get_post_field("post_content", $id);
$image   = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
$video   = get_post_meta( $id, '_video', true);
$audio   = get_post_meta( $id, '_audio', true );
$title   = get_the_title( $id );

$date       = get_the_date();
$categories = get_the_terms( $id, "appearance_category" );

$category     = isset($categories[0]->name) ? $categories[0]->name : "";
$categoryLink = "";
if($category != ""){
	$categoryLink = get_term_link( $categories[0]->term_id, "appearance_category"  ) ;
}

$image  = ($image != "") ? "<img class='attachment-blog_image_in_grid wp-post-image' src='$image' />" : "";


$videoFrame = getVideoFrame($video); 
?>

<?php get_header(); ?>




<div class="container">
	<div class="container_inner default_template_holder clearfix" >
		<div class="portfolio_single big-slider">
			<div class="two_columns_98_2 clearfix portfolio_container">
				<div class="blog_holder blog_single">	
					<article class="post post type-post status-publish format-standard has-post-thumbnail hentry " id="post">
					
					<div class="post_content_holder">
						<h2 class="portfolio_single_text_title"><span><?php echo $title; ?></span></h2>
						
						<div class="post_image">
	                        
	                        <?php if($videoFrame != "") : 
	                        		$image = "";
	                        ?>
		                        <div class="fluid-width-video-wrapper" style="padding-top: 50%;">
		                        	<?php echo $videoFrame;  ?>
		                        </div>
		                    <?php endif; ?>

		                    <?php echo $image; ?>

	                    </div>

						<div class="post_text">
							<div class="post_text_inner">
								<div class="post_info">
									<span class="time"><span><?php echo $date; ?></span></span>
									<span class="post_category">
									<span>In</span>
									<span><a rel="category tag" href="<?php echo $categoryLink; ?>"><?php echo $category; ?></a></span>
									</span>
								</div>
							<div class="post_content">
								<?php the_content(); ?>					
								<div class="clear"></div>

								<?php if( isset($audio["url"]) && $audio["url"] != "") : ?>
									<audio class="blog_audio" src="<?php echo $audio["url"]; ?>" controls="controls">
										<?php _e("Your browser don't support audio player","qode"); ?>
									</audio>
								<?php endif; ?>
							</div>
					</div>
				</div>
			</div>
			
		</article>
	</div>
										
  </div>
 </div>
	
</div>
</div>


<?php get_footer(); ?>	