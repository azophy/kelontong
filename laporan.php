<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <?php include($site_path."design/meta.php"); ?>
    <body onload="body_onLoad()">
		<div id="wrapper">
			<?php include($site_path."design/page_header.php"); ?>
			<?php include($site_path."design/menubar.php"); ?>
			<div id="content">
				<form id="form_laporan" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="box">
						<div class="row">
							<p>Keterangan: masukkan tanggal dengan format "tahun-bulan(dengan angka 0)-tanggal". Contoh: 2011-06-29</p>
							<label>Jenis Laporan:</label>
							<select id="action" name="action" onchange="action_onChange(this.value)">
								<option value="pelanggan_all">Seluruh Pelanggan</option>
								<option value="pemasok_all">Seluruh Pemasok</option>
								<option value="golongan_all">Seluruh Golongan</option>
								<option value="barang_all">Seluruh Barang</option>
								<option value="barang_per_golongan">Seluruh Barang per Golongan</option>
								<option value="pembelian_per_transaksi">Seluruh Pembelian per Transaksi</option>
								<option value="penjualan_per_transaksi">Seluruh Penjualan per Transaksi</option>
								<option value="penjualan_per_tanggal">Seluruh Penjualan per Tanggal</option>
								<option value="penjualan_per_periode">Seluruh Penjualan per Periode</option>
								<option value="penjualan_harian">Penjualan Harian</option>
								<option value="penjualan_pekanan">Penjualan Pekanan</option>
								<option value="penjualan_bulanan">Penjualan Bulanan</option>
								<option value="pengguna_all">Seluruh Pengguna</option>
							</select>
							<div id="extend_selection"></div>
						</div>
						<div class="row">
							<label>&nbsp;</label>
							<input type="button" id="preview" value="Preview" onclick="preview_onClick()" />
							<input type="button" id="show" value="Tampilkan" onclick="show_onClick()" />
							<input type="button" id="print" value="Cetak" onclick="print_onClick()" />
						</div>
					</div>
					<iframe id="frame_preview" style="width: 1000px" src=""></iframe>
				</form>
			</div>
			<script language="Javascript">
			//variabel
			var action = document.getElementById('action');
			var extend_selection = document.getElementById('extend_selection');
			var frame_preview = document.getElementById('frame_preview');
			
			var subWindow;
			var no = 1;
			//var url_laporan = '<?php echo $site_path; ?>laporan_print.php?action=pelanggan_all';
			
			//event handler
			function body_onLoad() {
				//id_pelanggan_onChange(document.getElementById('id_pelanggan').value);
			}
			function action_onChange(val) { 
				//update nilai variabel url_laporan
				//url_laporan = '<?php echo $site_path; ?>laporan_print.php?action=' + val;
				
				//update element form
				extend_selection.innerHTML = ''; //kosongkan dulu
				switch (val) {
					case "barang_per_golongan":
						extend_selection.innerHTML = '<div class="row"><label>Golongan:</label><select id="id_golongan"><?php showComboBox('golongan',0); ?></select></div>';
					break;
					case "pembelian_per_transaksi":
					case "penjualan_per_transaksi":
						extend_selection.innerHTML = '<div class="row"><label>Id Transaksi:</label><input type="text" id="id_transaksi" /></div>';
					break;
					case "penjualan_per_tanggal":
						extend_selection.innerHTML = '<div class="row"><label>Tanggal Transaksi:</label><input type="text" id="tgl_trans" /></div>';
					break;
					case "penjualan_per_periode":
						extend_selection.innerHTML = '<div class="row"><label>Tanggal Awal:</label><input type="text" id="tgl_trans_awal" /></div>';
						extend_selection.innerHTML += '<div class="row"><label>Tanggal Akhir:</label><input type="text" id="tgl_trans_akhir" /></div>';
					break;
				}
			}
			function preview_onClick(val) { //alert(update_url());
				var url = update_url(); //alert(url);
				frame_preview.src = url;
			}
			function show_onClick() {
				openLaporan();
			}
			function print_onClick() {
				openLaporan();
				subWindow.print();
				subWindow.close();
			}
			function openLaporan() {
				//var url_laporan = '<?php echo $site_path; ?>laporan_print.php?action=' + action.value;
				if (!subWindow || subWindow.closed) {
					var width = 1000;
					var height = 600;
					
					//dapatkan posisi kiri atas dari ojok layar
					var left = parseInt((screen.availWidth/2) - (width/2));
					var top = parseInt((screen.availHeight/2) - (height/2));
					
					//fitur2 window
					var fitur = "width="+width+", height="+height+", resizable=0, top="+top+", left="+left+", screenX="+left+", screenY="+top;
					subWindow = window.open(update_url(), "sub", fitur);
				} else {
					subWindow.focus();
				}
			}
			function update_url() {
				var url = '<?php echo $site_path; ?>laporan_print.php?action=' + action.value;
				
				switch (action.value) {
					case "barang_per_golongan":
						url += '&id_gol=' + document.getElementById('id_golongan').value;
					break;
					case "pembelian_per_transaksi":
					case "penjualan_per_transaksi":
						url += '&id_trans=' + document.getElementById('id_transaksi').value;
					break;
					case "penjualan_per_tanggal":
						url += '&tgl_trans=' + document.getElementById('tgl_trans').value;
					break;
					case "penjualan_per_periode":
						url += '&tgl_trans_awal=' + document.getElementById('tgl_trans_awal').value + '&tgl_trans_akhir=' + document.getElementById('tgl_trans_akhir').value;
					break;
				}
				
				return(url);
			}
			</script>
			<style type="text/css">
				#form_laporan label { width: 130px; }
				div.col2 label {
					width: 100px;
				}
				iframe {
					margin-top: 10px;
					width: 1060px;
					height: 340px;
				}
			</style>
			<?php include($site_path."design/page_footer.php"); ?>
		</div>
    </body>
</html>
