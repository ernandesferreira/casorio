<?php

class ffFrontendQueryIdentificator extends ffBasicObject {
	
	/**
	 * 
	 * @var ffWPLayer
	 */
	private $WPLayer = null;
	
	
	private $_singularType = null;
	private $_singularName = null;
	private $_singularId = null;
	
	private $_isSingular = false;
	
	
	private $_taxName = null;
	private $_taxId = null;
	private $_taxType = null;
	
	private $_isTaxonomy = false;
	
	public function __construct( ffWPLayer $WPLayer ) {
		$this->_setWPLayer($WPLayer);
		$this->_getWPLayer()->add_action('wp',array($this, 'actWp'));
	}

	
	private function _detectSingular( $wp_query ) {
		if( $wp_query->is_singular ) {
			$this->_isSingular = true;
			$this->_singularType = $wp_query->query_vars['post_type'];
			if( empty( $this->_singularType ) ) {
		
				if( $wp_query->is_page )
					$this->_singularType = 'page';
				else
					$this->_singularType = 'post';
			}
				
			$this->_singularName = $wp_query->query_vars['name'];
			$this->_singularId = $this->_getWPLayer()->get_queried_object_id();
			
			return true;
		}
		
		return false;
	}
	
	private function _detectTaxonomy( $wp_query ) {
		//var_dump( $wp_query );
		if( $wp_query->is_category ) {
			$this->_taxName = $wp_query->query_vars['category_name'];
			$this->_taxId = $wp_query->query_vars['cat'];
			$this->_taxType = 'category';
			$this->_isTaxonomy = true;

			return true;
		} else if ( $wp_query->is_tax ) {
			$this->_taxName = $wp_query->query_vars['term'];
			$this->_taxType = $wp_query->query_vars['taxonomy'];
			$this->_taxId = $this->_getWPLayer()->get_queried_object_id();
			$this->_isTaxonomy = true;
			
			return true;
		}
		
		return false;
	}
	
	public function actWp() {
		$wp_query = $this->_getWPLayer()->get_wp_query();

		if( $this->_detectSingular($wp_query) )
			true;//return;
		
		if( $this->_detectTaxonomy( $wp_query ) ) 
			true;//return;
		

		//var_dump($this->getTaxonomyId(), $this->getSingularId());
		
		
	/*
		
		- soubor
			- taxonomy ano
				- jmeno nebo cislo
				- jmeno nebo cislo
				- jmeno nebo cislo
			- taxonomy ne
				- jmeno nebo cislo
				- jmeno nebo cislo
				- jmeno nebo cislo
			- post type ano
				- jmeno nebo cislo
		*/	

		
		
		//die();
		
		
		// POST
		
		
		//die();
		//return;
		//is_page()
		//global $wp_query;
		//var_dump($wp_query);
		//die();
		//var_Dump( $wp_query );
		
		
		
		
		
		
		//var_dump( $wp_query);
		//var_dump( $this->is_taxonomy() );
		//global $wp_query;
		//var_dump( $wp_query );
		
		//vaR_dump( $wp_query,  $this );
		//die();
		//die();
		//var_dump( is_taxonomy() );
	}
	
/*******************************************************************************
/* CONDITIONS 
/******************************************************************************/
	public function isSingular() {
		return $this->_isSingular;
	}	
	
	public function isTaxonomy() {
		return $this->_isTaxonomy();
	}

	public function isPage() {
		
	}
	
	public function getTaxonomyId() {
		if( !$this->_isTaxonomy ) {
			return false;
		}
		return $this->_taxId;
	}
	
	public function getSingularId() {
		if( !$this->_isSingular ) {
			return false;
		}
		return $this->_singularId;
	}
	
/*******************************************************************************
/* SETTERS and GETTERS
/******************************************************************************/	
	/**
	 * 
	 * @return ffWPLayer
	 */
	protected function _getWPLayer() {
		return $this->_WPLayer;
	}
	
	/**
	 * 
	 * @param ffWPLayer $WPLayer
	 * @return ffFrontendQueryIdentificator
	 */
	protected function _setWPLayer(ffWPLayer $WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}
	
	
}