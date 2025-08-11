<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	leftpanel.php
 *********************************************************************************/

//include ("common.php");
//print '
//<form name="MyNetForm">';
if (get_session("Cookie_koperasiID") == '0') {
	if (get_session("Cookie_groupID") == '1' or get_session("Cookie_groupID") == '2' or get_session("Cookie_groupID") == '3' or get_session("Cookie_groupID") == '4' or get_session("Cookie_groupID") == '5') {
		// Admin/Manager
		$strModul = 'MODUL ' . strtoupper(get_session("Cookie_groupName"));
		if ((get_session("Cookie_userName") == 'superadmin') or (get_session("Cookie_userName") == 'admin')) {
			$strModul = '';
			$strModul = 'TETAPAN ADMIN';
		}
		if ((get_session("Cookie_groupID") == '3')) {
			$strModul = '';
			$strModul = 'TETAPAN PENYELIA';
		}
		if ((get_session("Cookie_groupID") == '4')) {
			$strModul = '';
			$strModul = 'TETAPAN PENGURUS KEWANGAN';
		}
		//DASHBOARD PROTOTYPE
		if (@$mn == 900) {
			$mn900 = "mm-collapse mm-show";
			$mu900 = "mm-active";
		} else {
			$mn900 = '';
			$mu900 = '';
		}
		echo '<li class="' . $mu900 . '">';
		TitleBarBlue("DASHBOARD", 'mdi mdi-contacts-outline');
		echo '<ul class="sub-menu ' . $mn900 . '" aria-expanded="false">';
		//MenuLink("statistic.php", "Statistik",900, @$_REQUEST['vw']);
		if (get_session("Cookie_groupID") == '1' or get_session("Cookie_groupID") == '2' or get_session("Cookie_groupID") == '3' or get_session("Cookie_groupID") == '4') {
			MenuLink("statistic.php", "Statistik", 900, @$_REQUEST['vw']);
			MenuLink("listKoperasi.php", "Senarai Koperasi", 900, @$_REQUEST['vw']);
		}
		MenuLink("memberMOF.php", "Senarai Koperasi CODE", 900, @$_REQUEST['vw']);
		MenuLink("statisticMOF.php", "Statistik Koperasi CODE", 900, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (get_session("Cookie_groupID") <> 5) {
			if (@$mn == 901) {
				$mn901 = "mm-collapse mm-show";
				$mu901 = "mm-active";
			} else {
				$mn901 = '';
				$mu901 = '';
			}
			echo '<li class="' . $mu901 . '">';
			TitleBarBlue($strModul, 'mdi mdi-shield-home-outline');
			echo '<ul class="sub-menu ' . $mn901 . '" aria-expanded="false">';
			MenuLink("mainpage.php", "Laman Utama/Buletin", 901, @$_REQUEST['vw']);

			if (get_session("Cookie_groupID") == '2') {

				//if ((get_session("Cookie_userName") == 'superadmin') OR (get_session("Cookie_userName") == 'admin')) {
				if (get_session("Cookie_groupID") == '2') {
					MenuLink("mainpage.php?page=add&id=0", "Tambah Buletin", 901, @$_REQUEST['vw']);
					MenuLink("addAdmin.php", "Tambah Kakitangan", 901, @$_REQUEST['vw']);
					MenuLink("admin.php", "Senarai Kakitangan", 901, @$_REQUEST['vw']);
				}
				//MenuLink("aktivitiLog.php", "Log Aktiviti",901, @$_REQUEST['vw']);
			}
			MenuLink("aktivitiLog.php", "Log Aktiviti", 901, @$_REQUEST['vw']);
			MenuLink("profile.php", "Tukar Katalaluan", 901, @$_REQUEST['vw']);
			echo '</ul></li>';
		}

		if (@$mn == 902) {
			$mn902 = "mm-collapse mm-show";
			$mu902 = "mm-active";
		} else {
			$mn902 = '';
			$mu902 = '';
		}
		echo '<li class="' . $mu902 . '">';
		echo '<ul class="sub-menu ' . $mn902 . '" aria-expanded="false">';
	
		if (get_session("Cookie_groupID") == '1' or get_session("Cookie_groupID") == '2' or get_session("Cookie_groupID") == '3' or get_session("Cookie_groupID") == '4') {
			echo '</ul></li>';

			if (@$mn == 903) {
				$mn903 = "mm-collapse mm-show";
				$mu903 = "mm-active";
			} else {
				$mn903 = '';
				$mu903 = '';
			}
			echo '<li class="' . $mu903 . '">';
			if (get_session("Cookie_groupID") == '1' or get_session("Cookie_groupID") == '2') {
				TitleBarBlue("INFORMASI ASAS", 'mdi mdi-book-information-variant');
			}
			echo '<ul class="sub-menu ' . $mn903 . '" aria-expanded="false">';
?>
			<li>

				<form name="MyNetForm" method="post" action="?vw=general&mn=903" style="margin-left: 3.5em;">
					<select name="selCode" class="button btn-light form-select-sm" onchange="this.form.submit()">
						<?php
						for ($i = 0; $i < count($basicList); $i++) {
							if (@$_REQUEST['selCode'] == $basicVal[$i]) {
								$sele = "selected";
							} else {
								$sele = '';
							}
							print '	<option ' . $sele . ' value="' . $basicVal[$i] . '" >' . $basicList[$i];
						}
						?>
					</select>
				</form>

			</li>
		<?php
			echo '</ul></li>';
		}

		if ((get_session("Cookie_userName") == 'superadmin') or (get_session("Cookie_userName") == 'admin') or (get_session("Cookie_groupID") == '2') or (get_session("Cookie_groupID") == '4')) {

			if (@$mn == 904) {
				$mn904 = "mm-collapse mm-show";
				$mu904 = "mm-active";
			} else {
				$mn904 = '';
				$mu904 = '';
			}
			echo '<li class="' . $mu904 . '">';
			TitleBarBlue("INFORMASI AKAUN", 'mdi mdi-chart-areaspline');
			echo '<ul class="sub-menu ' . $mn904 . '" aria-expanded="false">';


		?>
			<li>

				<form method="post" action="?vw=generalACC&mn=904" style="margin-left: 3.5em;">


					<select name="selCodeACC" class="button btn-light form-select-sm" onchange="this.form.submit()">
						<?php
						for ($i = 0; $i < count($basicListACC); $i++) {
							if (@$_REQUEST['selCodeACC'] == $basicValACC[$i]) {
								$seleACC = "selected";
							} else {
								$seleACC = '';
							}

							print '	<option ' . $seleACC . ' value="' . $basicValACC[$i] . '" >' . $basicListACC[$i];
						}
						?>
					</select>
					<!--input type="button" value="Masuk" onclick="selectCodeACC();" style="margin-bottom: 4px"-->

				</form>

			</li>
<?php
			echo '</ul></li>';
		}
		if (@$mn == 905) {
			$mn905 = "mm-collapse mm-show";
			$mu905 = "mm-active";
		} else {
			$mn905 = '';
			$mu905 = '';
		}
		echo '<li class="' . $mu905 . '">';
		TitleBarBlue("KOPERASI", 'mdi mdi-contacts-outline');
		echo '<ul class="sub-menu ' . $mn905 . '" aria-expanded="false">';


		MenuLink("member.php", "Senarai Permohonan", 905, @$_REQUEST['vw']);
		MenuLink("memberProfil.php", "Profil Koperasi", 905, @$_REQUEST['vw']);
		MenuLink("memberLanggan.php", "Langganan Koperasi", 905, @$_REQUEST['vw']);
		MenuLink("memberLangganTmt.php", "Langganan (Tamat)", 905, @$_REQUEST['vw']);
		MenuLink("training.php", "Latihan Koperasi", 905, @$_REQUEST['vw']);
		if (get_session("Cookie_groupID") <> 3 and get_session("Cookie_groupID") <> 4) {
			MenuLink("memberT.php", "Senarai Berhenti", 905, @$_REQUEST['vw']);
		}
		
		echo '</ul></li>';

		if (@$mn == 907) {
			$mn907 = "mm-collapse mm-show";
			$mu907 = "mm-active";
		} else {
			$mn907 = '';
			$mu907 = '';
		}
		echo '<li class="' . $mu907 . '">';
		//only pengurus
		if (get_session("Cookie_groupID") == '2') {
			TitleBarBlue("FPX", 'mdi mdi-cellphone-link');
			echo '<ul class="sub-menu ' . $mn907 . '" aria-expanded="false">';
			MenuLink("fpx_list.php", "Senarai Permohonan", 907, @$_REQUEST['vw']);
			echo '</ul></li>';
		}


			if (@$mn == 913) {
				$mn913 = "mm-collapse mm-show";
				$mu913 = "mm-active";
			} else {
				$mn913 = '';
				$mu913 = '';
			}
			echo '<li class="' . $mu913 . '">';
			//Modul Staff cannot view
			if (get_session("Cookie_groupID") <> 3 and get_session("Cookie_groupID") <> 1) {
				TitleBarBlue("LEJER UTAMA", 'mdi mdi-archive-outline');
				echo '<ul class="sub-menu ' . $mn913 . '" aria-expanded="false">';
				MenuLink("ACClejerList.php", "Pembuka Akaun", 913, @$_REQUEST['vw']);
				MenuLink("ACCGeneralejer.php", "General Lejer", 913, @$_REQUEST['vw']);
				echo '</ul></li>';


						if (@$mn == 908) {
			$mn908 = "mm-collapse mm-show";
			$mu908 = "mm-active";
		} else {
			$mn908 = '';
			$mu908 = '';
		}
		echo '<li class="' . $mu908 . '">';
		if (get_session("Cookie_groupID") <> 3) {
			TitleBarBlue("URUSNIAGA KOPERASI", 'mdi mdi-shopping-search');
			echo '<ul class="sub-menu ' . $mn908 . '" aria-expanded="false">';
			MenuLink("resitList.php", "Resit Koperasi", 908, @$_REQUEST['vw']);
			MenuLink("vouchersList.php", "Baucer Koperasi", 908, @$_REQUEST['vw']);
			MenuLink("memberStmtEdit.php", "Edit Import Fail", 908, @$_REQUEST['vw']);
			echo '</ul></li>';


				if (@$mn == 914) {
					$mn914 = "mm-collapse mm-show";
					$mu914 = "mm-active";
				} else {
					$mn914 = '';
					$mu914 = '';
				}
				echo '<li class="' . $mu914 . '">';
				TitleBarBlue("BUKU TUNAI", 'mdi mdi-badge-account-horizontal-outline');
				echo '<ul class="sub-menu ' . $mn914 . '" aria-expanded="false">';
				MenuLink("ACCvouchersList.php", "Pembayaran (Baucer)", 914, @$_REQUEST['vw']);
				MenuLink("ACCresitList.php", "Penerimaan (Resit)", 914, @$_REQUEST['vw']);
				MenuLink("ACCbankrecon.php", "Bank Rekonsilasi", 914, @$_REQUEST['vw']);
				echo '</ul></li>';

				if (@$mn == 915) {
					$mn915 = "mm-collapse mm-show";
					$mu915 = "mm-active";
				} else {
					$mn915 = '';
					$mu915 = '';
				}
				echo '<li class="' . $mu915 . '">';
				TitleBarBlue("PENGHUTANG", 'mdi mdi-account-cash');
				echo '<ul class="sub-menu ' . $mn915 . '" aria-expanded="false">';

				MenuLink("ACCquotationList.php", "Sebut Harga",915, @$_REQUEST['vw']);
				MenuLink("ACCinvoiceList.php", "Invois", 915, @$_REQUEST['vw']);
				MenuLink("ACCDebtorList.php", "Terima Bayaran", 915, @$_REQUEST['vw']);
				MenuLink("reportDebtor.php", "Laporan Penghutang",915, @$_REQUEST['vw']);
				echo '</ul></li>';

				if (@$mn == 916) {
					$mn916 = "mm-collapse mm-show";
					$mu916 = "mm-active";
				} else {
					$mn916 = '';
					$mu916 = '';
				}
				echo '<li class="' . $mu916 . '">';
				TitleBarBlue("PEMIUTANG", 'mdi mdi-account-details');
			}
		}
		echo '<ul class="sub-menu ' . $mn916 . '" aria-expanded="false">';
		MenuLink("ACCpurchaseList.php", "Purchase Order",916, @$_REQUEST['vw']);
		MenuLink("ACCpurchaseInvoiceList.php", "Purchase Invois", 916, @$_REQUEST['vw']);
		MenuLink("ACCbillList.php", "Bayaran Bil", 916, @$_REQUEST['vw']);
		MenuLink("reportCreditor.php", "Laporan Pemiutang",916, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 917) {
			$mn917 = "mm-collapse mm-show";
			$mu917 = "mm-active";
		} else {
			$mn917 = '';
			$mu917 = '';
		}
		echo '<li class="' . $mu917 . '">';
		TitleBarBlue("SURAT/EMEL", 'mdi mdi-ballot-recount-outline');
		echo '<ul class="sub-menu ' . $mn917 . '" aria-expanded="false">';
		MenuLink("memberLetter.php", "Senarai Surat/Emel", 917, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 918) {
			$mn918 = "mm-collapse mm-show";
			$mu918 = "mm-active";
		} else {
			$mn918 = '';
			$mu918 = '';
		}
		echo '<li class="' . $mu918 . '">';
		TitleBarBlue("LAPORAN", 'mdi mdi-clipboard-text-multiple-outline');
		echo '<ul class="sub-menu ' . $mn918 . '" aria-expanded="false">';
		MenuLink("reports.php?cat=A", "Laporan Koperasi", 918, @$_REQUEST['vw']);
		MenuLink("reports.php?cat=D", "Laporan Akaun", 918, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 919) {
			$mn919 = "mm-collapse mm-show";
			$mu919 = "mm-active";
		} else {
			$mn919 = '';
			$mu919 = '';
		}
		echo '<li class="' . $mu919 . '">';
		TitleBarBlue("PENYATA", 'mdi mdi-calculator-variant-outline');
		echo '<ul class="sub-menu ' . $mn919 . '" aria-expanded="false">';
		MenuLink("memberStmt.php", "Senarai Penyata", 919, @$_REQUEST['vw']);
		echo '</ul></li>';



		if (get_session("Cookie_groupID") == '1' or get_session("Cookie_groupID") == '2' or get_session("Cookie_groupID") == '3' or get_session("Cookie_groupID") == '4') {

		if (@$mn == 920) {
				$mn920 = "mm-collapse mm-show";
				$mu920 = "mm-active";
			} else {
				$mn920 = '';
				$mu920 = '';
			}
			echo '<li class="' . $mu920 . '">';
			TitleBarBlue("OPEN TICKET", 'mdi mdi-ticket-outline');
			echo '<ul class="sub-menu ' . $mn920 . '" aria-expanded="false">';
			MenuLink("taskList.php", "Senarai Tugasan", 920, @$_REQUEST['vw']);
			MenuLink("completedList.php", "Senarai Selesai", 920, @$_REQUEST['vw']);
			echo '</ul></li>';

		}


	} else if (get_session("Cookie_groupID") == '0') {

		$berhenti = 0;
		$sqlterm = "SELECT * FROM userdetails WHERE STATUS = 3 and userID = '" . get_session("Cookie_userID") . "'";
		$rs = &$conn->Execute($sqlterm);
		if ($rs->RowCount() <> 0) {
			$berhenti = 1;
		}

		$strModul = 'PROFIL PENGGUNA';
		if (@$mn == 4) {
			$mn4 = "mm-collapse mm-show";
			$mu4 = "mm-active";
		} else {
			$mn4 = '';
			$mu4 = '';
		}
		echo '<li class="' . $mu4 . '">';
		TitleBarBlue($strModul, 'mdi mdi-account-reactivate-outline');
		echo '<ul class="sub-menu ' . $mn4 . '" aria-expanded="false">';
		MenuLink("main", "Laman Utama/Buletin", 4, @$_REQUEST['vw']);
		if (!$berhenti) {
			MenuLink("profile.php", "Tukar Katalaluan", 4, @$_REQUEST['vw']);
			MenuLink("memberUpdate.php", "Kemaskini Profil", 4, @$_REQUEST['vw']);
		}

		MenuLink("manual.php", "Manual Bantuan", 4, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 1) {
			$mn1 = "mm-collapse mm-show";
			$mu1 = "mm-active";
		} else {
			$mn1 = '';
			$mu1 = '';
		}
		echo '<li class="' . $mu1 . '">';
		TitleBarBlue("KOPERASI", 'mdi mdi-account-box');
		echo '<ul class="sub-menu ' . $mn1 . '" aria-expanded="false">';
		if (!$berhenti) {
			MenuLink("memberSahBank.php", "Pengesahan Pengeluaran Dividen", 1, @$_REQUEST['vw']);
			MenuLink("memberSahAnggota.php", "Saksi Keanggotaan", 1, @$_REQUEST['vw']);
		}
		MenuLink("memberApplyT.php", "Mohon Berhenti", 1, @$_REQUEST['vw']);
		MenuLink("memberStatusT.php", "Status Berhenti", 1, @$_REQUEST['vw']);

		echo '</ul></li>';

		if (@$mn == 3) {
			$mn3 = "mm-collapse mm-show";
			$mu3 = "mm-active";
		} else {
			$mn3 = '';
			$mu3 = '';
		}
		echo '<li class="' . $mu3 . '">';
		TitleBarBlue("PEMBIAYAAN", 'mdi mdi-alarm-panel');
		echo '<ul class="sub-menu ' . $mn3 . '" aria-expanded="false">';
		if (!$berhenti) {
			MenuLink("biayaEdit.php", "Info Gaji", 3, @$_REQUEST['vw']);
			MenuLink("loanApply.php", "Mohon Baru", 3, @$_REQUEST['vw']);
		}
		//MenuLink("loanView.php", "Senarai Pembiayaan");
		//MenuLink("loanInProcess.php", "Dalam Proses",3, @$_REQUEST['vw']);
		//MenuLink("loanApproved.php", "Diluluskan",3, @$_REQUEST['vw']);
		//MenuLink("loanOthers.php", "Lain-Lain Status",3, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 5) {
			$mn5 = "mm-collapse mm-show";
			$mu5 = "mm-active";
		} else {
			$mn5 = '';
			$mu5 = '';
		}
		echo '<li class="' . $mu5 . '">';
		TitleBarBlue("PENJAMIN", 'mdi mdi-buffer');
		echo '<ul class="sub-menu ' . $mn5 . '" aria-expanded="false">';
		MenuLink("biayaMember.php", "Permohonan", 5, @$_REQUEST['vw']);
		MenuLink("biayaSahMember.php", "Pengesahan", 5, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 9) {
			$mn9 = "mm-collapse mm-show";
			$mu9 = "mm-active";
		} else {
			$mn9 = '';
			$mu9 = '';
		}
		echo '<li class="' . $mu9 . '">';
		TitleBarBlue("PEMBAYARAN", 'mdi mdi-clipboard-check-outline');
		echo '<ul class="sub-menu ' . $mn9 . '" aria-expanded="false">';
		MenuLink("bayaranOnline.php", "Bayaran Atas Talian", 9, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 10) {
			$mn10 = "mm-collapse mm-show";
			$mu10 = "mm-active";
		} else {
			$mn10 = '';
			$mu10 = '';
		}
		echo '<li class="' . $mu10 . '">';
		TitleBarBlue("PENYATA KOPERASI", 'mdi mdi-book-open-outline');
		echo '<ul class="sub-menu ' . $mn10 . '" aria-expanded="false">';
		MenuLink("memberStmtN.php", "Senarai Penyata", 10, @$_REQUEST['vw']);
		echo '</ul></li>';

		if (@$mn == 11) {
			$mn10 = "mm-collapse mm-show";
			$mu10 = "mm-active";
		} else {
			$mn10 = '';
			$mu10 = '';
		}
		echo '<li class="' . $mu10 . '">';
		TitleBarBlue("DIVIDEN", 'dripicons dripicons-graph-pie');
		echo '<ul class="sub-menu ' . $mn10 . '" aria-expanded="false">';
		MenuLink("reportsDIVuser.php", "Senarai Dividen", 11, @$_REQUEST['vw']);
		echo '</ul></li>';
	} else {
		$strModul = 'PELAWAT';
		$mn11 = "mm-collapse mm-show";
		echo '<li class="' . $mu11 . '">';
		TitleBarBlue($strModul . ' mdi mdi-badge-account-outline');
		echo '<ul class="sub-menu ' . $mn11 . '" aria-expanded="false">';
		MenuLink("mainpage.php", "Login", 11, @$_REQUEST['vw']);
		MenuLink("checkIC.php", "Daftar/Semakan", 11, @$_REQUEST['vw']);
		echo '</ul></li>';
	}
	//print '<tr>'.'<td>'.'&nbsp;'.'</td>'.'</tr>';
	//print '<tr>'.'<td>'.'&nbsp;'.'</td>'.'</tr>';
} ?>
<!--</form>-->
<?php

function TitleBarBlue($strTitle, $class = 'mdi mdi-email')
{

	echo '<a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="' . $class . '"></i>
                                    <span>' . ucwords(strtolower($strTitle)) . '</span>
                                </a>';
	/*
	$strImgLink1 = "images/shade-bkrm-03.gif";
	print
	'<tr>'.'<td>'
	.'<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#008080">'
	.'<tr>'
	.'<td width="14%">'
	.'&nbsp;<!--img src="'.$strImgLink2.'" width="28" height="24"-->'
	.'</td>'
	.'<td width="86%" valign="middle">'
	.'<div class="headerteal" style="width:160px;">'.strtoupper($strTitle).'</div>'
	.'</td>'
	.'</tr>'
	.'</table>'
	.'</td>'
	.'</tr>';
     * 
     */
}
function TitleBarOrange($strTitle)
{
	$strImgLink1 = "images/shade-bkrm-04.gif";
	$strImgLink2 = "images/shade-logo-bkrm-04.gif";
	print
		'<table width="100%" cellspacing="0" cellpadding="0">'
		. '<tr>'
		. '<td width="14%">'
		. '<img src="' . $strImgLink2 . '" width="28" height="24">'
		. '</td>'
		. '<td width="86%">'
		. '<div class="headerorange" style="width:160px;">' . $strTitle . '</div>'
		. '</td>'
		. '</tr>'
		. '</table>';
}

function MenuLink($strLink, $strTitle, $mnu = '', $aktif = '')
{

	if ($aktif . ".php" == $strLink) {
		$akt = "active";
	} else {
		$akt = '';
	}

	if (@$mnu != '') {
		$lnk = "&mn=$mnu";
	} else {
		$lnk = '';
	}
	//echo '<li><a href="'.$strLink.'">'.ucwords(strtolower($strTitle)).'</a></li>';
	$strLink = str_replace('?', '&', $strLink);
	$strLink = str_replace('.php', '', $strLink);
	echo '<li><a class="' . $akt . '" href="?vw=' . $strLink . $lnk . '">' . ucwords(strtolower($strTitle)) . '</a></li>';
	/*
	print
	'<tr>'
		.'<td>'
			.'<table width="100%" cellspacing="0" cellpadding="0">'
				.'<tr>'
					.'<td width="2%">'
						.'<div class="nav"><img src="images/sym-tick-red-bkrm-01.gif" width="20" height="20"></div>'
					.'</td>'
					.'<td>'
						.'<div class="nav"><a href="'.$strLink.'" target="mainFrame">'.$strTitle.'</a></div>'
					.'</td>'
				.'</tr>'
			.'</table>'
		.'</td>'
	.'</tr>';
     
     */
}

function MenuLogout()
{
	print
		'<tr>'
		. '<td>'
		. '<table width="100%" cellspacing="0" cellpadding="0">'
		. '<tr>'
		. '<td width="2%">'
		. '<div class="nav"><img src="images/sym-tick-red-bkrm-01.gif" width="20" height="20"></div>'
		. '</td>'
		. '<td>'
		. '<div class="nav"><a href="logout.php" onClick="return confirm(\'Adakah anda Pasti?\')">Keluar</a></div>'
		. '</td>'
		. '</tr>'
		. '</table>'
		. '</td>'
		. '</tr>';
}

function MenuLinkPopup($strPopup, $strTitle)
{
	print
		'<tr>'
		. '<td>'
		. '<table width="100%" cellspacing="0" cellpadding="0">'
		. '<tr>'
		. '<td width="2%">'
		. '<div class="nav">'
		. '<img src="images/sym-tick-red-bkrm-01.gif" width="20" height="20">'
		. '</div>'
		. '</td>'
		. '<td>'
		. '<div class="nav">';
	print '<a href="' . $strPopup . '" target="_blank" title="' . $strPopup . '">' . $strTitle . '</a>';
	print					'</div>'
		. '</td>'
		. '</tr>'
		. '</table>'
		. '</td>'
		. '</tr>';
}

function MenuLinkSmallPopup($strPopup, $strTitle)
{
	print
		'<tr>'
		. '<td>'
		. '<table width="100%" cellspacing="0" cellpadding="0">'
		. '<tr>'
		. '<td width="2%">'
		. '<div class="nav">'
		. '<img src="images/sym-tick-red-bkrm-01.gif" width="20" height="20">'
		. '</div>'
		. '</td>'
		. '<td>'
		. '<div class="nav">'
		. '<a href="#" onclick="window.open(\''
		. $strPopup
		. '\',\'pop\',\'top=100, left=100, width=500, height=100, scrollbars=no, resizable=no, toolbars=no, location=no, menubar=no\');">'
		. $strTitle . '</a>'
		. '</div>'
		. '</td>'
		. '</tr>'
		. '</table>'
		. '</td>'
		. '</tr>';
}

function MenuManualPopup($strPopup, $strTitle)
{
	print
		'<tr>'
		. '<td>'
		. '<table width="100%" cellspacing="0" cellpadding="0">'
		. '<tr>'
		. '<td width="2%">'
		. '<div class="nav">'
		. '<img src="images/sym-tick-red-bkrm-01.gif" width="20" height="20">'
		. '</div>'
		. '</td>'
		. '<td>'
		. '<div class="nav">'
		. '<a href="#" onclick="window.open(\''
		. $strPopup
		. '\',\'pop\',\'top=100, left=100, width=900, height=500, scrollbars=yes, resizable=yes, toolbars=no, location=no, menubar=no\');">'
		. $strTitle . '</a>'
		. '</div>'
		. '</td>'
		. '</tr>'
		. '</table>'
		. '</td>'
		. '</tr>';
}
?>
<script>
	function selectCode() {
		c = document.forms['MyNetForm'].selCode;
		parent.mainFrame.location = "general.php?cat=" + c.options[c.selectedIndex].value;
	}

	function selectCodeACC() {
		c = document.forms['MyNetForm'].selCodeACC;
		parent.mainFrame.location = "generalACC.php?cat=" + c.options[c.selectedIndex].value;
	}

	function selectLap() {
		c = document.forms['MyNetForm'].selLap;
		document.location = "reports.php?cat=" + c.options[c.selectedIndex].value;
	}

	function selectSurat() {
		c = document.forms['MyNetForm'].selSurat;
		s = "memberList.php";
		if (c.options[c.selectedIndex].value == "C" || c.options[c.selectedIndex].value == "H") {
			s = "loanList.php";
		}
		if (c.options[c.selectedIndex].value == "D" || c.options[c.selectedIndex].value == "E") {
			s = "memberListT.php";
		}
		if (c.options[c.selectedIndex].value == "F") {
			s = "dividenList.php";
		}
		document.location = s + "?code=" + c.options[c.selectedIndex].value;
	}

	function selectPop(rpt) {
		window.open(rpt + ".php", "pop", "scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");
	}

	function selectAnggota(rpt) {
		if (rpt == "rptA4" || rpt == "rptA5" || rpt == "rptA6" || rpt == "rptA7" || rpt == "rptA8" || rpt == "rptA9" ||
			rpt == "rptA10" || rpt == "rptA11" || rpt == "rptA12" || rpt == "rptA13" || rpt == "rptA14" || rpt == "rptA15" || rpt == "rptDaftarAng") {
			window.open(rpt + ".php", "pop", "scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");
		} else {
			s = "selDateOpt.php";
			url = s + "?rpt=" + rpt;
			window.open(url, "pop", "top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");
		}
	}
</script>