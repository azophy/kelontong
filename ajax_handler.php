<?php
require_once("./script/config.php");
require_once($site_path."script/library.php");
cekSession();

//baca action
switch ($_GET[action]) {
	case 'ComboNamaGol':
		$id=validasi($_GET[id]);
		$proses = mysql_query("SELECT nama FROM golongan WHERE id='$id'");
		if ($proses) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0];
		}
	break;
	
	case 'data_pemasok':
		$id=validasi($_GET[id]);
		$proses = mysql_query("SELECT nama, alamat FROM pemasok WHERE id='$id'");
		if ($proses) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0].'|'.$hasil[1];
		}
	break;
	
	case 'data_pelanggan':
		$id=validasi($_GET[id]);
		$proses = mysql_query("SELECT nama FROM pelanggan WHERE id='$id'");
		if ($proses) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0];
		}
	break;
	
	case 'jual_barang_dari_barcode':
		$id=validasi($_GET[id]); 
		$proses = mysql_query("SELECT id, nama, harga_beli, persen_markup, kode, barcode FROM barang WHERE barcode='$id'");
		if ($proses && (mysql_num_rows($proses) > 0)) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0].'|'.$hasil[1].'|'.($hasil[2]*(100+$hasil[3])/100).'|'.$hasil[4].'|'.$hasil[5];
		} else {
			echo 'error';
		}
	break;
	
	case 'jual_barang_dari_kode_barang':
		$id=validasi($_GET[id]);
		$proses = mysql_query("SELECT id, nama, harga_beli, persen_markup, kode, barcode FROM barang WHERE kode='$id'");
		if ($proses && (mysql_num_rows($proses) > 0)) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0].'|'.$hasil[1].'|'.($hasil[2]*(100+$hasil[3])/100).'|'.$hasil[4].'|'.$hasil[5];
		} else {
			echo 'error';
		}
	break;
	
	case 'beli_barang_dari_barcode':
		$id=validasi($_GET[id]); 
		$proses = mysql_query("SELECT id, nama, harga_beli, kode, barcode FROM barang WHERE barcode='$id'");
		if ($proses && (mysql_num_rows($proses) > 0)) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0].'|'.$hasil[1].'|'.$hasil[2].'|'.$hasil[3].'|'.$hasil[4];
		} else {
			echo 'error';
		}
	break;
	
	case 'beli_barang_dari_kode_barang':
		$id=validasi($_GET[id]);
		$proses = mysql_query("SELECT id, nama, harga_beli, kode, barcode FROM barang WHERE kode='$id'");
		if ($proses && (mysql_num_rows($proses) > 0)) {
			$hasil = mysql_fetch_row($proses);
			echo $hasil[0].'|'.$hasil[1].'|'.$hasil[2].'|'.$hasil[3].'|'.$hasil[4];
		} else {
			echo 'error';
		}
	break;
}
?>
