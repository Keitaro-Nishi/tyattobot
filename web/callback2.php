<html>
<head><title>PHP TEST</title></head>
<body>

<?php

$conn = "host=ec2-54-83-26-65.compute-1.amazonaws.com dbname=ec2-54-83-26-65.compute-1.amazonaws.com user=gopasxxhdasfak password=ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4";
$link = pg_connect($conn);
if (!$link) {
	error_log(接続失敗です。);
}

error_log(接続に成功しました。);

pg_set_client_encoding("git");

$result = pg_query('SELECT contents FROM botlog');
if (!$result) {
	error_log(クエリーが失敗しました。);
}

for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    error_log($rows[USERID]);
    error_log($rows[CONTENTS]);
}

$close_flag = pg_close($link);

if ($close_flag){
	error_log(切断に成功しました。);
}

?>
<table width="80%" border="1">
 <tr>
  <th scope="col">USERID</th>
  <th scope="col">log</th>
 </tr>
 <?php
 while($table = mysql_fetch_assoc($recordset)) {
 ?>
 <tr>
  <td><?php print(htmlspecialchars($table['USERID'])); ?> </td>
  <td><?php print(htmlspecialchars($table['CONTENTS'])); ?> </td>
 </tr>
 <?php
 }
 ?>
 </table>
</body>
</html>