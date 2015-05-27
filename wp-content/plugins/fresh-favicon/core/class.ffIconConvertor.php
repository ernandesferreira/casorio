<?php

class ffIconConvertor extends ffBasicObject {

	var $_images = array();

	protected $_ffFileSystem;

	protected $_ffDataStorage_Cache;

	function __construct( ffFileSystem $ffFileSystem, ffDataStorage_Cache $ffDataStorage_Cache ){
		$this->_setFileSystem($ffFileSystem);
		$this->_setDataStorage_Cache($ffDataStorage_Cache);
	}

	private function _adjustNewImage($newImage){
		imagecolortransparent( $newImage, imagecolorallocatealpha( $newImage, 0, 0, 0, 127 ) );
		imagealphablending( $newImage, false );
		imagesavealpha( $newImage, true );

		return $newImage;
	}

	private function _copyImage( $new, $old, $size, $width, $height ){
		imagecopyresampled( $new, $old, 0, 0, 0, 0, $size, $size, $width, $height );
	}

	private function _getImgDimensionsPath( $path ){
		$dim = getimagesize( $path );
		$result = new stdClass();
		$result->width = $dim[0];
		$result->height = $dim[1];
		return $result;
	}

	private function _getImgDimensionsResource( $resource ){
		$result = new stdClass();
		$result->width = imagesx($resource);
		$result->height = imagesy($resource);
		return $result;
	}

	private function _getNewSquareImage( $size ){
		return imagecreatetruecolor($size, $size);
	}

	public function addImage( $oneSize, $filePath ){

		$oldImage = $this->_getImageResource( $filePath );
		$newImage = $this->_getNewSquareImage($oneSize);
		$newImageAdjusted = $this->_adjustNewImage($newImage);
		$imageSize = $this->_getImgDimensionsPath($filePath);
		$this->_copyImage($newImageAdjusted, $oldImage, $oneSize, $imageSize->width, $imageSize->height);
		$newImageAdjustedDimensions = $this->_getImgDimensionsResource( $newImageAdjusted );
		$this->_insertImage( $newImageAdjusted, $newImageAdjustedDimensions );

		return true;
	}

	public function createWinXML( $timestamp_suffix, $img_to_png_filepaths, $background){

		$DS_Cache = $this->_getDataStorage_Cache();

		$content = '';
		$content .= '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$content .= '<browserconfig>' . "\n";
		$content .= '<msapplication>' . "\n";
		$content .= '<tile>' . "\n";

		$icon_settings = array(
			'favicon_70x70'   => '<square70x70logo src="%s"/> <!-- IE11 Windows 8.1 favicon -->' . "\n",
			'favicon_150x150' => '<square150x150logo src="%s"/> <!-- IE11 Windows 8.1 favicon -->' . "\n",
			'favicon_310x310' => '<square310x310logo src="%s"/> <!-- IE11 Windows 8.1 favicon -->' . "\n",
			'favicon_310x150' => '<wide310x150logo src="%s"/> <!-- IE11 Windows 8.1 wide favicon -->' . "\n",
		);

		foreach ($icon_settings as $key => $value){
			if( ! empty($img_to_png_filepaths[ $key ]) ){
				$url = $DS_Cache->getOptionUrl( ffPluginFreshFaviconContainer::STRUCTURE_NAME, $key . '--' . $timestamp_suffix, 'png' );
				$content .= sprintf( $value, $url ) ;
			}
		}

		if( !empty( $options[ 'favicon_144x144_bg' ] ) ){
			echo '<meta name="msapplication-TileColor" content="'.$options[ 'favicon_144x144_bg' ].'" >' . ' <!-- IE10 Windows 8.0 favicon -->' . "\n";
		}

		if( !empty($background) ){
			$content .= '<TileColor>'.$background.'</TileColor>';
		}
		$content .= '</tile>';
		$content .= '</msapplication>';
		$content .= '</browserconfig>';

		$this->_getDataStorage_Cache()->setOption(
			ffPluginFreshFaviconContainer::STRUCTURE_NAME
			, 'browserconfig--' . $timestamp_suffix
			, $content
			, 'xml'
		);
	}

	public function savePNG( $size, $imgFile, $timestamp_suffix ){
		if( false === ( $data = $this->_getFileSystem()->getContents( $imgFile ) ) )
			return false;

		$_size = str_replace('favicon_', '', $size);
		list( $new_width, $new_height ) = explode('x', $_size);

		$png_data = imagecreatefromstring( $data );
		if( FALSE === $png_data ) return false;

		$width = imagesx($png_data);
		$height = imagesy($png_data);

		if( ($new_height == $height) and ($new_width == $width) and ( strtolower( substr($imgFile, -4) ) == '.png' ) ){

		}else{
			$image_p = imagecreatetruecolor($new_width, $new_height);

			imagealphablending($image_p, false);
			imagesavealpha($image_p,true);
			$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			imagefilledrectangle($image_p, 0, 0, $new_width, $new_height, $transparent);

			if( FALSE === $image_p ) return false;

			$dst_x = 0;
			$dst_y = 0;

			if( $new_width * $height != $width * $new_height ){
				// Bad ratio;
				if( $new_width > $width * ( $new_height / $height )  ){
					$dst_x = ceil( ( $new_width - $width * ( $new_height / $height ) ) / 2 );
				}

				if( $new_height > $height * ( $new_width / $width )  ){
					$dst_y = ceil( ( $new_height - $height * ( $new_width / $width ) ) / 2 );
				}
			}

			imagecopyresampled($image_p, $png_data, $dst_x, $dst_y, 0, 0, $new_width - 2*$dst_x, $new_height- 2*$dst_y, $width, $height);
			imagedestroy($png_data);

			ob_start();
			imagepng($image_p);
			$data = ob_get_clean();
			imagedestroy($image_p);
		}

		$this->_getDataStorage_Cache()->setOption(
			ffPluginFreshFaviconContainer::STRUCTURE_NAME
			, $size . '--' . $timestamp_suffix
			, $data
			, 'png'
		);

		return true;
	}


	function saveICO( $timestamp_suffix ){

		if( false === ( $data = $this->_getIcoData() ) )
			return false;

		$this->_getDataStorage_Cache()->setOption(
			ffPluginFreshFaviconContainer::STRUCTURE_NAME
			, 'icon' . $timestamp_suffix
			, $data
			, 'ico'
		);

		return true;
	}

	private function _getIcoData(){
		if( ! is_array( $this->_images ) || empty( $this->_images ) )
			return false;

		$data = pack( 'vvv', 0, 1, count( $this->_images ) );
		$pixel_data = '';

		$icon_dir_entry_size = 16;

		$offset = 6 + ( $icon_dir_entry_size * count( $this->_images ) );

		foreach ( $this->_images as $image ){
			$data .= pack( 'CCCCvvVV', $image['width'], $image['height'], $image['color_palette_colors'], 0, 1, $image['bits_per_pixel'], $image['size'], $offset );
			$pixel_data .= $image['data'];

			$offset += $image['size'];
		}

		$data .= $pixel_data;

		return $data;
	}

 	private function _prepareImage( $image, $dimensions ){
		$pixel_data = array();
		$pixel_opacity = array();

		for( $posY = $dimensions->height -1; $posY >= 0; $posY-- ){
			for( $posX = 0; $posX < $dimensions->width; $posX++ ){
				$pix = $this->_getImageColorAtSomePixel( $image, $posX, $posY);
				$alpha = $this->_calculateAlpha($pix);
				$pix = $this->_getPixelRGBA($pix, $alpha);
				$pixel_data[] = $pix;
				$opacity = ( $alpha <= 127 ) ? 1 : 0;
				if( ( ( $posX + 1 ) % 32 ) == 0 ){
					$pixel_opacity[] = $opacity;
				}
			}

			if( ( $posX % 32 ) > 0 ){
				$pixel_opacity[] = 0;
			}
		}
		return array( $pixel_data, $pixel_opacity );
 	}

	private function _getImageColorAtSomePixel( $image, $posX, $posY ){
		return imagecolorat( $image, $posX, $posY );
	}

	private function _calculateAlpha( $color ){
		$alpha = ( $color & 0x7F000000 ) >> 24;
		$alpha = ( 1 - ( $alpha / 127 ) ) * 255;
		$alpha = ceil( $alpha );
		return $alpha;
	}

 	private function _getPixelRGBA($pix, $alpha){
 		$pix = $pix & 0xFFFFFF;
 		// White is transformed to transparent somehow
 		if( 0xFFFFFF == $pix ){
 			$pix = 0xFFFEFF;
 		}

 		$pix = $pix + ( $alpha * 0x1000000 );

 		return $pix;
 	}

	private function _insertImage( $image, $dimensions ){

		list( $pixel_data, $pixel_opacity ) = $this->_prepareImage( $image, $dimensions );

		$color_mask_size = $dimensions->width * $dimensions->height * 4;
		$opacity_mask_size = ( ceil( $dimensions->width / 32 ) * 4 ) * $dimensions->height;

		$dataNew = pack( 'VVVvvVVVVVV', 40, $dimensions->width, ( $dimensions->height * 2 ), 1, 32, 0, 0, 0, 0, 0, 0 );

		foreach ( $pixel_data as $color ){
			$dataNew .= pack( 'V', $color );
		}

		foreach ( $pixel_opacity as $opacity ){
			$dataNew .= pack( 'N', $opacity );
		}

		$image = array(
				'width'                => $dimensions->width,
				'height'               => $dimensions->height,
				'color_palette_colors' => 0,
				'bits_per_pixel'       => 32,
				'size'                 => 40 + $color_mask_size + $opacity_mask_size,
				'data'                 => $dataNew,
		);

		$this->_images[] = $image;
	}

	private function _getImageResource( $file ){
		if( !function_exists( 'imagecreatefromstring' ) ){
			throw new Exception('Function IMAGECREATEFROMSTRING does not exists!');
		}

		$imageSource = $this->_getFileSystem()->getContents( $file );
		$resource = imagecreatefromstring( $imageSource );

		if( FALSE === $resource ){
			echo '<div class="error"><p>Unable to load image from '.$file.'</p></div>';
		}

		return $resource;

	}


	/**
	 *
	 * @return ffFileSystem
	 */
	protected function _getFileSystem(){
		return $this->_ffFileSystem;
	}

	/**
	 *
	 * @param ffFileSystem $_ffFileSystem
	 */
	protected function _setFileSystem(ffFileSystem $_ffFileSystem){
		$this->_ffFileSystem = $_ffFileSystem;
		return $this;
	}

	/**
	 *
	 * @return ffDataStorage_Cache
	 */
	protected function _getDataStorage_Cache(){
		return $this->_ffDataStorage_Cache;
	}

	/**
	 *
	 * @param ffDataStorage_Cache $_ffDataStorage_Cache
	 */
	protected function _setDataStorage_Cache(ffDataStorage_Cache $_ffDataStorage_Cache){
		$this->_ffDataStorage_Cache = $_ffDataStorage_Cache;
		return $this;
	}



}









