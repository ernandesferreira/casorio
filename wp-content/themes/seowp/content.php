<?php
/**
 * The template for displaying all pages.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * This is the template that displays all pages by default.
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
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	// The functions below called only if Live Composer isn't active
	// otherwise LiveComposer outputs everything for us
	echo lbmn_thumbnail(); echo lbmn_posttitle(); echo lbmn_postdate(); ?>
	<div class="entry-content">
		<?php if ( is_singular() ) : ?>
			<!-- 00000 -->
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'lbmn' ),
					'after'  => '</div>',
				) );
			?>
		<?php else : ?>
			<?php
				// Called only if Live Composer isn't active
				the_excerpt();
			?>
		<?php endif; ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->