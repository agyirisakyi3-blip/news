<?php get_header(); ?>

<div class="container error-404">
	<h1>404</h1>
	<p>The page you're looking for doesn't exist.</p>
	<a href="<?php echo esc_url(home_url('/')); ?>" class="btn">Go Home</a>
</div>

<?php get_footer(); ?>
