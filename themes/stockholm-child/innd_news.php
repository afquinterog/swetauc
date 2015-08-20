<?php 
/*
Template Name: Innd News
*/ 
?>
<?php 

global $wp_query;

$id = $wp_query->get_queried_object_id();
$sidebar = get_post_meta($id, "qode_show-sidebar", true);  

$enable_page_comments = false;
if(get_post_meta($id, "qode_enable-page-comments", true) == 'yes') {
	$enable_page_comments = true;
}

if(get_post_meta($id, "qode_page_background_color", true) != ""){
	$background_color = get_post_meta($id, "qode_page_background_color", true);
}else{
	$background_color = "";
}

$content_style = "";
if(get_post_meta($id, "qode_content-top-padding", true) != ""){
	if(get_post_meta($id, "qode_content-top-padding-mobile", true) == "yes"){
		$content_style = "style='padding-top:".get_post_meta($id, "qode_content-top-padding", true)."px !important'";
	}else{
		$content_style = "style='padding-top:".get_post_meta($id, "qode_content-top-padding", true)."px'";
	}
}

if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }


?>
	<?php get_header(); ?>
		
	<?php get_template_part( 'title' ) ; ?>
		
	<div class="full_width"<?php if($background_color != "") { echo " style='background-color:". $background_color ."'";} ?>>
		<div class="full_width_inner" <?php if($content_style != "") { echo $content_style; } ?>>



			<div class="two_columns_75_25 clearfix grid2">

				<div class="column1">

			<?php
				// Sticky Appearances
				$args = array(
					'post_type' => 'appearances',
					'post__in'  => get_option( 'sticky_posts' )
				 );
				$query = new WP_Query( $args );
				$dataAppearances =  $query->get_posts();
				if(count($dataAppearances) >= 1 ){
					showData("Featured Appearances", $dataAppearances);
				}

				// Sticky Blog
				$args = array(
					'post_type' => 'post',
					'post__in'  => get_option( 'sticky_posts' )
				 );
				$query = new WP_Query( $args );
				$dataBlog =  $query->get_posts();
				if(count($dataBlog) >= 1 ){
					showData("Featured Blog Posts", $dataBlog);
				}

				// Sticky publications
				$args = array(
					'post_type' => 'portfolio_page',
					'post__in'  => get_option( 'sticky_posts' )
				 );
				$query = new WP_Query( $args );
				$dataPublications =  $query->get_posts();
				if(count($dataPublications) >= 1 ){
					showData("Featured Publications", $dataPublications, true);
				}
			?>

				</div>

				<div class="column2"><?php get_sidebar();?></div>
			</div>
		</div>
	</div>	

	<div style="" class="separator  transparent center  "></div>

	<?php get_footer(); ?>

	<?php
	function showData($title, $data, $isLastSection=false){
		?>
		<h2><?php echo $title; ?></h2>
			<div style="height:20px" class="separator  transparent center  "></div>
		<?php
		foreach( $data as $item ) :
			// Get permalink
	    	$permalink = get_permalink( $item->ID );
	   	 	// Get featured image 
			$image = wp_get_attachment_url( get_post_thumbnail_id( $item->ID ) );
			$image = ($image == "") ? "http://placehold.it/300x250" : $image;

			$video   = get_post_meta( $item->ID, '_video', true);
			$videoFrame = getVideoFrame($video);
			
		?>

		<!-- Title -->
		<div style=" text-align:left;" class="vc_row wpb_row section vc_row-fluid">
			<div class=" full_section_inner clearfix">
				<div class="vc_col-sm-4 wpb_column vc_column_container ">
					<div class="wpb_wrapper">
						<div class="wpb_single_image wpb_content_element vc_align_center">
							<div>
								<div class="wpb_wrapper">

									<?php 
										if ($videoFrame != ""){ 
											echo "<div class='fluid-width-video-wrapper' style='min-height:200px!important'>";
											echo $videoFrame;
											echo "</div>";
										} 
										else{
									?> 
										<a target="_self" 
											href="<?php echo $permalink; ?>">
												<div class="vc_single_image-wrapper vc_box_shadow  vc_box_border_grey">
													<img  alt="dia-logo" 
													class="vc_single_image-img attachment-thumbnail" 
													src="<?php echo $image; ?> ">
												</div>
										</a>
									<?php } ?>
									
								</div> 
							</div>
						</div> 
					</div> 
				</div> 				
				<div class="vc_col-sm-8 wpb_column vc_column_container ">
					<div class="wpb_wrapper">
						<div class="wpb_text_column wpb_content_element  vc_custom_1431478643819">
							<div class="wpb_wrapper">
								<h3><a href="<?php echo $permalink; ?>"><?php echo $item->post_title; ?></a></h3>
							</div>
						</div>
					</div>
				</div>
				

				<div class="vc_col-sm-8 wpb_column vc_column_container ">
					<div class="wpb_wrapper">
						<div class="wpb_text_column wpb_content_element ">
							<div class="wpb_wrapper">
								<p><strong>Description</strong></p>
								<p><?php echo $item->post_excerpt; ?></p>
							</div> 
						</div> 
						<div style="" class="separator  transparent center  "></div>
					</div> 
				</div> 
			</div>
		</div>
		<!--End Content -->

		<div style="" class="separator  transparent center  "></div>
		<div style="" class="separator  transparent center  "></div>
		<div style="" class="separator  transparent center  "></div>

		<?php
		endforeach;
		// Reset Post Data
		wp_reset_postdata();
		echo ($isLastSection) ? "" : "<hr/><div style='' class='separator  transparent center'></div>";
	}
		?>