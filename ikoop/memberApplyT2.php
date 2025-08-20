<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberApplyT.php
*          Date 		: 	31/03/2004
*********************************************************************************/
include("header.php");	
include("koperasiQry.php");	
include ("forms.php");

date_default_timezone_set("Asia/Jakarta");
if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}

if (get_session("Cookie_groupID") == 0) {
	$userID		= get_session('Cookie_userID');
	$kopNum	= dlookup("userdetails", "kopNum", "userID=" . tosql(get_session('Cookie_userID'), "Text"));
	$userName	= get_session('Cookie_fullName');
	$newIC		= dlookup("userdetails", "newIC", "userID=" . tosql(get_session('Cookie_userID'), "Text"));
	$oldIC		= dlookup("userdetails", "oldIC", "userID=" . tosql(get_session('Cookie_userID'), "Text"));
}

$sFileName		= "?vw=memberApplyT&mn=1";
$sActionFileName= "?vw=memberApplyT&mn=1";
$title     		= "Permohonan Berhenti Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

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

$a = 0;
$FormLabel[$a]   	= "No./ID Koperasi";
$FormElement[$a] 	= "kopNum";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "20";

$a = $a + 1;
$FormLabel[$a]   	= "Nama Koperasi";
$FormElement[$a] 	= "userName";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "45";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "* Jenis";
$FormElement[$a] 	= "type";
$FormType[$a]	  	= "selectx";
$FormData[$a]   	= $terminateList;
$FormDataValue[$a]	= $terminateVal;
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";
//--- End   :Set the listing list (you may insert here any new listing) -------------------------->

//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->
if ($SubmitForm <> "") {
	//--- Begin : Call function FormValidation ---  
	for ($i = 0; $i < count($FormLabel); $i++) {
		for($j=0 ; $j < count($FormCheck[$i]); $j++) {
			FormValidation ($FormLabel[$i], 
							$FormElement[$i], 
							$$FormElement[$i],
							$FormCheck[$i][$j],
							$i);
		}
	}	
	//--- End   : Call function FormValidation ---  
	//--- BEGIN	: Checking member id ---
	if ($kopNum <> "") {
		if (dlookup("userdetails", "userID", "kopNum=" . tosql($kopNum, "Text")) == "") {
			array_push ($strErrMsg, 'kopNum');
			print '- <font class=redText>No Koperasi - '.$kopNum.' tidak wujud...!</font><br>';
			$userName = "";
			/* $newIC = "";
			$oldIC = "";
			$unitOnHand = ""; */
		} else {
			$userID = dlookup("userdetails", "userID", "kopNum=" . tosql($kopNum, "Text"));		
			$userName 	= dlookup("users", "name", "userID=" . tosql($userID, "Text"));		
			/* $newIC 	= dlookup("users", "name", "userID=" . tosql($userID, "Text"));		
			$oldIC 	= dlookup("userdetails", "oldIC", "userID=" . tosql($userID, "Text")); 		
			$unitOnHand = dlookup("userdetails", "totalShare", "userID=" . tosql($userID, "Text"));*/
		}		
	}
	//--- END  	: Checking member id ---
	if (count($strErrMsg) == "0") {
		$applyDate = date("Y-m-d H:i:s");             
		$sSQL = "";
		$sSQL	= "INSERT INTO userterminate (" . 
		          "userID," . 
		          "applyDate," . 
		          "type)" . 
		          " VALUES (" . 
		          tosql($userID, "Text") . "," .
		          tosql($applyDate, "Text") . "," .
		          tosql($type, "Text") . ")";
		$rs = &$conn->Execute($sSQL);
		print '<script>
					alert ("Permohonan Berhenti telah didaftarkan ke dalam sistem.");
					window.location.href="'.$sActionFileName.'";
				</script>';
	}
}			
//--- End   : Form Validation Field / Add / Update ---------------------------------------------->
?>
<h4 class="card-title"><?=strtoupper($title)?></h4>

<form name="MyForm" action=<? print $sFileName;?> method=post>
<input type="hidden" name="userID" value="<? print $userID;?>">
<input type="hidden" name="shareType" value="<? print $shareType;?>">
<input type="hidden" name="unitOnHand" value="<? print $unitOnHand;?>">


<?php
if (get_session("Cookie_groupID") == 0){
	$uid=get_session('Cookie_userID'); 	
	$pk	= dlookup("userterminate", "ID", "userID=" . tosql($uid, "Text"));
}

if($pk) {
?>
    <div class="">
                            <div class="alert alert-success mb-0" role="alert">
                                <h4 class="alert-heading font-size-18">Makluman!</h4>
                                <p>Permohonan Koperasi Telah Dibuat.</p>
                            </div>
                        </div>

<?php
} else {
//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 0; $i < count($FormLabel); $i++) {
	print '<div class="mb-2 row"><label class="col-md-2 col-form-label">'.$FormLabel[$i].'</label>';
	if (in_array($FormElement[$i], $strErrMsg))
	  print '<div class="col-md-8 bg-danger">';
	else
	  print '<div class="col-md-8">';
	//--- Begin : Call function FormEntry ---------------------------------------------------------  
	$strFormValue = $$FormElement[$i];
	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);
			  
	if ($i == 0) {
		if (get_session("Cookie_groupID") == 1 OR get_session("Cookie_groupID") == 2) {
			?>&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-secondary" value="Pilih" onclick="window.open('selMember.php','sel','top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no');"><?
		}
	}
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    ?></div></div><?php
}
}
if(!$pk) {
?>
    <div class="mb-3 mt-3 row">
                                <label class="col-md-2 col-form-label"></label>
                                <div class="col-md-8">
                                    <input type="Submit" name="SubmitForm" class="btn btn-primary w-md waves-effect waves-light" value="Hantar">
                                </div>
                            </div>

<?php }?>


</form>
</div>
<?php
include("footer.php");	
?>
