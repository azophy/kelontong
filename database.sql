-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2011 at 09:37 
-- Server version: 5.1.30
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `retail3`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE IF NOT EXISTS `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(15) NOT NULL,
  `id_golongan` int(11) NOT NULL,
  `barcode` int(11) NOT NULL,
  `nama` varchar(120) NOT NULL,
  `id_satuan` int(11) NOT NULL DEFAULT '1',
  `harga_beli` int(11) NOT NULL,
  `persen_markup` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode`, `id_golongan`, `barcode`, `nama`, `id_satuan`, `harga_beli`, `persen_markup`, `stok`) VALUES
(1, '001', 1, 0, 'Apa Ajalah', 0, 10000, 10, 100),
(2, '1', 1, 11, '1', 1, 1, 1, 11),
(5, '001', 1, 1234567, 'apa', 1, 1000, 10, 100);

-- --------------------------------------------------------

--
-- Table structure for table `golongan`
--

CREATE TABLE IF NOT EXISTS `golongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL DEFAULT '0',
  `kode` varchar(3) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `golongan`
--

INSERT INTO `golongan` (`id`, `id_parent`, `kode`, `nama`, `keterangan`) VALUES
(1, 0, 'COM', 'Komputer', 'aaa'),
(10, 0, 'AJA', 'aja', 'aja'),
(11, 0, 'ABI', 'abi', 'abiabiabai');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(6) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `kontak` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `kode`, `nama`, `alamat`, `telepon`, `fax`, `kontak`) VALUES
(2, 'PL-001', 'UMUM', '-TAK ADA-', '0123456789', '0123456789', 'UMUM');

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE IF NOT EXISTS `pemasok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(6) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `kontak` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id`, `kode`, `nama`, `alamat`, `telepon`, `fax`, `kontak`) VALUES
(1, 'PM-001', 'azophy', 'jl. antah berantah 2', '0123456789', '0123456789', 'azo'),
(3, 'PM-002', 'abit ganteng', 'dunia maya no. 1', '08127539608', '08127539608', 'abit faqoth');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE IF NOT EXISTS `pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pembelian_umum` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id`, `id_pembelian_umum`, `id_barang`, `jumlah`) VALUES
(1, 4, 2, 4),
(2, 5, 5, 5),
(3, 5, 2, 7),
(4, 6, 5, 10),
(5, 6, 2, 12),
(6, 7, 2, 4),
(7, 7, 5, 10),
(8, 8, 2, 20),
(9, 9, 5, 30),
(10, 10, 5, 30),
(11, 11, 5, 10),
(12, 12, 5, 10),
(13, 13, 5, 2),
(14, 14, 5, 4),
(15, 15, 5, 4),
(16, 16, 5, 5),
(17, 17, 5, 5),
(18, 18, 5, 5),
(19, 19, 5, 5),
(20, 20, 5, 5),
(21, 21, 5, 5),
(22, 22, 5, 5),
(23, 23, 5, 5),
(24, 24, 5, 5),
(25, 25, 5, 5),
(26, 26, 5, 4),
(27, 27, 2, 4),
(28, 27, 5, 5),
(29, 27, 2, 5),
(30, 28, 5, 5),
(31, 28, 2, 5),
(32, 29, 5, 5),
(33, 30, 5, 5),
(34, 31, 5, 5),
(35, 32, 5, 5),
(36, 33, 5, 5),
(37, 34, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_umum`
--

CREATE TABLE IF NOT EXISTS `pembelian_umum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_pemasok` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `pembelian_umum`
--

INSERT INTO `pembelian_umum` (`id`, `id_user`, `id_pemasok`, `waktu`, `total`) VALUES
(1, 15, 1, '2011-06-26 00:00:00', 10000),
(2, 1, 1, '2011-06-27 17:21:58', 440404),
(3, 1, 1, '2011-06-27 17:46:18', 404),
(4, 1, 1, '2011-06-27 17:46:52', 404),
(5, 1, 1, '2011-06-27 18:31:00', 550707),
(6, 1, 1, '2011-06-27 18:47:20', 1101212),
(7, 1234567, 3, '2011-06-27 18:51:56', 1100404),
(8, 1, 3, '2011-06-27 18:53:44', 2020),
(9, 1234567, 3, '2011-06-27 18:54:14', 3300000),
(10, 1234567, 1, '2011-06-27 18:54:51', 3300000),
(11, 1234567, 1, '2011-06-27 18:56:52', 1100000),
(12, 1, 1, '2011-06-27 18:58:59', 1100000),
(13, 1234567, 1, '2011-06-27 19:33:58', 220000),
(14, 1234567, 1, '2011-06-27 19:34:51', 440000),
(15, 1, 1, '2011-06-27 19:35:27', 440000),
(16, 1234567, 1, '2011-06-27 19:39:29', 550000),
(17, 1234567, 1, '2011-06-27 19:40:36', 550000),
(18, 1, 1, '2011-06-27 19:41:55', 550000),
(19, 1, 1, '2011-06-27 19:41:58', 550000),
(20, 1, 1, '2011-06-27 19:42:24', 550000),
(21, 1234567, 1, '2011-06-27 19:43:19', 550000),
(22, 1234567, 1, '2011-06-27 22:05:14', 550000),
(23, 1, 1, '2011-06-27 22:05:20', 550000),
(24, 1, 1, '2011-06-27 22:05:41', 550000),
(25, 1234567, 1, '2011-06-27 22:06:12', 550000),
(26, 1234567, 1, '2011-06-27 22:09:22', 440000),
(27, 1, 1, '2011-06-28 19:58:43', 550909),
(28, 1, 1, '2011-06-28 20:06:30', 550505),
(29, 1234567, 1, '2011-06-28 20:06:42', 550000),
(30, 1234567, 1, '2011-06-29 15:25:57', 550000),
(31, 1, 1, '2011-06-29 15:26:37', 550000),
(32, 1, 1, '2011-06-29 15:28:15', 550000),
(33, 1, 1, '2011-06-29 15:29:00', 550000),
(34, 1, 1, '2011-06-29 15:29:25', 550000);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `id_status` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `nama_lengkap`, `id_status`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE IF NOT EXISTS `penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan_umum` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`id`, `id_penjualan_umum`, `id_barang`, `jumlah`) VALUES
(1, 1, 5, 5),
(2, 2, 5, 5),
(3, 3, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_umum`
--

CREATE TABLE IF NOT EXISTS `penjualan_umum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `total` int(11) NOT NULL,
  `potongan` int(11) NOT NULL,
  `jasa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `penjualan_umum`
--

INSERT INTO `penjualan_umum` (`id`, `id_user`, `id_pelanggan`, `waktu`, `total`, `potongan`, `jasa`) VALUES
(1, 1, 2, '2011-06-29 22:16:55', 550000, 0, 0),
(2, 1, 2, '2011-06-29 22:19:55', 550000, 0, 0),
(3, 1, 2, '2011-06-29 22:20:14', 505, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE IF NOT EXISTS `satuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id`, `nama`) VALUES
(1, 'Buah'),
(2, 'Batang'),
(3, 'Dus'),
(4, 'Bungkus'),
(5, 'Kardus'),
(6, 'Karton'),
(7, 'Lembar'),
(8, 'Sashet'),
(9, 'Potong'),
(10, 'Paket'),
(11, 'Tablet'),
(12, 'Botol');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `nama`) VALUES
(1, 'Admin'),
(2, 'Kasir');

-- --------------------------------------------------------

--
-- Table structure for table `priviledge`
--

CREATE TABLE IF NOT EXISTS `priviledge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL,
  `id_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `priviledge`
--

INSERT INTO `priviledge` (`id`, `page`, `id_status`) VALUES
(1, 'ajax_handler.php', 1),
(2, 'ajax_handler.php', 2),
(3, 'ajax_handler.php', 3),
(4, 'data_barang.php', 1),
(5, 'data_barang.php', 3),
(6, 'data_golongan.php', 1),
(7, 'data_golongan.php', 3),
(8, 'data_pelanggan.php', 1),
(9, 'data_pelanggan.php', 3),
(10, 'data_pemasok.php', 1),
(11, 'data_pemasok.php', 3),
(12, 'data_pengguna.php', 1),
(13, 'data_pengguna.php', 3),
(14, 'laporan.php', 1),
(15, 'laporan.php', 3),
(16, 'laporan_print.php', 1),
(17, 'laporan_print.php', 3),
(18, 'transaksi_pembelian.php', 1),
(19, 'transaksi_pembelian.php', 2),
(20, 'transaksi_penjualan.php', 1),
(21, 'transaksi_penjualan.php', 2),
(22, 'cetak_nota.php', 1),
(23, 'cetak_nota.php', 2),
(24, 'index.php', 0),
(25, 'login.php', 0),
(26, 'logout.php', 0);

