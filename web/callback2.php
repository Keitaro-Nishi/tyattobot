<?php
// error_log ( $conversation_id );
$accessToken = getenv ( 'LINE_CHANNEL_ACCESS_TOKEN' );

// ユーザーからのメッセージ取得
$json_string = file_get_contents ( 'php://input' );
$jsonObj = json_decode ( $json_string );

$type = $jsonObj->{"events"} [0]->{"message"}->{"type"};
$eventType = $jsonObj->{"events"} [0]->{"type"};
// メッセージ取得
$text = $jsonObj->{"events"} [0]->{"message"}->{"text"};
// ReplyToken取得
$replyToken = $jsonObj->{"events"} [0]->{"replyToken"};
// ユーザーID取得
$userID = $jsonObj->{"events"} [0]->{"source"}->{"userId"};

// メッセージ以外のときは何も返さず終了
if ($type != "text") {
	exit ();
}

$classfier = "12d0fcx34-nlc-410";
$workspace_id = "766caa32-cd4c-4103-bb83-5719c9996ecc";

// $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/".$classfier."/classify?text=".$text;
// $url = "https://gateway.watson-j.jp/natural-language-classifier/api/v1/classifiers/".$classfier."/classify";
$url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/" . $workspace_id . "/message?version=2017-04-21";

$username = "e6ab6b4a-5e21-4649-9286-c6e20c60abc4";
$password = "TyPTVSgRHbp5";

// $data = array("text" => $text);
$data = array (
		'input' => array (
				"text" => $text 
		) 
);

$jsonString = callWatson ();
$json = json_decode ( $jsonString, true );

$conversation_id = $json ["context"] ["conversation_id"];
$userArray [$userID] ["cid"] = $conversation_id;
$userArray [$userID] ["time"] = date ( 'Y/m/d H:i:s' );
// $lastConversationData [];

// データベースへの接続
$conn = "host=ec2-54-83-26-65.compute-1.amazonaws.com dbname=d9pf8qthde7brb user=gopasxxhdasfak
 password=ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4";
$link = pg_connect ( $conn );
if (! $link) {
	error_log ( '202接続に失敗' );
} else {
	error_log ( '204接続に成功' );
}

// cvsdataテーブルからデータの取得
$result = pg_query ( "SELECT dnode FROM cvsdata WHERE userid = '$userID'" );
$rows = pg_fetch_array ( $result, NULL, PGSQL_ASSOC );

if ($rows [dnode] == null) {
	error_log ( 214 );
	
	$data ["context"] = array (
			"conversation_id" => $conversation_id,
			"system" => array (
					"dialog_stack" => array (
							array (
									"dialog_node" => 'root' 
							) 
					),
					"dialog_turn_counter" => 1,
					"dialog_request_counter" => 1 
			) 
	);
} else {
	$data ["context"] = array (
			"conversation_id" => $conversation_id,
			"system" => array (
					"dialog_stack" => array (
							array (
									"dialog_node" => $rows [dnode] 
							) 
					),
					"dialog_turn_counter" => 1,
					"dialog_request_counter" => 1 
			) 
	);
}

error_log ( 245 );
error_log ( "dialog_node" );

// データベースの切断
pg_close ( $conn );

$jsonString = callWatson ();
// error_log($jsonString);
$json = json_decode ( $jsonString, true );

$mes = $json ["output"] ["text"] [0];
// $mes = $json["output"];



$response_format_text = [ 
		"type" => "text",
		"text" => $mes 
];

lineSend:
error_log ( $response_format_text );
$post_data = [ 
		"replyToken" => $replyToken,
		"messages" => [ 
				$response_format_text 
		] 
];

// データベースへの接続
$conn = "host=ec2-54-83-26-65.compute-1.amazonaws.com dbname=d9pf8qthde7brb user=gopasxxhdasfak
 password=ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4";
$link = pg_connect ( $conn );
if (! $link) {
	error_log ( '344接続に失敗' );
} else {
	error_log ( '346接続に成功' );
}

error_log ( $userID );
error_log ( $text );
error_log ( $mes );

// botlog テーブルへのデータ登録
$sql = "INSERT INTO botlog (userid, contents, return) VALUES ('$userID', '$text', '$mes')";
$result_flag = pg_query ( $sql );

// botlog テーブルからのデータの取得
$result = pg_query ( 'SELECT time, userid, contents FROM botlog ORDER BY no DESC LIMIT 1' );

if (! $result) {
	die ( 'クエリーが失敗しました。' . pg_last_error () );
}
$rows = pg_fetch_array ( $result, NULL, PGSQL_ASSOC );
error_log ( $rows ['time'] );
error_log ( $rows ['userid'] );
error_log ( $rows ['contents'] );

// データベースの切断
pg_close ( $conn );

/*
curl -v -H "Content-type: application/json" -d "{ \"api_version\":\"\",
\"session_id\":\"\", \"choice_id\":\"\", \"message\":\"\" }" -X POST -u
w2cuser:w2cuser "https://xx.front.mybluemix.net/w2c_classifier/api/webchat";
*/
$url = "https://xx.front.mybluemix.net/w2c_classifier/api/webchat";
$ch = curl_init ($url);
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		'Host: xx.front.mybluemix.net',
		'Content-Type: application/json; charset=UTF-8',
		'Authorization: BASIC dXNlcjpwYXNzd29yZA== '
) );
curl_setopt ($ch, CURLOPT_POSTFIELDS, array (
		
		
));
$result = curl_exec ( $ch );
curl_close ( $ch );

$ch = curl_init ( "https://api.line.me/v2/bot/message/reply" );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $post_data ) );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken 
) );
$result = curl_exec ( $ch );
curl_close ( $ch );
function makeOptions() {
	global $username, $password, $data;
	return array (
			CURLOPT_HTTPHEADER => array (
					'Content-Type: application/json' 
			),
			CURLOPT_USERPWD => $username . ':' . $password,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode ( $data ),
			CURLOPT_RETURNTRANSFER => true 
	);
}

curl_setopt_array ( $curl, $options );
$jsonString = curl_exec ( $curl );
$json = json_decode ( $jsonString, true );

$conversationId = $json ["context"] ["conversation_id"];
$dialogNode = $json ["context"] ["system"] ["dialog_stack"] [0] ["dialog_node"];
error_log ( $dialogNode );
// データベースへの接続
$conn = "host=ec2-54-83-26-65.compute-1.amazonaws.com dbname=d9pf8qthde7brb user=gopasxxhdasfak
 password=ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4";
$link = pg_connect ( $conn );
if (! $link) {
	error_log ( '407接続に失敗' );
} else {
	error_log ( '409接続に成功' );
}

// cvsdataテーブルでデータ変更

$result = pg_query ( "SELECT * FROM cvsdata WHERE userid = '$userID'" );
$rows = pg_fetch_array ( $result, NULL, PGSQL_ASSOC );
error_log ( $rows [userid] );
error_log ( $userID );

if (! $rows [userid] == null) {
	$sql = sprintf ( "UPDATE cvsdata SET  conversationid = '$conversationId', dnode = '$dialogNode' WHERE userid = '$userID'", pg_escape_string ( $conversationId, $dialogNode ) );
	$result_flag = pg_query ( $sql );
} else {
	$sql = "INSERT INTO cvsdata (userid, conversationid, dnode) VALUES ('$userID', '$conversationId', '$dialogNode')";
	$result_flag = pg_query ( $sql );
}

// データベースの切断
pg_close ( $conn );
function callWatson() {
	global $curl, $url, $username, $password, $data, $options;
	$curl = curl_init ( $url );
	
	$options = array (
			CURLOPT_HTTPHEADER => array (
					'Content-Type: application/json' 
			),
			CURLOPT_USERPWD => $username . ':' . $password,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode ( $data ),
			CURLOPT_RETURNTRANSFER => true 
	);
	
	curl_setopt_array ( $curl, $options );
	return curl_exec ( $curl );
}
