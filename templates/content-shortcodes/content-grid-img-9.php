<?php
  $customlink = ex_teampress_customlink(get_the_ID());
?> 
<figure class="tpstyle-img-9 tppost-<?php the_ID();?>">
  <a href="<?php echo $customlink; ?>"><?php the_post_thumbnail(); ?></a>
  <figcaption>
    <div>
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
        if($position!=''){ ?>
          <h5><?php echo esc_html($position); ?></h5>
      <?php }?>
    </div>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
  </figcaption>
</figure>