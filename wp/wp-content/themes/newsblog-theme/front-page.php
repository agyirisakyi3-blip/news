<?php get_header(); ?>

<?php
$featured = newsblog_featured_posts(6);
$latest = new WP_Query(['posts_per_page' => 6, 'ignore_sticky_posts' => 1]);
?>

<?php if (!empty($featured)) : ?>
<section class="hero-section">
	<div class="container">
		<?php
		$hero_post = array_shift($featured);
		setup_postdata($hero_post);
		?>
		<div class="hero-layout">
			<article class="hero-featured">
				<a href="<?php the_permalink(); ?>">
					<?php if (has_post_thumbnail()) : ?>
						<div class="hero-thumb"><?php the_post_thumbnail('newsblog-hero'); ?></div>
					<?php endif; ?>
					<div class="hero-overlay">
						<span class="cat-badge" style="background:<?php echo newsblog_get_category_color(get_the_category()[0]->term_id ?? 1); ?>">
							<?php echo get_the_category()[0]->name ?? 'News'; ?>
						</span>
						<h2 class="hero-title"><?php the_title(); ?></h2>
						<div class="hero-meta">
							<span><?php echo get_the_date(); ?></span>
							<span>By <?php the_author(); ?></span>
						</div>
					</div>
				</a>
			</article>
			<div class="hero-sidebar">
				<h3 class="hero-sidebar-title">Highlights</h3>
				<?php foreach ($featured as $post) : setup_postdata($post); ?>
					<article class="hero-side-item">
						<a href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('newsblog-sidebar'); ?>
							<?php endif; ?>
							<div class="hero-side-content">
								<h4><?php the_title(); ?></h4>
								<span class="hero-date"><?php echo get_the_date('M j, Y'); ?></span>
							</div>
						</a>
					</article>
				<?php endforeach;
				wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="highlights-section">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Highlights</h2>
		</div>
		<div class="highlights-grid">
			<?php
			$highlights = new WP_Query(['posts_per_page' => 3, 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count', 'order' => 'DESC']);
			if ($highlights->have_posts()) :
				while ($highlights->have_posts()) : $highlights->the_post(); ?>
					<article class="highlight-card">
						<a href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<div class="highlight-thumb"><?php the_post_thumbnail('newsblog-card'); ?></div>
							<?php endif; ?>
							<div class="highlight-body">
								<?php
								$cats = get_the_category();
								if (!empty($cats)) : ?>
									<span class="cat-badge small" style="background:<?php echo newsblog_get_category_color($cats[0]->term_id); ?>"><?php echo $cats[0]->name; ?></span>
								<?php endif; ?>
								<h3><?php the_title(); ?></h3>
								<p><?php echo wp_trim_words(get_the_excerpt(), 12); ?></p>
								<span class="highlight-date"><?php echo get_the_date('M j, Y'); ?></span>
							</div>
						</a>
					</article>
				<?php endwhile;
				wp_reset_postdata();
			endif; ?>
		</div>
	</div>
</section>

<section class="videos-section">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Latest Videos</h2>
		</div>
		<?php
		$featured_video_url = get_theme_mod('featured_video_url', 'https://www.youtube.com/watch?v=bQNLeBfsX84');
		$sidebar_videos = [
			1 => ['url' => get_theme_mod('sidebar_video_1_url', 'https://www.youtube.com/watch?v=UKLMjScECLI'), 'title' => get_theme_mod('sidebar_video_1_title', 'Ukraine frontline: the high-tech drones defeating Russian air attacks')],
			2 => ['url' => get_theme_mod('sidebar_video_2_url', 'https://www.youtube.com/watch?v=ryTZimin9Ok'), 'title' => get_theme_mod('sidebar_video_2_title', 'US and Iranian negotiators reach deal to re-open Strait of Hormuz')],
			3 => ['url' => get_theme_mod('sidebar_video_3_url', 'https://www.youtube.com/watch?v=nV8ebWjoBPQ'), 'title' => get_theme_mod('sidebar_video_3_title', 'Shell used Nigerian pipeline for years despite pollution evidence')],
		];
		$featured_id = newsblog_get_youtube_id($featured_video_url);
		?>
		<div class="videos-grid">
			<div class="video-featured">
				<?php if ($featured_id) : ?>
					<div class="video-wrapper">
						<iframe src="https://www.youtube.com/embed/<?php echo esc_attr($featured_id); ?>" frameborder="0" allowfullscreen></iframe>
					</div>
				<?php endif; ?>
			</div>
			<div class="video-sidebar">
				<?php foreach ($sidebar_videos as $sv) :
					$sid = newsblog_get_youtube_id($sv['url']);
					if (!$sid) continue;
				?>
				<div class="video-thumb-item">
					<a href="<?php echo esc_url($sv['url']); ?>" target="_blank">
						<div class="video-thumb-img" style="background-image:url(https://img.youtube.com/vi/<?php echo $sid; ?>/mqdefault.jpg)">
							<div class="video-play-icon small">&#9654;</div>
						</div>
						<h4><?php echo esc_html($sv['title']); ?></h4>
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<section class="latest-news-section">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title">Latest News</h2>
			<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="view-all">View All</a>
		</div>
		<div class="news-index">
			<div class="news-index-main">
				<?php if ($latest->have_posts()) :
					$first = true;
					while ($latest->have_posts()) : $latest->the_post();
						if ($first) : $first = false; ?>
							<article class="news-featured-card">
								<a href="<?php the_permalink(); ?>">
									<?php if (has_post_thumbnail()) : ?>
										<div class="news-featured-thumb"><?php the_post_thumbnail('large'); ?></div>
									<?php endif; ?>
									<div class="news-featured-body">
										<?php
										$cats = get_the_category();
										if (!empty($cats)) : ?>
											<span class="cat-badge" style="background:<?php echo newsblog_get_category_color($cats[0]->term_id); ?>"><?php echo $cats[0]->name; ?></span>
										<?php endif; ?>
										<h3><?php the_title(); ?></h3>
										<p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
										<div class="news-meta">
											<span><?php echo get_the_date('M j, Y'); ?></span>
											<span><?php the_author(); ?></span>
										</div>
									</div>
								</a>
							</article>
						<?php else : ?>
							<article class="news-list-item">
								<a href="<?php the_permalink(); ?>">
									<?php if (has_post_thumbnail()) : ?>
										<?php the_post_thumbnail('newsblog-sidebar'); ?>
									<?php endif; ?>
									<div class="news-list-body">
										<?php
										$cats = get_the_category();
										if (!empty($cats)) : ?>
											<span class="cat-badge small" style="background:<?php echo newsblog_get_category_color($cats[0]->term_id); ?>"><?php echo $cats[0]->name; ?></span>
										<?php endif; ?>
										<h4><?php the_title(); ?></h4>
										<span class="news-list-date"><?php echo get_the_date('M j, Y'); ?></span>
									</div>
								</a>
							</article>
						<?php endif;
					endwhile;
					wp_reset_postdata();
				endif; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<section class="newsletter-section">
	<div class="container">
		<div class="newsletter-inner">
			<h2>Subscribe to Our Newsletter</h2>
			<p>Get the top stories delivered to your inbox every morning.</p>
			<form class="newsletter-form" method="post">
				<input type="email" name="newsblog_email" placeholder="Enter your email address" required>
				<button type="submit">Subscribe</button>
			</form>
		</div>
	</div>
</section>

<?php get_footer(); ?>
