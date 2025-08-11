<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberEdit.php
*          Date 		: 	29/03/2006
*********************************************************************************/
include("header.php");	
include("koperasiList.php");	
include("koperasiQry.php");	
include("forms.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}

$sFileName		= "?vw=memberUpdate&mn=4";
$sActionFileName= "?vw=memberUpdate&mn=4";
$title     		= "Kemaskini Profil";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

//--- Prepare race type
$raceList = Array();
$raceVal  = Array();
$GetRace = ctGeneral("","E");
if ($GetRace->RowCount() <> 0){
	while (!$GetRace->EOF) {
		array_push ($raceList, $GetRace->fields(name));
		array_push ($raceVal, $GetRace->fields(ID));
		$GetRace->MoveNext();
	}
}	

//--- Prepare religion type
$religionList = Array();
$religionVal  = Array();
$GetReligion = ctGeneral("","F");
if ($GetReligion->RowCount() <> 0){
	while (!$GetReligion->EOF) {
		array_push ($religionList, $GetReligion->fields(name));
		array_push ($religionVal, $GetReligion->fields(ID));
		$GetReligion->MoveNext();
	}
}	

//--- Prepare state type
$stateList = Array();
$stateVal  = Array();
$GetState = ctGeneral("","H");
if ($GetState->RowCount() <> 0){
	while (!$GetState->EOF) {
		array_push ($stateList, $GetState->fields(name));
		array_push ($stateVal, $GetState->fields(ID));
		$GetState->MoveNext();
	}
}	
//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$GetDept = ctGeneral("","B");
if ($GetDept->RowCount() <> 0){
	while (!$GetDept->EOF) {
		array_push ($deptList, $GetDept->fields(name));
		array_push ($deptVal, $GetDept->fields(ID));
		$GetDept->MoveNext();
	}
}

$bankList = Array();
$bankVal  = Array();
$Getbank = ctGeneral("","Z");
if ($Getbank->RowCount() <> 0){
	while (!$Getbank->EOF) {
		array_push ($bankList, $Getbank->fields(name));
		array_push ($bankVal, $Getbank->fields(ID));
		$Getbank->MoveNext();
	}
}

//--- Prepare society
$societyList = Array();
$societyVal  = Array();
$GetSociety = ctGeneral("","L");
if ($GetSociety->RowCount() <> 0){
	while (!$GetSociety->EOF) {
		array_push ($societyList, $GetSociety->fields(name));
		array_push ($societyVal, $GetSociety->fields(ID));
		$GetSociety->MoveNext();
	}
}	

//--- Prepare payment type
$pymtList = Array();
$pymtVal  = Array();
$GetPymt = ctGeneral("","K");
if ($GetPymt->RowCount() <> 0){
	while (!$GetPymt->EOF) {
		array_push ($pymtList, $GetPymt->fields(name));
		array_push ($pymtVal, $GetPymt->fields(ID));
		$GetPymt->MoveNext();
	}
}	

$a = 1;
$FormLabel[$a]   	= "Nama Penuh";
$FormElement[$a] 	= "name";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "No. Anggota";
$FormElement[$a] 	= "memberID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "Id Pengguna";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Tarikh Menjadi Anggota";
$FormElement[$a] 	= "approvedDate";
$FormType[$a]	  	= "hiddenDate";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Emel";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

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
$FormLabel[$a]   	= "Kad Pengenalan<br/>Tiada (-)";
$FormElement[$a] 	= "newIC";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank,CheckNumeric);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "12";	

$a++;
$FormLabel[$a]   	= "Tarikh Lahir";
$FormElement[$a] 	= "dateBirth";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Jawatan Pekerjaan";
$FormElement[$a] 	= "job";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";	

$a++;
$FormLabel[$a]   	= "Cawangan / Kawasan / Zon";
$FormElement[$a] 	= "departmentIDd";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Alamat Kediaman";
$FormElement[$a] 	= "address";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "Alamat Cawangan";
$FormElement[$a] 	= "addressSuratd";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "Poskod Kediaman";
$FormElement[$a] 	= "postcode";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckNumeric);
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "5";

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
$FormLabel[$a]   	= "Bandar Kediaman";
$FormElement[$a] 	= "city";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";	

$a++;
$FormLabel[$a]   	= "* No. Telefon<br/>Tiada (-)";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";	

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
$FormLabel[$a]   	= "No. Pekerja<br>(Sekiranya Ada)";
$FormElement[$a] 	= "staftNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Jantina";
$FormElement[$a] 	= "sex";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Lelaki','Perempuan');
$FormDataValue[$a]	= array('0','1');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Bangsa";
$FormElement[$a] 	= "raceID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $raceList;
$FormDataValue[$a]	= $raceVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Status Perkahwinan";
$FormElement[$a] 	= "maritalID";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Bujang','Berkahwin','Janda/Duda');
$FormDataValue[$a]	= array('0','1','2');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Agama";
$FormElement[$a] 	= "religionID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $religionList;
$FormDataValue[$a]	= $religionVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

/*$a++;
$FormLabel[$a]   	= "Status Pekerjaan";
$FormElement[$a] 	= "statuskerja";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Tetap','Kontrak','Sendiri');
$FormDataValue[$a]	= array('0','1','2');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";*/

$a++;
$FormLabel[$a]   	= "Yuran Bulanan (RM)";
$FormElement[$a] 	= "monthFee";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Syer Bulanan (RM)";
$FormElement[$a] 	= "unitShare";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Deposit Khas (RM)";
$FormElement[$a] 	= "monthDepo";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

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
$FormLabel[$a]   	= "Nama Pencadang";
$FormElement[$a] 	= "saksi1";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "Kad Pengenalan<br/>Tiada (-)";
$FormElement[$a] 	= "saksiIC1";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "12";	

$a++;
$FormLabel[$a]   	= "No. Akaun Bank";
$FormElement[$a] 	= "accTabungan";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
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
$pk = get_session('Cookie_userID');
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '".$pk."' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);

//$saksi1 = dlookup("users", "name", "userID=" . tosql($GetMember->fields('userID'), "Number"));

//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->
if ($SubmitForm <> "") {
	//--- Begin : Call function FormValidation ---  
	if ($dept =='') {
		array_push ($strErrMsg, "departmentIDd");
		print '- <font class=redText>Sila pilih jabatan.</font><br />';
	}	

	if ($bankID =='') {
		array_push ($strErrMsg, "bankID");
		print '- <script>alert("Pilih Bank Anggota")</script><br />';
		//
	}

	if ($accTabungan =='') {
		array_push ($strErrMsg, "accTabungan");
		print '- <script>alert("Kemaskini Maklumat BANK")</script><br />';
		//
	}

	if (!$w_name1) {
		array_push ($strErrMsg, "w_name1");
		//print '- <font class=redText>* Penama mesti dilengkapkan.</font><br />';
                                    echo '                                                     
                                                    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                        </button>
                                                        <strong>Penama mesti dilengkapkan.</strong> 
                                                    </div>';
                
		$penama = "errData";
                                    $penamaerr = "parsley-error";
	}

	for ($i = 1; $i <= count($FormLabel); $i++) {
		for($j=0 ; $j < count($FormCheck[$i]); $j++) {
			FormValidation ($FormLabel[$i], 
							$FormElement[$i], 
							$$FormElement[$i],
							$FormCheck[$i][$j],
							$i);
		}
	}	
	//--- End   : Call function FormValidation ---  
	$memberDate = substr($memberDate,6,4).'-'.substr($memberDate,3,2).'-'.substr($memberDate,0,2);
	$dateBirth = substr($dateBirth,6,4).'-'.substr($dateBirth,3,2).'-'.substr($dateBirth,0,2);
	$dateStarted   = substr($dateStarted,6,4).'-'.substr($dateStarted,3,2).'-'.substr($dateStarted,0,2);
	
	if (count($strErrMsg) == "0") {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = "";
		$sWhere = "";				
	    $sWhere = "userID=" .tosql($pk, "Text");
		$sWhere = " WHERE (".$sWhere.")";		
       	$sSQL	= "UPDATE users SET " .
		          "email=" 	. tosql($email, "Text").
				  ",updatedDate=" . tosql($updatedDate, "Text") .
		          ",updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL = $sSQL . $sWhere;
	$rs = &$conn->Execute($sSQL);

		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "userID=" .tosql($pk, "Text");
		$sWhere = " WHERE (". $sWhere.")";		
       	$sSQL	= "UPDATE userdetails SET " .
		          "	 sex=" 			. tosql($sex, "Number").
		          ", raceID=" 		. tosql($raceID, "Number").
		          ", staftNo=" 		. tosql($staftNo, "Text").
		          ", religionID=" 	. tosql($religionID, "Number").
		          ", maritalID=" 	. tosql($maritalID, "Number").
		          ", job=" 			. tosql($job, "Text").
		          ", address=" 		. tosql($address, "Text").
		          ", accTabungan=" 	. tosql($accTabungan, "Text").
		          ", bankID=" 		. tosql($bankID, "Text").
		          ", dateBirth=" 	. tosql($dateBirth, "Text").
		          ", city=" 		. tosql($city, "Text").
		          ", postcode=" 	. tosql($postcode, "Text").				  			  
		          ", stateID=" 		. tosql($stateID, "Number").
		          ", mobileNo=" 	. tosql($mobileNo, "Text").
		          ", departmentID=" . tosql($dept, "Number").
		          ", w_name1=" 		. tosql($w_name1, "Text").
		          ", w_ic1=" 		. tosql($w_ic1, "Text").
		          ", w_relation1=" 	. tosql($w_relation1, "Text").
		          ", w_contact1=" 	. tosql($w_contact1, "Text").
		          ", w_address1=" 	. tosql($w_address1, "Text").
				  ", updatedDate=" 	. tosql($updatedDate, "Text") .
		          ", updatedBy=" 	. tosql($updatedBy, "Text") ;

		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);
		$activity = "Mengemaskini maklumat anggota";
 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini maklumat akaun anggota -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);	
                
                alert ("Maklumat anggota telah dikemaskinikan ke dalam sistem.");
                                    gopage("$sActionFileName",1000);
                                    /*
		print '<script>
					alert ("Maklumat anggota telah dikemaskinikan ke dalam sistem.");
					window.location.href = "'.$sActionFileName.'";
				</script>'; */
	}
}			
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->
//<input type="hidden" name="picture" value="'.$pic.'">
print '
<form name="MyForm" action='.$sFileName.' method=post>
<h5 class="card-title"><i class="fas fa-user"></i>&nbsp;'.strtoupper($title).' &nbsp;</h5>

<div class="mb-3 row">
';
//(* Menunjukkan anggota dibenarkan mengubah maklumat.)
//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	if ($i == 1) print '<div class="card-header">MAKLUMAT PENDAFTARAN ID</div>';
	if ($i == 7) print '<div class="card-header mt-3">BUTIR-BUTIR PERIBADI</div>';
	if ($i == 23) print '<div class="card-header mt-3">BAYARAN YURAN/SYER BULANAN</div>';
	
	$addr = str_replace("<pre>","",$GetMember->fields('w_address1'));
	$addr1 = str_replace("</pre>","",$addr);
	
	if ($i == 27) {
		print '<div class="card-header mt-3">PENAMA (18 TAHUN KE ATAS)</div>';
                                           
                                    print '<div class="row m-3 mt-3">
                                                    <div class="col-md-3">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom032">Nama Penama</label><br/>
                                                            <input type="text" name="w_name1" value="'.tohtml($GetMember->fields('w_name1')).'" class="form-control'.$penamaerr.'" size=30 maxlength=50 id="validationCustom032">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom03">Kad Pengenalan</label><br/>
                                                            <input type="text" name="w_ic1" value="'.tohtml($GetMember->fields('w_ic1')).'" class="form-control'.$penamaerr.'" size=15 maxlength=14 id="validationCustom03" placeholder="(999999999999)">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom04">No. Telefon</label><br/>
                                                            <input type="text" name="w_contact1" value="'.tohtml($GetMember->fields('w_contact1')).'" id="validationCustom04" class="form-control'.$penamaerr.'" size=15 maxlength=15 placeholder="(XXXXXXXXXXX)">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom05">Hubungan Penama</label><br/>
                                                            <input type="text" name="w_relation1" value="'.tohtml($GetMember->fields('w_relation1')).'" class="form-control'.$penamaerr.'" size=15 maxlength=15 id="validationCustom05" >                                                            
                                                                                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom06">Alamat Kediaman</label><br/>
                                                            <textarea class="form-control'.$penamaerr.'" cols=30 rows=3 id="validationCustom06" wrap="hard" name="w_address1" >'.$addr1.'</textarea>                                                            
                                                        </div>
                                                    </div>
                                                </div>';
                            
                /*
		print '<table width="100%">
                                                                            <tr class="DataB">
                                                                                    <td>&nbsp;</td>	
                                                                                    <td>Nama Penama</td>
                                                                                    <td>Nombor Kad Pengenalan<br><b>(XXXXXXXXXXXX)</b></td>
                                                                                    <td>No. Telefon Koperasi<br><b>(6XXXXXXXXXX)</b></td>
                                                                                    <td>Hubungan Penama</td>
                                                                                    <td>Alamat Kediaman</td>
                                                                            </tr>
                                                                    <tr class="'.$penama.'">
                                                                                    <td valign="top">&nbsp;</td>	
                                                                                    <td valign="top"><input type="text" name="w_name1" value="'.tohtml($GetMember->fields('w_name1')).'" class="form-control-sm" size=30 maxlength=50></td>
                                                                                    <td valign="top"><input type="text" name="w_ic1" value="'.tohtml($GetMember->fields('w_ic1')).'" class="form-control-sm" size=15 maxlength=14  ></td>
                                                                                    <td valign="top"><input type="text" name="w_relation1" value="'.tohtml($GetMember->fields('w_relation1')).'" class="form-control-sm" size=15 maxlength=15  ></td>
                                                                                    <td valign="top"><input type="text" name="w_contact1" value="'.tohtml($GetMember->fields('w_contact1')).'" class="form-control-sm" size=15 maxlength=15  ></td>
                                                                                    <td valign="top"><textarea class="form-control-sm" cols=30 rows=3 wrap="hard" name="w_address1" >'.$addr1.'</textarea></td>
                                                                    </tr>';
	
                                    print '</table>'; */
                                    
                    print '<div class="card-header mt-3">PENCADANG</div>';
	}

	if ($i == 29) print '<div class="card-header mt-3">MAKLUMAT BANK</div>';

	if ($cnt == 1) print '<div class="m-3 row">';
	print '<label class="col-md-2 col-form-label">'.$FormLabel[$i];
	// if (!($i == 6 OR $i == 14 OR $i == 26)) print ':';
	print ' </label>';
	if (in_array($FormElement[$i], $strErrMsg))
	  print '<div class="col-md-4 bg-danger">';
	else
	  print '<div class="col-md-4">';
	//--- Begin : Call function FormEntry ---------------------------------------------------------  
	$strFormValue = tohtml($GetMember->fields($FormElement[$i])); 
	if ($FormType[$i] == 'textarea') {
		$strFormValue = str_replace("<pre>","",$GetMember->fields($FormElement[$i]));
		$strFormValue = str_replace("</pre>","",$strFormValue);
	}

	if($i==12){
		if(!$dept){ $strFormValue = dlookup("general", "b_Address", "ID=" . tosql($GetMember->fields('departmentID'), "Number"));
		}else{
			$strFormValue = dlookup("general", "b_Address", "ID=" .$dept);
		}
		$strFormValue = str_replace("<pre>","",$strFormValue);
		$strFormValue = str_replace("</pre>","",$strFormValue);
	}

	if($i==27){ 
		$strFormValue = dlookup("users", "name", "userID=" . tosql($GetMember->fields('saksi1'), "Number"));
	}

	if($i==28){ 
		$strFormValue = dlookup("userdetails", "newIC", "userID=" . tosql($GetMember->fields('saksi1'), "Number"));
	}

	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);

/*	if($i == 1){
		if(!isset($pic)) $pic = dlookup("userdetails", "picture", "userID=" . tosql($pk, "Text"));
		$Gambar= "upload_images/".$pic;
		print '<img id="elImage" src="'.$Gambar.'" width="100" height="90">&nbsp;<input type="button" name="GetPicture" value="Tambah Gambar" width="30" height="10" onclick= "Javascript:(window.location.href=\'uploadwin.php?up=1&pk='.$pk.'\')">';
	}*/
	if($i==10){
			if(!isset($dept)) $dept = $GetMember->fields('departmentID');  
			print '<select name="dept" class="form-select"">
				<option value="">- Semua -';
			for ($j = 0; $j < count($deptList); $j++) {
				print '	<option value="'.$deptVal[$j].'" ';
				if ($dept == $deptVal[$j]) print ' selected';
				print '>'.$deptList[$j];
			}
			print '		</select>&nbsp;';
	}
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';
}

print '<div class="mb-3 row">
                                    <center>
                                            <input type="hidden" name="pk" value="'.$pk.'">
			<input type="Submit" name="SubmitForm" class="btn btn-primary w-md waves-effect waves-light" value="Kemaskini Maklumat">
                                    </center>
                                </div>'; 

print '</div></form>';
include("footer.php");	?>