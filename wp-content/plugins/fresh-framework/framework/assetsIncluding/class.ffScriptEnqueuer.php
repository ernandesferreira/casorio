<?php

class ffScriptEnqueuer extends ffBasicObject {
/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
	
	/**
	 * 
	 * @var ffWPLayer
	 */
	protected $_WPLayer = null;
	
	/**
	 * 
	 * @var ffScript_Factory
	 */
	protected $_scriptFactory = null;


	/**
	 * 
	 * @var array[ffScript]
	 */
	protected $_scripts = array();
	
	protected $_scriptsNonMinificable = array();
	
	protected $_actionEnqueueScriptsHeaderTriggered = false;
	
	/**
	 * 
	 * @var ffFrameworkScriptLoader
	 */
	protected $_frameworkScriptLoader = null;
	
/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	public function __construct( ffWPLayer $WPLayer, ffScript_Factory $scriptFactory ) {
		$this->_setWplayer($WPLayer);
		$this->_setScriptfactory($scriptFactory);
		$this->_getWplayer()->getHookManager()->addActionEnqueuScripts( array( $this, 'actionEnqueueScripts' ) );
		//$this->_getWplayer()->add_action_enque_scripts( array( $this, 'actionEnqueueScripts' ) );
	}
	
	public function addScriptFramework( $handle = null, $source = null, $dependencies = null, $version = null, $inFooter = null, $type = null, $additionalInfo = true ) {
		$source = $this->_getWplayer()->getFrameworkUrl() . $source;
		$this->addScript( $handle, $source, $dependencies, $version, $inFooter, $type, $additionalInfo );
	}
	
	public function addScript( $handle = null, $source = null, $dependencies = null, $version = null, $inFooter = null, $type = null, $additionalInfo = true ) {
		$script = $this->_getScriptfactory()
						->createScript( $handle, $source, $dependencies, $version, $inFooter, $type, $additionalInfo);
		
		$this->_addScript( $script );
		
	}
	
	public function addScriptNonMinificable( $handle = null, $source = null, $dependencies = null, $version = null, $inFooter = null, $type = null ) {
		$additionalInfo = false;
		$script = $this->_getScriptfactory()
						->createScript( $handle, $source, $dependencies, $version, $inFooter, $type, $additionalInfo);
		
		$this->_addScript( $script );
		
	}
	
	public function addScriptObject( ffScript $script ) {
		$this->_addScript($script);
	}
	
	public function actionEnqueueScripts() {
		$this->_actionEnqueueScriptsHeaderTriggered = true;
		$this->_enqueueNonMinificableScripts();
		
		if( !empty($this->_scripts) ) {
			foreach( $this->_scripts as $oneScript ) {
				$this->_getWplayer()
					->wp_enqueue_script(
							$oneScript->handle,
							$oneScript->source, 
							$oneScript->dependencies, 
							null, 
							$oneScript->inFooter
					);
			}
		}
	}
/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/
	private function _actionScriptsHeaderHasBeenTriggered() {

		if( $this->_actionEnqueueScriptsHeaderTriggered ) {
			return true;
		}
		
		
		if( $this->_getWplayer()->action_been_executed( $this->_getWplayer()->action_enqueue_scripts_name() ) ) {
			return true;
		}
		
		return false;
	}
	
	private function _addScript( ffScript $script ) {
		if( $this->_actionScriptsHeaderHasBeenTriggered() ) {
			$this->_getWplayer()
				->wp_enqueue_script(
					$script->handle,
					$script->source,
					$script->dependencies,
					null,
					true
			);
		}
	
		$this->_scriptsNonMinificable[ $script->handle ] = $script;
	
	}
	
	protected function _enqueueNonMinificableScripts() {
		if( !empty($this->_scriptsNonMinificable) ) {
			foreach( $this->_scriptsNonMinificable as $oneScript ) {
				$this->_getWplayer()
				->wp_enqueue_script(
						$oneScript->handle,
						$oneScript->source,
						$oneScript->dependencies,
						null,
						$oneScript->inFooter
				);
			}
		}
	}
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/	
	
	public function setFrameworkScriptLoader( ffFrameworkScriptLoader $frameworkScriptLoader ) {
		$this->_frameworkScriptLoader = $frameworkScriptLoader;
	}
	
	public function getFrameworkScriptLoader() {
		return $this->_frameworkScriptLoader;
	}
	
	/**
	 * @return ffWPLayer
	 */
	protected function _getWplayer() {
		return $this->_WPLayer;
	}
	
	/**
	 * @param ffWPLayer $_WPLayer
	 */
	protected function _setWplayer(ffWPLayer $WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}
	
	/**
	 * @return ffScript_Factory
	 */
	protected function _getScriptfactory() {
		return $this->_scriptFactory;
	}
	
	/**
	 * @param ffScript_Factory $_scriptFactory
	 */
	protected function _setScriptfactory(ffScript_Factory $scriptFactory) {
		$this->_scriptFactory = $scriptFactory;
		return $this;
	}	
}