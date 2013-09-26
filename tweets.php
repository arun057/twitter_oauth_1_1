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

// To access a list
// $list_name = '<list name>';
// $tweets_from = $list_name;
// $list = '?slug='.$list_name.'&owner_screen_name=<list_owner>&per_page=14&page=1&include_entities=false';

// $tweets = rest_helper(
// 	'https://api.twitter.com/1.1/lists/statuses.json',
// 	array(
// 		'slug' => $list_name,
// 		'owner_screen_name' => '<list_owner>',
// 		'per_page' => 14,
// 		'page' => 1,
// 		'include_entities' => false
// 	),
// 	"GET",
// 	'json',
// 	'Authorization: Bearer ' . $access_token
// );

/*
// Get user timeline
 */
$tweets = rest_helper(
	'https://api.twitter.com/1.1/statuses/user_timeline.json',
	array(
		'count' => 100,
		"screen_name" => $tweets_from
	),
	"GET",
	'json',
	'Authorization: Bearer ' . $access_token
);

// var_dump($tweets);
// die();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Tweets From : <?php echo $tweets_from; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="bootstrap.min.css" rel="stylesheet" media="screen">
	</head>
	<body>
	<div class="row">
		<?php
			if (!empty($tweets)) {
				foreach ($tweets as $tweet) { ?>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<img src="<?php echo $tweet->user->profile_image_url; ?>" />
							<?php echo $tweet->user->screen_name; ?>
							<p><?php echo humanTiming($tweet->created_at); ?> ago</p>
						</div>
						<div class="panel-body">
							<?php echo $tweet->text; ?>
						</div>
					</div>
				</div>
				<?php
				}
			}
		?>
	</div>
	</body>
</html>