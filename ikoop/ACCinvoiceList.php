<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename	: 	ACCinvoiceList.php
 *          Date 		: 	04/8/2006
 *********************************************************************************/
if (!isset($mm))	$mm = "ALL"; //date("m");
if (!isset($yy))	$yy = date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);
date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($StartRec))	$StartRec = 1;
if (!isset($pg))		$pg = 30;
if (!isset($q))			$q = "";
if (!isset($code))		$code = "ALL";
if (!isset($filter))	$filter = "0";
if (!isset($debt))		$debt = "";

include("header.php");
include("koperasiQry.php");

$koperasiID = dlookup("setup", "koperasiID", "setupID=" . tosql(1, "Text"));

if (get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 4 or get_session("Cookie_koperasiID") <> $koperasiID) {
	print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>'; //dari mana file ni
}

$sFileName = "?vw=ACCinvoiceList&mn=$mn"; //file name
$sFileRef  = "?vw=ACCinvoicedebtor&mn=$mn"; // file ni pergi mane
$sFileRefPay  = "?vw=ACCDebtorPayment&mn=$mn"; // file ni pergi mane
$title     =  "Invois Penghutang"; //Title 

$IDName = get_session("Cookie_userName");


//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {

		$sWhere = "invNo=" . tosql($pk[$i], "Text");
		$sSQL 	= "DELETE FROM cb_invoice WHERE " . $sWhere;
		$rs 	= &$conn->Execute($sSQL);

		$sWhere = "docNo=" . tosql($pk[$i], "Text"); //new 

		$docNo = dlookup("transactionacc", "docNo", $sWhere);

		$sSQL 	= "DELETE FROM transactionacc WHERE " . $sWhere;
		$rs 	= &$conn->Execute($sSQL);

		$strActivity = $_POST['Submit'] . ' Invois Dihapuskan - ' . $docNo;
		activityLog($sSQL, $strActivity, get_session('Cookie_userID'), get_session('Cookie_userName'), 3);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sSQL = "";
$sWhereYear = " WHERE c.status NOT IN (5) AND (YEAR(tarikh_inv) = " . $yy . " OR tarikh_inv = '0000-00-00' OR tarikh_inv is NULL)"; //status 5 is credit note

if ($q <> "" || $debt <> "") {
	if ($by == 1) {
		$sWhere .= " AND A.batchNo = B.ID";
		$sWhere .= " AND B.name like '%" . $q . "%'";
	} else if ($by == 2) {
		$sWhere .= " AND A.invNo like '%" . $q . "%'";
	} else if ($by == 3) {
		$sWhere .= " AND A.companyID = $debt";
	}
}

if ($q <> "" || $debt <> "") $sWhere = " $sWhereYear $sWhere";
else $sWhere = " $sWhereYear";

if ($q <> "" || $debt <> "") {
	if ($by == 1 or $by == 3) {
		$sSQL = "SELECT	DISTINCT A.* FROM cb_invoice A
			LEFT JOIN generalacc b ON a.batchNo = b.ID
			LEFT JOIN transactionacc c ON a.invNo = c.docNo";
	} else if ($by == 2) {
		$sSQL = "SELECT	DISTINCT A.* FROM cb_invoice A
			LEFT JOIN transactionacc c ON a.invNo = c.docNo";
	}
} else {
	$sSQL = "SELECT	DISTINCT A.* FROM cb_invoice A 
		LEFT JOIN transactionacc c ON a.invNo = c.docNo
		";
}
//if($mm <> "ALL") $sWhere .= " AND month( A.createdDate ) =" .$mm;
if ($mm <> "ALL") $sWhere .= " AND MONTH(A.tarikh_inv) =" . $mm;
$sSQL = $sSQL . $sWhere . ' ORDER BY A.tarikh_inv DESC';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$GetBaucers = &$conn->Execute($sSQL);
$GetBaucers->Move($StartRec - 1);

$TotalRec 	= $GetBaucers->RowCount();
$TotalPage 	=  ($TotalRec / $pg);

$sqlYears 	= "SELECT DISTINCT YEAR(tarikh_inv) AS year FROM cb_invoice WHERE tarikh_inv IS NOT NULL AND tarikh_inv != '' AND tarikh_inv != 0 ORDER BY year ASC";
$rsYears 	= $conn->Execute($sqlYears);

$debtorList = array();
$debtorVal  = array();
$sSQLDebtor = "SELECT name AS debtorName, ID AS debtorID FROM generalacc WHERE category = 'AC' ORDER BY ID ASC";
$rsDebtor 	= &$conn->Execute($sSQLDebtor);
if ($rsDebtor->RowCount() <> 0) {
	while (!$rsDebtor->EOF) {
		array_push($debtorList, $rsDebtor->fields(debtorName));
		array_push($debtorVal, $rsDebtor->fields(debtorID));
		$rsDebtor->MoveNext();
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
if ($by == 1)	print '<option value="1" selected>Nama Batch</option>';
else print '<option value="1">Nama Batch</option>';
if ($by == 2)	print '<option value="2" selected>No. Invoice</option>';
else print '<option value="2">No. Invoice</option>';
if ($by == 3)	print '<option value="3" selected>Nama Syarikat</option>';
else print '<option value="3">Nama Syarikat</option>';
print '</select>';

// Dropdown for selecting debtor
print '&nbsp;<select id="debtDropdown" name="debt" class="form-select-sm" style="display: ';
print ($by == 3) ? 'inline-block' : 'none';
print ';" onchange="document.MyForm.submit();">
        <option value="">- Semua -';
for ($i = 0; $i < count($debtorList); $i++) {
	print '<option value="' . $debtorVal[$i] . '" ';
	if ($debt == $debtorVal[$i]) print ' selected';
	print '>' . $debtorList[$i];
}
print '</select>';

// Input box for searching
print '<input id="searchInput" type="text" name="q" value="" maxlength="50" size="30" class="form-control-sm" style="display: ';
print ($by != 3) ? 'inline-block' : 'none';
print ';">
<input type="submit" class="btn btn-sm btn-secondary" value="Cari">';

print '&nbsp;&nbsp
		<input type="button" class="btn btn-sm btn-primary" value="Tambah" onClick="location.href=\'' . $sFileRef . '&action=new\';">';

if (($IDName == 'admin') or ($IDName == 'superadmin')) {
	print '&nbsp; <input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">';
}

print '
</div>';

//--------------------------------------------------Top right display total invoice and payment depends on year and month---------START

// query total invoice based on year selected
$sqljumlahInv = "SELECT SUM(outstandingbalance) AS totalInvoice,
				 GROUP_CONCAT(DISTINCT a.invNo) AS docNoInv 
                 FROM cb_invoice a
				 WHERE
				 a.invNo IN (
					SELECT DISTINCT c.docNo 
					FROM transactionacc c 
					WHERE c.status NOT IN (5)
				 )	
                 AND YEAR(a.tarikh_inv) = $yy";

// concat query if any month is selected
if ($mm !== "ALL") {
	$sqljumlahInv .= " AND MONTH(tarikh_inv) = $mm";
	$stringDesc = "Bulan $mm ";
}

$rsjumlahInv = $conn->Execute($sqljumlahInv);
$totalInvoice = $rsjumlahInv->fields['totalInvoice'];
$docNoInv = $rsjumlahInv->fields['docNoInv'];

// handle case where no invNo is found
if (empty($docNoInv)) {
	$docNoInv = 'NULL'; // avoids SQL error in IN clause
} else {
	$docNoInv = "'" . str_replace(',', "','", $docNoInv) . "'";
}

// query total payment
$sqljumlahPayment = "SELECT SUM(outstandingbalance - balance) AS totalPayment 
                     FROM cb_payments 
                     WHERE invNo IN ($docNoInv)";

$rsjumlahPayment = $conn->Execute($sqljumlahPayment);
$totalPayment = $rsjumlahPayment->fields['totalPayment'];

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
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">

    <tr valign="top" class="Header">
	   	<td align="left" >
	 </td>
	</tr>';
if ($GetBaucers->RowCount() <> 0) {
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
		</tr>';
	print '
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-primary">
						<td nowrap>&nbsp;</td>
						<td nowrap><b>No. Invois</b></td>
						<td nowrap align="center"><b>Nama Batch</b></td>
						<td nowrap align="center"><b>Tarikh</b></td>
						<td nowrap><b>Nama Syarikat</b></td>
						<td nowrap align="left"><b>Catatan</b></td>
						<td nowrap align="right"><b>Jum Invois (RM)</b></td>
						<td nowrap align="right"><b>Bayaran (RM)</b></td>
						<td nowrap align="right"><b>Baki Invois (RM)</b></td>
						<td nowrap align="center"><b>Status</b></td>
						<td nowrap align="center"><b>Action</b></td>
					</tr>';
	$DRTotal = 0;
	$CRTotal = 0;
	while (!$GetBaucers->EOF && $cnt <= $pg) {
		$jumlah = 0;

		$namacomp 		= dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(companyID), "Text"));
		$nama 			= dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(batchNo), "Text"));
		$description	= $GetBaucers->fields(description);
		$tarikh_inv 	= toDate("d/m/y", $GetBaucers->fields(tarikh_inv));
		$tarikh_akhir 	= strtotime($GetBaucers->fields(tarikh_akhir));
		$today 			= time();
		$amaun 			= $GetBaucers->fields(outstandingbalance);
		$sqlPayment 	= 	"SELECT SUM(outstandingbalance - balance) AS totalPayment 
								FROM cb_payments 
								WHERE invNo = '" . $GetBaucers->fields(invNo) . "'";
		$rsBayaran 		= $conn->Execute($sqlPayment);
		$bayaran 		= $rsBayaran->fields['totalPayment'];
		$balance 		= $amaun - $bayaran;

		if ($tarikh_akhir == null) {
			$statusInv = "-";
		} elseif ($balance == 0) {
			$statusInv = '<span class="badge badge-soft-primary"><b>Paid</b></span>';
		} elseif ($balance <> 0 && $today > $tarikh_akhir) {
			$statusInv = '<span class="badge badge-soft-danger"><b>Late</b></span>';
		} elseif ($balance <> 0 && $today < $tarikh_akhir) {
			$statusInv = '<span class="badge badge-soft-warning"><b>Unpaid</b></span>';
		}

		$cetak 			= '<i class="mdi mdi-printer text-primary" title="cetak" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCinvoicedebtorPrint.php?id=' . $GetBaucers->fields(invNo) . '\')"></i>';
		$edit 			= '<a href="' . $sFileRef . '&action=view&invNo=' . tohtml($GetBaucers->fields['invNo']) . '&yy=' . $yy . '&mm=' . $mm . '" title="kemaskini"><i class="mdi mdi-lead-pencil text-warning" style="font-size: 1.4rem;"></i></a>';
		$editLock 		= '<span style="cursor: not-allowed; color: gray; opacity: 0.5;"><i class="mdi mdi-lead-pencil" style="font-size: 1.4rem; opacity: 0.5;"></i></span>';
		$bayar 			= '<i class="bx bxs-dollar-circle text-info" title="bayar" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'?vw=ACCDebtorPayment&action=new&invNo=' . $GetBaucers->fields(invNo) . '\')"></i>';
		$view 			= '<i class="mdi mdi-file-document text-muted" title="lihat" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCinvoicedebtorView.php?id=' . $GetBaucers->fields(invNo) . '\')"></i>';

		$sSQL2 = "SELECT g_lockstat FROM generalacc WHERE ID = " . $GetBaucers->fields(batchNo) . " ORDER BY ID";
		$rsDetail = &$conn->Execute($sSQL2);

		print ' <tr><td class="Data" style="text-align: center; vertical-align: middle;">' . $bil . '</td>';

		if (($rsDetail->fields['g_lockstat'] == 1) && ($GetBaucers->fields('batchNo') <> "")) {
			print '
		<td class="Data" style="text-align: left; vertical-align: middle;" nowrap><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetBaucers->fields(invNo)) . '">
		' . $GetBaucers->fields(invNo) . '</td>';
		} else {
			print '
		<td class="Data" style="text-align: left; vertical-align: middle;" nowrap><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetBaucers->fields(invNo)) . '">
		<a href="' . $sFileRef . '&action=view&invNo=' . tohtml($GetBaucers->fields(invNo)) . '&yy=' . $yy . '&mm=' . $mm . '">
		' . $GetBaucers->fields(invNo) . '</td>';
		}
		print '
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $nama . '</td>
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $tarikh_inv . '</td>
		<td class="Data" style="text-align: left; vertical-align: middle;">' . $namacomp . '</td>
		<td class="Data" style="text-align: left; vertical-align: middle;">' . $description . '</td>
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($amaun, 2) . '</td>
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($bayaran, 2) . '</td>
		<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($balance, 2) . '</td>
		<td class="Data" style="text-align: center; vertical-align: middle;">' . $statusInv . '</td>
		';
		if (($rsDetail->fields['g_lockstat'] == 1) && ($GetBaucers->fields('batchNo') <> "")) {
			print '
		<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $editLock . '&nbsp;&nbsp;' . $view . '</td>
		';
		} else {
			print '
		<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $edit . '&nbsp;&nbsp;' . $view . '</td>
		';
		}
		print '
		</tr>
		';
		$cnt++;
		$bil++;
		$GetBaucers->MoveNext();
	}
	$GetBaucers->Close();

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
			<td class="textFont">Jumlah Baucer : <b>' . $GetBaucers->RowCount() . '</b></td>
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

	function ITRActionButtonPay() {
		e =	document.MyForm;
		if(e==null)	{
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked)	{
					count++;
					pk = e.elements[c].value;
				}
			}

			if(count !=	1) {
				alert(\'Sila pilih satu	rekod untuk proses bayaran!\');
			} else {
				window.open(\'?vw=ACCDebtorPayment&mn=915&action=new&invNo=\' + pk,"sort","top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");
			}
		}
	}
		
	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '&yy=' . $yy . '&mm=' . $mm . '&code=' . $code . '&filter=' . $filter . '&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

	function toggleSearchFields(selectedValue) {
		var debtDropdown = document.getElementById("debtDropdown");
		var searchInput = document.getElementById("searchInput");
		if (selectedValue == 3) {
			debtDropdown.style.display = "inline-block";
			searchInput.style.display = "none";
		} else {
			debtDropdown.style.display = "none";
			searchInput.style.display = "inline-block";
		}
	}
		
</script>';