<?php

// Local Host
$server = "localhost";
$user = "root";
$pass = "";
$db = "db_baraka";

// barakaph.com
// $server = "localhost";
// $user = "barakadotph";
// $pass = "Agik123!";
// $db = "db_baraka3";

$conn = mysqli_connect($server, $user, $pass, $db);

// Check error connection.
// if ($connect -> connection_error)
if (!$conn) {
	trigger_error("Database connection failed." . $conn -> connect_error, E_USER_ERROR);
}

?>