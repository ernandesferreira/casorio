<?php

class ffOptionsHolderFavicon extends ffOptionsHolder {

	public function getOptions() {

		$s = $this->_getOnestructurefactory()->createOneStructure( ffPluginFreshFaviconContainer::STRUCTURE_NAME );

		$s->startSection(ffPluginFreshFaviconContainer::STRUCTURE_NAME, ffOneSection::TYPE_NORMAL );

		$s->addElement( ffOneElement::TYPE_HTML, 'TYPE_HTML', '<div class="ff-favicon-admin-tab-basic ff-favicon-admin-tab-content">' );

				$s->addOption( ffOneOption::TYPE_TEXT, 'timestamp_suffix')
					->addParam( 'class', 'timestamp_suffix')
					->addParam( 'class', 'hidden');

				$s->addElement( ffOneElement::TYPE_PARAGRAPH, '', 'In order to display a Favicon, you need to upload an image below. If you need more control, you can select device-specific Favicons under the <strong>Advanced</strong> tab.' );

				$s->addElement( ffOneElement::TYPE_TABLE_START );


					$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', 'Basic Favicon');

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_basic', 'Select Favicon');
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '– Recommended size of the Basic Favicon is at least 310 x 310 px.');
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '– Some mobile devices will convert the transparent channel to solid black and apply rounded corners with drop shadow.');

				$s->addElement( ffOneElement::TYPE_TABLE_DATA_END );
				$s->addElement( ffOneElement::TYPE_TABLE_END );

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_BUTTON_PRIMARY, 'Save', 'Save Changes' );

		$s->addElement( ffOneElement::TYPE_HTML, 'TYPE_HTML', '</div><div class="ff-favicon-admin-tab-advanced ff-favicon-admin-tab-content">' );

				$s->addElement( ffOneElement::TYPE_PARAGRAPH, '', 'If you need more control than what the <strong>Basic Favicon</strong> tab offers, you can select any device-specific Favicons below.' );

				$s->addElement( ffOneElement::TYPE_TABLE_START );

					$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', 'General');

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_16x16', '16 x 16')
							->addParam('data-forced-width', 16)
							->addParam('data-forced-height', 16);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Default small favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_32x32', '32 x 32')
							->addParam('data-forced-width', 32)
							->addParam('data-forced-height', 32);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Default medium favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_48x48', '48 x 48')
							->addParam('data-forced-width', 48)
							->addParam('data-forced-height', 48);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Default large favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

					$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', 'Mobile');

						$s->addElement( ffOneElement::TYPE_PARAGRAPH, '', '<strong>Note:</strong> Some mobile devices will convert the transparent channel to solid black and apply rounded corners with drop shadow.' );
						$s->addElement( ffOneElement::TYPE_NEW_LINE, '', '' );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_196x196', '196 x 196')
							->addParam('data-forced-width', 196)
							->addParam('data-forced-height', 196);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Android Chrome M31+ favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_120x120', '120 x 120')
							->addParam('data-forced-width', 120)
							->addParam('data-forced-height', 120);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPhone iOS ≥ 7 Retina favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_152x152', '152 x 152')
							->addParam('data-forced-width', 152)
							->addParam('data-forced-height', 152);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPad iOS ≥ 7 Retina favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_114x114', '114 x 114')
							->addParam('data-forced-width', 114)
							->addParam('data-forced-height', 114);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPhone iOS ≤ 6 Retina favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_144x144', '144 x 144')
							->addParam('data-forced-width', 144)
							->addParam('data-forced-height', 144);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPad iOS ≤ 6 Retina favicon' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_60x60', '60 x 60')
							->addParam('data-forced-width', 60)
							->addParam('data-forced-height', 60);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPhone iOS ≥ 7 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_76x76', '76 x 76')
							->addParam('data-forced-width', 76)
							->addParam('data-forced-height', 76);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPad iOS ≥ 7 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_57x57', '57 x 57')
							->addParam('data-forced-width', 57)
							->addParam('data-forced-height', 57);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPhone iOS ≤ 6 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_72x72', '72 x 72')
							->addParam('data-forced-width', 72)
							->addParam('data-forced-height', 72);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'iPad iOS ≤ 6 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

					$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', 'Windows');

						$s->addOption( ffOneOption::TYPE_TEXT, 'favicon_144x144_bg', '', '#FFFFFF')
							->addParam('class', 'ff-default-wp-color-picker');
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Windows 8 tile background color' )
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_70x70', '70 x 70')
							->addParam('data-forced-width', 70)
							->addParam('data-forced-height', 70);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'IE11 Windows 8.1 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_150x150', '150 x 150')
							->addParam('data-forced-width', 150)
							->addParam('data-forced-height', 150);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'IE11 Windows 8.1 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_310x310', '310 x 310')
							->addParam('data-forced-width', 310)
							->addParam('data-forced-height', 310);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'IE11 Windows 8.1 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_310x150', '310 x 150')
							->addParam('data-forced-width', 310)
							->addParam('data-forced-height', 150);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'IE11 Windows 8.1 wide favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

					$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', 'Other devices');

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_96x96', '96 x 96')
							->addParam('data-forced-width', 96)
							->addParam('data-forced-height', 96);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Google TV favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

						$s->addOption( ffOneOption::TYPE_IMAGE, 'favicon_160x160', '160 x 160')
							->addParam('data-forced-width', 160)
							->addParam('data-forced-height', 160);
						$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Opera Speed Dial ≤ 12 favicon')
							->addParam('tag', 'span');
						$s->addElement( ffOneElement::TYPE_NEW_LINE );

				$s->addElement( ffOneElement::TYPE_TABLE_DATA_END );
				$s->addElement( ffOneElement::TYPE_TABLE_END );

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_BUTTON_PRIMARY, 'Save', 'Save Changes' );

		$s->addElement( ffOneElement::TYPE_HTML, 'TYPE_HTML', '</div>' );

		$s->endSection();

		return $s;
	}

}










