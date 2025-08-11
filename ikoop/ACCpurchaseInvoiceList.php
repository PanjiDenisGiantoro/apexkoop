<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename	: 	ACCpurchaseInvoiceList.php
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
if (!isset($jenis_cari))	$jenis_cari = "";
if (!isset($credit))	$credit = "";

include("header.php");
include("koperasiQry.php");

$koperasiID = dlookup("setup", "koperasiID", "setupID=" . tosql(1, "Text"));

if (get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 4 or get_session("Cookie_koperasiID") <> $koperasiID) {
	print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>'; //dari mana file ni
}

$sFileName 	= "?vw=ACCpurchaseInvoiceList&mn=$mn"; //file name
$sFileRef  	= "?vw=ACCpurchaseInvoice&mn=$mn"; // file ni pergi mane
$sFileRefPO = "?vw=ACCpurchase&mn=$mn"; // file ni pergi mane
$title     	=  "Pembayaran Belian Invois"; //Title 

$IDName = get_session("Cookie_userName");


//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {

		$sWhere = "PINo=" . tosql($pk[$i], "Text");
		$sSQL 	= "DELETE FROM cb_purchaseinv WHERE " . $sWhere;
		$rs 	= &$conn->Execute($sSQL);

		$sWhere = "docNo=" . tosql($pk[$i], "Text"); //new 

		$docNo = dlookup("transactionacc", "docNo", $sWhere);

		$sSQL 	= "DELETE FROM transactionacc WHERE " . $sWhere;

		$rs = &$conn->Execute($sSQL);

		$strActivity = $_POST['Submit'] . 'Purchase Invois Dihapuskan - ' . $docNo;
		activityLog($sSQL, $strActivity, get_session('Cookie_userID'), get_session('Cookie_userName'), 3);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------
$sSQL 	= "";
$sWhereYear = " WHERE c.status NOT IN (6) AND (YEAR(tarikh_PI) = " . $yy . " OR tarikh_PI = '0000-00-00' OR tarikh_PI is NULL)"; //status 6 is debit note

if ($q <> "" || $credit <> "") {
	if ($by == 1) {
		$sWhere .= " AND A.batchNo = B.ID";
		$sWhere .= " AND B.name like '%" . $q . "%'";
	} else if ($by == 2) {
		$sWhere .= " AND A.PINo like '%" . $q . "%'";
	} else if ($by == 3) {
		$sWhere .= " AND A.purcNo like '%" . $q . "%'";
	} else if ($by == 4) {
		$sWhere .= " AND A.companyID = $credit";
	}
}

if ($q <> "" || $credit <> "") $sWhere = " $sWhereYear $sWhere";
else $sWhere = " $sWhereYear";

$sSQL = "SELECT DISTINCT a.*, b.name, b.g_lockstat 
    FROM cb_purchaseinv a 
    LEFT JOIN generalacc b ON a.batchNo = b.ID
	LEFT JOIN transactionacc c ON a.PINo = c.docNo
    ";

if ($mm <> "ALL") $sWhere .= " AND MONTH(A.tarikh_PI) =" . $mm;
$sSQL = $sSQL . $sWhere . ' ORDER BY A.PINo DESC';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$GetBaucers = &$conn->Execute($sSQL);
$GetBaucers->Move($StartRec - 1);

$TotalRec 	= $GetBaucers->RowCount();
$TotalPage 	=  ($TotalRec / $pg);
$jenisList_cari = array('Pembekal', 'Pemiutang');
$jenisVal_cari 	= array(1, 2);

$sqlYears 	= "SELECT DISTINCT YEAR(tarikh_PI) AS year FROM cb_purchaseinv WHERE tarikh_PI IS NOT NULL AND tarikh_PI != '' AND tarikh_PI != 0 ORDER BY year ASC";
$rsYears 	= $conn->Execute($sqlYears);

$creditorList = array();
$creditorVal  = array();
$sSQLCreditor = "SELECT name AS creditorName, ID AS creditorID FROM generalacc WHERE category = 'AB' ORDER BY ID ASC";
$rsCreditor = &$conn->Execute($sSQLCreditor);
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
if ($by == 1)	print '<option value="1" selected>Nama Batch</option>';
else print '<option value="1">Nama Batch</option>';
if ($by == 2)	print '<option value="2" selected>No. Purchase Invoice</option>';
else print '<option value="2">No. Purchase Invoice</option>';
if ($by == 3)	print '<option value="3" selected>No. Purchase Order</option>';
else print '<option value="3">No. Purchase Order</option>';
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

	print '&nbsp; <input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">';
}

print '
</div>';

//--------------------------------------------------Top right display total invoice and payment depends on year and month---------START

// query total invoice based on year selected
$sqljumlahInv = "SELECT SUM(outstandingbalance - balance) AS totalInvoice,
				 GROUP_CONCAT(DISTINCT a.PINo) AS docNoInv 
                 FROM cb_purchaseinv a
				 WHERE
				 a.PINo IN (
					SELECT DISTINCT c.docNo 
					FROM transactionacc c 
					WHERE c.status NOT IN (6)
				 )	
                 AND YEAR(a.tarikh_PI) = $yy";

// concat query if any month is selected
if ($mm !== "ALL") {
	$sqljumlahInv .= " AND MONTH(tarikh_PI) = $mm";
	$stringDesc = "Bulan $mm ";
}

$rsjumlahInv = $conn->Execute($sqljumlahInv);
$totalInvoice = $rsjumlahInv->fields['totalInvoice'];
$docNoInv = $rsjumlahInv->fields['docNoInv'];

// handle case where no PINo is found
if (empty($docNoInv)) {
	$docNoInv = 'NULL'; // avoids SQL error in IN clause
} else {
	$docNoInv = "'" . str_replace(',', "','", $docNoInv) . "'";
}

// query total payment
$sqljumlahPayment = "SELECT SUM(pymtAmt - balance) AS totalPayment 
                     FROM billacc 
                     WHERE PINo IN ($docNoInv)";

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
						<td nowrap><b>No. Pembayaran Pemiutang</b></td>
						<td nowrap align="center"><b>Nama Batch</b></td>
						<td nowrap align="center"><b>Tarikh</b></td>
						<td nowrap><b>Nama Syarikat</b></td>
						<td nowrap><b>No Purchase Order</b></td>
						<td nowrap align="right"><b>Amaun PO (RM)</b></td>
						<td nowrap align="right"><b>Jumlah Invois (RM)</b></td>
						<td nowrap align="right"><b>Bayaran (RM)</b></td>
						<td nowrap align="right"><b>Baki Invois (RM)</b></td>
						<td nowrap align="center"><b>Action</b></td>
					</tr>';

	$DRTotal = 0;
	$CRTotal = 0;
	while (!$GetBaucers->EOF && $cnt <= $pg) {
		$jumlah = 0;

		$namakp 	= dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(companyID), "Text"));
		$nama 		= dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(batchNo), "Text"));
		$tarikh_PI 	= toDate("d/m/y", $GetBaucers->fields(tarikh_PI));
		$cetak 		= '<i class="mdi mdi-printer text-primary" title="cetak" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCPurchaseInvoicePrint.php?id=' . $GetBaucers->fields(PINo) . '\')"></i>';
		$edit 		= '<a href="' . $sFileRef . '&action=view&PINo=' . tohtml($GetBaucers->fields['PINo']) . '&yy=' . $yy . '&mm=' . $mm . '" title="kemaskini"><i class="mdi mdi-lead-pencil text-warning" style="font-size: 1.4rem;"></i></a>';
		$editLock 	= '<span style="cursor: not-allowed; color: gray; opacity: 0.5;"><i class="mdi mdi-lead-pencil" style="font-size: 1.4rem; opacity: 0.5;"></i></span>';
		$view 		= '<i class="mdi mdi-file-document text-muted" title="lihat" style="font-size: 1.4rem; cursor: pointer;" onClick="open_(\'ACCPurchaseInvoiceView.php?id=' . $GetBaucers->fields(PINo) . '\')"></i>';

		$sSQL2 		= "SELECT g_lockstat FROM generalacc WHERE ID = " . $GetBaucers->fields(batchNo) . " ORDER BY ID";
		$rsDetail 	= &$conn->Execute($sSQL2);

		$sql3 		= "SELECT * FROM transactionacc WHERE addminus IN (1) AND docNo = '" . $GetBaucers->fields(PINo) . "' ORDER BY ID";
		$rsDetail1 	= $conn->Execute($sql3);
		$amaun 		= $rsDetail1->fields(pymtAmt);

		$sqlPayment = "SELECT pymtAmt - balance AS totalPayment
                     FROM billacc WHERE PINo = '" . $GetBaucers->fields(PINo) . "'";
		$rsBayaran 		= $conn->Execute($sqlPayment);
		$bayaran 		= $rsBayaran->fields['totalPayment'];
		$balance 		= $amaun - $bayaran;

		if ($GetBaucers->fields(purcNo))
			$purcNo = $GetBaucers->fields(purcNo);
		else
			$purcNo = "-";

		print ' <tr>
			<td class="Data" style="text-align: center; vertical-align: middle;">' . $bil . '</td>';

		if ($rsDetail->fields(g_lockstat) == 1) {
			print '
	<td class="Data" style="text-align: left; vertical-align: middle;"><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetBaucers->fields(PINo)) . '">
	' . $GetBaucers->fields(PINo) . '</td>';
		} else {
			print '
	<td class="Data" style="text-align: left; vertical-align: middle;"><input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetBaucers->fields(PINo)) . '">
	<a href="' . $sFileRef . '&action=view&PINo=' . tohtml($GetBaucers->fields(PINo)) . '&yy=' . $yy . '&mm=' . $mm . '">
	' . $GetBaucers->fields(PINo) . '</td>';
		}

		print '
	<td class="Data" style="text-align: center; vertical-align: middle;">' . $nama . '</td>
	<td class="Data" style="text-align: center; vertical-align: middle;">' . $tarikh_PI . '</td>
	<td class="Data" style="text-align: left; vertical-align: middle;">' . $namakp . '</td>
	<td class="Data" style="text-align: left; vertical-align: middle;"><a href="' . $sFileRefPO . '&action=view&purcNo=' . $purcNo . '&yy=' . $yy . '&mm=' . $mm . '">' . $purcNo . '</td>';
		if ($GetBaucers->fields(purcNo) <> "") {
			print '<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format(dlookup("cb_purchase", "pymtAmt", "purcNo=" . tosql($GetBaucers->fields(purcNo), "Text")), 2) . '</td>';
		} else {
			print '<td class="Data" style="text-align: right; vertical-align: middle;">-</td>';
		}
		print '
	<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($amaun, 2) . '</td>
	<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($bayaran, 2) . '</td>
	<td class="Data" style="text-align: right; vertical-align: middle;">' . number_format($balance, 2) . '</td>
	';
		if (($rsDetail->fields('g_lockstat') == 1) && ($GetBaucers->fields('batchNo') <> "")) {
			print '
	<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $editLock . '&nbsp;&nbsp;' . $view . '</td>
	';
		} else {
			print '
	<td class="Data" style="text-align: center; vertical-align: middle;" nowrap>' . $cetak . '&nbsp;&nbsp;' . $edit . '&nbsp;&nbsp;' . $view . '</td>
	';
		}
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