<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

/*------------------------
	PROSES KIRIMAN DATA 
--------------------------*/
//inisialisasi data
$pesan="";

// <<< PROSES METHOD POST >>>
if (!empty($_POST[action])) {
	switch ($_POST[action]) {
		case 'save': // <<< SIMPAN DATA TRANSAKSI >>>			
			//INSERT data transaksi
			$elemen=array(	'id_user' => $_SESSION[id], 
							'id_pemasok' => $_POST[id_pemasok],
							'waktu' => (date('Y-m-d').' '.date('H:i:s')),
							'total' => $_POST[total_akhir] ); 
			$pesan=addNew('pembelian_umum', $elemen, true);
			if (strlen($pesan) > 0) break;
			$id_transaksi = getInsertedId(); //dapet ID terakhir
			
			//INSERT rincian data transaksi
			$id_barang = $_POST[id_barang];
			$jml_barang = $_POST[jml_barang];
			$n_barang = count($id_barang);
			for ($i=0; $i<$n_barang; $i++) {
				$elemen = array('id_pembelian_umum' => $id_transaksi, 'id_barang' => $id_barang[$i], 'jumlah' => $jml_barang[$i]);
				$pesan = addNew('pembelian_detail', $elemen, true);
				if (strlen($pesan) > 0) break;
			}
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <?php include($site_path."design/meta.php"); ?>
    <body onload="body_onLoad()">
		<div id="wrapper">
			<?php include($site_path."design/menubar.php"); ?>
			<div id="content" class="row">
				<?php echo $pesan; ?>
				<form id="form_transaksi_pembelian" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="row">
						<div id="form_data_pemasok" class="column">
							<h3>Data Transaksi</h3>
							<div class="box" style="width: 320px;">
								<div class="row">
									<label>Tanggal Transaksi</label> <input type="text" id="tanggal" disabled value="" />
								</div>
								<div class="row">
									<label>Kode pemasok</label>
									<select name="id_pemasok" id="id_pemasok" tabindex=1 onchange="id_pemasok_onChange(this.value)">
										<?php showComboBox('pemasok',0, 'id', 'kode'); ?>
									</select>
								</div>
								<div class="row">
									<label>Nama Pemasok</label> <input type="text" id="nama_pemasok" disabled value="<?php echo $form_value[nama]; ?>" />
								</div>
								<div class="row">
									<label>Alamat Pemasok</label>
									<textarea id="alamat_pemasok" disabled></textarea>
								</div>
							</div>
						</div>
						<div id="form_barang" class="column" style="width: 550px;">
							<h3>Data Barang</h3>
							<div class="box" style="width: 650px;">
								<input type="hidden" id="id_barang_temp" />
								<div class="row">
									<div class="column" style="width: 300px;">
										<label>Barcode</label> <input type="text" id="barcode" tabindex=2 autocomplete="off" onblur="barcode_nama_onblur(this)" value="" />
									</div>
									<div class="column col2">
										<label>Kode Barang</label> <input type="text" id="kode_barang" tabindex=3 autocomplete="off" onblur="barcode_nama_onblur(this)" value="" />
									</div>
								</div>
								<div class="row">
									<label>Nama Barang</label> <input type="text" id="nama_barang" tabindex=4 autocomplete="off" style="width: 500px;" onkeyup="nama_barang_onkeyup(this.value)" value="" onkeyup="nama_barang_onKeyup(this)" />
									<div id="suggest_nama_barang"></div>
								</div>
								<div class="row">
									<div class="column" style="width: 183px;">
										<label>Harga</label> <input type="text" id="harga" style="width: 75px;" autocomplete="off" disabled value="" />
									</div>
									<div class="column">
										<label style="width: 40px;">Jumlah</label> <input type="text" id="jumlah" tabindex=5 autocomplete="off" style="width: 40px;" onchange="jumlah_onChange(this.value)" value="0" />
									</div>
									<div class="column col2">
										<label>Sub Total</label> <input type="text" id="sub_total" autocomplete="off" disabled value="" />
									</div>
								</div>
								
								<div class="row">
								<label>&nbsp;</label>
								<input type="button" name="insert" value="Masukkan!" tabindex=6 onclick="insert_onClick()" />
							</div>
							</div>
						</div>
					</div>
					<div class="row">
						<br />
						<div style="width: 1010px; height: 180px; border: 1px solid #BFBFBF; overflow: scroll;">
							<table id="tabel_barang" class="sortable">
								<tr>
									<th>No.</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Satuan</th><th>Jumlah</th><th>Sub Total</th>
								</tr>
							</table>
						</div>
						<br />
					</div>
					<div id="command_area" class="row">
						<input type="submit" name="action" value="save" tabindex=7 accesskey="s" class="large_button" onclick="return action_onclick()" />
						<input type="submit" name="action" value="new" tabindex=7 accesskey="s" class="large_button" />
						<input type="text" id="total_akhir_temp" value="0" tabindex=7 disabled style="float: right;" />
						<input type="hidden" id="total_akhir" name="total_akhir" value="0" />
						<label style="float: right;">Total Akhir : Rp. </label>
					</div>
				</form>
			</div>
			<script language="Javascript">
			//variabel
			var harga = document.getElementById('harga');
			var nama_pemasok = document.getElementById('nama_pemasok');
			var alamat_pemasok = document.getElementById('alamat_pemasok');
			var sub_total= document.getElementById('sub_total');
			var id_barang_temp = document.getElementById('id_barang_temp');
			var nama_barang = document.getElementById('nama_barang');
			var jumlah = document.getElementById('jumlah');
			var kode_barang = document.getElementById('kode_barang');
			var barcode = document.getElementById('barcode');
			var total_akhir = document.getElementById('total_akhir');
			var total_akhir_temp = document.getElementById('total_akhir_temp');
			
			var no = 1;
			
			//event handler
			function body_onLoad() {
				id_pemasok_onChange(document.getElementById('id_pemasok').value);
			}
			function id_pemasok_onChange(val) {
				var res = lookupArray('<?php echo $site_path; ?>ajax_handler.php?action=data_pemasok&id='+val);
				nama_pemasok.value = res[0];
				alamat_pemasok.innerHTML = res[1];
			}
			function barcode_nama_onblur(obj) {
				//alert('A');
				var url="ajax_handler.php?action=beli_barang_dari_"+obj.id+"&id="+obj.value; //alert(url);
				if (obj.value.length !== 0) {
					var nilai = lookup(url); //alert(nilai); alert(url);
					if (nilai.indexOf('|') == -1) {
						alert('Data tidak ditemukan!');
						obj.focus();
					} else {
						larik = nilai.split('|');
						id_barang_temp.value = larik[0]; //alert(larik[0]);
						nama_barang.value = larik[1];
						harga.value = addThaousandSeparator(larik[2]);
						kode_barang.value = larik[3];
						barcode.value = larik[4];
						jumlah_onChange(jumlah.value);
						nama_barang.focus();
					}
				}
			}
			function nama_barang_onKeyup(obj) {}
			function jumlah_onChange(val) {
				sub_total.value = addThaousandSeparator((removeThaousandSeparator(harga.value) * val).toString());
			}
			function insert_onClick() {
				//validasi
				if (jumlah.value <= 0) {
					alert('Jumlah barang masih kosong!');
					jumlah.focus();
					return(0);
				}
				
				//hitung hasil akhir
				total_akhir.value = parseInt(total_akhir.value) + removeThaousandSeparator(sub_total.value);
				total_akhir_temp.value = addThaousandSeparator(total_akhir.value.toString());
				
				//inisialisasi variabel
				var element = new Array('kode_barang', 'nama_barang', 'harga', 'jumlah', 'sub_total');
				var tabel = document.getElementById('tabel_barang');
				
				//tambah row
				var row = tabel.insertRow(-1);
				var cell = null;
				
				//sisipkan data untuk ikut disubmit
				row.innerHTML += '<input type="hidden" name="id_barang[]" value="' + id_barang_temp.value + '" />';
				row.innerHTML += '<input type="hidden" name="jml_barang[]" value="' + jumlah.value + '" />';
				
				//tambah cell nomor
				cell = row.insertCell(-1);
				cell.innerHTML = no;
				
				for (var i=0;i<element.length;i++) {
					var obj = document.getElementById(element[i]);
					cell = row.insertCell(-1);
					cell.innerHTML = obj.value;
					obj.value = ''; //kosongkan field
				}
				
				//pasca-pengaturan
				no++;
				alternate(tabel); 
				//atur field yang belum diatur
				barcode.value = '';
				jumlah.value='0';
				barcode.focus(); //atur fokus ke awal
			}
			function confirm_delete(val) {
				return confirm('Apakah anda benar-benar ingin menghapus barang: '+val+'?')
			}
			function row_click(val) {
				window.location = '<?php echo $_SERVER['PHP_SELF']; ?>?n_data_halaman=<?php echo $n_data_halaman; ?>&halaman=<?php echo $halaman; ?>&id='+val;
			}
			function action_onclick() {
				return confirm('Apakah anda yakin ingin menyelesaikan transaksi ini?');
			}
			</script>
			<style type="text/css">
				#form_data_pemasok label { width: 120px; }
				#form_barang label { width: 90px; }
				div.col2 label {
					width: 100px;
				}
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
