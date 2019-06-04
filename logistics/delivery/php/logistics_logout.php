<?php
session_start();

if(session_start()) {
	// clear session variables
	session_unset();
	// destroy the session
	session_destroy();

	header("Location: ../../index.php");
}

?>