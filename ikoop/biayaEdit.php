<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	biayaEdit.php
*          Date 		: 	12/12/2006
*********************************************************************************/
include("header.php");	
include("koperasiQry.php");	
include("forms.php");
date_default_timezone_set("Asia/Jakarta");

if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}
$user = 0;
if (get_session("Cookie_groupID") == 0) $user = 1;
if(!isset($valAddDpt)) $valAddDpt = 0;
if(!isset($valAddBlj)) $valAddBlj = 0;
// checking whether update is by staf or by member
if($pk) 
{
	$loanID = $pk ; $pk = dlookup("loans", "userID", "loanID=" . $pk); 
}

if($user) 
{
	$pk = get_session('Cookie_userID'); $user = 1; 
}

if($loanID) 
{
	$strpk = "&pk=".$loanID; 
}
else $strpk = '';

//$sFileName		= "biayaEdit.php".$strpk;
$sFileName		= "?vw=biayaEdit&mn=3".$strpk;
//if (get_session("Cookie_groupID") == 0) $sActionFileName= "biayaEdit.php"; else $sActionFileName= "biayaEdit.php?pk=".$loanID;
if (get_session("Cookie_groupID") == 0) $sActionFileName= "?vw=biayaEdit&mn=3"; else $sActionFileName= "?vw=biayaEdit&mn=3&pk=".$loanID;
$title     		= "Kemaskini Maklumat Pembiayaan";

//--------- delete upon hapus selection --------
if ($_GET['del']){
	$delete  = "DELETE FROM userstates WHERE ID = ".$del;
	$conn->Execute($delete);
}

if ($_GET['dele']){
	$delete  = "DELETE gaji_img FROM userloandetails WHERE ID = ".$dele;
	$conn->Execute($delete);
}
//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();
// $a = 1;
// $FormLabel[$a]   	= "Nama Penuh";
// $FormElement[$a] 	= "name";
// $FormType[$a]	  	= "hidden";
// $FormData[$a]   	= "";
// $FormDataValue[$a]	= "";
// $FormCheck[$a]   	= array();
// $FormSize[$a]    	= "30";
// $FormLength[$a]  	= "50";

// $a++;
// $FormLabel[$a]   	= "No Kad Pengenalan";
// $FormElement[$a] 	= "newIC";
// $FormType[$a]	  	= "hidden";
// $FormData[$a]   	= "";
// $FormDataValue[$a]	= "";
// $FormCheck[$a]   	= array();
// $FormSize[$a]    	= "20";
// $FormLength[$a]  	= "12";	

$a = 1;
$FormLabel[$a]   	= "Nama";
$FormElement[$a] 	= "namapsgn";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "35";
$FormLength[$a]  	= "35";	

$a++;
$FormLabel[$a]   	= "Hubungan";
$FormElement[$a] 	= "hbgnruj1";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Pekerjaan";
$FormElement[$a] 	= "jobpsgn";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Majikan";
$FormElement[$a] 	= "majikanpsgn";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Gaji";
$FormElement[$a] 	= "gaji";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Alamat Majikan";
$FormElement[$a] 	= "addresspsgn";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "No. Telefon";
$FormElement[$a] 	= "mobruj1";
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
$FormCheck[$a]   	= '';
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";

//RUJUKAN NOMBOR 2
/*$a++;
$FormLabel[$a]   	= "Nama Rujukan (2)";
$FormElement[$a] 	= "namaruj2";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "35";
$FormLength[$a]  	= "25";	

$a++;
$FormLabel[$a]   	= "Hubungan (2)";
$FormElement[$a] 	= "hbgnruj2";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Pekerjaan Rujukan (2)";
$FormElement[$a] 	= "jobruj2";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "35";
$FormLength[$a]  	= "25";

$a++;
$FormLabel[$a]   	= "Majikan Rujukan (2)";
$FormElement[$a] 	= "majikanruj2";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Gaji Rujukan (2)";
$FormElement[$a] 	= "gajiruj2";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";


$a++;
$FormLabel[$a]   	= "Alamat Majikan Rujukan (2)";
$FormElement[$a] 	= "addressruj2";
$FormType[$a]	  	= "textarea";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "3";	

$a++;
$FormLabel[$a]   	= "No Telefon Rujukan (2)";
$FormElement[$a] 	= "mobruj2";
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
$FormCheck[$a]   	= '';
$FormSize[$a]    	= "";
$FormLength[$a]  	= "";*/

//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
$strMember = "SELECT a.*, b.memberID, b.newIC, b.dateBirth, b.job, b.address, b.city, b.postcode, b.stateID, b.departmentID, b.approvedDate, c.* FROM users a, userdetails b, userloandetails c WHERE a.userID = '".$pk."' AND a.userID = b.userID and b.userID = c.userID";
$GetMember = &$conn->Execute($strMember);
//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->
$updatedBy 	= get_session("Cookie_userName");
$updatedDate = date("Y-m-d H:i:s");               
if ($SubmitForm <> "") {
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
	if (count($strErrMsg) == "0") {
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";		
		$sSQL	= "UPDATE userloandetails SET " .
		          " namapsgn= '" . $namapsgn . "' " .
		          ", jobpsgn= '" . $jobpsgn . "' " .
				  ", hbgnruj1= '" . $hbgnruj1 . "' " .
		          ", majikanpsgn= '" . $majikanpsgn . "' " .
		          ", addresspsgn= '" . $addresspsgn . "' " .
				  ", mobruj1= '" . $mobruj1 . "' " .
				  ", gaji= '" . $gaji . "' " ./*
				  ", namaruj2= '" . $namaruj2 . "' " .
				  ", jobruj2= '" . $jobruj2 . "' " .
				  ", hbgnruj2= '" . $hbgnruj2 . "' " .
				  ", majikanruj2= '" . $majikanruj2 . "' " .
				  ", addressruj2= '" . $addressruj2 . "' " .
				  ", gajiruj2= '" . $gajiruj2 . "' " .
				  ", mobruj2= '" . $mobruj2 . "' " .*/
				  ", updatedDate= '" . $updatedDate . "' " .
		          ", updatedBy= '" . $updatedBy . "' ";
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);
                
                alert ("Maklumat telah dikemaskinikan ke dalam sistem.");
                gopage("$sActionFileName",1000);
                
                
                /*
print '<script>
			alert ("Maklumat telah dikemaskinikan ke dalam sistem.");
			window.location.href = "'.$sActionFileName.'";
			</script>'; */
	}
}			
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->

if($SubmitDpt == 'Kemaskini' || $SubmitBlj == 'Kemaskini'){
	if($SubmitDpt == 'Kemaskini'){
		if($valueD){

			$totJ = count($stateIDd); 
			foreach($valueD as $key => $value){
			$ID = $stateIDd[$j]; //id for this  single pdpt
			$amt = $valueD[$ID]; //a
			$sSQL = "";
			$sWhere = "";		
			$sWhere = "ID='" . $key . "'";
			$sWhere = " WHERE (" . $sWhere . ")";		
			$sSQL	= "UPDATE userstates SET " .
					  " amt='" . $value . "'" .
					  ", updateDate=" . tosql($updatedDate, "Text") .
					  ", updateBy=" . tosql($updatedBy, "Text");
			$sSQL = $sSQL . $sWhere;
			$rs = &$conn->Execute($sSQL);
			}
		}
		if($id2){
		$userID = $pk;
		$payType = "A";
			$applyDate = date("Y-m-d H:i:s");  
			$payID = $id2;
			$amt = $value2;
			$sSQL = "";
			$sSQL	= "INSERT INTO userstates (" . 
					  "userID, " . 
					  "payType, " . 
					  "payID, " .							
					  "amt, " .							
					  "insertBy, " .							
					  "insertDate)" . 
					  " VALUES (" . 
					  tosql($userID, "Text") . ", " .
					  tosql($payType, "Text") . ", ".
					  tosql($payID, "Text") . ", " .
					  tosql($amt, "Text") . ", " .
					  tosql($updatedBy, "Text") . ", " .
					  tosql($updatedDate, "Text") . ") ";
			if($payID) $rs = &$conn->Execute($sSQL);	
			$valAddDpt = 0;
		}
	}
		//--------------------------
	if($SubmitBlj == 'Kemaskini'){
		if($valueJ){
			foreach($valueJ as $key => $value){
			$sSQL = "";
			$sWhere = "";		
			$sWhere = "ID='" . $key . "'";
			$sWhere = " WHERE (" . $sWhere . ")";		
			$sSQL	= "UPDATE userstates SET " .
					  " amt='" . $value . "'" .
					  ", updateDate=" . tosql($updatedDate, "Text") .
					  ", updateBy=" . tosql($updatedBy, "Text");
			$sSQL = $sSQL . $sWhere;
			$rs = &$conn->Execute($sSQL);
			}
		}
		if($idq2){
		$userID = $pk;
		$payType = "B";
			$applyDate = date("Y-m-d H:i:s");    
			$payID = $idq2;
			$amt = $valueq2;
			$sSQL = "";
			$sSQL	= "INSERT INTO userstates (" . 
					  "userID, " . 
					  "payType, " . 
					  "payID, " .							
					  "amt, " .							
					  "insertBy, " .							
					  "insertDate)" . 
					  " VALUES (" . 
					  tosql($userID, "Text") . ", " .
					  tosql($payType, "Text") . ", ".
					  tosql($payID, "Text") . ", " .
					  tosql($amt, "Text") . ", " .
					  tosql($updatedBy, "Text") . ", " .
					  tosql($updatedDate, "Text") . ") ";
			if($payID) $rs = &$conn->Execute($sSQL);	
			$valAddBlj = 0;
		}		
	}
}
print '
<form name="MyForm" action='.$sFileName.' method=post>
<table border="0" cellpadding="3" cellspacing="0" width="50%" align="left" class="table-striped">
<h5 class="card-title"><i class="mdi mdi-update"></i>&nbsp;'.strtoupper($title).'</h5>
	';
//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	// if ($i == 1) print '<div class="card-header mt-3">1. PEMOHON</div>';
	if ($i == 1) print '<div class="card-header mt-3">BUTIR-BUTIR RUJUKAN PERTAMA (PASANGAN / KELUARGA / KAWAN)</div>';

	// if ($i == 3) print '<div class="card-header mt-3">BUTIR-BUTIR RUJUKAN KEDUA (PASANGAN / KELUARGA / KAWAN)</div>';


	if ($cnt == 1) print '<div class="mb-3 mt-3 row">';
	print '<label class="col-md-2 col-form-label">'.$FormLabel[$i];
	// if (!($i == 10 or $i == 18 or $i == 19)) print ':';
	
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

	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);

	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';
}
if(!isset($pic)) $pic = dlookup("userloandetails", "gaji_img", "userID=" . tosql($pk, "Text"));
$Gambar= "upload_gaji/".$pic;
if(!isset($picjwtn)) $picjwtn = dlookup("userloandetails", "jwtn_img", "userID=" . tosql($pk, "Text"));
$Gambarjwtn= "upload_jwtn/".$pic;
if(!isset($picic)) $picic = dlookup("userloandetails", "ic_img", "userID=" . tosql($pk, "Text"));
$Gambaric= "upload_ic/".$pic;
if(!isset($picccris)) $picccris = dlookup("userloandetails", "ccris_img", "userID=" . tosql($pk, "Text"));
$Gambarccris= "upload_CCRIS/".$pic;

print '<div class="mb-3 row">
                <center>
                        <input type="Submit" class="btn btn-primary w-md waves-effect waves-light" name="SubmitForm" value="KEMASKINI RUJUKAN">
                </center>
                                </div>'; 

print '
                                    <div class="card-header mt-3">PENYATA PENDAPATAN &nbsp;&nbsp;
		<input type="button" name="kemaskini" value="KEMASKINI PENDAPATAN" class="btn btn-sm btn-info w-md waves-effect waves-light"
		onClick="window.location.href=\'?vw=biayaEditAdd&mn=3&payType=A&userID='.$pk.'&loanID='.$loanID.'\';"></div>';


//---------------------- update pendapatan dan perbelanjaan  ----------------

if($addD) $ctlTambah = 'disabled';
if($addB) $ctlTambah = 'disabled';
if($valAddDpt<=0) $valAddDpt=0;
if($addDpt) $valAddDpt++;
if($minDpt) $valAddDpt--;

		$SQLdpt = "";
		$SQLdpt	= "SELECT * FROM userstates WHERE userID = '".$pk."' AND payType = 'A'";
		$rsdpt = &$conn->Execute($SQLdpt);
		
		if ($rsdpt->RowCount() <> 0) {
			$i = 1;
			while (!$rsdpt->EOF) {
				$id = $rsdpt->fields('payID');
				$stateID = $rsdpt->fields('ID');
				$name = dlookup("general", "name", "ID=" . tosql($id, "Number"));
				$value = $rsdpt->fields('amt');
				if($loanID) $strpk = "&pk=".$loanID; else $strpk = '';
                                
                                                        print '<div class="row">
				<label class="col-md-1 col-form-label"></label>
                                                                        <label class="col-md-8 col-form-label">
                                                                        <input name="idD['.$stateID.']" type="hidden" value="'.$id.'">
					<input name="stateIDd['.$stateID.']" type="hidden" value="'.$stateID.'"> 
					<input name="nameD['.$stateID.']" type="text" class="btn btn-sm btn-light" value="'.strtoupper($name).'" size="30" align="right" onfocus="this.blur()"> &nbsp; <input name="valueD['.$stateID.']" class="form-control-sm" size="10" maxlength="15" type="text" value="'.$value.'">
                                                                                         
					<a href="?vw=biayaEdit&mn=3&del='.$stateID.''.$strpk.'" class="badge bg-danger text-dark" onClick="return confirm(\'Adakah anda Pasti?\')" title="Hapus"> <i class="fas fa-trash-alt"></i>
					</a></label>
				<label class="col-md-3 col-form-label"></label>				
				</div>';
                                
                                /*
				print 	'<tr valign="top">
				<td class="Data" align="right" width="20%">
					<input name="idD['.$stateID.']" type="hidden" value="'.$id.'">
					<input name="stateIDd['.$stateID.']" type="hidden" value="'.$stateID.'"> 
					<input name="nameD['.$stateID.']" type="text" class="btn btn-sm btn-secondary" value="'.strtoupper($name).'" size="20" align="right" onfocus="this.blur()">
                                                                                          : </td>
				<td class="Data">
					<input name="valueD['.$stateID.']" size="10" maxlength="15" type="text" value="'.$value.'"> &nbsp;
					<font face="Verdana, Arial, Helvetica, sans-serif" size="-2" color="#0000FF">
					<a href="?vw=biayaEdit&mn=3&del='.$stateID.''.$strpk.'" onClick="return confirm(\'Adakah anda Pasti?\')">Hapus 
					</a></font>
				</td>
				<td class="Data" align="right">&nbsp; </td>
				<td class="Data">&nbsp;</td>
				</tr>'; */
				$totD = $i;
				$i++;
				$rsdpt->MoveNext();
			}
			print '<input name="totidD" type="hidden" value="'.$totD.'">';
		}

		$SQLdptT = "";
		$SQLdptT	= "SELECT sum(amt) as tot FROM `userstates` WHERE userID = '".$pk."' AND payType = 'A'";
		$rsdptT = &$conn->Execute($SQLdptT);
if ($rsdptT->RowCount() <> 0) {

		$tot = $rsdptT->fields('tot');
                
                                    print '<div class="row">
				<label class="col-md-1 col-form-label"></label>
                                                                        <label class="col-md-8 col-form-label">
                                                                        <input name="nameD1" class="btn btn-sm" value="JUMLAH PENDAPATAN" size="30" onfocus="this.blur()" align="right" type="text"> &nbsp <input name="valueD1"  class="form-control-sm" size="10" maxlength="15" value="'.$tot.'" type="text" onfocus="this.blur()"></label>
				<label class="col-md-3 col-form-label"></label>				
				</div>';
                                    
}

if($addD){
print 	'<tr valign="top">
		<td class="Data" align="right" width="20%">
			<input name="id2" type="hidden" value="'.$id2.'">
			<input name="code2" type="hidden" value="'.$code2.'"> 
			<input name="name2" type="text" class="Data" value="'.$name2.'" size="20" align="right" onfocus="this.blur()">: </td>
		<td class="Data">
			<input name="value2" size="10" maxlength="15" type="text" value="'.$value2.'"> &nbsp;
			<input type="button" class="label" value="..." onclick="window.open(\'selBiaya.php?no='.$i.'&userID='.$pk.'\',\'sel\',\'top=10,left=10,width=550,height=300,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">

		</td>
		<td class="Data" align="right">&nbsp; </td>
		<td class="Data">&nbsp;</td>
		</tr>';
}


                        print '<div class="card-header mt-3">PENYATA PERBELANJAAN &nbsp;&nbsp;
		<input type="button" name="kemaskini" value="KEMASKINI PERBELANJAAN" class="btn btn-sm btn-info w-md waves-effect waves-light"
		onClick="window.location.href=\'?vw=biayaEditAdd&mn=3&payType=B&userID='.$pk.'&loanID='.$loanID.'\';"></div>';


//------------------------- penyata perbelanjaan ------------------
if($valAddBlj<=0) $valAddBlj=0;
if($addBlj) $valAddBlj++;
if($minBlj) $valAddBlj--;
$SQLblj = "";
		$SQLblj	= "SELECT * FROM `userstates` WHERE userID = '".$pk."' AND payType = 'B'";
		$rsblj = &$conn->Execute($SQLblj);
		
		if ($rsblj->RowCount() <> 0) {
			$i = 1;
			while (!$rsblj->EOF) {
				$id = $rsblj->fields('payID');
				$stateID = $rsblj->fields('ID');
				$name = dlookup("general", "name", "ID=" . tosql($id, "Number"));
				$value = $rsblj->fields('amt');
				if($loanID) $strpk = "&pk=".$loanID; else $strpk = '';
                                
                                print '<div class="row">
				<label class="col-md-1 col-form-label"></label>
                                                                        <label class="col-md-8 col-form-label">
                                                                        <input name="idJ['.$stateID.']" type="hidden" value="'.$id.'">
					<input name="stateIDj['.$stateID.']" type="hidden" value="'.$stateID.'"> 
					<input name="nameJ['.$stateID.']" type="text" class="btn btn-sm btn-light" value="'.$name.'" size="30" align="right" onfocus="this.blur()"> &nbsp <input name="valueJ['.$stateID.']" class="form-control-sm" size="10" maxlength="15" type="text" value="'.$value.'">
                                                                                         
					<a href="?vw=biayaEdit&mn=3&del='.$stateID.''.$strpk.'" class="badge bg-danger text-dark" onClick="return confirm(\'Adakah anda Pasti?\')" title="Hapus"> <i class="fas fa-trash-alt"></i></a> </label>
				<label class="col-md-3 col-form-label"></label>				
				</div>';
                                
                                /*
				print 	'<tr valign="top">
				<td class="Data" align="right" width="20%">
					<input name="idJ['.$stateID.']" type="hidden" value="'.$id.'">
					<input name="stateIDj['.$stateID.']" type="hidden" value="'.$stateID.'"> 
					<input name="nameJ['.$stateID.']" type="text" class="btn btn-sm btn-secondary" value="'.$name.'" size="20" align="right" onfocus="this.blur()">: </td>
				<td class="Data">
				<input name="valueJ['.$stateID.']" size="10" maxlength="15" type="text" value="'.$value.'"> &nbsp;
					<font face="Verdana, Arial, Helvetica, sans-serif" size="-2" color="#0000FF">
					<a href="?vw=biayaEdit&mn=3&del='.$stateID.''.$strpk.'" onClick="return confirm(\'Adakah anda Pasti?\')">Hapus 
					</a></font>
				</td>
				<td class="Data" align="right">&nbsp; </td>
				<td class="Data">&nbsp;</td>
				</tr>'; */
				$totJ = $i;
				$i++;
				$rsblj->MoveNext();
			}
			print '<input name="totidJ" type="hidden" value="'.$totJ.'">';
		}

		$SQLbljT = "";
		$SQLbljT	= "SELECT sum(amt) as tot FROM `userstates` WHERE userID = '".$pk."' AND payType = 'B'";
		$rsbljT = &$conn->Execute($SQLbljT);
if ($rsbljT->RowCount() <> 0) {

		$tot = $rsbljT->fields('tot');

                                            print '<div class="row">
				<label class="col-md-1 col-form-label"></label>
                                                                        <label class="col-md-8 col-form-label">
                                                                        <input name="nameD1" class="btn btn-sm" value="JUMLAH PERBELANJAAN" size="30" onfocus="this.blur()" align="right" type="text"> &nbsp <input name="valueD1"  class="form-control-sm" size="10" maxlength="15" value="'.$tot.'" type="text" onfocus="this.blur()"></label>
				<label class="col-md-3 col-form-label"></label>				
				</div>';
                     /*                       
                print 'div class="row"><tr valign="top">
				<td class="Data" align="right" width="20%">
                                                                        <input name="nameD1" class="btn btn-sm btn-danger" value="JUMLAH PERBELANJAAN" size="20" onfocus="this.blur()" align="right" type="text"> : </td>
				<td class="Data">
					<input name="valueD1"  class="Data" size="10" maxlength="15" value="'.$tot.'" type="text" onfocus="this.blur()"> &nbsp;<u>
				</td>
				<td class="Data" align="right">&nbsp; </td>
				<td class="Data">&nbsp;</td>
				</tr></div>'; */
}

print '
<div class="card-header mt-3 mb-3">SILA MUAT NAIK MAKLUMAT YANG BERKAITAN &nbsp;&nbsp;</div>';
print '<div class="row">
                                <label class="col-md-1 col-form-label"></label>
                                <div class="col-md-8 col-form-label"><input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik Slip Gaji"  onclick= "Javascript:(window.location.href=\'?vw=uploadwingaji&mn=3&userID='.$pk.'\')" /> &nbsp; ';
                                    if ($pic) {
                                        print '
                                            <button type=button class="btn btn-outline-danger" onClick=window.open(\'upload_gaji/'.$pic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");> <i class="far fa-file-pdf text-danger"></i> Paparan Slip Gaji </button>&nbsp;';
                                        }
                                print '</div><label class="col-md-3 col-form-label"></label></div>';
                                
                       print '<div class="row">
                                <label class="col-md-1 col-form-label"></label>
                                <div class="col-md-8 col-form-label"><input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik IC"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinic&mn=3&userID='.$pk.'\')"> &nbsp; ';
                                    if ($picic) {
                                        print '
                                            <button type=button class="btn btn-outline-danger" onClick=window.open(\'upload_ic/'.$picic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");> <i class="far fa-file-pdf text-danger"></i> Paparan IC </button>&nbsp;';
                                        }
                                print '</div><label class="col-md-3 col-form-label"></label></div>';
                                
                       print '<div class="row">
                                <label class="col-md-1 col-form-label"></label>
                                <div class="col-md-8 col-form-label"><input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik Jawatan Tetap"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinjwtn&mn=3&userID='.$pk.'\')"> &nbsp; ';
                                    if ($picjwtn) {
                                        print '
                                            <button type=button class="btn btn-outline-danger" onClick=window.open(\'upload_jwtn/'.$picjwtn.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");> <i class="far fa-file-pdf text-danger"></i> Paparan Pengesahan Jawatan </button>&nbsp;';
                                        }
                                print '</div><label class="col-md-3 col-form-label"></label></div>';
                                
                       print '<div class="row">
                                <label class="col-md-1 col-form-label"></label>
                                <div class="col-md-8 col-form-label"><input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik CCRIS"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinccris&mn=3&userID='.$pk.'\')"> &nbsp; ';
                                    if ($picccris) {
                                        print '
                                            <button type=button class="btn btn-outline-danger" onClick=window.open(\'upload_CCRIS/'.$picccris.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");> <i class="far fa-file-pdf text-danger"></i> Paparan CCRIS </button>&nbsp;';
                                        }
                                print '</div><label class="col-md-3 col-form-label"></label></div>';
                                
                         
/*

print '
<tr valign="top">
<td class="Data" align="right">
<br>
<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Upload Slip Gaji"  onclick= "Javascript:(window.location.href=\'?vw=uploadwingaji&userID='.$pk.'\')">&nbsp;:&nbsp;</br>
<br>
<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Upload IC"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinic&userID='.$pk.'\')">&nbsp;:&nbsp;
</br>
<br>
<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Upload Jawatan Tetap"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinjwtn&userID='.$pk.'\')">&nbsp;:&nbsp;
</br>
<br>
<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Upload CCRIS"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinccris&userID='.$pk.'\')">&nbsp;:&nbsp; </td>
</br>

<td class="Data" align="left">';
if ($pic) {
print '
<br>
<input type=button value="PAPARAN SLIP GAJI" class="but" onClick=window.open(\'upload_gaji/'.$pic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");> </br>';
}
if ($picic) {
print '
<br><input type=button value="PAPARAN IC" class="but" onClick=window.open(\'upload_ic/'.$picic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");></a></br>';
}
if ($picjwtn) {
print '
<br><input type=button value="PAPARAN PENGESAHAN JAWATAN" class="but" onClick=window.open(\'upload_jwtn/'.$picjwtn.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");></br>';
}

if ($picccris) {
print '
<br><input type=button value="PAPARAN CCRIS" class="but" onClick=window.open(\'upload_CCRIS/'.$picccris.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");></br>';
}
print'
	  </td> 	<td class="Data" align="centre" width="2"> </td>			
				<td class="Data">&nbsp;</td></tr>

'; */

if($addB){		
print 	'<tr valign="top">
		<td class="Data" align="right" width="20%">
			<input name="idq2" type="hidden" value="'.$idq2.'">
			<input name="codeq2" type="hidden" value="'.$codeq2.'"> 
			<input name="nameq2" type="text" class="Data" value="'.$nameq2.'" size="20" align="right" onfocus="this.blur()">: </td>
		<td class="Data">
			<input name="valueq2" size="10" maxlength="15" type="text" value="'.$valueq2.'"> &nbsp;
			<input type="button" class="label" value="..." onclick="window.open(\'selBiayaQ.php?no='.$i.'&userID='.$pk.'\',\'sel\',\'top=10,left=10,width=550,height=300,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">
		</td>
		<td class="Data" align="right">&nbsp; </td>
		<td class="Data">&nbsp;</td>
		</tr>';
}

print '	<div align="center" class="mt-3">
			<input type="hidden" name="pk" value="'.$loanID.'">&nbsp;';
			if(!$user) print '<input type=button name=ResetForm class="btn btn-md waves-light waves-effect btn-secondary" value=<< onClick="window.location.href =\'?vw=biaya&pk='.$loanID.'\'">&nbsp;'; //back button for admin.
print '</center>
</table></form>';
include("footer.php");	
?>