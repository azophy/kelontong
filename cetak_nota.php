<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

$id = validasi($_GET[id]);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="./design/favicon.ico">
        <title><?php echo $site_title; ?></title>
    </head>
    <body onload="body_onLoad()">
		<div id="wrapper">
			<div id="header">
				<h1>Toko Kelontong Makmur</h1>
				<p>jl. antah berantah no.1</p>
				<p>No. Nota: <?php echo $id; ?></p>
				<p>Tanggal: <?php echo date('d-m-Y'); ?></p>
			</div>
			<div id="main">
				<p>Nama Barang	Jumlah	Subtotal</p>
				<p>------------------------------------------------</p>
				<?php
				/*Generate tabel nota. Ada 2 penyelesaian yang bisa dipakai di sini: pakai data yg sudah di-POST 
				oleh user, atau me-retreive ulang dari database. di sini akan digunakan pendekatan ke-2.*/
				$total = 0;
				$proses = mysql_query("	SELECT barang.nama AS nama_barang, 
											penjualan_detail.jumlah AS jumlah, 
											barang.harga_beli AS harga_beli, 
											barang.persen_markup AS persen_markup
										FROM penjualan_detail, barang 
										WHERE penjualan_detail.id_penjualan_umum = $id AND barang.id = penjualan_detail.id_barang");
				if ($proses) {
					while ($hasil = mysql_fetch_array($proses)) {
						echo "<p>$hasil[nama_barang] x $hasil[jumlah] <label>Rp. ".$hasil[harga_beli]*(100+$hasil[persen_markup])."</label></p>";
						$total += $hasil[harga_beli]*(100+$hasil[persen_markup]);
					}
				}
				?>
				<p>------------------------------------------------</p>
				<p>Total: <label>Rp. <?php echo $total; ?></label></p>
			</div>
			<div id="footer">
				<p>Terima Kasih!</p>
			</div>
		</div>
	</body>
	<script language="javascript">
	function body_onLoad() {
		//prosedur untuk mencetak nota
		if (confirm('Apakah anda ingin mencetak nota transaksi ini?')) {
			window.print();
		}
		window.close();
	}
	</script>
	<style type="text/css">
	* {
		font-size: 12px;
	}
	#wrapper {
		width: 200px;
		margin: 0;
		padding: 0;
	}
	#header, #footer p {
		text-align: center;
	}
	p {
		clear: both;
		overflow: hidden;
		width: 100%;
		text-align: left;
	}
	label {
		float: right;
		text-align: right;
	}
	</style>
</html>
