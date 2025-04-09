<?php

session_start();
session_destroy();

if(!isset($_SESSION['kadi'])){
    echo 'giriş yetkiniz yoktur';
}

?>