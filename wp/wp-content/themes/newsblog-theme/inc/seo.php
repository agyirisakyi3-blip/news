<?php

define('NEWSBLOG_SEO_VERSION', '1.0');

function newsblog_seo_meta_tags() {
	if (is_admin()) return;

	echo "<!-- NewsBlog SEO v" . NEWSBLOG_SEO_VERSION . " -->\n";

	$title = wp_get_document_title();
	$desc = '';
	$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$image = '';
	$type = 'website';

	if (is_singular()) {
		$type = 'article';
		$post = get_queried_object();
		$desc = get_post_meta($post->ID, '_newsblog_meta_desc', true);
		if (!$desc) {
			$desc = wp_trim_words(wp_strip_all_tags($post->post_excerpt ?: $post->post_content), 30);
		}
		if (has_post_thumbnail($post->ID)) {
			$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
			if ($thumb) $image = $thumb[0];
		}
	} elseif (is_category() || is_tag() || is_tax()) {
		$term = get_queried_object();
		$desc = term_description($term) ?: '';
		$image = '';
	} elseif (is_home() || is_front_page()) {
		$desc = get_bloginfo('description');
		$type = 'website';
		$image = get_custom_logo() ? wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0] ?? '' : '';
	} elseif (is_search()) {
		$desc = 'Search results for: ' . get_search_query();
	}

	echo "<meta name=\"description\" content=\"" . esc_attr(wp_trim_words($desc, 30, '')) . "\">\n";
	echo "<link rel=\"canonical\" href=\"" . esc_url($url) . "\">\n";

	echo "<meta property=\"og:title\" content=\"" . esc_attr($title) . "\">\n";
	echo "<meta property=\"og:description\" content=\"" . esc_attr(wp_trim_words($desc, 30, '')) . "\">\n";
	echo "<meta property=\"og:url\" content=\"" . esc_url($url) . "\">\n";
	echo "<meta property=\"og:type\" content=\"" . esc_attr($type) . "\">\n";
	echo "<meta property=\"og:site_name\" content=\"" . esc_attr(get_bloginfo('name')) . "\">\n";
	echo "<meta property=\"og:locale\" content=\"" . esc_attr(get_locale()) . "\">\n";
	if ($image) {
		echo "<meta property=\"og:image\" content=\"" . esc_url($image) . "\">\n";
		echo "<meta property=\"og:image:width\" content=\"1200\">\n";
		echo "<meta property=\"og:image:height\" content=\"630\">\n";
	}

	echo "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
	echo "<meta name=\"twitter:title\" content=\"" . esc_attr($title) . "\">\n";
	echo "<meta name=\"twitter:description\" content=\"" . esc_attr(wp_trim_words($desc, 30, '')) . "\">\n";
	if ($image) {
		echo "<meta name=\"twitter:image\" content=\"" . esc_url($image) . "\">\n";
	}

	if (is_singular('post')) {
		$post = get_queried_object();
		echo "<meta property=\"article:published_time\" content=\"" . esc_attr(get_the_date('c', $post)) . "\">\n";
		echo "<meta property=\"article:modified_time\" content=\"" . esc_attr(get_the_modified_date('c', $post)) . "\">\n";
		$cats = get_the_category($post->ID);
		foreach ($cats as $cat) {
			echo "<meta property=\"article:section\" content=\"" . esc_attr($cat->name) . "\">\n";
		}
	}

	if (is_paged()) {
		$paged = get_query_var('paged') ?: 1;
		if ($paged > 1) {
			echo "<link rel=\"prev\" href=\"" . esc_url(get_pagenum_link($paged - 1)) . "\">\n";
		}
		echo "<link rel=\"next\" href=\"" . esc_url(get_pagenum_link($paged + 1)) . "\">\n";
	}
}
add_action('wp_head', 'newsblog_seo_meta_tags', 1);

function newsblog_json_ld_schema() {
	if (is_admin()) return;

	$site_name = get_bloginfo('name');
	$site_url = home_url('/');
	$logo = '';
	if (has_custom_logo()) {
		$logo_id = get_theme_mod('custom_logo');
		if ($logo_id) {
			$logo_data = wp_get_attachment_image_src($logo_id, 'full');
			if ($logo_data) $logo = $logo_data[0];
		}
	}

	$org = [
		'@context' => 'https://schema.org',
		'@type' => 'Organization',
		'name' => $site_name,
		'url' => $site_url,
	];
	if ($logo) $org['logo'] = $logo;

	if (is_singular('post')) {
		$post = get_queried_object();
		$author_name = get_the_author_meta('display_name', $post->post_author);
		$image_data = has_post_thumbnail($post->ID)
			? wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')
			: null;

		$article = [
			'@context' => 'https://schema.org',
			'@type' => 'NewsArticle',
			'headline' => get_the_title($post),
			'description' => wp_trim_words(wp_strip_all_tags($post->post_excerpt ?: $post->post_content), 30, ''),
			'datePublished' => get_the_date('c', $post),
			'dateModified' => get_the_modified_date('c', $post),
			'author' => [
				'@type' => 'Person',
				'name' => $author_name,
			],
			'publisher' => $org,
			'mainEntityOfPage' => get_permalink($post),
		];
		if ($image_data) {
			$article['image'] = [
				'@type' => 'ImageObject',
				'url' => $image_data[0],
				'width' => $image_data[1],
				'height' => $image_data[2],
			];
		}
		echo '<script type="application/ld+json">' . wp_json_encode($article, JSON_UNESCAPED_SLASHES) . "</script>\n";
	} elseif (is_home() || is_front_page()) {
		echo '<script type="application/ld+json">' . wp_json_encode($org, JSON_UNESCAPED_SLASHES) . "</script>\n";

		echo '<script type="application/ld+json">' . wp_json_encode([
			'@context' => 'https://schema.org',
			'@type' => 'WebSite',
			'name' => $site_name,
			'url' => $site_url,
			'potentialAction' => [
				'@type' => 'SearchAction',
				'target' => $site_url . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		], JSON_UNESCAPED_SLASHES) . "</script>\n";
	}
}
add_action('wp_head', 'newsblog_json_ld_schema', 2);

function newsblog_breadcrumbs() {
	if (is_front_page()) return;

	$html = '<nav class="breadcrumbs" aria-label="Breadcrumb">';
	$html .= '<span><a href="' . home_url('/') . '">Home</a></span>';

	if (is_category()) {
		$cat = get_queried_object();
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . $cat->name . '</span>';
	} elseif (is_tag()) {
		$tag = get_queried_object();
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . $tag->name . '</span>';
	} elseif (is_search()) {
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">Search: ' . get_search_query() . '</span>';
	} elseif (is_singular('post')) {
		$cats = get_the_category();
		if (!empty($cats)) {
			$html .= ' <span class="breadcrumb-sep">/</span> <a href="' . get_category_link($cats[0]->term_id) . '">' . $cats[0]->name . '</a>';
		}
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . get_the_title() . '</span>';
	} elseif (is_page()) {
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . get_the_title() . '</span>';
	} elseif (is_404()) {
		$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">404 Not Found</span>';
	} elseif (is_archive()) {
		if (is_day()) {
			$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . get_the_date('F j, Y') . '</span>';
		} elseif (is_month()) {
			$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . get_the_date('F Y') . '</span>';
		} elseif (is_year()) {
			$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">' . get_the_date('Y') . '</span>';
		} else {
			$html .= ' <span class="breadcrumb-sep">/</span> <span class="breadcrumb-current">Archives</span>';
		}
	}

	$html .= '</nav>';

	$breadcrumb_data = [
		'@context' => 'https://schema.org',
		'@type' => 'BreadcrumbList',
		'itemListElement' => [],
	];

	$items = [];
	$items[] = ['position' => 1, 'name' => 'Home', 'item' => home_url('/')];
	$i = 2;
	if (is_category()) {
		$items[] = ['position' => $i++, 'name' => $cat->name, 'item' => get_category_link($cat)];
	} elseif (is_singular('post')) {
		if (!empty($cats)) {
			$items[] = ['position' => $i++, 'name' => $cats[0]->name, 'item' => get_category_link($cats[0])];
		}
		$items[] = ['position' => $i++, 'name' => get_the_title(), 'item' => get_permalink()];
	}

	foreach ($items as $item) {
		$breadcrumb_data['itemListElement'][] = [
			'@type' => 'ListItem',
			'position' => $item['position'],
			'name' => $item['name'],
			'item' => $item['item'],
		];
	}

	if (count($items) > 1) {
		$html .= '<script type="application/ld+json">' . wp_json_encode($breadcrumb_data, JSON_UNESCAPED_SLASHES) . "</script>\n";
	}

	return $html;
}
