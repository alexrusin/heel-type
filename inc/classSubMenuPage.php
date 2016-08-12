<?php

class SubMenuPage {

	private $parent_slug;
	private $tax_slug;
	private $page_title; 
	private $menu_title; 
	private $capability;
	private $menu_slug;
	private $cb_function = 'nothing_displayed';

	public function __construct($arguments){
		foreach ($arguments as $key => $value) {
			$this->$key = $value;
			
		}

		add_action('admin_menu', array($this,'add_sbm_page'));
		add_action('wp_ajax_save_sort', array($this, 'save_reorder'));
	}

	public function add_sbm_page(){

		add_submenu_page(
		'edit.php?post_type='.$this->parent_slug, 
		$this->page_title, 
		$this->menu_title, 
		$this->capability, 
		$this->menu_slug, 
		array($this, $this->cb_function)
		);
	}

	public function nothing_displayed(){
		echo '<h3> No callback function is defined to display.  Please, define function in your args array from list below:</h3>';
		echo '<p>reorder_heels_page</p>';
	}

	public function reorder_heels_page(){
		$taxonomies=get_terms($this->tax_slug);
		$tax_cat_slugs=wp_list_pluck($taxonomies, 'name', 'slug');
		$counter=1;
		foreach ($tax_cat_slugs as $key => $value) {
		if ($counter==1){?>
			<h2 class="reorder-title">Reorder <?php echo $value;?>
				<img src="<?php echo esc_url( admin_url() . 'images/loading.gif' ); ?>" id="loading-animation"/> </h2>
		<?php } else {?>
			<h2 class="reorder-title-next">Reorder <?php echo $value;?>
				<img src="<?php echo esc_url( admin_url() . 'images/loading.gif' ); ?>" id="loading-animation"/> </h2>
		<?php }
			$args = array(
				'post_type' => $this->parent_slug, //don't really need this for query to work
				'orderby'   => 'menu_order',
				'order'		=> 'ASC',
				'post_status' => 'publish',
				'tax_query' => array(
					array(
						'taxonomy' => $this->tax_slug,
						'field'    => 'slug',
						'terms'    => $key,
					),
				),
			);
			$query = new WP_Query( $args );?>
			<div class="reordered-list">
				<?php $this->admin_heels_display($query);?>
				<div class="clear"></div>
			</div>
		<?php $counter++; }
	}	
	

	private function admin_heels_display($the_query){
		if ( $the_query->have_posts() ) :?>
			<ul id="custom-type-list">
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<li id="<?php esc_attr(the_id()); ?>"><?php esc_html(the_title()); ?></li>
				<?php endwhile;?>
			</ul>
			 <?php else : ?>
				<p><?php _e( 'Sorry, no posts were found' ); ?></p>
			<?php endif; 

	}

	public function save_reorder(){

		if(!check_ajax_referer('reorder-heel-types', 'security')){
			return wp_send_json_error('Invalid Nonce');
		}

		if(!current_user_can('manage_options')){
			return wp_send_json_error('You are not allowed to do this action');
		}

		$order=$_POST['order'];
		$counter=0;

		foreach ($order as $item_id) {
			$post = array(
				'ID' => (int)$item_id,
				'menu_order' => $counter,
			);

			wp_update_post($post);
			$counter ++;
		}
	}

}