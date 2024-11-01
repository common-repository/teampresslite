<?php
//shortcode
include plugin_dir_path(__FILE__).'shortcodes/teampress-list.php';
include plugin_dir_path(__FILE__).'shortcodes/teampress-grid.php';
include plugin_dir_path(__FILE__).'shortcodes/teampress-carousel.php';
//widget
include plugin_dir_path(__FILE__).'widgets/teampress.php';

if(!function_exists('extp_startsWith')){
	function extp_startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
} 
if(!function_exists('extp_get_google_fonts_url')){
	function extp_get_google_fonts_url ($font_names) {
	
		$font_url = '';
	
		$font_url = add_query_arg( 'family', urlencode(implode('|', $font_names)) , "//fonts.googleapis.com/css" );
		return $font_url;
	} 
}
if(!function_exists('extp_get_google_font_name')){
	function extp_get_google_font_name($family_name){
		$name = $family_name;
		if(extp_startsWith($family_name, 'http')){
			// $family_name is a full link, so first, we need to cut off the link
			$idx = strpos($name,'=');
			if($idx > -1){
				$name = substr($name, $idx);
			}
		}
		$idx = strpos($name,':');
		if($idx > -1){
			$name = substr($name, 0, $idx);
			$name = str_replace('+',' ', $name);
		}
		return $name;
	}
}
if(!function_exists('extp_template_plugin')){
	function extp_template_plugin($pageName,$shortcode=false){
		if(isset($shortcode) && $shortcode== true){
			if (locate_template('teampress/content-shortcodes/content-' . $pageName . '.php') != '') {
				get_template_part('teampress/content-shortcodes/content', $pageName);
			} else {
				include extp_get_plugin_url().'templates/content-shortcodes/content-' . $pageName . '.php';
			}
		}else{
			if (locate_template('teampress/content-' . $pageName . '.php') != '') {
				get_template_part('teampress/content', $pageName);
			} else {
				include extp_get_plugin_url().'templates/content-' . $pageName . '.php';
			}
		}
	}
}

if(!function_exists('ex_teampress_query')){
    function ex_teampress_query($posttype, $count, $order, $orderby, $cat, $tag, $taxonomy, $meta_key, $ids, $meta_value=false,$page=false,$mult=false){
		$posttype = 'ex_team';
		if($orderby == 'timeline_date'){
			$meta_key = 'wptl_orderdate';
			$orderby = 'meta_value_num';
		}
		if($posttype == 'ex_team' && $taxonomy == ''){
			$taxonomy = 'extp_cat';
		}
		$posttype = explode(",", $posttype);
		if($ids!=''){ //specify IDs
			$ids = explode(",", $ids);
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish', 'future' ),
				'post__in' =>  $ids,
				'order' => $order,
				'orderby' => $orderby,
				'ignore_sticky_posts' => 1,
			);
		}elseif($ids==''){
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish', 'future' ),
				'tag' => $tag,
				'order' => $order,
				'orderby' => $orderby,
				'meta_key' => $meta_key,
				'ignore_sticky_posts' => 1,
			);
			if(!is_array($cat) && $taxonomy =='') {
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){
					$args['category__in'] = $cats;
				}else{			 
					$args['category_name'] = $cat;
				}
			}elseif( is_array($cat) && count($cat) > 0 && $taxonomy ==''){
				$args['category__in'] = $cat;
			}
			if($taxonomy !='' && $tag!=''){
				$tags = explode(",",$tag);
				if(is_numeric($tags[0])){$field_tag = 'term_id'; }
				else{ $field_tag = 'slug'; }
				if(count($tags)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($tags as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $tags,
							  )
					  );
				}
			}
			//cats
			if($taxonomy !='' && $cat!=''){
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($cats)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($cats as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $cats,
							  )
					  );
				}
			}
			if(isset($mult) && $mult!=''){
				$texo['relation'] = 'AND';
				$texo[] = 
					array(
						'taxonomy' => 'wpex_category',
						'field' => 'term_id',
						'terms' => $mult,
					);
			}
			if(isset($texo)){
				$args += array('tax_query' => $texo);
			}
		}
		if(isset($meta_value) && $meta_value!='' && $meta_key!=''){
			if(!empty($args['meta_query'])){
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = array(
				'key'  => $meta_key,
				'value' => $meta_value,
				'compare' => '='
			);
		}	
		if(isset($page) && $page!=''){
			$args['paged'] = $page;
		}
		return apply_filters( 'extp_query', $args );
	}
}

if(!function_exists('ex_teampress_social')){
	function ex_teampress_social($id){
		if(!is_numeric($id) || extp_get_option('extp_disable_social') =='yes'){
			return ;
		}
		echo "<ul class ='ex-social-account'>";
			$behance = get_post_meta( $id, 'extp_behance', true ); 
			if($behance != ''){
				echo "<li class='teampress-behance'><a href='".esc_url($behance)."'><i class='fab fa-behance'></i></a></li>";
			}
			$dribbble = get_post_meta( $id, 'extp_dribble', true ); 
			if($dribbble != ''){
				echo "<li class='teampress-dribbble'><a href='".esc_url($dribbble)."'><i class='fab fa-dribbble'></i></a></li>";
			}
			$facebook = get_post_meta( $id, 'extp_facebook', true ); 
			if($facebook != ''){
				echo "<li class='teampress-facebook'><a href='".esc_url($facebook)."'><i class='fab fa-facebook-f'></i></a></li>";
			}
			$flickr = get_post_meta( $id, 'extp_flickr', true ); 
			if($flickr != ''){
				echo "<li class='teampress-flickr'><a href='".esc_url($flickr)."'><i class='fab fa-flickr'></i></a></li>";
			}
			$github = get_post_meta( $id, 'extp_github', true ); 
			if($github != ''){
				echo "<li class='teampress-github'><a href='".esc_url($github)."'><i class='fab fa-github'></i></a></li>";
			}
			$google = get_post_meta( $id, 'extp_google', true );
			if($google != ''){
				echo "<li class='teampress-google'><a href='".esc_url($google)."'><i class='fab fa-google-plus-g'></i></a></li>";
			}
			$instagram = get_post_meta( $id, 'extp_instagram', true ); 
			if($instagram != ''){
				echo "<li class='teampress-instagram'><a href='".esc_url($instagram)."'><i class='fab fa-instagram'></i></a></li>";
			}
			$linkedIn = get_post_meta( $id, 'extp_linkedin', true ); 
			if($linkedIn != ''){
				echo "<li class='teampress-linkedin'><a href='".esc_url($linkedIn)."'><i class='fab fa-linkedin-in'></i></a></li>";
			}
			$pinterest = get_post_meta( $id, 'extp_pinterest', true ); 
			if($pinterest != ''){
				echo "<li class='teampress-pinterest'><a href='".esc_url($pinterest)."'><i class='fab fa-pinterest'></i></a></li>";
			}
			$tumblr = get_post_meta( $id, 'extp_tumblr', true ); 
			if($tumblr != ''){
				echo "<li class='teampress-tumblr'><a href='".esc_url($tumblr)."'><i class='fab fa-tumblr'></i></a></li>";
			}
			$twitter = get_post_meta( $id, 'extp_twitter', true ); 
			if($twitter != ''){
				echo "<li class='teampress-twitter'><a href='".esc_url($twitter)."'><i class='fab fa-twitter'></i></a></li>";
			}
			$youtube = get_post_meta( $id, 'extp_youtube', true ); 
			if($youtube != ''){
				echo "<li class='teampress-youtube'><a href='".esc_url($youtube)."'><i class='fab fa-youtube'></i></a></li>";
			}
			$email = get_post_meta( $id, 'extp_email', true ); 
			if($email != ''){
				echo "<li class='teampress-email'><a href='mailto:".sanitize_email($email)."'><i class='far fa-envelope'></i></a></li>";
			}
			
		echo "</ul>";
	}
}

if(!function_exists('ex_teampress_customlink')){
	function ex_teampress_customlink($id){
		if ( extp_get_option('extp_disable_single') =='yes' ) {
			return 'javascript:;';
		}
		return get_the_permalink($id);
	}
}


if(!function_exists('extp_ajax_navigate_html')){
	function extp_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids){
		$html = '
			<div class="ex-loadmore">
				<input type="hidden"  name="id_grid" value="'.$ID.'">
				<input type="hidden"  name="num_page" value="'.$num_pg.'">
				<input type="hidden"  name="num_page_uu" value="1">
				<input type="hidden"  name="current_page" value="1">
				<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
				<input type="hidden"  name="param_query" value="'.esc_html(str_replace('\/', '/', json_encode($args))).'">
				<input type="hidden"  name="param_ids" value="'.esc_html(str_replace('\/', '/', json_encode($arr_ids))).'">
				<input type="hidden" id="param_shortcode" name="param_shortcode" value="'.esc_html(str_replace('\/', '/', json_encode($atts))).'">';
				if($num_pg > 1){
					$html .='
					<a  href="javascript:void(0)" class="loadmore-exbt" data-id="'.$ID.'">
						<span class="load-text">'.esc_html__('Load more','teampress').'</span><span></span>&nbsp;<span></span>&nbsp;<span></span>
					</a>';
				}
				$html .='
		</div>';
		echo $html;
	}
}

add_action( 'wp_ajax_extp_loadmore', 'ajax_extp_loadmore' );
add_action( 'wp_ajax_nopriv_extp_loadmore', 'ajax_extp_loadmore' );
function ajax_extp_loadmore(){
	global $columns,$number_excerpt,$show_time,$orderby,$img_size,$ID;
	global $fullcontent_in,$ID,$number_excerpt;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'extp-'.rand(10,9999);
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
	}
	$column = intval(isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2');
	if($column < 2 || $column > 5){
		$column = 2;
	}
	$posttype   = sanitize_text_field(isset($atts['posttype']) && $atts['posttype']!='' ? $atts['posttype'] : 'ex_team');
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
	$number_excerpt =  intval(isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10');
	if($number_excerpt < 0){
		$number_excerpt = 10;
	}
	$page = sanitize_text_field($_POST['page']);
	$layout = sanitize_text_field(isset($_POST['layout']) ? $_POST['layout'] : '');
	$param_query = json_decode( stripslashes( sanitize_text_field($_POST['param_query']) ), true );
	$param_ids = '';
	if(isset($_POST['param_ids']) && sanitize_text_field($_POST['param_ids'])!=''){
		$param_ids =  json_decode( stripslashes( $_POST['param_ids'] ), true )!='' ? json_decode( stripslashes( sanitize_text_field($_POST['param_ids']) ), true ) : explode(",",sanitize_text_field($_POST['param_ids']));
	}
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	if($orderby =='rand' && is_array($param_ids)){
		$param_query['post__not_in'] = $param_ids;
		$param_query['paged'] = 1;
	}
	global $wpdb;
	$first_char = isset($_POST['char']) ? sanitize_text_field($_POST['char']) : '';
	if($first_char!=''){
		$postids = $wpdb->get_col($wpdb->prepare("
			SELECT      ID
			FROM        $wpdb->posts
			WHERE       post_type = 'ex_team' AND SUBSTR($wpdb->posts.post_title,1,1) = %s
			ORDER BY    $wpdb->posts.post_title",
			$first_char)
		);
		if(!empty($postids)){
			$param_query['post__in'] = $postids;
		}else{
			echo esc_html__('No matching records found','teampress');die;
		}
	}
	//echo '<pre>';
	//print_r($param_query);//exit;
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$i =0;
		$arr_ids = array();
		$html_modal = '';
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			$arr_ids[] = get_the_ID();
			if($layout=='table'){
				extp_template_plugin('table-'.$style,1);
			}else if($layout=='list'){
				echo '<div class="tpitem-list item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow" >
						<?php 
						extp_template_plugin('list-'.$style,1);
						?>
						<div class="exclearfix"></div>
					</div>
                </div>
				<?php
			}else{?>
                <div class="item-grid de-active" data-id="ex_id-<?php echo esc_attr($ID).'-'.get_the_ID();?>">
                	<div class="exp-arrow" >
		                <?php extp_template_plugin('grid-'.$style,1); ?>
		                <div class="exclearfix"></div>
					</div>
                </div>
				<?php
				
			}
			
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
		wp_reset_postdata();
		//echo esc_html(str_replace('\/', '/', json_encode($arr_ids)));exit;
		if($orderby =='rand' && is_array($param_ids)){
		?>
        <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#<?php  echo esc_html__($_POST['id_crsc']);?> input[name=param_ids]').val(<?php echo str_replace('\/', '/', json_encode(array_merge($param_ids,$arr_ids)));?>);
		});
        </script>
        <?php 
		}?>
        </div>
        <?php
	}
	$html = ob_get_clean();
	$output =  array('html_content'=>$html,'html_modal'=> $html_modal);
	echo str_replace('\/', '/', json_encode($output));
	die;
}

// alphab ajax

function extp_convert_color($color){
	if ($color == '') {
		return;
	}
	$hex  = str_replace("#", "", $color);
	if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
	}
	$rgb = $r.','. $g.','.$b;
	return $rgb;
}
function extp_custom_single_color($style){
	
}