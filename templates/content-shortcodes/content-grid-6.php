<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $image = '';
?>
<figure class="tpstyle-6 tppost-<?php the_ID();?>">
  <figcaption>
    <div class="tpstyle-6-image">
      <a href="<?php echo $customlink; ?>">
        <?php the_post_thumbnail('full', array(
        'class' => 'tpstyle-6-profile'
        )); ?>
        <?php if($image!=''){?>
        <img class="second-img tpstyle-6-profile" src="<?php echo esc_url($image); ?>">
        <?php }
      ?>
      </a>
    </div>
    <?php 
	if($number_excerpt > 0){?>
	<p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
	<?php }?>
    <?php echo ex_teampress_social(get_the_ID());?>
  </figcaption>
  <div class="tpstyle-6-info">
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo esc_html($position); ?></h5>
    <?php }?>
  </div>
</figure>