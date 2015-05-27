<?php

class ffShortcodeFactory extends ffFactoryAbstract {
	public function createShortcode( $className ) {
		$shortcode = new $className();
		
		return $shortcode;
	}
}