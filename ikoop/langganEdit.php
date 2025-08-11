<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	langganEdit.php
*          Date 		: 	29/11/2023
*********************************************************************************/
include("header.php");		
include("koperasiQry.php");	
include ("forms.php");

date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.location="index.php";</script>';
}

$sFileName		= "?vw=langganEdit&mn=905";
$sActionFileName= "?vw=langganEdit&pk=".$pk."&mn=905";
$title     		= "Kemaskini Langganan Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

//--- Prepare pakej type
$pakejList = Array();
$pakejVal  = Array();

$GetPakej = ctGeneral("","G");
if ($GetPakej->RowCount() <> 0){
	while (!$GetPakej->EOF) {
		array_push ($pakejList, $GetPakej->fields(name));
		array_push ($pakejVal, $GetPakej->fields(ID));
		$GetPakej->MoveNext();
	}
}


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
$FormLabel[$a]   	= "Singkatan Koperasi";
$FormElement[$a] 	= "loginID";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "15";
$FormLength[$a]  	= "10";

$a++;
$FormLabel[$a]   	= "Emel Koperasi";
$FormElement[$a] 	= "email";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "30";
$FormLength[$a]  	= "50";


$a++;
$FormLabel[$a]   	= "No. Telefon Koperasi";
$FormElement[$a] 	= "mobileNo";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "15";

$a++;
$FormLabel[$a]   	= "Status";
$FormElement[$a] 	= "status";
$FormType[$a]	  	= "";
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
$FormLength[$a]  	= "1";	 */

$a++;
$FormLabel[$a]    = "Pakej Koperasi";
$FormElement[$a]  = "pakej";
$FormType[$a]      = "";
$FormData[$a]      = "";
$FormDataValue[$a] = "";
$FormCheck[$a]     = array();
$FormSize[$a]      = "1";
$FormLength[$a]    = "1";

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
$FormLabel[$a]   	= "Tarikh Langganan";
$FormElement[$a] 	= "langgananDate";
$FormType[$a]	  	= "date";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array(CheckBlank);
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "10";	

$a++;
$FormLabel[$a]   	= "Tempoh Langganan";
$FormElement[$a] 	= "tempohDate";
$FormType[$a]	  	= "hidden";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "20";
$FormLength[$a]  	= "20";	


//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '".$pk."' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);

//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->

	$SQLID = "SELECT * FROM userdetails WHERE userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['Submit1Year'])) {
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

		if (count($strErrMsg) == "0") {
			$updatedBy 	= get_session("Cookie_userName");
			$updatedDate = date("Y-m-d H:i:s");

			// Tambah setahun kepada tempoh langganan daripada tarikh langgan
			$langgananDate = substr($langgananDate,6,4).'-'.substr($langgananDate,3,2).'-'.substr($langgananDate,0,2);
			$tempohDate = strtotime($langgananDate . " +1 year");
			$tempohDate = date("Y-m-d", $tempohDate);

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
					"updatedDate=" . tosql($updatedDate, "Text") .
					",langgananDate=" . tosql($langgananDate, "Text") .
					",tempohDate=" . tosql($tempohDate, "Text") .
					", updatedBy=" . tosql($updatedBy, "Text");
			$sSQL = $sSQL . $sWhere;
			$rs = &$conn->Execute($sSQL);

			$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
						" VALUES ('Mengemaskini langganan koperasi -$loginID (1 Tahun)', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
			$rs = &$conn->Execute($sqlAct);
		
		$SQLID = "select * FROM userdetails where userID = '".$pk."'"; 	
		$GetLoansIDs =  &$conn->Execute($SQLID);
		$statusHLID = $GetLoansIDs->fields('statusHL');
			
			print '<script>
						alert ("Langganan koperasi telah dikemaskinikan ke dalam sistem.");
						window.location.href = "'.$sActionFileName.'";
					</script>';
		}
	}			

	else if (isset($_POST['Submit2Year'])) {
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

	if (count($strErrMsg) == "0") {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");

        // Tambah dua tahun kepada tempoh langganan daripada tarikh langgan
        $langgananDate = substr($langgananDate,6,4).'-'.substr($langgananDate,3,2).'-'.substr($langgananDate,0,2);
        $tempohDate = strtotime($langgananDate . " +2 year");
        $tempohDate = date("Y-m-d", $tempohDate);
        
                
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
                "updatedDate=" . tosql($updatedDate, "Text") .
                ",langgananDate=" . tosql($langgananDate, "Text") .
                ",tempohDate=" . tosql($tempohDate, "Text") .
                ", updatedBy=" . tosql($updatedBy, "Text");
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);

 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini langganan koperasi -$loginID', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);
	
	$SQLID = "select * FROM userdetails where userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');
        
		print '<script>
					alert ("Langganan koperasi telah dikemaskinikan ke dalam sistem.");
					window.location.href = "'.$sActionFileName.'";
				</script>';
	}
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
	if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT LANGGANAN KOPERASI</div>';
	if ($i == 9) print '<div class="card-header mb-3">BUTIR-BUTIR LANGGANAN KOPERASI</div>';

	// Check if the current element is the file upload element
    if ($FormType[$i] === "file") {
        // Display the file upload input field
        echo '<input type="file" name="' . $FormElement[$i] . '" id="' . $FormElement[$i] . '">';
    }
	
	if ($cnt == 1) print '<div class="m-1 row">';
	print '<label class="col-md-2 col-form-label">'.$FormLabel[$i];
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

        //display status
        $colorStatus = "Data";
        if ($status == 0) $colorStatus = "text-success";
        if ($status == 1) $colorStatus = "text-primary";
        if ($status == 3) $colorStatus = "text-danger";
        $status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields('userID'), "Text"));
        $pakej = dlookup("general", "name", "ID=" . tosql($GetMember->fields('pakej'), "Number"));

        //check ada email ke tak
        if ($i == 4) {
            $email = $GetMember->fields('email');
            if ($email === null) {
                echo '<td class="Data" align="center"><strong>-</strong></td>';
            } else {
                // Display nothing
            }
        }
        //check no fon koperasi ada ke tak
        if ($i == 5) {
            $mobileNo = $GetMember->fields('mobileNo');
            if ($mobileNo === null) {
                echo '<td class="Data" align="center"><strong>-</strong></td>';
            } else {
                // Display nothing
            }
        }                

        if ($i == 6) {
            echo '<td class="Data" align="center">&nbsp;<font class="' . $colorStatus . '">' . $statusList[$status] . '</font></td>';
        }
        if ($i == 7) {
            $pakej = $GetMember->fields('pakej');
            if ($pakej === null) {
                echo '<td class="Data" align="center"><strong>&nbsp;-</strong></td>';
            } else {
                echo '<td class="Data" align="center"><strong>' . dlookup("general", "name", "ID=" . tosql($GetMember->fields('pakej'), "Number")) . '</strong></td>';
            }
        }
		
        if ($i == 10) {
            $tempohDate = $GetMember->fields('tempohDate');
			$langgananDate = $GetMember->fields('langgananDate');
			$tempohYear = date('Y', strtotime($tempohDate));
			$langgananYear = date('Y', strtotime($langgananDate));
			$yearDifference = abs($tempohYear - $langgananYear);

            if ($tempohDate === null) {
                print '<td class="Data" align="center"><strong>&nbsp;Tiada Langganan</strong></td>';
            } 
			else {
				if($yearDifference === 1){
					print '<td class="Data" align="center"><br><strong>&nbsp;&nbsp;&nbsp;(1 Tahun)</strong></td>';
				}
				else if ($yearDifference === 2){
					print '<td class="Data" align="center"><br><strong>&nbsp;&nbsp;&nbsp;(2 Tahun)</strong></td>';
				}
			}
        }  
	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';
}

if ((get_session("Cookie_groupID") == '2')) {
print '<div class="mb-3 row">
                <center><br><br>
                        <input type="hidden" name="pk" value="'.$pk.'">
                        <input type="button" name="Kembali" value="<<"  class="btn btn-md btn-secondary" onclick= "Javascript:(window.location.href=\'?vw=memberLanggan\')">&nbsp;&nbsp;&nbsp;
                        <input type=Submit name=Submit1Year class="btn btn-primary btn-md waves-light waves-effects" value="1 Tahun" style="margin-right: 10px; onclick= "Javascript:(window.location.href=\'?vw=langganEdit\')">
                        <input type=Submit name=Submit2Year class="btn btn-info btn-md waves-light waves-effects" value="2 Tahun" style="margin-right: 10px; onclick= "Javascript:(window.location.href=\'?vw=langganEdit\')">    
                </center>
            </div>';
}

print '</div>
</form>';
include("footer.php");	
?>