<?php

require_once("config.php");
require_once(WWW_DIR."/lib/postprocess.php");

$db = new DB();
$query = "SELECT count(searchname), ID from releases use index (ix_releases_categoryID) where musicinfoID IS NULL and categoryID in ( select ID from category where parentID = 3000 );";

$i=1;
while($i=1)
{
  $result = mysql_query($query);

  if (empty($result)) {
    $result = $db->queryDirect($query);
    if (empty($result)) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
    }
  }

  while ($row = mysql_fetch_assoc($result)) {
    $count = $row['count(searchname)'];
  }
  if ($count > 0) {
    $postprocess = new PostProcess(true);
    $postprocess->processMusic();
  } else {
    printf("MusicPr : Processing $count audio releases\n");
    sleep(15);
  }
}

mysql_free_result($result);

?>

