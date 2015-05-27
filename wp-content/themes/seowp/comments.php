<?php
/**
 * NOT USED template for displaying Comments.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * In our theme LiveComposer plugin render the comments thread and
 * the comment form in it's own template system, so the standard
 * WordPress comments output is obsolete in our case.
 * Unfortunately this file is required to successfully pass ThemeForest
 * Theme Check validation.
 *
 * PLEASE edit comments styling and output properties in LiveComposer
 * (Templates section) as changes to this file will be ignored of cause
 * duplicating of comment threads on your pages.
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
?>
<?php
	// Process function only when Live Composer plugin is disabled
	if ( is_plugin_active('ds-live-composer/ds-live-composer.php') )
		return; // return early without loading the file
	if ( have_comments() || comments_open() ) {
		echo '<div class="comments-section"><div class="comments-section-inner">';
			echo '<div class="comments">';
				echo '<h3 id="comments-title">';
				printf(
					_n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'twentyten' ),
					number_format_i18n( get_comments_number() ),
					'<em>' . get_the_title() . '</em>' 
					);
				echo '</h3>';
				wp_list_comments( array( 'style' => 'div' ) );

				echo '<div class="comments-pagination">';
					paginate_comments_links();
				echo '</div>';
			echo '</div>';
			echo '<div class="comment-form-block">';
				comment_form();
			echo '</div>';
		echo '</div></div>';
	}
?>
