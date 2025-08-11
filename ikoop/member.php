<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	member.php
*          Date 		: 	
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="0";
if (!isset($dept))		$dept="";
if (!isset($fasa))		$fasa="";
if (!isset($jenisCode))		$jenisCode="";
if(!isset($picsst)) $picsst = dlookup("userdetails", "borangSST", "userID=" . tosql($pk, "Text"));
$borangSST= "upload_sst/".$borangSST;
date_default_timezone_set("Asia/Kuala_Lumpur");	

include("header.php");	
include("koperasiQry.php"); 

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$sFileName = '?vw=member&mn=905';
$sFileRef  = '?vw=memberEdit&mn=905';
$title     = "Status Permohonan Koperasi";

$IDName = get_session("Cookie_userName");
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	//var_dump($_POST); //debugging
	for ($i = 0; $i < count($pk); $i++) {
		$CheckUser = ctMemberDetail($pk[$i]);
		if ($CheckUser->RowCount() == 1) {
			if ($CheckUser->fields(status) == 0) {
				$sSQL = '';
				$updatedBy 	= get_session("Cookie_userName");
				$updatedDate = date("Y-m-d H:i:s");	
			    $sWhere = "userID=" . tosql($pk[$i], "Text");
				$sSQL = "DELETE FROM users WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);
				$sSQL = '';
				$sSQL = "DELETE FROM userdetails WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);

				$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
				" VALUES ('Hapus senarai permohonan - $pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
				$rs = &$conn->Execute($sqlAct);
			} 
			else {
				print '<script>alert("Pengguna '.$CheckUser->fields(name).' - tidak boleh dihapuskan...!");</script>';
			}
		}
	}
}

//--- End   : deletion based on checked box -------------------------------------------------------

/* $sSQL = "	/* SELECT a.departmentID, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b, users c
			WHERE a.departmentID = b.ID
			AND   a.status = 1 
			GROUP BY a.departmentID"; */

//--- Prepare department & code list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();
$fasaList = Array();
$fasaVal  = Array();

// Query for deptList
$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.jenisCode, a.migrasiAnggota, a.migrasiPembiayaan, a.migrasiYurSyer 	
         FROM userdetails a
         INNER JOIN general b ON a.departmentID = b.ID
         INNER JOIN users c ON a.userID = c.userID
         /* WHERE a.status = 1  */
         GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0){
	while (!$rsDept->EOF) {
		array_push ($deptList, $rsDept->fields(deptName));
		array_push ($deptVal, $rsDept->fields(departmentID));
		$rsDept->MoveNext();
	}
}

// Query for jenisCodeList
$sSQLJenisCode = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.jenisCode, a.migrasiAnggota, a.migrasiPembiayaan, a.migrasiYurSyer 
				FROM userdetails a
				INNER JOIN general b ON a.jenisCode = b.ID
				INNER JOIN users c ON a.userID = c.userID
				/* WHERE a.status = 1  */
				GROUP BY b.ID";

$rsJenisCode = &$conn->Execute($sSQLJenisCode);
if ($rsJenisCode->RowCount() <> 0) {
    while (!$rsJenisCode->EOF) {
        array_push($jenisCodeList, $rsJenisCode->fields(deptName));
        array_push($jenisCodeVal, $rsJenisCode->fields(jenisCode));
        $rsJenisCode->MoveNext();
    }
}

// Query for fasa list
$sSQLFasa = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.fasa
				FROM userdetails a
				INNER JOIN general b ON a.fasa = b.ID
				INNER JOIN users c ON a.userID = c.userID
				/* WHERE a.status = 1  */
				GROUP BY b.ID";

$rsFasa = &$conn->Execute($sSQLFasa);
if ($rsFasa->RowCount() <> 0) {
    while (!$rsFasa->EOF) {
        array_push($fasaList, $rsFasa->fields(deptName));
        array_push($fasaVal, $rsFasa->fields(fasa));
        $rsFasa->MoveNext();
    }
}


	$sSQL = "";
	$sWhere = " a.userID = b.userID ";

	if ($dept <> "") 	{
		$sWhere .= " AND b.departmentID = " . tosql($dept,"Number");
	}

	if ($jenisCode <> "") 	{
		$sWhere .= " AND b.jenisCode = " . tosql($jenisCode,"Number");
	}

	if($filter <> "ALL") $sWhere .= "  AND b.status = " . $filter;
	
	if ($q <> "") 	{
		if ($by == 1) {
			$sWhere .= " AND b.kopNum like '%" .$q ."%'";			
		} else if ($by == 2) {
			$sWhere .= " AND a.name like '%" . $q. "%'";
		} else if ($by == 3) {
			$sWhere .= " AND a.loginID like '%" . $q. "%'";		
		}
	}
	$sWhere = " WHERE (" . $sWhere . ")";
	$sSQL = "SELECT	DISTINCT a.*, b.*
			 FROM 	users a, userdetails b";
	if($filter == '1'){
		$sSQL = $sSQL . $sWhere . " order by CAST( b.kopNum AS SIGNED INTEGER ) asc";
	}
	else{
		$sSQL = $sSQL . $sWhere . ' ORDER BY applyDate DESC';
	}
	$GetMember = &$conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action='.$sFileName.' method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="'.$pk.'">
<input type="hidden" name="filter" value="'.$filter.'">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title">' . strtoupper($title) . '</h5>
    <input type="button" class="btn btn-md btn-primary" value="+ Mohon Baru" onClick="window.location.href=\'?vw=memberApply&mn=905\'"/>
</div>
    
<div class="mb-3 row m-1">
<div>Carian Melalui 
			<select name="by" class="form-select-sm mt-3">'; 
if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Koperasi </option>'; 	else print '<option value="2">Nama Koperasi</option>';
if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';					
print '		</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm mt-3">
 			<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm mt-3" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}
print '		</select>&nbsp;</div>
</div>

<div class="mb-3 row m-1">
<div>
Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			//print '<option value="ALL">Semua';
			for ($i = 0; $i < count($statusList); $i++) {
				if($i == 0 ||$i == 2||$i == 4||$i == 5){
				if ($statusVal[$i] < 6) {
					print '	<option value="'.$statusVal[$i].'" ';
					if ($filter == $statusVal[$i]) print ' selected';
					print '>'.$statusList[$i];
				}
			}
			}
			print' </select>';
			// if(!($filter == 1)){
				print '	</select>&nbsp;
				Kod
				<select name="jenisCode" class="form-select-sm" onchange="document.MyForm.submit();">;
						<option value="">- Semua -';
						for ($i = 0; $i < count($jenisCodeList); $i++) {
								print '	<option value="'.$jenisCodeVal[$i].'" ';
								if ($jenisCode == $jenisCodeVal[$i]) print ' selected';
								print '>'.$jenisCodeList[$i];
							}
	
						print
						'</select>&nbsp;&nbsp;&nbsp;';
			// }
			print'&nbsp;&nbsp;';

if (($IDName == 'superadmin') OR ($IDName == 'admin') OR get_session("Cookie_groupID") == '2') {

if($filter == 0) print      '<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">  '; }

print'     &nbsp;   
			<!--input type="button" class="btn btn-sm btn-danger" value="Status" onClick="ITRActionButtonStatus();"-->';
			if(get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4){
				print'
				<input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');">';
				if($filter == 1){
					print'&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-info" value="Kemaskini" onClick="kemaskini(\'kemaskini\');">';
				}
			}
	print '
	<div class="table-responsive">    
	<!--table border="1" cellspacing="1" cellpadding="3" width="100%" align="center" class="table"-->
		<tr valign="top" class="textFont">
			<td>
				<table width="100%">
					<tr>
						<!-- <td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td> -->					
						<td align="right" class="textFont">Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
						if ($pg == 5)	print '<option value="5" selected>5</option>'; 	 	else print '<option value="5">5</option>';				
						if ($pg == 10)	print '<option value="10" selected>10</option>'; 	else print '<option value="10">10</option>';				
						if ($pg == 20)	print '<option value="20" selected>20</option>'; 	else print '<option value="20">20</option>';				
						if ($pg == 30)	print '<option value="30" selected>30</option>'; 	else print '<option value="30">30</option>';				
						if ($pg == 40)	print '<option value="40" selected>40</option>'; 	else print '<option value="40">40</option>';				
						if ($pg == 50)	print '<option value="50" selected>50</option>';	else print '<option value="50">50</option>';				
						if ($pg == 100)	print '<option value="100" selected>100</option>';	else print '<option value="100">100</option>';				
		print '				</select> setiap mukasurat.<br><br>
						</td>
					</tr>
				</table>
			</td>
		</tr>';	
		if ($GetMember->RowCount() <> 0) {  
			$bil = $StartRec;
			$cnt = 1;

		//Filter SST Diterima
		if($filter == 1){
			print '<tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap>&nbsp;<b>No./Nama Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Tarikh Langganan</b></td>
						<td nowrap align="center">&nbsp;<b>Tarikh Tamat</b></td>
						<td nowrap align="center">&nbsp;<b>Data Migrasi Anggota</b></td>
						<td nowrap align="center">&nbsp;<b>Data Migrasi Pembiayaan</b></td>
						<td nowrap align="center">&nbsp;<b>Data Migrasi Yuran/Syer</b></td>';
			print '</tr>';

			while (!$GetMember->EOF && $cnt <= $pg) {
				$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));
				$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
				$userID = dlookup("userdetails", "userID", "userID=" . tosql($GetMember->fields(userID), "Text"));
				$picsst = dlookup("userdetails", "borangSST", "userID=" . tosql($userID, "Text"));
				
				$colorStatus = "Data";
				if ($status == 0) $colorStatus = "text-success";
				if ($status == 1) $colorStatus = "text-primary";
				if ($status == 2) $colorStatus = "text-warning";
				if ($status == 8) $colorStatus = "text-info";

				$langgananDate = $GetMember->fields(langgananDate);
				if ($langgananDate === null || strtotime($langgananDate) === false) {
					$langgananDate = 'Tiada Tarikh';
				}
				else {
					$langgananDate = date('Y-m-d', strtotime($langgananDate));
				}
	
				$tempohDate = $GetMember->fields(tempohDate);
				if ($tempohDate === null || strtotime($tempohDate) === false) {
					$tempohDate = 'Tiada Tarikh';
				}
				else {
					$tempohDate = date('Y-m-d', strtotime($tempohDate));
				}

				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					// Handle file upload
					if (isset($_FILES['borangSST']) && $_FILES['borangSST']['error'] === UPLOAD_ERR_OK) {
						// Define the directory where you want to store the uploaded files
						$uploadDirectory = 'upload_sst/';
						
						// Generate a unique filename for the uploaded file to prevent overwriting
						$fileName = uniqid() . '_' . $_FILES['borangSST']['name'];
						
						// Move the uploaded file to the destination directory
						if (move_uploaded_file($_FILES['borangSST']['tmp_name'], $uploadDirectory . $fileName)) {
							// File upload was successful
							$pic = $fileName; // Set $pic to the uploaded filename
						}
					}
				}

				print ' <tr>
				<td class="Data" align="right">' . $bil . '&nbsp;</td>
				<td class="Data">';
					if(get_session("Cookie_groupID") == '2'){
						print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
					}
					print'
					<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
					'.$GetMember->fields(kopNum).' - 
					'.strtoupper($GetMember->fields(name)).'</a></td>';
					//button redirect to upload borang sst
					
					// print '<td>';
					// if(get_session("Cookie_groupID") <> 2){
					// 	//nothing
					// }
					// else{
					// 	print'<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik" 
					// 	onclick="Javascript:(window.location.href=\'?vw=uploadBorangSST&mn=3&pk='.$userID.'\')"</td>&nbsp;&nbsp;';
					// }
					// if ($picsst) {
					// 	print '<button type="button" class="btn btn-outline-secondary" onClick="window.open(\'upload_sst/' . $picsst . '\', \'pop\', \'top=70,left=70,width=900,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');"><i class="far fa-file-pdf text-secondary"></i> Paparan Fail</button>&nbsp;';
					// }
					//--------------------------checkbox--------------------------------
					print '
					<td align="center">&nbsp;'.$langgananDate.'</td>
					<td align="center">&nbsp;'.$tempohDate.'</td>
					<td class="Data" align="center"><input type="checkbox" class="form-check-input" name="migrasiAnggota"
						 style="width: 20px; height: 20px;"'; 
					if ($GetMember->fields(migrasiAnggota) == 1) {
						echo ' checked '; 
					}
					echo ' disabled';
					print '></td>

					<td class="Data" align="center"><input type="checkbox" class="form-check-input" name="migrasiPembiayaan"
						value="'.tohtml($GetMember->fields(migrasiPembiayaan)).'" style="width: 20px; height: 20px;"'; 
					if ($GetMember->fields(migrasiPembiayaan) == 1) {
						echo ' checked '; 
					}
					echo ' disabled';
					print '></td>

					<td class="Data" align="center"><input type="checkbox" class="form-check-input" name="migrasiYurSyer"
						value="'.tohtml($GetMember->fields(migrasiYurSyer)).'" style="width: 20px; height: 20px;"'; 
					if ($GetMember->fields(migrasiYurSyer) == 1) {
						echo ' checked '; 
					}
					echo ' disabled';
					print '></td>

					<!-- <td class="Data" align="left">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].'</font></td> -->
					</tr>';
					$cnt++;
					$bil++;
					$GetMember->MoveNext();
				}		
		}
		// Filter bukan SST Diterima
		else if (!($filter == 1)){
			print '
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap>&nbsp;<b>No./Nama Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Singkatan Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Jenis</b></td>
						<td nowrap align="center">&nbsp;<b>Zon</b></td>
						<td nowrap align="center">&nbsp;<b>Kod</b></td>
						<td nowrap align="center">&nbsp;<b>Status</b></td>
						<td nowrap align="center">&nbsp;<b>Tarikh Permohonan</b></td>
						<td	nowrap align="center" colspan="1"></td>	';
					print '
					</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
				$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));
				$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
				$applyDate = $GetMember->fields(applyDate);
				if ($applyDate === null || strtotime($applyDate) === false) {
					$applyDate = 'Tiada Tarikh';
				}
				else {
					// Format the date as desired (e.g., 'd/m/Y H:i:s')
					$applyDate = date('d/m/Y', strtotime($applyDate));
				}
				
				$colorStatus = "Data";
				if ($status == 0) $colorStatus = "text-success";
				if ($status == 1) $colorStatus = "text-primary";
				if ($status == 2) $colorStatus = "text-warning";
				if ($status == 8) $colorStatus = "text-info";

				$rowID = "row-" . $GetMember->fields('userID');
				print ' <tr>
							<td class="Data" align="right">' . $bil . '&nbsp;</td>
							<td class="Data">';
							if(get_session("Cookie_groupID") <> 2){
								//print'<input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'" >';
							}
							else{
								print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
							}
							print'<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
								'.$GetMember->fields(kopNum).' - 
								'.strtoupper($GetMember->fields(name)).'</a>
							<td class="Data" align="center">&nbsp;'.$GetMember->fields(loginID).'</td>
							<!-- <td class="Data" align="center">'.$GetMember->fields(kopNum).'</td> -->
							<td class="Data" align="center">&nbsp;'.$jenisList[$jenis].'</font></td>
							<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td>
							<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('jenisCode'), "Number")).'</td>
							<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].'</font></td>
							<td class="Data" align="center">&nbsp;'.$applyDate.'</td>
						';

					print '
					<td>
					<button type="button" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#' . $rowID . '" aria-expanded="false" onclick="toggleArrow(this)" style="padding-top: 5px; padding-right: 0; padding-bottom: 0; padding-left: 0; font-size: 1.2rem; box-shadow: none; outline: none; display: flex; align-items: center;">
					<i class="fas fa-chevron-down text-secondary"></i>
					</button>
					</td>
			 	</tr>';
			 	
			 	print '

			 	<tr class="collapse" id="' . $rowID . '">
				 <td colspan="10" class="Data">
					 <div class="alert alert-secondary mt-2">
						 <ul>
							 <li>Emel Koperasi  : ' . $GetMember->fields(email) . '</li>
							 <li>Status Progres : ' . $GetMember->fields(statProgress) . '</li>
							 
						 </ul>
					 </div>
				 </td>
				</tr>

			 	';
			 	
			


					$cnt++;
					$bil++;
				$GetMember->MoveNext();
			}
		}

		$GetMember->Close();
		print ' </table>
			</td>
		</tr>		
		<tr>
			<td>';
				if ($TotalRec > $pg) {
					print '
					<table border="0" cellspacing="5" cellpadding="0"  class="textFont" width="100%">';
					if ($TotalRec % $pg == 0) {
						$numPage = $TotalPage;
					} else {
						$numPage = $TotalPage + 1;
					}
					print '<tr><td class="textFont" valign="top" align="left">Rekod Dari : <br>';
					for ($i=1; $i <= $numPage; $i++) {
						print '<A class="text-danger" href="'.$sFileName.'&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&dept='.$dept.'&filter='.$filter.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Rekod : <b>' . $GetMember->RowCount() . '</b></td>
		</tr>';
	} else {
		if ($q == "") {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	}
print ' 
</table></td></tr></table></div>
</form>';

include("footer.php");	

print '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
	
	// Function to update checkbox values to 1 if checked and 0 if unchecked
    function updateCheckedData() {
        var checkboxes = document.MyForm.elements["pk[]"];
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                // Set the value to 1 when checked
                checkboxes[i].value = 1;
            } else {
                // Set the value to 0 when unchecked
                checkboxes[i].value = 0;
            }
			console.log("Update Checkbox button clicked.");
        }
        // Submit the form
        document.MyForm.submit();
    }

	function ITRActionButtonClick(v) {
		e = document.MyForm;
		if(e==null) {
		  alert(\'Sila pastikan nama form diwujudkan.!\');
		} 
		else {
		  count=0;
		  for(c=0; c<e.elements.length; c++) {
			if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
			  count++;
			}
		  }
		  
		  if(count==0) {
			alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
		  } 
		  else {
			if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
			  e.action.value = v;
			  e.submit();
			}
		  }
		}
	}


	// Function to update checkbox values to 1 if checked and 0 if unchecked
	function updateCheckboxValues() {
		var checkboxes = document.MyForm.elements["pk[]"];
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked) {
				// Set the value to 1 when checked
				checkboxes[i].value = 1;
			} else {
				// Set the value to 0 when unchecked
				checkboxes[i].value = 0;
			}
		}
	}

	function ITRActionButtonClickStatus(v) {
	      var strStatus="";
		  e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        j=0;
			for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
				pk = e.elements[c].value;
				strStatus = strStatus + ":" + pk;
				count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } 
			else if (count > 1){
				alert(\'Sila pilih hanya satu rekod yang hendak di\' + v + \'kan.\');
			}
				else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
	          window.location.href ="?vw=memberStatus&pk=" + strStatus;
			  }
	        }
	      }
	    }

		function kemaskini(v) {
			var selectedCheckbox;
			var e = document.MyForm;
		  
			if (e == null) {
			  alert(\'Sila pastikan nama form diwujudkan.!\');
			} else {
			  var count = 0;
		  
			  for (var c = 0; c < e.elements.length; c++) {
				if (e.elements[c].name == "pk[]" && e.elements[c].checked) {
				  selectedCheckbox = e.elements[c];
				  count++;
				}
			  }
		  
			  if (count === 0) {
				alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
			  } else if (count > 1) {
				alert(\'Sila pilih hanya satu rekod yang hendak di\' + v + \'kan.\');
			  } else {
				var pkValue = selectedCheckbox.value;
				if (confirm(\'1 rekod hendak di\' + v + \'kan?\')) {
				  window.location.href = "?vw=memberKemaskini&pk=" + pkValue;
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
				window.location.href = "memberStatus.php?pk=" + pk;
			}
		}
	}


	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = \'' . $sFileName . '?&StartRec=1&pg=\' + c.options[c.selectedIndex].value;
	}

		function toggleArrow(button) {
            const icon = button.querySelector(\'i\');
            if (icon.classList.contains(\'fa-chevron-down\')) {
                icon.classList.remove(\'fa-chevron-down\');
                icon.classList.add(\'fa-chevron-up\');
            } else {
                icon.classList.remove(\'fa-chevron-up\');
                icon.classList.add(\'fa-chevron-down\');
		  }
		}

</script>';


?>
