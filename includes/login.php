<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = hash('sha256', htmlspecialchars($_POST['password']));
    if ($email == 'albara.m.hassan@gmail.com' && $password == hash('sha256', htmlspecialchars('testtest'))) {
        echo $email;
        echo '<br/>';
        echo $password;
        echo '<script>
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 2000); 
      </script>';
    } else {
        $_SESSION['error'] = 'Invalid credentials. Please try again.';
        header('Location: ../index.php');
        exit();
    }
}