<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberEdit.php
*          Date 		: 	10/10/2003
*********************************************************************************/
include("header.php");		
include("koperasiQry.php");	
include ("forms.php");

date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.location="index.php";</script>';
}

$sFileName		= "?vw=memberEdit&mn=905";
$sActionFileName= "?vw=memberEdit&pk=".$pk."&mn=905";
$title     		= "Kemaskini Maklumat Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

/* //--- Prepare race type
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
}	 */

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

$deptList = Array();
$deptVal  = Array();
$GetDept = ctGeneral("","B");
if ($GetDept->RowCount() <> 0){
	while (!$GetDept->EOF) {
		array_push ($deptList, $GetDept->fields(name));
//		array_push ($deptList, $GetDept->fields(code));
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


/* //--- Prepare society
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
 */
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
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "40";
$FormLength[$a]  	= "70";

$a++;
$FormLabel[$a]   	= "No./ID Koperasi";
$FormElement[$a] 	= "kopNum";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";

$a++;
$FormLabel[$a]   	= "Singkatan Koperasi";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Tarikh SST Diterima";
$FormElement[$a] 	= "approvedDate";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Emel Koperasi <br>(Pastikan Sah)";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "text";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
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
$FormLabel[$a]   	= "Telefon Bimbit<br>Cth: 6011XXXXXXXX";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";	

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
$FormLabel[$a]   	= "Negeri Kediaman";
$FormElement[$a] 	= "stateID";
$FormType[$a]	  	= "select";
$FormData[$a]   	= $stateList;
$FormDataValue[$a]	= $stateVal;
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";


$a++;
$FormLabel[$a]   	= "Nombor Pekerja<br>(Sekiranya Ada)";
$FormElement[$a] 	= "staftNo";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";	

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
$FormLabel[$a]   	= "Jenis Koperasi";
$FormElement[$a] 	= "jenis";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Kredit','Bukan Kredit');
$FormDataValue[$a]	= array('0','1');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Gaji";
$FormElement[$a] 	= "grossPay";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "BlackList Dividen";
$FormElement[$a] 	= "BlackListDIV";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Status Hutang Lapuk";
$FormElement[$a] 	= "statusHL";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Status Senarai Hitam";
$FormElement[$a] 	= "BlackListID";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Ya','Tidak');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

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
$FormLabel[$a]   	= "Bayaran Pendaftaran (RM)";
$FormElement[$a] 	= "totPay";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "* Yuran Bulanan (RM)";
$FormElement[$a] 	= "monthFee";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "* Deposit Khas (RM)";
$FormElement[$a] 	= "monthDepo";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "* Syer Bulanan (RM)";
$FormElement[$a] 	= "unitShare";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "No. Akaun Bank<br>(XXXXXXXXXXXXXXXX)";
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
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '".$pk."' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);
//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->

	
	$SQLID = "SELECT * FROM userdetails WHERE userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');

if ($SubmitForm <> "") {

	$sqlLoan = "SELECT * FROM loans WHERE userID = '".$pk."' AND status = 3 "; 	
	$GetLoans =  &$conn->Execute($sqlLoan);
    $kira = $GetLoans->RowCount();
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
		$sSQL = "";
		$sWhere = "";				
	    $sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";		
       	$sSQL	= "UPDATE users SET "."name=".tosql($name, "Text").",email=" . tosql($email, "Text").
				  ",updatedDate=" . tosql($updatedDate, "Text") .
		          ",updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL = $sSQL . $sWhere;

		$rs = &$conn->Execute($sSQL);

		if ($address <> "") $address = '<pre>'.$address.'</pre>';
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
		          " approvedDate=" . tosql($approvedDate, "Text").
				  ", mobileNo=" . tosql($mobileNo, "Text").
				  ", dateBirth=" . tosql($dateBirth, "Text").
				  ", address=" . tosql($address, "Text").
				  ", stateID=" . tosql($stateID, "Number"). 
		          ", staftNo=" . tosql($staftNo, "Text").
				  ", departmentID=" . tosql($dept, "Number").
		          /* ", newIC=" . tosql($newIC, "Text"). */
		          ", jenis=" . tosql($jenis, "Number").
		          /* ", raceID=" . tosql($raceID, "Number").
		          ", religionID=" . tosql($religionID, "Number").
		          ", maritalID=" . tosql($maritalID, "Number"). */
				  ", grossPay=" . tosql($grossPay, "Number").
				  ", BlackListDIV=" . tosql($BlackListDIV, "Number").
				  ", statusHL=" . tosql($statusHL, "Number").
				  ", BlackListID=" . tosql($BlackListID, "Number").
				  ", totPay=" . tosql($totPay, "Number").
		          ", monthFee=" . tosql($monthFee, "Number").
		          ", monthDepo=" . tosql($monthDepo, "Number").
		          ", unitShare=" . tosql($unitShare, "Number").
				  ", w_name1=" . tosql($w_name1, "Text").
		          ", w_ic1=" . tosql($w_ic1, "Text").
		          ", w_jawatan1=" . tosql($w_jawatan1, "Text").
		          ", w_contact1=" . tosql($w_contact1, "Text").
		          ", w_address1=" . tosql($w_address1, "Text").
		         /*  ", job=" . tosql($job, "Text"). */
			  	  ", accTabungan=" . tosql($accTabungan, "Text").
			  	  ", bankID=" . tosql($bankID, "Text").
		          /* ", city=" . tosql($city, "Text").
		          ", postcode=" . tosql($postcode, "Text").			 */	  			  
		         /*  ", addressSurat=" . tosql($addressSurat, "Text"). */
		         /*  ", citySurat=" . tosql($citySurat, "Text").
		          ", postcodeSurat=" . tosql($postcodeSurat, "Text").			  			  
		          ", stateIDSurat=" . tosql($stateIDSurat, "Number"). */
			      /* ", saksi1=" . tosql($saksi1, "Text"). */
				  ", updatedDate=" . tosql($updatedDate, "Text") .
		          ", updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);
 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini maklumat peribadi koperasi -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);
	
	$SQLID = "select * FROM userdetails where userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');

	if ($statusHLID == '1') {
		for ($i = 0; $i < $kira; $i++) {

	$sqlLoan = "select * FROM loans where userID = '".$pk."' AND status = 3 "; 	
	$GetLoans =  &$conn->Execute($sqlLoan);
	$GetLoansID = $GetLoans->fields('loanID');
				
	$sSQL =	'';
	$sWhere	= '	loanID	 = ' . $GetLoansID;
	$sSQL	= '	UPDATE loans ' ;
	$sSQL	.= ' SET ' .
		   ' status	=' . tosql(7, "Text").
		   ' ,selesaiBy	=' . tosql($updatedBy, "Text").
		   ' ,selesaiDate='	. tosql($updatedDate, "Text");
	$sSQL .= ' WHERE ' . $sWhere;
	$rsHL	= &$conn->Execute($sSQL);	
	
		}
      } // end loop update
		
	
		print '<script>
					alert ("Maklumat koperasi telah dikemaskinikan ke dalam sistem.");
					window.location.href = "'.$sActionFileName.'";
				</script>';
	}
}			
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->

print '
<form name="MyForm" action='.$sFileName.' method=post>
<div class="mb-3 row">

                    <h5 class="card-title">'.strtoupper($title).'</h5>';


//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT PENDAFTARAN ID</div>';
	if ($i == 7) print '<div class="card-header mb-3">BUTIR-BUTIR PERIBADI</div>';
	if ($i == 19) print '<div class="card-header mb-3">BAYARAN</div>';

		$addr = str_replace("<pre>","",$GetMember->fields('w_address1'));
		$addr1 = str_replace("</pre>","",$addr);

	if ($i == 23) {
		print '<div class="card-header mb-3">PERSON IN CHARGE</div>';
                
                                    print '<div class="row m-1 mt-3">
                                                    <div class="col-md-3">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom032">*Nama Penuh</label>
                                                            <input type="text" class="form-control" name="w_name1" value="'.tohtml($GetMember->fields('w_name1')) .'" size=30 maxlength=50 id="validationCustom032">                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom03">Kad Pengenalan</label>
                                                            <input type="text" class="form-control" name="w_ic1" value="'.tohtml($GetMember->fields('w_ic1')) .'" size=15 maxlength=14 id="validationCustom03" placeholder="Tiada (-)">                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom04">No. Telefon</label>
                                                            <input type="text" class="form-control" name="w_contact1" value="'.tohtml($GetMember->fields('w_contact1')) .'" size=15 maxlength=15 id="validationCustom04" placeholder="(6XXXXXXXXXX)">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom05">Jawatan</label>      
                                                            <input type="text" class="form-control" name="w_jawatan1" value="'.tohtml($GetMember->fields('w_jawatan1')) .'" size=15 maxlength=25 id="validationCustom05">         
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-2">
                                                            <label class="form-label" for="validationCustom06">Alamat Kediaman</label>
                                                            <textarea class="form-control" cols=30 rows=3 wrap="hard" name="w_address1" id="validationCustom06">'.$addr1.'</textarea>
                                                        </div>
                                                    </div>
                                                </div>';                                    
		
		
	/* print	'			</table>
					</td>
			   </tr>
		       <div class="card-header mt-3">PENCADANG (NOMBOR KOPERASI YANG TELAH BERDAFTAR DI DALAM SISTEM)</div>'; */
	}


	if ($i == 23) print '<div class="card-header mt-3">MAKLUMAT BANK</div>'; 

	if ($cnt == 1) print '<div class="m-1 row">';
	print '<label class="col-md-2 col-form-label">'.$FormLabel[$i];
	// if (!($i == 6 OR $i == 26 OR $i == 32 )) print ':';
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

	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);

	if($i==12){
			if(!isset($dept)) $dept = $GetMember->fields('departmentID'); 
			print '<select name="dept"  class="form-selectx">
				<option value="">- Semua -';
			for ($j = 0; $j < count($deptList); $j++) {
				print '	<option value="'.$deptVal[$j].'" ';
				if ($dept == $deptVal[$j]) print ' selected';
				print '>'.$deptList[$j];
			}
			print '</select>&nbsp;';
	}
	
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';
}

if ((get_session("Cookie_groupID") == 2)) {
print '<div class="mb-3 row">
                <center>
                        <input type="hidden" name="pk" value="'.$pk.'">
						<!--input type="button" class="btn btn-secondary btn-md waves-effect waves-light" value="<<"-->
                        <input type=Submit name=SubmitForm class="btn btn-primary btn-md waves-light waves-effects" value=Kemaskini>
                </center>
            </div>';
}

print '</div>
</form>';
include("footer.php");	
?>