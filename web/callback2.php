<html>
<head><title>PHP TEST</title></head>
<body>

<?php

$conn = "host=ec2-54-83-26-65.compute-1.amazonaws.com dbname=ec2-54-83-26-65.compute-1.amazonaws.com user=gopasxxhdasfak password=ab14f9f8cbd407f8e7c7c99d3d03ac82f3c35b9d7a141615a563adeb2dd964f4";
$link = pg_connect($conn);
if (!$link) {
    die('接続失敗です。'.pg_last_error());
}

print('接続に成功しました。<br>');

pg_set_client_encoding("git");

$result = pg_query('SELECT contents FROM botlog');
if (!$result) {
    die('クエリーが失敗しました。'.pg_last_error());
}

for ($i = 0 ; $i < pg_num_rows($result) ; $i++){
    $rows = pg_fetch_array($result, NULL, PGSQL_ASSOC);
    print('id='.$rows['id']);
    print(',name='.$rows['name'].'<br>');
}

$close_flag = pg_close($link);

if ($close_flag){
    print('切断に成功しました。<br>');
}

?>
<table width="80%" border="1">
 <tr>
  <th scope="col">ID</th>
  <th scope="col">メーカー</th>
 </tr>
 <?php
 while($table = mysql_fetch_assoc($recordset)) {
 ?>
 <tr>
  <td><?php print(htmlspecialchars($table['id'])); ?> </td>
  <td><?php print(htmlspecialchars($table['maker'])); ?> </td>
 </tr>
 <?php
 }
 ?>
 </table>
</body>
</html>