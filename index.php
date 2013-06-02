<?php

class API {

    private $json;

    public function run() {
        if (isset($_GET['type']) == FALSE) {
            $this->json['code'] = 99;
            echo json_encode($this->json);
            return;
        }

        if ($_GET['type'] == 'auth') {
            $this->auth();
        } elseif ($_GET['type'] == 'reg') {
            $this->reg();
        }elseif ($_GET['type'] == 'sent') {
            $this->sent_message(); 
        }



        echo json_encode($this->json);
    }

    private function auth() {
        if (isset($_GET['email']) && isset($_GET['password'])) {
            $hostname = 'localhost';
            $username = 'admin';
            $password = '';
            $db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
            mysql_select_db('messeger') or die('db not found');
// Формируем и отправляем запрос, результат запишется в $result
            $query = 'SELECT * FROM users WHERE email = \'' . $_GET['email'] . '\';';
            $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .
                            mysql_error() . ' query: ' . $sql);

            if (mysql_num_rows($result) == 1) {
                $info_user = mysql_fetch_assoc($result);
                if ($info_user['password'] != $_GET['password'])
                    $this->json['code'] = 4;
                else {
                    $this->json['code'] = 0;
                    $this->json['token'] = $info_user['token'];
                }
            } else {
                $this->json['code'] = 4;
            }

            mysql_close($db);
        } else {
            $this->json['code'] = 4;
        }
    }

    private function reg() {
        if (isset($_GET['name']) && isset($_GET['email']) && isset($_GET['password'])) {
            $md5 = md5($_GET['name']);

            $hostname = 'localhost';
            $username = 'admin';
            $password = '';
            $db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
            mysql_select_db('messeger') or die('db not found');
            $query = 'INSERT INTO users (email,password,name,token) VALUES(\'' . $_GET['email'] . '\',\'' . $_GET['password'] . '\',\'' . $_GET['name'] . '\',\'' . $md5 . '\');';
            echo $query;
            mysql_query($query) or trigger_error(mysql_errno() . ' ' .
                            mysql_error() . ' query: ' . $sql);

            $this->json['code'] = 0;
            $this->json['token'] = $md5;
            mysql_close($db);
        } else {
            $this->json['code'] = 1;
        }
    }

    private function sent_message() {
        if (isset($_GET['token']) && isset($_GET['message']) && isset($_GET['who'])) {
            $hostname = 'localhost';
            $username = 'admin';
            $password = '';
            $db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
            mysql_select_db('messeger') or die('db not found');
            $query = 'SELECT * FROM users WHERE token = \'' . $_GET['token'] . '\';';
            $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .
                            mysql_error() . ' query: ' . $sql);

            if (mysql_num_rows($result) == 1) {
                $info_user = mysql_fetch_assoc($result);
                date_default_timezone_set('UTC');
               $query = 'INSERT INTO '.$_GET['who'].'_table_message (message,date,who) VALUES (\''.$_GET['message'].'\',\''.date('1').'\',\''.$info_user['name'].'\');';
               echo $query;
            $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .
                            mysql_error() . ' query: ' . $sql);
            $this->json['code'] = 0;
            
            } else {
                $this->json['code'] = 4;
            } 
        }
    }

}

;

$api = new API();
$api->run();
?>