<?php
include 'inc/metadata-functions.php';
class EX_TeamPress_Posttype {
	public function __construct()
    {
        add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'init', array( &$this, 'register_category_taxonomies' ) );
    }

	function register_post_type(){
		$labels = array(
			'name'               => esc_html__('Team','teampress'),
			'singular_name'      => esc_html__('Team','teampress'),
			'add_new'            => esc_html__('Add New Member','teampress'),
			'add_new_item'       => esc_html__('Add New Member','teampress'),
			'edit_item'          => esc_html__('Edit Member','teampress'),
			'new_item'           => esc_html__('New Member','teampress'),
			'all_items'          => esc_html__('Members','teampress'),
			'view_item'          => esc_html__('View Member','teampress'),
			'search_items'       => esc_html__('Search Member','teampress'),
			'not_found'          => esc_html__('No Member found','teampress'),
			'not_found_in_trash' => esc_html__('No Member found in Trash','teampress'),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Team','teampress')
		);
		
		$extp_single_slug = extp_get_option('extp_single_slug');
		if($extp_single_slug==''){
			$extp_single_slug = 'member';
		}
		$rewrite =  array( 'slug' => untrailingslashit( $extp_single_slug ), 'with_front' => false, 'feeds' => true );
		
		$args = array(  
			'labels' => $labels,  
			'menu_position' => 8, 
			'supports' => array('title','editor','thumbnail', 'excerpt','custom-fields'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			//'show_in_menu'       => 'edit.php?post_type=product',
			'menu_icon' =>  'dashicons-groups',
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite' => $rewrite,
		);  
		register_post_type('ex_team',$args);  
	}
	function register_category_taxonomies(){
		$labels = array(
			'name'              => esc_html__( 'Category', 'wp-timeline' ),
			'singular_name'     => esc_html__( 'Category', 'wp-timeline' ),
			'search_items'      => esc_html__( 'Search','wp-timeline' ),
			'all_items'         => esc_html__( 'All category','wp-timeline' ),
			'parent_item'       => esc_html__( 'Parent category' ,'wp-timeline'),
			'parent_item_colon' => esc_html__( 'Parent category:','wp-timeline' ),
			'edit_item'         => esc_html__( 'Edit category' ,'wp-timeline'),
			'update_item'       => esc_html__( 'Update category','wp-timeline' ),
			'add_new_item'      => esc_html__( 'Add New category' ,'wp-timeline'),
			'menu_name'         => esc_html__( 'Categories','wp-timeline' ),
		);			
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'timeline-category' ),
		);
		register_taxonomy('extp_cat', 'ex_team', $args);
	}	
}
$EX_TeamPress_Posttype = new EX_TeamPress_Posttype();