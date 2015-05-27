<?php
/**
 * The template for displaying blog posts and custom post types pages.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * This is the template that displays standard WordPress posts and
 * all custom post type pages. By default our theme includes
 * the next custom types:
 * 	– Projects     > dslc_projects
 * 	– Galleries    > dslc_galleries
 * 	– Staff        > dslc_staff
 * 	– Downloads    > dslc_downloads
 * 	– Testimonials > dslc_testimonials
 * 	– Partners     > dslc_partners
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
<div id="content" class="site-content" role="main">
	<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content' );
		}

		if ( !is_plugin_active('ds-live-composer/ds-live-composer.php') ) {
			// Normally tags displayed by LiveComposer
			the_tags( '<div class="post-tags">', ',', '</div>' );
		}
	?>
</div><!-- #content -->
<?php

//All the comment forms and threads outputted by LiveComposer
comments_template('/comments.php');

get_footer();
