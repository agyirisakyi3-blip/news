<?php

define('NEWSBLOG_VERSION', '1.4.0');

function newsblog_setup() {
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
	add_theme_support('custom-logo', [
		'height'      => 60,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	]);
	add_theme_support('align-wide');
	add_theme_support('responsive-embeds');

	set_post_thumbnail_size(400, 250, true);
	add_image_size('newsblog-hero', 800, 500, true);
	add_image_size('newsblog-card', 400, 250, true);
	add_image_size('newsblog-sidebar', 100, 100, true);

	register_nav_menus([
		'primary' => __('Primary Menu', 'newsblog'),
		'top-bar' => __('Top Bar Menu', 'newsblog'),
		'social'  => __('Social Links', 'newsblog'),
	]);
}
add_action('after_setup_theme', 'newsblog_setup');

function newsblog_widgets_init() {
	register_sidebar([
		'name'          => __('Sidebar', 'newsblog'),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);

	register_sidebar([
		'name'          => __('Footer Column 1', 'newsblog'),
		'id'            => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);

	register_sidebar([
		'name'          => __('Footer Column 2', 'newsblog'),
		'id'            => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);

	register_sidebar([
		'name'          => __('Footer Column 3', 'newsblog'),
		'id'            => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);

	register_sidebar([
		'name'          => __('Footer Column 4', 'newsblog'),
		'id'            => 'footer-4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	]);
}
add_action('widgets_init', 'newsblog_widgets_init');

function newsblog_enqueue_scripts() {
	wp_enqueue_style('newsblog-style', get_stylesheet_uri(), [], NEWSBLOG_VERSION);
	wp_enqueue_style('newsblog-main', get_template_directory_uri() . '/assets/css/main.css', ['newsblog-style'], NEWSBLOG_VERSION);
	wp_enqueue_script('newsblog-main', get_template_directory_uri() . '/assets/js/main.js', [], NEWSBLOG_VERSION, true);
	wp_localize_script('newsblog-main', 'newsblog_ajax', [
		'url'   => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('newsblog_nonce'),
	]);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'newsblog_enqueue_scripts');

function newsblog_custom_excerpt_length($length) {
	return 20;
}
add_filter('excerpt_length', 'newsblog_custom_excerpt_length');

function newsblog_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'newsblog_excerpt_more');

function newsblog_social_links() {
	$socials = [
		'facebook'  => ['label' => 'Facebook', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
		'twitter'   => ['label' => 'Twitter', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
		'instagram' => ['label' => 'Instagram', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>'],
		'youtube'   => ['label' => 'YouTube', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'],
		'whatsapp'  => ['label' => 'WhatsApp', 'icon' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>'],
	];
	$output = '';
	foreach ($socials as $key => $s) {
		$url = get_theme_mod("social_$key", '#');
		if ($url) {
			$output .= '<a href="' . esc_url($url) . '" class="social-link social-' . $key . '" target="_blank" rel="noopener" aria-label="' . esc_attr($s['label']) . '">';
			$output .= $s['icon'];
			$output .= '</a>';
		}
	}
	return $output;
}

function newsblog_breaking_news_posts($count = 5) {
	return get_posts([
		'posts_per_page' => $count,
		'meta_key'       => 'breaking_news',
		'meta_value'     => '1',
	]);
}

function newsblog_featured_posts($count = 5) {
	$sticky = get_option('sticky_posts');
	if (!empty($sticky)) {
		return get_posts([
			'posts_per_page' => $count,
			'post__in'       => $sticky,
			'ignore_sticky_posts' => 1,
		]);
	}
	return get_posts([
		'posts_per_page' => $count,
	]);
}

function newsblog_category_posts($category_id, $count = 4) {
	return get_posts([
		'posts_per_page' => $count,
		'cat'            => $category_id,
	]);
}

function newsblog_related_posts($post_id, $count = 4) {
	$categories = wp_get_post_categories($post_id);
	if (empty($categories)) {
		return [];
	}
	return get_posts([
		'posts_per_page' => $count,
		'category__in'   => $categories,
		'post__not_in'   => [$post_id],
	]);
}

function newsblog_get_category_color($category_id) {
	$saved = get_term_meta($category_id, 'newsblog_color', true);
	if (!empty($saved)) {
		return $saved;
	}
	$colors = [
		'#d32f2f',
		'#1976d2',
		'#388e3c',
		'#f57c00',
		'#7b1fa2',
		'#00796b',
		'#c2185b',
		'#546e7a',
	];
	$index = $category_id % count($colors);
	return $colors[$index];
}

function newsblog_body_classes($classes) {
	if (isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === '1') {
		$classes[] = 'dark-mode';
	}
	return $classes;
}
add_filter('body_class', 'newsblog_body_classes');

function newsblog_track_post_views() {
	if (is_single()) {
		$post_id = get_the_ID();
		$views = get_post_meta($post_id, '_newsblog_views', true);
		$views = $views ? (int)$views + 1 : 1;
		update_post_meta($post_id, '_newsblog_views', $views);
	}
}
add_action('wp_head', 'newsblog_track_post_views');

function newsblog_load_more() {
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 2;
	$query_vars = json_decode(stripslashes($_POST['query']), true);
	if (!is_array($query_vars)) {
		wp_die();
	}
	$query_vars['paged'] = $page;
	$query_vars['post_status'] = 'publish';

	$query = new WP_Query($query_vars);

	if ($query->have_posts()) :
		while ($query->have_posts()) : $query->the_post();
			get_template_part('template-parts/article-card');
		endwhile;
	endif;

	wp_die();
}
add_action('wp_ajax_newsblog_load_more', 'newsblog_load_more');
add_action('wp_ajax_nopriv_newsblog_load_more', 'newsblog_load_more');

function newsblog_handle_newsletter() {
	if (!isset($_POST['newsblog_email'])) {
		return;
	}
	$email = sanitize_email($_POST['newsblog_email']);
	if (!is_email($email)) {
		return;
	}
	$subscribers = get_option('newsblog_subscribers', []);
	if (!in_array($email, $subscribers)) {
		$subscribers[] = $email;
		update_option('newsblog_subscribers', $subscribers);
	}
	setcookie('newsblog_subscribed', '1', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
}
add_action('init', 'newsblog_handle_newsletter');

function newsblog_category_color_field($term = null) {
	$color = '';
	if ($term && is_object($term)) {
		$color = get_term_meta($term->term_id, 'newsblog_color', true);
	}
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	?>
	<tr class="form-field">
		<th scope="row"><label for="newsblog_color">Category Color</label></th>
		<td>
			<input name="newsblog_color" id="newsblog_color" type="text" value="<?php echo esc_attr($color); ?>" class="color-picker" data-default-color="#d32f2f" />
			<p class="description">Choose a color for this category. Used for badges and section headers.</p>
		</td>
	</tr>
	<script>jQuery(function($){$('.color-picker').wpColorPicker()})</script>
	<?php
}
add_action('category_add_form_fields', function() {
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	?>
	<div class="form-field">
		<label for="newsblog_color">Category Color</label>
		<input name="newsblog_color" id="newsblog_color" type="text" value="#d32f2f" class="color-picker" data-default-color="#d32f2f" />
		<p>Choose a color for this category. Used for badges and section headers.</p>
	</div>
	<script>jQuery(function($){$('.color-picker').wpColorPicker()})</script>
	<?php
});
add_action('category_edit_form_fields', 'newsblog_category_color_field', 10, 1);

function newsblog_save_category_color($term_id) {
	if (isset($_POST['newsblog_color'])) {
		$color = sanitize_hex_color($_POST['newsblog_color']);
		if (!empty($color)) {
			update_term_meta($term_id, 'newsblog_color', $color);
		} else {
			delete_term_meta($term_id, 'newsblog_color');
		}
	}
}
add_action('created_category', 'newsblog_save_category_color');
add_action('edited_category', 'newsblog_save_category_color');

require_once get_template_directory() . '/inc/widget-trending-posts.php';
require_once get_template_directory() . '/inc/seo.php';

function newsblog_get_youtube_id($url) {
	if (!$url) return '';
	$parts = parse_url($url);
	if (isset($parts['query'])) {
		parse_str($parts['query'], $qs);
		if (!empty($qs['v'])) return $qs['v'];
	}
	if (preg_match('~youtu\.be/([a-zA-Z0-9_-]+)~', $url, $m)) return $m[1];
	if (preg_match('~embed/([a-zA-Z0-9_-]+)~', $url, $m)) return $m[1];
	return '';
}

function newsblog_customize_register($wp_customize) {
	$wp_customize->add_section('newsblog_social', [
		'title'    => 'Social Links',
		'priority' => 30,
	]);
	$socials = ['facebook', 'twitter', 'instagram', 'youtube', 'whatsapp'];
	foreach ($socials as $s) {
		$wp_customize->add_setting("social_$s", [
			'sanitize_callback' => 'esc_url_raw',
		]);
		$wp_customize->add_control("social_$s", [
			'label'   => ucfirst($s) . ' URL',
			'section' => 'newsblog_social',
			'type'    => 'url',
		]);
	}

	$wp_customize->add_section('newsblog_videos', [
		'title'    => 'Video Section',
		'priority' => 35,
	]);
	$wp_customize->add_setting('featured_video_url', [
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control('featured_video_url', [
		'label'       => 'Featured Video URL',
		'description' => 'YouTube URL for the main featured video',
		'section'     => 'newsblog_videos',
		'type'        => 'url',
	]);
	for ($i = 1; $i <= 3; $i++) {
		$wp_customize->add_setting("sidebar_video_{$i}_url", [
			'sanitize_callback' => 'esc_url_raw',
		]);
		$wp_customize->add_control("sidebar_video_{$i}_url", [
			'label'       => "Sidebar Video $i URL",
			'section'     => 'newsblog_videos',
			'type'        => 'url',
		]);
		$wp_customize->add_setting("sidebar_video_{$i}_title", [
			'sanitize_callback' => 'sanitize_text_field',
		]);
		$wp_customize->add_control("sidebar_video_{$i}_title", [
			'label'       => "Sidebar Video $i Title",
			'section'     => 'newsblog_videos',
			'type'        => 'text',
		]);
	}
}
add_action('customize_register', 'newsblog_customize_register');

function newsblog_robots_txt($output, $public) {
	if (!$public) return $output;
	$site_url = home_url('/');
	return "User-agent: *\nAllow: /\n\nSitemap: {$site_url}wp-sitemap.xml\n";
}
add_filter('robots_txt', 'newsblog_robots_txt', 10, 2);
