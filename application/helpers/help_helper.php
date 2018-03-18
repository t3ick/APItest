<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 13/03/2018
 * Time: 16:10
 */
function connect()
{
    $servername = 'localhost';
    $username = 'root';
    $password = 'root';
    $db = 'etna_crowdin';
    $conn = mysqli_connect($servername, $username, $password, $db);
    if (!$conn) {
        die ("connection fail" . mysqli_connect_error());
    }
    echo "Connected successfully";
}