<?php

/**
 * options necessary for feeding twitter
 * 
 * @author FRESHFACE
 *
 */
class ffOptionsHolder_Twitter extends ffOptionsHolder {
	const SECTION_NAME = 'fw_twitter';
	public function getOptions() {
		$struct = $this->_getOnestructurefactory()
						->createOneStructure( ffOptionsHolder_Twitter::SECTION_NAME );
		
		$struct->startSection( ffOptionsHolder_Twitter::SECTION_NAME );
			$struct->addOption(ffOneOption::TYPE_TEXT, 'username', 'Username', '_freshface');
			$struct->addOption(ffOneOption::TYPE_TEXT, 'number-of-tweets', 'Number of Tweets', '5');
			$struct->addOption(ffOneOption::TYPE_TEXT, 'caching-time-in-minutes', 'Caching time in minutes', '60');
			
			// $this->_auth['consumerKey'], $this->_auth['consumerSecret'], $this->_auth['accessToken'], $this->_auth['accessTokenSecret']
			
			$struct->addOption(ffOneOption::TYPE_TEXT, 'consumer-key', 'Consumer Key');
			$struct->addOption(ffOneOption::TYPE_TEXT, 'consumer-secret', 'Consumer Secret');
			$struct->addOption(ffOneOption::TYPE_TEXT, 'access-token', 'Access Token');
			$struct->addOption(ffOneOption::TYPE_TEXT, 'access-token-secret', 'Access Token Secret');
		$struct->endSection();
			
		return $struct;
	}
}