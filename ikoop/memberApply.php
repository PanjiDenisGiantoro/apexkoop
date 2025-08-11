<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	memberApply.php
 *          Date 		: 	21/03/2006
 *          Date Update	: 	08/11/2023
 *********************************************************************************/
include("header.php");
include("koperasiQry.php");
include("forms.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

$Cookie_userID = get_session('Cookie_userID');
$Cookie_userName = get_session("Cookie_userName");
$sFileName		= "?vw=memberApply&mn=$mn";
$sActionFileName = "?vw=mainpage";
$title     		= "Pendaftaran Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress

$strErrMsg = array();

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

//--- Prepare department type
$deptList = array();
$deptVal  = array();
$GetDept = ctGeneral("", "B");
if ($GetDept->RowCount() <> 0) {
	while (!$GetDept->EOF) {
		array_push($deptList, $GetDept->fields(name));
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
$FormLabel[$a]   	= "* Nama Penuh Koperasi";
$FormElement[$a] 	= "name";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "500";
$FormLength[$a]  	= "500";

$a++;
$FormLabel[$a]   	= "* Singkatan Koperasi";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "30";

$a++;
$FormLabel[$a]   	= "* Nombor ID Koperasi";
$FormElement[$a] 	= "kopNum";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "* Emel Koperasi<br>(Pastikan Sah)";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "* Nombor Telefon Koperasi<br>Cth: 603XXXXXXXXX";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "12";

$a++;
$FormLabel[$a]   	= "* Tarikh Ditubuhkan";
$FormElement[$a] 	= "dateBirth";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
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
$FormLabel[$a]   	= "* Zon";
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
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "test";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

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
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";

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
$FormLabel[$a]   	= "Kategori";
$FormElement[$a] 	= "kategori";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "* Jenis Koperasi";
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
$FormLength[$a]  	= "15";

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

//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->
if (!$SubmitForm) {
	if ($dateBirth) {
		$getdate = explode("/", $dateBirth);
		$dateBirth = $getdate[2] . '/' . sprintf("%02s",  $getdate[1]) . '/' . sprintf("%02s",  $getdate[0]);
	}
}

if ($SubmitForm <> "") {

	if ($dept == '') {
		array_push($strErrMsg, "departmentIDd");
		print '<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                    </button>
                                                    <strong>Sila pilih Zon.</strong> 
                                                </div>';
	}

	$GetLogin = ctLogin($loginID);
	if ($GetLogin->RowCount() == 1) {
		array_push($strErrMsg, "loginID");
		print '<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                        </button>
                                                        <strong>* Singkatan Koperasi sudah wujud. Sila pilih Singkatan Koperasi yang lain</strong> 
                                                    </div>';
	}

	if ($accTabungan) {
		if (!dlookup("userdetails", "newIC", "newIC=" . tosql($newIC, "Text"))) {
			if (dlookup("userdetails", "accTabungan", "accTabungan=" . tosql($accTabungan, "Text")) <> '') {
				array_push($strErrMsg, "accTabungan");
				print '<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                                                    </button>
                                                                                    <strong>* No. akaun tersebut telah digunakan.</strong> 
                                                                                </div>';
			}
		}
	}

	if ($w_email1 == null) {
		print '<div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                        </button>
                                                        <strong>Staf yang bertanggungjawab mesti dilengkapkan.</strong> 
                                                    </div>';
		$penama = "errData";
		$penamaerr = "parsley-error";
	}
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
	$getdate = explode("/", $dateBirth);
	$dateBirth   = substr($dateBirth, 6, 4) . '-' . substr($dateBirth, 3, 2) . '-' . substr($dateBirth, 0, 2);
	$dateStarted   = substr($dateStarted, 6, 4) . '-' . substr($dateStarted, 3, 2) . '-' . substr($dateStarted, 0, 2);
	if (count($strErrMsg) == "0") {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");
		$applyDate = date("Y-m-d H:i:s");
		$sSQLi = "";
		$sSQLi	= "SELECT max( CAST( memberID AS SIGNED INTEGER ) ) + 1 as new FROM userdetails";
		$rsi = &$conn->Execute($sSQLi);
		$userID = $rsi->fields('new');
		$sSQL = "";
		$sSQL	= "INSERT INTO users (" . "userID," . "loginID,"/* ."password," */ . "email," . "name," . "applyDate)" . "VALUES(" .      tosql($userID, "Text") . ", " .
			tosql($loginID, "Text") . ", " .
			/*  tosql($password, "Text") . ", " . */
			tosql($email, "Text") . ", " .
			tosql($name, "Text") . ", " .
			tosql($applyDate, "Text") . ") ";
		$rs = &$conn->Execute($sSQL);

		$memberID = $userID;

		if (!isset($Cookie_userID)) $uid = $userID;
		else $uid =  $Cookie_userID;
		if (!isset($Cookie_userName)) $uname = $loginID;
		else $uname =  $Cookie_userName;
		$activity = "Permohonan Koperasi";
		if ($rs) activityLog($sSQL, $activity, $uid, $uname);

		if ($address <> "") $address = '<pre>' . $address . '</pre>';
		if ($w_address <> "") $w_address = '<pre>' . $w_address . '</pre>';
		if ($w_address1 <> "") $w_address1 = '<pre>' . $w_address1 . '</pre>';

		//get checkbox checked or not
		$checkA = isset($_POST['Anggota']) ? 'on' : 'off';
		$checkB = isset($_POST['Pembiayaan']) ? 'on' : 'off';
		$checkC = isset($_POST['Akaun']) ? 'on' : 'off';

		$sSQL = "";
		$sSQL	= "INSERT INTO userdetails 
(
" . "userID, 
" . "memberID, 
" . "kopNum,
" . "checkA,
" . "checkB,
" . "checkC,
" . "pakej,
" . "totalFee,
" . "totalShare,
" . "kategori,
" . "dorman,
" . "guna_fpx,
" . "poskod, 
" . "fax, 
" . "staftNo,
" . "dateBirth,
" . "jenis, 
" . "jenisCode,
" . "accTabungan,
" . "bankID,
" . "address,
" . "stateID, 
" . "mobileNo,
" . "departmentID, 
" . "cajID, 
" . "statProgress, 
" . "w_name1, 
" . "w_email1, 
" . "w_contact1, 
" . "w_jawatan1, 
" . "w_name2, 
" . "w_email2, 
" . "w_contact2, 
" . "w_jawatan2,
" . "updatedBy, 
" . "updatedDate)" .
			" VALUES (" .
			tosql($userID, "Text") . ", " .
			tosql($memberID, "Text") . ", " .
			tosql($kopNum, "Text") . ", " .
			tosql($checkA, "Text") . ", " .
			tosql($checkB, "Text") . ", " .
			tosql($checkC, "Text") . ", " .
			tosql($pakej, "Number") . ", " .
			tosql($totalFee, "Number") . ", " .
			tosql($totalShare, "Number") . ", " .
			tosql($kategori, "Number") . ", " .
			tosql($dorman, "Number") . ", " .
			tosql($guna_fpx, "Number") . ", " .
			tosql($poskod, "Text") . ", " .
			tosql($fax, "Text") . ", " .
			tosql($staftNo, "Text") . ", " .
			tosql($dateBirth, "Text") . ", " .
			tosql($jenis, "Number") . ", " .
			tosql($jenisCode, "Number") . ", " .
			tosql($accTabungan, "Text") . ", " .
			tosql($bankID, "Text") . ", " .
			tosql($address, "Text") . ", " .
			tosql($stateID, "Number") . ", " .
			tosql($mobileNo, "Text") . ", " .
			tosql($dept, "Number") . ", " .
			tosql($cajID, "Number") . ", " .
			tosql($statProgress, "Text") . ", " .
			tosql($w_name1, "Text") . ", " .
			tosql($w_email1, "Text") . ", " .
			tosql($w_contact1, "Text") . ", " .
			tosql($w_jawatan1, "Text") . ", " .
			tosql($w_name2, "Text") . ", " .
			tosql($w_email2, "Text") . ", " .
			tosql($w_contact2, "Text") . ", " .
			tosql($w_jawatan2, "Text") . ", " .
			tosql($name, "Text") . ", " .
			tosql($applyDate, "Text") . ")";

		$rs = &$conn->Execute($sSQL);
		if (!isset($Cookie_userID)) $uid = $userID;
		else $uid =  $Cookie_userID;
		if (!isset($Cookie_userName)) $uname = $loginID;
		else $uname =  $Cookie_userName;
		$activity = "Permohonan Koperasi";
		if ($rs) activityLog($sSQL, $activity, $uid, $uname);

		//error handling
		// echo "SQL Query: $sSQL<br>";
		// if (!$rs) {
		// 	echo "Database Error: " . $conn->ErrorMsg();
		// }

		$sSQL = "";
		$sSQL	= "INSERT INTO userloandetails 
		(" . "userID," . "memberID)" . " VALUES (" .
			tosql($userID, "Text") . ", " .
			tosql($memberID, "Text") . ")";


		$rs = &$conn->Execute($sSQL);
		alert("Permohonan menjadi koperasi telah didaftarkan ke dalam sistem.");
		gopage("$sActionFileName", 1000);
	}
	$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)" .
		" VALUES ('Permohonan Koperasi - $loginID', 'UPDATE', '" . str_replace("'", "", $sSQL) . "', '" . get_session('Cookie_userID') . "','" . $updatedDate . "', '" . $updatedBy . "')";
	$rs = &$conn->Execute($sqlAct);
}
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->
?>
<form name="MyForm" action="<?php print $sFileName; ?>" method=post>
	<input type="hidden" name="userID" value="<?php print $userID; ?>">
	<input type="hidden" name="loanType" value="<?php print $loanType; ?>">
	<div class="mb-3 row">
		<h5 class="card-title"><?php echo strtoupper($title); ?><br></h5>

		<?php
		//--- Begin : Looping to display label -------------------------------------------------------------
		for ($i = 1; $i <= count($FormLabel); $i++) {
			$cnt = $i % 2;
			if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT PENDAFTARAN KOPERASI</div>';

			//Modul Koperasi
			if ($i == 24) {
				print '<label class="col-md-2 col-form-label">Modul</label>&nbsp;&nbsp;&nbsp;

		<input type="checkbox" class="form-check-input" id="checkA" name="Anggota" style="width: 18px; height: 20px;"' . $checkA . '>&nbsp;&nbsp;Anggota </td> &nbsp;&nbsp;&nbsp;&nbsp;
		<td><input type="checkbox" class="form-check-input" id="checkB" name="Pembiayaan" style="width: 18px; height: 20px;" ' . $checkB . '>&nbsp;&nbsp;Pembiayaan&nbsp;&nbsp;&nbsp;&nbsp;
		<td><input type="checkbox" class="form-check-input" id="checkC" name="Akaun" style="width: 18px; height: 20px;" ' . $checkC . '>&nbsp;&nbsp;Kewangan </td>';
			}

			if ($i == 15) {
				print '<div class="card-header">MAKLUMAT TAMBAHAN KOPERASI</div>';
			}

			if ($i == 25) {
				print '<div class="card-header">STAF KOPERASI (PEGAWAI YANG BERTANGGUNGJAWAB)</div>';

				print '<div class="row m-1 mt-3">
					<div class="col-md-3">
						<div class="mb-2">
							<label class="form-label" for="validationCustom032">Nama Staf 1</label>
							<input type="text" class="form-control" name="w_name1" value="' . $w_name1 . '" size=35 maxlength=70 id="validationCustom032">
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom03">* Emel</label>
							<input type="text" class="form-control ' . $penamaerr . '" class="form-control" name="w_email1" value="' . $w_email1 . '" size=15 maxlength=70 id="validationCustom03">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom04">Nombor Telefon</label>
							<input type="text" class="form-control " name="w_contact1" value="' . $w_contact1 . '" size=15 maxlength=14 id="validationCustom04" placeholder="Cth: 6011XXXXXXXX">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom05">Jawatan</label>                        
							<input type="text" class="form-control " name="w_jawatan1" value="' . $w_jawatan1 . '" size=25 maxlength=70 id="validationCustom05">                                                           
						</div>
					</div>
				</div>';

				print '<div class="row m-1 mt-3">
					<div class="col-md-3">
						<div class="mb-2">
							<label class="form-label" for="validationCustom032">Nama Staf 2</label>
							<input type="text" class="form-control" name="w_name2" value="' . $w_name2 . '" size=35 maxlength=70 id="validationCustom032">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom03">Emel</label>
							<input type="text" class="form-control" class="form-control" name="w_email2" value="' . $w_email2 . '" size=15 maxlength=70 id="validationCustom03">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom04">Nombor Telefon</label>
							<input type="text" class="form-control" name="w_contact2" value="' . $w_contact2 . '" size=15 maxlength=14 id="validationCustom04" placeholder="Cth: 6011XXXXXXXX">
						</div>
					</div>
					<div class="col-md-2">
						<div class="mb-2">
							<label class="form-label" for="validationCustom05">Jawatan</label>                        
							<input type="text" class="form-control" name="w_jawatan2" value="' . $w_jawatan2 . '" size=25 maxlength=70 id="validationCustom05">                                                           
						</div>
					</div>
				</div>';

			}
			if ($i == 25) print '<div class="card-header">MAKLUMAT BANK KOPERASI</div>';

			if ($cnt == 1) print '<div class="m-1 row">';
			print '<label class="col-md-2 col-form-label">' . $FormLabel[$i];
			print ' </label>';
			if (in_array($FormElement[$i], $strErrMsg))
				print '<div class="col-md-4 bg-danger">';
			else
				print '<div class="col-md-4">';

			if ($i == 6) {
				if ($birth) $strFormValue = '12/45/1922';
			}

			if ($i == 8) {
				if ($dept) $strFormValue = dlookup("general", "b_Address", "ID=" . $dept);
				$strFormValue = str_replace("<pre>", "", $strFormValue);
				$strFormValue = str_replace("</pre>", "", $strFormValue);
				print '<b>' . $strFormValue . '</b>';
			}

			//--- Begin : Call function FormEntry ---------------------------------------------------------  
			$strFormValue = $$FormElement[$i];
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

			//zon dropdown
			if ($i == 10) {
				print '<select class="form-selectx" name="dept">
				<option value="">- pilih Zon Koperasi -';
				for ($j = 0; $j < count($deptList); $j++) {
					print '	<option value="' . $deptVal[$j] . '" ';
					if ($dept == $deptVal[$j]) print ' selected';
					print '>' . $deptList[$j];
				}
				print '		</select>&nbsp;';
			}

			//Kod dropdown
			if ($i == 16) {
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
				//if(!isset($pakej)) $pakej = $GetMember->fields('pakej'); 
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
			if ($i == 18) {
				//if(!isset($kategori)) $kategori = $GetMember->fields('kategori'); 
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
		?>
		<div class="mb-3 row">
			<center><br>
				<input type="Submit" class="btn btn-primary w-md waves-effect waves-light" name="SubmitForm" value="Hantar">
				<!-- <input type="Reset" class="btn btn-secondary w-md waves-effect waves-light" name="ResetForm" value="Isi semula"> -->
			</center>
		</div>
	</div>
</form>
<?php include("footer.php"); ?>