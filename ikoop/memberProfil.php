<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberProfil.php
*          Date 		: 	26/03/2006
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
//tukar default listing view from 10 to 50 /
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="1";
if (!isset($dept))		$dept="";
if (!isset($jenisCode))		$jenisCode="";
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

$sFileName = "?vw=memberProfil&mn=$mn";
$sFileRef  = "?vw=memberEdit&mn=$mn";
$title     = "Profil Koperasi";



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

//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();


$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName
			FROM userdetails a
			INNER JOIN general b ON a.departmentID = b.ID
			INNER JOIN users c ON a.userID = c.userID
			GROUP BY a.departmentID";
	

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0) {
    while (!$rsDept->EOF) {
        array_push($deptList, $rsDept->fields(deptName));
        array_push($deptVal, $rsDept->fields(departmentID));
        $rsDept->MoveNext();
    }
}

$sSQLJenisCode = "SELECT a.departmentID, b.code as deptCode, b.name as codeName, a.jenisCode
				FROM userdetails a
				INNER JOIN general b ON a.jenisCode = b.ID
				INNER JOIN users c ON a.userID = c.userID
				WHERE a.status = 1 
				GROUP BY b.ID";

$rsJenisCode = &$conn->Execute($sSQLJenisCode);
if ($rsJenisCode->RowCount() <> 0) {
    while (!$rsJenisCode->EOF) {
        array_push($jenisCodeList, $rsJenisCode->fields(codeName));
        array_push($jenisCodeVal, $rsJenisCode->fields(jenisCode));
        $rsJenisCode->MoveNext();
    }
}

$sSQL = "";
$sWhere = " a.userID = b.userID";
if ($filter == 1) {
    $sWhere .= " AND b.status = " . tosql($filter, "Number");
}
if ($filter == 4) {
    $sWhere .= " AND b.status = " . tosql($filter, "Number");
}

if ($filter == 3) {
    $sWhere .= " AND b.status = " . tosql($filter, "Number") . " AND a.isActive = " . tosql($active, "Number");
}
//$sWhere = " a.userID = b.userID AND b.status = " . tosql($filter, "Number") . " AND a.isActive = " . tosql($active, "Number");
//$sWhere = " a.userID = b.userID AND b.status = " . tosql($filter, "Number");
if ($dept <> "") 	{
    $sWhere .= " AND b.departmentID = " . tosql($dept, "Number");
}

if ($jenisCode <> "") 	{
    $sWhere .= " AND b.jenisCode = " . tosql($jenisCode, "Number");
}

//search value
if ($q <> "") 	{
	if ($by == 1) {
		$sWhere .= " AND b.kopNum like '%" .$q ."%'";			
	} else if ($by == 2) {
		$sWhere .= " AND a.name like '%" . $q. "%'";
	} else if ($by == 3) {
		//$sWhere .= " AND b.jenis like '%" . $q. "%'";
		//$sWhere .= " AND ((b.jenis = 0 AND 'Kredit' = '" . $q . "') OR (b.jenis = 1 AND 'Bukan Kredit' = '" . $q . "'))";
		$sWhere .= " AND a.loginID like '%" . $q. "%'";
	}
}

$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$sSQL = $sSQL . $sWhere;
$sSQL = $sSQL . "order by CAST( b.kopNum AS SIGNED INTEGER ) asc";
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
					if ($statusVal[$i] < 5) {
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
			'</select>&nbsp;';

			print '&nbsp;&nbsp;<input type="button" class="btn btn-sm btn-primary" value="Statistik" onClick="ITRActionButtonClickStatistik(\'statistik\');">';
			

			if ($filter == 3) {
				print '&nbsp;&nbsp;Status Login Sistem
				<select name="active" class="form-select-xs" onchange="document.MyForm.submit();">';
				for ($i = 0; $i < count($activeList); $i++) {
					if ($activeVal[$i] < 4) {
						print '	<option value="'.$activeVal[$i].'" ';
					if ($active == $activeVal[$i]) print ' selected';
						print '>'.$activeList[$i];
					}
				}
				if(get_session("Cookie_groupID") <> 2) {
					//print '	</select>&nbsp;&nbsp;<input type="hidden" class="btn btn-primary btn-sm" value="Ubah" onClick="ITRActionButtonClickStatus(\'ubah\');">';
				}
				else{
					print '	</select>&nbsp;&nbsp;<input type="button" class="btn btn-primary btn-sm" value="Ubah" onClick="ITRActionButtonClickStatus(\'ubah\');">';
				}
			}else
			{
				
			/*if (($IDName == 'superadmin') OR ($IDName == 'admin') OR get_session("Cookie_groupID") == '2'){
				print 'Kata Laluan <input type="button" class="btn btn-warning btn-sm" value="Set Semula" onClick="ITRActionButtonReset();">';			
			
			}*/
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
					'&nbsp;&nbsp;';
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
						<td nowrap align="center"><b>Singkatan Koperasi</b></td>
						<td nowrap align="center"><b>Jenis</b></td>
						<!--td nowrap align="center"><b>Zon</b></td-->
						<td nowrap align="center"><b>Kod</b></td>
						<!--td nowrap align="center"><b>Tarikh Daftar</b></td-->
						<!--td nowrap align="center"><b>Tarikh SST Diterima</b></td-->
						<!--td nowrap align="center"><b>Tempoh Permohonan (Hari)</b></td-->
					</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
			$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$approvedDate = $GetMember->fields(approvedDate);
			if ($approvedDate === null || strtotime($approvedDate) === false) {
				$approvedDate = 'Tiada Tarikh';
			}
			else {
				// Format the date as desired (e.g., 'd/m/Y H:i:s')
				$approvedDate = date('d/m/Y', strtotime($approvedDate));
			}
			$colorStatus = "Data";
			if ($status == 1) $colorStatus = "greenText";
			if ($status == 3) $colorStatus = "redText";
			if ($status == 4) $colorStatus = "blueText";
			
			$MSS = dlookup("userdetails", "statusMSS", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
			
			
			//find permohonan duration

			$datetime1 = strtotime ($GetMember->fields(applyDate));
			$datetime2 = strtotime ($GetMember->fields(approvedDate));

			$secs = $datetime2 - $datetime1; //differences in seconds 
			$duration = (int) ($secs / 86400); //convert to days 

			print '<tr>
						<td class="Data" align="right">' . $bil . '&nbsp;</td>
						<td class="Data" style="text-transform:uppercase" >';
						// if(get_session("Cookie_groupID") <> 2) {
						// 	//print '<input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						// }
						// else {
							print '<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
						// }
						print '	<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
							'.$GetMember->fields(kopNum).' -
							'.$GetMember->fields(name).'</a></td>	
						<td class="Data" align="center">&nbsp;'.$GetMember->fields(loginID).'</td>	
						<td class="Data" align="center">&nbsp;'.$jenisList[$jenis].'</font></td>			
						<!--td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td-->
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('jenisCode'), "Number")).'</td>
						<td class="Data" align="center" hidden>&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].' '.$MSSList[$MSS].'</font></td>
						<!--td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(applyDate)).'</td-->
						<!--td class="Data" align="center">&nbsp;'.$approvedDate.'</td-->
						<!--td class="Data" align="center">' . $duration . '</td-->
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

		
	function ITRActionButtonClickStatistik(v) {
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
	        //   if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
	          window.location.href ="?vw=statisticKoperasi&mn=905&pk=" + strStatus;
			//   }
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