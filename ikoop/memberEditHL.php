<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberEdit.php
*          Date 		: 	10/10/2003
*********************************************************************************/
include("header.php");	
	
include("koperasiQry.php");	
include ("forms.php");
date_default_timezone_set("Asia/Jakarta");
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.location="index.php";</script>';
}

//if ($HTTP_COOKIE_VARS["Cookie_groupID"] <> 1 AND $HTTP_COOKIE_VARS["Cookie_groupID"] <> 2) {
//	print '<script>alert("'.$errPage.'");window.location="index.php";
//}

$sFileName		= "?vw=memberEditHL&mn=$mn";
$sActionFileName= "?vw=memberEditHL&mn=$mn&pk=".$pk;
$title     		= "Kemaskini Maklumat Anggota Hutang Lapuk";

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

/*/--- Prepare department type
	


//--- Prepare department type
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
*/



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
$a++;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "a";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";	

$a++;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "b";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";	

$FormLabel[$a]   	= "Nama Penuh";
$FormElement[$a] 	= "name";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "40";
$FormLength[$a]  	= "70";

$a++;
$FormLabel[$a]   	= "No. Anggota";
$FormElement[$a] 	= "memberID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "Id Pengguna";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Tarikh Menjadi Anggota";
$FormElement[$a] 	= "approvedDate";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckDate);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Emel";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "Kad Pengenalan";
$FormElement[$a] 	= "newIC";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank,CheckNumeric);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "12";	

$a++;
$FormLabel[$a]   	= "Alamat";
$FormElement[$a] 	= "address";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Bandar";
$FormElement[$a] 	= "city";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";	

$a++;
$FormLabel[$a]   	= "Poskod";
$FormElement[$a] 	= "postcode";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckNumeric);
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "5";

$a++;
$FormLabel[$a]   	= "Negeri";
$FormElement[$a] 	= "stateID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $stateList;
$FormDataValue[$a]	= $stateVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "No. Telefon Rumah";
$FormElement[$a] 	= "homeNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";	

$a++;
$FormLabel[$a]   	= "No. Telefon";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";	

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "No. Akaun Tabungan<br>(12-345-678901-2)";
$FormElement[$a] 	= "accTabungan";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";	

$a++;
$FormLabel[$a]   	= "Pekerjaan (Nama Syarikat)";
$FormElement[$a] 	= "NameCmp";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";	

$a++;
$FormLabel[$a]   	= "No. Pekerja Bank Rakyat";
$FormElement[$a] 	= "staftNo";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";

$a++;
$FormLabel[$a]   	= "Jawatan Perkerjaan";
$FormElement[$a] 	= "JobDesp";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "25";

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
$FormLabel[$a]   	= "Alamat Perkerjaan Sekarang";
$FormElement[$a] 	= "addOffice";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "Agama";
$FormElement[$a] 	= "religionID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $religionList;
$FormDataValue[$a]	= $religionVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "CTOS";
$FormElement[$a] 	= "ctos";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Mahkamah Tribunal";
$FormElement[$a] 	= "mhkmh";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Bulan Akhir Pembayaran (mm/yyyy)";
$FormElement[$a] 	= "DatePymt";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckDate);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Mahkamah Sesyen";
$FormElement[$a] 	= "sesyen";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Peringkat";
$FormElement[$a] 	= "peringkat";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckNumeric);
$FormSize[$a]    	= "5";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "Mahkamah Tinggi";
$FormElement[$a] 	= "Tinggi";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Akuan Hapus Kira";
$FormElement[$a] 	= "AKHP";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";	

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";

$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";


$a++;
$FormLabel[$a]   	= "";
$FormElement[$a] 	= "";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";


//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
//$conn->debug=1;
//$GetMember = ctMemberDetail($pk);
$strMember = "SELECT a . * , b . * FROM users a, userdetails b WHERE a.userID = '".$pk."' AND a.userID = b.userID ";
$GetMember = &$conn->Execute($strMember);

//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->
if ($SubmitForm <> "") {

/*	if ($dept =='') {
		array_push ($strErrMsg, "departmentIDd");
		print '- <font class=redText>Sila pilih jabatan.</font><br />';
	}	

	
	if ($accTabungan) {
	if (!ereg ("([0-9]{2})-([0-9]{3})-([0-9]{6})-([0-9]{1})", $accTabungan, $regs)) {
		array_push ($strErrMsg, "accTabungan");
		print '- <font class=redText>No. akaun tabungan tersebut tidak mengikut format.</font><br />';
	}
	}
	
	if ($accTabungan) {
	if(dlookup("userdetails", "accTabungan", "accTabungan=" . tosql($accTabungan, "Text")) <> '') {
		array_push ($strErrMsg, "accTabungan");
		print '- <font class=redText>No. akaun tabungan tersebut telah digunakan.</font><br />';
	}
	}
	*/
	//--- Begin : Call function FormValidation ---  
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
	$approvedDate = substr($approvedDate,6,4).'-'.substr($approvedDate,3,2).'-'.substr($approvedDate,0,2);
	$dateBirth = substr($dateBirth,6,4).'-'.substr($dateBirth,3,2).'-'.substr($dateBirth,0,2);
	$dateStarted   = substr($dateStarted,6,4).'-'.substr($dateStarted,3,2).'-'.substr($dateStarted,0,2);
	if (count($strErrMsg) == "0") {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");
		$DatePymt = substr($DatePymt,6,4).'-'.substr($DatePymt,3,2).'-'.substr($DatePymt,0,2);
		$statusHL = 1;
		$statusBL = 0;
		$sSQL = "";
		$sWhere = "";				
	    $sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";		
       	$sSQL	= "UPDATE userdetails SET " .
			       "BlackListHL=" . tosql($statusBL, "Text")  ;
		$sSQL = $sSQL . $sWhere;
//		print $sSQL.'<br>';
		$rs = &$conn->Execute($sSQL);
		
		
				//$updatedBy 	= get_session("Cookie_userName");
		//$updatedDate = date("Y-m-d H:i:s");               
		$sSQL2 = "";
		$sWhere2 = "";				
	    $sWhere2 = "userID=" . tosql($pk, "Text");
		$sWhere2 = " WHERE (" . $sWhere . ")";		
       	$sSQL2	= "UPDATE users SET " .
		          "name=" . tosql($name, "Text") .
		          ",email=" . tosql($email, "Text").
				  ",updatedDate=" . tosql($updatedDate, "Text") .
		          ",updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL2 = $sSQL2 . $sWhere2;
//		print $sSQL.'<br>';
		$rs2 = &$conn->Execute($sSQL2);
if ($address <> "") $address = '<pre>'.$address.'</pre>';
		//if ($w_address <> "") $w_address = '<pre>'.$w_address.'</pre>';
		if ($w_address1 <> "") $w_address1 = '<pre>'.$w_address1.'</pre>';
		if ($w_address2 <> "") $w_address2 = '<pre>'.$w_address2.'</pre>';
		if ($w_address3 <> "") $w_address3 = '<pre>'.$w_address3.'</pre>';
		if ($w_address4 <> "") $w_address4 = '<pre>'.$w_address4.'</pre>';
		if ($w_address5 <> "") $w_address5 = '<pre>'.$w_address5.'</pre>';
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";		
       	$sSQL	= "UPDATE userdetails SET " .
		          "newIC=" . tosql($newIC, "Text").
		          ", raceID=" . tosql($raceID, "Number").
		          ", religionID=" . tosql($religionID, "Number").
		          ", JobDesp=" . tosql($JobDesp, "Text").
			  	  ", accTabungan=" . tosql($accTabungan, "Text").
		          ", address=" . tosql($address, "Text").
		          ", city=" . tosql($city, "Text").
		          ", postcode=" . tosql($postcode, "Text").				  			  
		          ", stateID=" . tosql($stateID, "Number").
		          ", homeNo=" . tosql($homeNo, "Number").
		          ", mobileNo=" . tosql($mobileNo, "Text").
		          ", addOffice=" . tosql($addOffice, "Text").
		          ", NameCmp=" . tosql($NameCmp, "Text").
		          ", ctos=" . tosql($ctos, "Number").				  			  
		          ", mhkmh=" . tosql($mhkmh, "Number").
				  ", sesyen=" . tosql($sesyen, "Number").
				  ", Tinggi=" . tosql($Tinggi, "Number").
				  ", AKHP=" . tosql($AKHP, "Number").
				  ", peringkat=" . tosql($peringkat, "Text").
		          ", statusHL=" . tosql($statusHL, "Number").
		          ", w_name1=" . tosql($w_name1, "Text").
		          ", w_ic1=" . tosql($w_ic1, "Text").
		          ", w_relation1=" . tosql($w_relation1, "Text").
		          ", w_address1=" . tosql($w_address1, "Text").
			      ", saksi1=" . tosql($saksi1, "Text").
		          ", saksiIC1=" . tosql($saksiIC1, "Text").
		          ", saksi2=" . tosql($saksi2, "Text").
		          ", saksiIC2=" . tosql($saksiIC2, "Text").
				  ", updatedDate=" . tosql($updatedDate, "Text") .
				  ", DatePymt=" . tosql($DatePymt, "Text") .
		          ", updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);
		//echo $rs3;
		//if($rs) activityLog("UPDATE", "Mengemaskini maklumat peribadi anggota -$pk",get_session('Cookie_userID'),$updatedBy);
 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini maklumat peribadi anggota -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL3 ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);
		
		print '<script>
					alert ("Maklumat anggota telah dikemaskinikan ke dalam sistem.");
					window.location.href = "'.$sActionFileName.'";
				</script>';
	}
}			
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->

print '
<form name="MyForm" action='.$sFileName.' method=post>
<input type="hidden" name="picture" value="'.$pic.'">
<div class="mb-3 row">
	<h5 class="card-title">'.strtoupper($title).'</h5>';

//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	if ($i == 1) print '<div class="card-header mb-3 mt-3">MAKLUMAT PENDAFTARAN ID</div>';
	if ($i == 9) print '<div class="card-header mb-3 mt-3">BUTIR-BUTIR PERIBADI</div>';
	//if ($i == 31) print '<tr><td class=Header colspan=4>B. JUMLAH HUTANG TERTUNGGAK :</td></tr>';
	
//	if ($i == 37) print '<tr><td class=Header colspan=4>Maklumat Waris:</td></tr>';
//	if ($i == 43) print '<tr><td class=Header colspan=4>Pembelian Syer Koperasi :</td></tr>';
	//	$addr = str_replace("<pre>","",$GetMember->fields('w_address1'));
	//	$addr1 = str_replace("</pre>","",$addr);
	/*	$addr = str_replace("<pre>","",$GetMember->fields('w_address2'));
		$addr2 = str_replace("</pre>","",$addr);
		$addr = str_replace("<pre>","",$GetMember->fields('w_address3'));
		$addr3 = str_replace("</pre>","",$addr);
		$addr = str_replace("<pre>","",$GetMember->fields('w_address4'));
		$addr4 = str_replace("</pre>","",$addr);
		$addr = str_replace("<pre>","",$GetMember->fields('w_address5'));
		$addr5 = str_replace("</pre>","",$addr); */
/*	if ($i == 33) {
		print '<tr><td class=Header colspan=4>C. PENAMA:</td></tr>';
		print '<tr class="Data">
					<td colspan="4">
						<table width="100%">
							<tr class="DataB">
								<td>&nbsp;</td>	
								<td>Nama</td>
								<td>No KP</td>
								<td>Hubungan</td>
								<td>Alamat</td>
							</tr>
						<tr class="Data">
								<td valign="top">&nbsp;</td>	
								<td valign="top"><input type="text" name="w_name1" value="'.tohtml($GetMember->fields('w_name1')) .'" size=30 maxlength=50></td>
								<td valign="top"><input type="text" name="w_ic1" value="'.tohtml($GetMember->fields('w_ic1')) .'" size=15 maxlength=14></td>
								<td valign="top"><input type="text" name="w_relation1" value="'.tohtml($GetMember->fields('w_relation1')) .'" size=15 maxlength=15></td>
								<td valign="top"><textarea cols=30 rows=3 wrap="hard" name="w_address1">'.$addr1.'</textarea></td>
							</tr>';
print	'			</table>
					</td>
			   </tr>
		       <tr><td class=Header colspan=4>D. PENJAMIN :</td></tr>';
	} */

	if ($cnt == 1) print '<div class="m-1 row">';
	print '<label class="col-md-2 col-form-label">'.$FormLabel[$i];
	//if (!($i == 1 or $i == 2 or $i == 8  or $i ==20 or $i ==21 or $i ==22 or $i ==30 or $i == 32)) print ':';
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
/*
	if($i==19){
		if(!$dept){ $strFormValue = dlookup("general", "b_Address", "ID=" . tosql($GetMember->fields('departmentID'), "Number"));
		}else{
			$strFormValue = dlookup("general", "b_Address", "ID=" .$dept);
		}
		$strFormValue = str_replace("<pre>","",$strFormValue);
		$strFormValue = str_replace("</pre>","",$strFormValue);
	}
*/
	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);

	if($i == 1){
		if(!isset($pic)) $pic = dlookup("userdetails", "picture", "userID=" . tosql($pk, "Text"));
		$Gambar= "upload_images/".$pic;
		print '<img id="elImage" src="'.$Gambar.'" width="100" height="90">&nbsp;<input type="button" name="GetPicture" class="btn btn-secondary" value="Tambah Gambar" width="30" height="10" onclick= "Javascript:(window.location.href=\'?vw=uploadwin&edthl&mn='.$mn.'&pk='.$pk.'\')">';
	}

	
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';
}

//if (get_session("Cookie_groupID") == 2) {
print '<div class="mb-3 row">
                <center>
			<input type="hidden" name="pk" value="'.$pk.'">
			<input type=Submit name=SubmitForm class="btn btn-primary waves-light waves-effect" value=Kemaskini>
			</center>
            </div>';
//}

print '</table>
</form></div>';

include("footer.php");	
?>
