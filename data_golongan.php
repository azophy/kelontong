<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

//atur variabel paging
$n_data=countNData('golongan'); 
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
				$elemen=array('kode', 'nama', 'keterangan');
				$pesan=addNew('golongan', $elemen);
			} else { // <<< UDATE DATA LAMA >>>
				$elemen=array('kode', 'nama', 'keterangan');
				$pesan=updateOld('golongan', $elemen, $_POST[id]);
			}
		break;
		case 'delete': // <<< HAPUS DATA >>>
			$pesan=deleteData('golongan',$_POST[id]);
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
$elemen=array('id', 'kode', 'nama', 'keterangan');
$form_value=fillForm('golongan', $elemen, $_GET[id]);
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
					<h3>Data Golongan <?php echo ($tampil_baru)?'Baru':'Lama'; ?></h3>
					<div class="box" style="width: 320px;">
						<form id="form_golongan" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<input type="hidden" name="id" value="<?php echo $form_value[id]; ?>" />
							<div class="row">
								<label>Kode Golongan</label> <input type="text" name="kode" value="<?php echo $form_value[kode]; ?>" />
							</div>
							<div class="row">
								<label>Nama Golongan</label> <input type="text" name="nama" value="<?php echo $form_value[nama]; ?>" />
							</div>
							<div class="row">
								<label>Keterangan</label> <textarea name="keterangan"><?php echo $form_value[keterangan]; ?></textarea>
							</div>
							<div class="row">
								<label>&nbsp;</label>
								<input type="submit" name="action" value="new" class="hidable" title="baru" />
								<input type="submit" name="action" value="save" title="simpan" />
								<input type="submit" name="action" value="delete" class="hidable" title="hapus" onclick="return confirm_delete('<?php echo $form_value[nama]; ?>')" />
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
					<div style="border: 1px solid #BFBFBF; overflow-y:none; overflow-x: scroll;">
						<table id="tabel_golongan" class="sortable">
							<?php
							$query=	"SELECT golongan.id AS id, 
										golongan.nama AS nama, 
										golongan.kode AS kode, 
										golongan.keterangan AS keterangan
									FROM golongan
									LIMIT $posisi, $n_data_halaman"; //echo $query;
							$elemen=array('Kode Golongan' => 'kode', 'Nama golongan' => 'nama', 'Keterangan' => 'keterangan');
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
				return confirm('Apakah anda benar-benar ingin menghapus golongan: '+val+'?\nPerhatian: cek terlebih dahulu barang-barang yang termasuk ke dalam golongan ini.')
			}
			</script>
			<style type="text/css">
				#form_golongan label { width: 115px; }
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
