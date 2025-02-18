<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $image = '';
?>
<figure class="tpstyle-1 tppost-<?php the_ID();?>">
  <div class="tpstyle-1-image">
    <a href="<?php echo $customlink; ?>">
      <div class="image-bg-circle" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)">
        <?php if($image!=''){?>
          <div class="image-bg-circle second-img" style="background-image: url(<?php echo esc_url($image); ?>)"></div>
          <?php }
        ?>
      </div>

    </a>
  </div><figcaption>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo esc_html($position); ?></h5>
      <?php }?>
    <?php 
	if($number_excerpt > 0){?>
	<p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
	<?php }?>
    <?php echo ex_teampress_social(get_the_ID());?>
  </figcaption>
</figure>