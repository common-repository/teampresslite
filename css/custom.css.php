<?php
$extp_color = extp_get_option('extp_color');

$hex  = str_replace("#", "", $extp_color);
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
if($extp_color!=''){
	?>
    .ex-loadmore .loadmore-exbt span:not(.load-text),
    .ex-social-account li a:hover,
    .tpstyle-9 .ex-social-account,
    figure.tpstyle-img-9 h3,
    .ex-tplist .ex-hidden .ex-social-account li a:hover,
    .extp-mdbutton > div:hover,
    .exteam-lb .ex-social-account li a:hover,
    .ex-loadmore .loadmore-exbt:hover{background:<?php echo esc_attr($extp_color);?>;}
    .extp-member-single .member-info h3,
    .ex-loadmore .loadmore-exbt,
        .ex-tplist:not(.style-3):not(.style-7):not(.style-11):not(.style-17):not(.style-19):not(.style-20):not(.style-img-2):not(.style-img-3):not(.style-img-4):not(.style-img-5):not(.style-img-6):not(.style-img-7):not(.style-img-9):not(.style-img-10):not(.list-style-3) h3 a{ color:<?php echo esc_attr($extp_color);?>;}
    .ex-loadmore .loadmore-exbt,

    .tpstyle-img-4 h3 a,
    .ex-tplist .ex-hidden .ex-social-account li a:hover,
    .extp-mdbutton > div:hover,
    .exteam-lb .ex-social-account li a:hover{ border-color:<?php echo esc_attr($extp_color);?>}
    .tpstyle-9 .tpstyle-9-position{background:rgba(<?php echo esc_attr($rgb);?>,.7)}
    .extp-loadicon, .extp-loadicon::before, .extp-loadicon::after{  border-left-color:<?php echo esc_attr($extp_color);?>}
    <?php
}
$extp_font_family = extp_get_option('extp_font_family');

$wt_googlefont_js = extp_get_option('extp_disable_ggfont','extp_js_css_file_options');
if($wt_googlefont_js!='yes'){
    $main_font_family = explode(":", $extp_font_family);
    $main_font_family = '"'.$main_font_family[0].'", sans-serif';
}else{ $main_font_family = $extp_font_family;}
if($extp_font_family!=''){?>
    .ex-tplist,
    .extp-member-single .member-desc,
    .ex-tplist .exp-expand p,
    div#glightbox-body.exteam-lb,
    .exteam-lb{font-family: <?php echo $main_font_family;?>;}
    <?php
}
$extp_font_size = extp_get_option('extp_font_size');
if($extp_font_size!=''){?>
	.ex-table-1 p,
    .exteam-lb .gslide-description.description-right p,
    .extp-member-single .member-desc,
    .ex-tplist .exp-expand p,
    .ex-tplist{font-size: <?php echo esc_html($extp_font_size);?>;}
    <?php
}


$extp_custom_css = extp_get_option('extp_custom_css','extp_custom_code_options');
if($extp_custom_css!=''){
	echo $extp_custom_css;
}