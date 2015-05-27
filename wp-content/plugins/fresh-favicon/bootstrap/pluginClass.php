<?php
 
class ffPluginFreshFavicon extends ffPluginAbstract {
	/**
	 *
	 * @var ffPluginFreshFaviconContainer
	 */
	protected $_container = null;

	protected function _registerAssets() {
		$fwc = $this->_getContainer()->getFrameworkContainer();
		$fwc->getAdminScreenManager()->addAdminScreenClassName('ffAdminScreenFavicon');

		$WPLayer = $this->_getContainer()->getFrameworkContainer()->getWPLayer();
		$WPLayer->add_action('wp_head', array($this, 'actionWPHeadPrintIcon'), 999);
	}

	protected function _run() {

		//	$fwc = $this->_getContainer()->getFrameworkContainer();
	}

	protected function _registerActions() {

	}

	public function actionWPHeadPrintIcon(){
		$fwc = ffPluginFreshFaviconContainer::getInstance()->getFrameworkContainer();
		$DS_Cache = $fwc->getDataStorageCache();
		$DS = $fwc->getDataStorageFactory()->createDataStorageWPOptionsNamespace( ffPluginFreshFaviconContainer::STRUCTURE_NAME );

		$options_tmp = $DS->getOption( ffPluginFreshFaviconContainer::STRUCTURE_NAME );

		if( empty($options_tmp) ) return;
		if( empty($options_tmp[ ffPluginFreshFaviconContainer::STRUCTURE_NAME ] ) ) return;
		$options= $options_tmp[ ffPluginFreshFaviconContainer::STRUCTURE_NAME ];

		if( empty($options[ 'timestamp_suffix' ] ) ) return;

		$timestamp_suffix = $options[ 'timestamp_suffix' ];

		// Favicon

		echo "\n" . '<!-- Favicon -->' . "\n";

		// .PNG Favicons

		$icons = array(
			'favicon_57x57'   => '<link rel="apple-touch-icon" sizes="57x57" href="%s">' . ' <!-- iPhone iOS ≤ 6 favicon -->' . "\n" ,
			'favicon_114x114' => '<link rel="apple-touch-icon" sizes="114x114" href="%s">' . ' <!-- iPhone iOS ≤ 6 Retina favicon -->' . "\n" ,
			'favicon_72x72'   => '<link rel="apple-touch-icon" sizes="72x72" href="%s">' . ' <!-- iPad iOS ≤ 6 favicon -->' . "\n" ,
			'favicon_144x144' => '<link rel="apple-touch-icon" sizes="144x144" href="%s">' . ' <!-- iPad iOS ≤ 6 Retina favicon -->' . "\n" ,
			'favicon_60x60'   => '<link rel="apple-touch-icon" sizes="60x60" href="%s">' . ' <!-- iPhone iOS ≥ 7 favicon -->' . "\n" ,
			'favicon_120x120' => '<link rel="apple-touch-icon" sizes="120x120" href="%s">' . ' <!-- iPhone iOS ≥ 7 Retina favicon -->' . "\n" ,
			'favicon_76x76'   => '<link rel="apple-touch-icon" sizes="76x76" href="%s">' . ' <!-- iPad iOS ≥ 7 favicon -->' . "\n" ,
			'favicon_152x152' => '<link rel="apple-touch-icon" sizes="152x152" href="%s">' . ' <!-- iPad iOS ≥ 7 Retina favicon -->' . "\n" ,
			'favicon_196x196' => '<link rel="icon" type="image/png" sizes="196x196" href="%s">' . ' <!-- Android Chrome M31+ favicon -->' . "\n" ,
			'favicon_160x160' => '<link rel="icon" type="image/png" sizes="160x160" href="%s">' . ' <!-- Opera Speed Dial ≤ 12 favicon -->' . "\n" ,
			'favicon_96x96'   => '<link rel="icon" type="image/png" sizes="96x96" href="%s">' . ' <!-- Google TV favicon -->' . "\n" ,
			'favicon_32x32'   => '<link rel="icon" type="image/png" sizes="32x32" href="%s">' . ' <!-- Default medium favicon -->' . "\n" ,
			'favicon_16x16'   => '<link rel="icon" type="image/png" sizes="16x16" href="%s">' . ' <!-- Default small favicon -->' . "\n" ,
		);

		foreach ($icons as $size => $tag) {
			if( empty( $options[ $size ] ) and empty( $options[ 'favicon_basic' ] ) ){
				continue;
			}
			$url = $DS_Cache->getOptionUrl( ffPluginFreshFaviconContainer::STRUCTURE_NAME, $size . '--' . $timestamp_suffix, 'png' );
			printf( $tag, $url );
		}

		// Windows Favicons 

		if( !empty( $options[ 'favicon_144x144_bg' ] ) ){
			echo '<meta name="msapplication-TileColor" content="'.$options[ 'favicon_144x144_bg' ].'" >' . ' <!-- IE10 Windows 8.0 favicon -->' . "\n";
		}

		if( !empty( $options[ 'favicon_144x144' ] ) ){
			$url = json_decode( $options[ 'favicon_144x144' ] );
			$url = $url->url;
			echo '<meta name="msapplication-TileImage" content="'.$url.'" >' . ' <!-- IE10 Windows 8.0 favicon background color -->' . "\n";
		}

		$win_img_to_png = array(
			'favicon_70x70',
			'favicon_150x150',
			'favicon_310x310',
			'favicon_310x150',
		);

		$add_win_xml = false;
		foreach ($win_img_to_png as $size) {
			if( !empty( $options[ $size ] ) ){
				$add_win_xml = true;
				break;
			}
		}

		if( $add_win_xml ){
			$url = $DS_Cache->getOptionUrl(
				ffPluginFreshFaviconContainer::STRUCTURE_NAME,
				'browserconfig--' . $timestamp_suffix,
				'xml'
			);
			echo '<meta name="msapplication-config" content="'.$url.'" />' . ' <!-- Windows 8.1 browserconfig.xml -->' . "\n";
		}

		// .ICO Favicons

		$img_to_ico_all    = array( '16x16', '32x32', '48x48', 'basic' );
		$img_to_ico_picked = array();

		foreach ($img_to_ico_all as $size) {
			if( !empty( $options[ 'favicon_'.$size ] ) ){
				$img_to_ico_picked[] = $size;
			}
		}

		if( !empty( $img_to_ico_picked ) ){
			echo '<link rel="shortcut icon" href="';
			echo $DS_Cache->getOptionUrl( ffPluginFreshFaviconContainer::STRUCTURE_NAME, 'icon' . $timestamp_suffix, 'ico' );
			echo '" />';
			echo ' <!-- Default favicons (16, 32, 48) in .ico format -->';
			echo "\n";
		}


		echo '<!--/Favicon -->' . "\n\n";

	}

	protected function _setDependencies() {

	}


	/**
	 * @return ffPluginFreshFaviconContainer
	 */
	protected function _getContainer() {
		return $this->_container;
	}
}