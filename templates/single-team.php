<?php 
get_header();?>
<div class="extp-member-single">
    <div class="extp-content-member">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php if(has_post_thumbnail()){?>
                <div class="member-img">
                	<div class="first-img">
						<?php the_post_thumbnail('full');?>
                    </div>
					<?php ex_teampress_social(get_the_ID());?>
                </div>
            <?php } ?>
            <div class="member-desc">
				<div class="member-info">
                    <h3><?php the_title(); ?></h3>
                    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
                    if($position!=''){ ?>
                        <div class="mb-meta">
                          <span><?php echo esc_html__('Position: ','teampress'); ?></span>
                          <?php echo esc_html($position); ?>
                        </div>
                    <?php }?>
                    <?php $phone = get_post_meta( get_the_ID(), 'extp_phone', true ); 
                    if($phone!=''){ ?>
                      <div class="mb-meta">
                          <span><?php echo esc_html__('Phone: ','teampress'); ?></span>
                          <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                      </div>
                    <?php }?>
                    <?php $email = get_post_meta( get_the_ID(), 'extp_email', true ); 
                    if($email!=''){ ?>
                      <div class="mb-meta">
                          <span> <?php echo esc_html__('Email: ','teampress'); ?></span>
                          <a href="mailto:<?php echo sanitize_email($email); ?>"><?php echo sanitize_email($email); ?></a>
                      </div>
                    <?php }?>
                </div>
				<?php the_content();?>
            </div>
			
		</div>
		<?php endwhile; 
		endif; ?>
    </div><!--end post-->

</div><!--end main-content-->

<?php 
get_footer(); ?>