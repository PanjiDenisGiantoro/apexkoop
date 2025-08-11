<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	.arrow {
		display: none;
	}
	.sort-link:hover .arrow {
		display: inline;
	}
	.custom-button {
		border-radius: 5px;
		background-color:  #479ddc ;
		border: none;
		color: white;
		padding: 6px 12px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 14px;
		margin: 4px 2px;
		cursor: pointer;
	}
	.dropdown-menu.custom {
		box-shadow: none;
		border-left-color: transparent;
		border: 0px solid transparent;
		background-color: rgb(253, 253, 253);
		border-radius: 2px; 
		font-size: 14px; 
		border-left-width: 570px;
		border-top-width: 13px;
		max-height: 200px; 
		overflow-y: auto; 
	}
	.dropdown-item {
		border: 1px solid transparent; 
	}
	.dropdown-item:hover {
		background-color: rgb(255, 244, 244);
		transition: background-color 0.3s ease;
	}
	input[type="checkbox"] {
        position: absolute;
        opacity: 0;
    }
    .checkmark {
        margin-left: 15px;
        font-size: 16px; 
    }
    input[type="checkbox"]:checked + label .checkmark::before {
        content: '\2713'; 
    }
	</style>
</head>
<body>

</body>
</html>
<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	listKoperasi.php
*          Date 		: 	4/12/2023
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 100;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="ALL";
if (!isset($dept))		$dept="";
if (!isset($fasa))		$fasa="";
date_default_timezone_set("Asia/Kuala_Lumpur");	

include("header.php");	
include("koperasiQry.php"); 

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 AND get_session("Cookie_groupID") <> 5 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$sFileName = '?vw=listKoperasi&mn=900';
$sFileRef  = '?vw=memberEdit&mn=905';
$title     = "Senarai Koperasi";

$IDName = get_session("Cookie_userName");
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$CheckUser = ctMemberDetail($pk[$i]);
		if ($CheckUser->RowCount() == 1) {
			if ($CheckUser->fields(status) == 0) {
				$sSQL = '';
			    $sWhere = "userID=" . tosql($pk[$i], "Text");
				$sSQL = "DELETE FROM users WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);
				$sSQL = '';
				$sSQL = "DELETE FROM userdetails WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);
			} else {
				print '<script>alert("Pengguna '.$CheckUser->fields(name).' - tidak boleh dihapuskan...!");</script>';
			}
		}
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------
//--- Prepare list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();
$fasaList = Array();
$fasaVal  = Array();

// Query for deptList
$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.jenisCode
         FROM userdetails a
         INNER JOIN general b ON a.departmentID = b.ID
         INNER JOIN users c ON a.userID = c.userID
         GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0){
	while (!$rsDept->EOF) {
		array_push ($deptList, $rsDept->fields(deptName));
		array_push ($deptVal, $rsDept->fields(departmentID));
		$rsDept->MoveNext();
	}
}

// Query for jenisCodeList
$sSQLJenisCode = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.jenisCode
				FROM userdetails a
				INNER JOIN general b ON a.jenisCode = b.ID
				INNER JOIN users c ON a.userID = c.userID
				GROUP BY b.ID";

$rsJenisCode = &$conn->Execute($sSQLJenisCode);
if ($rsJenisCode->RowCount() <> 0) {
    while (!$rsJenisCode->EOF) {
        array_push($jenisCodeList, $rsJenisCode->fields(deptName));
        array_push($jenisCodeVal, $rsJenisCode->fields(jenisCode));
        $rsJenisCode->MoveNext();
    }
}

// Query for statustraining
$sSQLtraining = "SELECT a.departmentID, b.code as deptCode, b.name as codeName, a.training
				FROM userdetails a
				LEFT JOIN general b ON a.training = b.ID
				GROUP BY b.ID";

$rstraining = &$conn->Execute($sSQLtraining);
if ($rstraining->RowCount() <> 0) {
    while (!$rstraining->EOF) {
        array_push($trainingList, $rstraining->fields(codeName));
        array_push($trainingVal, $rstraining->fields(training));
        $rstraining->MoveNext();
    }
}

// Query for fasa list
$sSQLFasa = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.fasa
				FROM userdetails a
				INNER JOIN general b ON a.fasa = b.ID
				INNER JOIN users c ON a.userID = c.userID
				GROUP BY b.ID";

$rsFasa = &$conn->Execute($sSQLFasa);
if ($rsFasa->RowCount() <> 0) {
    while (!$rsFasa->EOF) {
        array_push($fasaList, $rsFasa->fields(deptName));
        array_push($fasaVal, $rsFasa->fields(fasa));
        $rsFasa->MoveNext();
    }
}
	$sSQL = "";
	$sWhere = " a.userID = b.userID " ;
	$sWhere1 = "";
	
	if ($dept <> "") 	{
		$sWhere1 .= " AND b.departmentID = " . tosql($dept,"Number");
	}

	if ($jenisCode <> "") 	{
		$sWhere1 .= " AND b.jenisCode = " . tosql($jenisCode,"Number");
	}

	if ($training <> "") 	{
		$sWhere1 .= " AND b.training = " . tosql($training, "Number");
	}
	
	if ($fasa <> "") 	{
		$sWhere1 .= " AND b.fasa = " . tosql($fasa, "Number");
	}
	
	if($filter <> "ALL") $sWhere1 .= "  AND b.status = " . $filter;
	
	if ($q <> "") 	{
		if ($by == 1) {
			$sWhere1 .= " AND b.kopNum like '%" .$q ."%'";			
		} else if ($by == 2) {
			$sWhere1 .= " AND a.name like '%" . $q. "%'";
		} else if ($by == 3) {
			$sWhere1 .= " AND a.loginID like '%" . $q. "%'";		
		}
	}
	$sWhere .= $sWhere1;
	$sWhere = " WHERE (" . $sWhere. ")";
	
	$orderBy = '';
	$sortOrderNoKop = '';
	$sortOrderSingkatan = '';
	$sortOrderLatihan = '';
	$sortOrderFasa = '';
	$sortOrderJenis = '';
	$sortOrderZon = '';
	$sortOrderKod = '';
	$sortOrderYuran = '';
	$sortOrderSyer = '';
	$sortOrderStatus = '';
	$sortOrderSST = '';
	
	if (isset($_GET['sort'])) {
		switch ($_GET['sort']) {
			//No./Nama
			case 'asc':
				$sortOrderNoKop = 'asc';
				break;
			case 'desc':
				$sortOrderNoKop = 'desc';
				break;
			//singkatan
			case 'asc_singkatan':
				$sortOrderSingkatan = 'asc_singkatan';
				break;
			case 'desc_singkatan':
				$sortOrderSingkatan = 'desc_singkatan';
				break;
			//latihan
			case 'asc_latihan':
				$sortOrderLatihan = 'asc_latihan';
				break;
			case 'desc_latihan':
				$sortOrderLatihan = 'desc_latihan';
				break;
			//fasa
			case 'asc_fasa':
				$sortOrderFasa = 'asc_fasa';
				break;
			case 'desc_fasa':
				$sortOrderFasa = 'desc_fasa';
				break;
			//jenis
			case 'asc_jenis':
				$sortOrderJenis = 'asc_jenis';
				break;
			case 'desc_jenis':
				$sortOrderJenis = 'desc_jenis';
				break;
			//Zon
			case 'asc_zon':
				$sortOrderZon = 'asc_zon';
				break;
			case 'desc_zon':
				$sortOrderZon = 'desc_zon';
				break;
			//Kod
			case 'asc_kod':
				$sortOrderKod = 'asc_kod';
				break;
			case 'desc_kod':
				$sortOrderKod = 'desc_kod';
				break;
			//Yuran
			case 'asc_yuran':
				$sortOrderYuran = 'asc_yuran';
				break;
			case 'desc_yuran':
				$sortOrderYuran = 'desc_yuran';
				break;
				//Syer
			case 'asc_syer':
				$sortOrderSyer = 'asc_syer';
				break;
			case 'desc_syer':
				$sortOrderSyer = 'desc_syer';
				break;
			//Status
			case 'asc_stats':
				$sortOrderStatus = 'asc_stats';
				break;
			case 'desc_stats':
				$sortOrderStatus = 'desc_stats';
				break;
			//SST
			case 'asc_sst':
				$sortOrderSST = 'asc_sst';
				break;
			case 'desc_sst':
				$sortOrderSST = 'desc_sst';
				break;
			default:
				break;
		}
	}
	$sSQL = "SELECT DISTINCT a.*, b.*
	FROM users a, userdetails b " . $sWhere;

	// Construct the links for sorting
	$ascNoKopLink = $sFileName . '&sort=asc';
	$ascNoKopLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descNoKopLink = $sFileName . '&sort=desc';
	$descNoKopLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by. '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascSingkatanLink = $sFileName . '&sort=asc_singkatan';
	$ascSingkatanLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descSingkatanLink = $sFileName . '&sort=desc_singkatan';
	$descSingkatanLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascLatihanLink = $sFileName . '&sort=asc_latihan';
	$ascLatihanLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descLatihanLink = $sFileName . '&sort=desc_latihan';
	$descLatihanLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascFasaLink = $sFileName . '&sort=asc_fasa';
	$ascFasaLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descFasaLink = $sFileName . '&sort=desc_fasa';
	$descFasaLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascJenisLink = $sFileName . '&sort=asc_jenis';
	$ascJenisLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descJenisLink = $sFileName . '&sort=desc_jenis';
	$descJenisLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascZonLink = $sFileName . '&sort=asc_zon';
	$ascZonLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descZonLink = $sFileName . '&sort=desc_zon';
	$descZonLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascKodLink = $sFileName . '&sort=asc_kod';
	$ascKodLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descKodLink = $sFileName . '&sort=desc_kod';
	$descKodLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascYuranLink = $sFileName . '&sort=asc_yuran';
	$ascYuranLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descYuranLink = $sFileName . '&sort=desc_yuran';
	$descYuranLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascSyerLink = $sFileName . '&sort=asc_syer';
	$ascSyerLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descSyerLink = $sFileName . '&sort=desc_syer';
	$descSyerLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	
	$ascStatsLink = $sFileName . '&sort=asc_stats';
	$ascStatsLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descStatsLink = $sFileName . '&sort=desc_stats';
	$descStatsLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	$ascSSTLink = $sFileName . '&sort=asc_sst';
	$ascSSTLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;
	$descSSTLink = $sFileName . '&sort=desc_sst';
	$descSSTLink .= '&dept=' . $dept . '&jenisCode=' . $jenisCode . '&training=' . $training . '&fasa=' . $fasa . '&filter=' . $filter . '&q=' . $q . '&by=' . $by . '&StartRec=' . $StartRec . '&pg=' . $pg;

	// Generate the links based on the current sort order for each column
	$sortLinkNoKop = ($sortOrderNoKop === 'asc' || $sortOrderNoKop === 'desc') ? ($sortOrderNoKop === 'asc' ? $descNoKopLink : $ascNoKopLink) : $descNoKopLink;
	
	$sortLinkSingkatan = ($sortOrderSingkatan === 'asc_singkatan' || $sortOrderSingkatan === 'desc_singkatan') ? ($sortOrderSingkatan === 'asc_singkatan' ? $descSingkatanLink : $ascSingkatanLink) : $descSingkatanLink;
	
	$sortLinkLatihan = ($sortOrderLatihan === 'asc_latihan' || $sortOrderLatihan === 'desc_latihan') ? ($sortOrderLatihan === 'asc_latihan' ? $descLatihanLink : $ascLatihanLink) : $descLatihanLink;

	$sortLinkFasa = ($sortOrderFasa === 'asc_fasa' || $sortOrderFasa === 'desc_fasa') ? ($sortOrderFasa === 'asc_fasa' ? $descFasaLink : $ascFasaLink) : $descFasaLink;
	
	$sortLinkJenis = ($sortOrderJenis === 'asc_jenis' || $sortOrderJenis === 'desc_jenis') ? ($sortOrderJenis === 'asc_jenis' ? $descJenisLink : $ascJenisLink) : $descJenisLink;

	$sortLinkZon = ($sortOrderZon === 'asc_zon' || $sortOrderZon === 'desc_zon') ? ($sortOrderZon === 'asc_zon' ? $descZonLink : $ascZonLink) : $descZonLink;

	$sortLinkKod = ($sortOrderKod === 'asc_kod' || $sortOrderKod === 'desc_kod') ? ($sortOrderKod === 'asc_kod' ? $descKodLink : $ascKodLink) : $descKodLink;

	$sortLinkYuran = ($sortOrderYuran === 'asc_yuran' || $sortOrderYuran === 'desc_yuran') ? ($sortOrderYuran === 'asc_yuran' ? $descYuranLink : $ascYuranLink) : $descYuranLink;

	$sortLinkSyer = ($sortOrderSyer === 'asc_syer' || $sortOrderSyer === 'desc_syer') ? ($sortOrderSyer === 'asc_syer' ? $descSyerLink : $ascSyerLink) : $descSyerLink;

	$sortLinkStats = ($sortOrderStatus === 'asc_stats' || $sortOrderStatus === 'desc_stats') ? ($sortOrderStatus === 'asc_stats' ? $descStatsLink : $ascStatsLink) : $descStatsLink;

	$sortLinkSST = ($sortOrderSST === 'asc_sst' || $sortOrderSST === 'desc_sst') ? ($sortOrderSST === 'asc_sst' ? $descSSTLink : $ascSSTLink) : $descSSTLink;

	if ($sortOrderNoKop === 'asc') {
		$orderBy .= ($orderBy !== '') ? ', b.kopNum ASC' : 'b.kopNum ASC';
	} elseif ($sortOrderNoKop === 'desc') {
		$orderBy .= ($orderBy !== '') ? ', b.kopNum DESC' : 'b.kopNum DESC';
	}

	if ($sortOrderSingkatan === 'asc_singkatan') {
		$orderBy .= ($orderBy !== '') ? ', a.loginID ASC' : 'a.loginID ASC';
	} elseif ($sortOrderSingkatan === 'desc_singkatan') {
		$orderBy .= ($orderBy !== '') ? ', a.loginID DESC' : 'a.loginID DESC';
	}

	if ($sortOrderLatihan === 'asc_latihan') {
		$orderBy .= ($orderBy !== '') ? ', b.training ASC' : 'b.training ASC';
	} elseif ($sortOrderLatihan === 'desc_latihan') {
		$orderBy .= ($orderBy !== '') ? ', b.training DESC' : 'b.training DESC';
	}

	if ($sortOrderFasa === 'asc_fasa') {
		$orderBy .= ($orderBy !== '') ? ', b.fasa ASC' : 'b.fasa ASC';
	} elseif ($sortOrderFasa === 'desc_fasa') {
		$orderBy .= ($orderBy !== '') ? ', b.fasa DESC' : 'b.fasa DESC';
	}

	if ($sortOrderJenis === 'asc_jenis') {
		$orderBy .= ($orderBy !== '') ? ', b.jenis ASC' : 'b.jenis ASC';
	} elseif ($sortOrderJenis === 'desc_jenis') {
		$orderBy .= ($orderBy !== '') ? ', b.jenis DESC' : 'b.jenis DESC';
	}

	if ($sortOrderZon === 'asc_zon') {
		$orderBy .= ($orderBy !== '') ? ', b.departmentID ASC' : 'b.departmentID ASC';
	} elseif ($sortOrderZon === 'desc_zon') {
		$orderBy .= ($orderBy !== '') ? ', b.departmentID DESC' : 'b.departmentID DESC';
	}

	if ($sortOrderKod === 'asc_kod') {
		$orderBy .= ($orderBy !== '') ? ', b.jenisCode ASC' : 'b.jenisCode ASC';
	} elseif ($sortOrderKod === 'desc_kod') {
		$orderBy .= ($orderBy !== '') ? ', b.jenisCode DESC' : 'b.jenisCode DESC';
	}

	if ($sortOrderYuran === 'asc_yuran') {
		$orderBy .= ($orderBy !== '') ? ', b.totalFee ASC' : 'b.totalFee ASC';
	} elseif ($sortOrderYuran === 'desc_yuran') {
		$orderBy .= ($orderBy !== '') ? ', b.totalFee DESC' : 'b.totalFee DESC';
	}

	if ($sortOrderSyer === 'asc_syer') {
		$orderBy .= ($orderBy !== '') ? ', b.totalShare ASC' : 'b.totalShare ASC';
	} elseif ($sortOrderSyer === 'desc_syer') {
		$orderBy .= ($orderBy !== '') ? ', b.totalShare DESC' : 'b.totalShare DESC';
	}

	if ($sortOrderStatus === 'asc_stats') {
		$orderBy .= ($orderBy !== '') ? ', b.status ASC' : 'b.status ASC';
	} elseif ($sortOrderStatus === 'desc_stats') {
		$orderBy .= ($orderBy !== '') ? ', b.status DESC' : 'b.status DESC';
	}

	if ($sortOrderSST === 'asc_sst') {
		$orderBy .= ($orderBy !== '') ? ', b.approvedDate ASC' : 'b.approvedDate ASC';
	} elseif ($sortOrderSST === 'desc_sst') {
		$orderBy .= ($orderBy !== '') ? ', b.approvedDate DESC' : 'b.approvedDate DESC';
	}

	if ($orderBy !== '') {
		$sSQL .= ' ORDER BY ' . $orderBy;
	}

	$GetMember = &$conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action='.$sFileName.' method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="'.$pk.'">
<input type="hidden" name="filter" value="'.$filter.'">
<h5 class="card-title">'.strtoupper($title).'</h5>
    
<div class="mb-3 row m-1">
<div>Carian Melalui 
			<select name="by" class="form-select-sm mt-3">'; 
if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Koperasi </option>'; 	else print '<option value="2">Nama Koperasi</option>';
if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';					
print '		</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm mt-3">
 			<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm mt-3" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}
print '	
</select>&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button class="btn btn-success custom-button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	Columns
</button>
<div class="dropdown-menu custom" style="border-left-width: 570px; border-top-width: 13px;"aria-labelledby="dropdownMenuButton">

	<label for="col1" class="dropdown-item">	
		<input type="checkbox" id="col1" class="form-check-input" value="col1">
		<label for="col1">
			No./Nama Koperasi<span class="checkmark"></span>
		</label>
	</label>

	<label for="col2" class="dropdown-item">	
		<input type="checkbox" id="col2" class="form-check-input" value="col2">
		<label for="col2">
			Singkatan Koperasi<span class="checkmark"></span>
		</label>
	</label>

	<label for="col3" class="dropdown-item">	
		<input type="checkbox" id="col3" class="form-check-input" value="col3">
		<label for="col3">
			Latihan<span class="checkmark"></span>
		</label>
	</label>

	<label for="col4" class="dropdown-item">	
		<input type="checkbox" id="col4" class="form-check-input" value="col4">
		<label for="col4">
			Fasa<span class="checkmark"></span>
		</label>
	</label>

	<label for="col5" class="dropdown-item">	
		<input type="checkbox" id="col5" class="form-check-input" value="col5">
		<label for="col5">
			Jenis<span class="checkmark"></span>
		</label>
	</label>

	<label for="col6" class="dropdown-item">	
		<input type="checkbox" id="col6" class="form-check-input" value="col6">
		<label for="col6">
			Zon<span class="checkmark"></span>
		</label>
	</label>

	<label for="col7" class="dropdown-item">	
		<input type="checkbox" id="col7" class="form-check-input" value="col7">
		<label for="col7">
			Kod<span class="checkmark"></span>
		</label>
	</label>

	<label for="col8" class="dropdown-item">	
		<input type="checkbox" id="col8" class="form-check-input" value="col8">
		<label for="col8">
			Yuran<span class="checkmark"></span>
		</label>
	</label>

	<label for="col9" class="dropdown-item">	
		<input type="checkbox" id="col9" class="form-check-input" value="col9">
		<label for="col9">
			Syer<span class="checkmark"></span>
		</label>
	</label>

	<label for="col10" class="dropdown-item">	
		<input type="checkbox" id="col10" class="form-check-input" value="col10">
		<label for="col10">
			Status<span class="checkmark"></span>
		</label>
	</label>

	<label for="col11" class="dropdown-item">	
		<input type="checkbox" id="col11" class="form-check-input" value="col11">
		<label for="col11">
			Tarikh SST Diterima<span class="checkmark"></span>
		</label>
	</label>
</div>

</td>
</div></div>

<div class="mb-3 row m-1">
<div>
Status
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			print '<option value="ALL">- Semua -';
			for ($i = 0; $i < count($statusList); $i++) {
				if($i == 0 ||$i == 1||$i == 2||$i == 3){
				if ($statusVal[$i] < 4) {
					print '	<option value="'.$statusVal[$i].'" ';
					if ($filter == $statusVal[$i]) print ' selected';
					print '>'.$statusList[$i];
				}
			}
			}
	print 
	'</select>&nbsp
	Kod
	<select name="jenisCode" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($jenisCodeList); $i++) {
					print '	<option value="'.$jenisCodeVal[$i].'" ';
					if ($jenisCode == $jenisCodeVal[$i]) print ' selected';
					print '>'.$jenisCodeList[$i];
				}
				print
			'</select>&nbsp
			Latihan
			<select name="training" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($trainingList); $i++) {
				if($i == 1 || $i==0){
					print '	<option value="'.$trainingVal[$i].'" ';
				if ($training == $trainingVal[$i]) print ' selected';
					print '>'.$trainingList[$i];
				}
			}
			print
			'</select>&nbsp
			Fasa
			<select name="fasa" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($fasaList); $i++) {
				if($i==0|| $i==1|| $i==2|| $i==3){
					print '	<option value="'.$fasaVal[$i].'" ';
					if ($fasa == $fasaVal[$i]) print ' selected';
					print '>'.$fasaList[$i];
				}
			}

			print
			'</select>&nbsp;';

if (($IDName == 'superadmin') OR ($IDName == 'admin') OR get_session("Cookie_groupID") == '2') {

if($filter == 0) print    '&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');"> &nbsp; '; }
if(get_session("Cookie_groupID") == '2'){
	print'<input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');"></div>';
}
	
print '
<div class="table-responsive">    
<!--table border="1" cellspacing="1" cellpadding="3" width="100%" align="center" class="table"-->
	<tr valign="top" class="textFont">
		<td>
			<table width="100%">
				<tr>
					<!-- <td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td> -->
					<td align="right" class="textFont">
					Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
					if ($pg == 5)	print '<option value="5" selected>5</option>'; 	 	else print '<option value="5">5</option>';				
					if ($pg == 10)	print '<option value="10" selected>10</option>'; 	else print '<option value="10">10</option>';				
					if ($pg == 20)	print '<option value="20" selected>20</option>'; 	else print '<option value="20">20</option>';				
					if ($pg == 30)	print '<option value="30" selected>30</option>'; 	else print '<option value="30">30</option>';				
					if ($pg == 40)	print '<option value="40" selected>40</option>'; 	else print '<option value="40">40</option>';				
					if ($pg == 50)	print '<option value="50" selected>50</option>';	else print '<option value="50">50</option>';				
					if ($pg == 100)	print '<option value="100" selected>100</option>';	else print '<option value="100">100</option>';				
	print '				</select> setiap mukasurat.
					</td>
				</tr>
			</table>
		</td>
	</tr>';	
	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
	    <tr valign="top" ><br>
			<td valign="top">
				<table id="dataTable" border="1" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap class="Data col1">';
							if ($sortOrderNoKop === 'asc') {
								echo '<strong><a name="asc" class="text-danger " href="'.$sortLinkNoKop.'">No./Nama Koperasi ⬇</a></strong>';
							} else if ($sortOrderNoKop === 'desc') {
								echo '<strong><a name="desc" class="text-danger " href="'.$sortLinkNoKop.'">No./Nama Koperasi ⬆</a></strong>';
							}
							else{
								echo '<strong><a name="desc" class = "sort-link " style="color: black;" href="'.$sortLinkNoKop.'">No./Nama Koperasi <span class="arrow">⇅</span></a></strong>';	
							}
						
						//singkatan
						print'</td><td nowrap align="center" class="Data col2">';
						if ($sortOrderSingkatan === 'asc_singkatan') {
							print '<strong><a name="asc_singkatan" class="text-danger" href="'.$sortLinkSingkatan.'">Singkatan Koperasi ⬇</a></strong>';
						} else if ($sortOrderSingkatan === 'desc_singkatan'){
							print '<strong><a name="desc_singkatan" class="text-danger" href="'.$sortLinkSingkatan.'">Singkatan Koperasi ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_singkatan" class = "sort-link" style="color: black;" href="'.$sortLinkSingkatan.'">Singkatan Koperasi <span class="arrow">⇅</span></a></strong>';	
						}

						//latihan
						print'</td><td nowrap align="center" class="Data col3">';
						if ($sortOrderLatihan === 'asc_latihan') {
							echo '<strong><a name="asc_latihan" class="text-danger" href="'.$sortLinkLatihan.'">Latihan ⬇</a></strong>';
						} else if ($sortOrderLatihan === 'desc_latihan'){
							echo '<strong><a name="desc_latihan" class="text-danger" href="'.$sortLinkLatihan.'">Latihan ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_latihan" class = "sort-link" style="color: black;" href="'.$sortLinkLatihan.'">Latihan <span class="arrow">⇅</span></a></strong>';	
						}

						//fasa
						print'</td><td nowrap align="center" class="Data col4">';
						if ($sortOrderFasa === 'asc_fasa') {
							echo '<strong><a name="asc_fasa" class="text-danger" href="'.$sortLinkFasa.'">Fasa ⬇</a></strong>';
						} else if ($sortOrderFasa === 'desc_fasa'){
							echo '<strong><a name="desc_fasa" class="text-danger" href="'.$sortLinkFasa.'">Fasa ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_fasa" class = "sort-link" style="color: black;" href="'.$sortLinkFasa.'">Fasa <span class="arrow">⇅</span></a></strong>';	
						}

						//jenis
						print'</td><td nowrap align="center" class="Data col5">';
						if ($sortOrderJenis === 'asc_jenis') {
							echo '<strong><a name="asc_jenis" class="text-danger" href="'.$sortLinkJenis.'">Jenis ⬇</a></strong>';
						} else if ($sortOrderJenis === 'desc_jenis'){
							echo '<strong><a name="desc_jenis" class="text-danger" href="'.$sortLinkJenis.'">Jenis ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_jenis" class = "sort-link" style="color: black;" href="'.$sortLinkJenis.'">Jenis <span class="arrow">⇅</span></a></strong>';
						}

						//zon
						print'</td><td nowrap align="center" class="Data col6">';
						if ($sortOrderZon === 'asc_zon') {
							echo '<strong><a name="asc_zon" class="text-danger" href="'.$sortLinkZon.'">Zon ⬇</a></strong>';
						} else if ($sortOrderZon === 'desc_zon'){
							echo '<strong><a name="desc_zon" class="text-danger" href="'.$sortLinkZon.'">Zon ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_zon" class = "sort-link" style="color: black;" href="'.$sortLinkZon.'">Zon <span class="arrow">⇅</span></a></strong>';
						}

						//kod
						print'</td><td nowrap align="center" class="Data col7">';
						if ($sortOrderKod === 'asc_kod') {
							echo '<strong><a name="asc_kod" class="text-danger" href="'.$sortLinkKod.'">Kod ⬇</a></strong>';
						} else if ($sortOrderKod === 'desc_kod'){
							echo '<strong><a name="desc_kod" class="text-danger" href="'.$sortLinkKod.'">Kod ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_kod" class = "sort-link" style="color: black;" href="'.$sortLinkKod.'">Kod <span class="arrow">⇅</span></a></strong>';
						}

						//yuran
						print'</td><td nowrap align="center" class="Data col8">';
						if ($sortOrderYuran === 'asc_yuran') {
							echo '<strong><a name="asc_yuran" class="text-danger" href="'.$sortLinkYuran.'">Yuran (RM) ⬇</a></strong>';
						} else if ($sortOrderYuran === 'desc_yuran'){
							echo '<strong><a name="desc_yuran" class="text-danger" href="'.$sortLinkYuran.'">Yuran (RM) ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_yuran" class = "sort-link" style="color: black;" href="'.$sortLinkYuran.'">Yuran (RM) <span class="arrow">⇅</span></a></strong>';
						}

						//syer
						print'</td><td nowrap align="center" class="Data col9">';
						if ($sortOrderSyer === 'asc_syer') {
							echo '<strong><a name="asc_syer" class="text-danger" href="'.$sortLinkSyer.'">Syer (RM) ⬇</a></strong>';
						} else if ($sortOrderSyer === 'desc_syer'){
							echo '<strong><a name="desc_syer" class="text-danger" href="'.$sortLinkSyer.'">Syer (RM) ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_syer" class = "sort-link" style="color: black;" href="'.$sortLinkSyer.'">Syer (RM) <span class="arrow">⇅</span></a></strong>';
						}

						//Status
						print'</td><td nowrap align="center" class="Data col10">';
						if ($sortOrderStatus === 'asc_stats') {
							echo '<strong><a name="asc_stats" class="text-danger" href="'.$sortLinkStats.'">Status ⬇</a></strong>';
						} else if ($sortOrderStatus === 'desc_stats'){
							echo '<strong><a name="desc_stats" class="text-danger" href="'.$sortLinkStats.'">Status ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_stats" class = "sort-link" style="color: black;" href="'.$sortLinkStats.'">Status <span class="arrow">⇅</span></a></strong>';
						}

						//SST
						print'</td><td nowrap align="center" class="Data col11">';
						if ($sortOrderSST === 'asc_sst') {
							echo '<strong><a name="asc_sst" class="text-danger" href="'.$sortLinkSST.'">Tarikh SST Diterima ⬇</a></strong>';
						} else if ($sortOrderSST === 'desc_sst'){
							echo '<strong><a name="desc_sst" class="text-danger" href="'.$sortLinkSST.'">Tarikh SST Diterima ⬆</a></strong>';
						}
						else{
							echo '<strong><a name="desc_sst" class = "sort-link" style="color: black;" href="'.$sortLinkSST.'">Tarikh SST Diterima <span class="arrow">⇅</span></a></strong>';
						}
						print'</td>
					</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
			$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$fasa  = dlookup("userdetails", "fasa", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$training = dlookup("userdetails", "training", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$yuran = dlookup("userdetails", "totalFee", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$syer = dlookup("userdetails", "totalShare", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$fpx = dlookup("fpx", "fpx_ID", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$approvedDate = $GetMember->fields(approvedDate);

			if ($approvedDate === null || strtotime($approvedDate) === false) {
				$approvedDate = 'Tiada Tarikh';
			} else {
				$approvedDate = date('d/m/Y', strtotime($approvedDate));
			}
			$colorStatus = "Data";
			if ($status == 0) $colorStatus = "text-success";
			if ($status == 1) $colorStatus = "text-primary";
			if ($status == 2) $colorStatus = "text-warning";
			if ($status == 3) $colorStatus = "text-danger";
			$colorTraining = "Data";
			if($training == 0) $colorTraining = "text";
			if($training == 1) $colorTraining = "text-info";

			print ' <tr>
						<td class="Data" align="right">' . $bil . '&nbsp;</td>
						<td class="Data col1">';
						if(get_session("Cookie_groupID") == '2'){
							print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						}

						if (get_session("Cookie_groupID") == 5) {
							print '
							<span class="text-danger">
							' . $GetMember->fields(kopNum) . ' - 
							' . strtoupper($GetMember->fields(name)) . '</span>';
						} else {
							print '
								<a class="text-danger" href="' . $sFileRef . '&pk=' . tohtml($GetMember->fields(userID)) . '">
								' . $GetMember->fields(kopNum) . ' - 
								' . strtoupper($GetMember->fields(name)) . '</a>';
						}

							print'</td>
						<td class="Data col2" align="center">&nbsp;'.$GetMember->fields(loginID).'</td>
						<td class="Data col3" align="center">&nbsp;<font class="'.$colorTraining.'">'.$trainingList[$training].'</font></td>
						<td class="Data col4" align="center">&nbsp;'.($fasa == 0 ? 'TIADA FASA' : dlookup("general", "name", "ID=" . tosql($GetMember->fields('fasa'), "Number"))).'</td>
						<td class="Data col5" align="center">&nbsp;'.$jenisList[$jenis].'</font></td>
						<td class="Data col6" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td>
						<td class="Data col7" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('jenisCode'), "Number")).'</td>
						<td class="Data col8" align="center">&nbsp;'.$yuran.'</td>
						<td class="Data col9" align="center">&nbsp;'.$syer.'</td>
						<td class="Data col10" align="center">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].'</font></td>
						<td class="Data col11" align="center">&nbsp;'.$approvedDate.'</td>
					</tr>';
				$cnt++;
				$bil++;
			$GetMember->MoveNext();
		}

		$GetMember->Close();
		print ' </table>
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
					for ($i=1; $i <= $numPage; $i++) { 
						print '<A class="text-danger" href="'.$sFileName.'&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&dept='.$dept.'&filter='.$filter.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp;';
					}															
																				
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Rekod : <b>' . $GetMember->RowCount() . '</b></td>
		</tr>';
	} else {
		if ($q == "") {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	}
print ' 
</table></td></tr></table></div>
</form>';

include("footer.php");	

print '
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script language="JavaScript">

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
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	            e.action.value = v;
	            e.submit();
	          }
	        }
	      }
	    }
		
	function ITRActionButtonClickStatus(v) {
	      var strStatus="";
		  e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        j=0;
			for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
				pk = e.elements[c].value;
				strStatus = strStatus + ":" + pk;
				count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
	          window.location.href ="?vw=memberStatus&pk=" + strStatus;
			  }
	        }
	      }
	    }

	function ITRActionButtonStatus() {
		e = document.MyForm;
		if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
					count++;
					pk = e.elements[c].value;
				}
			}
	        
			if(count != 1) {
				alert(\'Sila pilih satu rekod sahaja untuk kemaskini status\');
			} else {
				window.location.href = "memberStatus.php?pk=" + pk;
			}
		}
	}

	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '?&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

	/* document.getElementById(\'columnSelector\').addEventListener(\'change\', function() {
		var selectedValue = this.value;
		var columns = document.querySelectorAll(\'.Data\');
	
		columns.forEach(function(column) {
			column.style.display = \'table-cell\';
		});
	
		if (selectedValue !== \'all\') {
			var selectedColumn = document.querySelectorAll(\'.\' + selectedValue);
			selectedColumn.forEach(function(column) {
				column.style.display = \'none\';
			});
		}
	}); */

	/* document.querySelectorAll(\'.columnCheckbox\').forEach(function(checkbox) {
		checkbox.addEventListener(\'change\', function() {
			var checkedColumns = document.querySelectorAll(\'.Data.\' + this.value);
			checkedColumns.forEach(function(column) {
				column.style.display = checkbox.checked ? \'none\' : \'table-cell\';
			});
		});
	}); */
	
	$(document).ready(function() {
		$(\'.dropdown-toggle\').dropdown();
	});

	document.querySelectorAll(\'.dropdown-item input[type="checkbox"]\').forEach(function(checkbox) {
		checkbox.addEventListener(\'change\', function() {
			var columnClass = this.value; 
			var columns = document.querySelectorAll(\'.\' + columnClass);
	
			columns.forEach(function(column) {
				if (checkbox.checked) {
					column.style.display = \'none\';
				} else {
					column.style.display = \'table-cell\'; // Or any display you want
				}
			});
		});
	});

</script>';
?>
