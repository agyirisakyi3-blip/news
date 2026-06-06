<?php get_header(); ?>

<div class="container content-area">
	<div class="content-main">
		<?php if (have_posts()) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Latest News</h1>
			</header>
			<div class="posts-grid" id="posts-grid">
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('template-parts/article-card'); ?>
				<?php endwhile; ?>
			</div>
			<div class="load-more-wrap" data-query='<?php echo esc_js(json_encode($wp_query->query_vars)); ?>' data-page="1" data-max="<?php echo $wp_query->max_num_pages; ?>">
				<button class="load-more-btn" id="load-more-btn">Load More</button>
			</div>
		<?php else : ?>
			<p>No posts found.</p>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
