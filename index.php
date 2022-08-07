<?php
require_once 'function.php';
setHeaderJson();
$type = strtolower(getDataFromGetPost('type'));
if ($type === 'list-domain-other') {
	printJson(array('statusCode' => 200, 'list_domain' => $array_domain_other, 'message' => 'OK'));
} else if ($type === 'create-mail') {
	$username = strtolower(getDataFromGetPost('username'));
	$mail_type = strtolower(getDataFromGetPost('mail_type'));
	$domain = strtolower(getDataFromGetPost('domain'));
	if ($mail_type === 'other') {
		if (strlen($username) > 0 && strlen($username) < 6) printJson(array('statusCode' => 400, 'message' => 'The username must be at least 6 characters.'));
		if (strlen($username) < 1) $username = 'random';
		if (strlen($domain) > 0 && (!in_array($domain, $array_domain_other) || $domain !== 'random')) printJson(array('statusCode' => 400, 'message' => 'Wrong domain.'));
		if (strlen($domain) < 1 || $domain === 'random') $domain = $array_domain_other[array_rand($array_domain_other)];
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"'.$domain.'","username":"'.$username.'"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']));
		$id = $json['items'];
		$email = getEmail($id, 'other', $domain, $username);
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'other', 'token' => $token, 'message' => 'OK'));
	} else if ($mail_type === 'gmail') {
		if (strlen($username) > 0) printJson(array('statusCode' => 400, 'message' => 'This feature is only for premium accounts!'));
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"gmail.com","server":"server-1","type":"alias","username":"random"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']));
		$id = $json['items'];
		$email = getEmail($id, 'gmail');
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'gmail', 'token' => $token, 'message' => 'OK'));
	} else if ($mail_type === 'googlemail') {
		if (strlen($username) > 0) printJson(array('statusCode' => 400, 'message' => 'This feature is only for premium accounts!'));
		$result = requestPost('https://smailpro.com/app/key', '{"domain":"googlemail.com","server":"server-1","type":"alias","username":"random"}');
		$json = json_decode($result, true);
		if ($json['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json['msg']));
		$id = $json['items'];
		$email = getEmail($id, 'googlemail');
		$token = getToken($id, $email);
		printJson(array('statusCode' => 200, 'email' => $email, 'type' => 'googlemail', 'token' => $token, 'message' => 'OK'));
	}
	if (strlen($mail_type) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty mail_type.'));
	printJson(array('statusCode' => 400, 'message' => 'Wrong mail_type.')); 
} else if ($type === 'get-new-token') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'));
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'));
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'));
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'));
	$new_token = getToken($token, $email);
	printJson(array('statusCode' => 200, 'new_token' => $new_token, 'message' => 'OK'));
} else if ($type === 'get-list-mail') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'));
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'));
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'));
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'));
	$listmail = getListMail($token, $email);
	printJson(array('statusCode' => 200, 'list_mail' => replaceKey($listmail), 'message' => 'OK'));
} else if ($type === 'read-mail') {
	$email = strtolower(getDataFromGetPost('email'));
	$token = getDataFromGetPost('token');
	$message_id = getDataFromGetPost('message_id');
	if (strlen($email) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty email.'));
	else if (strlen($token) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty token.'));
	else if (strlen($message_id) < 1) printJson(array('statusCode' => 400, 'message' => 'Empty message_id.'));
	else if (!isValidEmail($email)) printJson(array('statusCode' => 400, 'message' => 'Not valid email.'));
	else if (strlen($token) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid token.'));
	else if (strlen($message_id) < 10) printJson(array('statusCode' => 400, 'message' => 'Not valid message_id.'));
	$token_message = getTokenMessageId($token, $email, $message_id);
	$body = readMail($token_message, $email, $message_id);
	printJson(array('statusCode' => 200, 'body' => $body, 'message' => 'OK'));
}
printJson(array('statusCode' => 400, 'message' => 'Bad request'));
?>
