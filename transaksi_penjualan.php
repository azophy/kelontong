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
							'id_pelanggan' => $_POST[id_pelanggan],
							'waktu' => (date('Y-m-d').' '.date('H:i:s')),
							'total' => $_POST[total_akhir]); 
			//tambahan: jasa & potongan
			if (!empty($_POST[potongan])) $elemen['potongan'] = $_POST[potongan];
			if (!empty($_POST[jasa])) $elemen['jasa'] = $_POST[jasa];
			$pesan=addNew('penjualan_umum', $elemen, true);
			if (strlen($pesan) > 0) break;
			$id_transaksi = getInsertedId(); //dapet ID terakhir
			
			//INSERT rincian data transaksi
			$id_barang = $_POST[id_barang];
			$jml_barang = $_POST[jml_barang];
			$n_barang = count($id_barang);
			for ($i=0; $i<$n_barang; $i++) {
				$elemen = array('id_penjualan_umum' => $id_transaksi, 'id_barang' => $id_barang[$i], 'jumlah' => $jml_barang[$i]);
				$pesan = addNew('penjualan_detail', $elemen, true);
				if (strlen($pesan) > 0) break;
			}
			//SHOW nota transaksi
			$cetak_nota = "openSubWindow('".$site_path."cetak_nota.php?id=".$id_transaksi."', subWindow, 210, 0);";
		break;
		case 'new': // <<< KOSONGKAN FORM >>>
			header("location: ".$_SERVER['PHP_SELF']);
		break;
	}
	
	// <<< ATUR ALERT >>>
	if (strlen($pesan) > 0) $pesan='<div class="box">'.$pesan.'</div>';
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
						<div id="form_barang" class="column" style="width: 800px;">
							<h3>Data Barang</h3>
							<div class="box" style="width: 750px;">
								<input type="hidden" id="id_barang_temp" />
								<div class="row">
									<label>Kode Pelanggan</label>
									<select name="id_pelanggan" id="id_pelanggan" tabindex=1 onchange="id_pelanggan_onChange(this.value)">
										<?php showComboBox('pelanggan',0, 'id', 'kode'); ?>
									</select>
									<div class="column" style="width: 350px;">
										<label style="width: 120px;">Nama Pelanggan</label> <input type="text" id="nama_pelanggan" disabled value="<?php echo $form_value[nama]; ?>" />
									</div>
								</div>	
								<div class="row">
									<div class="column" style="width: 320px;">
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
									<div class="column" style="width: 210px;">
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
						<div style="width: 1010px; height: 170px; border: 1px solid #BFBFBF; overflow: scroll;">
							<table id="tabel_barang" class="sortable">
								<tr>
									<th>No.</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Satuan</th><th>Jumlah</th><th>Sub Total</th>
								</tr>
							</table>
						</div>
						<br />
					</div>
					<div id="command_area" class="row">
						<div id="input_akhir" class="column">
							<label>Potongan (%) : </label> <input type="text" id="potongan" name="potongan" value="0" tabindex=7 onchange="potongan_onChange(this.value)" />
							<label>Jasa : Rp.</label> <input type="text" id="jasa" name="jasa" value="0" tabindex=8 onChange="jasa_onChange(this.value)"  />
							<label>Total Akhir : Rp.</label> <input type="text" id="total_akhir_temp" value="0" class="pjg" disabled tabindex=9 style="font-weight: bold;" />
							<label>Uang Bayar : Rp.</label> <input type="text" id="uang_bayar" value="0" class="pjg" tabindex=10 onchange="uang_bayar_onChange(this.value)" />
							<label>Kembalian : Rp.</label> <input type="text" id="kembalian" value="0" class="pjg" disabled tabindex=11 />
							<input type="hidden" id="total_akhir" name="total_akhir" value="0" />
						</div>
						<div class="column-right">
							<input type="submit" name="action" value="save" tabindex=12 accesskey="s" class="large_button" onclick="return action_onclick()" />
							<input type="submit" name="action" value="new" tabindex=13 accesskey="s" class="large_button" />
						</div>
					</div>
				</form>
			</div>
			<script language="Javascript">
			//variabel
			var harga = document.getElementById('harga');
			var nama_pelanggan = document.getElementById('nama_pelanggan');
			var sub_total= document.getElementById('sub_total');
			var id_barang_temp = document.getElementById('id_barang_temp');
			var nama_barang = document.getElementById('nama_barang');
			var jumlah = document.getElementById('jumlah');
			var kode_barang = document.getElementById('kode_barang');
			var barcode = document.getElementById('barcode');
			var total_akhir = document.getElementById('total_akhir');
			var total_akhir_temp = document.getElementById('total_akhir_temp');
			var kembalian = document.getElementById('kembalian');
			var uang_bayar = document.getElementById('uang_bayar');
			
			var no = 1;
			var subWindow;
			
			//event handler
			function body_onLoad() {
				id_pelanggan_onChange(document.getElementById('id_pelanggan').value);
				//cetak nota penjualan
				<?php echo $cetak_nota; ?>
			}
			function id_pelanggan_onChange(val) {
				nama_pelanggan.value = lookup('<?php echo $site_path; ?>ajax_handler.php?action=data_pelanggan&id='+val);
			}
			function barcode_nama_onblur(obj) {
				//alert('A');
				var url="ajax_handler.php?action=jual_barang_dari_"+obj.id+"&id="+obj.value; //alert(url);
				if (obj.value.length !== 0) {
					var nilai = lookup(url); //alert(nilai); alert(url);
					if (nilai.indexOf('|') == -1) {
						alert('Data tidak ditemukan!');
						obj.focus();
					} else {
						larik = nilai.split('|');
						id_barang_temp.value = larik[0]; //alert(larik[0]);
						nama_barang.value = larik[1];
						//harga.value = larik[2]; 
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
			function potongan_onChange(val) {
				//hitung hasil akhir
				var x = parseInt(total_akhir.value) * (100-parseInt(val)) / 100;
				total_akhir_temp.value = addThaousandSeparator(x.toString());
			}
			function jasa_onChange(val) {
				//hitung hasil akhir
				var x = removeThaousandSeparator(total_akhir_temp.value) + parseInt(val);
				total_akhir_temp.value = addThaousandSeparator(x.toString());
			}
			function uang_bayar_onChange(val) {
				kembalian.value = addThaousandSeparator((parseInt(val)-removeThaousandSeparator(total_akhir_temp.value)).toString());
			}
			function action_onclick() {
				if (uang_bayar.value <= 0) {
					alert('Uang yang dibayar masih kosong!');
					return(0);
				}
				if (kembalian.value <= 0) {
					alert('Uang yang dibayar terlalu kecil!');
					return(0);
				}
				if (confirm('Apakah anda yakin ingin menyelesaikan transaksi ini?')) {
					return true;
				}
			}
			
			//other supporting functions				
			</script>
			<style type="text/css">
				#form_barang label { width: 110px; }
				div.col2 label {
					width: 100px;
				}
				#input_akhir input { width: 50px;}
				#input_akhir input.pjg { width: 75px;}
			</style>
			<?php include($site_path."design/page_footer.php"); ?>
		</div>
    </body>
</html>
