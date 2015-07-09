<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

//atur variabel paging
$n_data=countNData('barang'); 
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
				$elemen=array('kode', 'nama', 'id_golongan', 'id_satuan', 'barcode', 'harga_beli', 'persen_markup', 'stok');
				$pesan=addNew('barang', $elemen);
			} else { // <<< UDATE DATA LAMA >>>
				$elemen=array('kode', 'nama', 'id_golongan', 'id_satuan', 'barcode', 'harga_beli', 'persen_markup', 'stok');
				$pesan=updateOld('barang', $elemen, $_POST[id]);
			}
		break;
		case 'delete': // <<< HAPUS DATA >>>
			$pesan=deleteData('barang',$_POST[id]);
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
$elemen=array('id', 'kode', 'nama', 'id_golongan', 'id_satuan', 'barcode', 'harga_beli', 'persen_markup', 'stok');
$form_value=fillForm('barang', $elemen, $_GET[id]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <?php include($site_path."design/meta.php"); ?>
    <body onload="body_onLoad()">
		<div id="wrapper">
			<?php include($site_path."design/page_header.php"); ?>
			<?php include($site_path."design/menubar.php"); ?>
			<div id="content" class="row">
				<?php echo $pesan; ?>
				<div id="form_area" class="column">
					<h3>Data Barang <?php echo ($tampil_baru)?'Baru':'Lama'; ?></h3>
					<div class="box" style="width: 410px;">
						<form id="form_barang" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<input type="hidden" name="id" value="<?php echo $form_value[id]; ?>" />
							<div class="row">
								<label>Kode Barang</label> <input type="text" name="kode" value="<?php echo $form_value[kode]; ?>" />
							</div>
							<div class="row">
								<label>Kode Barcode</label> <input type="text" name="barcode" value="<?php echo $form_value[barcode]; ?>" />
							</div>
							<div class="row">
								<label>Nama Barang</label> <input type="text" name="nama" value="<?php echo $form_value[nama]; ?>" />
							</div>
							<div class="row">
								<label>Kode Golongan</label>
								<select name="id_golongan" id="id_golongan" onchange="id_gol_onChange(this.value)">
									<?php showComboBox('golongan',$form_value[id_golongan]); ?>
								</select>
								<span id="nama_golongan" class="ajax_label"></span>
							</div>
							<div class="row">
								<div class="column" style="width: 200px;">
									<label>Harga Beli</label> <input type="text" name="harga_beli" style="width: 75px;" value="<?php echo $form_value[harga_beli]; ?>" />
								</div>
								<div class="column">
									<label>Persen Markup</label> <input type="text" name="persen_markup" style="width: 40px;" value="<?php echo $form_value[persen_markup]; ?>" />
								</div>
							</div>
							<div class="row">
								<div class="column" style="width: 200px;">
									<label>Satuan</label>
									<select name="id_satuan">
										<?php showComboBox('satuan',$form_value[id_satuan]); ?>
									</select>
								</div>
								<div class="column">
									<label>Jumlah Stok</label> <input type="text" name="stok" style="width: 40px;" value="<?php echo $form_value[stok]; ?>" />
								</div>
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
				<div id="table_area" class="column" style="width: 550px;">
					<h3>List data</h3>
					<div class="box" style="width: 550px;">
						<form id="form_tabel" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div class="row">
								<label>Banyak data per halaman :</label> <input type="text" name="n_data_halaman" size="5" value="10" /> <input type="submit" value="Ganti!" />
							</div>
						</form>
					</div>
					<br />
					<div style="width: 550px; border: 1px solid #BFBFBF; overflow-y:none; overflow-x: scroll;">
						<table id="tabel_barang" class="sortable">
							<?php
							$query=	"SELECT barang.id AS id, 
										barang.nama AS nama, 
										barang.kode AS kode, 
										barang.harga_beli AS harga_beli,
										barang.persen_markup AS persen_markup,
										barang.stok AS stok,
										satuan.nama AS nama_satuan,
										golongan.nama AS nama_golongan
									FROM barang, satuan, golongan
									WHERE satuan.id = barang.id_satuan AND golongan.id = barang.id_golongan 
									LIMIT $posisi, $n_data_halaman"; //echo $query;
							$elemen=array('Kode barang' => 'kode', 'Nama barang' => 'nama', 'Nama golongan' => 'nama_golongan', 'Harga beli' => 'harga_beli', 'Persen Markup' => 'persen_markup', 'Satuan' => 'nama_satuan', 'Jumlah stok' => 'stok');
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
			function id_gol_onChange(val) {
				document.getElementById('nama_golongan').innerHTML = lookup('<?php echo $site_path; ?>ajax_handler.php?action=ComboNamaGol&id='+val);
			}
			function body_onLoad() {
				id_gol_onChange(document.getElementById('id_golongan').value);
			}
			function confirm_delete(val) {
				return confirm('Apakah anda benar-benar ingin menghapus barang: '+val+'?')
			}
			</script>
			<style type="text/css">
				#form_barang label { width: 100px; }
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
