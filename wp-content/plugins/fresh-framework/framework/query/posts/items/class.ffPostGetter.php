<?php

class ffPostGetter extends ffBasicObject{

	protected $_selectArgs = array();

	public function __construct( ffWPLayer $WPLayer, ffPostCollection_Factory $ffPostCollection_Factory ) {
		$this->_setWPLayer($WPLayer);
		$this->_setPostCollectionFactory( $ffPostCollection_Factory );
	}

	////////////////////////////////////////////////////////////////////////
	//
	//   Post types / $args[tax_query]
	//
	////////////////////////////////////////////////////////////////////////

	// More than one

	public function setNumberOfPosts( $numberOfPosts ) {
		$this->_selectArgs['posts_per_page'] = $numberOfPosts;
		return $this;
	}

	/**
	 * 
	 * @param string $post_type
	 * @return ffPostCollection
	 */
	public function getPostsByType( $post_type = 'any' ){
		$this->_selectArgs['post_type'] = $post_type;

		$_posts = $this->_getWPLayer()->get_posts( $this->_selectArgs );

		if( empty($_posts) ){
			return NULL;
		}

		$this->_selectArgs = array();

		return $this->_getPostCollectionFactory()->createPostCollection( $_posts );
	}

	public function getAll(){
		return $this->getPostsByType();
	}

	public function getAllPosts(){
		return $this->getPostsByType( 'post' );
	}

	public function getAllPages(){
		return $this->getPostsByType( 'page' );
	}

	// Single

	public function getPostByID( $ID ){
		$this->_selectArgs = array();
		return $this->_getPostCollectionFactory()->createPostCollectionItem(
			$this->_getWPLayer()->get_post( $ID )
		);
	}

	public function getPostByTypeSingle( $post_type = 'any' ){
		$ret = $this->getPostsByType( $post_type );
		if( defined('WP_DEBUG') and WP_DEBUG ){
			if( 1 < count($ret) ){
				echo 'Warning: '.__FILE__.' - line '.__LINE__.' returned more than 1 post type ['.$post_type.']<br />'."\n";
			}
		}

		if( 0 < count($ret) ){
			$param = $ret->current()->getWPPost();
			return $this->_getPostCollectionFactory()->createPostCollectionItem( $param );
		}else{
			return FALSE;
		}
	}

	public function getSingle(){
		return $this->getPostByTypeSingle();
	}

	public function getPostBySlug( $slug, $post_type = 'any' ){
		$this->_selectArgs['name'] = $slug;
		return $this->getPostByTypeSingle( $post_type );
	}

	public function getPost(){
		return $this->getPostByTypeSingle( 'post' );
	}

	public function getPage(){
		return $this->getPostByTypeSingle( 'page' );
	}

	////////////////////////////////////////////////////////////////////////
	//
	//   Taxonomies / $args[tax_query]
	//
	////////////////////////////////////////////////////////////////////////

	// Relation
	// - AND = must have all taxonomies (default)
	// - OR  = must have one of

	private function initTaxQueryFilter(){
		if( ! isSet( $this->_selectArgs['tax_query'] ) ){
			$this->_selectArgs['tax_query'] = array( 'relation' => 'AND' );
		}
		return $this;
	}

	public function setFilterRelation_OR(){
		$this->initTaxQueryFilter();
		$this->_selectArgs['tax_query']['relation'] = 'OR';
		return $this;
	}

	public function setFilterRelation_AND(){
		$this->initTaxQueryFilter();
		$this->_selectArgs['tax_query']['relation'] = 'AND';
		return $this;
	}

	// Taxonomies

	public function filterByTaxonomy( $ID, $taxonomy ){
		if( empty( $ID ) ){
			return;
		}

		if( empty( $taxonomy ) ){
			return;
		}

		if( is_int( $ID ) ){
			$this->_selectArgs['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => array( $ID ),
			);
		}else{
			$this->_selectArgs['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => array( $ID ),
			);
		}
		return $this;
	}

	public function filterByCategory( int $ID ){
		$this->initTaxQueryFilter();
		$this->filterByTaxonomy( $ID, 'category' );
		return $this;
	}

	public function filterByTag( int $ID ){
		$this->initTaxQueryFilter();
		$this->filterByTaxonomy( $ID, 'category' );
		return $this;
	}

	////////////////////////////////////////////////////////////////////////
	//
	//   getters / setters
	//
	////////////////////////////////////////////////////////////////////////


	/**
	 * 
	 * @var ffWPLayer
	 */
	private $_WPLayer = null;

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
	}



	/**
	 * 
	 * @var ffPostCollection_Factory
	 */
	private $_PostCollection_Factory = null;

	/**
	 * @return ffPostCollection_Factory
	 */
	protected function _getPostCollectionFactory() {
		return $this->_PostCollection_Factory;
	}
	
	/**
	 * @param ffPostCollection_Factory $PostCollection_Factory
	 */
	protected function _setPostCollectionFactory(ffPostCollection_Factory $PostCollection_Factory) {
		$this->_PostCollection_Factory = $PostCollection_Factory;
		return $this;
	}

}