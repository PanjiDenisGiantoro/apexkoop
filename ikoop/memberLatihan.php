<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberKemaskini.php
*          Date 		: 	10/10/2003
*********************************************************************************/
include("header.php");		
include("koperasiQry.php");	
include ("forms.php");
if (!isset($StartRec))	$StartRec= 1; 

date_default_timezone_set("Asia/Jakarta");
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.location="index.php";</script>';
}

$sFileName		= "?vw=memberLatihan&mn=905";
$sActionFileName= "?vw=memberLatihan&pk=".$pk."&mn=905";
$title     		= "Kemaskini LATIHAN Koperasi";

//--- Begin : Set Form Variables (you may insert here any new fields) ---------------------------->
//--- FormCheck  = CheckBlank, CheckNumeric, CheckDate, CheckEmailAddress
$strErrMsg = Array();

//--- Prepare fasa type
$fasaList = Array();
$fasaVal  = Array();
$GetFasa = ctGeneral("","F");
if ($GetFasa->RowCount() <> 0){
	while (!$GetFasa->EOF) {
		array_push ($fasaList, $GetFasa->fields(name));
		array_push ($fasaVal, $GetFasa->fields(ID));
		$GetFasa->MoveNext();
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
$FormLabel[$a]   	= "Latihan Koperasi";
$FormElement[$a] 	= "training";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1";


$a++;
$FormLabel[$a]   	= "Fasa Latihan";
$FormElement[$a] 	= "fasa";
$FormType[$a]	  	= "";
$FormData[$a]   	= "";
$FormDataValue[$a]	= "";
$FormCheck[$a]   	= array();
$FormSize[$a]    	= "1";
$FormLength[$a]  	= "1"; 


//--- End   :Set the listing list (you may insert here any new listing) -------------------------->
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '".$pk."' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);

// Query to retrieve training data based on userID
$query = "SELECT * FROM training WHERE userID = " . $pk . " ORDER BY ID";
        $result = $conn->Execute($query);
        $ID =$result->fields(ID);
//--- Begin : Form Validation Field / Add / Update ---------------------------------------------->

if (isset($_GET['del'])) {
    $pk = isset($_GET['pk']) ? intval($_GET['pk']) : 0;
    $ID = isset($_GET['del']) ? intval($_GET['del']) : 0;

    print "PK: " . $pk . "<br>";
    print "ID: " . $ID . "<br>";

if (isset($_GET['del']) && isset($_GET['pk'])) {
    $delete = "DELETE FROM training WHERE ID = $ID AND userID = $pk";
    print "Query: " . $delete . "<br>";

    if ($conn->Execute($delete)) {
        header("Location: index.php?vw=memberLatihan&mn=905&pk=$pk");
        exit();
    } else {
        print "Penghapusan gagal.";
    }
}
}
	
	$SQLID = "SELECT * FROM userdetails WHERE userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');

if ($SubmitForm <> "") {
    print "Form submitted";
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
        $trainingDate= date("Y-m-d H:i:s");              
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
                "training=" . tosql($training, "Number") .
                ", fasa=" . tosql($fasa, "Number") .
                ", updatedDate=" . tosql($updatedDate, "Text") .
                ",trainingDate=" . tosql($trainingDate, "Text") .
                ", updatedBy=" . tosql($updatedBy, "Text");
		$sSQL = $sSQL . $sWhere;
		$rs = &$conn->Execute($sSQL);

        if (!$rs) {
            print "Database update error: " . $conn->ErrorMsg(); // Debug output
        } else {
            print "Database update successful";
        }

 		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
					" VALUES ('Mengemaskini latihan koperasi -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);
	
	$SQLID = "select * FROM userdetails where userID = '".$pk."'"; 	
	$GetLoansIDs =  &$conn->Execute($SQLID);
	$statusHLID = $GetLoansIDs->fields('statusHL');


		print '<script>
					alert ("Maklumat koperasi telah dikemaskinikan ke dalam sistem.");
					window.location.href = "'.$sActionFileName.'";
				</script>';
	}
}			

if($simpan <> ""){
    $online_offsite = isset($_POST['online_offsite']) ? $_POST['online_offsite'] : '';
        
    $sSQL = "INSERT INTO training (" . 
            "userID," . 
            "tarikh_latihan," . 
            "person_in_charge," . 
            "modul," . 
            "online_offsite," . 
            "catatan) " . 
            "VALUES (" .
            "'". $pk . "', ".
            "'". $tarikh_latihan . "', ".
            "'". $person_in_charge . "', ".
            "'". $modul . "', ".
            "'". $online_offsite . "', ".
            "'". $catatan . "')";

            $rs = &$conn->Execute($sSQL);
            print '<script>
                    alert ("Maklumat koperasi telah dikemaskinikan ke dalam sistem.");
                    window.location.href = "'.$sActionFileName.'";
                    </script>';
    

}

//--- End   : Form Validation Field / Add / Update ---------------------------------------------->

print '
<form name="MyForm" action="" method=post enctype="multipart/form-data">
<div class="mb-3 row">

                    <h5 class="card-title">'.strtoupper($title).'</h5>';


//--- Begin : Looping to display label -------------------------------------------------------------
for ($i = 1; $i <= count($FormLabel); $i++) {
 	$cnt = $i % 2;
	if ($i == 1) print '<div class="card-header mb-3">MAKLUMAT LATIHAN KOPERASI</div>';
	if ($i == 7) print '<div class="card-header mb-3">BUTIR-BUTIR LATIHAN KOPERASI</div>';
    if ($i == 9) print '<div class="card-header mb-3">KEMASKINI LATIHAN</div>';


	// Check if the current element is the file upload element
    if ($FormType[$i] === "file") {
        // Display the file upload input field
        print '<input type="file" name="' . $FormElement[$i] . '" ID="' . $FormElement[$i] . '">';
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

        //display status
        $colorStatus = "Data";
        if ($status == 0) $colorStatus = "text-success";
        if ($status == 1) $colorStatus = "text-primary";
        if ($status == 3) $colorStatus = "text-danger";
        $status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields('userID'), "Text"));
        if ($i == 6) {
            print '<td class="Data" align="center">&nbsp;<font class="' . $colorStatus . '">' . $statusList[$status] . '</font></td>';
        }

        //training dropdown
        if ($i == 7) {
            if (!isset($training)) $training = $GetMember->fields('training');
            print '<select class="form-selectx" name="training"';

            // Check the value of $training and disable the dropdown if it's not 0
            if ($training != 0) {
                print ' disabled';
            }

            print '>';

            for ($j = 0; $j < count($trainingList); $j++) {
                print '    <option value="' . $trainingVal[$j] . '" ';
                if ($training == $trainingVal[$j]) print ' selected';
                print '>' . $trainingList[$j];
            }
            print '        </select>&nbsp;';

            // JavaScript to enable the dropdown before form submission
            print '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var trainingDropdown = document.getElementsByName("training")[0];
                    var form = document.forms["MyForm"];
                    
                    // Check if the form is submitted
                    form.addEventListener("submit", function(event) {
                        // Enable the dropdown if the value is not 0
                        if (trainingDropdown.value !== "0") {
                            trainingDropdown.removeAttribute("disabled");
                        }
                    });
                });
                </script>';

            // Show $fasa dropdown if $training is 1
            if ($training == 1) {
                $a++;
                $FormLabel[$a]     = "";
                $FormElement[$a]   = "fasa";
                $FormType[$a]      = "";
                $FormData[$a]      = "";
                $FormDataValue[$a] = "";
                $FormCheck[$a]     = array();
                $FormSize[$a]      = "1";
                $FormLength[$a]    = "1";
            }

        }
         //fasa dropdown
        // if($i==8){
        //     if(!isset($fasa)) $training = $GetMember->fields('fasa'); 
        //     print '<select class="form-selectx" name="fasa">';
        //     for ($j = 0; $j < count($fasaList); $j++) {
        //         print '	<option value="'.$fasaVal[$j].'" ';
        //         if ($training == $fasaVal[$j]) print ' selected';
        //         print '>'.$fasaList[$j];
        //     }
        //     print '		</select>&nbsp;';
        //     } 

            if($i==8){
                if(!isset($fasa)) $fasa = $GetMember->fields('fasa'); 
                print '<select class="form-selectx" name="fasa">';
                for ($j = 0; $j < count($fasaList); $j++) {
                    print '	<option value="'.$fasaVal[$j].'" ';
                    if ($fasa == $fasaVal[$j]) print ' selected';
                    print '>'.$fasaList[$j];
                }
                print '		</select>&nbsp;';

                print '<div class="mb-3 row">
                <center>
                        <input type=Submit name=SubmitForm class="btn btn-primary btn-md waves-light waves-effects" value="Kemaskini" style="margin-left: -880px; margin-top: 20px; onclick= "Javascript:(window.location.href=\'?vw=member\')">    
                </center>
            </div>';
            }


	//--- End   : Call function FormEntry ---------------------------------------------------------  
    print '</div>';
	if ($cnt == 0) print '</div>';

       

    }  

        print '
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tarikh Latihan</th>
                <th>Orang Yang Bertugas (PIC)</th>
                <th>Modul</th>
                <th>Online/Onsite</th>
                <th>Catatan</th>
            </tr>
            </thead>
            <tbody>';

        $j = 0; 
        print '
        <tr>
        <td><input type="date" name="tarikh_latihan" value="' . $trainingData[$j]['tarikh_latihan'] . '" class="form-control"></td>
        <td><input type="text" name="person_in_charge" value="' . $trainingData[$j]['person_in_charge'] . '" class="form-control"></td>
        <td><input type="text" name="modul" value="' . $trainingData[$j]['modul'] . '" class="form-control"></td>
        <td>
            <select name="online_offsite" class="form-select">
                <option value="choose"' . ($trainingData[$j]['online_offsite'] == 'choose' ? ' selected' : '') . '>Choose</option>
                <option value="1"' . ($trainingData[$j]['online_offsite'] == '1' ? ' selected' : '') . '>Online</option>
                <option value="0"' . ($trainingData[$j]['online_offsite'] == '0' ? ' selected' : '') . '>Onsite</option>
            </select>
        </td>
        <td><textarea name="catatan" class="form-control" rows="4" style="width: 100%;">' . $trainingData[$j]['catatan'] . '</textarea></td>
        <td><input type="submit" value="Simpan" name="simpan" class="btn btn-primary"></td>
        </tr>
        </tbody>
        </table>';
       
        print '<div class="card-header mb-3">SENARAI LATIHAN</div>';
        
print '
<table class="table table-sm table-striped">
    <tr class="table-primary">
        <td align="center"><b>Bil</b></td>
        <td align="center"><b>Tarikh Latihan</b></td>
        <td align="center"><b>Orang Yang Bertugas (PIC)</b></td>
        <td align="center"><b>Modul</b></td>
        <td align="center"><b>Online/Onsite</b></td>
        <td align="center"><b>Catatan</b></td>
        <td align="center"><b>Tindakan</b></td> <!-- Kolom tindakan -->
    </tr>';
    
    $bil = 1; // Counter for row numbering
    if ($result->RowCount() > 0) {
    while (!$result->EOF) {
        print '
        <tr>
            <td align="center">' . $bil . '</td>
            <td align="center">' . toDate('d/m/Y', $result->fields['tarikh_latihan']) . '</td>
            <td align="center">' . htmlspecialchars($result->fields['person_in_charge']) . '</td>
            <td align="center">' . htmlspecialchars($result->fields['modul']) . '</td>
            <td align="center">';
    
            // Displaying Online or Onsite value
            $onlineOffsiteValue = $result->fields['online_offsite'];
            if ($onlineOffsiteValue == '0') {
                print 'Onsite';
            } elseif ($onlineOffsiteValue == '1') {
                print 'Online';
            } else {
                print 'Unknown';
            }
    
        print '</td>
            <td align="center">' . htmlspecialchars($result->fields['catatan']) . '</td>
            <td align="center">'; // Action column (Delete)
    
        // Delete button with ID and userID
        print '<a href="?vw=memberLatihan&mn=905&pk=' . $result->fields['userID'] . '&del=' . $result->fields['ID'] . '" class="badge bg-danger text-dark" onClick="return confirm(\'Are you sure?\')" title="Delete">
                <i class="fas fa-trash-alt"></i>
              </a>';
    
        print '</td>
        </tr>';
    
        $bil++;
        $result->MoveNext();
        }
    } else { 
        print '
            <tr><td colspan="7" align="center">
                <b>- Tiada rekod -</b>
            </td></tr>';
    }
    

    // Close the table
    print '</table>';
    
// Query to retrieve training data based on userID
$query = "SELECT * FROM training WHERE userID = " . $pk ." ORDER BY ID";
$result = $conn->Execute($query);
$ID = $result->fields['ID'];  // Ensure the field ID is called correctly

if ((get_session("Cookie_groupID") == 2)) {
print '<div class="mb-3 row">
                <center>
                        <input type="hidden" name="pk" value="'.$pk.'">
						<!--input type="button" class="btn btn-secondary btn-md waves-effect waves-light" value="<<"--><br>
                        <input type="button" name="Kembali" value="<<"  class="btn btn-md btn-secondary" onclick= "Javascript:(window.location.href=\'?vw=training\')">
                </center>
            </div>';
}

print '</div>
</form>';
include("footer.php");	

print '
<script language="JavaScript">
	var allChecked=false;
	function ITRViewSelectAll() {
	    e = document.MyForm.elements;
	    allChecked = !allChecked;
	    for(c=0; c< e.length; c++) {
	      if(e[c].type=="checkbox" && e[c].name!="all") {
	        e[c].checked = allChecked;
	      }
	    }
	}

	function ITRActionButtonClick(v) {
	      e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
	            count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak dihapuskan.\');
	        } else {
	          if(confirm(count + \' rekod hendak dihapuskan?\')) {
	            e.action.value = v;
	            e.submit();
	          }
	        }
	      }
	    }	   

	function ITRActionButtonStatus() {
		e = document.MyForm;
		if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
					count++;
					pk = e.elements[c].value;
				}
			}
	        
			if(count != 1) {
				alert(\'Sila pilih satu rekod sahaja untuk kemaskini status\');
			} else {
				window.open(\'transStatus.php?pk=\' + pk,\'status\',\'top=50,left=50,width=500,height=250,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');					
			}
		}
	}
		
	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '&yy='.$yy.'&mm='.$mm.'&code='.$code.'&filter='.$filter.'&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

</script>';

?>