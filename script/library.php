<?php
/*--------------------------
	FUNGSI-FUNGSI KEAMANAN 
----------------------------*/
//fungsi validasi input
function validasi($masuk) {
	$masuk=strip_tags($masuk);
	if (!get_magic_quotes_gpc()) {
		return addslashes($masuk);
	} else {
		return $masuk;
	}
}

//fungsi untuk mengecek, apakah user sudah login atau belum
function isLoggedIn() {
	return ((!empty($_SESSION[id]) && !empty($_SESSION[nama_lengkap]) && !empty($_SESSION[id_status])));
} 

//klo user belum login, langsung redirect ke halaman login
function cekSession() {
	if (!isLoggedIn()) 
		header("location: ".$site_path."login.php");
		
	//cek priviledge
	//get filename
	$file_name = explode('/',$_SERVER[SCRIPT_NAME]);
	$file_name = $file_name[count($file_name)-1]; //echo $file_name;
	//cek priviledge cocok atau tidak
	$proses = mysql_query("SELECT id FROM priviledge WHERE page = '$file_name' AND id_status = $_SESSION[id_status]");
	if (!($proses && mysql_num_rows($proses)>0)) { //if not found
		//cek apakah halaman memang bukan bebas akses
		$proses2 = mysql_query("SELECT id FROM priviledge WHERE page = '$file_name' AND id_status = 0");
		if (!($proses2 && mysql_num_rows($proses2)>0)) { //if not found
			exit("Maaf, anda tidak diperkenankan mengakses halaman ini. <br /><a href=\"index.php\">Home Page</a>");
		}
	}
}

/*--------------------------------
	FUNGSI-FUNGSI PEMFORMATAN
----------------------------------*/

function convertDate($str) {
	//return(date('d M y', str));
	//return($str);
	
	//format menggunakan manipulasi string
	$y = substr($str, 0, 4);
	$m = substr($str, 5, 2);
	$d = substr($str, 8, 2);
	
	return("$d-$m-$y");
}

/*--------------------------------------
	FUNGSI-FUNGSI PAGING MANAGEMENT
----------------------------------------*/
function showPagingLink($halaman, $n_data_halaman, $n_halaman) {
	//tampil link paging
	for ($i=1;$i<=$n_halaman;$i++) {
		if ($i==$halaman) {
			echo " $i |";
		} else {
			echo " <a href=\"".$_SERVER['PHP_SELF']."?n_data_halaman=".$n_data_halaman."&halaman=$i\">$i</a> |";
		}
	}
}

function countNData ($table_name, $record_name='id') {
	$query='SELECT '.$record_name.' FROM '.$table_name;
	$proses=mysql_query($query);
	if ($proses) {
		return(mysql_num_rows($proses));
	}
}

/*--------------------------------------
	FUNGSI-FUNGSI DATABASE MANAGEMENT
----------------------------------------*/
function getInsertedId() {
	return mysql_insert_id();
}
function showTable($query, $element, $func=null, $id_name='id', $no=1) {
	//print table header
	echo '<thead><tr>';
	echo '<th>No.</th>';
	foreach ($element as $key => $val) {
		echo '<th>'.$key.'</th>';
	}
	echo '</tr></thead><tbody>';
	
	//query operation
	$proses = mysql_query($query);
	if ($proses) {
		while ($hasil = mysql_fetch_array($proses)) {		
			//if function available, prepare function
			echo '<tr'.((!empty($func))?(' onClick="'.$func.'('.$hasil[$id_name].')"'):'').'>';
			//echo '<tr onClick="alert(\'AAAAAA\')">';
			echo '<td>'.$no++.'</td>';
			foreach ($element as $key => $val) {
				echo '<td>'.$hasil[$val].'</td>';
			}
			echo '</tr>'."\n";
		}
	}
	echo '</tbody>';
}
function showComboBox($table_name, $selected, $id='id', $nama='nama', $where_clause="") {	
	//construct query
	$query="SELECT ".$id.", ".$nama." FROM ".$table_name." ".$where_clause;
	
	//proses query
	$proses=mysql_query($query);	
	if ($proses) {
		while ($hasil=mysql_fetch_row($proses)) { 
			if ($hasil[0]==$selected)
				$cetak="selected"; 
			else 
				$cetak="";
			echo "<option value=\"".$hasil[0]."\" ".$cetak.">".$hasil[1]."</option>";
		}
	}
}
function addNew($table_name, $element, $custom = false) {
	//validasi data
	$id_val = validasi($id_val); 
	if (!$custom) { //gunakan POST sebagai sumber data
		foreach ($element as $val) {
			if (empty($_POST[$val])) return("Ada data yang kosong!");
			if ($val != 'password') {
				$variabel[$val] = validasi($_POST[$val]);
			} else {
				$variabel[$val] = md5($_POST[$val]);
			}
		}
		
		//construct query
		$query = "INSERT INTO ".$table_name." (";
		foreach ($element as $val) {
			if (!isset($udah)) $udah=true; else $query.=", "; //handler coma pemisah
			$query.=$val;
		}
		$query.=") VALUES ("; unset($udah);
		foreach ($element as $val) {
			if (!isset($udah)) $udah=true; else $query.=", "; //handler coma pemisah
			$query.="'".$variabel[$val]."'";
		}
		$query.=")"; 

	} else { //gunakan input lain sebagai sebagai sumber data
		foreach ($element as $val => $data) {
			if (empty($data)) return("Ada data yang kosong!");
			if ($val != 'password') {
				$variabel[$val] = validasi($data);
			} else {
				$variabel[$val] = md5($data);
			}
		}
		
		//construct query
		$query = "INSERT INTO ".$table_name." (";
		foreach ($element as $val => $dummy) {
			if (!isset($udah)) $udah=true; else $query.=", "; //handler coma pemisah
			$query.=$val;
		}
		$query.=") VALUES ("; unset($udah);
		foreach ($element as $val => $dummy) {
			if (!isset($udah)) $udah=true; else $query.=", "; //handler coma pemisah
			$query.="'".$variabel[$val]."'";
		}
		$query.=")"; 
	} //echo $query;
	
	//proses query
	$proses=mysql_query($query);
	if (!$proses) return("Operasi gagal!");
}
function updateOld($table_name, $element, $id_val, $source, $id_name='id', $custom = false) {
	//validasi data
	if (!$custom) { //gunakan POST sebagai sumber data
		foreach ($element as $val) {
			if (empty($_POST[$val])) return("Ada data yang kosong!");
			if ($val != 'password') {
				$variabel[$val] = validasi($_POST[$val]);
			} else {
				$variabel[$val] = md5($_POST[$val]);
			}
		}
	} else { //gunakan array 'source' sebagai sumber data
		foreach ($element as $val) {
			if (empty($_POST[$val])) return("Ada data yang kosong!");
			if ($val != 'password') {
				$variabel[$val] = validasi($source[$val]);
			} else {
				$variabel[$val] = md5($source[$val]);
			}
		}
	}
	$id_val = validasi($id_val); 
	
	//construct query
	$query = "UPDATE ".$table_name." SET ";
	foreach ($element as $val) {
		if (!isset($udah)) $udah=true; else $query.=", "; //handler coma pemisah
		$query.=$val. "='".$variabel[$val]."'";
	}
	$query.=" WHERE ".$id_name."='".$id_val."'"; //echo $query;
	
	//proses query
	$proses=mysql_query($query);
	if (!$proses) return("Operasi gagal!");
}
function fillForm($table_name, $element, $id_val, $id_name='id') {
	//inisialisasi array
	$hasil=array();
	if (!empty($id_val)) {
		//validasi
		$id_val = validasi($id_val); 
		
		//construct query
		$query="SELECT ";	
		foreach ($element as $val) {
			if (!isset($udah)) $udah=TRUE; else $query.=", ";
			$query.=$val;
		}
		$query.=" FROM ".$table_name." WHERE ".$id_name."='".$id_val."'"; //echo $query;
		
		//proses query
		$proses=mysql_query($query);
		if (!$proses) return("Operasi gagal!");
		$hasil=mysql_fetch_array($proses);		
	}
	return ($hasil);
}
function deleteData($table_name, $id_val, $id_name = 'id') {
	//validasi input
	if (empty($id_val)) return("Ada data yang kosong!");
	$id_val = validasi($id_val);
	
	//construct query
	$query="DELETE FROM ".$table_name." WHERE ".$id_name."='".$id_val."'"; //echo $query;
	
	//proses query
	$proses=mysql_query($query);
	if (!$proses) return("Operasi gagal!");
}
?>
