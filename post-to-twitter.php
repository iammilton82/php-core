<?
// post to twitter function

function postToTwitter($message){
	require_once('twitter/twitteroauth/twitteroauth.php');
	$tConsumerKey       = 'xxxx';
	$tConsumerSecret    = 'xxxx';
	$tAccessToken       = 'xxxx';
	$tAccessTokenSecret = 'xxxx';
	$tweet = new TwitterOAuth($tConsumerKey, $tConsumerSecret, $tAccessToken, $tAccessTokenSecret);
	$msg = $tweet->post('statuses/update', array('status' => $message));
	if($msg){
		return true;
	} else {
		return false;
	}
}