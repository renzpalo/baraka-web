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

$connect = mysqli_connect($server, $user, $pass, $db);

// Check error connection.
// if ($connect -> connection_error)
if (!$connect) {
	trigger_error("Database connection failed." . $connect -> connect_error, E_USER_ERROR);
}

// $prodImageUrl = "http://192.168.137.1/baraka/images/products/";

// Home
// $prodImageUrl = "http://192.168.254.111/baraka/images/products/";
// $sellerImageUrl = "http://192.168.254.111/baraka/images/sellers/";
// $carouselImageUrl = "http://192.168.254.111/baraka/images/carousel/";
// $categoryImageUrl = "http://192.168.254.111/baraka/images/categories/";
// $provinceImageUrl = "http://192.168.254.111/baraka/images/provinces/";
// $sellerImageUrl = "http://192.168.254.111/baraka/images/sellers/";
// $bannerImageUrl = "http://192.168.254.111/baraka/images/banners/";

// Home
// $prodImageUrl = "http://192.168.254.111/admin/images/products/";
// $sellerImageUrl = "http://192.168.254.111/admin/images/sellers/";
// $carouselImageUrl = "http://192.168.254.111/admin/images/carousel/";
// $categoryImageUrl = "http://192.168.254.111/admin/images/categories/";
// $provinceImageUrl = "http://192.168.254.111/admin/images/provinces/";
// $sellerImageUrl = "http://192.168.254.111/admin/images/sellers/";
// $bannerImageUrl = "http://192.168.254.111/admin/images/banners/";

// Pocket Wifi
// $prodImageUrl = "http://192.168.8.100/baraka/images/products/";
// $sellerImageUrl = "http://192.168.8.100/baraka/images/sellers/";
// $bannerImageUrl = "http://192.168.8.100/baraka/images/carousel/";
// $categoryImageUrl = "http://192.168.8.100/baraka/images/categories/";
// $provinceImageUrl = "http://192.168.8.100/baraka/images/provinces/";

$prodImageUrl = "http://192.168.8.100/baraka/images/products/";
$sellerImageUrl = "http://192.168.8.100/baraka/images/sellers/";
$carouselImageUrl = "http://192.168.8.100/baraka/images/carousel/";
$categoryImageUrl = "http://192.168.8.100/baraka/images/categories/";
$provinceImageUrl = "http://192.168.8.100/baraka/images/provinces/";
$sellerImageUrl = "http://192.168.8.100/baraka/images/sellers/";
$bannerImageUrl = "http://192.168.8.100/baraka/images/banners/";

// SMART Wifi
// $prodImageUrl = "http://192.168.137.1/baraka/images/products/";
// $sellerImageUrl = "http://192.168.137.1/baraka/images/sellers/";
// $bannerImageUrl = "http://192.168.137.1/baraka/images/carousel/";
// $categoryImageUrl = "http://192.168.137.1/baraka/images/categories/";
// $provinceImageUrl = "http://192.168.137.1/baraka/images/provinces/";

// barakaph.com
// $prodImageUrl = "http://barakaph.com/baraka/images/products/";
// $sellerImageUrl = "http://barakaph.com/baraka/images/sellers/";
// $bannerImageUrl = "http://barakaph.com/baraka/images/carousel/";
// $categoryImageUrl = "http://192.168.254.111/baraka/images/categories/";
// $provinceImageUrl = "http://192.168.254.111/baraka/images/provinces/";

// $prodImageUrl = "http://barakaph.com/images/products/";
// $sellerImageUrl = "http://barakaph.com/images/sellers/";
// $carouselImageUrl = "http://barakaph.com/images/carousel/";
// $categoryImageUrl = "http://barakaph.com/images/categories/";
// $provinceImageUrl = "http://barakaph.com/images/provinces/";
// $sellerImageUrl = "http://barakaph.com/images/sellers/";
// $bannerImageUrl = "http://barakaph.com/images/banners/";


?>