<?php 
    include('includes/include.php');
    
    extract(@$_POST);

    echo 'id_User: '.$usersList.'<br>';
    echo 'Username: '.$usersUsername.'<br>';
    echo 'New Password: '.$userNewPassword.'<br>';
    echo 'Confirm Password: '.$userConfirmPassword.'<br>';
    echo 'Active: '.$userActive.'<br>';
    echo 'Role: '.$userRole.'<br>';
?>