<?php 

class ffWPUpgrader {

/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
	/**
	 * 
	 * @var ffWPLayer
	 */
	private $_WPLayer = null;

	/**
	 * 
	 * @var ffPluginIdentificator
	 */
	private $_pluginIdentificator = null;
	
	/**
	 * 
	 * @var ffHttp
	 */
	private $_http = null;
	
	/**
	 * 
	 * @var ffDataStorage_WPOptions_NamespaceFacede
	 */
	private $dataStorage = null;
	
	/**
	 * 
	 * @var string
	 */
	private $_freshfaceServerUrl = null;
	
	
	private $_hasBeenLoaded = null;

	
	private $_responseFromOurServer = null;
	

/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	/**
	 * Constructor
	 */
	public function __construct( ffWPLayer $WPLayer, ffPluginIdentificator $pluginIdentificator, ffHttp $http, ffDataStorage_WPOptions_NamespaceFacede $dataStorage, $serverUrl ) {
		$this->_setWPLayer( $WPLayer );
		$this->_setPluginIdentificator( $pluginIdentificator );
		$this->_setHttp( $http );
		$this->_setFreshfaceServerUrl($serverUrl);
		$this->_setDataStorage( $dataStorage );
		
		$this->_getDataStorage()->setNamespace('ffWPUpgrader');
		$this->_setHasBeenLoaded(false);
		$this->_hookActions();
	}
	
	/**
	 * This function is injecting our plugin updates. Wordpress is calling this
	 * function twice, so I made a primitive memory caching to avoid double
	 * http requests.
	 * 
	 * @param unknown $transient
	 * @return unknown
	 */
	public function actionPreUpdateOptionShow( $transient ) {
		
		
		if( $this->_getHasBeenLoaded() == false ) {
			$action = 'update_info';
			
			$info_themes = serialize($this->_getPluginIdentificator()->getAllThemesInfo());
			$info_plugins = serialize($this->_getPluginIdentificator()->getAllPluginInfo());
			$website_url = $this->_getWPLayer()->get_home_url();
			
			
			$infoBack = $this->_getHttp()
								->post(
										$this->_getFreshfaceServerUrl(), 
										array( 'action' => $action, 
												'info_themes' => $info_themes,
												'info_plugins' => $info_plugins,
												'info_home' => $website_url
										)
								);

			// echo '<pre>';
			// var_dump(	
			// 	array( 
			// 		'action' => $action, 
			// 		'info_themes' => $info_themes,
			// 		'info_plugins' => $info_plugins,
			// 		'info_home' => $website_url
			// 	)
			// ); 
			// echo '</pre>';
			if( !($infoBack instanceof WP_Error) && $infoBack['response']['code'] == 200 ) {
				//echo '<pre>';var_dump($infoBack['body']);exit;
				$ourUpdateInfo = unserialize($infoBack['body']);


				if( is_array( $ourUpdateInfo ) ) {
					
					if( is_object($transient) && property_exists( $transient, 'response') && is_array( $transient->response ) ) {
						$transient->response = array_merge( $transient->response, $ourUpdateInfo );
					}
					
					//$transient->response = array_merge( $transient->response, $ourUpdateInfo );
					$this->_setHasBeenLoaded(true);
					$this->_setResponseFromOurServer( $ourUpdateInfo );
				}	
			} 
		} else {
			if( $this->_getResponseFromOurServer() != null ) {
				$transient->response = array_merge( $transient->response, $this->_responseFromOurServer['upgrades'] );
			}
		}
		
		return $transient;
	}
	
	/**
	 * Gather info about the plugin upgrade. If we have it return the info,
	 * if we dont have it, return false and wordpress will try another ways.
	 * 
	 * @param unknown $false
	 * @param unknown $action
	 * @param unknown $arg
	 * @return unknown|boolean
	 */
	public function actionPluginsApi($false, $action, $arg)
	{
		$response = $this->_getResponseFromOurServer();

		$plug = null;
		if( isset( $arg->slug) && isset( $response['informations'][ $arg->slug] ) ) {
			$plug = $response['informations'][ $arg->slug];
			return $plug;
		}
		
		return false;
	}
	
	
/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/
	private function _hookActions() {
		$this->_getWPLayer()->add_filter('pre_set_site_transient_update_plugins', array( $this, 'actionPreUpdateOptionShow'));
		$this->_getWPLayer()->add_filter('plugins_api', array( $this, 'actionPluginsApi' ), 10, 3);
		
	}
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/
	
	/**
	 * @return ffWPLayer
	 */
	protected function _getWPLayer() {
		return $this->_WPLayer;
	}
	
	/**
	 * @param ffWPLayer $WPLayer
	 */
	protected function _setWPLayer(ffWPLayer $WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}

	protected function _getFreshfaceServerUrl() {
		return $this->_freshfaceServerUrl;
	}
	
	protected function _setFreshfaceServerUrl( $freshfaceServerUrl) {
		$this->_freshfaceServerUrl = $freshfaceServerUrl;
		return $this;
	}

	/**
	 * @return ffPluginIdentificator
	 */
	protected function _getPluginIdentificator() {
		return $this->_pluginIdentificator;
	}
	
	/**
	 * @param ffPluginIdentificator $pluginIdentificator
	 */
	protected function _setPluginIdentificator(ffPluginIdentificator $pluginIdentificator) {
		$this->_pluginIdentificator = $pluginIdentificator;
		return $this;
	}
	
	/**
	 * @return ffHttp
	 */
	protected function _getHttp() {
		return $this->_http;
	}
	
	/**
	 * @param ffHttp $http
	 */
	protected function _setHttp(ffHttp $http) {
		$this->_http = $http;
		return $this;
	}

	protected function _getHasBeenLoaded() {
		return $this->_hasBeenLoaded;
	}
	
	protected function _setHasBeenLoaded($hasBeenLoaded) {
		$this->_hasBeenLoaded = $hasBeenLoaded;
		return $this;
	}
	
	protected function _getResponseFromOurServer() {
		if( $this->_responseFromOurServer == null ) {
			$this->_responseFromOurServer = $this->_getDataStorage()->getOption('response_from_our_server');
		}
		return $this->_responseFromOurServer;
	}
	
	protected function _setResponseFromOurServer($responseFromOurServer) {
		$this->_responseFromOurServer = $responseFromOurServer;
		$this->_getDataStorage()->setOption('response_from_our_server', $responseFromOurServer );
		return $this;
	}

	/**
	 * @return ffDataStorage_WPOptions_NamespaceFacede
	 */
	protected function _getDataStorage() {
		return $this->_dataStorage;
	}
	
	/**
	 * @param ffDataStorage_WPOptions_NamespaceFacede $dataStorage
	 */
	protected function _setDataStorage(ffDataStorage_WPOptions_NamespaceFacede $dataStorage) {
		$this->_dataStorage = $dataStorage;
		return $this;
	}
}