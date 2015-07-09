<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <?php include($site_path."design/meta.php"); ?>
    <body>
		<div id="wrapper">
			<?php include($site_path."design/page_header.php"); ?>
			<?php include($site_path."design/menubar.php"); ?>
			<div id="content">
				<h2>Selamat Datang!</h2>
				<p>Kelontong adalah sebuah software "Point-Of-Sales" atau software berbasis web yang didesain sesederhana mungkin, dan ditujukan untuk para penggiat Usaha Kecil dan Menengah (UKM) di Indonesia. Kelontong di luncurkan dengan lisensi GPL, yang berarti anda bisa menggunakannya secara gratis, dan memiliki kebebasan untuk merubah kode-kode program ini dengan bebas. Situs ini adalah situs demo untuk aplikasi kelontong.</p><br />
				<p>Untuk login ke situs ini, gunakan username: test dan password: test.</p><br />
				<p>Jika anda ingin mendapatkan informasi lebih lanjut tentang aplikasi ini, atau ingin mengunduh script aplikasi ini, silahkan mengunjungi halaman project aplikasi ini di <a href="https://sourceforge.net/projects/kelontong/files/">SourceForge</a> atau ke folder <a href="http://www.kelontonk.co.cc/dev/">http://www.kelontonk.co.cc/dev/</a></p><br />
				<p>Saat ini, aplikasi Kelontong baru mencapai tahap Alpha Version, yang berarti aplikasi ini masih dalam tahap pengembangan yang sangat intensif dan masih jauh dari sempurna. Walau bagaimanapun, anda bebas untuk mengunduh dan menggunakan aplikasi ini sesuai dengan lisensi yang kami gunakan. Namun pada akhirnya, kama sangat mengharapkan masukan dari anda semua yang bisa membuat aplikasi ini semakin baik. Jika anda punya pertanyaan, ide, saran, kritik, atau masukan-masukan membangun lainnya, silahkan kirimkan ke email saya di: azophy[at]gmail[dot]com, atau akun facebook saya di: <a href="facebook.com/abdurrahman.shofy">facebook.com/abdurrahman.shofy</a>, atau bisa juga dengan mengunjungi blog saya di: <a href="www.azophy.co.cc">www.azophy.co.cc</a>.</p><br />
				<p>Atas perhatian dan partisipasi anda semua, saya mengucapkan banyak sekali terima kasih.</p><br />
				<p>~Azophy~</p>

				<br />
				<?php
				if (!isLoggedIn()) {
				?>
				<div class="box" style="padding: 10px; width: 275px;">
					<h3>Log In Form</h3>
					<form id="form_login" method="POST" action="<?php echo $site_path; ?>login.php">
						<div class="row">
							<label>User Name</label>
							<input type="text" name="username" />
						</div>
						<div class="row">
							<label>Password</label>
							<input type="password" name="password" />
						</div>
						<div class="row">
							<label>&nbsp;</label>
							<input type="submit" name="action" value="Log In" />
						</div>
					</form>
				</div>
				<?php 
				} 
				?>
			</div>
			<style type="text/css">
				#form_login label { 
					width: 75px; 
				}
			</style>
			<?php include($site_path."design/page_footer.php"); ?>
		</div>
    </body>
</html>
