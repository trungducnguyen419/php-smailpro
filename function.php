<?php
error_reporting(E_ERROR);
$array_domain_other = array('donymails.com', 'stempmail.com', 'storegmail.com', 'instasmail.com', 'yousmail.com', 'woopros.com');
function setHeaderJson() {
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json; charset=utf-8');
}
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function requestGet($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$headers = array(
	   "x-rapidapi-ua: RapidAPI-Playground",
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36');
	$resp = curl_exec($curl);
	curl_close($curl);
	return $resp;
}
function getEmail($id, $type, $domain = '', $username = '') {
	$url_email_get = "https://public-sonjj.p.rapidapi.com/email/gm/get?key=$id&rapidapi-key=f871a22852mshc3ccc49e34af1e8p126682jsn734696f1f081&domain=gmail.com&username=random&server=server-1&type=alias";
	if ($type === 'other') $url_email_get = "https://public-sonjj.p.rapidapi.com/email/gd/get?key=$id&rapidapi-key=f871a22852mshc3ccc49e34af1e8p126682jsn734696f1f081&username=$username&domain=$domain";
	else if ($type === 'googlemail') $url_email_get = "https://public-sonjj.p.rapidapi.com/email/gm/get?key=$id&rapidapi-key=f871a22852mshc3ccc49e34af1e8p126682jsn734696f1f081&domain=googlemail.com&username=random&server=server-1&type=alias";
	$result_email = requestGet($url_email_get);
	$json_email = json_decode($result_email, true);
	if ($json_email['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json_email['msg']), 400);
	return $json_email['items']['email'];
}
function getToken($id, $email) {
	$timestamp = (round (time() / 60) * 60) - 86400;
	$result_token = requestPost('https://smailpro.com/app/key', '{"email":"'.$email.'","timestamp":'.$timestamp.'}', $id);
	$json_token = json_decode($result_token, true);
	if ($json_token['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json_token['msg']), 400);
	return $json_token['items'];
}
function getTokenMessageId($id, $email, $message_id) {
	$result_token = requestPost('https://smailpro.com/app/key', '{"email":"'.$email.'","message_id":"'.$message_id.'"}', $id);
	$json_token = json_decode($result_token, true);
	if ($json_token['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json_token['msg']), 400);
	return $json_token['items'];
}
function getListMail($id, $email) {
	$timestamp = (round (time() / 60) * 60) - 86400;
	$result_listmail = requestGet("https://public-sonjj.p.rapidapi.com/email/gd/check?key=$id&rapidapi-key=f871a22852mshc3ccc49e34af1e8p126682jsn734696f1f081&email=$email&timestamp=".$timestamp);
	$json_listmail = json_decode($result_listmail, true);
	if ($json_listmail['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json_listmail['msg']), 400);
	return $json_listmail['items'];
}
function readMail($id, $email, $message_id) {
	$result_mail = requestGet("https://public-sonjj.p.rapidapi.com/email/gd/read?key=$id&rapidapi-key=f871a22852mshc3ccc49e34af1e8p126682jsn734696f1f081&email=$email&message_id=$message_id");
	$json_mail = json_decode($result_mail, true);
	if ($json_mail['code'] !== 200) printJson(array('statusCode' => 400, 'message' => $json_mail['msg']), 400);
	return $json_mail['items']['body'];
}
function replaceKey($arr) {
	$json_key = json_encode($arr, JSON_UNESCAPED_UNICODE);
	$json_key = str_replace('"mid":"', '"message_id":"', $json_key);
	return json_decode($json_key);
}
function isNumber($value) {
	return preg_match('~[0-9]+~', $value);
}
function isValidBase64($s) {
	return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
}
function requestPost($url, $data, $token = '') {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$headers = array(
	   "Content-Type: application/json",
	);
	if (strlen($token) > 0) {
		$headers = array(
		   "Content-Type: application/json",
		   "X-XSRF-TOKEN: $token",
		);
	}
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36');
	$resp = curl_exec($curl);
	curl_close($curl);
	return $resp;
}
function printJson($arr, $code) {
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
	http_response_code($code);
	exit;
}
function getDataFromGetPost($name) {
	$str = $_GET[$name];
	if (empty($str)) $str = $_POST[$name];
	return urldecode($str);
}
?>