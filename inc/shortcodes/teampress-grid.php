<?php
function extp_shortcode_grid( $atts ) {
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
	global $ID,$number_excerpt;
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'extp-'.rand(10,9999);
	if(!isset($atts['ID'])){
		$atts['ID']= $ID;
	}
	$style = sanitize_text_field(isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1');
	if($style == 1){
		$style = 1;
	}elseif ($style == 2) {
		$style = 2;
	}
	elseif ($style == 3) {
		$style = 6;
	}
	elseif ($style == 4) {
		$style = 9;
	}
	elseif ($style == 'img-1') {
		$style = 'img-4';
	}elseif ($style == 'img-2') {
		$style = 'img-9';
	}else{
		$style = 1;
	}
	$column = intval(isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2');
	if($column < 2 || $column > 5){
		$column = 2;
	}
	$posttype = 'ex_team';
	$ids   = sanitize_text_field(isset($atts['ids']) ? $atts['ids'] : '');
	$taxonomy  = sanitize_text_field(isset($atts['taxonomy']) ? $atts['taxonomy'] : '');
	$cat   = sanitize_text_field(isset($atts['cat']) ? $atts['cat'] : '');
	$tag  = sanitize_text_field(isset($atts['tag']) ? $atts['tag'] : '');
	$count   = intval(isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9');
	if($count < 0){
		$count = 9;
	}
	$posts_per_page   = intval(isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3');
	if($posts_per_page < 0){
		$posts_per_page = 3;
	}
	$order  = sanitize_text_field(isset($atts['order']) ? $atts['order'] : '');
	$orderby  = sanitize_text_field(isset($atts['orderby']) ? $atts['orderby'] : '');
	$meta_key  = sanitize_text_field(isset($atts['meta_key']) ? $atts['meta_key'] : '');
	$meta_value  = sanitize_text_field(isset($atts['meta_value']) ? $atts['meta_value'] : '');
	$class  = sanitize_text_field(isset($atts['class']) ? $atts['class'] : '');
	$page_navi  = sanitize_text_field(isset($atts['page_navi']) ? $atts['page_navi'] : '');
	$number_excerpt =  intval(isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10');
	if($number_excerpt < 0){
		$number_excerpt = 10;
	}
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
	$args = ex_teampress_query($posttype, $posts_per_page, $order, $orderby, $cat, $tag, 'extp_cat', $meta_key, $ids, $meta_value);
	
	$the_query = new WP_Query( $args );
	ob_start();
	$class = $class." column-".$column;
	
	$class = $class." style-".$style;
	if($style == 1 || $style == 3 || $style == 13 || $style == 14 || $style == 15 || $style == 16){
		$class = $class." style-classic";
	}
	$html_modal ='';
	?>
	<div class="ex-tplist <?php echo esc_attr($class);?>" id ="<?php echo esc_attr($ID);?>">
        <div class="ctgrid">
		<?php
		$num_pg = '';
		if ($the_query->have_posts()){ 
			$i=0;
			$it = $the_query->found_posts;
			if($it < $count || $count=='-1'){ $count = $it;}
			if($count  > $posts_per_page){
				$num_pg = ceil($count/$posts_per_page);
				$it_ep  = $count%$posts_per_page;
			}else{
				$num_pg = 1;
			}
			$arr_ids = array();
			while ($the_query->have_posts()) { $the_query->the_post();
				$arr_ids[] = get_the_ID();
				$i++;
				if(($num_pg == $paged) && $num_pg!='1'){
					if($i > $it_ep){ break;}
				}
				echo '<div class="item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow" >
						<?php 
						extp_template_plugin('grid-'.$style,1);
						?>
					<div class="exclearfix"></div>
					</div>
					<?php
				echo '</div>';
			}
		} ?>
		</div>
        
		<?php
		if($num_pg>1){
			if($page_navi=='loadmore'){
				extp_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids); 
			}
		}
		?>
	</div>
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_tpgrid', 'extp_shortcode_grid' );
add_action( 'after_setup_theme', 'wt_reg_grid_vc' );
function wt_reg_grid_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("TeamPress - Grid", "teampress"),
	   "base" => "ex_tpgrid",
	   "class" => "",
	   "icon" => "icon-grid",
	   "controls" => "full",
	   "category" => esc_html__('TeamPress','teampress'),
	   "params" => array(
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Style", 'teampress'),
			 "param_name" => "style",
			 "value" => array(
				esc_html__('1', 'teampress') => '1',
				esc_html__('2', 'teampress') => '2',
				esc_html__('3', 'teampress') => '3',
				esc_html__('4', 'teampress') => '4',
				esc_html__('Image hover 1', 'teampress') => 'img-1',
				esc_html__('Image hover 2', 'teampress') => 'img-2',
			 ),
			 "description" => esc_html__('Number of style', 'teampress')
		  ),
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Columns", 'teampress'),
			 "param_name" => "column",
			 "value" => array(
				esc_html__('2 columns', 'teampress') => '2',
				esc_html__('3 columns', 'teampress') => '3',
				esc_html__('4 columns', 'teampress') => '4',
				esc_html__('5 columns', 'teampress') => '5',
			 ),
			 "description" => esc_html__('Number of column', 'teampress')
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Count", "teampress"),
			"param_name" => "count",
			"value" => "",
			"description" => esc_html__("Number of posts", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Posts per page", "teampress"),
			"param_name" => "posts_per_page",
			"value" => "",
			"description" => esc_html__("Number items per page", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Full content in", 'teampress').'<span style="color:red"> Only Pro version</span>',
			 "param_name" => "fullcontent_in",
			 "value" => array(
				esc_html__('None', 'teampress') => '',
				esc_html__('Lightbox', 'teampress') => 'lightbox',
				esc_html__('Collapse', 'teampress') => 'collapse',
				esc_html__('Modal', 'teampress') => 'modal',
			 ),
			 "description" => esc_html__('Show full infomartion member in', 'teampress')
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("IDs", "teampress"),
			"param_name" => "ids",
			"value" => "",
			"description" => esc_html__("Specify post IDs to retrieve", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Category", "teampress"),
			"param_name" => "cat",
			"value" => "",
			"description" => esc_html__("List of cat ID (or slug), separated by a comma", "teampress"),
		  ),
		  /*array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Tags", "teampress"),
			"param_name" => "tag",
			"value" => "",
			"description" => esc_html__("List of tags, separated by a comma", "teampress"),
		  ),*/
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order", 'teampress'),
			 "param_name" => "order",
			 "value" => array(
			 	esc_html__('DESC', 'teampress') => 'DESC',
				esc_html__('ASC', 'teampress') => 'ASC',
			 ),
			 "description" => ''
		  ),
		  array(
		  	 "admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order by", 'teampress'),
			 "param_name" => "orderby",
			 "value" => array(
			 	esc_html__('Date', 'teampress') => 'date',
				esc_html__('ID', 'teampress') => 'ID',
				esc_html__('Author', 'teampress') => 'author',
			 	esc_html__('Title', 'teampress') => 'title',
				esc_html__('Name', 'teampress') => 'name',
				esc_html__('Modified', 'teampress') => 'modified',
			 	esc_html__('Parent', 'teampress') => 'parent',
				esc_html__('Random', 'teampress') => 'rand',
				esc_html__('Menu order', 'teampress') => 'menu_order',
				esc_html__('Meta value', 'teampress') => 'meta_value',
				esc_html__('Meta value num', 'teampress') => 'meta_value_num',
				esc_html__('Post__in', 'teampress') => 'post__in',
				esc_html__('None', 'teampress') => 'none',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta key", "teampress"),
			"param_name" => "meta_key",
			"value" => "",
			"description" => esc_html__("Enter meta key to query", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta value", "teampress"),
			"param_name" => "meta_value",
			"value" => "",
			"description" => esc_html__("Enter meta value to query", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of Excerpt", "teampress"),
			"param_name" => "number_excerpt",
			"value" => "",
			"description" => esc_html__("Enter number", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Page navi", 'teampress'),
			 "param_name" => "page_navi",
			 "value" => array(
			 	esc_html__('None', 'teampress') => '',
			 	esc_html__('Load more', 'teampress') => 'loadmore',
			 	esc_html__('Number (Only Pro version)', 'teampress') => '',
			 ),
			 "description" => esc_html__("Select type of page navigation", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Search box", 'teampress').'<span style="color:red"> Only Pro version</span>',
			 "param_name" => "search_box",
			 "value" => array(
			 	esc_html__('Hide', 'teampress') => 'hide',
			 	esc_html__('Show', 'teampress') => 'show',
			 ),
			 "description" => esc_html__("Show search box", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Alphabetical filter", 'teampress').'<span style="color:red"> Only Pro version</span>',
			 "param_name" => "alphab_filter",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
				esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Show Alphabetical filter", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Masonry layout", 'teampress').'<span style="color:red"> Only Pro version</span>',
			 "param_name" => "masonry",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
				esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Enable Masonry layout", "teampress"),
		  ),
	   )
	));
	}
}