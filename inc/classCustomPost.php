<?php
class CustomPost {
	protected $singular;
	protected $plural;
	protected $slug; 
	protected $supports  = array('title');	
	protected $menu_icon; 
	protected $labels;
    public $args;
	public function __construct($singular, $plural, $supports=array(), $menu_icon='dashicons-admin-post'){
		$this->singular = $singular;
		$this->plural = $plural;
		$this->slug = str_replace(' ', '_', strtolower($singular));
		$this->supports = array_unique( array_merge( $this->supports, $supports ) );
		$this->menu_icon=$menu_icon;
   	$this->labels = array( 
 		'name' 			=> $this->plural, 
 		'singular_name' 	=> $this->singular, 
 		'add_new' 		=> 'Add New', 
 		'add_new_item'  	=> 'Add New ' . $this->singular, 
 		'edit'		        => 'Edit', 
 		'edit_item'	        => 'Edit ' . $this->singular, 
 		'new_item'	        => 'New ' . $this->singular, 
 		'view' 			=> 'View ' . $this->singular, 
 		'view_item' 		=> 'View ' . $this->singular, 
 		'search_term'   	=> 'Search ' . $this->plural, 
 		'parent' 		=> 'Parent ' . $this->singular, 
 		'not_found' 		=> 'No ' . $this->plural .' found', 
 		'not_found_in_trash' 	=> 'No ' . $this->plural .' in Trash' 
 		); 

   	$this->args = array( 
 		    'labels'              => $this->labels, 
 	        'public'              => true, 
 	        'publicly_queryable'  => true, 
 	        'exclude_from_search' => false, 
 	        'show_in_nav_menus'   => true, 
 	        'show_ui'             => true, 
 	        'show_in_menu'        => true, 
 	        'show_in_admin_bar'   => true, 
 	        'menu_position'       => 10, 
 	        'menu_icon'           => $this->menu_icon, 
 	        'can_export'          => true, 
 	        'delete_with_user'    => false, 
 	        'hierarchical'        => false, 
 	        'has_archive'         => true, 
 	        'query_var'           => true, 
 	        'capability_type'     => 'post', 
 	        'map_meta_cap'        => true, 
 	        // 'capabilities' => array(), 
 	        'rewrite'             => array(  
 	        	'slug' => $this->slug, 
 	        	'with_front' => true, 
 	        	'pages' => true, 
 	        	'feeds' => true, 
  
 	        ), 
 	        'supports' => $this->supports

 	       ); 

		apply_filters('ht_post_change_args', $this->args);
		
		add_action( 'init', array( $this, 'register_post' ) );	
  }

 	public function register_post(){
		register_post_type( $this->slug, $this->args );
		
	}

	public function get_slug(){
		return $this->slug;
	}

}

