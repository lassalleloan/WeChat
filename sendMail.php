<?php     
extract(@$_POST);

echo 'Username: '.$mailUsername.'<br>';
echo 'Subject: '.$mailSubject.'<br>';
echo 'Mail body: '.$mailBody.'<br>';
?>