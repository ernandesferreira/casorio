<?php

class ffLib_TwitterFeeder_OneTweet_Factory extends ffFactoryAbstract {
	public function createTweet( $tweetFromTwitter ) {
		$this->_getClassloader()->loadClass('ffLib_TwitterFeeder_OneTweet');
		$tweet = new ffLib_TwitterFeeder_OneTweet();
		
		$tweet->date = $tweetFromTwitter->created_at;
		$tweet->id = $tweetFromTwitter->id;
		$tweet->profileImage = $tweetFromTwitter->user->profile_image_url;
		$tweet->profileName = $tweetFromTwitter->user->name;
		$tweet->profileScreenName = $tweetFromTwitter->user->screen_name;
		$tweet->source = $tweetFromTwitter->source;
		$tweet->text = $tweetFromTwitter->text;
		
		return $tweet;
	}
}