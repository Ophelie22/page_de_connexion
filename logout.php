<?php

session_start();
// notre navigateur va creeer un e fois un id unique qui vas ns permettre des
// variables de session , on veux detruire cette variable avec session-unset
session_unset();
//on va dtruire notre ancien numero de session
// DETRUIT LE COOKIE
session_destroy();setcookie('auth', '', time()-1, '/', null, false, true);
header('location:index.php');
exit();
?>