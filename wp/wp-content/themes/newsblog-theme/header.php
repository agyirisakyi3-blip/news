<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<div class="top-bar">
		<div class="container top-bar-inner">
			<span class="top-date"><?php echo date_i18n('l, F j, Y'); ?></span>
			<div class="top-bar-right">
				<div class="top-social">
					<?php echo newsblog_social_links(); ?>
				</div>
				<div class="top-links">
					<?php wp_nav_menu([
						'theme_location' => 'top-bar',
						'menu_id'        => 'top-bar-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					]); ?>
				</div>
			</div>
		</div>
	</div>

	<header id="masthead" class="site-header">
		<div class="nav-bar">
			<div class="container nav-bar-inner">
				<div class="site-branding">
					<?php if (has_custom_logo()) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
					<?php endif; ?>
				</div>

				<button class="menu-toggle" id="menu-toggle" aria-label="Menu">
					<span></span><span></span><span></span>
				</button>

				<nav id="site-navigation" class="main-navigation">
					<?php wp_nav_menu([
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => false,
					]); ?>
				</nav>

				<div class="nav-actions">
					<button class="dark-mode-toggle" id="dark-mode-toggle" aria-label="Toggle dark mode">
						<svg class="sun-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
						<svg class="moon-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
					</button>
					<button class="search-toggle" id="search-toggle" aria-label="Search">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
					</button>
				</div>
			</div>
		</div>

		<div class="search-bar" id="search-bar" style="display:none;">
			<div class="container">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
					<input type="search" placeholder="Search news..." value="<?php echo get_search_query(); ?>" name="s" />
					<button type="submit">Search</button>
				</form>
			</div>
		</div>
	</header>

	<div class="breaking-ticker" id="breaking-ticker">
		<div class="container breaking-ticker-inner">
			<span class="breaking-label">Breaking News</span>
			<div class="breaking-ticker-wrapper">
				<div class="breaking-ticker-track" id="ticker-track">
					<?php
					$breaking = newsblog_breaking_news_posts();
					if (!empty($breaking)) :
						foreach ($breaking as $post) : setup_postdata($post); ?>
							<a href="<?php the_permalink(); ?>" class="ticker-item"><?php the_title(); ?></a>
						<?php endforeach;
						wp_reset_postdata();
					else : ?>
						<span class="ticker-item">Stay tuned for the latest news updates</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<main id="primary" class="site-main">
