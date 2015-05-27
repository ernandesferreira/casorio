<?php
/**
 * Theme defaults: colors, settings, etc.
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * Our theme use these values on theme activation while user haven't
 * made any changes through Theme Customizer
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

/**
* ----------------------------------------------------------------------
* Default Settings
* ! do not move these constants below file import:
* require_once( get_template_directory() . '/inc/customizer/customized-css.php'); !
*/

define( 'THEMENAME', 'seowp' );  // need for TGM plugin
define('LBMN_THEME_NAME', 'seowp'); // used in code, should be var safe
define('LBMN_THEME_NAME_DISPLAY', 'SEOWP Theme');
define('LBMN_DEVELOPER_NAME_DISPLAY', 'Lumberman Designs');
define('LBMN_DEVELOPER_URL', 'http://themeforest.net/user/lumbermandesigns');
define('LBMN_SUPPORT_URL', 'http://themeforest.net/user/lumbermandesigns#contact');
define('LBMN_INSTALLER', 'http://seowptheme.com');
define('LBMN_PLUGINS', '/themeinstaller/plugins/');

define('LBMN_CONTENT_WIDTH', 1200); // in pixels

define('LBMN_HOME_TITLE', 'Home page');
define('LBMN_BLOG_TITLE', 'Blog');

/**
 * ----------------------------------------------------------------------
 * Notification panel
 */
define('LBMN_NOTIFICATIONPANEL_HEIGHT_DEFAULT', 50);
define('LBMN_NOTIFICATIONPANEL_DEFAULT', 'im-icon-wordpress');
define('LBMN_NOTIFICATIONPANEL_MESSAGE_DEFAULT', 'Customize your theme in the Theme Customizer panel. See changes live without page reloading.' );
define('LBMN_NOTIFICATIONPANEL_BUTTONURL_DEFAULT', '/wp-admin/customize.php');

define('LBMN_NOTIFICATIONPANEL_BACKGROUNDCOLOR_DEFAULT', 'RGB(24, 101, 160)' );
define('LBMN_NOTIFICATIONPANEL_TXTCOLOR_DEFAULT', 'RGB(189, 227, 252)' );

define('LBMN_NOTIFICATIONPANEL_BACKGROUNDCOLOR_HOVER_DEFAULT', 'RGB(15, 119, 200)' );
define('LBMN_NOTIFICATIONPANEL_TXTCOLOR_HOVER_DEFAULT', 'RGB(255, 255, 255)' );

/**
 * ----------------------------------------------------------------------
 * Default top bar configuration
 */
define('LBMN_TOPBAR_SWITCH_DEFAULT', 1);
define('LBMN_TOPBAR_HEIGHT_DEFAULT', 48);

define('LBMN_TOPBAR_BACKGROUNDCOLOR_DEFAULT', 'rgb(27, 80, 131)');
define('LBMN_TOPBAR_LINKCOLOR_DEFAULT', 'rgba(150, 193, 216, 0.81)');
define('LBMN_TOPBAR_LINKHOVERCOLOR_DEFAULT', 'RGB(255, 255, 255)');
define('LBMN_TOPBAR_LINKHOVERBGCOLOR_DEFAULT', 'RGB(86, 174, 227)');
define('LBMN_TOPBAR_TEXTCOLOR_DEFAULT', 'rgba(150, 193, 216, 0.81)');

define('LBMN_TOPBAR_FIRSTLEVELITEMS_FONT_DEFAULT', 1);
define('LBMN_TOPBAR_FIRSTLEVELITEMS_FONTWEIGHT_DEFAULT', 400);
define('LBMN_TOPBAR_FIRSTLEVELITEMS_FONTSIZE_DEFAULT', 15);

define('LBMN_TOPBAR_FIRSTLEVELITEMS_ALIGN_DEFAULT', 'right');
define('LBMN_TOPBAR_FIRSTLEVELITEMS_ICONPOSITION_DEFAULT', 'left');
define('LBMN_TOPBAR_FIRSTLEVELITEMS_ICONSIZE_DEFAULT', 16);
define('LBMN_TOPBAR_FIRSTLEVELITEMS_SEPARATOR_DEFAULT', 'smooth');
define('LBMN_TOPBAR_FIRSTLEVELITEMS_SEPARATOR_OPACITY_DEFAULT', 0.15);

define('LBMN_HEADER_DESIGN_DEFAULT', 'header_design_1');

/**
 * ----------------------------------------------------------------------
 * Default header top section configuration
 */
define('LBMN_HEADERTOP_SWITCH_DEFAULT', 1);

define('LBMN_HEADERTOP_BACKGROUNDCOLOR_DEFAULT', 'RGBA(255, 255, 255, 0.3)');
define('LBMN_HEADERTOP_HEIGHT_DEFAULT', 120);
define('LBMN_HEADERTOP_MENUHEIGHT_DEFAULT', 40);

define('LBMN_HEADERTOP_STICK_SWITCH_DEFAULT', 0);
define('LBMN_HEADERTOP_STICK_BACKGROUNDCOLOR_DEFAULT', 'RGBA(255, 255, 255, 0.95)');
define('LBMN_HEADERTOP_STICK_DEFAULT', 34);
define('LBMN_HEADERTOP_STICKY_PADDING_DEFAULT', 10);
define('LBMN_HEADERTOP_STICKYOFFSET_DEFAULT', 134);

/**
 * ----------------------------------------------------------------------
 * Logo
 */
define('LBMN_LOGO_PLACEMENT_DEFAULT', 'bottom-left');
define('LBMN_LOGO_IMAGE_DEFAULT', esc_url_raw (get_template_directory_uri() .'/design/images/seo-wordpress-theme-logo-horizontal.png') );
define('LBMN_LOGO_IMAGE_HEIGHT_DEFAULT', 90); // percent of the header height

/**
 * ----------------------------------------------------------------------
 * Mega Menu
 */
define('LBMN_HEADERTOP_LINKCOLOR_DEFAULT', 'RGBA(0, 0, 0, 0.91)');
define('LBMN_HEADERTOP_LINKHOVERCOLOR_DEFAULT', 'rgb(31, 155, 232)');
define('LBMN_MEGAMENU_LINKHOVERBACKGROUNDCOLOR_DEFAULT', 'RGBA(255, 255, 255, 0)');
define('LBMN_HEADERTOP_TEXTCOLOR_DEFAULT', 'RGB(129, 129, 129)');

define('LBMN_MEGAMENU_FIRSTLEVELITEMS_FONT_DEFAULT', 1);
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_FONTWEIGHT_DEFAULT', 400);
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_FONTSIZE_DEFAULT', 16);

define('LBMN_MEGAMENU_FIRSTLEVELITEMS_ALIGN_DEFAULT', 'right');
define('LBMN_HEADERTOP_LINKHOVERBORDERRADIUS_DEFAULT', 3);
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_SPACING_DEFAULT', 0);
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_INNERSPACING_DEFAULT', 16);

define('LBMN_MEGAMENU_FIRSTLEVELITEMS_ICONPOSITION_DEFAULT', 'left');
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_ICONSIZE_DEFAULT', 17);

define('LBMN_MEGAMENU_FIRSTLEVELITEMS_SEPARATOR_DEFAULT', 'none');
define('LBMN_MEGAMENU_FIRSTLEVELITEMS_SEPARATOR_OPACITY_DEFAULT', 0);

/**
 * ----------------------------------------------------------------------
 * Mega Menu: Dropdown
 */
define('LBMN_MEGAMENU_DROPDOWN_TEXTCOLOR_DEFAULT', 'rgba(2, 11, 18, 0.63)');
define('LBMN_MEGAMENU_DROPDOWN_LINKCOLOR_DEFAULT', 'rgba(2, 11, 18, 0.63)');
define('LBMN_MEGAMENU_DROPDOWN_LINKHOVERCOLOR_DEFAULT', 'RGB(255, 255, 255)');
define('LBMN_MEGAMENU_DROPDOWN_LINKHOVERBACKGROUNDCOLOR_DEFAULT', 'RGB(86, 174, 227)');
define('LBMN_MEGAMENU_DROPDOWN_BACKGROUND_DEFAULT', 'RGB(255, 255, 255)');
define('LBMN_MEGAMENU_DROPDOWN_MENUITEMSDIVIDERCOLOR_DEFAULT', 'rgba(141, 141, 141, 0.07)');

define('LBMN_MEGAMENU_DROPDOWN_FONT_DEFAULT', 1);
define('LBMN_MEGAMENU_DROPDOWN_FONTWEIGHT_DEFAULT', 300);
define('LBMN_MEGAMENU_DROPDOWN_FONTSIZE_DEFAULT', 15);
define('LBMN_MEGAMENU_DROPDOWN_ICONSIZE_DEFAULT', 14);

define('LBMN_MEGAMENU_DROPDOWN_ANIMATION_DEFAULT', 'anim_4');
define('LBMN_MEGAMENU_DROPDOWNRADIUS_DEFAULT', 3);
define('LBMN_MEGAMENU_DROPDOWN_MARKEROPACITY_DEFAULT', 0);

/**
 * ----------------------------------------------------------------------
 * Search Field
 */
define('LBMN_SEARCHBLOCK_INPUTFIELDWIDTH_DEFAULT', 232);
define('LBMN_SEARCHBLOCK_INPUTFIELDRADIUS_DEFAULT', 100);
define('LBMN_SEARCHBLOCK_SHADOW_DEFAULT', 'inside');
define('LBMN_SEARCHBLOCK_INPUTBACKGROUNDCOLOR_DEFAULT', 'RGB(255, 255, 255)');
define('LBMN_SEARCHBLOCK_TEXTANDICONCOLOR_DEFAULT', 'RGB(79, 79, 79)');

/**
 * ----------------------------------------------------------------------
 * Page Layout
 */
define('LBMN_CONTENT_BACKGROUND_COLOR_DEFAULT', '#FFF');
define('LBMN_PAGELAYOUTBOXED_SWITCH_DEFAULT', 0);
define('LBMN_PAGEBACKGROUNDCOLOR_DEFAULT', 'RGB(102, 130, 144)');

/**
 * ----------------------------------------------------------------------
 * Typography
 */
define('LBMN_TYPOGRAPHY_LINK_COLOR_DEFAULT', 'rgb(42, 160, 239)');
define('LBMN_TYPOGRAPHY_LINK_HOVER_COLOR_DEFAULT', 'rgb(93, 144, 226)');

define('LBMN_TYPOGRAPHY_P_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_P_FONTWEIGHT_DEFAULT', '300');
define('LBMN_TYPOGRAPHY_P_FONTSIZE_DEFAULT','17');
define('LBMN_TYPOGRAPHY_P_LINEHEIGHT_DEFAULT','27');
define('LBMN_TYPOGRAPHY_P_MARGINBOTTOM_DEFAULT','20');
define('LBMN_TYPOGRAPHY_P_COLOR_DEFAULT','RGB(65, 72, 77)');

define('LBMN_TYPOGRAPHY_H1_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H1_FONTWEIGHT_DEFAULT', '200');
define('LBMN_TYPOGRAPHY_H1_FONTSIZE_DEFAULT','42');
define('LBMN_TYPOGRAPHY_H1_LINEHEIGHT_DEFAULT','48');
define('LBMN_TYPOGRAPHY_H1_MARGINBOTTOM_DEFAULT','25');
define('LBMN_TYPOGRAPHY_H1_COLOR_DEFAULT','RGB(70, 72, 75)');

define('LBMN_TYPOGRAPHY_H2_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H2_FONTWEIGHT_DEFAULT', '300');
define('LBMN_TYPOGRAPHY_H2_FONTSIZE_DEFAULT','31');
define('LBMN_TYPOGRAPHY_H2_LINEHEIGHT_DEFAULT','38');
define('LBMN_TYPOGRAPHY_H2_MARGINBOTTOM_DEFAULT','20');
define('LBMN_TYPOGRAPHY_H2_COLOR_DEFAULT','RGB(39, 40, 43)');

define('LBMN_TYPOGRAPHY_H3_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H3_FONTWEIGHT_DEFAULT', '300');
define('LBMN_TYPOGRAPHY_H3_FONTSIZE_DEFAULT','24');
define('LBMN_TYPOGRAPHY_H3_LINEHEIGHT_DEFAULT','33');
define('LBMN_TYPOGRAPHY_H3_MARGINBOTTOM_DEFAULT','20');
define('LBMN_TYPOGRAPHY_H3_COLOR_DEFAULT','RGB(16, 16, 17)');

define('LBMN_TYPOGRAPHY_H4_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H4_FONTWEIGHT_DEFAULT', '300');
define('LBMN_TYPOGRAPHY_H4_FONTSIZE_DEFAULT','21');
define('LBMN_TYPOGRAPHY_H4_LINEHEIGHT_DEFAULT','29');
define('LBMN_TYPOGRAPHY_H4_MARGINBOTTOM_DEFAULT','18');
define('LBMN_TYPOGRAPHY_H4_COLOR_DEFAULT','RGB(53, 54, 57)');

define('LBMN_TYPOGRAPHY_H5_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H5_FONTWEIGHT_DEFAULT', '500');
define('LBMN_TYPOGRAPHY_H5_FONTSIZE_DEFAULT','17');
define('LBMN_TYPOGRAPHY_H5_LINEHEIGHT_DEFAULT','27');
define('LBMN_TYPOGRAPHY_H5_MARGINBOTTOM_DEFAULT','25');
define('LBMN_TYPOGRAPHY_H5_COLOR_DEFAULT','RGB(16, 16, 17)');

define('LBMN_TYPOGRAPHY_H6_FONT_DEFAULT','1');
define('LBMN_TYPOGRAPHY_H6_FONTWEIGHT_DEFAULT', '400');
define('LBMN_TYPOGRAPHY_H6_FONTSIZE_DEFAULT','17');
define('LBMN_TYPOGRAPHY_H6_LINEHEIGHT_DEFAULT','27');
define('LBMN_TYPOGRAPHY_H6_MARGINBOTTOM_DEFAULT','25');
define('LBMN_TYPOGRAPHY_H6_COLOR_DEFAULT','RGB(70, 72, 75)');

/**
 * ----------------------------------------------------------------------
 * Font presets
 */
define('LBMN_FONT_PRESET_STANDARD_1_DEFAULT','helvetica');
define('LBMN_FONT_PRESET_STANDARD_2_DEFAULT','helvetica');
define('LBMN_FONT_PRESET_STANDARD_3_DEFAULT','helvetica');
define('LBMN_FONT_PRESET_STANDARD_4_DEFAULT','helvetica');

define('LBMN_FONT_PRESET_GOOGLEFONT_1_DEFAULT','Roboto');
define('LBMN_FONT_PRESET_GOOGLEFONT_2_DEFAULT','');
define('LBMN_FONT_PRESET_GOOGLEFONT_3_DEFAULT','');
define('LBMN_FONT_PRESET_GOOGLEFONT_4_DEFAULT','');

/**
 * ----------------------------------------------------------------------
 * Call to action area
 */
define('LBMN_CALLTOACTION_HEIGHT_DEFAULT', 160);
define('LBMN_CALLTOACTION_MESSAGE_DEFAULT', 'Call to action title here');
define('LBMN_CALLTOACTION_URL_DEFAULT', '/wp-admin/customize.php');

define('LBMN_CALLTOACTION_FONT_DEFAULT', 1);
define('LBMN_CALLTOACTION_FONTWEIGHT_DEFAULT', '300');
define('LBMN_CALLTOACTION_FONTSIZE_DEFAULT', 35);

define('LBMN_CALLTOACTION_BACKGROUNDCOLOR_DEFAULT', 'rgb(54, 61, 65)');
define('LBMN_CALLTOACTION_TXTCOLOR_DEFAULT', 'RGB(255, 255, 255)');

define('LBMN_CALLTOACTION_BACKGROUNDCOLOR_HOVER_DEFAULT', 'rgb(86, 174, 227)');
define('LBMN_CALLTOACTION_TXTCOLOR_HOVER_DEFAULT', 'RGB(255, 255, 255)');

// Footer
define('LBMN_FOOTER_DESIGN_TITLE_DEFAULT', '4 columns footer');

// System Page Templates
define('LBMN_SYSTEMPAGE_404_DEFAULT', '404 Page Not Found');
define('LBMN_SYSTEMPAGE_SEARCHRESULTS_DEFAULT', 'Search Results');
define('LBMN_SYSTEMPAGE_NOSEARCHRESULTS_DEFAULT', 'Nothing Found');
define('LBMN_SYSTEMPAGE_CATEGORY_DEFAULT', 'General Archive Page Template');
define('LBMN_SYSTEMPAGE_TAG_DEFAULT', 'General Archive Page Template');
define('LBMN_SYSTEMPAGE_DATE_DEFAULT', 'General Archive Page Template');
define('LBMN_SYSTEMPAGE_AUTHORS_DEFAULT', 'General Archive Page Template');
define('LBMN_SYSTEMPAGE_FRONTPAGE_POSTS_DEFAULT', 'General Archive Page Template');
