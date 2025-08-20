<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	biayaMember.php
 *          Date 		: 	12/12/2018
 *********************************************************************************/
if (!isset($StartRec))	$StartRec = 1;
if (!isset($pg))		$pg = 50;
if (!isset($q))			$q = "";
if (!isset($by))		$by = "0";
if (!isset($filter))	$filter = "0";
if (!isset($dept))		$dept = "";
date_default_timezone_set("Asia/Jakarta");

include("header.php");
include("koperasiQry.php");

$koperasiID = dlookup("setup", "koperasiID", "setupID=" . tosql(1, "Text"));

if (get_session("Cookie_groupID") <> 0 or get_session("Cookie_koperasiID") <> $koperasiID) {
	print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
	exit;
}

$sFileName = '?vw=biayaMember&mn=5';
$sFileRef  = '?vw=biayaMohonJaminan&mn=5';
$title     = "Permohonan Penjamin Pembiayaan";

//--- Prepare department list
$deptList = array();
$deptVal  = array();
$sSQL = "SELECT a.departmentID, b.code as deptCode, b.name as deptName 
         FROM userdetails a, general b
         WHERE a.departmentID = b.ID
         AND a.status = 1 
         GROUP BY a.departmentID";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() > 0) {
	while (!$rs->EOF) {
		array_push($deptList, $rs->fields['deptName']);
		array_push($deptVal, $rs->fields['departmentID']);
		$rs->MoveNext();
	}
}

$loList = array();
$sSQL = "SELECT * FROM general WHERE category = 'C' AND c_gurrantor = 1";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() > 0) {
	while (!$rs->EOF) {
		array_push($loList, $rs->fields['ID']);
		$rs->MoveNext();
	}
	$loList = implode(",", $loList);
}

$pk = get_session('Cookie_userID');
$sqlGet = "SELECT DISTINCT A.* FROM loans A, userdetails B 
           WHERE A.userID = B.userID 
           AND B.userID = '" . $pk . "' 
           AND A.loanType IN (" . $loList . ") 
           AND A.status NOT IN (9) 
           ORDER BY A.applyDate DESC";
$GetLoan = &$conn->Execute($sqlGet);

$TotalRec = $GetLoan->RowCount();
if ($TotalRec > 0) {
	$GetLoan->Move($StartRec - 1);
}

print '
<form name="MyForm" action="' . $sFileName . '" method="post">
<input type="hidden" name="action">
<div class="table-responsive">
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
	<tr>
		<td><h5 class="card-title"><i class="typcn typcn-tick-outline"></i>&nbsp;' . strtoupper($title) . '</h5></td>		
	</tr>';
if ($GetLoan->RowCount() <>	0) {
	print '
	<tr>
		<td align="left">
			<input type="button" value="Kemaskini Penjamin" class="btn btn-primary btn-sm w-md waves-effect waves-light" onClick="ITRActionButtonClick(\'ubah\')">
			<hr class="mb-2">
			<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
				<tr class="table-primary">
					<td nowrap>&nbsp;</td>
					<td nowrap align="left"><b>Nombor Rujukan/Pembiayaan</b></td>
					<td nowrap align="center"><b>Status</b></td>
					<td nowrap align="right"><b>Penjamin 1</b></td>
					<td nowrap align="right"><b>Penjamin 2</b></td>
					<td nowrap align="right"><b>Penjamin 3</b></td>
				</tr>';
	$bil = $StartRec;
	$cnt = 1;
	while (!$GetLoan->EOF && $cnt <= $pg) {
		$jabatan = dlookup("userdetails", "departmentID", "userID=" . tosql($GetLoan->fields['userID'], "Text"));
		$amt =  number_format(tosql($GetLoan->fields['loanAmt'], "Number"), 2);

		$status = $GetLoan->fields['status'];
		$colorStatus = $status == 3 ? "text-primary" : ($status >= 4 ? "text-danger" : "text-success");

		$approve1 = $GetLoan->fields['statuspID1'] ? '<i class="mdi mdi-check text-primary"></i>' : '<i class="mdi mdi-close text-danger"></i>';
		$approve2 = $GetLoan->fields['statuspID2'] ? '<i class="mdi mdi-check text-primary"></i>' : '<i class="mdi mdi-close text-danger"></i>';
		$approve3 = $GetLoan->fields['statuspID3'] ? '<i class="mdi mdi-check text-primary"></i>' : '<i class="mdi mdi-close text-danger"></i>';

		print '
				<tr>
					<td align="center">' . $bil . '</td>
					<td>' . $GetLoan->fields['loanNo'] . ' (' . dlookup("general", "name", "ID=" . $GetLoan->fields['loanType']) . ')</td>
					<td align="center"><font class="' . $colorStatus . '">' . $biayaList[$status] . '</font></td>
					<td align="right">' . $approve1 . '&nbsp;' . dlookup("users", "name", "userID=" . tohtml($GetLoan->fields['penjaminID1'])) . '&nbsp;</td>
					<td align="right">' . $approve2 . '&nbsp;' . dlookup("users", "name", "userID=" . tohtml($GetLoan->fields['penjaminID2'])) . '&nbsp;</td>
					<td align="right">' . $approve3 . '&nbsp;' . dlookup("users", "name", "userID=" . tohtml($GetLoan->fields['penjaminID3'])) . '&nbsp;</td>
				</tr>';
		$cnt++;
		$bil++;
		$GetLoan->MoveNext();
	}
	print '</table>';
} else {
	print '
	<tr>
		<td align="center"><hr size="1"><b class="textFont">- Tiada Rekod Untuk ' . $title . ' -</b><hr size="1"></td>
	</tr>';
}

print '
</table>
</div>
</form>';

include("footer.php");
