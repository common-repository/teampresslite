<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $image = '';
?>
<figure class="tpstyle-9 tppost-<?php the_ID();?>">
  <div class="tpstyle-9-image">
    <a href="<?php echo $customlink; ?>">
      <?php the_post_thumbnail('full'); ?>
      <?php if($image!=''){?>
        <img class="second-img second-cus" src="<?php echo esc_url($image); ?>">
        <?php }
      ?>
      </a>
    <div class="tpstyle-9-position">
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo esc_html($position); ?></h5>
      <?php }?>
    </div>
  </div>
  <div class="tpstyle-9-content">
    <figcaption>
      <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
      <div class="tpstyle-9-meta">
        <?php $phone = get_post_meta( get_the_ID(), 'extp_phone', true ); 
        if($phone!=''){ ?>
          <h4><?php echo esc_html__('Phone: ','teampress'). esc_html($phone); ?></h4>
        <?php }?>
        <?php $email = get_post_meta( get_the_ID(), 'extp_email', true ); 
        if($email!=''){ ?>
          <h4><?php echo esc_html__('Email: ','teampress'). sanitize_email($email); ?></h4>
        <?php }?>
      </div>
      <?php 
	if($number_excerpt > 0){?>
	<p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
	<?php }?>

    </figcaption>
    <?php echo ex_teampress_social(get_the_ID());?>
  </div>
</figure>