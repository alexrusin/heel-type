<?php

class CustomMetabox {

	public $metabox_id;
	public $metabox_title;

	public function __construct($metabox_id, $metabox_title){
		$this->metabox_id=$metabox_id;
		$this->metabox_title=$metabox_title;

		add_action( 'add_meta_boxes', array($this, 'custom_meta' ));
		add_action( 'save_post', array($this, 'meta_save' ));
	}

	
	public function custom_meta(){
			add_meta_box(
      		$this->metabox_id,
      		$this->metabox_title,
     		array($this, 'description_field_callback'),
      		'heel',
      		'normal',
      		'high'
    	);
	}

	
	public function description_field_callback($post){
		wp_nonce_field( basename( __FILE__ ), 'heels_nonce' );
		
		?>
		 <div class="meta-row"> 
        	<div class="meta-th"> 
	          <label for="heel-description" class="wpdt-row-title"><?php _e( 'Please, describe the heel')?></label> 
	        </div> 
	        <div class="meta-td"> 
 	          <textarea name="heel_description" class ="hrm-textarea" id="heel-description"><?php if ( isset ( $stored_meta['heel_description'] ) ) echo esc_attr( $stored_meta['heel_description'][0] ); ?></textarea> 
 	        </div> 
 	    </div> 
 	    

<?php
	}

	public function meta_save($post_id){
		if ( isset( $_POST[ 'heel_description' ] ) ) { 
		     	update_post_meta( $post_id, 'heel_description', sanitize_text_field( $_POST[ 'heel_description' ] ) ); 

		}
	}	
}