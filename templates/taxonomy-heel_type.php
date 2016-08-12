<?php
/*
 Template Name: Full Width Page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="main" class="m-all t-all d-all cf hentry" role="main">
                        
                        <header class="article-header">

									<h1 class="page-title"><?php echo get_queried_object()->name; ?></h1>

													

								</header>

			<?php $args = array(
				'orderby'   => 'menu_order',
				'order'		=> 'ASC',
				'post_status' => 'publish',
				'tax_query' => array(
					array(
						'taxonomy' => get_queried_object()->taxonomy,
						'field'    => 'slug',
						'terms'    => get_queried_object()->slug,
					),
				),
			);

			$heel_query = new WP_Query( $args );?>



							<?php if ($heel_query->have_posts()) : ?>
							
							<?php while ($heel_query->have_posts()) : $heel_query->the_post(); ?>
                           
							 <div id="heel-types">
							
                
                    <?php the_post_thumbnail('full'); ?>
               
                
                    <?php the_title( '<p class="heel-text"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></p>' ); ?>
                
              
           					 </div>
				<?php
				

							 endwhile; ?>
							<div class="clear-fix"></div>
							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'yooplugtheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'yooplugtheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page-custom.php template.', 'yooplugtheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div>


				</div>

			</div>


<?php get_footer(); ?>
