<?php

class CustomTaxonomy{
	protected $singular;
	protected $plural;
	protected $slug;
	protected $labels;
	private $post_slug;
	public $args;

	public function __construct($singular, $plural, $post_slug, $hierarchical=true) {
	$this->singular = $singular;
	$this->plural = $plural;
	$this->post_slug = $post_slug;
	$this->slug = str_replace(' ', '_', strtolower($singular));
    $this->labels = array(
 		'name' => $this->plural,
        'singular_name' => $this->singular,
        'search_items' => 'Search ' . $this->plural,
        'popular_items' => 'Popular ' . $this->plural,
 		'all_items' => 'All ' . $this->plural,
 		'parent_item' => null,
 		'parent_item_colon' => null,
 		'edit_item' => 'Edit ' . $this->singular,
 		'update_item' => 'Update ' . $this->singular,
 		'add_new_item' => 'Add New ' . $this->singular,
 		'new_item_name' => 'New ' . $this->singular . ' Name',
 		'separate_items_with_commas' => 'Separate ' . $this->plural . ' with commas',
 		'add_or_remove_items' => 'Add or remove ' . $this->plural,
 		'choose_from_most_used' => 'Choose from the most used ' . $this->plural,
 		'not_found' => 'No ' . $this->plural . ' found.',
 		'menu_name' => $this->plural,
 		);
	
    $this->args = array(
 		'hierarchical' => $hierarchical,
 		'labels' => $this->labels,
 		'show_ui' => true,
 		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
 		'query_var' => true,
 		'rewrite' => array( 'slug' => $this->slug ),
		 );
    	
    	apply_filters('ht_change_tax_args', $this->args);
   	
		add_action( 'init', array($this, 'register_custom_tax') );
		
	}

	

	public function register_custom_tax(){
		register_taxonomy( $this->slug, $this->post_slug, $this->args );
 	}
	
	public function get_slug(){
		return $this->slug;
	}


	//////////////add upload image //////////////////////////
	public function add_upload_img(){
		add_action($this->slug.'_add_form_fields', array($this, 'custom_img_field') );
		add_action($this->slug.'_edit_form_fields', array($this, 'custom_img_field_edit'));
		add_action('edited_'.$this->slug, array($this, 'save_custom_image'));
		add_action('create_'.$this->slug, array($this, 'save_custom_image'));
	}
	public function custom_img_field(){
		wp_nonce_field(basename(__FILE__), 'custom_image_nonce');
	?>
	<div class="form-field">
		<img id="image-tag"><br />
		<input type="hidden" id="img-hidden-field" name="custom_image_data">
		<input type="button" id="image-upload-button" class="button" value="Add Image">
		<input type="button" id="image-delete-button" class="button" value="Delete Image" style="display:none;">
<p class="description"><?php _e( 'Upload an image for this category','pippin' ); ?></p>
	</div>
<?php
}
public function custom_img_field_edit($term){
		$btn_hide_delete = '';
		$btn_hide_add = '';
		$t_id = $term->term_id;
		$term_meta = get_option( "taxonomy_".$t_id);
		if(!$term_meta || sizeof($term_meta)==0){
			$btn_hide_delete = 'none';
			$style_none = '';
		} else {
			$btn_hide_add = 'none';
			$style_none = 'style="width:200px;"';
		}
		
		wp_nonce_field(basename(__FILE__), 'custom_image_nonce');
	?>
	<div class="form-field">
		<img id="image-tag" src="<?php echo $term_meta['src'];?>" <?php echo $style_none;?> ><br />
		<input type="hidden" id="img-hidden-field" name="custom_image_data">
		<input type="button" id="image-upload-button" class="button" value="Add Image" style="display:<?php echo $btn_hide_add;?>;">
		<input type="button" id="image-delete-button" class="button" value="Delete Image" style="display:<?php echo $btn_hide_delete;?>;" >
<p class="description"><?php _e( 'Upload an image for this category','heel-type' ); ?></p>
	</div>
<?php
}

    public function save_custom_image($term_id){
    	$t_id=$term_id;
    	$is_valid_nonce = ( isset( $_POST[ 'custom_image_nonce' ] ) && wp_verify_nonce( $_POST[ 'custom_image_nonce' ], basename( __FILE__ ) ) );
    	if (!$is_valid_nonce ) {
			return;
		}


	if ( isset( $_POST[ 'custom_image_data' ] ) ) {
		$image_data = json_decode( stripslashes( $_POST[ 'custom_image_data' ] ) );
		if ( is_object( $image_data[0] ) ) {
			$image_data = array( 'id' => intval( $image_data[0]->id ), 'src' => esc_url_raw( $image_data[0]->url
			) );
		} else {
			$image_data = [];
		}
		update_option( "taxonomy_".$t_id, $image_data );
	}
	
}


	
	
}