<?php
get_header();
?>
<?php get_sidebar(); ?>

<div id="main-content">
	<?php while ( have_posts() ) : the_post();?>
		<figure class="snip1244">
			<?php the_post_thumbnail() ?>
			<figcaption>
		    <h3><?php the_title() ?></h3>
		    <h4><?php echo get_post_meta( get_the_ID(), 'extp_position', true ); ?></h4>
		    <p><?php the_excerpt();?></p>
			</figcaption>
			
		</figure>
	<?php endwhile; ?>
</div><!--end main-content-->
<?php get_sidebar('second'); ?>
<?php get_footer(); ?>