<?php
//sesion management
session_start();

//site info
$site_path="./";
$site_title="Kelontonk Web Based Retail Software";
$site_headline="Welcome to Kelontong Version Alpha 10-7-2011";
$site_tagline="Easy, Simple, Elegance, yet Powerfull";

//database info
//kelontonk.co.cc connection info
/*$db_host = "mysql13.000webhost.com";
$db_name = "a8244316_indo";
$db_user = "a8244316_indo";
$db_password = "h1d4y4hx.c0m!";*/

//localhost (development) connection info
$db_name="retail3";
$db_host="localhost";
$db_user="root";
$db_password="";

mysql_connect($db_host, $db_user, $db_password);
mysql_select_db($db_name);

//transaction info
$persen_pajak=10;

//inisialisasi paging
$n_data_halaman=10; 
if (!empty($_GET[n_data_halaman]) && ($_GET[n_data_halaman])) 
	$n_data_halaman=$_GET[n_data_halaman];
	
$halaman=1;
if (!empty($_GET[halaman]) && ($_GET[halaman])) 
	$halaman=$_GET[halaman];
	
$posisi = ($halaman-1) * $n_data_halaman;
$no=$posisi+1;

?>
