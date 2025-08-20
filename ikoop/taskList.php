<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberT.php
*          Date 		: 	
*********************************************************************************/
if (!isset($StartRec)) $StartRec = 1;
if (!isset($pg)) $pg = 10;
if (!isset($q)) $q = "";
if (!isset($by)) $by = "1";
if (!isset($filter)) $filter = "0";
if (!isset($dept)) $dept = "";
include("header.php");	
include("koperasiQry.php");	
date_default_timezone_set("Asia/Jakarta");
if (get_session("Cookie_groupID") <> 1 
AND get_session("Cookie_groupID") <> 2 
AND get_session("Cookie_groupID") <> 3 
AND get_session("Cookie_groupID") <> 4 
OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}
$IDName = get_session("Cookie_userName");

$sFileName = '?vw=taskList&mn=920';
// $sFileRef  = '?vw=memberEditT&mn=905';
$title     = "Senarai Tugasan Koperasi";

//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
    $sWhere = "";
    for ($i = 0; $i < count($pk); $i++) {
        $sSQL = '';
        $sWhere = "userID=" . tosql($pk[$i], "Text");
        $sSQL = "DELETE FROM task WHERE " . $sWhere;
        $rs = &$conn->Execute($sSQL);

        //record
        $sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)". 
                   " VALUES ('Hapus koperasi -$pk', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
        $rs = &$conn->Execute($sqlAct);
    }
}
//--- End   : deletion based on checked box -------------------------------------------------------

//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$sSQL = "	SELECT a.departmentID, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b
			WHERE a.departmentID = b.ID
			AND   a.status = 1 
			GROUP BY a.departmentID";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		$rs->MoveNext();
	}
}
/* array_push ($deptList, "Bersara");
array_push ($deptVal, "BSR"); */
if ($dept == "BSR") {  $filter = 4; $dept = ""; }

	$sSQL = "";
	$sWhere = "1=1";  // Start with a basic condition for concatenation

	// Add status filter if it's not "ALL" or "0"
	if ($filter !== "0") {  
		$sWhere .= " AND a.status = " . tosql($filter, "Number");
	}

	// Add department filter if `$dept` is not empty
	if (!empty($dept)) {
		$sWhere .= " AND c.departmentID = " . tosql($dept, "Number");
	}

	// Add search filter based on `$by` criteria
	if (!empty($q)) {
		switch ($by) {
			case 1:
				$sWhere .= " AND c.kopNum LIKE " . tosql($q . "%", "Text");
				break;
			case 2:
				$sWhere .= " AND b.name LIKE " . tosql("%" . $q . "%", "Text");
				break;
			case 3:
				$sWhere .= " AND b.loginID LIKE " . tosql($q . "%", "Text");
				break;
		}
	}
	$sSQL = "SELECT DISTINCT 
				IFNULL(a.status, 1) AS statusTask, a.*, b.*, c.* 
				FROM task a 
				JOIN users b ON a.userID = b.userID 
				JOIN userdetails c ON a.userID = c.userID 
				WHERE $sWhere 
				ORDER BY CAST(c.memberID AS SIGNED), a.startDate DESC";


	$GetMember = $conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action=' .$sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="filter" value="'.$filter.'">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title">' . strtoupper($title) . '</h5>
    <input type="button" class="btn btn-md btn-primary" value="+ Mohon Baru" onClick="window.location.href=\'?vw=taskEntry&mn=920\'"/>
</div>

<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
';	
print '<div class="mb-3 row m-1">
<div>
			Carian Melalui 
			<select name="by" class="form-select-sm">'; 
if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Koperasi</option>'; 	else print '<option value="2">Nama Koperasi</option>';				
if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';							
print '		</select>
			<input type="text" name="q" value="" class="form-control-sm" maxlength="50" size="20" class="Data">
			<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}
	print '	</select>
		</div>
</div> 
	<div class="mb-3 row m-1">
<div>&nbsp;
			Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
                // print '<option value="ALL">Semua';
                for ($i = 0; $i < count($tugasanList); $i++) {
                    if($i == 0 ||$i == 1||$i == 2 |$i == 3){
                    if ($tugasanVal[$i] < 4) {
                        print '	<option value="'.$tugasanVal[$i].'" ';
                        if ($filter == $tugasanVal[$i]) print ' selected';
                        print '>'.$tugasanList[$i];
                    }
                }
            }
			print '		</select>&nbsp;';

			if (($IDName == 'superadmin') OR ($IDName == 'admin') OR get_session("Cookie_groupID") == '2') {
				if ($filter == 0)  print '&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">';
			}
			if(get_session("Cookie_groupID") <> 2) {
				//print'<input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');">'; 
			}
			else{
				print '&nbsp;&nbsp;'; 
				print'<input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');">'; 
			}
			print '</div>
			</div>

			<table width="100%">
				<tr>';
				if(get_session("Cookie_groupID") == '2') {
					print'<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>';
				}
				print'<td align="right" class="textFont">
						Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
					if ($pg == 5)	print '<option value="5" selected>5</option>'; 	 	else print '<option value="5">5</option>';				
					if ($pg == 10)	print '<option value="10" selected>10</option>'; 	else print '<option value="10">10</option>';				
					if ($pg == 20)	print '<option value="20" selected>20</option>'; 	else print '<option value="20">20</option>';				
					if ($pg == 30)	print '<option value="30" selected>30</option>'; 	else print '<option value="30">30</option>';				
					if ($pg == 40)	print '<option value="40" selected>40</option>'; 	else print '<option value="40">40</option>';				
					if ($pg == 50)	print '<option value="50" selected>50</option>';	else print '<option value="50">50</option>';				
					if ($pg == 100)	print '<option value="100" selected>100</option>';	else print '<option value="100">100</option>';				
	print '				</select>
					</td>
				</tr>
			</table>
		';

	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
		
				<div class="table-responsive">
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center" class="table table-sm table-striped">
				<tr class="table-danger" style="height: 35px;"> <!-- Set row height -->
					<td nowrap align="center">&nbsp;</td>
					<td nowrap><b>No./Nama Koperasi</b></td>
					<td nowrap align="center"><b>Dokumen Tugasan</b></td>
					<td nowrap align="center"><b>Singkatan Koperasi</b></td>
					<td nowrap align="center"><b>Masalah</b></td>
					<td nowrap align="center"><b>Status</b></td>
					<td nowrap align="center"><b>Orang Yang Bertugas</b></td>
					<td nowrap align="center"><b>Keterangan</b></td>
					<td nowrap align="center"><b>Tarikh Daftar</b></td>
					<td nowrap align="center"><b>Tarikh Anggaran</b></td>
				</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
			$jabatan = dlookup("userdetails", "departmentID", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$sqlBal = "SELECT sum( outstandingAmt ) AS bal FROM `loans` WHERE status = 3 and userID =" . tosql($GetMember->fields(userID), "Text");
			$rsBal = &$conn->Execute($sqlBal);
			$balLoan = $rsBal->fields('bal');
			$totYrSh = $totYr + $totSh;
			$status = $GetMember->fields(statusTask);
			$colorStatus = "Data";
			if ($status == 2) $colorStatus = "greenText";
			if ($status == 3) $colorStatus = "redText";
			if ($status == 1) $colorStatus = "text-info";
			print ' <tr>
						<td class="Data" align="center">' . $bil . '</td>
						<td class="Data">';
						if(get_session("Cookie_groupID") <> 2) {
							//print'<input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						}
						else {
							print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.$GetMember->fields(userID).'">';
						}
						print'
						'.$GetMember->fields(kopNum).' -
						'.$GetMember->fields(name).'
						</td>';
						if($GetMember->fields(doc_tugasan) == NULL){
							print '<td align="center" class="Data">&nbsp;-</td>';
						}
						else {
							print '<td class="Data" align="center"><button type="button" class="btn btn-outline-danger" onClick="window.open(\'upload_tugasan/' .$GetMember->fields(doc_tugasan). '\', \'pop\', \'top=70,left=70,width=900,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');"><i class="far fa-file-pdf text-secondary"></i> Paparan Fail</button>&nbsp;
							<input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik Semula"  onclick= "Javascript:window.location.href=\'?vw=reuploadTugasan&pk='.$GetMember->fields(userID).'\';">
							</td>';
						}
						print'<td align="center" class="Data">'.$GetMember->fields(loginID).'</td>
						<td class="Data" align="center">'.$GetMember->fields(title_problem).'</td>
						<td class="Data" align="center"><font class="'.$colorStatus.'">'.$tugasanList[$status].'</font></td>
						<td class="Data" align="center">'.$GetMember->fields(person_in_charge).'</td>
						<td class="Data" align="center">'.$GetMember->fields(keterangan).'</td>
						<td class="Data" align="center">'.toDate("d/m/Y",$GetMember->fields(startDate)).'</td>
						<td class="Data" align="center">'.toDate("d/m/Y",$GetMember->fields(estimatedDate)).'</td>
					</tr>';
				$cnt++;
				$bil++;
			$GetMember->MoveNext();
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
						print '<A href="'.$sFileName.'?&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&filter='.$filter.'">';
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
			<tr><td align="center"><hr size=1"><b>- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b>- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	}
print ' 
</table>
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
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	            e.action.value = v;
	            e.submit();
	          }
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
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
				window.location.href ="?vw=tugasanStatus&mn=920&pk=" + strStatus;
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
				window.location.href = "?vw=tugasanStatus&mn=920&pk=" + pk;
			}
		}
	}
	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '?&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

</script>';
?>