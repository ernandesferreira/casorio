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

get_header();
// Output HTML comment with template file name if LBMN_THEME_DEBUG = 1
if ( LBMN_THEME_DEBUG ) echo '<!-- FILE: '.__FILE__.' -->';
?>

<div id="content" class="site-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content' ); ?>
	<?php endwhile; // end of the loop. ?>
</div><!-- #content -->
<?php wp_reset_query(); ?>
<?php get_footer(); ?>