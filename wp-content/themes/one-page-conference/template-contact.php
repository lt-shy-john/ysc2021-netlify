<?php // Template Name: Contact ?>
<?php get_header(); ?>

<div class="contact-block">
	<div class="container">
		<div class="contact-holder">
			<div class="contact-info">
				<h3 class="main-title">Contact Info</h3>
				<div class="c-info">
					<div class="icon">
						<span class="icon-location" aria-hidden="true"></span>
					</div>
					<div class="text">
						<span class="title">Address</span>
						<span>Mirpur New Bazar Road, Block-c, Uttara, Dhaka-1210</span>
					</div>
				</div>
				<div class="c-info">
					<div class="icon">
						<span class="icon-phone" aria-hidden="true"></span>
					</div>
					<div class="text">
						<span class="title">Phone</span>
						<a href="tel:(+123)6780989">(+123)678 0989</a>
					</div>
				</div>
				<div class="c-info">
					<div class="icon">
						<span class="icon-mail-alt" aria-hidden="true"></span>
					</div>
					<div class="text">
						<span class="title">Email</span>
						<a href="mailto:info.demo@demo.com">info.demo@demo.com</a>
					</div>
				</div>
			</div>
			<div class="contact-form-holder">
				<h3 class="main-title">Get In Touch</h3>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>