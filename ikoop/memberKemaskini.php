<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberKemaskini.php
*          Date 		: 	10/10/2003
*********************************************************************************/
include("header.php");		
include("koperasiQry.php");	
include ("forms.php");

date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.location="index.php";</script>';
}

$sFileName		= "?vw=memberKemaskini&mn=905";
$sActionFileName= "?vw=memberKemaskini&pk=".$pk."&mn=905";
$title     		= "Kemaskini Maklumat Koperasi SST Diterima";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

$a = 1;

$FormLabel[$a]   	= "Nama Penuh Koperasi";
$FormElement[$a] 	= "name";
$FormType[$a]	  	= "hidden";
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
$FormLabel[$a]   	= "ID Koperasi";
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
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Emel Koperasi<br>(Pastikan Sah)";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";

$a++;
$FormLabel[$a]   	= "No. Telefon Koperasi<br>Cth: 603XXXXXXXXX";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

/* $a++;
$FormLabel[$a]   	= "&nbsp;";
$FormElement[$a] 	= "test";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";	 */


$a++;
$FormLabel[$a]   	= "Migrasi Anggota";
$FormElement[$a] 	= "migrasiAnggota";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Diberi','Tidak Diberi');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Migrasi Pembiayaan";
$FormElement[$a] 	= "migrasiPembiayaan";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Diberi','Tidak Diberi');
$FormDataValue[$a]	= array('1','0');
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";

$a++;
$FormLabel[$a]   	= "Migrasi Yuran/Syer";
$FormElement[$a] 	= "migrasiYurSyer";
$FormType[$a]	  	= "radio";
$FormData[$a]   	= array('Diberi','Tidak Diberi');
$FormDataValue[$a]	= array('1','0');
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
    echo "Form submitted";
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
       	$sSQL	= "UPDATE users SET ".
        ",updatedDate=" . tosql($updatedDate, "Text") .
        ",updatedBy=" . tosql($updatedBy, "Text") ;
		$sSQL = $sSQL . $sWhere;

		$rs = &$conn->Execute($sSQL);

		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "userID=" . tosql($pk, "Text");
		$sWhere = " WHERE (" . $sWhere . ")";		
        $sSQL = "UPDATE userdetails SET ".
                "migrasiAnggota=" . tosql($migrasiAnggota, "Number") .
                ", migrasiPembiayaan=" . tosql($migrasiPembiayaan, "Number") .
                ", migrasiYurSyer=" . tosql($migrasiYurSyer, "Number") .
                ", updatedDate=" . tosql($updatedDate, "Text") .
                ", updatedBy=" . tosql($updatedBy, "Text");
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);

        if (!$rs) {
            echo "Database update error: " . $conn->ErrorMsg(); // Debug output
        } else {
            echo "Database update successful";
        }

 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini migrasi data koperasi -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
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
<form name="MyForm" action='.$sFileName.' method=post enctype="multipart/form-data">
<div class="mb-3 row">

                    <h5 class="card-title">'.strtoupper($title).'</h5>';


//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT KOPERASI</div>';
	if ($i == 7) print '<div class="card-header mb-3">BUTIR-BUTIR MIGRASI DATA</div>';

	// Check if the current element is the file upload element
    if ($FormType[$i] === "file") {
        // Display the file upload input field
        echo '<input type="file" name="' . $FormElement[$i] . '" id="' . $FormElement[$i] . '">';
    }

	
	//if ($i == 17) print '<div class="card-header mt-3">C. MAKLUMAT BANK</div>'; 
	

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

if ((get_session("Cookie_groupID") == 2)) {
print '<div class="mb-3 row">
                <center>
                        <input type="hidden" name="pk" value="'.$pk.'">
						<!--input type="button" class="btn btn-secondary btn-md waves-effect waves-light" value="<<"--><br>
                        <input type="button" name="<<" value="<<"  class="btn btn-md btn-secondary" onclick= "Javascript:(window.location.href=\'?vw=member&mn=905\')">
						<input type=Submit name=SubmitForm class="btn btn-primary btn-md waves-light waves-effects" value="Kemaskini" style="margin-right: 10px; onclick= "Javascript:(window.location.href=\'?vw=member&mn=905\')">
					</center>
            </div>';
}

print '</div>
</form>';
include("footer.php");	
?>