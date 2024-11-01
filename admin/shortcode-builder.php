<?php
class EXTP_SC_Builder {
	public function __construct(){
        add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array(&$this,'register_metabox') );
		add_action( 'save_post', array($this,'save_shortcode'),1 );
		add_shortcode( 'extpsc', array($this,'run_extpsc') );
    }
	function run_extpsc($atts, $content){
		$id = isset($atts['id']) ? $atts['id'] : '';
		$sc = get_post_meta( $id, '_tpsc', true );
		if($id=='' || $sc==''){ return;}
		return do_shortcode($sc);
	}
	function save_shortcode($post_id){
		if('team_scbd' != get_post_type()){ return;}
		if(isset($_POST['sc_type'])){
			$style = sanitize_text_field(isset($_POST['style']) ? $_POST['style'] : 1);
			$column = intval(isset($_POST['column']) ? $_POST['column'] : 3);
			if($column < 2 || $column > 5){
				$column = 2;
			}
			$count = intval(isset($_POST['count']) && $atts['count'] !=''? $_POST['count'] : '9');
			if($count < 0){
				$count = 9;
			}
			$posts_per_page = intval(isset($_POST['posts_per_page']) ? $_POST['posts_per_page'] : '3');
			if($posts_per_page < 0){
				$posts_per_page = 3;
			}
			$slidesshow = intval(isset($_POST['slidesshow']) ? $_POST['slidesshow'] : '');
			if($slidesshow < 0){
				$slidesshow = 3;
			}
			$ids = sanitize_text_field(isset($_POST['ids']) ? $_POST['ids'] : '');
			$cat = sanitize_text_field(isset($_POST['cat']) ? $_POST['cat'] : '');
			$order = sanitize_text_field(isset($_POST['order']) ? $_POST['order'] : '');
			$orderby = sanitize_text_field(isset($_POST['orderby']) ? $_POST['orderby'] : '');
			$meta_key = sanitize_text_field(isset($_POST['meta_key']) ? $_POST['meta_key'] : '');
			$meta_value = sanitize_text_field(isset($_POST['meta_value']) ? $_POST['meta_value'] : '');
			$number_excerpt = intval(isset($_POST['number_excerpt']) ? $_POST['number_excerpt'] : '');
			if($number_excerpt < 0){
				$number_excerpt = 10;
			}
			$page_navi = sanitize_text_field(isset($_POST['page_navi']) ? $_POST['page_navi'] : '');
			$autoplay = intval(isset($_POST['autoplay']) ? $_POST['autoplay'] : '');
			if($autoplay < 0 || $autoplay > 1){
				$autoplay = 0;
			}
			$autoplayspeed = intval(isset($_POST['autoplayspeed']) ? $_POST['autoplayspeed'] : '');
			if($autoplayspeed < 0){
				$autoplayspeed = 3000;
			}
			$infinite = sanitize_text_field(isset($_POST['infinite']) ? $_POST['infinite'] : '');
			$slidestoscroll = sanitize_text_field(isset($_POST['slidestoscroll']) ? $_POST['slidestoscroll'] : '');
			if ($slidestoscroll !='') {
				$slidestoscroll = intval($slidestoscroll);
				if ($slidestoscroll < 1) {
					$slidestoscroll='';
				}
			}
			if(sanitize_text_field($_POST['sc_type']) == 'grid'){
				
				$sc = '[ex_tpgrid style="'.$style.'" column="'.$column.'" count="'.$count.'" posts_per_page="'.$posts_per_page.'" ids="'.$ids.'" cat="'.$cat.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'" page_navi="'.$page_navi.'"]';
				
			}elseif(sanitize_text_field($_POST['sc_type']) == 'list'){
				
				$sc = '[ex_tplist style="'.$style.'" count="'.$count.'" posts_per_page="'.$posts_per_page.'" ids="'.$ids.'" cat="'.$cat.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'"  page_navi="'.$page_navi.'"]';
				
			}else{
				
				$sc = '[ex_tpcarousel style="'.$style.'" count="'.$count.'" slidesshow="'.$slidesshow.'" ids="'.$ids.'" cat="'.$cat.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'"  autoplay="'.$autoplay.'"  autoplayspeed="'.$autoplayspeed.'" infinite="'.$infinite.'" slidestoscroll="'.$slidestoscroll.'"]';
				
			}
			if($sc!=''){
				update_post_meta( $post_id, '_tpsc', $sc );
			}
			update_post_meta( $post_id, '_shortcode', '[extpsc id="'.$post_id.'"]' );
		}
	}
	function register_post_type(){
		$labels = array(
			'name'               => esc_html__('Shortcodes','teampress'),
			'singular_name'      => esc_html__('Shortcodes','teampress'),
			'add_new'            => esc_html__('Add New Shortcodes','teampress'),
			'add_new_item'       => esc_html__('Add New Shortcodes','teampress'),
			'edit_item'          => esc_html__('Edit Shortcodes','teampress'),
			'new_item'           => esc_html__('New Shortcode','teampress'),
			'all_items'          => esc_html__('Shortcodes builder','teampress'),
			'view_item'          => esc_html__('View Shortcodes','teampress'),
			'search_items'       => esc_html__('Search Shortcodes','teampress'),
			'not_found'          => esc_html__('No Shortcode found','teampress'),
			'not_found_in_trash' => esc_html__('No Shortcode found in Trash','teampress'),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Shortcodes','teampress')
		);
		$rewrite = false;
		$args = array(  
			'labels' => $labels,  
			'menu_position' => 8, 
			'supports' => array('title','custom-fields'),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=ex_team',
			'menu_icon' =>  'dashicons-editor-ul',
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite' => $rewrite,
		);  
		register_post_type('team_scbd',$args);  
	}
	
	function register_metabox() {
		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$layout = new_cmb2_box( array(
			'id'            => 'sc_shortcode',
			'title'         => esc_html__( 'Shortcode type', 'teampress' ),
			'object_types'  => array( 'team_scbd' ), // Post type
		) );
	
		$layout->add_field( array(
			'name'             => esc_html__( 'Type', 'teampress' ),
			'desc'             => esc_html__( 'Select type of shortcode', 'teampress' ),
			'id'               => 'sc_type',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => 'grid',
			'options'          => array(
				'grid' => esc_html__( 'Grid', 'teampress' ),
				'list'   => esc_html__( 'List', 'teampress' ),
				'carousel'     => esc_html__( 'Carousel', 'teampress' ),
			),
		) );
		if(isset($_GET['post']) && is_numeric($_GET['post'])){
			$layout->add_field( array(
				'name'       => esc_html__( 'Shortcode', 'teampress' ),
				'desc'       => esc_html__( 'Copy this shortcode and paste it into your post, page, or text widget content:', 'teampress' ),
				'id'         => '_shortcode',
				'type'       => 'text',
				'classes'             => '',
				'attributes'  => array(
					'readonly' => 'readonly',
				),
			) );
		}
		$sc_option = new_cmb2_box( array(
			'id'            => 'sc_option',
			'title'         => esc_html__( 'Option', 'teampress' ),
			'object_types'  => array( 'team_scbd' ),
		) );
		
		$sc_option->add_field( array(
			'name'             => esc_html__( 'Style', 'teampress' ),
			'desc'             => esc_html__( 'Select style of shortcode', 'teampress' ),
			'id'               => 'style',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '1',
			'options'          => array(
				'1' => esc_html__('1', 'teampress'),
				'2' => esc_html__('2', 'teampress'),
				'3' => esc_html__('3', 'teampress'),
				'4' => esc_html__('4', 'teampress'),
				'img-1' => esc_html__('Image hover 1', 'teampress'),
				'img-2' => esc_html__('Image hover 2', 'teampress'),
			),
		) );
		
		$sc_option->add_field( array(
			'name'             => esc_html__( 'Columns', 'teampress' ),
			'desc'             => esc_html__( 'Select Columns of shortcode', 'teampress' ),
			'id'               => 'column',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel hide-intable hide-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '3',
			'options'          => array(
				'2' => esc_html__( '2 columns', 'teampress' ),
				'3'   => esc_html__( '3 columns', 'teampress' ),
				'4'   => esc_html__( '4 columns', 'teampress' ),
				'5'     => esc_html__( '5 columns', 'teampress' ),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Count', 'teampress' ),
			'desc'       => esc_html__( 'Number of posts', 'teampress' ),
			'id'         => 'count',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Posts per page', 'teampress' ),
			'desc'       => esc_html__( 'Number items per page', 'teampress' ),
			'id'         => 'posts_per_page',
			'type'       => 'text',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number items visible', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'slidesshow',
			'type'       => 'text',
			'classes'             => 'show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number item to scroll', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'slidestoscroll',
			'type'       => 'text',
			'classes'             => 'show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Full content in', 'teampress' ).'<span style="color:red"> Only Pro version</span>',
			'desc'       => esc_html__( 'Show full infomartion member in', 'teampress' ),
			'id'         => 'fullcontent_in',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__( 'None', 'teampress' ),
				'lightbox'   => esc_html__( 'Lightbox', 'teampress' ),
				'collapse'   => esc_html__( 'Collapse', 'teampress' ),
				'modal'     => esc_html__( 'Modal', 'teampress' ),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'IDs', 'teampress' ),
			'desc'       => esc_html__( 'Specify post IDs to retrieve', 'teampress' ),
			'id'         => 'ids',
			'type'       => 'text',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Category', 'teampress' ),
			'desc'       => esc_html__( 'List of cat ID (or slug), separated by a comma', 'teampress' ),
			'id'         => 'cat',
			'type'       => 'text',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order', 'teampress' ),
			'desc'       => '',
			'id'         => 'order',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'DESC' => esc_html__('DESC', 'teampress'),
				'ASC'   => esc_html__('ASC', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order by', 'teampress' ),
			'desc'       => '',
			'id'         => 'orderby',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'date' => esc_html__('Date', 'teampress'),
				'ID'   => esc_html__('ID', 'teampress'),
				'author' => esc_html__('Author', 'teampress'),
				'title'   => esc_html__('Title', 'teampress'),
				'name' => esc_html__('Name', 'teampress'),
				'modified'   => esc_html__('Modified', 'teampress'),
				'parent' => esc_html__('Parent', 'teampress'),
				'rand'   => esc_html__('Rand', 'teampress'),
				'menu_order' => esc_html__('Menu order', 'teampress'),
				'meta_value'   => esc_html__('Meta value', 'teampress'),
				'meta_value_num' => esc_html__('Meta value num', 'teampress'),
				'post__in'   => esc_html__('Post__in', 'teampress'),
				'None'   => esc_html__('None', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Meta key', 'teampress' ),
			'desc'       => esc_html__( 'Enter meta key to query', 'teampress' ),
			'id'         => 'meta_key',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Meta value', 'teampress' ),
			'desc'       => esc_html__( 'Enter meta value to query', 'teampress' ),
			'id'         => 'meta_value',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number of Excerpt', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'number_excerpt',
			'type'       => 'text',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Page navi', 'teampress' ),
			'desc'       => esc_html__( 'Select type of page navigation', 'teampress' ),
			'id'         => 'page_navi',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				''   => esc_html__('None', 'teampress'),
				'loadmore'   => esc_html__('Load more', 'teampress'),
				'none' => esc_html__('Number(Only Pro version)', 'teampress'),
				
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Search box', 'teampress' ).'<span style="color:red"> Only Pro version</span>',
			'desc'       => esc_html__( 'Show search box', 'teampress' ),
			'id'         => 'search_box',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'hide' => esc_html__('Hide', 'teampress'),
				'show'   => esc_html__('Show', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Alphabetical filter', 'teampress' ).'<span style="color:red"> Only Pro version</span>',
			'desc'       => esc_html__( 'Show Alphabetical filter', 'teampress' ),
			'id'         => 'alphab_filter',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Masonry layout', 'teampress' ).'<span style="color:red"> Only Pro version</span>',
			'desc'       => esc_html__( 'Enable Masonry layout', 'teampress' ),
			'id'         => 'masonry',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel hide-intable hide-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		
		/*$sc_option->add_field( array(
			'name'       => esc_html__( 'Slide to start on', 'teampress' ),
			'desc'       => esc_html__( 'Enter number, Default:0', 'teampress' ),
			'id'         => 'start_on',
			'type'             => 'text',
		) );*/
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Autoplay', 'teampress' ),
			'desc'       => esc_html__( 'Enable Autoplay', 'teampress' ),
			'id'         => 'autoplay',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'1'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Autoplay Speed', 'teampress' ),
			'desc'       => esc_html__( 'Autoplay Speed in milliseconds. Default:3000', 'teampress' ),
			'id'         => 'autoplayspeed',
			'type'             => 'text',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Loading effect', 'teampress' ).'<span style="color:red"> Only Pro version</span>',
			'desc'       => esc_html__( 'Enable Loading effect', 'teampress' ),
			'id'         => 'loading_effect',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'1'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Infinite', 'teampress' ),
			'desc'       => esc_html__( 'Infinite loop sliding ( go to first item when end loop)', 'teampress' ),
			'id'         => 'infinite',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
	
	}

	
}
$EXTP_SC_Builder = new EXTP_SC_Builder();