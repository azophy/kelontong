<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?php echo $site_path; ?>design/style_print.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="./design/favicon.ico">
        <title><?php echo $site_title; ?></title>
    </head>
    <body onload="body_onLoad()">
	    <div id="header"></div>
	    <div id="content">
		<?php
		/*------------------------
			TAMPIL LAPORAN
		--------------------------*/
		//inisialisasi data
		$pesan="";
		$tanggal = date('d-m-Y');
		
		// <<< PROSES METHOD POST >>>
		if (!empty($_GET[action])) {
			switch ($_GET[action]) {
				case 'pelanggan_all':
					echo "<h1>DAFTAR SELURUH PELANGGAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "SELECT kode, nama, alamat, telepon FROM pelanggan";
					$elemen=array('Kode Pelanggan' => 'kode', 'Nama Pelanggan' => 'nama', 'Alamat' => 'alamat', 'Telepon' => 'telepon');
					echo "<table>";
					showTable($query, $elemen);
					echo "</table>";
				break;
				case 'pemasok_all': 
					echo "<h1>DAFTAR SELURUH PEMASOK</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "SELECT kode, nama, alamat, telepon FROM pemasok";
					$elemen=array('Kode Pemasok' => 'kode', 'Nama Pemasok' => 'nama', 'Alamat' => 'alamat', 'Telepon' => 'telepon');
					echo "<table>";
					showTable($query, $elemen);
					echo "</table>";
				break;
				case 'golongan_all':
					echo "<h1>DAFTAR SELURUH GOLONGAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "SELECT kode, nama, keterangan FROM golongan";
					$elemen=array('Kode Golongan' => 'kode', 'Nama Golongan' => 'nama', 'Keterangan' => 'keterangan');
					echo "<table>";
					showTable($query, $elemen);
					echo "</table>";
				break;
				case 'barang_all':
					echo "<h1>DAFTAR SELURUH BARANG</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT golongan.nama AS nama_golongan,
									barang.nama AS nama,
									barang.harga_beli AS harga,
									barang.persen_markup AS persen_markup,
									satuan.nama AS nama_satuan,
									barang.stok AS stok
								FROM barang, golongan, satuan
								WHERE golongan.id = barang.id_golongan AND satuan.id = barang.id_satuan";
					//$elemen=array('Nama Golongan' => 'nama_golongan', 'Nama Barang' => 'nama', 'Harga' => 'harga', 'Satuan' => 'nama_satuan', 'Stok' => 'stok');
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>No.</th><th>Nama Golongan</th><th>Nama Barang</th><th>Harga (Rp.)</th><th>Satuan</th><th>Stok</th></tr>";
					//showTable($query, $elemen);
					if ($proses) {
						$a=1;
						while ($hasil = mysql_fetch_array($proses)) {
							echo "<tr><td>".$a++."</td><td>$hasil[nama_golongan]</td><td>$hasil[nama]</td><td>".($hasil[harga]*(100+$hasil[persen_markup])/100)."</td><td>$hasil[nama_satuan]</td><td>$hasil[stok]</td></tr>";
						}
					}
					echo "</table>";
				break;
				case 'barang_per_golongan': 
					$id_gol = validasi($_GET[id_gol]);
					//dapatkan nama golongan
					$proses = mysql_query("SELECT nama FROM golongan WHERE golongan.id = $id_gol");
					if ($proses) {
						$hasil = mysql_fetch_array($proses);
						$nama_gol = $hasil[nama];
					}
					echo "<h1>DAFTAR BARANG PER-GOLONGAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					echo "<div class=\"box\">Golongan: ".$nama_gol."</div>";
					$query = "	SELECT barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga,
									barang.persen_markup AS persen_markup,
									satuan.nama AS nama_satuan,
									barang.stok AS stok,
									barang.barcode AS barcode
								FROM barang, satuan
								WHERE barang.id_golongan = $id_gol AND satuan.id = barang.id_satuan"; //echo $query;
					$elemen=array('Kode Barang' => 'kode', 'Nama barang' => 'nama', 'Harga Beli' => 'harga', 'Persen Markup' => 'persen_markup', 'Satuan' => 'nama_satuan', 'Stok' => 'stok', 'Barcode' => 'barcode');
					echo "<table>";
					showTable($query, $elemen);
					echo "</table>";
				break;
				case 'pembelian_per_transaksi': 
					$id_trans = validasi($_GET[id_trans]);
					//dapatkan tgl transaksi, nama, dan alamat pemasok
					$proses = mysql_query("SELECT DATE(pembelian_umum.waktu) AS tanggal, pemasok.nama AS nama, pemasok.alamat AS alamat FROM pembelian_umum, pemasok WHERE pembelian_umum.id = $id_trans AND pemasok.id = pembelian_umum.id_pemasok");
					if ($proses) {
						$hasil = mysql_fetch_array($proses);
						$tanggal_trans = $hasil[tanggal];
						$nama_pemasok = $hasil[nama];
						$alamat_pemasok = $hasil[alamat];
					}
					echo "<h1>DAFTAR PEMBELIAN PER-TRANSAKSI</h1>";
					echo "<div class=\"box\">Id Transaksi: ".$id_trans."</div>";
					echo "<div class=\"box\">Tanggal: ".convertDate($tanggal_trans)."</div>";
					echo "<div class=\"box\">Nama: ".$nama_pemasok."</div>";
					echo "<div class=\"box\">Alamat: ".$alamat_pemasok."</div>";
					$query = "	SELECT barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga,
									satuan.nama AS nama_satuan,
									pembelian_detail.jumlah AS jumlah
								FROM pembelian_detail, barang, satuan
								WHERE pembelian_detail.id_pembelian_umum = $id_trans AND barang.id = pembelian_detail.id_barang AND satuan.id = barang.id_satuan"; //echo $query;
					//$elemen=array('Kode Barang' => 'kode', 'Nama barang' => 'nama', 'Harga Beli' => 'harga', 'Persen Markup' => 'persen_markup', 'Satuan' => 'nama_satuan', 'Stok' => 'stok', 'Barcode' => 'barcode');
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>No.</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Beli(Rp.)</th><th>Satuan</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					//showTable($query, $elemen);
					if ($proses) {
						$a=1;
						while ($hasil = mysql_fetch_array($proses)) {
							echo "<tr><td>".$a++."</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$hasil[harga]</td><td>$hasil[nama_satuan]</td><td>$hasil[jumlah]</td><td>".($hasil[harga]*$hasil[jumlah])."</td></tr>";
						}
					}
					echo "</table>";
				break;
				case 'penjualan_per_transaksi': 
					$id_trans = validasi($_GET[id_trans]);
					//dapatkan tgl transaksi
					$proses = mysql_query("SELECT DATE(penjualan_umum.waktu) AS tanggal FROM penjualan_umum WHERE penjualan_umum.id = $id_trans");
					if ($proses) {
						$hasil = mysql_fetch_array($proses);
						$tanggal_trans = $hasil[tanggal];
					}
					echo "<h1>DAFTAR PENJUALAN PER-TRANSAKSI</h1>";
					echo "<div class=\"box\">Id Transaksi: ".$id_trans."</div>";
					echo "<div class=\"box\">Tanggal: ".convertDate($tanggal_trans)."</div>";
					$query = "	SELECT barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									satuan.nama AS nama_satuan,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_detail, barang, satuan
								WHERE penjualan_detail.id_penjualan_umum = $id_trans AND barang.id = penjualan_detail.id_barang AND satuan.id = barang.id_satuan"; //echo $query;
					//$elemen=array('Kode Barang' => 'kode', 'Nama barang' => 'nama', 'Harga Jual' => 'harga', 'Persen Markup' => 'persen_markup', 'Satuan' => 'nama_satuan', 'Stok' => 'stok', 'Barcode' => 'barcode');
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>No.</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Satuan</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					//showTable($query, $elemen);
					if ($proses) {
						$a=1;
						while ($hasil = mysql_fetch_array($proses)) {
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo "<tr><td>".$a++."</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[nama_satuan]</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
						}
					}
					echo "</table>";
				break;
				case 'penjualan_per_tanggal': 
					$tgl_trans = validasi($_GET[tgl_trans]);
					echo "<h1>DAFTAR PENJUALAN PER-TANGGAL</h1>";
					echo "<div class=\"box\">Tanggal: ".convertDate($tgl_trans)."</div>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT penjualan_umum.id AS id,
									barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_umum, penjualan_detail, barang
								WHERE barang.id = penjualan_detail.id_barang AND penjualan_detail.id_penjualan_umum = penjualan_umum.id AND DATE(penjualan_umum.waktu) = '$tgl_trans'"; //echo $query;
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>Id Transaksi</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					if ($proses) {
						$id = 0;
						while ($hasil = mysql_fetch_array($proses)) {
							echo "<tr><td>";
							if ($id != $hasil[id]) {
								$id = $hasil[id];
								echo $id;
							}
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo"</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
						}
					}
					echo "</table>";
				break;
				case 'penjualan_per_periode': 
					$tgl_trans_awal = validasi($_GET[tgl_trans_awal]);
					$tgl_trans_akhir = validasi($_GET[tgl_trans_akhir]);
					echo "<h1>DAFTAR PENJUALAN PER-PERIODE</h1>";
					echo "<div class=\"box\">Tanggal: ".convertDate($tgl_trans_awal)." s/d ".convertDate($tgl_trans_akhir)."</div>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT DATE(penjualan_umum.waktu) AS tanggal,
									penjualan_umum.id AS id,
									barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_umum, penjualan_detail, barang
								WHERE barang.id = penjualan_detail.id_barang AND penjualan_detail.id_penjualan_umum = penjualan_umum.id AND DATE(penjualan_umum.waktu) >= '$tgl_trans_awal' AND DATE(penjualan_umum.waktu) <= '$tgl_trans_akhir'"; //echo $query;
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>Tanggal</th><th>Id Transaksi</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					if ($proses) {
						$id = 0;
						$tgl_lama = 0;
						while ($hasil = mysql_fetch_array($proses)) {
							echo "<tr><td>";
							if ($tgl_lama != $hasil[tanggal]) {
								$tgl_lama = $hasil[tanggal];
								echo convertDate($tgl_lama);
							}
							echo "</td><td>";
							if ($id != $hasil[id]) {
								$id = $hasil[id];
								echo $id;
							}
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo"</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
						}
					}
					echo "</table>";
				break;
				case 'penjualan_harian': 
					echo "<h1>DAFTAR PENJUALAN HARIAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT DATE(penjualan_umum.waktu) AS tanggal,
									penjualan_umum.id AS id,
									barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_umum, penjualan_detail, barang
								WHERE barang.id = penjualan_detail.id_barang AND penjualan_detail.id_penjualan_umum = penjualan_umum.id AND DATE(penjualan_umum.waktu) = '".date('Y-m-d')."'"; //echo $query;
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>Tanggal</th><th>Id Transaksi</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					if ($proses) {
						$id = 0;
						$tgl_lama = 0;
						$total = 0;
						while ($hasil = mysql_fetch_array($proses)) {
							if ($id != $hasil[id] && $id != 0) { 
								//hitung total
								echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
								$total = 0;
							}
							echo "<tr><td>";
							if ($id != $hasil[id]) {
								$id = $hasil[id];
								echo convertDate($hasil[tanggal])."</td><td>".$id;
							} else echo "</td><td>";
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo"</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
							$total += ($harga*$hasil[jumlah]);
						}
						//hitung total yg kelewat
						echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
					}
					echo "</table>";
				break;
				case 'penjualan_pekanan': 
					echo "<h1>DAFTAR PENJUALAN PEKANAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT DATE(penjualan_umum.waktu) AS tanggal,
									penjualan_umum.id AS id,
									barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_umum, penjualan_detail, barang
								WHERE barang.id = penjualan_detail.id_barang AND penjualan_detail.id_penjualan_umum = penjualan_umum.id AND DATE_FORMAT(penjualan_umum.waktu, '%u') = WEEK('".date('Y-m-d')."')"; //echo $query;
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>Tanggal</th><th>Id Transaksi</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					if ($proses) {
						$id = 0;
						$tgl_lama = 0;
						$total = 0;
						while ($hasil = mysql_fetch_array($proses)) {
							if ($id != $hasil[id] && $id != 0) { 
								//hitung total
								echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
								$total = 0;
							}
							echo "<tr><td>";
							if ($id != $hasil[id]) {
								$id = $hasil[id];
								echo convertDate($hasil[tanggal])."</td><td>".$id;
							} else echo "</td><td>";
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo"</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
							$total += ($harga*$hasil[jumlah]);
						}
						//hitung total yg kelewat
						echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
					}
					echo "</table>";
				break;
				case 'penjualan_bulanan': 
					echo "<h1>DAFTAR PENJUALAN BULANAN</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT DATE(penjualan_umum.waktu) AS tanggal,
									penjualan_umum.id AS id,
									barang.kode AS kode,
									barang.nama AS nama,
									barang.harga_beli AS harga_beli,
									barang.persen_markup AS persen_markup,
									penjualan_detail.jumlah AS jumlah
								FROM penjualan_umum, penjualan_detail, barang
								WHERE barang.id = penjualan_detail.id_barang AND penjualan_detail.id_penjualan_umum = penjualan_umum.id AND DATE_FORMAT(penjualan_umum.waktu, '%m') = MONTH('".date('Y-m-d')."')"; //echo $query;
					$proses = mysql_query($query);
					echo "<table>
					<tr><th>Tanggal</th><th>Id Transaksi</th><th>Kode Barang</th><th>Nama Barang</th><th>Harga Jual(Rp.)</th><th>Jumlah</th><th>Sub Total (Rp.)</th></tr>";
					if ($proses) {
						$id = 0;
						$tgl_lama = 0;
						$total = 0;
						while ($hasil = mysql_fetch_array($proses)) {
							if ($id != $hasil[id] && $id != 0) { 
								//hitung total
								echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
								$total = 0;
							}
							echo "<tr><td>";
							if ($id != $hasil[id]) {
								$id = $hasil[id];
								echo convertDate($hasil[tanggal])."</td><td>".$id;
							} else echo "</td><td>";
							$harga = $hasil[harga_beli]*(100+$hasil[persen_markup])/100;
							echo"</td><td>$hasil[kode]</td><td>$hasil[nama]</td><td>$harga</td><td>$hasil[jumlah]</td><td>".($harga*$hasil[jumlah])."</td></tr>";
							$total += ($harga*$hasil[jumlah]);
						}
						//hitung total yg kelewat
						echo "<tr class=\"last_row\"><td colspan=\"6\" style=\"text-align: right\">Total :</td><td>$total</td></tr>";
					}
					echo "</table>";
				break;
				case 'pengguna_all':
					echo "<h1>DAFTAR SELURUH PENGGUNA</h1>";
					echo "<div class=\"box\">Tanggal Cetak: ".$tanggal."</div>";
					$query = "	SELECT pengguna.nama_lengkap AS nama,
									pengguna.username AS username,
									status.nama AS nama_status
								FROM pengguna, status
								WHERE pengguna.id_status = status.id";
					$elemen=array('Nama Pengguna' => 'nama', 'User Name' => 'username', 'Status' => 'nama_status');
					echo "<table>";
					showTable($query, $elemen);
					echo "</table>"; 
				break;
			}
			
			// <<< ATUR ALERT >>>
			if (strlen($pesan) > 0) $pesan='<div class="box">'.$pesan.'</div>';
			$tampil_baru=true;
		} else if (empty($_GET[id])) {
			$tampil_baru=true;
		}
		?>
		</div>
	    <div id="footer"></div>
	<script language="Javascript">
	//variabel
	
	var no = 1;
	
	//event handler
	function body_onLoad() {
		//cetak laporan ini
		//window.print()
	}
	function print_onClick() {}
	</script>
	<style type="text/css">
		#form_laporan label { width: 100px; }
		div.col2 label {
			width: 100px;
		}
	</style>
    </body>
</html>
