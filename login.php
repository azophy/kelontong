<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");

// <<< CEK SESSION >>>
//klo udah login, redirect ke homepage
if (isLoggedIn()) {
	header("location: ".$site_path."index.php");
}

//inisialisasi data
$pesan="";

// <<< PROSES METHOD POST >>>
if (!empty($_POST[action])) {
	if (empty($_POST[username]) || empty($_POST[password])) {
		$pesan = "Data ada yang kosong!";
	} else {
		$username=validasi($_POST[username]);
		$password=md5($_POST[password]);
		
		$query = "SELECT id, id_status, nama_lengkap FROM pengguna WHERE username='$username' AND password='$password'"; //echo $query;
		$proses = mysql_query($query);
		if (!$proses) {
			$pesan = "Operasi gagal!";
		} else  if (mysql_num_rows($proses) == 0) {
			$pesan = "Username atau Password tidak ditemukan!";
		} else {
			$hasil = mysql_fetch_array($proses);
			
			//inisialisasi session
			/*
			session_register("id");
			session_register("id_status");
			session_register("nama_lengkap");*/
			
			//isi nilai session
			$_SESSION[id] = $hasil[id];
			$_SESSION[id_status] = $hasil[id_status];
			$_SESSION[nama_lengkap] = $hasil[nama_lengkap];
			
			//redirect
			header("location: ".$site_path."index.php");
		} 
	} 
}

// <<< ATUR ALERT >>>
if (strlen($pesan) > 0) $pesan='<div style="width: 90%;" class="box">'.$pesan.'</div>';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?php echo $site_path; ?>design/style.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="./design/favicon.ico">
        <title><?php echo $site_title; ?></title>
    </head>
    <body>
		<div class="row">
			<div class="box" style="padding: 10px; width: 300px; margin: 10% 25% 45% 30%;">
				<?php echo $pesan; ?>
				<h3>Log In Form</h3>
				<form id="form_login" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="row">
						<label>User Name</label> <input type="text" name="username" />
					</div>
					<div class="row">
						<label>Password</label> <input type="password" name="password" />
					</div>
					<div class="row">
						<label>&nbsp;</label>
						<input type="submit" name="action" value="Log In" />
					</div>
				</form>
			</div>
			<style type="text/css">
				#form_login label { 
					width: 100px; 
				}
			</style>
		</div>
	</body>
</html>
