<?php
	
	//Include the shortcodes
	include_once('shortcodes.php');

	// enqueue the child theme stylesheet
	function wp_schools_enqueue_scripts() {
		wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
		wp_enqueue_style( 'childstyle' );
	}
	add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);



	/***
	* Portfolio Custom fields
	*/
	add_action('add_meta_boxes', 'addPortfolioMetabox' );
	add_action('save_post'     , 'savePortfolio' ) ;

	function addPortfolioMetabox(){
		add_meta_box( 'portfolio_metabox', 
	                  __( 'Title','' ), 
	                  'showPortfolioMetaBox'  , 
	                  'portfolio_page', 
	                  'normal', 
	                  'high' );
	}


    /**
	* Display the slideshow Metabox
	*
	* @param object $post the post to display
	*/
	function showPortfolioMetaBox($post){
		// retrieve our custom meta box values
	    $subtitle = get_post_meta( $post->ID, '_subtitle', true );

	    //Display the view 
	    include_once('templates/portfolio_metabox.php');
	} 

	/**
	* Save the portfolio data
	*
	* @param  int  $postId The post to save
	* @return void
	*/
	function savePortfolio( $postId ){
		//verify the post type is for Halloween Products and metadata has been posted
		if ( get_post_type( $postId ) == "portfolio_page" && isset( $_POST['subtitle'] ) ) {

			
			//if autosave skip saving data
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return;

			//check nonce for security
			//check_admin_referer( 'portfolio_meta_save', 'lport' );

			// save the meta box data as post metadata
			update_post_meta( $postId, '_subtitle', sanitize_text_field( $_POST['subtitle'] ) );
		}
	}



	/***
	* Appearence custom post type
	*/
	register_post_type( 'appearances',
		array(
				'labels' => array(
				'name' => __( 'Appearances','qode' ),
				'singular_name' => __( 'Appearances Item','qode' ),
				'add_item' => __('New Appearances Item','qode'),
				'add_new_item' => __('Add New Appearances Item','qode'),
				'edit_item' => __('Edit Appearances Item','qode')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => "appearances"),
			'menu_position' => 4,
			'show_ui' => true,
	        'supports' => array('author', 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'comments')
		)
	);

	/* Create Appearances Categories */

	$labels = array(
		'name' => __( 'Appearances Categories', 'taxonomy general name' ),
		'singular_name' => __( 'Appearances Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Appearances Categories','qode' ),
		'all_items' => __( 'All Appearances Categories','qode' ),
		'parent_item' => __( 'Parent Appearances Category','qode' ),
		'parent_item_colon' => __( 'Parent Appearances Category:','qode' ),
		'edit_item' => __( 'Edit Appearances Category','qode' ), 
		'update_item' => __( 'Update Appearances Category','qode' ),
		'add_new_item' => __( 'Add New Appearances Category','qode' ),
		'new_item_name' => __( 'New Appearances Category Name','qode' ),
		'menu_name' => __( 'Appearances Categories','qode' ),
	);     

	register_taxonomy('appearance_category',array('appearances'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'appearance-category' ),
	  ));


	add_action('add_meta_boxes', 'addAppearanceMetabox' );
	add_action('save_post'     , 'saveAppearance' ) ;

	function addAppearanceMetabox(){
		add_meta_box( 'appearance_metabox', 
	                  __( 'Audio and Video','' ), 
	                  'showAppearanceMetaBox'  , 
	                  'appearances', 
	                  'normal', 
	                  'high' );
	}


	add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );

	function post_edit_form_tag( ) {
	    echo ' enctype="multipart/form-data"';
	}

	/**
	* Display the appearance Metabox
	*
	* @param object $post the post to display
	*/
	function showAppearanceMetaBox($post){
		// retrieve our custom meta box values
	    $audio = get_post_meta( $post->ID, '_audio', true );
	    $video = get_post_meta( $post->ID, '_video', true );

	    //Display the view 
	    include_once('templates/appearance_metabox.php');
	} 


	/**
	* Save the appearance data
	*
	* @param  int  $postId The post to save
	* @return void
	*/
	function saveAppearance( $postId ){
		//verify the post type is for Halloween Products and metadata has been posted
		if ( get_post_type( $postId ) == "appearances"  ) {

			
			//if autosave skip saving data
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return;


			if( current_user_can('edit_page', $postId )) {
				update_post_meta($postId, '_video', sanitize_text_field( $_POST['video'] )); 
				$upload = saveFile("audio");
				if(is_array($upload)){
					add_post_meta($postId, '_audio', $upload);
	            	update_post_meta($postId, '_audio', $upload);  
	            }
			}
			else{
				//echo "Sin permisos";
				//exit;
			}

			//check nonce for security
			//check_admin_referer( 'portfolio_meta_save', 'lport' );

			// save the meta box data as post metadata
			///update_post_meta( $postId, '_subtitle', sanitize_text_field( $_POST['subtitle'] ) );
		}
	}

	/***
	* Save an input  field
	* 
	* @param $fieldName the field name in the form
	*/
	function saveFile($fieldName){

		// Make sure the file array isn't empty
    	if(!empty($_FILES[$fieldName]['name'])) {
         	echo "Guardando";
	        // Setup the array of supported file types. In this case, it's just PDF.
	        $supported_types = array('application/pdf');
	         
	        // Get the file type of the upload
	        $arr_file_type = wp_check_filetype(basename($_FILES[$fieldName]['name']));
	        $uploaded_type = $arr_file_type['type'];
	       

	         
	        // Check if the type is supported. If not, throw an error.
	        if(in_array($uploaded_type, $supported_types) || true ) {
	 

	            // Use the WordPress API to upload the file
	            $upload = wp_upload_bits($_FILES[$fieldName]['name'], null, file_get_contents($_FILES[$fieldName]['tmp_name']));

	            return $upload;
	     
	            /*if(isset($upload['error']) && $upload['error'] != 0) {
	                //wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
	                //return $upload['error'];
	            } else {
	                add_post_meta($id, 'wp_custom_attachment', $upload);
	                update_post_meta($id, 'wp_custom_attachment', $upload);    
	            } // end if/else
	            */
	 
	        } else {
	        	return "The file type is not supported";
	            //wp_die("The file type that you've uploaded is not a PDF.");
	        } // end if/else
	         
	    } // end if
	}


	function addMymeType($mime_types){
    	$mime_types['mp3'] = 'audio/mpeg3'; //Adding mp3
    	return $mime_types;
	}
	add_filter('upload_mimes', 'addMymeType', 1, 1);


	/**
	* Return the iframe url from a video url
	*
	* @param  string  $videoUrl the url of the video
	* @return string The url to be used in a iframe element src property
	*/
	function getEmbedVideoUrl($videoUrl){
	    // Remove info from videos on youtube
	    $youtubeHide = "?hd=1&rel=0&autohide=1&showinfo=0";
	    if(strpos($videoUrl , "vimeo") !== FALSE){
	        $tmp = explode("/", $videoUrl );
	        $values = array_values($tmp);
	        $src = "//player.vimeo.com/video/" . end( $values );
	        return $src;
	    }
	    elseif(strpos($videoUrl , "youtube") !== FALSE){
	        //https://www.youtube.com/watch?v=rLy-3pqY2YM&feature=youtu.be
	        $tmp = explode("=", $videoUrl );
	        $values = array_values($tmp);
	        $video = $values[1];
	        $tmp = explode("&", $video );
	        $src = "//www.youtube.com/embed/" . $tmp[0] . $youtubeHide;
	        return $src;
	    }
	    elseif(strpos($videoUrl , "youtu.be") !== FALSE){
	        $tmp = explode("youtu.be/", $videoUrl);
	        $videoValue = $tmp[1];
	        $src = "//www.youtube.com/embed/" . $tmp[1] . $youtubeHide;
	        return $src;
	    }
	    return $videoUrl;
	}


	/**
	* Return the media html for a selected appearance
	*
	* @param  int  $id the appearance id
	* @param  string  $thumb_size the image size
	* @return string The HTML to use
	*/
	function getAppearanceMediaHTML($id, $thumb_size ){
		$image   = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
		$video   = get_post_meta( $id, '_video', true);
		$audio   = get_post_meta( $id, '_audio', true );
		$videoE  = getEmbedVideoUrl($video);

		$content = "";
		$preFrame = '<div class="fluid-width-video-wrapper appearancesList" style="min-height:300px;height:300px">';
		$postFrame = '</div>';
		if(strpos($video , "vimeo") !== FALSE){
			$content .= $preFrame ;
			$content .= "<iframe src='$videoE?title=0&amp;byline=0&amp;portrait=0' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
			$content .= $postFrame ;
		}
		elseif(strpos($video , "youtube") !== FALSE){
			$content .= $preFrame ;
			$content .= "<iframe  src='$videoE' wmode='Opaque' frameborder='0' allowfullscreen></iframe>";
			$content .= $postFrame ;
		}
		elseif(strpos($video , "youtu.be") !== FALSE){
			$content .= $preFrame ;
			$content .= "<iframe  src='$videoE' wmode='Opaque' frameborder='0' allowfullscreen></iframe>";
			$content .= $postFrame ;
		}
		elseif( $image != ""){
			$content .= $preFrame ;
			$content .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
			///$content .= "<img class='attachment-blog_image_in_grid wp-post-image' src='$image' />";
			$content .= $postFrame ;
		}
		elseif( isset($audio["url"]) && $audio["url"] != "" ){
			$content  = '<audio class="blog_audio" src="'.$audio["url"].'" controls="controls">
						Your browser dont support audio player
					  </audio>';
		}
		else{
			$content = "<div class='noMedia'><img src='http://placehold.it/300x300?text=+'  /></div>";
		}

		return $content;
	}

	/**
	* Return the iframe of a video url
	*
	* @param  string  $video the video url
	* @return string The iframe html
	*/
	function getVideoFrame($video){
		$videoFrame = "";
		$videoE  = getEmbedVideoUrl($video);

		if(strpos($video , "vimeo") !== FALSE){
			$videoFrame = "<iframe src='$videoE?title=0&amp;byline=0&amp;portrait=0' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
		}
		elseif(strpos($video , "youtube") !== FALSE){
			$videoFrame = "<iframe  src='$videoE' wmode='Opaque' frameborder='0' allowfullscreen></iframe>";
		}
		elseif(strpos($video , "youtu.be") !== FALSE){
			$videoFrame = "<iframe  src='$videoE' wmode='Opaque' frameborder='0' allowfullscreen></iframe>";
		}
		return $videoFrame;
	}


