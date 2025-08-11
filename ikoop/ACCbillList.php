<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename	: 	ACCbillList.php
 *          Date 		: 	04/8/2006
 *********************************************************************************/
if (!isset($mm))	$mm = "ALL"; //date("m");
if (!isset($yy))	$yy = date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);
if (!isset($StartRec))	$StartRec = 1;
if (!isset($pg))		$pg = 10;
if (!isset($q))			$q = "";
if (!isset($jenis_cari))	$jenis_cari = "";
if (!isset($code))		$code = "ALL";
if (!isset($filter))	$filter = "0";
if (!isset($credit))	$credit = "";

include("header.php");
include("koperasiQry.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$koperasiID = dlookup("setup", "koperasiID", "setupID=" . tosql(1, "Text"));

if (get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 4 or get_session("Cookie_koperasiID") <> $koperasiID) {
	print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$sFileName  	= "?vw=ACCbillList&mn=$mn";
$sFileRef   	= "?vw=ACCbillpembayaran&mn=$mn";
$sFileRefPI 	= "?vw=ACCpurchaseInvoice&mn=$mn";
$sFileRefNote  	= "?vw=ACCdebitNote&mn=$mn"; // file ni pergi mane
$title      	=  "Pembayaran Bil";

$IDName = get_session("Cookie_userName");
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$sWhere = "no_bill=" . tosql($pk[$i], "Text");
		$sSQL 	= "DELETE FROM billacc WHERE " . $sWhere;
		$rs 	= &$conn->Execute($sSQL);
		$sWhere = "docNo=" . tosql($pk[$i], "Text"); //new 

		$docNo = dlookup("transactionacc", "docNo", $sWhere);

		$sSQL 	= "DELETE FROM transactionacc WHERE " . $sWhere;
		$rs 	= &$conn->Execute($sSQL);

		$strActivity = $_POST['Submit'] . 'Bayaran Bil Dihapuskan - ' . $docNo;
		activityLog($sSQL, $strActivity, get_session('Cookie_userID'), get_session('Cookie_userName'), 3);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

//if no_bill is 1 then meaning it is not bulk payment
$subQuery = "
SELECT no_bill 
FROM billacc 
GROUP BY no_bill 
HAVING COUNT(no_bill) = 1
";

$sSQL 	= "";
$sWhere = "  YEAR(tarikh_bill) = " . $yy;
$sWhere = " (a.no_bill IN ($subQuery) AND a.PINo IS NOT NULL)";
$sWhere .= " AND year( tarikh_bill ) = " . $yy;
if ($q <> "" || $credit <> "") {
	if ($by == 1) {
		$sWhere .= " AND A.batchNo = B.ID";
		$sWhere .= " AND B.name like '%" . $q . "%'";
	} else if ($by == 2) {
		$sWhere .= " AND A.no_bill like '%" . $q . "%'";
	} else if ($by == 3) {
		if (strpos($q, 'opening') !== false || strpos($q, 'balance') !== false) {
			// If 'opening', 'balance', or 'opening balance' is found in the search query
			$sWhere .= " AND A.PINo = ''";
		} else {
			$sWhere .= " AND A.PINo like '%" . $q . "%'";
		}
	} else if ($by == 4) {
		$sWhere .= " AND A.diterima_drpd = $credit";
	}
}

$sWhere = " WHERE (" . $sWhere . ")";

$sSQL = "SELECT *, b.name, b.g_lockstat 
    FROM billacc a 
    LEFT JOIN generalacc b ON a.batchNo = b.ID
    ";

if ($mm <> "ALL") $sWhere .= " AND MONTH(A.tarikh_bill) =" . $mm;
$sSQL = $sSQL . $sWhere . ' ORDER BY A.no_bill DESC';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$GetReceipts 	= &$conn->Execute($sSQL);
$GetReceipts->Move($StartRec - 1);

$TotalRec 		= $GetReceipts->RowCount();
$TotalPage		=  ($TotalRec / $pg);
$jenisList_cari = array('No Anggota', 'Nama');
$jenisVal_cari 	= array(1, 2);

$sqlYears 	= "SELECT DISTINCT YEAR(tarikh_bill) AS year FROM billacc WHERE tarikh_bill IS NOT NULL AND tarikh_bill != '' AND tarikh_bill != 0 ORDER BY year ASC";
$rsYears 	= $conn->Execute($sqlYears);

$creditorList 	= array();
$creditorVal  	= array();
$sSQLCreditor 	= "SELECT name AS creditorName, ID AS creditorID FROM generalacc WHERE category = 'AB' ORDER BY ID ASC";
$rsCreditor 	= &$conn->Execute($sSQLCreditor);
if ($rsCreditor->RowCount() <> 0) {
	while (!$rsCreditor->EOF) {
		array_push($creditorList, $rsCreditor->fields(creditorName));
		array_push($creditorVal, $rsCreditor->fields(creditorID));
		$rsCreditor->MoveNext();
	}
}

print '<div class="table-responsive">
<form name="MyForm" action=' . $sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="code" value="' . $code . '">
<input type="hidden" name="filter" value="' . $filter . '">
<h5 class="card-title">' . strtoupper($title) . ' &nbsp;</h5>
<div clas="row">
    Bulan   
			<select name="mm" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="ALL"';
if ($mm == "ALL") print 'selected';
print '>- Semua -';
for ($j = 1; $j < 13; $j++) {
	print '	<option value="' . $j . '"';
	if ($mm == $j) print 'selected';
	print '>' . $j;
}
print '		</select>
			Tahun 
			<select name="yy" class="form-select-sm" onchange="document.MyForm.submit();">';
while (!$rsYears->EOF) {
	$year = $rsYears->fields['year'];
	print '	<option value="' . $year . '"';
	if ($yy == $year) print 'selected';
	print '>' . $year;
	$rsYears->MoveNext();
}
print '		</select>
			<input type="submit" name="action1" value="Capai" class="btn btn-sm btn-secondary">
</div><br/>
<div clas="row">
Carian Melalui
<select name="by" class="form-select-sm" onchange="toggleSearchFields(this.value);">';
// if ($by == 1)	print '<option value="1" selected>Nama Batch</option>'; 			else print '<option value="1">Nama Batch</option>';				
if ($by == 2)	print '<option value="2" selected>No. Bill</option>';
else print '<option value="2">No. Bill</option>';
if ($by == 3)	print '<option value="3" selected>No. Purchase Invois</option>';
else print '<option value="3">No. Invois</option>';
if ($by == 4)	print '<option value="4" selected>Nama Syarikat</option>';
else print '<option value="4">Nama Syarikat</option>';
print '</select>';

// Dropdown for selecting creditor
print '&nbsp;<select id="creditDropdown" name="credit" class="form-select-sm" style="display: ';
print ($by == 4) ? 'inline-block' : 'none';
print ';" onchange="document.MyForm.submit();">
        <option value="">- Semua -';
for ($i = 0; $i < count($creditorList); $i++) {
	print '<option value="' . $creditorVal[$i] . '" ';
	if ($credit == $creditorVal[$i]) print ' selected';
	print '>' . $creditorList[$i];
}
print '</select>';

// Input box for searching
print '<input id="searchInput" type="text" name="q" value="" maxlength="50" size="30" class="form-control-sm" style="display: ';
print ($by != 4) ? 'inline-block' : 'none';
print ';">
<input type="submit" class="btn btn-sm btn-secondary" value="Cari">';

print '&nbsp;&nbsp;
			<input type="button" class="btn btn-sm btn-primary" value="Tambah" onClick="location.href=\'' . $sFileRef . '&action=new&jenis=' . $jenis . '\';">';

if (($IDName == 'admin') or ($IDName == 'superadmin')) {
	print '&nbsp; <input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');"> ';
}

print '';

//--------------------------------------------------Top right display total invoice and payment depends on year and month---------START

// query total payment
$sqljumlahPayment = "SELECT SUM(pymtAmt - balance) AS totalPayment,
					 GROUP_CONCAT(PINo) AS docNoInv 
                     FROM billacc 
					 WHERE YEAR(tarikh_bill) = $yy";

// concat query if any month is selected
if ($mm !== "ALL") {
	$sqljumlahPayment .= " AND MONTH(tarikh_bill) = $mm";
	$stringDesc = "Bulan $mm ";
}

$rsjumlahPayment = $conn->Execute($sqljumlahPayment);
$totalPayment = $rsjumlahPayment->fields['totalPayment'];
$docNoInv = $rsjumlahPayment->fields['docNoInv'];

// handle case where no invNo is found
if (empty($docNoInv)) {
	$docNoInv = 'NULL'; // avoids SQL error in IN clause
} else {
	$docNoInv = "'" . str_replace(',', "','", $docNoInv) . "'";
}
// Query total invoice from normal invoice and also company's opening balance
$sqljumlahInv = "SELECT SUM(pymtAmt) AS totalInvoice
                 FROM billacc
                 WHERE PINo IN ($docNoInv) 
				 AND PINo != ''
                 UNION ALL
                 SELECT SUM(pymtAmt) AS totalInvoice
                 FROM billacc
                 WHERE PINo = ''
				 AND YEAR(tarikh_bill) = $yy
				 AND (diterima_drpd, id) IN ( SELECT diterima_drpd, MIN(ID) 
				 FROM billacc 
				 WHERE PINo = '' 
				 AND YEAR(tarikh_bill) = $yy GROUP BY diterima_drpd )";
// Concatenate query if any month is selected
if ($mm !== "ALL") {
	$sqljumlahInv .= " AND MONTH(tarikh_bill) = $mm";
	$stringDesc = "Bulan $mm ";
}

$rsjumlahInv = $conn->Execute($sqljumlahInv);

// Initialize totalInvoice fields
$totalInvoice1 = 0;
$totalInvoice2 = 0;

// Process the result set
if ($rsjumlahInv && !$rsjumlahInv->EOF) {
	$totalInvoice1 = isset($rsjumlahInv->fields['totalInvoice']) ? $rsjumlahInv->fields['totalInvoice'] : 0;
	$rsjumlahInv->MoveNext();
	$totalInvoice2 = isset($rsjumlahInv->fields['totalInvoice']) ? $rsjumlahInv->fields['totalInvoice'] : 0;
}

// Sum both totalInvoice values
$totalInvoice = $totalInvoice1 + $totalInvoice2;

// Display totals
print '
<div style="position: absolute; top: 25px; right: 25px;">
	<table border="0" cellspacing="0" cellpadding="5" width="100%">
		<tr class="table-warning">
			<td nowrap align="right" style="padding-right: 5px; width: 500%;" colspan="2"><b><u>Jumlah Pada ' . $stringDesc . ' Tahun ' . $yy . '</u></b></td>
		</tr>
		<tr class="table-warning">
			<td nowrap align="right" style="padding-right: 5px; width: 500%;"><b>Jumlah Invois (RM):</b></td>
			<td nowrap align="right" style="padding-right: 5px; width: 50%;">' . number_format($totalInvoice, 2) . '</td>
		</tr>
		<tr class="table-warning">
			<td nowrap align="right" style="padding-right: 5px; width: 500%;"><b>Jumlah Bayaran (RM):</b></td>
			<td nowrap align="right" style="padding-right: 5px; width: 50%;">' . number_format($totalPayment, 2) . '</td>
		</tr>
	</table>
</div>';

//--------------------------------------------------Top right display total invoice and payment depends on year and month---------END

print '
</div>
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
    <tr valign="top" class="Header">
	   	<td align="left" >
		</td>
	</tr>';
if ($GetReceipts->RowCount() <> 0) {
	$bil = $StartRec;
	$cnt = 1;
	print '
		<tr valign="top" class="textFont">
			<td>
				<table width="100%">
					<tr>
						<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>
						<td align="right" class="textFont">';
	echo papar_ms($pg);
	print '</td>
					</tr>
				</table>
			</td>
		</tr>
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-primary">
						<td nowrap align="center">&nbsp;</td>
						<td nowrap style="text-align: left; vertical-align: bottom;"><b>No. Bill</b></td>
						<td nowrap style="text-align: center; vertical-align: bottom;"><b>Nama Batch</b></td>
						<td nowrap style="text-align: center; vertical-align: bottom;"><b>Tarikh</b></td>
						<td nowrap style="text-align: left; vertical-align: bottom;"><b>Nama Syarikat</b></td>
						<td nowrap><div style="text-align: center; white-space: nowrap;"><b>No. Purchase Invois/</b><br><b>Opening Balance</b></div></td>			
						<td nowrap><div style="text-align: right; white-space: nowrap;"><b>Amaun Purchase Invois/</b><br><b>Tunggakan (RM)</b></div></td>
						<td nowrap><div style="text-align: right; white-space: nowrap;"><b>Jumlah</b><br><b>Bayaran (RM)</b></div></td>
						<td nowrap style="text-align: right; vertical-align: bottom;"><b>Baki (RM)</b></td>
						<td nowrap style="text-align: center; vertical-align: bottom;"><b>Action</b></td>
					</tr>';

	// Determining debit note -------------START
	$sqlDebitNote = "
			SELECT DISTINCT docNo
			FROM transactionacc 
			WHERE status IN (6) AND addminus IN (0)";
	$rsDebitNote = $conn->Execute($sqlDebitNote);

	$debitNoteInvList = array();
	if ($rsDebitNote->RowCount() <> 0) {
		while (!$rsDebitNote->EOF) {
			array_push($debitNoteInvList, $rsDebitNote->fields('docNo'));
			$rsDebitNote->MoveNext();
		}
	}
	// Determining debit note -------------END

	$DRTotal = 0;
	$CRTotal = 0;
	while (!$GetReceipts->EOF && $cnt <= $pg) {
		$status = $GetReceipts->fields(status);
		$colorStatus = "Data";
		if ($status == 1) $colorStatus = "greenText";
		if ($status == 2) $colorStatus = "redText";
		$jumlah = 0;
		$tarikh_bill = toDate("d/m/y", $GetReceipts->fields(tarikh_bill));

		if ($GetReceipts->fields(PINo) <> '') {

			if (in_array($GetReceipts->fields(PINo), $debitNoteInvList)) { //if debit note then open debit note
				$PINo 	 = $sFileRefNote . '&action=view&PINo=' . $GetReceipts->fields(PINo) . '&yy=' . $yy . '&mm=' . $mm;
			} else { //if not debit note then open normal invoice
				$PINo 	 = $sFileRefPI . '&action=view&PINo=' . $GetReceipts->fields(PINo) . '&yy=' . $yy . '&mm=' . $mm;
			}
		} else $PINo = "Opening Balance";

		$namabatch 	= dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(batchNo), "Text"));
		//$bank 	= dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(kod_bank), "Text"));
		$namacomp 	= dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(diterima_drpd), "Text"));
		//$addminus = $GetLoan->fields(addminus);

		$akaun 		= dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(deductID), "Text"));
		$akaunno 	= dlookup("generalacc", "code", "ID=" . tosql($GetReceipts->fields(deductID), "Text"));

		$amaun 		= $GetReceipts->fields(pymtAmt);
		$balance 	= $GetReceipts->fields(balance);
		$bayaran 	= $amaun - $balance;

		$cetak 		= '<i class="mdi mdi-printer text-primary" title="cetak" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCBillPrintCustomer.php?id=' . $GetReceipts->fields(no_bill) . '\')"></i>';
		$edit 		= '<a href="' . $sFileRef . '&action=view&no_bill=' . tohtml($GetReceipts->fields['no_bill']) . '&yy=' . $yy . '&mm=' . $mm . '" title="kemaskini"><i class="mdi mdi-lead-pencil text-warning" style="font-size: 1.4rem;"></i></a>';
		$editLock 	= '<span style="cursor: not-allowed; color: gray; opacity: 0.5;"><i class="mdi mdi-lead-pencil" style="font-size: 1.4rem; opacity: 0.5;"></i></span>';
		$view 		= '<i class="mdi mdi-file-document text-muted" title="lihat" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCBillViewCustomer.php?id=' . $GetReceipts->fields(no_bill) . '\')"></i>';

		$sSQL2 = "SELECT g_lockstat FROM generalacc WHERE ID = " . $GetReceipts->fields(batchNo) . " ORDER BY ID";
		$rsDetail = &$conn->Execute($sSQL2);

		print ' <tr>
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $bil . '</td>';
		if ($rsDetail->fields(g_lockstat) == 1) {
			print '
		<td class="Data" style="text-align: left; vertical-align: middle;"><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetReceipts->fields(no_bill)) . '">
		' . $GetReceipts->fields(no_bill) . '</td>';
		} else {
			print '
		<td class="Data" style="text-align: left; vertical-align: middle;"><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetReceipts->fields(no_bill)) . '">
		<a href="' . $sFileRef . '&action=view&no_bill=' . tohtml($GetReceipts->fields(no_bill)) . '&yy=' . $yy . '&mm=' . $mm . '">
		' . $GetReceipts->fields(no_bill) . '</td>';
		}

		print '
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $namabatch . '</td>
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $tarikh_bill . '</td>	
		<td class="Data" style="text-align: left; vertical-align: middle;">' . $namacomp . '</td>';
		if ($GetReceipts->fields(PINo) <> '') print ' <td class="Data" style="text-align: center; vertical-align: middle;"><a href="' . $PINo . '">' . $GetReceipts->fields(PINo) . '</td>';
		else print ' <td class="Data" style="text-align: center; vertical-align: middle;"><span style="color: blue;">Opening Balance</span></td>';
		print '
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($amaun, 2) . '</td>
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($bayaran, 2) . '</td>
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($balance, 2) . '</td>
		';
		if (($rsDetail->fields('g_lockstat') == 1) && ($GetReceipts->fields('batchNo') <> "")) {
			print '
		<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $editLock . '&nbsp;&nbsp;' . $view . '</td>
		';
		} else {
			print '
		<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $edit . '&nbsp;&nbsp;' . $view . '</td>
		';
		}
		print '	</tr>';
		$cnt++;
		$bil++;
		$GetReceipts->MoveNext();
	}
	$GetReceipts->Close();

	print '	</table>
			</td>
		</tr>	
		<tr>
			<td>';
	if ($TotalRec > $pg) {
		print '
					<table border="0" cellspacing="5" cellpadding="0"  class="textFont" width="100%">';
		if ($TotalRec % $pg == 0) {
			$numPage = $TotalPage;
		} else {
			$numPage = $TotalPage + 1;
		}
		print '<tr><td class="textFont" valign="top" align="left">Rekod Dari : <br>';
		for ($i = 1; $i <= $numPage; $i++) {
			if (is_int($i / 10)) print '<br />';
			print '<A href="' . $sFileName . '&yy=' . $yy . '&mm=' . $mm . '&code=' . $code . '&filter=' . $filter . '&StartRec=' . (($i * $pg) + 1 - $pg) . '&pg=' . $pg . '">';
			print '<b><u>' . (($i * $pg) - $pg + 1) . '-' . ($i * $pg) . '</u></b></a>&nbsp;&nbsp;';
		}
		print '</td>
						</tr>
					</table>';
	}
	print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Baucer : <b>' . $GetReceipts->RowCount() . '</b></td>
		</tr>';
} else {
	if ($q == "") {
		print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk ' . $title . ' Bagi Bulan/Tahun - ' . $mm . '/' . $yy . ' -</b><hr size=1"></td></tr>';
	} else {
		print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "' . $q . '" tidak jumpa  -</b><hr size=1"></td></tr>';
	}
}
print ' 
</table>
</form></div>';

include("footer.php");

print '
<script language="JavaScript">

	function open_(url) {
		window.open(url,"pop","top=10,left=10,width=990,height=600, scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");
	}

	var allChecked=false;
	function ITRViewSelectAll() {
	    e = document.MyForm.elements;
	    allChecked = !allChecked;
	    for(c=0; c< e.length; c++) {
	      if(e[c].type=="checkbox" && e[c].name!="all") {
	        e[c].checked = allChecked;
	      }
	    }
	}

	function ITRActionButtonClick(v) {
	      e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
	            count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak dihapuskan.\');
	        } else {
	          if(confirm(count + \' rekod hendak dihapuskan?\')) {
	            e.action.value = v;
	            e.submit();
	          }
	        }
	      }
	    }	   

	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '&yy=' . $yy . '&mm=' . $mm . '&code=' . $code . '&filter=' . $filter . '&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

	function toggleSearchFields(selectedValue) {
		var creditDropdown = document.getElementById("creditDropdown");
		var searchInput = document.getElementById("searchInput");
		if (selectedValue == 4) {
			creditDropdown.style.display = "inline-block";
			searchInput.style.display = "none";
		} else {
			creditDropdown.style.display = "none";
			searchInput.style.display = "inline-block";
		}
	}

</script>';