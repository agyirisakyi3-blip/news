<?php get_header(); ?>

<div class="container content-area page-content">
	<div class="content-main">
		<?php while (have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
