<?php
/**
 * Template tags.
 *
 * Reusable presentational helper functions shared across templates. All output
 * is escaped at the point of print.
 *
 * @package Campussian
 * @author  WhyCodeBD
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'cmpsian_site_logo' ) ) {
	/**
	 * Output the site logo.
	 *
	 * Preference order: Customizer "School Logo" -> core custom_logo -> site title text.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function cmpsian_site_logo() {
		$logo = cmpsian_get_option( 'cmpsian_logo' );

		if ( $logo ) {
			printf(
				'<a class="cmpsian-logo" href="%1$s" rel="home"><img src="%2$s" alt="%3$s" /></a>',
				esc_url( home_url( '/' ) ),
				esc_url( $logo ),
				esc_attr( get_bloginfo( 'name' ) )
			);
			return;
		}

		if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
			return;
		}

		printf(
			'<a class="cmpsian-logo cmpsian-logo--text" href="%1$s" rel="home">%2$s</a>',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
	}
}

if ( ! function_exists( 'cmpsian_get_social_icon' ) ) {
	/**
	 * Return an inline SVG icon for a known social network.
	 *
	 * @since 1.0.0
	 * @param string $name Icon key.
	 * @return string SVG markup (safe, internal source).
	 */
	function cmpsian_get_social_icon( $name ) {
		$icons = array(
			'facebook' => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.3-1.5 1.6-1.5h1.6V3.6c-.8-.1-1.6-.2-2.4-.2-2.4 0-4 1.5-4 4.1v2.3H7.5V13h2.8v8h3.2z"/></svg>',
			'youtube'  => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M23 7.5s-.2-1.6-.9-2.3c-.8-.9-1.8-.9-2.2-1C16.9 4 12 4 12 4h0s-4.9 0-7.9.2c-.4.1-1.4.1-2.2 1C1.2 5.9 1 7.5 1 7.5S.8 9.4.8 11.3v1.4c0 1.9.2 3.8.2 3.8s.2 1.6.9 2.3c.8.9 1.9.9 2.4 1 1.7.1 7.7.2 7.7.2s4.9 0 7.9-.2c.4-.1 1.4-.1 2.2-1 .7-.7.9-2.3.9-2.3s.2-1.9.2-3.8v-1.4c0-1.9-.2-3.8-.2-3.8zM9.7 15.1V8.9l5.2 3.1-5.2 3.1z"/></svg>',
			'linkedin' => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6.9 8.4H3.6V21h3.3V8.4zM5.2 3a1.9 1.9 0 100 3.8 1.9 1.9 0 000-3.8zM21 21v-6.9c0-3.3-1.8-4.8-4.1-4.8-1.9 0-2.7 1-3.2 1.8V8.4H10.4c0 .9 0 12.6 0 12.6h3.3v-7c0-.4 0-.7.1-1 .3-.7.9-1.4 1.9-1.4 1.4 0 1.9 1 1.9 2.6V21H21z"/></svg>',
			'twitter'  => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M18.9 3H22l-7 8 8.2 10h-6.4l-5-6.1L6 21H2.9l7.5-8.6L2.5 3h6.6l4.5 5.6L18.9 3zm-1.1 16h1.8L8.3 4.8H6.4L17.8 19z"/></svg>',
		);

		return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
	}
}

if ( ! function_exists( 'cmpsian_social_links' ) ) {
	/**
	 * Output the social icon links defined in the Customizer.
	 *
	 * @since 1.0.0
	 * @param string $classes Extra CSS classes for the wrapper.
	 * @return void
	 */
	function cmpsian_social_links( $classes = '' ) {
		$networks = array(
			'cmpsian_facebook' => array( 'label' => 'Facebook', 'icon' => 'facebook' ),
			'cmpsian_youtube'  => array( 'label' => 'YouTube',  'icon' => 'youtube' ),
			'cmpsian_linkedin' => array( 'label' => 'LinkedIn', 'icon' => 'linkedin' ),
			'cmpsian_twitter'  => array( 'label' => 'Twitter',  'icon' => 'twitter' ),
		);

		$items = array();
		foreach ( $networks as $key => $data ) {
			$url = cmpsian_get_option( $key );
			if ( $url && '#' !== $url ) {
				$items[] = sprintf(
					'<a class="cmpsian-social cmpsian-social--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a>',
					esc_attr( $data['icon'] ),
					esc_url( $url ),
					esc_attr( $data['label'] ),
					cmpsian_get_social_icon( $data['icon'] )
				);
			}
		}

		if ( empty( $items ) ) {
			return;
		}

		printf(
			'<span class="cmpsian-socials %1$s">%2$s</span>',
			esc_attr( $classes ),
			implode( '', $items ) // Icons are pre-escaped SVG from a trusted internal map.
		);
	}
}

if ( ! function_exists( 'cmpsian_dark_mode_toggle' ) ) {
	/**
	 * Output the Light/Dark mode toggle button.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function cmpsian_dark_mode_toggle() {
		?>
		<button type="button" class="cmpsian-theme-toggle" id="cmpsianThemeToggle"
			aria-label="<?php esc_attr_e( 'Toggle light and dark mode', 'campussian' ); ?>">
			<span class="cmpsian-theme-toggle__icon cmpsian-theme-toggle__icon--sun" aria-hidden="true">
				<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 17a5 5 0 100-10 5 5 0 000 10zm0 4a1 1 0 011 1v0a1 1 0 01-2 0v0a1 1 0 011-1zm0-20a1 1 0 011 1v0a1 1 0 01-2 0v0a1 1 0 011-1zm10 11a1 1 0 010 2h0a1 1 0 010-2h0zM3 12a1 1 0 010 2H3a1 1 0 010-2h0zm15.7 6.3a1 1 0 011.4 1.4l-.1.1a1 1 0 01-1.4-1.4l.1-.1zM4.3 4.3a1 1 0 011.4 1.4l-.1.1A1 1 0 014.2 4.4l.1-.1zm14.4 0l.1.1a1 1 0 01-1.4 1.4l-.1-.1a1 1 0 011.4-1.4zM5.7 18.3l-.1.1a1 1 0 01-1.4-1.4l.1-.1a1 1 0 011.4 1.4z"/></svg>
			</span>
			<span class="cmpsian-theme-toggle__icon cmpsian-theme-toggle__icon--moon" aria-hidden="true">
				<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
			</span>
		</button>
		<?php
	}
}

if ( ! function_exists( 'cmpsian_notice_date' ) ) {
	/**
	 * Return a formatted notice date (custom meta, falling back to publish date).
	 *
	 * @since 1.0.0
	 * @param int|null $post_id Optional post ID.
	 * @return string Formatted date.
	 */
	function cmpsian_notice_date( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$meta    = get_post_meta( $post_id, '_cmpsian_notice_date', true );
		$stamp   = $meta ? strtotime( $meta ) : get_the_time( 'U', $post_id );

		return date_i18n( get_option( 'date_format' ), $stamp );
	}
}

if ( ! function_exists( 'cmpsian_event_datetime' ) ) {
	/**
	 * Return a formatted event date/time string.
	 *
	 * @since 1.0.0
	 * @param int|null $post_id Optional post ID.
	 * @return string
	 */
	function cmpsian_event_datetime( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$date    = get_post_meta( $post_id, '_cmpsian_event_date', true );
		$time    = get_post_meta( $post_id, '_cmpsian_event_time', true );

		if ( ! $date ) {
			return '';
		}

		$out = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
		if ( $time ) {
			$out .= ' · ' . date_i18n( get_option( 'time_format' ), strtotime( $time ) );
		}

		return $out;
	}
}

if ( ! function_exists( 'cmpsian_get_posts' ) ) {
	/**
	 * Convenience wrapper to fetch notice/event posts.
	 *
	 * @since 1.0.0
	 * @param string $type  Post type slug.
	 * @param int    $count Number of posts.
	 * @param array  $extra Extra WP_Query args (merged).
	 * @return WP_Query
	 */
	function cmpsian_get_posts( $type = 'cmpsian_notice', $count = 5, $extra = array() ) {
		$args = array(
			'post_type'           => $type,
			'posts_per_page'      => absint( $count ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		return new WP_Query( array_merge( $args, $extra ) );
	}
}

if ( ! function_exists( 'cmpsian_breadcrumb' ) ) {
	/**
	 * Output a simple, accessible breadcrumb trail.
	 *
	 * Renders "Home / Parent / Current" for pages and singular views. Skipped on
	 * the front page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function cmpsian_breadcrumb() {
		if ( is_front_page() ) {
			return;
		}

		$sep   = '<span class="cmpsian-breadcrumb__sep" aria-hidden="true">/</span>';
		$items = array();

		// Home.
		$items[] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( home_url( '/' ) ),
			esc_html__( 'Home', 'campussian' )
		);

		if ( is_page() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $ancestor ) {
				$items[] = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( get_permalink( $ancestor ) ),
					esc_html( get_the_title( $ancestor ) )
				);
			}
			$items[] = '<span class="cmpsian-breadcrumb__current">' . esc_html( get_the_title() ) . '</span>';
		} elseif ( is_singular() ) {
			$pt_obj = get_post_type_object( get_post_type() );
			if ( $pt_obj && ! empty( $pt_obj->has_archive ) ) {
				$archive = get_post_type_archive_link( get_post_type() );
				if ( $archive ) {
					$items[] = sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( $archive ),
						esc_html( $pt_obj->labels->name )
					);
				}
			}
			$items[] = '<span class="cmpsian-breadcrumb__current">' . esc_html( get_the_title() ) . '</span>';
		} else {
			$items[] = '<span class="cmpsian-breadcrumb__current">' . esc_html( wp_get_document_title() ) . '</span>';
		}

		printf(
			'<nav class="cmpsian-breadcrumb" aria-label="%1$s">%2$s</nav>',
			esc_attr__( 'Breadcrumb', 'campussian' ),
			implode( ' ' . $sep . ' ', $items ) // Each item pre-escaped above.
		);
	}
}
