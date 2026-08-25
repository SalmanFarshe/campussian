<?php

/**
 * Admission call-to-action banner.
 *
 * A bold, full-width teal band with the admission heading, supporting text and
 * a single primary button. All content is Customizer-driven.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

$heading = cmpsian_get_option('cmpsian_cta_heading');
$text    = cmpsian_get_option('cmpsian_cta_text');
$btn     = cmpsian_get_option('cmpsian_cta_btn');
$url     = cmpsian_get_option('cmpsian_cta_url');

if (! $heading && ! $text && ! $btn) {
	return;
}
?>
<section class="cmpsian-cta" id="admission" data-aos="fade-up">
	<div class="container">
		<div class="cmpsian-cta__inner">

			<div class="cmpsian-cta__content">
				<?php if ($heading) : ?>
					<h2 class="cmpsian-cta__heading"><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>
				<?php if ($text) : ?>
					<p class="cmpsian-cta__text"><?php echo esc_html($text); ?></p>
				<?php endif; ?>
			</div>

			<?php if ($btn) : ?>
				<div class="cmpsian-cta__action">
					<a class="cmpsian-btn cmpsian-btn--orange-cta cmpsian-btn--lg" href="<?php echo esc_url($url); ?>">
						<?php echo esc_html($btn); ?>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>