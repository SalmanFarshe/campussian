<?php
/**
 * The template for displaying comments.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Do not load for password-protected posts until the password is entered.
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="cmpsian-comments">

	<?php if ( have_comments() ) : ?>
		<h2 class="cmpsian-comments__title">
			<?php
			$cmpsian_count = get_comments_number();
			if ( '1' === (string) $cmpsian_count ) {
				esc_html_e( 'One comment', 'campussian' );
			} else {
				printf(
					/* translators: %s: comment count. */
					esc_html( _n( '%s comment', '%s comments', $cmpsian_count, 'campussian' ) ),
					esc_html( number_format_i18n( $cmpsian_count ) )
				);
			}
			?>
		</h2>

		<ol class="cmpsian-comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( '&larr; Older', 'campussian' ),
				'next_text' => esc_html__( 'Newer &rarr;', 'campussian' ),
			)
		);
		?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="cmpsian-comments__closed"><?php esc_html_e( 'Comments are closed.', 'campussian' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div>
