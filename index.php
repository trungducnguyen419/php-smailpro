<?php
require_once 'function.php';
setHeaderJson();
$type = strtolower(getDataFromGetPost('type'));
if ($type === 'list-domain-other') {
	printJson(array('statusCode' => 200, 'list_domain' => $array_domain_other, 'message' => 'OK'), 200);
} else if ($type === 'create-mail') {
	$username = strtolower(getDataFromGetPost('username'));
	$mail_type = strtolower(getDataFromGetPost('mail_type'));
	$domain = strtolower(getDataFromGetPost('domain'));
	if ($mail_type === 'other') {
		if (strlen($username) > 0 && strlen($username) < 6) printJson(array('statusCode' => 400, 'message' => 'The username must be at least 6 characters.'), 400);
		if (strlen($username) < 1) $username = 'random';
		if (strlen($domain) > 0 && (!in_array($domain, $array_domain_other) || $domain !== 'random')) printJson(array('statusCode' => 400, 'message' => 'Wrong domain.'), 400);
		if (strlen($domain) < 1 || $domain === 'random') $domain = $array_domain_other[array_rand($array_domain_other)];
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"'.$domain.'","username":"'.$username.'"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']), 400);
		$id = $json['items'];
		$email = getEmail($id, 'other', $domain, $username);
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'other', 'token' => $token, 'message' => 'OK'), 200);
	} else if ($mail_type === 'gmail') {
		if (strlen($username) > 0) printJson(array('statusCode' => 400, 'message' => 'This feature is only for premium accounts!'), 400);
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"gmail.com","server":"server-1","type":"alias","username":"random"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']), 400);
		$id = $json['items'];
		$email = getEmail($id, 'gmail');
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'gmail', 'token' => $token, 'message' => 'OK'), 200);
	} else if ($mail_type === 'googlemail') {
		if (strlen($username) > 0) printJson(array('statusCode' => 400, 'message' => 'This feature is only for premium accounts!'), 400);
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"googlemail.com","server":"server-1","type":"alias","username":"random"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']), 400);
		$id = $json['items'];
		$email = getEmail($id, 'googlemail');
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'googlemail', 'token' => $token, 'message' => 'OK'), 200);
	}
	if (strlen($mail_type) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty mail_type.'), 400);
	printJson(array('statusCode' => 400, 'message' => 'Wrong mail_type.'), 400); 
} else if ($type === 'get-new-token') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'), 400);
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'), 400);
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'), 400);
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'), 400);
	$new_token = getToken($token, $email);
	printJson(array('statusCode' => 200, 'new_token' => $new_token, 'message' => 'OK'), 200);
} else if ($type === 'get-list-mail') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'), 400);
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'), 400);
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'), 400);
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'), 400);
	$listmail = getListMail($token, $email);
	printJson(array('statusCode' => 200, 'list_mail' => replaceKey($listmail), 'message' => 'OK'), 200);
} else if ($type === 'read-mail') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	$message_id = getDataFromGetPost('message_id');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'), 400);
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'), 400);
	else if (strlen($message_id) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty message_id.'), 400);
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'), 400);
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'), 400);
	else if (strlen($message_id) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid message_id.'), 400);
	$token_message = getTokenMessageId($token, $email, $message_id);
	$body = readMail($token_message, $email, $message_id);
	printJson(array('statusCode' => 200, 'body' => $body, 'message' => 'OK'), 200);
}
printJson(array('statusCode' => 400, 'message' => 'Bad request'), 400);
?>