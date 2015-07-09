<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

//atur variabel paging
$n_data=countNData('pengguna'); 
$n_halaman=ceil($n_data/$n_data_halaman);

/*------------------------
	PROSES KIRIMAN DATA 
--------------------------*/
//inisialisasi data
$pesan="";

// <<< PROSES METHOD POST >>>
if (!empty($_POST[action])) {
	switch ($_POST[action]) {
		case 'save': // <<< SIMPAN DATA >>>
			if ($_POST[id] == 0) { // <<< SIMPAN DATA BARU >>>
				$elemen=array('username','password','nama_lengkap','id_status');
				$pesan=addNew('pengguna', $elemen);
			} else { // <<< UDATE DATA LAMA >>>
				if (empty($_POST[password])) { // <<< UDATE TANPA PASSWORD >>>
					$elemen=array('username','nama_lengkap','id_status');
					$pesan=updateOld('pengguna', $elemen, $_POST[id]);
				} else { // <<< UDATE DENGAN PASSWORD >>>
					$elemen=array('username','password','nama_lengkap','id_status');
					$pesan=updateOld('pengguna', $elemen, $_POST[id]);
				}
			}
		break;
		case 'delete': // <<< HAPUS DATA >>>
			$pesan=deleteData('pengguna',$_POST[id]);
		break;
		case 'new': // <<< KOSONGKAN FORM >>>
			header("location: ".$_SERVER['PHP_SELF']);
		break;
	}
	
	// <<< ATUR ALERT >>>
	if (strlen($pesan) > 0) $pesan='<div class="box">'.$pesan.'</div>';
	$tampil_baru=true;
} else if (empty($_GET[id])) {
	$tampil_baru=true;
}

/*-------------
	ISI FORM
---------------*/
$elemen=array('id', 'username','nama_lengkap','id_status'); 
$form_value=fillForm('pengguna', $elemen, $_GET[id]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <?php include($site_path."design/meta.php"); ?>
    <body>
		<div id="wrapper">
			<?php include($site_path."design/page_header.php"); ?>
			<?php include($site_path."design/menubar.php"); ?>
			<div id="content" class="row">
				<?php echo $pesan; ?>
				<div id="form_area" class="column">
					<h3>Data Pengguna <?php echo ($tampil_baru)?'Baru':'Lama'; ?></h3>
					<div class="box" style="width: 310px;">
						<form id="form_pengguna" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<input type="hidden" name="id" value="<?php echo $form_value[id]; ?>" />
							<div class="row">
								<label>User Name</label> <input type="text" name="username" value="<?php echo $form_value[username]; ?>" />
							</div>
							<div class="row">
								<label>Pass Word</label> <input type="password" name="password" value="" />
							</div>
							<div class="row">
								<label>Nama Lengkap</label> <input type="text" name="nama_lengkap" value="<?php echo $form_value[nama_lengkap]; ?>" />
							</div>
							<div class="row">
								<label>Status</label>
								<select name="id_status">
									<?php showComboBox('status',$form_value[id_status]); ?>
								</select>
							</div>
							<div class="row">
								<label>&nbsp;</label>
								<input type="submit" name="action" value="new" class="hidable" title="baru" />
								<input type="submit" name="action" value="save" title="simpan" />
								<input type="submit" name="action" value="delete" class="hidable" title="hapus" onclick="return confirm_delete('<?php echo $form_value[nama_lengkap]; ?>')" />
							</div>
						</form>
					</div>
				</div>
				<div id="table_area" class="column">
					<h3>List data</h3>
					<div class="box" style="width: 650px;">
						<form id="form_tabel" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div class="row">
								<label>Banyak data per halaman :</label> <input type="text" name="n_data_halaman" size="5" value="10" /> <input type="submit" value="Ganti!" />
							</div>
						</form>
					</div>
					<br />
					<div style="width: 660px; border: 1px solid #BFBFBF; overflow-y:none; overflow-x: scroll;">
						<table id="tabel_pengguna" class="sortable">
							<?php
							$query=	"SELECT pengguna.id AS id, 
										pengguna.username AS username, 
										pengguna.nama_lengkap AS nama, 
										status.nama AS nama_status 
									FROM pengguna, status 
									WHERE status.id=pengguna.id_status 
									LIMIT $posisi, $n_data_halaman"; //echo $query;
							$elemen=array('User Id' => 'username', 'Nama Pengguna' => 'nama', 'Status' => 'nama_status');
							showTable($query, $elemen, 'row_click');
							?>
						</table>
					</div>
					<p>Halaman: |<?php showPagingLink($halaman, $n_data_halaman, $n_halaman); ?></p>
					<p>Keterangan: klik pada judul tabel untuk mengurutkan data.</p>
				</div>
			</div>
			<script language="Javascript">
			function row_click(val) {
				window.location = '<?php echo $_SERVER['PHP_SELF']; ?>?n_data_halaman=<?php echo $n_data_halaman; ?>&halaman=<?php echo $halaman; ?>&id='+val;
			}
			function confirm_delete(val) {
				return confirm('Apakah anda benar-benar ingin menghapus pengguna: '+val+'?')
			}
			</script>
			<style type="text/css">
				#form_pengguna label { width: 100px; }
				#form_tabel label { width: 175px; }
				<?php
				if ($tampil_baru) {
				?>
				.hidable {
					display: none;
				}
				<?php } ?>
			</style>
			<?php include($site_path."design/page_footer.php"); ?>
		</div>
    </body>
</html>
