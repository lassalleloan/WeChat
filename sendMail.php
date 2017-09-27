<?php     
extract(@$_POST);


//TODO: format de date
echo 'Date: '.date('Y-m-d').'T'.date('h:i:s').'<br>';
echo 'From: '.$from.'<br>';
echo 'To: '.$to.'<br>';
echo 'Subject: '.$subject.'<br>';
echo 'Mail body: '.$body.'<br>';
?>