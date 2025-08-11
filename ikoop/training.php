<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	training.php
*          Date 		: 	26/03/2006
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
//tukar default listing view from 10 to 50 /
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="1";
//if (!isset($filter))	$filter="ALL";
if (!isset($dept))		$dept="";
if (!isset($jenisCode))		$jenisCode="";
if (!isset($fasa))		$fasa="";
if (!isset($active))	$active="1";
if ($filter == 1)	$active="1";

include("header.php");	

if (get_session("Cookie_groupID") <> 1 
AND get_session("Cookie_groupID") <> 2 
AND get_session("Cookie_groupID") <> 3 
AND get_session("Cookie_groupID") <> 4 
OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$IDName = get_session("Cookie_userName");

$sSQL2 = "	SELECT *
			FROM users WHERE loginID = '".$IDName."'";
$rs2 = &$conn->Execute($sSQL2);

$IDGroup = $rs2->fields(groupID) ;

$sFileName = "?vw=training&mn=$mn";
$sFileRef  = "?vw=memberEdit&mn=$mn";
$title     = "Latihan Koperasi";



//--- Begin : reset based on checked box -------------------------------------------------------
/* if ($action == "reset") {
	$sSQL = '';
	$sWhere = "";
	$sWhere = ' userID = ' . tosql($pk[0],"Text");
	$sSQL	= ' UPDATE users SET ' .
	          ' password=' . tosql(strtoupper(md5("koperasi123")), "Text") ;
	$sSQL .= ' WHERE ' . $sWhere;
	$rs = &$conn->Execute($sSQL);
	print '<script>alert("Katalaluan koperasi ini telah direset kepada \"koperasi123\"\nSila maklumkan kepada koperasi ini supaya menukar kata laluan.");</script>';
} */
//--- End   : reser based on checked box -------------------------------------------------------
//--- Prepare status MSS list
$MSSList = array("","(MSS)");

//--- Prepare list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();
$fasaList = Array();
$fasaVal  = Array();

/* $sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as codeName
             FROM userdetails a
             LEFT JOIN general b ON a.departmentID = b.ID
             GROUP BY a.departmentID"; */

$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName
			FROM userdetails a
			INNER JOIN general b ON a.departmentID = b.ID
			INNER JOIN users c ON a.userID = c.userID
			/* WHERE a.status = 1  */
			GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0) {
    while (!$rsDept->EOF) {
        array_push($deptList, $rsDept->fields(deptName));
        array_push($deptVal, $rsDept->fields(departmentID));
        $rsDept->MoveNext();
    }
}

// Query for jenisCodeList
$sSQLJenisCode = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, a.jenisCode
				FROM userdetails a
				INNER JOIN general b ON a.jenisCode = b.ID
				INNER JOIN users c ON a.userID = c.userID
				/* WHERE a.status = 1  */
				GROUP BY a.jenisCode";

$rsJenisCode = &$conn->Execute($sSQLJenisCode);
if ($rsJenisCode->RowCount() <> 0) {
    while (!$rsJenisCode->EOF) {
        array_push($jenisCodeList, $rsJenisCode->fields(deptName));
        array_push($jenisCodeVal, $rsJenisCode->fields(jenisCode));
        $rsJenisCode->MoveNext();
    }
}

// Query for statustraining
$sSQLtraining = "SELECT a.departmentID, b.code as deptCode, b.name as codeName, a.training
				FROM userdetails a
				LEFT JOIN general b ON a.training = b.ID
				GROUP BY a.training";

$rstraining = &$conn->Execute($sSQLtraining);
if ($rstraining->RowCount() <> 0) {
    while (!$rstraining->EOF) {
        array_push($trainingList, $rstraining->fields(codeName));
        array_push($trainingVal, $rstraining->fields(training));
        $rstraining->MoveNext();
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
$sWhere = " a.userID = b.userID AND b.status = " . tosql($filter, "Number")/*  . " AND a.isActive = " . tosql($active, "Number") */;
//$sWhere = " a.userID = b.userID ";
if ($dept <> "") 	{
    $sWhere .= " AND b.departmentID = " . tosql($dept, "Number");
}

if ($jenisCode <> "") 	{
    $sWhere .= " AND b.jenisCode = " . tosql($jenisCode, "Number");
}


if ($training <> "") 	{
    $sWhere .= " AND b.training = " . tosql($training, "Number");
}

if ($fasa <> "") 	{
    $sWhere .= " AND b.fasa = " . tosql($fasa, "Number");
}

//search value
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
$sSQL = $sSQL . $sWhere;
// $sSQL = $sSQL . "order by CAST( b.kopNum AS SIGNED INTEGER ) asc";
$sSQL = $sSQL . "order by b.training desc";
$GetMember = &$conn->Execute($sSQL);
$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print
'<form name="MyForm" action='.$sFileName.' method="post">'
.'<input type="hidden" name="action">'
.'<input type="hidden" name="pk" value="<?=$pk?>">'
.'<input type="hidden" name="filter" value="'.$filter.'">'
.'<div class="table-responsive">'
.'<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">'
.'<h5 class="card-title">'.strtoupper($title).'</h5>';
	
	print
    '<tr valign="top" class="Header">'
	   	.'<td align="left" >'
			.'Carian Melalui '
			.'<select name="by" class="form-select-sm">'; 
			if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
			if ($by == 2)	print '<option value="2" selected>Nama Koperasi</option>'; 	else print '<option value="2">Nama Koperasi</option>';				
			if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';							
			print
 			'</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm">
 			<input type="submit" class="btn btn-secondary btn-sm" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}
			print	'</select>
		</td>
	</tr>
	<tr valign="top">
		<td align="left">&nbsp;
			Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			for ($i = 0; $i < count($statusList); $i++) {
				if($i == 1 || $i==3){
					if ($statusVal[$i] < 4) {
						print '	<option value="'.$statusVal[$i].'" ';
						if ($filter == $statusVal[$i]) print ' selected';
						print '>'.$statusList[$i];
					}
				}
			}
			print
			'</select>&nbsp
			Kod
			<select name="jenisCode" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($jenisCodeList); $i++) {
					print '	<option value="'.$jenisCodeVal[$i].'" ';
					if ($jenisCode == $jenisCodeVal[$i]) print ' selected';
					print '>'.$jenisCodeList[$i];
			}
			print
			'</select>&nbsp
			Status Latihan
			<select name="training" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($trainingList); $i++) {
				if($i == 1 || $i==0){
					print '	<option value="'.$trainingVal[$i].'" ';
				if ($training == $trainingVal[$i]) print ' selected';
					print '>'.$trainingList[$i];
				}
			}
			print
			'</select>&nbsp
			Fasa
			<select name="fasa" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
			for ($i = 0; $i < count($fasaList); $i++) {
				/* if($i==0|| $i==1|| $i==2|| $i==3){ */
					print '	<option value="'.$fasaVal[$i].'" ';
					if ($fasa == $fasaVal[$i]) print ' selected';
					print '>'.$fasaList[$i];
				/* } */
			}
			if(get_session("Cookie_groupID") <> 2) {
				//print '</br></select><input type="hidden" class="btn btn-primary btn-sm" value="Kemaskini" onClick="ITRActionButtonClickStatus2(\'ubah\');">';
			}
			else{
				print '</br></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-primary btn-sm" value="Kemaskini" onClick="ITRActionButtonClickStatus2(\'ubah\');">';
			}
			
			
print '	</td>
	</tr>
	<tr valign="top" class="textFont">
		<td>
			<table width="100%">
				<tr>';
					//if ($filter == 3) {
					/*if ($filter) {
						print
						'<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>
						<td align="right" class="textFont">';
					} */
						print
						'<td class="textFont">&nbsp;</td><td align="right" class="textFont">';

					print
					'&nbsp;&nbsp;Paparan';
                    echo papar_ms($pg);
                    print '</td>
				</tr>
			</table>
		</td>
	</tr>';	
	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print 
	    '<tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped" style="font-size:">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap><b>No./Nama Koperasi</b></td>
						<td nowrap align="center"><b>Status Latihan</b></td>
						<td nowrap align="center"><b>Jenis Fasa</b></td>
						<!-- <td nowrap align="center"><b>Singkatan Koperasi</b></td> -->
						<td nowrap align="center"><b>Jenis</b></td>
						<td nowrap align="center"><b>Zon</b></td>
						<td nowrap align="center"><b>Kod</b></td>
						<td nowrap align="center"><b>Status</b></td>
						<!--td nowrap align="center"><b>Tarikh Daftar</b></td-->
						<td nowrap align="center"><b>Tarikh SST Diterima</b></td>
						<!--td nowrap align="center"><b>Tempoh Permohonan (Hari)</b></td-->
					</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
			$training = dlookup("userdetails", "training", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$approvedDate = $GetMember->fields(approvedDate);
			if ($approvedDate === null || strtotime($approvedDate) === false) {
				$approvedDate = 'Tiada Tarikh';
			}
			else {
				// Format the date as desired (e.g., 'd/m/Y H:i:s')
				$approvedDate = date('d/m/Y', strtotime($approvedDate));
			}
			$colorTraining = "Data";
			if ($Training == 0) $colorTraining = "greenText";
			if ($Training == 1) $colorTraining = "redText";

			$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));

			$colorStatus = "Data";
			if ($Status == 1) $colorStatus = "greenText";
			if ($Status == 3) $colorStatus = "redText";
			
			$MSS = dlookup("userdetails", "statusMSS", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$fasa  = dlookup("userdetails", "fasa", "userID=" . tosql($GetMember->fields(userID), "Text"));
			
			//find permohonan duration

			$datetime1 = strtotime ($GetMember->fields(applyDate));
			$datetime2 = strtotime ($GetMember->fields(approvedDate));

			$secs = $datetime2 - $datetime1; //differences in seconds 
			$duration = (int) ($secs / 86400); //convert to days 

			if($training == 0){
				$fasa =  0;
			}

			$colorStatus = "Data";
			if ($status == 1) $colorStatus = "text-primary";
			if ($status == 3) $colorStatus = "text-danger";

			$colorTraining = "Data";
			//if($training == 0) $colorTraining = "text-black";
			if($training == 1) $colorTraining = "text-info";

			//print data
			print '<tr>
						<td class="Data" align="right">' . $bil . '&nbsp;</td>
						<td class="Data" style="text-transform:uppercase" >';
						if(get_session("Cookie_groupID") <> 2) {
							//print '<input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						}
						else {
							print '<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						}
						//}
						print '	<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
							'.$GetMember->fields(kopNum).' -
							'.$GetMember->fields(name).'</a></td>	
						<td class="Data" align="center">&nbsp;<font class="'.$colorTraining.'">'.$trainingList[$training].'</td>
						<!-- <td class="Data" align="center">&nbsp;'.$fasaList[$fasa].'</td> -->
						<td class="Data" align="center">&nbsp;'.($fasa == 0 ? 'TIADA FASA' : dlookup("general", "name", "ID=" . tosql($GetMember->fields('fasa'), "Number"))).'</td>
						<!-- <td class="Data" align="center">&nbsp;'.$fasa.'</td> -->
						<!-- <td class="Data" align="center">&nbsp;'.$GetMember->fields(loginID).'</td>	-->
						<td class="Data" align="center">&nbsp;'.$jenisList[$jenis].'</font></td>
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td>
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('jenisCode'), "Number")).'</td>
						<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].' '.$MSSList[$MSS].'</font></td>
						<!--td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(applyDate)).'</td> -->
						<td class="Data" align="center">&nbsp;'.$approvedDate.'</td>
						<!--td class="Data" align="center">' . $duration . '</td> -->
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
			<td class="textFont">Jumlah Rekod : <b>'.$GetMember->RowCount().'</b></td>
		</tr>';
	} else {
		if ($q == '') {
			print '<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size="1"></td></tr>';
		} else {
			print '<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size="1"></td></tr>';
		}
	}
print ' 
</table></td></tr></table></div>
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
	          if(confirm(count + \' rekod hendak di \' + v + \'?\')) {
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
	        } 
			else if (count > 1){
				alert(\'Sila pilih hanya satu rekod yang hendak di\' + v + \'kan.\');
			}
			else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
	          window.location.href ="?vw=memberTraining&mn=905&pk=" + strStatus;
			  }
	        }
	      }
	    }

		function ITRActionButtonClickStatus2(v) {
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
				  window.location.href = "?vw=memberLatihan&pk=" + pkValue;
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
				window.location.href = "?vw=memberAktif&mn=905&pk=" + pk;
			}
		}
	}


	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

    function ITRActionButtonReset() {
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
				alert(\'Sila pilih satu rekod sahaja untuk reset kala laluan\');
			} else {
	          if(confirm(\' Rekod ini akan diresetkan kata laluan?\')) {
	            e.action.value = \'reset\';
				e.dept.value = "'.$dept.'";
				e.by.value = "'.$by.'";
				e.q.value = "'.$q.'";
	            e.submit();
	          }
			}
		}
	}


</script>';

?>