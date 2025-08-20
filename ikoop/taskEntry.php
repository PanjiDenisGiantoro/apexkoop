<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	taskEntry.php
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

$sFileName		= "?vw=taskEntry&mn=920";
$sActionFileName= "?vw=taskEntry&mn=920";
$title     		= "Kemasukan Tugasan";

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
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "dump";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a = 1;
$FormLabel[$a]   	= "No./ID Koperasi";
$FormElement[$a] 	= "kopNum";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "10";
$FormLength[$a]  	= "20";

// Increment for each new field
$a = $a + 1;
// Nama Koperasi (already given in your example)
$FormLabel[$a]   	= "Nama Koperasi";
$FormElement[$a] 	= "userName";
$FormType[$a]	  	= "hiddentext";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "45";
$FormLength[$a]  	= "15";

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
		$startDate = date("Y-m-d H:i:s");             
		$sSQL = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Initialize variables for capturing form data
			$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
		}
			// SQL Insert
			$sSQL = "INSERT INTO task (" . 
			"userID," . 
			"doc_tugasan," . 
			"startDate," . 
			"title_problem," . 
			"level_priority," . 
			"structure," . 
			"person_in_charge," . 
			"estimatedDate," . 
			"keterangan) VALUES (" . 
			tosql($userID, "Text") . "," . 
			tosql($picture, "Text") . "," . 
			tosql($startDate, "Text") . "," . 
			tosql($title_problem, "Text") . "," . 
			tosql($level_priority, "Text") . "," . 
			tosql($structure, "Text") . "," . 
			tosql($person_in_charge, "Text") . "," . 
			tosql($estimatedDate, "Text") . "," . 
			tosql($keterangan, "Text") . ")";

			// Execute SQL
			$rs = $conn->Execute($sSQL);
			if ($rs) {
				echo "Data inserted successfully.";
				print '<script>
						alert("Tugasan sudah masuk ke dalam sistem.");
						window.location.href="?vw=taskList&mn=920"; 
					</script>';
			} else {
				echo "Error inserting data: " . $conn->ErrorMsg();
			}
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

    <div class="box">
    <?php
    for ($i = 0; $i < count($FormLabel); $i++) {
        FormEntry($FormLabel[$i],
                  $FormElement[$i], 
                  $$FormElement[$i], 
                  $FormType[$i], 
                  $FormData[$i], 
                  $FormDataValue[$i], 
                  $FormCheck[$i], 
                  $FormSize[$i],
                  $FormLength[$i]);
    }
    ?>
<?php
if (get_session("Cookie_groupID") == '0'){
	$uid=get_session('Cookie_userID'); 	
	$pk	= dlookup("task", "ID", "userID=" . tosql($uid, "Text"));
}
?>
<?php
//} else {
//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 0; $i < count($FormLabel); $i++) {
	if ($i == 0) print '<div class="card-header mt-3 mb-3">DOKUMEN KOPERASI</div>';
	if ($i == 1) print '<div class="card-header mt-3 mb-3">MAKLUMAT TUGASAN</div>';
    print '<div class="mb-4 row field-spacing">'; // Added custom class
    print '<label class="col-md-2 col-form-label">'.$FormLabel[$i].'</label>';
	// print '<div class="mb-2 row"><label class="col-md-2 col-form-label">'.$FormLabel[$i].'</label>';
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
			  
	if ($i == 1) {
		//if (get_session("Cookie_groupID") == '2') {
			?>&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-secondary" value="Pilih" onclick="window.open('selMember.php','sel','top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no');"><?
		//}
	}
	$Gambar = "upload_tugasan/" . $pic;
	if ($i == 0) {
		print '<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik" onclick="Javascript:(window.location.href=\'?vw=uploadDokumen&mn=920&userID='.$pk.'\')">';

		if ($pic) {
			print '&nbsp;<input type=button value="Paparan Dokumen" class="btn btn-outline-secondary" onClick=window.open(\'upload_tugasan/'.$pic.'\',"pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");>';
		}
		
	}
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    ?></div></div><?php
}
?>
<?php
if ($i == 1) {
    print '<div class="card-header mt-3 mb-3">MAKLUMAT TUGASAN</div>';
} 
?>
<div class="container mt-4">
  <!-- Start of the form -->
  <form name="MyForm" action="<?= $sFileName; ?>" method="POST" class="needs-validation" novalidate>

  <div class="form-group row">
    <label for="startDate" class="col-md-3 col-form-label" style="margin-left: -20px;">Tarikh</label>
    <div class="col-md-9">
        <input type="date" id="startDate" name="startDate" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;" required>
        <div class="invalid-feedback">Sila pilih tarikh.</div>
    </div>
</div>
<div class="form-group row">
    <label for="problem" class="col-md-3 col-form-label" style="margin-left: -20px;">Tajuk Masalah</label>
    <div class="col-md-9">
        <input type="text" id="title_problem" name="title_problem" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;" required>
        <div class="invalid-feedback">Sila terangkan masalah koperasi.</div>
    </div>
</div>

<div class="form-group row">
    <label for="priority_level" class="col-md-3 col-form-label" style="margin-left: -20px;">Tahap Keutamaan</label>
    <div class="col-md-9">
        <select id="level_priority" name="level_priority" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;" required>
            <option value="">Tahap Keutamaan</option>
            <option value="low">Rendah</option>
            <option value="medium">Sederhana</option>
            <option value="high">Tinggi</option>
        </select>
        <div class="invalid-feedback">Sila pilih tahap keutamaan.</div>
    </div>
</div>

<div class="form-group row">
    <label for="structure" class="col-md-3 col-form-label" style="margin-left: -20px;">Struktur</label>
    <div class="col-md-9">
        <select id="structure" name="structure" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;" required>
            <option value="">Jenis Struktur</option>
            <option value="minor">Minor</option>
            <option value="major">Major</option>
        </select>
        <div class="invalid-feedback">Sila pilih jenis struktur.</div>
    </div>
</div>

<div class="form-group row">
    <label for="responsible_person" class="col-md-3 col-form-label" style="margin-left: -20px;">Orang Yang Bertugas</label>
    <div class="col-md-9">
        <input type="text" id="person_in_charge" name="person_in_charge" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;" required>
        <div class="invalid-feedback">Sila masukkan nama orang yang bertanggungjawab.</div>
    </div>
</div>

<div class="form-group row">
    <label for="approximate_date" class="col-md-3 col-form-label" style="margin-left: -20px;">Tarikh Anggaran</label>
    <div class="col-md-9">
        <input type="date" id="estimatedDate" name="estimatedDate" class="form-control" style="font-size: 14px; width: 300px; height: 35px; margin-bottom: 20px;">
    </div>
</div>

<div class="form-group row">
    <label for="description" class="col-md-3 col-form-label" style="margin-left: -20px;">Keterangan</label>
    <div class="col-md-9">
        <textarea id="keterangan" name="keterangan" class="form-control" rows="4" style="font-size: 14px; width: 300px; height: 70px; margin-bottom: 20px;" required></textarea>
        <div class="invalid-feedback">Sila berikan penerangan.</div>
    </div>
</div>

  </form>
</div>
	<script>
	// Bootstrap validation
	(function () {
	'use strict';
	window.addEventListener('load', function () {
		// Get the forms
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function (form) {
		form.addEventListener('submit', function (event) {
			if (form.checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
			}
			form.classList.add('was-validated');
		}, false);
		});
	}, false);
	})();
	</script>
	
    <div class="mb-3 mt-3 row">
		<label class="col-md-2 col-form-label"></label>
		<div class="col-md-8">
		<input type="submit" name="SubmitForm" class="btn btn-primary" value="Hantar">
		</div>
		</div>
<?php ?>
</form>
</div>
<?php
include("footer.php");	
?>