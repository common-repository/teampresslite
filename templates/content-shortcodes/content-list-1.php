<?php global $ID,$number_excerpt; 
  $customlink = ex_teampress_customlink(get_the_ID());
?>
<figure class="tpstyle-list-1 tppost-<?php the_ID();?>">
  <div class="tplist-1-info">
    <div class="tplist-1-image">
    <?php $customlink = ex_teampress_customlink(get_the_ID()); ?>
    <a href="<?php echo esc_url($customlink); ?>"><div class="image-bg-circle" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)"></div></a>
    </div><figcaption>
      <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
        if($position!=''){ ?>
          <h5><?php echo esc_html($position); ?></h5>
      <?php }?>
      <?php 
	  if($number_excerpt > 0){?>
      <p><?php echo wp_trim_words(get_the_excerpt(),50,'...'); ?></p>
      <?php }?>
      <?php echo ex_teampress_social(get_the_ID());?>
    </figcaption>
  </div>
</figure>