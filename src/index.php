<?php get_header(); ?>

  <main role="main" aria-label="Content">
    <!-- section -->
      <section id="content" role="main">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'entry' ); ?>
        <?php endwhile; endif; ?>
        <?php get_template_part( 'nav', 'below' ); ?>
      </section>
    <!-- /section -->
  </main>

<?php get_footer(); ?>