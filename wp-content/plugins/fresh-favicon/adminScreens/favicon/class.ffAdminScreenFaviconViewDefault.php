<?php

class ffAdminScreenFaviconViewDefault extends ffAdminScreenView {


	protected function _render() {

		echo '<div class="wrap">';
		echo '<form method="post">';

		echo '<h2 class="nav-tab-wrapper">';
		echo '<a href="#ff-favicon-admin-tab-basic" class="nav-tab nav-tab-active" data-for="ff-favicon-admin-tab-basic">Basic Favicon</a>';
		echo '<a href="#ff-favicon-admin-tab-advanced" class="nav-tab" data-for="ff-favicon-admin-tab-advanced">Advanced</a>';
		echo '</h2>';

		$structureFaviconAdvanced = ffContainer::getInstance()->getOptionsHolderFactory()->createOptionsHolder('ffOptionsHolderFavicon')->getOptions();
		$postReader = ffContainer::getInstance()->getOptionsFactory()->createOptionsPostReader();
		$postReader->setOptionsStructure( $structureFaviconAdvanced );
		$postData = $postReader->getData( ffPluginFreshFaviconContainer::STRUCTURE_NAME );

		$dataStorage = ffContainer::getInstance()->getDataStorageFactory()->createDataStorageWPOptionsNamespace( ffPluginFreshFaviconContainer::STRUCTURE_NAME );
		if( ! empty($postData) ){
			// Repair timestamp for icons
			$timestamp_suffix = Date('Y_m_d__h_i_s');
			$postData[ ffPluginFreshFaviconContainer::STRUCTURE_NAME ]['timestamp_suffix'] = $timestamp_suffix;
			$dataStorage->setOption( ffPluginFreshFaviconContainer::STRUCTURE_NAME, $postData);
		}
		$data = $dataStorage->getOption( ffPluginFreshFaviconContainer::STRUCTURE_NAME );

		$printer = ffContainer::getInstance()->getOptionsFactory()->createOptionsPrinterBoxed( $data, $structureFaviconAdvanced );
		$printer->setIdprefix( ffPluginFreshFaviconContainer::STRUCTURE_NAME );
		$printer->setNameprefix( ffPluginFreshFaviconContainer::STRUCTURE_NAME );
		$printer->walk();

		echo '</form>';
		echo '</div>';

		?>

		<script>
			jQuery(window).load(function(){
				jQuery(".ff-default-wp-color-picker").wpColorPicker();
			});
			</script>

			<script>
			jQuery(".nav-tab").click(function(){
				jQuery(".ff-favicon-admin-tab-content").hide();
				jQuery("." + jQuery(this).attr("data-for")).show();
				jQuery(".nav-tab-active").removeClass("nav-tab-active");
				jQuery(this).addClass("nav-tab-active");
			});
			</script>

			<script>
			jQuery(document).ready(function(){
				jQuery(".nav-tab-active").click();
			});
			</script>

			<script>
			(function($){
				$(document).ready(function(){
					$('.ff-open-image-library-button-wrapper input').change(function(){
						var $_input_ = $(this);
						window.setTimeout(function(){
							var $_par_ = $_input_.parents('.ff-open-image-library-button-wrapper');
							var $_button_ = $_par_.find('.ff-open-image-library-button');
							var $_next_to_ = $_par_.next();
							if( ! $_button_.hasClass('ff-bad-resolution') ){
								$_next_to_.find('.ff-warning').hide();
								return;
							}
							if( 0 == $_next_to_.find('.ff-warning').size() ){
								var width = $_button_.attr('data-forced-width');
								var height = $_button_.attr('data-forced-height');
								$_next_to_.append('<span class="ff-warning" style="color: #aa0000;"> &ndash; Select an image that is exactly '+width+' x '+height+' px to avoid quality loss due to scaling </span>');
							}
							$_next_to_.find('.ff-warning').show();
						},50);
					}).change();
				});
			})(jQuery);
			</script>

		<?php

		if( ! empty($postData) ){

			$dataStorageCache = ffContainer::getInstance()
				->getDataStorageCache()
				->deleteNamespace(ffPluginFreshFaviconContainer::STRUCTURE_NAME);

			// Prepare icons

			$img_to_ico = array(
				'favicon_16x16',
				'favicon_32x32',
				'favicon_48x48',
			);

			$img_to_ico_filepaths = $this->_getImagesFilePathFromPost( $img_to_ico, $postData );

			if( !empty( $img_to_ico_filepaths ) ){
				$this->_createIconsAfterSave( $timestamp_suffix, $img_to_ico_filepaths );
			}

			// Copy all to PNGs

			$img_to_png = array(
				'favicon_196x196',
				'favicon_195x195',
				'favicon_160x160',
				'favicon_152x152',
				'favicon_144x144',
				'favicon_120x120',
				'favicon_114x114',
				'favicon_96x96',
				'favicon_76x76',
				'favicon_72x72',
				'favicon_60x60',
				'favicon_57x57',
				'favicon_32x32',
				'favicon_16x16',

				'favicon_70x70',
				'favicon_150x150',
				'favicon_310x310',
				'favicon_310x150',
			);

			$img_to_png_filepaths = $this->_getImagesFilePathFromPost( $img_to_png, $postData, true );

			if( !empty( $img_to_png_filepaths ) ){
				$this->_createPNGAfterSave( $timestamp_suffix, $img_to_png_filepaths);
			}

			// Special for Winows

			$win_img_to_png = array(
				'favicon_70x70',
				'favicon_150x150',
				'favicon_310x310',
				'favicon_310x150',
			);

			$win_img_to_png_filepaths = $this->_getImagesFilePathFromPost( $win_img_to_png, $postData, true );

			if( !empty( $win_img_to_png_filepaths ) ){
				$iconConvertor = ffPluginFreshFaviconContainer::getInstance()->getIconConvertor();

				$iconConvertor->createWinXML( $timestamp_suffix, $win_img_to_png_filepaths, $postData['ff_fresh_favicon'][ 'favicon_144x144_bg' ] );
			}
		}
	}

	protected function _getImagesFilePathFromPost( $img_to_ico, $_post_updated, $full_index = false ){
		$fileSystem = ffContainer::getInstance()->getFileSystem();

		$img_to_ico_filepaths = array();

		foreach ($img_to_ico as $opt) {
			if( empty( $_post_updated['ff_fresh_favicon'][ $opt ] ) ){



				// TODO: When empty use the first bigger icon



				if( empty( $_post_updated['ff_fresh_favicon'][ 'favicon_basic' ] ) ){
					continue;
				}else{
					$_post_updated['ff_fresh_favicon'][ $opt ] = $_post_updated['ff_fresh_favicon'][ 'favicon_basic' ];
				}
			}
			$img = json_decode( stripslashes( $_post_updated['ff_fresh_favicon'][$opt] ) );
			if( empty($img->url) ){
				continue;
			}
			$imgURL = $img->url;
			if( $full_index ){
				$size = $opt;
			}else{
				$size = explode('x', $opt);
				$size = 1 * $size[1];
			}

			$imgFile = $fileSystem->findFileFromUrl( $imgURL );

			$img_to_ico_filepaths[ $size ] = $imgFile;
		}

		return $img_to_ico_filepaths;
	}

	protected function _createIconsAfterSave( $timestamp_suffix, $img_to_ico_filepaths ){
		if( !empty( $img_to_ico_filepaths ) ){

			$iconConvertor = ffPluginFreshFaviconContainer::getInstance()->getIconConvertor();

			foreach ($img_to_ico_filepaths as $size => $imgFile) {
				$iconConvertor->addImage( $size, $imgFile );
			}

			$iconConvertor->saveICO( $timestamp_suffix );
		}
	}

	protected function _createPNGAfterSave( $timestamp_suffix, $img_to_ico_filepaths ){
		if( !empty( $img_to_ico_filepaths ) ){

			$iconConvertor = ffPluginFreshFaviconContainer::getInstance()->getIconConvertor();

			foreach ($img_to_ico_filepaths as $size => $imgFile) {
				$iconConvertor->savePNG( $size, $imgFile, $timestamp_suffix );
			}
		}
	}

	protected function _requireAssets() {
		$container = ffPluginFreshFaviconContainer::getInstance();
		$fwc = ffContainer::getInstance();

		$fwc->getWPLayer()->wp_enqueue_media();

		$pluginUrl = $container->getPluginUrl();
		$scriptEnqueuer = $fwc->getScriptEnqueuer();
		$styleEnqueuer = $fwc->getStyleEnqueuer();

		// code

		$styleEnqueuer->addStyle( 'wp-color-picker' );
		$scriptEnqueuer->addScript( 'wp-color-picker' );

		$styleEnqueuer->addStyle('ff-file-editor-less', $pluginUrl.'/assets/css/ff-favicon.less');

	}

	protected function _setDependencies() {

	}

	public function ajaxRequest( ffAdminScreenAjax $ajax ) {

	}
}