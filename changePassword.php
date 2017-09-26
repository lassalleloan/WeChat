<?php 
    include('includes/include.php');
    
    extract(@$_POST);

    echo 'Old Password: '.$oldPassword.'<br>';
    echo 'New Password: '.$newPassword.'<br>';
    echo 'Confirm Password: '.$confirmPassword.'<br>';
?>