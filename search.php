<?php
ini_set('display_errors', 1);
require_once('rest_helper.php');

$consumerkey		=	"<consumerkey>";
$consumersecret		=	"<consumersecret>";

$requesttokenURL	=	"https://api.twitter.com/oauth/request_token";
$authorizeURL		=	"https://api.twitter.com/oauth/authorize";
$accesstokenURL		=	"https://api.twitter.com/oauth/access_token";
$callbackURL		=	"http://www.someurl.com/twitter.php";
$tweets_from		=	"looneydoodle"; // < your user >

$bearerToken = base64_encode(rawurlencode($consumerkey) . ":" . rawurlencode($consumersecret));


$authCall =	rest_helper(
	'https://api.twitter.com/oauth2/token',
	array("grant_type" => "client_credentials"),
	"POST",
	'json',
	array(
		"Authorization: Basic " . $bearerToken . " \r\n" .
		"Content-Type: application/x-www-form-urlencoded;charset=UTF-8 \r\n" .
		"grant_type=client_credentials"
	)
);

$access_token = $authCall->access_token;
$token_type = $authCall->token_type;

$tweets = rest_helper(
	'https://api.twitter.com/1.1/search/tweets.json',
	array(
		'count' => 100,
		'q' => 'placeiq'
	),
	"GET",
	'json',
	'Authorization: Bearer ' . $access_token
);
if (!empty($tweets)) {
	foreach ($tweets as $tweet) {
		echo $tweet->text . "\n";
	}
}