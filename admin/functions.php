<?php
include 'class-teampress-postype.php';
include 'shortcode-builder.php';
add_action( 'admin_enqueue_scripts', 'extp_admin_scripts' );
function extp_admin_scripts(){
	$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'jquery', 'extp_ajax', $js_params  );
	wp_enqueue_style('extp-admin_style', TEAMPRESS_PATH . 'admin/css/style.css','','1.0');
	wp_enqueue_script('extp-admin-js', TEAMPRESS_PATH . 'admin/js/admin.js', array( 'jquery' ),'1.0' );
}

add_filter( 'manage_ex_team_posts_columns', 'extp_edit_columns',99 );
function extp_edit_columns( $columns ) {
	global $wpdb;
	unset($columns['date']);
	$columns['extp_id'] = esc_html__( 'ID' , 'teampress' );
	$columns['extp_position'] = esc_html__( 'Posistion' , 'teampress' );
	$columns['extp_order'] = esc_html__( 'Order' , 'teampress' );
	$columns['extp_color'] = esc_html__( 'Color' , 'teampress' );
	$columns['date'] = esc_html__( 'Publish date' , 'teampress' );		
	return $columns;
}
add_action( 'manage_ex_team_posts_custom_column', 'ex_team_custom_columns',12);
function ex_team_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'extp_id':
			$extp_id = $post->ID;
			echo '<span class="extp_id">'.$extp_id.'</span>';
			break;
		case 'extp_position':
			$extp_position = get_post_meta($post->ID, 'extp_position', true);
			echo '<input type="text" style="max-width:100%" data-id="' . $post->ID . '" name="extp_position" value="'.esc_attr($extp_position).'">';
			break;	
		case 'extp_order':
			$extp_order = get_post_meta($post->ID, 'extp_order', true);
			echo '<input type="number" style="max-width:60px" data-id="' . $post->ID . '" name="extp_sort" value="'.esc_attr($extp_order).'">';
			break;
		case 'extp_color':
			$extp_color = get_post_meta($post->ID, 'extp_color', true);
			echo '<span style=" background-color:'.esc_attr($extp_color).'; width: 15px;
    height: 15px; border-radius: 50%; display: inline-block;"></span>';
			break;	
	}
}


add_filter( 'manage_team_scbd_posts_columns', 'extp_edit_scbd_columns',99 );
function extp_edit_scbd_columns( $columns ) {
	global $wpdb;
	unset($columns['date']);
	$columns['layout'] = esc_html__( 'Type' , 'teampress' );
	$columns['shortcode'] = esc_html__( 'Shortcode' , 'teampress' );
	$columns['date'] = esc_html__( 'Publish date' , 'teampress' );		
	return $columns;
}
add_action( 'manage_team_scbd_posts_custom_column', 'extp_scbd_custom_columns',12);
function extp_scbd_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'layout':
			$sc_type = get_post_meta($post->ID, 'sc_type', true);
			$extp_id = $post->ID;
			echo '<span class="layout">'.$sc_type.'</span>';
			break;
		case 'shortcode':
			$_shortcode = get_post_meta($post->ID, '_shortcode', true);
			echo '<input type="text" style="max-width:100%" readonly name="_shortcode" value="'.esc_attr($_shortcode).'">';
			break;	
	}
}

add_action( 'wp_ajax_extp_change_sort_mb', 'extp_change_sort' );
function extp_change_sort(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'extp_order', esc_attr(str_replace(' ', '', $value)));
	}
	die;
}
add_action('wp_ajax_extp_change_position', 'extp_change_position' );
function extp_change_position(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'extp_position', esc_attr($value));
	}
	die;
}
function extp_id_taxonomy_columns( $columns ){
	$columns['cat_id'] = esc_html__('ID','teampress');

	return $columns;
}
add_filter('manage_edit-extp_cat_columns' , 'extp_id_taxonomy_columns');
function extp_taxonomy_columns_content( $content, $column_name, $term_id ){
    if ( 'cat_id' == $column_name ) {
        $content = $term_id;
    }
	return $content;
}
add_filter( 'manage_extp_cat_custom_column', 'extp_taxonomy_columns_content', 10, 3 );
add_action('admin_menu', function(){
	add_submenu_page(
		'edit.php?post_type=ex_team',
		'Team Helps',
		'Help',
		'manage_options',
		'extp_helps',
		'extp_create_help'

	);
});
function extp_create_help(){
    include( 'extp-help.php' );	
}