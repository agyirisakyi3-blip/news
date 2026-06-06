	</main>

	<footer id="colophon" class="site-footer">
		<div class="footer-newsletter">
			<div class="container">
				<div class="newsletter-wrap">
					<div class="newsletter-text">
						<h3>Stay Informed</h3>
						<p>Get the latest news delivered to your inbox.</p>
					</div>
					<form class="newsletter-form" method="post">
						<input type="email" name="newsblog_email" placeholder="Enter your email" required>
						<button type="submit">Subscribe</button>
					</form>
				</div>
			</div>
		</div>
		<div class="footer-main">
			<div class="container footer-grid">
				<div class="footer-col footer-about">
					<h4 class="footer-title">About Us</h4>
					<p><?php bloginfo('description'); ?></p>
					<div class="footer-social">
						<?php echo newsblog_social_links(); ?>
					</div>
				</div>
				<div class="footer-col">
					<?php if (is_active_sidebar('footer-1')) : ?>
						<?php dynamic_sidebar('footer-1'); ?>
					<?php else : ?>
						<h4 class="footer-title">Categories</h4>
						<ul><?php wp_list_categories(['title_li' => '', 'depth' => 1]); ?></ul>
					<?php endif; ?>
				</div>
				<div class="footer-col">
					<?php dynamic_sidebar('footer-2'); ?>
				</div>
				<div class="footer-col">
					<?php dynamic_sidebar('footer-3'); ?>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
