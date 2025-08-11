<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	reports.php
 *          Date 		: 	29/03/2004
 *********************************************************************************/
include("header.php");

if (get_session("Cookie_groupID") <> 1 and get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 3 and get_session("Cookie_groupID") <> 4 or get_session("Cookie_koperasiID") <> 0) {
	$temp = '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
	print $temp;
}

$sFileName = "?vw=reports&mn=$mn";
$sFileRef  = "?vw=reports&mn=$mn";
$title     = $lapList[array_search($cat, $lapVal)];

?>
<h5 class="card-title"><? print strtoupper($title); ?></h5>
<div style="width: 100%; text-align:left">
	<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
		<? if ($cat == 'A') { ?>
			<div>&nbsp;</div>
			<tr>
				<td class="Label" valign="top" colspan="3">
					<h6 class="card-subtitle"><u><b>LAPORAN UMUM</b></u></h6>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA14')">Senarai Keseluruhan Maklumat Koperasi (MASTER LIST)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA30')">Senarai Kategori Penubuhan Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA32')">Senarai Koperasi Aktif (DORMAN)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA28')">Senarai Koperasi Kredit (DORMAN)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA33')">Senarai Koperasi Tidak Aktif (TIDAK DORMAN)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA31')">Senarai Koperasi Bukan Kredit (TIDAK DORMAN)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectPembiayaan3('rptB5')">Senarai Keseluruhan Pembiayaan Mengikut Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA40')">Senarai Koperasi Kredit (Caj)</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA41')">Senarai Koperasi Kredit (Tidak Caj)</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN MAKLUMAT BERHENTI</u></h6>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA12a')">Senarai Permohonan Tamat Langganan Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA12')">Senarai Permohonan Koperasi Berhenti (Diluluskan)</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN LATIHAN KOPERASI</u></h6>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA26')">Senarai Koperasi Sudah Dilatih</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA27')">Senarai Koperasi Belum Dilatih</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA34')">Senarai Tarikh Latihan Koperasi</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN OPEN TICKET</u></h6>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA39')">Senarai Keseluruhan Open Ticket</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA37')">Senarai Selesai Open Ticket</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA38')">Senarai Koperasi Open Ticket Ditolak </a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN SST</u></h6>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA1')">Senarai Permohonan Penggunaan Sistem iKOOP</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA2')">Senarai Terimaan SST Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA29')">Senarai Pakej Langganan Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA35')">Senarai Keseluruhan Tarikh Tamat Langganan Koperasi</a></li>
					<li id="print" class="textFont"><a class="text-danger" href="#" onclick="selectAnggota('rptA36')">Senarai Langganan Koperasi Berdasarkan Tarikh Tamat Langganan (Pilihan Tarikh)</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>

		<? } elseif ($cat == 'B') { ?>

			<!-- <tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>PERMOHONAN</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB1')">Permohonan Pembiayaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB2')">Kelulusan Pembiayaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaanK('rptB1A')">Permohonan Pembiayaan Mengikut Koperasi</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaanK('rptB2B')">Kelulusan Pembiayaan Mengikut Koperasi</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB3')">Pembatalan Pembiayaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB2A')">Keseluruhan Pembiayaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptkomoditi')">Laporan Komoditi</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptkomoditisah')">Laporan Pengesahan Komoditi</a>
			</tr>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>SENARAI TERIMA PROSES</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('F')">Permohonan Pembiayaan Yang Diterima dan Diproses Bagi Jenis Pembiayaan Pembiayaan Peribadi</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('G')">Permohonan Pembiayaan Yang Diterima dan Diproses Bagi Jenis Pembiayaan Pembiayaan Kenderaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('H')">Permohonan Pembiayaan Yang Diterima dan Diproses Bagi Jenis Pembiayan Barangan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('rptBiayaTerima')">Pembiayaan Yang Diterima dan Diproses</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('A')">Senarai Surat Tawaran Keluar Bagi Jenis Pembiayaan Peribadi</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('B')">Senarai Surat Tawaran Keluar Jenis Pembiayaan Kenderaaan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('C')">Senarai Surat Tawaran Keluar Jenis Pembiayaan Barangan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('rptBiayaBond')">Senarai Pembiayaan Bulanan Yang Dikeluarkan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('rptBiayaJangkaan')">Kesimpulan Jangkaan Kutipan Penghutang</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('rptBiayaPecahan')">Jumlah dan Pecahan Pinjaman Bulanan</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectBiaya('rptBiayaBersara')">Senarai Bayaran Bulanan Ahli Bersara</a>


				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>EMEL KOPERASI</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptEmel')">Senarai Emel Dihantar</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>PENYATA</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptPenyataBayaran')">Penyata Kesimpulan Bayaran</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptCodeAcc')">Penyata Laporan Urusniaga</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptbank_urusniaga')">Penyata Laporan Urusniaga Bank</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptbank_resit')">Penyata Laporan Resit</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptbank_baucer')">Penyata Laporan Baucer</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptbank_yuran')">Penyata Laporan Penyata Yuran</a></li>
			</tr>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>DSR</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB1')">Permohonan Pembiayaan DSR</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB2D')">Kelulusan Pembiayaan DSR</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB3')">Pembatalan Pembiayaan DSR</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB4')">Laporan Nisbah Pembayaran Balik Hutang (DSR) </a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptB2D')">Kelulusan Pembiayaan DSR (ALL)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR3K')">Kelulusan Pembiayaan DSR (0-3000)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR3K40')">Kelulusan Pembiayaan DSR (0-3000) (&lt;=40%)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR3K41')">Kelulusan Pembiayaan DSR (0-3000) (&gt;40%)</a>

					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR5K')">Kelulusan Pembiayaan DSR (3001-5000)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR5K40')">Kelulusan Pembiayaan DSR (3001-5000) (&lt;=40%)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR5K41')">Kelulusan Pembiayaan DSR (3001-5000) (&gt;40%)</a>

					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR10K')">Kelulusan Pembiayaan DSR (5001-10000)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR10K40')">Kelulusan Pembiayaan DSR (5001-10000) (&lt;=40%)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR10K41')">Kelulusan Pembiayaan DSR (5001-10000) (&gt;40%)</a>

					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR11K')">Kelulusan Pembiayaan DSR (10001 -)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR11K40')">Kelulusan Pembiayaan DSR (10001 -) (&lt;=40%)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptDSR11K41')">Kelulusan Pembiayaan DSR (10001 -) (&gt;40%)</a>
			</tr> -->

		<? } elseif ($cat == 'D') { ?>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN UTAMA</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptA25')">Laporan Imbangan Duga (Trial Balance)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptCashFlow')">Laporan Aliran Tunai (Cash Flow)</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptACCPNL')">Laporan Profit And Loss</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptACCBS')">Laporan Balance Sheet</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptA18')">Laporan Transaksi Penyata Ledger</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptA23')">Laporan Keseluruhan Penyata Ledger</a>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaanAD('rptA22')">Laporan Penyata Ledger Mengikut Carta Akaun</a>
			</tr>

			<tr>
				<td colspan="3">
					<hr size=1>
				</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>PENYATA URUSNIAGA</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptACCbank_resit')">Laporan Transaksi Resit</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPenyata('rptACCbank_baucer')">Laporan Transaksi Baucer</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptACCbank_recon')">Laporan Transaksi Rekonsilasi Bank</a></li>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<hr size=1>
				</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>PENYATA URUSNIAGA (FPX)</u></h6>

					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptbank_onlineA')">Penyata Laporan Harian Transaksi Atas Talian </a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan('rptbank_onlineX')">Penyata Laporan Harian Transaksi Atas Talian (Tiada Transaksi)</a></li>

				</td>
			</tr>

			<tr>
				<td colspan="3">
					<hr size=1>
				</td>
			</tr>
			<tr>
				<td class="Label" valign="top">
					<h6 class="card-subtitle"><u>LAPORAN PENGHUTANG & PEMIUTANG</u></h6>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan2('ACCinvoisAll')">Laporan Statement Account</a></li>
					<li id="print" class="textFont">&nbsp;&nbsp;<a class="text-danger" href="#" onclick="selectPembiayaan2('ACCAging')">Laporan Aging</a></li>
				</td>
			</tr>

		<? } ?>

	</table>
	<?php
	include("footer.php");
	print '
<script>
	function selectDividen(rpt) {
		url = "selYear.php?rpt="+rpt+"&id=ALL";
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}
	
	function selectAsas(code) {
		window.open("rptAsas.php?code="+code ,"pop","scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");		
	}	  

	function selectAnggota(rpt) {
		if (rpt == "rptA4" || rpt == "rptA5" || rpt == "rptA6" || rpt == "rptA7" || rpt == "rptA8" || rpt == "rptA9" ||
			rpt == "rptA10" || rpt == "rptA11" || rpt == "rptA12a" || rpt == "rptA12" || rpt == "rptA13" || rpt == "rptA14" || rpt == "rptA15" || rpt == "rptA26"|| rpt == "rptA27"||
			rpt == "rptmbrBersara" ||rpt == "rptA19" ||rpt == "rptA20" || rpt == "rptA28"|| rpt == "rptA31" || rpt == "rptA29" || rpt == "rptA30" || rpt == "rptA32" || rpt == "rptA33" ||
			rpt == "rptDaftarAng" || rpt == "rptPembiayaanT" || rpt == "rptJumPotonganThn" || rpt == "rptA35" || rpt == "rptA40" || rpt == "rptA41")  {
			window.open(rpt+".php" ,"pop","scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");					
		} else {
			s = "selDateOpt.php";
			url = s + "?rpt=" + rpt;
			window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
		}
	}	  

	function selectPembiayaan(rpt) {
		s = "selDateOpt.php";
		url = s + "?rpt=" + rpt;
		window.open(url ,"pop","top=100,left=100,width=800,height=200,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	}

	function selectPembiayaanK(rpt) {
		s = "selDateOpt2.php";
		url = s + "?rpt=" + rpt;
		window.open(url ,"pop","top=100,left=100,width=800,height=200,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	}

	function selectPembiayaanAD(rpt) {
		s = "selDateOptAD.php";
		url = s + "?rpt=" + rpt;
		window.open(url ,"pop","top=100,left=0,width=1200,height=200,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	}
	
	function selectPembiayaan1(rpt) {
		s = "selDateOptN.php";
		url = s + "?rpt=" + rpt;
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	}	  

	function selectPembiayaan2(rpt) {
		url = "selYear2.php?rpt="+rpt+"";
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	} 

	function selectPembiayaan3(rpt) {
		url = "selYear3.php?rpt="+rpt+"";
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");	
		
	} 

	function selectSaham(rpt) {
		s = "selDateOpt.php";
		url = s + "?rpt=" + rpt;
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}	  	
	
	function selectUrusniaga(rpt) {
		if (rpt == "rptD1") {
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else {
			url = "selYear.php?rpt="+rpt+"&id=ALL";
		}
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}	  
	
	function selectPengurusan(rpt) {
		window.open(rpt+".php" ,"pop","scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");					
	}	  

	function selectPenyata(rpt) {
		if (rpt == "rptG1") {
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt == "rptG2Dept") {
			url = "selYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptPecahanPin"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptBakiAwlAkhir"){
			url = "selYear.php?rpt="+rpt+"&id=ALL";
     	} else if (rpt=="rptSenaraiBakiAwlAkhir"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptSenaraiUntungBulanan"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptSenaraiBakiAkhirPem"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptPecahanPinYuran"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}  else if (rpt=="rptACCbank_resit"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}  else if (rpt=="rptACCbank_baucer"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else if (rpt=="rptbank_urusniaga"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} 
		else if (rpt=="rptbank_resit"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}
		else if (rpt=="rptB4"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}
		else if (rpt=="rptbank_baucer"){
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}
		 else {
			url = "selYear.php?rpt="+rpt+"&id=ALL";
		}
		
		window.open(url ,"pop","top=100,left=100,width=750,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}	  
	
	function selectHotList(rpt) {
	
		if (rpt=="hotYuran" || rpt=="hotPembiayaan") {
			url = "selTempoh.php?rpt="+rpt;
			window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");					
		} else {
			url = rpt+".php";
			window.open (url, "mthyear","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
		}
			

	}

	function selectBiaya(rpt) {

		if (rpt == "A") {
			url = "selMthYear.php?rpt=rptBiayaKeluar&id=PRBD";
		} else 	if (rpt == "B") {
			url = "selMthYear.php?rpt=rptBiayaKeluar&id=KDRN";
		} else 	if (rpt == "C") {
			url = "selMthYear.php?rpt=rptBiayaKeluar&id=BRG";
		} else	if (rpt == "F") {
			url = "selMthYear.php?rpt=rptBiayaPermohonan&id=PRBD";
		} else	if (rpt == "G") {
			url = "selMthYear.php?rpt=rptBiayaPermohonan&id=KDRN";
		} else	if (rpt == "H") {
			url = "selMthYear.php?rpt=rptBiayaPermohonan&id=BRG";
		}  else  if (rpt == "rptBakiAwlAkhir"){
			url = "selYear.php?rpt="+rpt+"&id=ALL";
		} else  if (rpt == "rptPecahanPin"){
			url = "selYearPem.php?rpt="+rpt+"&id=ALL";
		} else	if (rpt == "rptBiayaPecahanBaki") {
			url = "selYear.php?rpt=rptBiayaPecahanBaki";
		} else{
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		}
		window.open(url ,"pop","top=100,left=100,width=650,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}	
</script>';
	?>