<div id="menubar">
				<ul id="menu">
					<li><a href="#" class="top">Main</a>
						<ul class="under">
							<li><a href="index.php">Home</a></li>
							<li><?php 
							if (isLoggedIn()) {
								echo '<a href="logout.php">Log Out</a>';
							} else {
								echo '<a href="login.php">Log In</a>'; 
							} ?></li>
						</ul>
					</li>
					<li><a href="#" class="top">Data</a>
						<ul class="under">
							<li><a href="data_golongan.php">Data Golongan</a></li>
							<li><a href="data_barang.php">Data Barang</a></li>
							<li><a href="data_pemasok.php">Data Pemasok</a></li>
							<li><a href="data_pelanggan.php">Data Pelanggan</a></li>
							<li><a href="data_pengguna.php">Data Pengguna</a></li>
						</ul>
					</li>
					<li><a href="#" class="top">Transaksi</a>
						<ul class="under wide">
							<li><a href="transaksi_pembelian.php">Transaksi Pembelian</a></li>
							<li><a href="transaksi_penjualan.php">Transaksi Penjualan</a></li>
						</ul>
					</li>
					<li><a href="#" class="top last">Laporan</a>
						<ul class="under wide">
							<li><a href="laporan.php">Laporan Usaha</a></li>
						</ul>
					</li>
				</ul>
			</div>
