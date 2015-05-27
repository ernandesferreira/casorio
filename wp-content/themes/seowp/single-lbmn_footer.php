<?php
/**
 * The template for displaying footer editing page.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * We use the special content type lbmn_footer to make possible for
 * end user to create complex unlimited footer designs. This single
 * post template is used while editing this custom content posts.
 * The template is never used for site visitors, only site admins
 * access it while working with footers.
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

get_header();
?>

<div class="footer-editing__pseudo-content">
	<p>To edit the footer design: click <a href="<?php the_permalink() ?>?dslc=active" class="dslca-activate-composer-hook">ACTIVATE EDITOR</a> and scroll page down.</p>
</div>
</div><!-- #main -->

<?php
/**
 * ----------------------------------------------------------------------
 * Output 'Call to action' area before the footer output
 */

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
?>

<div id="content" class="site-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'lbmn' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
		</article><!-- #post-## -->
	<?php endwhile; // end of the loop. ?>
</div><!-- #content -->

<a href="#" class="off-canvas__overlay exit-off-canvas">&nbsp;</a>
<aside class="right-off-canvas-menu off-canvas-area">
	<?php if ( is_active_sidebar( 'mobile-offcanvas' ) ): /* Mobile off-canvas */ ?>
		<div class="close-offcanvas">
			<a class="right-off-canvas-toggle" href="#"><i aria-hidden="true" class="lbmn-icon-cross"></i> <span>close</span></a>
		</div>
		<?php dynamic_sidebar( 'mobile-offcanvas' ); ?>
	<?php endif; ?>
 </aside>
</div><!--  .global-wrapper -->
</div><!-- .global-container -->
</div><!-- .off-canvas-wrap -->

<?php wp_footer(); ?>

</body>
</html>