<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	memberEdit.php
 *          Date 		: 	10/10/2003
 *********************************************************************************/
include("header.php");
include("koperasiQry.php");
include("forms.php");

date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session("Cookie_groupID") <> 1 and get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 3 and get_session("Cookie_groupID") <> 4 and get_session("Cookie_groupID") <> 5 or get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("' . $errPage . '");window.location="index.php";</script>';
}

$sFileName		= "?vw=memberEdit&mn=905";
$sActionFileName = "?vw=memberEdit&pk=" . $pk . "&mn=905";
$title     		= "Kemaskini Maklumat Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = array();



//--- Prepare state type
$stateList = array();
$stateVal  = array();
$GetState = ctGeneral("", "H");
if ($GetState->RowCount() <> 0) {
	while (!$GetState->EOF) {
		array_push($stateList, $GetState->fields(name));
		array_push($stateVal, $GetState->fields(ID));
		$GetState->MoveNext();
	}
}

$deptList = array();
$deptVal  = array();
$GetDept = ctGeneral("", "B");
if ($GetDept->RowCount() <> 0) {
	while (!$GetDept->EOF) {
		array_push($deptList, $GetDept->fields(name));
		//		array_push ($deptList, $GetDept->fields(code));
		array_push($deptVal, $GetDept->fields(ID));
		$GetDept->MoveNext();
	}
}

//--- Prepare jenis code type
$jenisCodeList = array();
$jenisCodeVal  = array();
$GetJenisCode = ctGeneral("", "E");
if ($GetJenisCode->RowCount() <> 0) {
	while (!$GetJenisCode->EOF) {
		array_push($jenisCodeList, $GetJenisCode->fields(name));
		array_push($jenisCodeVal, $GetJenisCode->fields(ID));
		$GetJenisCode->MoveNext();
	}
}

$bankList = array();
$bankVal  = array();
$Getbank = ctGeneral("", "Z");
if ($Getbank->RowCount() <> 0) {
	while (!$Getbank->EOF) {
		array_push($bankList, $Getbank->fields(name));
		array_push($bankVal, $Getbank->fields(ID));
		$Getbank->MoveNext();
	}
}


//--- Prepare society
$societyList = array();
$societyVal  = array();
$GetSociety = ctGeneral("", "L");
if ($GetSociety->RowCount() <> 0) {
	while (!$GetSociety->EOF) {
		array_push($societyList, $GetSociety->fields(name));
		array_push($societyVal, $GetSociety->fields(ID));
		$GetSociety->MoveNext();
	}
}

//--- Prepare pakej type
$pakejList = array();
$pakejVal  = array();
$GetPakej = ctGeneral("", "G");
if ($GetPakej->RowCount() <> 0) {
	while (!$GetPakej->EOF) {
		array_push($pakejList, $GetPakej->fields(name));
		array_push($pakejVal, $GetPakej->fields(ID));
		$GetPakej->MoveNext();
	}
}

//--- Prepare kategori type
$kategoriList = array();
$kategoriVal  = array();
$GetKategori = ctGeneral("", "I");
if ($GetKategori->RowCount() <> 0) {
	while (!$GetKategori->EOF) {
		array_push($kategoriList, $GetKategori->fields(name));
		array_push($kategoriVal, $GetKategori->fields(ID));
		$GetKategori->MoveNext();
	}
}

//--- Prepare payment type
$pymtList = array();
$pymtVal  = array();
$GetPymt = ctGeneral("", "K");
if ($GetPymt->RowCount() <> 0) {
	while (!$GetPymt->EOF) {
		array_push($pymtList, $GetPymt->fields(name));
		array_push($pymtVal, $GetPymt->fields(ID));
		$GetPymt->MoveNext();
	}
}

$a = 1;


$FormLabel[$a]   	= "Nama Penuh Koperasi";
$FormElement[$a] 	= "name";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "500";
$FormLength[$a]  	= "500";

$a++;
$FormLabel[$a]   	= "Singkatan Koperasi";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Nombor ID Koperasi";
$FormElement[$a] 	= "kopNum";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "Emel Koperasi";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "Nombor Telefon Koperasi<br>Cth: 603XXXXXXXXX";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Tarikh SST Diterima";
$FormElement[$a] 	= "approvedDate";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";

/* $a++;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "test";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";	
 */

$a++;
$FormLabel[$a]   	= "Tarikh Ditubuhkan";
$FormElement[$a] 	= "dateBirth";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Alamat Koperasi";
$FormElement[$a] 	= "address";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";

$a++;
$FormLabel[$a]   	= "Negeri Koperasi";
$FormElement[$a] 	= "stateID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $stateList;
$FormDataValue[$a]	= $stateVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Poskod Koperasi";
$FormElement[$a] 	= "poskod";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "12";

$a++;
$FormLabel[$a]   	= "Zon";
$FormElement[$a] 	= "departmentIDd";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Nombor Faks Koperasi";
$FormElement[$a] 	= "fax";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "18";

$a++;
$FormLabel[$a]   	= "Yuran Terkumpul";
$FormElement[$a] 	= "totalFee";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "25";

$a++;
$FormLabel[$a]   	= "Syer Terkumpul";
$FormElement[$a] 	= "totalShare";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "25";

$a++;
$FormLabel[$a]   	= "Bilangan Anggota";
$FormElement[$a] 	= "staftNo";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";


$a++;
$FormLabel[$a]   	= "Jenis Kod";
$FormElement[$a] 	= "jenisCode";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Pakej";
$FormElement[$a] 	= "pakej";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Amaun Pakej";
$FormElement[$a] 	= "pakej_amaun";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";

$a++;
$FormLabel[$a]   	= "Kategori";
$FormElement[$a] 	= "kategori";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Jenis Koperasi";
$FormElement[$a] 	= "jenis";
$FormType[$a]	  	= "select";
$FormData[$a]   	= array('Kredit', 'Bukan Kredit');
$FormDataValue[$a]	= array('0', '1');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";


$a++;
$FormLabel[$a]   	= "Dorman";
$FormElement[$a] 	= "dorman";
$FormType[$a]	  	= "select";
$FormData[$a]   	= array('Aktif', 'Tak Aktif');
$FormDataValue[$a]	= array('1', '0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "FPX";
$FormElement[$a] 	= "guna_fpx";
$FormType[$a]	  	= "select";
$FormData[$a]   	= array('Ya', 'Tidak');
$FormDataValue[$a]	= array('1', '0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Status Senarai Hitam";
$FormElement[$a] 	= "BlackListID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= array('Ya', 'Tidak');
$FormDataValue[$a]	= array('1', '0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Caj";
$FormElement[$a] 	= "cajID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= array('Ya', 'Tidak');
$FormDataValue[$a]	= array('1', '0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Status Progres";
$FormElement[$a] 	= "statProgress";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";

$a++;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "test";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";	

$a++;
$FormLabel[$a]   	= "Nombor Akaun Bank<br>(XXXXXXXXXXXXXXXX)";
$FormElement[$a] 	= "accTabungan";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";

$a++;
$FormLabel[$a]   	= "Nama Bank";
$FormElement[$a] 	= "bankID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $bankList;
$FormDataValue[$a]	= $bankVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '" . $pk . "' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);
//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->

$checkA = $GetMember->fields('checkA'); //check dpt value on
$checkB = $GetMember->fields('checkB');
$checkC = $GetMember->fields('checkC');

$SQLID = "SELECT * FROM userdetails WHERE userID = '" . $pk . "'";
$GetLoansIDs =  &$conn->Execute($SQLID);
$statusHLID = $GetLoansIDs->fields('statusHL');

if ($SubmitForm <> "") {

	$sqlLoan = "SELECT * FROM loans WHERE userID = '" . $pk . "' AND status = 3 ";
	$GetLoans =  &$conn->Execute($sqlLoan);
	$kira = $GetLoans->RowCount();
	//--- Begin : Call function FormValidation ---  
	for ($i = 1; $i <= count($FormLabel); $i++) {
		for ($j = 0; $j < count($FormCheck[$i]); $j++) {
			FormValidation(
				$FormLabel[$i],
				$FormElement[$i],
				$$FormElement[$i],
				$FormCheck[$i][$j],
				$i
			);
		}
	}
	//--- End   : Call function FormValidation ---  
	$memberDate = substr($memberDate, 6, 4) . '-' . substr($memberDate, 3, 2) . '-' . substr($memberDate, 0, 2);
	$approvedDate = substr($approvedDate, 6, 4) . '-' . substr($approvedDate, 3, 2) . '-' . substr($approvedDate, 0, 2);
	$dateBirth = substr($dateBirth, 6, 4) . '-' . substr($dateBirth, 3, 2) . '-' . substr($dateBirth, 0, 2);
	$dateStarted   = substr($dateStarted, 6, 4) . '-' . substr($dateStarted, 3, 2) . '-' . substr($dateStarted, 0, 2);
	if (count($strErrMsg) == "0") {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");
		$sSQL = "";
		$sWhere = "";
		$sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";
		$sSQL	= "UPDATE users SET " . "name=" . tosql($name, "Text") . ",email=" . tosql($email, "Text") .
			",loginID=" . tosql($loginID, "Text") .
			",updatedDate=" . tosql($updatedDate, "Text") .
			",updatedBy=" . tosql($updatedBy, "Text");
		$sSQL = $sSQL . $sWhere;

		$rs = &$conn->Execute($sSQL);

		if ($address <> "") $address = '<pre>' . $address . '</pre>';
		if ($w_address1 <> "") $w_address1 = '<pre>' . $w_address1 . '</pre>';
		if ($w_address2 <> "") $w_address2 = '<pre>' . $w_address2 . '</pre>';
		if ($w_address3 <> "") $w_address3 = '<pre>' . $w_address3 . '</pre>';
		if ($w_address4 <> "") $w_address4 = '<pre>' . $w_address4 . '</pre>';
		if ($w_address5 <> "") $w_address5 = '<pre>' . $w_address5 . '</pre>';
		$sSQL = "";
		$sWhere = "";
		$sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";

		//get checkbox checked or not
		$checkA = isset($_POST['Anggota']) ? 'on' : 'off';
		$checkB = isset($_POST['Pembiayaan']) ? 'on' : 'off';
		$checkC = isset($_POST['Akaun']) ? 'on' : 'off';

		$sSQL	= "UPDATE userdetails SET " .
			" approvedDate=" . tosql($approvedDate, "Text") .
			", mobileNo=" . tosql($mobileNo, "Text") .
			", jenis=" . tosql($jenis, "Number") .
			", pakej=" . tosql($pakej, "Number") .
			", kategori=" . tosql($kategori, "Number") .
			", totalFee=" . tosql($totalFee, "Number") .
			", totalShare=" . tosql($totalShare, "Number") .
			", poskod=" . tosql($poskod, "Text") .
			", dateBirth=" . tosql($dateBirth, "Text") .
			", departmentID=" . tosql($dept, "Number") .
			", address=" . tosql($address, "Text") .
			", stateID=" . tosql($stateID, "Number") .
			", staftNo=" . tosql($staftNo, "Text") .
			", jenisCode=" . tosql($jenisCode, "Number") .
			", dorman=" . tosql($dorman, "Number") .
			", guna_fpx=" . tosql($guna_fpx, "Number") .
			", BlackListID=" . tosql($BlackListID, "Number") .
			", w_name1=" . tosql($w_name1, "Text") .
			", w_email1=" . tosql($w_email1, "Text") .
			", w_contact1=" . tosql($w_contact1, "Text") .
			", w_jawatan1=" . tosql($w_jawatan1, "Text") .
			", w_name2=" . tosql($w_name2, "Text") .
			", w_email2=" . tosql($w_email2, "Text") .
			", w_contact2=" . tosql($w_contact2, "Text") .
			", w_jawatan2=" . tosql($w_jawatan2, "Text") .
			", accTabungan=" . tosql($accTabungan, "Text") .
			", bankID=" . tosql($bankID, "Text") .
			", cajID=" . tosql($cajID, "Number") .
			", statProgress=" . tosql($statProgress, "Text") .
			", updatedDate=" . tosql($updatedDate, "Text") .
			", updatedBy=" . tosql($updatedBy, "Text") .
			", checkA=" . tosql($checkA, "Text") .
			", checkB=" . tosql($checkB, "Text") .
			", checkC=" . tosql($checkC, "Text");

		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);
		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)" .
			" VALUES ('Mengemaskini maklumat peribadi koperasi - $loginID', 'UPDATE', '" . str_replace("'", "", $sSQL) . "', '" . get_session('Cookie_userID') . "','" . $updatedDate . "', '" . $updatedBy . "')";
		$rs = &$conn->Execute($sqlAct);

		$SQLID = "select * FROM userdetails where userID = '" . $pk . "'";
		$GetLoansIDs =  &$conn->Execute($SQLID);
		$statusHLID = $GetLoansIDs->fields('statusHL');

		if ($statusHLID == '1') {
			for ($i = 0; $i < $kira; $i++) {

				$sqlLoan = "select * FROM loans where userID = '" . $pk . "' AND status = 3 ";
				$GetLoans =  &$conn->Execute($sqlLoan);
				$GetLoansID = $GetLoans->fields('loanID');

				$sSQL =	'';
				$sWhere	= '	loanID	 = ' . $GetLoansID;
				$sSQL	= '	UPDATE loans ';
				$sSQL	.= ' SET ' .
					' status	=' . tosql(7, "Text") .
					' ,selesaiBy	=' . tosql($updatedBy, "Text") .
					' ,selesaiDate='	. tosql($updatedDate, "Text");
				$sSQL .= ' WHERE ' . $sWhere;
				$rsHL	= &$conn->Execute($sSQL);
			}
		} // end loop update


		print '<script>
					alert ("Maklumat koperasi telah dikemaskinikan ke dalam sistem.");
					window.location.href = "' . $sActionFileName . '";
				</script>';
	}
}
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->

$checkA = ($checkA == "on" ? "checked" : "on");
$checkB = ($checkB == "on" ? "checked" : "on");
$checkC = ($checkC == "on" ? "checked" : "on");

print '
<form name="MyForm" action=' . $sFileName . ' method=post>
<div class="mb-3 row">

                    <h5 class="card-title">' . strtoupper($title) . '</h5>';


//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
	$cnt = $i % 2;

	if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT PENDAFTARAN KOPERASI</div>';

	$addr = str_replace("<pre>", "", $GetMember->fields('w_address1'));
	$addr1 = str_replace("</pre>", "", $addr);

	//Modul Koperasi
	if ($i == 27) {
		print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="col-md-2 col-form-label">Modul</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<input type="checkbox" class="form-check-input" id="checkA" name="Anggota" style="width: 18px; height: 20px;"' . $checkA . '>&nbsp;&nbsp;Anggota </td> &nbsp;&nbsp;&nbsp;&nbsp;
		<td><input type="checkbox" class="form-check-input" id="checkB" name="Pembiayaan" style="width: 18px; height: 20px;" ' . $checkB . '>&nbsp;&nbsp;Pembiayaan&nbsp;&nbsp;&nbsp;&nbsp;
		<td><input type="checkbox" class="form-check-input" id="checkC" name="Akaun" style="width: 18px; height: 20px;" ' . $checkC . '>&nbsp;&nbsp;Akaun </td>';
	}

	if ($i == 15) {
		print '<div class="card-header mb-3">MAKLUMAT TAMBAHAN KOPERASI</div>';
	}
	//B
	if ($i == 27) {
		print '<div class="card-header mb-3">STAF KOPERASI (PEGAWAI YANG BERTANGGUNGJAWAB)</div>';

		print '<div class="row m-1 mt-3">
						<div class="col-md-3">
							<div class="mb-2">
								<label class="form-label" for="validationCustom032">Nama Staf 1</label>
								<input type="text" class="form-control" name="w_name1" value="' . tohtml($GetMember->fields('w_name1')) . '" size=30 maxlength=70 id="validationCustom032">                                                            
							</div>
						</div>
					
						<div class="col-md-2">
							<div class="mb-2">
								<label class="form-label" for="validationCustom04">* Emel</label>
								<input type="text" class="form-control " name="w_email1" value="' . tohtml($GetMember->fields('w_email1')) . '" size=30 maxlength=70 id="validationCustom04">
							</div>
						</div>
						<div class="col-md-2">
							<div class="mb-2">
								<label class="form-label" for="validationCustom04">No. Telefon</label>
								<input type="text" class="form-control" name="w_contact1" value="' . tohtml($GetMember->fields('w_contact1')) . '" size=15 maxlength=15 id="validationCustom04" placeholder="(6XXXXXXXXXX)">
							</div>
						</div>
						<div class="col-md-2">
							<div class="mb-2">
								<label class="form-label" for="validationCustom05">Jawatan</label>      
								<input type="text" class="form-control" name="w_jawatan1" value="' . tohtml($GetMember->fields('w_jawatan1')) . '" size=15 maxlength=50 id="validationCustom05">         
							</div>
						</div>
				</div>';

		print '<div class="row m-1 mt-3">
					<div class="col-md-3">
						<div class="mb-2">
							<label class="form-label" for="validationCustom032">Nama Staf 2</label>
							<input type="text" class="form-control" name="w_name2" value="' . tohtml($GetMember->fields('w_name2')) . '" size=30 maxlength=50 id="validationCustom032">                                                            
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom04">Emel</label>
							<input type="text" class="form-control" name="w_email2" value="' . tohtml($GetMember->fields('w_email2')) . '" size=30 maxlength=70 id="validationCustom04">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom04">No. Telefon</label>
							<input type="text" class="form-control" name="w_contact2" value="' . tohtml($GetMember->fields('w_contact2')) . '" size=15 maxlength=55 id="validationCustom04" placeholder="(6XXXXXXXXXX)">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom05">Jawatan</label>      
							<input type="text" class="form-control" name="w_jawatan2" value="' . tohtml($GetMember->fields('w_jawatan2')) . '" size=15 maxlength=30 id="validationCustom05">         
						</div>
					</div>
			</div>';


		/* print	'			</table>
					</td>
			   </tr>
		       <div class="card-header mt-3">PENCADANG (NOMBOR ANGGOTA YANG TELAH BERDAFTAR BERSAMA KOPERASI)</div>'; */
	}


	if ($i == 27) print '<div class="card-header mt-3">MAKLUMAT BANK KOPERASI</div>';

	if ($cnt == 1) print '<div class="m-1 row">';
	print '<label class="col-md-2 col-form-label">' . $FormLabel[$i];
	// if (!($i == 6 OR $i == 26 OR $i == 32 )) print ':';
	print ' </label>';
	if (in_array($FormElement[$i], $strErrMsg))
		print '<div class="col-md-4 bg-danger">';
	else
		print '<div class="col-md-4">';
	//--- Begin : Call function FormEntry ---------------------------------------------------------  
	$strFormValue = tohtml($GetMember->fields($FormElement[$i]));
	if ($FormType[$i] == 'textarea') {
		$strFormValue = str_replace("<pre>", "", $GetMember->fields($FormElement[$i]));
		$strFormValue = str_replace("</pre>", "", $strFormValue);
	}

	if ($i == 11) {
		if (!$dept) {
			$strFormValue = dlookup("general", "b_Address", "ID=" . tosql($GetMember->fields('departmentID'), "Number"));
		} else {
			$strFormValue = dlookup("general", "b_Address", "ID=" . $dept);
		}
		$strFormValue = str_replace("<pre>", "", $strFormValue);
		$strFormValue = str_replace("</pre>", "", $strFormValue);
	}

	FormEntry(
		$FormLabel[$i],
		$FormElement[$i],
		$FormType[$i],
		$strFormValue,
		$FormData[$i],
		$FormDataValue[$i],
		$FormSize[$i],
		$FormLength[$i]
	);

	// zon dropdown
	if ($i == 11) {
		if (!isset($dept)) $dept = $GetMember->fields('departmentID');
		print '<select name="dept"  class="form-selectx">
				<option value="">- Semua -';
		for ($j = 0; $j < count($deptList); $j++) {
			print '	<option value="' . $deptVal[$j] . '" ';
			if ($dept == $deptVal[$j]) print ' selected';
			print '>' . $deptList[$j];
		}
		print '</select>&nbsp;';
	}

	//jenis kod drop down
	if ($i == 16) {
		if (!isset($jenisCode)) $jenisCode = $GetMember->fields('jenisCode');
		print '<select class="form-selectx" name="jenisCode">
			<option value="">- pilih jenis Kod -';
		for ($j = 0; $j < count($jenisCodeList); $j++) {
			print '	<option value="' . $jenisCodeVal[$j] . '" ';
			if ($jenisCode == $jenisCodeVal[$j]) print ' selected';
			print '>' . $jenisCodeList[$j];
		}
		print '		</select>&nbsp;';
	}

	//pakej drop down
	if ($i == 17) {
		if (!isset($pakej)) $pakej = $GetMember->fields('pakej');
		print '<select class="form-selectx" name="pakej">
			<option value="">- pilih Pakej -';
		for ($j = 0; $j < count($pakejList); $j++) {
			print '	<option value="' . $pakejVal[$j] . '" ';
			if ($pakej == $pakejVal[$j]) print ' selected';
			print '>' . $pakejList[$j];
		}
		print '		</select>&nbsp;';
	}

	//kategori drop down
	if ($i == 19) {
		if (!isset($kategori)) $kategori = $GetMember->fields('kategori');
		print '<select class="form-selectx" name="kategori">
			<option value="">- pilih Kategori -';
		for ($j = 0; $j < count($kategoriList); $j++) {
			print '	<option value="' . $kategoriVal[$j] . '" ';
			if ($kategori == $kategoriVal[$j]) print ' selected';
			print '>' . $kategoriList[$j];
		}
		print '		</select>&nbsp;';
	}

	//--- End   : Call function FormEntry ---------------------------------------------------------  
	print '</div>';
	if ($cnt == 0) print '</div>';
}

if (get_session("Cookie_groupID") == 2) {
	print '<div class="mb-3 row">
                <center>
                        <input type="hidden" name="pk" value="' . $pk . '">
						<!--input type="button" class="btn btn-secondary btn-md waves-effect waves-light" value="<<"--><br>
						<!-- <input type="button" name="Kembali" value="<<"  class="btn btn-md btn-secondary" onclick= "Javascript:(window.location.href=\'?vw=memberProfil&mn=905\')"> -->
                        <input type=Submit name=SubmitForm class="btn btn-primary btn-md waves-light waves-effects" value="Kemaskini" style="margin-right: 10px;">
                </center>
            </div>';
}
print '</div>
</form>';
include("footer.php");
