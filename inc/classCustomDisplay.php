<?php
class CustomDisplay {

	private $title;
	private $taxonomy_name;

	/*sets up class attributes.  $title is an optional variable*/

	public function __construct($taxonomy_name, $title=''){
		$this->title = $title;
		$this->taxonomy_name = $taxonomy_name;
	}

	public function display_heel_type(){
		
		add_shortcode('display_heel_types', array( $this, 'heel_type_list' ) );
	}


	//short code function cannot be private

	public function heel_type_list(){
		
		$atts = shortcode_atts(
		array(
			'title' => $this->title
		), $atts
	);
	$heel_types = get_terms($this->taxonomy_name);
	$output='';
	$output.='<h2>'.$atts['title'].'</h2>';
	foreach ($heel_types as $heel_type) {
		$t_id = $heel_type->term_id;
		$term_meta = get_option( "taxonomy_".$t_id);
		
		$output.='<div id="heel-types">';
		$output.='<div class="heel-container">';
		$output.='<a href="'. get_term_link($t_id) .'">';
		$output.='<img src="'. $term_meta['src'].'" width="200px">';
		$output.='</a>';
		$output.='<div class="heel-text">';
		$output.='<p>'.$heel_type->name.'</p>';
		$output.='</div></div><div class="clear-fix"></div></div>';
			
		}
	return $output;

	}

	public function cust_taxonomy_display(){
		add_action('template_include', array($this, 'load_tax_template'));
	}

	public function load_tax_template($original_template){
 	if ( is_tax($this->taxonomy_name)) {
              
                        return plugin_dir_path(__FILE__).'../templates/taxonomy-heel_type.php';
   	}else{
        	return $original_template;
        }
 }
}