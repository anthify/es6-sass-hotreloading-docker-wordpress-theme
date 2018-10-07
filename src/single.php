<?php get_header(); ?>

	<main role="main" aria-label="Content">
		<!-- section -->
		<section>

		<?php if ( have_posts() ) : while (have_posts() ) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<!-- post thumbnail -->
				<?php if ( has_post_thumbnail() ) : // Check if Thumbnail exists. ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail(); // Fullsize image for the single post. ?>
					</a>
				<?php endif; ?>
				<!-- /post thumbnail -->

				<!-- post title -->
				<h1>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</h1>
				<!-- /post title -->


				<?php the_content(); // Dynamic Content. ?>

				<?php edit_post_link(); // Always handy to have Edit Post Links available. ?>

			</article>
			<!-- /article -->

		<?php endwhile; ?>

		<?php else : ?>

			<!-- article -->
			<article>

				<h1>Nothing to display</h1>

			</article>
			<!-- /article -->

		<?php endif; ?>

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
