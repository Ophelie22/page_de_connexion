<?php
$db = new PDO('mysql:dbname=netflix;host=localhost;port=3306;charset=utf8',
'ophelie',
'pensee',
[
    // Option to display an error when SQL syntax is incorrect
    // On envoie une option supplémentaire pour indiquer à PHP de prendre les erreurs de MySQL transmises par PDO
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ]);
?>
