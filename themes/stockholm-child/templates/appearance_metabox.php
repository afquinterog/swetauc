<?php
//nonce field for security
wp_nonce_field( 'appearance_meta_save', 'form');

?>

<p> 
	Audio: <input type="file" id="audio" name="audio" value="" size="25" /> 
</p>

<?php 	if ( isset($audio["url"]) && $audio["url"] != "" ): ?>
	<p>
		Actual Audio File: <a href="<?php echo $audio["url"]; ?>">Download</a>
	</p>
<?php endif; ?>

<p> 
	Video <input type="text" name="video" value="<?php echo esc_attr( $video ); ?>" style="width:80%" />
</p>