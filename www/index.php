<?php

$json['code'] = 99;

if (isset($_POST['type'])) {
    /*
     * Registration
     */
    if ($_POST['type'] == 'registration') {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {
            
        }
    }
    /*
     * AUTH
     */
    if ($_POST['type'] == 'auth') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            getToken($_POST['email'], $_POST['password']);
        }
    }
}

echo json_encode($json);

function getToken($email, $password) {
    $mysqli = new mysqli('127.0.0.1', 'root', '', 'messenger');
    if (mysqli_connect_errno()) {
        echo "Подключение невозможно: " . mysqli_connect_error();
    }
   echo "SELECT * FROM users WHERE email='".$email."',password='".$password."';";
   $query = $mysqli->query("SELECT * FROM users WHERE email='".$email."' password='".$password."';");
   print_r($query);
    $mysqli->close();
}

function registration($email, $password, $name) {
    
}

?>