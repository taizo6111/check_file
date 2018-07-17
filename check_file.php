<?php

date_default_timezone_set('Asia/Tokyo');

$url = "";
$IP = "";
$url = "";
$array = getFileList($url);
$param["to"] = "";
$param['from'] = "";
$param['body'] = "至急調査して下さい\n\n";
$param['subject'] = "【重要】 IP:" . $IP . " で改ざんの可能性あり";

if(count($array) > 0){
  foreach($array as $key => $val){
    $param['body'] .= "[" . $key . "]" . $val . "\n";
  }
    send_mail($param);
}

function getFileList($dir) {
    $pref = '/\.pdf$|\.gif$|\.png$|\.jpg$|\.jpeg$|\.bmp$/i';
    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
        return !in_array($file, array('.', '..'));
    });

    $list = array();
    foreach ($files as $file) {
        $fullpath = rtrim($dir, '/') . '/' . $file;
        if (is_file($fullpath)) {
          if(!preg_match($pref, $fullpath))
            $list[] = $fullpath;
        }
        if (is_dir($fullpath)) {
            $list = array_merge($list, getFileList($fullpath));
        }
    }

    return $list;
}

function send_mail($param){
  mb_language("Japanese");
  mb_internal_encoding('UTF-8');

  $to      = $param['to'];
  $from    = $param['from'];
  $body    = $param['body'];
  $subject = $param['subject'];

  mb_send_mail($to, $subject, $body, "from:$from");
}


?>
