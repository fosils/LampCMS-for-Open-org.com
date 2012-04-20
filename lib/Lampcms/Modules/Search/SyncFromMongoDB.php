<?php
$mysql_host = 'localhost';
$mysql_db   = 'LAMPCMS';
$mysql_user = $argv[1];
$mysql_pass = $argv[2];

$dbh = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $dbh->prepare("INSERT INTO question_title (qid, title, q_body, url, intro, ts, uid, username, userlink, avtr, tags_html) " .
                      "VALUES (:qid, :title, :q_body, :url, :intro, FROM_UNIXTIME(:ts), :uid, :username, :userlink, :avtr, :tags_html);");
$stmt->bindParam(':qid',       $qid);
$stmt->bindParam(':title',     $title);
$stmt->bindParam(':q_body',    $q_body);
$stmt->bindParam(':url',       $url);
$stmt->bindParam(':intro',     $intro);
$stmt->bindParam(':ts',        $ts);
$stmt->bindParam(':uid',       $uid);
$stmt->bindParam(':username',  $username);
$stmt->bindParam(':userlink',  $userlink);
$stmt->bindParam(':avtr',      $avtr);
$stmt->bindParam(':tags_html', $tags_html);

$mongo = new Mongo();
$cursor = $mongo->LAMPCMS->QUESTIONS->find();
$count = 0;
foreach ($cursor as $question) {
    $count++;
    $qid       = $question['_id'];
    $title     = $question['title'];
    $q_body    = $question['b'];
    $url       = $question['url'];
    $intro     = $question['intro'];
    $ts        = $question['i_ts'];
    $uid       = $question['i_uid'];
    $username  = $question['username'];
    $userlink  = $question['ulink'];
    $avtr      = $question['avtr'];
    $tags_html = $question['tags_html'];

    $stmt->execute();
}
print "$count posts synced\n";
?>
