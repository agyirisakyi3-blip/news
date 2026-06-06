<section class="related-posts">
	<h3 class="section-title">Related Articles</h3>
	<div class="related-grid">
		<?php
		$related = newsblog_related_posts(get_the_ID());
		if (!empty($related)) :
			foreach ($related as $post) : setup_postdata($post); ?>
				<article class="article-card">
					<a href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<div class="card-thumb"><?php the_post_thumbnail('newsblog-card'); ?></div>
						<?php endif; ?>
						<div class="card-body">
							<h4 class="card-title"><?php the_title(); ?></h4>
							<span class="card-date"><?php echo get_the_date('M j, Y'); ?></span>
						</div>
					</a>
				</article>
			<?php endforeach;
			wp_reset_postdata();
		endif; ?>
	</div>
</section>
