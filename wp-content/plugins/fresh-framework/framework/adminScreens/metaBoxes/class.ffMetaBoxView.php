<?php

abstract class ffMetaBoxView extends ffBasicObject {
	const USED_META_BOX_COLLECTOR_NAME = 'ff-used-meta-box-classes';
	
	private $_params = null;
	
	public function setParams( $params ) {
		$this->_params = $params;
	}
	
	protected function _getParam( $name, $default = null ) {
		if( isset( $this->_params[ $name ] ) ) {
			return $this->_params[ $name ];
		} else {
			return $default;
		}
	} 
	
	public function render($post) {
		$container = ffContainer::getInstance();
		$container->getFrameworkScriptLoader()
					->requireFrsLib()
					->requireFrsLibMetaboxes();
		
		echo '<input type="hidden" name="'.ffMetaBoxView::USED_META_BOX_COLLECTOR_NAME.'[]" value="'. str_replace('View','', get_class( $this ) ).'" >';
		$this->_requireAssets();
		
		
		
		echo '<div class="ff-metabox-content">';

		if( $this->_getParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS ) == true ) {
			echo '<div class="ff-metabox-normalize-options">';
		}
		
			$this->_render($post);

		if( $this->_getParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS ) == true ) {
			echo '</div>';
		}
		
		echo '</div>';
	}
	
	public function save($postId) {
		$this->_save($postId);
	}
	
	protected function _requireAssets() {
		
	}
	
	abstract protected function _render( $post);
	
	abstract protected function _save( $postId );
}