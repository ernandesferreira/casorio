<?php
/**
 * The template for displaying the footer.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * This file outputs the next theme parts:
 * 	– Site-wide pre-footer 'Call to action' block
 * 	– LiveComposer based footer
 * 	– Off-canvas mobile menu ('mobile-offcanvas' widget area)
 *
 * @package    SEOWP WordPress Theme
 * @author     Vlad Mitkovsky <info@lumbermandesigns.com>
 * @copyright  2014 Lumberman Designs
 * @license    http://themeforest.net/licenses
 * @link       http://themeforest.net/user/lumbermandesigns
 *
 * -------------------------------------------------------------------
 *
 * Send your ideas on code improvement or new hook requests using
 * contact form on http://themeforest.net/user/lumbermandesigns
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Output HTML comment with template file name if LBMN_THEME_DEBUG = 1
if ( LBMN_THEME_DEBUG ) echo '<!-- FILE: '.__FILE__.' -->';

echo '</div><!-- .site-main -->';
// <div class="site-main"> opened in the header.php

/**
 * ----------------------------------------------------------------------
 * Output 'Call to action' area before the footer output
 */
// disable 'Call to action' area for editing mode in Live Composer
if ( defined('DS_LIVE_COMPOSER_ACTIVE') && ! DS_LIVE_COMPOSER_ACTIVE ) {
	// Get data from theme customizer
	$calltoaction_switch  = get_theme_mod( 'lbmn_calltoaction_switch', 1 );
	$calltoaction_message = get_theme_mod( 'lbmn_calltoaction_message', LBMN_CALLTOACTION_MESSAGE_DEFAULT );
	$calltoaction_url     = get_theme_mod( 'lbmn_calltoaction_url', LBMN_CALLTOACTION_URL_DEFAULT );

	global $wp_customize;
	// If call to action panel is active or we are in theme customiser
	if ( $calltoaction_switch || isset($wp_customize) ) {
		?>
		<section class='calltoaction-area' data-stateonload='<?php echo $calltoaction_switch; ?>'>
			<span class='calltoaction-area__content'>
				<?php
						echo $calltoaction_message."&nbsp;&nbsp;&nbsp;<i class='fa-icon-angle-right calltoaction-area__cta-icon'></i>";
				?>
			</span>
			<?php if ( 4 < strlen($calltoaction_url)  ) { ?>
				<a href='<?php echo esc_url( $calltoaction_url );?>' class='calltoaction-area__cta-link'></a>
			<?php } ?>
		</section>
		<?php
	}
}

/**
 * ----------------------------------------------------------------------
 * Output LiveComposer-based footer ( WP Admin > Appearance > Footers )
 * For WordPress it's a page of custom content type: Footer > lbmn_footer
 */

if ( defined( 'DS_LIVE_COMPOSER_URL' ) && defined('LBMN_THEME_CONFUGRATED') && LBMN_THEME_CONFUGRATED  ){
	// Show footer only if LC isn't active
	if ( defined('DS_LIVE_COMPOSER_ACTIVE') && DS_LIVE_COMPOSER_ACTIVE ) {
		echo '<footer class="site-footer">The footer is disabled when editing the page.</footer>';
	} else {

		// If not 404 or no search results page otherwise get_the_ID() throws a notice
		if ( have_posts() ) {
			$post_id = get_the_ID();
		} else {
			$post_id = '';
		}

		$footer_post_id = lbmn_get_footerid_by_pageid($post_id);
		$composer_code = get_post_meta($footer_post_id, 'dslc_code', true );
		$composer_content = '';

		// If composer code not empty
		if ( $composer_code ) {
			// Generate the composer output
			$composer_content = do_shortcode( $composer_code );
		}

		global $dslc_active;
		if ( $composer_code ) {
			// Show footer content only if LiveComposer edit mode isn't active
			if ( defined('DS_LIVE_COMPOSER_ACTIVE') && ! DS_LIVE_COMPOSER_ACTIVE ) {
				echo '<footer id="dslc-content" class="site-footer dslc-content dslc-clearfix"><div id="dslc-footer" class="dslc-content dslc-clearfix">' . do_action( 'dslc_footer_output_prepend') . $composer_content . do_action( 'dslc_footer_output_append') . '</div></footer>';
			}
		}
	}

} else {
	// The functions below called only if Live Composer isn't active
	echo lbmn_footer();
}

/**
 * ----------------------------------------------------------------------
 * Off-canvas mobile menu panel ('mobile-offcanvas' widget area)
 */
// disable Off-canvas panel for editing mode in Live Composer
if ( defined('DS_LIVE_COMPOSER_ACTIVE') && ! DS_LIVE_COMPOSER_ACTIVE ):
?>

<a href="#" class="off-canvas__overlay exit-off-canvas">&nbsp;</a>
<aside class="right-off-canvas-menu off-canvas-area">
	<?php if ( is_active_sidebar( 'mobile-offcanvas' ) ): /* Mobile off-canvas */ ?>
		<div class="close-offcanvas">
			<a class="right-off-canvas-toggle" href="#"><i aria-hidden="true" class="lbmn-icon-cross"></i> <span>close</span></a>
		</div>
		<?php dynamic_sidebar( 'mobile-offcanvas' ); ?>
	<?php endif; ?>
</aside>

<?php endif; // !$dslc_active ?>

		</div><!--  .global-wrapper -->
	</div><!-- .global-container -->
</div><!-- .off-canvas-wrap -->

<?php wp_footer(); ?>

</body>
</html>