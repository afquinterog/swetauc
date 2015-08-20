<?php
//nonce field for security
wp_nonce_field( 'portfolio_meta_save', 'port' );
?>


<p>
	<?php echo __('Title', '') ;?>
	<input type="text" name="subtitle" value="<?php echo esc_attr( $subtitle ); ?>" style="width:90%">
</p>