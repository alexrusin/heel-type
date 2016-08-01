<?php

class SubMenuPage {

	private $parent_slug; 
	private $page_title; 
	private $menu_title; 
	private $capability;
	private $menu_slug;
	private $cb_function;

	public function __construct($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $cb_function=null){
		$this->parent_slug = $parent_slug;
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->menu_slug = $menu_slug;
		if ($cb_function != null){
			$this->cb_function = array($this, $cb_function);
		} else {
			$this->cb_function=$cb_function;
		}


		add_action('admin_menu', array($this,'add_sbm_page'));
	}

	public function add_sbm_page(){
		add_submenu_page(
		'edit.php?post_type='.$this->parent_slug, 
		$this->page_title, 
		$this->menu_title, 
		$this->capability, 
		$this->menu_slug, 
		$this->cb_function
		);
	}

	public function reorder_heels_page(){
		echo '<h2>This is reorder heels page</h2>';
	}
}