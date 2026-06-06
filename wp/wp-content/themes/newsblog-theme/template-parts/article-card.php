<article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
	<a href="<?php the_permalink(); ?>">
		<?php if (has_post_thumbnail()) : ?>
			<div class="card-thumb"><?php the_post_thumbnail('newsblog-card'); ?></div>
		<?php endif; ?>
		<div class="card-body">
			<?php
			$categories = get_the_category();
			if (!empty($categories)) : ?>
				<span class="cat-badge small" style="background:<?php echo newsblog_get_category_color($categories[0]->term_id); ?>">
					<?php echo $categories[0]->name; ?>
				</span>
			<?php endif; ?>
			<h3 class="card-title"><?php the_title(); ?></h3>
			<p class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
			<div class="card-meta">
				<span class="card-date"><?php echo get_the_date('M j, Y'); ?></span>
				<span class="card-author"><?php the_author(); ?></span>
			</div>
		</div>
	</a>
</article>
