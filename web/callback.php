
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

// 画像
$json_string = file_get_contents ( 'php://input' );
$jsonObj = json_decode ( $json_string );

$replyToken = $jsonObj->{"events"} [0]->{"replyToken"};
$messageId = $jsonObj->{"events"} [0]->{"message"}->{"id"};


error_log ( $eventType );
if ($eventType == "follow") {
	$response_format_text = [
			"type" => "template",
			"altText" => "this is a buttons template",
			"template" => [
					"type" => "buttons",
					"thumbnailImageUrl" => "https://" . $_SERVER ['SERVER_NAME'] . "/gyosei.jpg",
					"title" => "行政市役所",
					"text" => "こんにちは。\n行政市のすいか太郎です。\n皆さんの質問にはりきってお答えしますよ～",
					"actions" => [
							[
									"type" => "postback",
									"label" => "LINEで質問",
									"data" => "action=qaline"
							],
							[
									"type" => "postback",
									"label" => "証明書",
									"data" => "action=shomei"
							],
							[
									"type" => "postback",
									"label" => "施設予約",
									"data" => "action=shisetsu"
							],
							[
									"type" => "postback",
									"label" => "ご利用方法",
									"data" => "action=riyo"
							]
					]
			]
	];
	goto lineSend;
}

if ($eventType == "postback") {
	$bData = $jsonObj->{"events"} [0]->{"postback"}->{"data"};
	if ($bData == 'action=qaline') {
		$response_format_text = [
				"type" => "text",
				"text" => "それでは、質問をお願いします。"
		];
		goto lineSend;
	}

	if ($bData == 'action=shomei') {
		$response_format_text = [
				"type" => "text",
				"text" => "証明書についてはこちらをごらんください。"
		];
		goto lineSend;
	}

	if ($bData == 'action=shisetsu') {
		$response_format_text = [
				"type" => "text",
				"text" => "施設予約についてはこちらをごらんください。"
		];
		goto lineSend;
	}

	if ($bData == 'action=riyo') {
		$response_format_text = [
				"type" => "text",
				"text" => "ご利用方法についてはこちらをごらんください。"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_1_1') {
		$response_format_text = [
				"type" => "text",
				"text" => "①○○地区、△△地区、□□地区ですね。\nその場合、最寄りの税務署は「行政第一税務署」になります。「行政第一税務署」の詳細はURLをご確認ください。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_1_2') {
		$response_format_text = [
				"type" => "text",
				"text" => "②●●地区、▲▲地区、■■地区ですね。\nその場合、最寄りの税務署は「行政第二税務署」になります。「行政第二税務署」の詳細はURLをご確認ください。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_1_3') {
		$response_format_text = [
				"type" => "text",
				"text" => "③Ａ地区、Ｂ地区、Ｃ地区ですね。\nその場合、最寄りの税務署は「行政第三税務署」になります。「行政第三税務署」の詳細はURLをご確認ください。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_1_4') {
		$response_format_text = [
				"type" => "text",
				"text" => "④あ地区、い地区、う地区ですね。\nその場合、最寄りの税務署は「行政第四税務署」になります。「行政第四税務署」の詳細はURLをご確認ください。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_2_1') {
		$response_format_text = [
				"type" => "text",
				"text" => "ありがとうございます。\n個人番号カードをお持ちでコンビニエンスストアでの証明書交付の利用申請がお済の方は、下記のコンビニエンスストアでも住民票の写しが取れますよ～\n\n・セブンイレブン\n・ローソン\n・ファミリーマート\n・サークルＫサンクス\n\nまた、コンビニエンスストアの証明交付サービスは、年末年始（12月29日～翌年1月3日）を除き、毎日6:30から23:00まで、ご利用いただけます。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_2_2') {
		$response_format_text = [
				"type" => "text",
				"text" => "個人番号カードを持っていればコンビニで住民票が発行できて便利ですよ。\n他に質問はありますか？"
		];
		goto lineSend;
	}

	if ($bData == 'action=uc_2_3') {
		$response_format_text = [
				"type" => "text",
				"text" => "もし、個人番号カードを持っていればコンビニで住民票が発行できて便利ですよ。\n他に質問はありますか？"
		];
		goto lineSend;
	}
}




	// 画像ファイルのバイナリ取得
	$ch = curl_init ( "https://api.line.me/v2/bot/message/" . $messageId . "/content" );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
			'Content-Type: application/json; charser=UTF-8',
			'Authorization: Bearer ' . $accessToken
	) );
	$result = curl_exec ( $ch );
	curl_close ( $ch );

	// 画像ファイルの作成
	$fp = fopen ( './img/test.jpg', 'wb' );

	if ($fp) {
		if (flock ( $fp, LOCK_EX )) {
			if (fwrite ( $fp, $result ) === FALSE) {
				error_log('ファイル書き込みに失敗しました') ;
			} else {
				error_log ($data . 'をファイルに書き込みました') ;
			}

			flock ( $fp, LOCK_UN );
		} else {
			eeror_log ('ファイルロックに失敗しました') ;
		}
	}

	fclose ( $fp );

	// そのまま画像をオウム返しで送信
	$response_format_text = [
			"type" => "image",
			"originalContentUrl" => "【画像ファイルのパス】/img/test.jpg",
			"previewImageUrl" => "【画像ファイルのパス】/img/test.jpg"
	];

	$post_data = [
			"replyToken" => $replyToken,
			"messages" => [
					$response_format_text
			]
	];

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


$classfier = "12d0fcx34-nlc-410";
$workspace_id = "4c2bcc67-db84-438e-b20d-c1d76e143a68";

$url = "https://gateway.watsonplatform.net/conversation/api/v1/workspaces/" . $workspace_id . "/message?version=2017-04-21";

$username = "3179e86b-8590-463c-b610-c5e75af4a424";
$password = "aeFxMqFHFRdG";

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

if ($mes == "usrChoise_1") {
	$response_format_text = [
			"type" => "template",
			"altText" => "this is a buttons template",
			"template" => [
					"type" => "buttons",
					"text" => "お調べしますので、あなたのお住いの地区名を下記から選択してください。",
					"actions" => [
							[
									"type" => "postback",
									"label" => "①○○地区、△△地区、□□地区",
									"data" => "action=uc_1_1"
							],
							[
									"type" => "postback",
									"label" => "②●●地区、▲▲地区、■■地区",
									"data" => "action=uc_1_2"
							],
							[
									"type" => "postback",
									"label" => "③Ａ地区、Ｂ地区、Ｃ地区",
									"data" => "action=uc_1_3"
							],
							[
									"type" => "postback",
									"label" => "④あ地区、い地区、う地区",
									"data" => "action=uc_1_4"
							]
					]
			]
	];
	goto lineSend;
}

if ($mes == "usrChoise_2") {
	$response_format_text = [
			"type" => "template",
			"altText" => "this is a buttons template",
			"template" => [
					"type" => "buttons",
					"text" => "住民票の写しは行政市役所本庁舎、行政第一支所、行政第二支所の窓口で発行できます。\n受付時間は、月曜日～金曜日の午前8時30分～午後5時です。\nちなみに個人番号カードはお持ちですか？",
					"actions" => [
							[
									"type" => "postback",
									"label" => "１．はい",
									"data" => "action=uc_2_1"
							],
							[
									"type" => "postback",
									"label" => "２．いいえ",
									"data" => "action=uc_2_2"
							],
							[
									"type" => "postback",
									"label" => "３．わからない",
									"data" => "action=uc_2_3"
							]
					]
			]
	];
	goto lineSend;
}

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
