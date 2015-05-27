/* Map tinymce scripts */
/* version 1.0 */

(function() {
	"use strict";
	tinymce.PluginManager.add( 'sbmapshortcodes', function( editor, url ) {
		editor.addButton( 'sbmapshortcodes', {
			type	: 'menubutton',
			text	: '',
			icon	: 'sb_google_map',
			tooltip	: 'SB Google Map',
			onselect: function(e) {
			},
			menu: sbmap_shortcodes
		});
	});
 
})();