<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	fpx_add.php
*          Date 		: 	15/04/2017
*********************************************************************************/
include ("header.php");	
include("koperasiQry.php"); 
date_default_timezone_set("Asia/Kuala_Lumpur");	
include ("forms.php");

if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$sFileName		= "?vw=fpx_add&mn=907";
$sActionFileName= "?vw=fpx_list&mn=907";
$title     		= "Kemasukan FPX";
//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

//--- Prepare item type
// $itemList = Array();
// $itemVal  = Array();
// $Getitem = ctGeneral("","X");
// if ($Getitem->RowCount() <> 0){
// 	while (!$Getitem->EOF) {
// 	array_push ($itemList, $Getitem->fields(name));
// 	array_push ($itemVal, $Getitem->fields(ID));
// 	$Getitem->MoveNext();
// 	}
// }	
$a = 0;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "dump";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a = 1;
$FormLabel[$a]   	= "* No./ID Koperasi";
$FormElement[$a] 	= "no_koperasi";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a = $a + 1;
$FormLabel[$a]   	= "Nama Koperasi";
$FormElement[$a] 	= "userName";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "50";
$FormLength[$a]  	= "50";

/* $a = $a + 1;
$FormLabel[$a]   	= "No. Rujukan";
$FormElement[$a] 	= "name_type";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50"; */

/* $a = $a + 1;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "loanid";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10"; */

/* $a = $a + 1;
$FormLabel[$a]   	= "Jumlah Pembiayaan";
$FormElement[$a] 	= "amt";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "* No. Sijil";
$FormElement[$a] 	= "no_sijil";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "* Komoditi";
$FormElement[$a] 	= "item";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1"; */

$a++;
$FormLabel[$a]   	= "* Tarikh Mohon FPX";
$FormElement[$a] 	= "tarikh_fpx";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";


/* $a++;
$FormLabel[$a]   	= "* Masa Pembelian Sijil (hhmmss)";
$FormElement[$a] 	= "masa_beli";
$FormType[$a]	  	= "textx";
$FormData[$a]   	= "";
$FormData[$a]   	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "10";	 */

//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
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
	//DateFormat:
	$tarikh_fpx = substr($tarikh_fpx,6,4).'-'.substr($tarikh_fpx,3,2).'-'.substr($tarikh_fpx,0,2);


	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s");	
	
	$sSQL	= "INSERT INTO fpx (					
					" ."userID,
					" ."tarikh_fpx,
					" ."dokumen_fpx
				)"." VALUES (				
					".tosql($no_koperasi, "Text").",			
					".tosql($tarikh_fpx, "Text") . ", 
					".tosql($picture, "Text"). ")";
			$rs = &$conn->Execute($sSQL);

            $sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
			" VALUES ('Muat Naik FPX koperasi - " . implode(', ', $pk) . "', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
			$rs = &$conn->Execute($sqlAct);

			print '<script>
			alert ("Permohonan FPX telah dimasukkan ke dalam sistem.");
			window.location.href = "'.$sActionFileName.'";
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
<input type="hidden" name="picture" value="<? print $pic;?>">

<?php
if (get_session("Cookie_groupID") == 0){
	$uid=get_session('Cookie_userID'); 	
	$pk	= dlookup("fpx", "fpx_ID", "userID=" . tosql($uid, "Text"));
}
//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 0; $i <= count($FormLabel); $i++) {
	//Print Header Maklumat Pemohon
	if ($i == 0) print '<div class="card-header mt-3 mb-3">Dokumen FPX</div>';
	if ($i == 1) print '<div class="card-header mt-3 mb-3">Maklumat FPX</div>'; 	
	print '<div class="mb-2 row"><label class="col-md-2 col-form-label">'.$FormLabel[$i].'</label>';
	if (in_array($FormElement[$i], $strErrMsg))
	  print '<div class="col-md-8 bg-danger">';
	else
	  print '<div class="col-md-8">';
	//--- Begin : Call function FormEntry ---------------------------------------------------------  
	
	
	// if ($FormElement[$i]=="tarikh_fpx")
	// {
	// $strFormValue = date("Y/m/d");
	// }else{
	// $strFormValue = $$FormElement[$i];
	// }
	FormEntry($FormLabel[$i], 
			  $FormElement[$i], 
			  $FormType[$i],
			  $strFormValue,
			  $FormData[$i],
			  $FormDataValue[$i],
			  $FormSize[$i],
			  $FormLength[$i]);
	if ($i == 1) {
		if (get_session("Cookie_groupID") == '2') {
			?>&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-secondary" value="Pilih" onclick="window.open('selFpx.php','sel','top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no');">
                        <?php
		}
	}
	
	/* if($i==7){
	print '<select name="item" class="form-selectx">
	<option value="">- Pilihan FPX -';
	for ($j = 0; $j < count($itemList); $j++) {
		print '	<option value="'.$itemVal[$j].'" ';
	if ($item == $itemVal[$j]) print ' selected';
		print '>'.$itemList[$j];
	}
	print '</select>';
	} */

$Gambar= "upload_fpx/".$pic;
	if ($i == 0) {
	print'<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik FPX" onclick= "Javascript:(window.location.href=\'?vw=uploadFpx&mn=907&userID='.$pk.'\')">';

	if ($pic) {
	print'&nbsp;<input type=button value="Paparan Dokumen" class="btn btn-outline-secondary" onClick=window.open(\'upload_fpx/'.$pic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");>
	';
	}
	}
//--- End   : Call function FormEntry ---------------------------------------------------------  
print '</div></div>';
}
print '<div class="mb-3 mt-3 row">
<label class="col-md-2 col-form-label"></label>
<div class="col-md-8">
	<input type="Submit" name="SubmitForm" class="btn btn-primary w-md waves-effect waves-light" value="Hantar">
</div>
</div>'; 

print '</form>';
include("footer.php");	
?>