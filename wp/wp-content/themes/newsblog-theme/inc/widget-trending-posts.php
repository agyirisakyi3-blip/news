<?php

class NewsBlog_Trending_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'newsblog_trending',
			'NewsBlog Trending Posts',
			['description' => 'Shows the most viewed posts.']
		);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];
		$title = !empty($instance['title']) ? $instance['title'] : 'Trending Now';
		echo $args['before_title'] . esc_html($title) . $args['after_title'];

		$count = !empty($instance['count']) ? (int)$instance['count'] : 5;
		$days = !empty($instance['days']) ? (int)$instance['days'] : 7;

		$query = new WP_Query([
			'posts_per_page' => $count,
			'meta_key'       => '_newsblog_views',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'date_query'     => [
				['after' => "$days days ago"],
			],
		]);

		if ($query->have_posts()) : ?>
			<ul class="trending-posts-list">
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<span class="trending-thumb"><?php the_post_thumbnail('newsblog-sidebar'); ?></span>
							<?php endif; ?>
							<span class="trending-info">
								<span class="trending-title"><?php the_title(); ?></span>
								<span class="trending-date"><?php echo get_the_date('M j, Y'); ?></span>
							</span>
						</a>
					</li>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</ul>
		<?php else : ?>
			<p>No trending posts yet.</p>
		<?php endif;

		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : 'Trending Now';
		$count = !empty($instance['count']) ? (int)$instance['count'] : 5;
		$days = !empty($instance['days']) ? (int)$instance['days'] : 7;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>">Number of posts:</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo esc_attr($count); ?>" min="1" max="20">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('days'); ?>">Trending period (days):</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id('days'); ?>" name="<?php echo $this->get_field_name('days'); ?>" type="number" value="<?php echo esc_attr($days); ?>" min="1" max="365">
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = [];
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['count'] = min(20, max(1, (int)$new_instance['count']));
		$instance['days'] = min(365, max(1, (int)$new_instance['days']));
		return $instance;
	}
}

function newsblog_register_trending_widget() {
	register_widget('NewsBlog_Trending_Posts_Widget');
}
add_action('widgets_init', 'newsblog_register_trending_widget');
