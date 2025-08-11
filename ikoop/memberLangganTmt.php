<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	memberLatihan.php
 *          Date 		: 	10/11/2023
 *********************************************************************************/
if (!isset($StartRec))    $StartRec = 1;
if (!isset($pg))        $pg = 50;
if (!isset($q))            $q = "";
if (!isset($by))        $by = "1";
if (!isset($filter))    $filter = "1";
if (!isset($dept))        $dept = "";
if (!isset($jenisCode))        $jenisCode = "";
if (!isset($jenis))        $jenis = "";
if (!isset($active))    $active = "1";
if ($filter == 1)    $active = "1";

include("header.php");

if (
    get_session("Cookie_groupID") <> 1
    and get_session("Cookie_groupID") <> 2
    and get_session("Cookie_groupID") <> 3
    and get_session("Cookie_groupID") <> 4
    or get_session("Cookie_koperasiID") <> 0
) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$IDName = get_session("Cookie_userName");

$sSQL2 = "	SELECT *
			FROM users WHERE loginID = '" . $IDName . "'";
$rs2 = &$conn->Execute($sSQL2);

$IDGroup = $rs2->fields(groupID);

$sFileName = "?vw=memberLangganTmt&mn=$mn";
$sFileRef  = "?vw=memberEdit&mn=$mn";
$title     = "Langganan Koperasi";

$MSSList = array("", "(MSS)");

//--- Prepare department list
$pakejList = array();
$pakejVal  = array();
$jenisCodeList = array();
$jenisCodeVal  = array();
$tempohDateList = array();
$tempohDateVal  = array();
$tempohMthList = array();
$tempohMthVal  = array();


$sSQLPakej = "SELECT a.pakej, b.code as deptCode, b.name as codeName
				FROM userdetails a
				INNER JOIN general b ON a.pakej = b.ID
				INNER JOIN users c ON a.userID = c.userID
				-- WHERE a.status = 1 
				GROUP BY b.ID";

$rsPakej = &$conn->Execute($sSQLPakej);
if ($rsPakej->RowCount() <> 0) {
    while (!$rsPakej->EOF) {
        array_push($pakejList, $rsPakej->fields(codeName));
        array_push($pakejVal, $rsPakej->fields(pakej));
        $rsPakej->MoveNext();
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

$sSQLtempohDate = "SELECT YEAR(tempohDate) AS tahun 
					FROM userdetails 
					WHERE tempohDate IS NOT NULL
					GROUP BY YEAR(tempohDate)
					ORDER BY tahun";

$rstempohDate = &$conn->Execute($sSQLtempohDate);
if ($rstempohDate->RowCount() <> 0) {
    while (!$rstempohDate->EOF) {
        array_push($tempohDateList, $rstempohDate->fields(tahun));
        array_push($tempohDateVal, $rstempohDate->fields(tahun));
        $rstempohDate->MoveNext();
    }
}

$sSQLtempohMth = "SELECT MONTH(tempohDate) AS bulan 
					FROM userdetails 
					WHERE tempohDate IS NOT NULL
					GROUP BY MONTH(tempohDate)
					ORDER BY bulan";

$rstempohMth = &$conn->Execute($sSQLtempohMth);
if ($rstempohMth->RowCount() <> 0) {
    while (!$rstempohMth->EOF) {
        array_push($tempohMthList, $rstempohMth->fields(bulan));
        array_push($tempohMthVal, $rstempohMth->fields(bulan));
        $rstempohMth->MoveNext();
    }
}

$sSQL = "";
$sWhere = " a.userID = b.userID";

// Filter for `status`
if ($filter == 1) {
    $sWhere .= " AND b.status = " . tosql($filter, "Number");
}

if ($filter == 3) {
    $sWhere .= " AND b.status = " . tosql($filter, "Number") . " AND a.isActive = " . tosql($active, "Number");
}

// Filter for `pakej`
if ($pakej <> "") {
    $sWhere .= " AND b.pakej = " . tosql($pakej, "Number");
}

// Filter for `jenisCode`
if ($jenisCode <> "") {
    $sWhere .= " AND b.jenisCode = " . tosql($jenisCode, "Number");
}

// Filter for `jenis`
if ($jenis <> "") {
    $sWhere .= " AND b.jenis = " . tosql($jenis, "Number");
}

// Search value conditions
if ($q <> "") {
    if ($by == 1) {
        $sWhere .= " AND b.kopNum like '%" . $q . "%'";
    } else if ($by == 2) {
        $sWhere .= " AND a.name like '%" . $q . "%'";
    } else if ($by == 3) {
        $sWhere .= " AND a.loginID like '%" . $q . "%'";
    }
}

// Add the date range condition for `tempohDate`
$sWhere .= " AND b.tempohDate >= CURDATE()";
$sWhere .= " AND b.tempohDate <= CURDATE() + INTERVAL 30 DAY";

// Complete the WHERE clause
$sWhere = " WHERE (" . $sWhere . ")";

// Build the final SQL query
$sSQL = "SELECT DISTINCT a.*, b.* FROM users a, userdetails b" . $sWhere;
$sSQL = $sSQL . " ORDER BY b.tempohDate DESC";

// Execute the query
$GetMember = &$conn->Execute($sSQL);
$GetMember->Move($StartRec - 1);

// Get the total record count
$TotalRec = $GetMember->RowCount();
$TotalPage = ($TotalRec / $pg);


print
    '<form name="MyForm" action=' . $sFileName . ' method="post">'
    . '<input type="hidden" name="action">'
    . '<input type="hidden" name="pk" value="<?=$pk?>">'
    . '<input type="hidden" name="filter" value="' . $filter . '">'
    . '<div class="table-responsive">'
    . '<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">'
    . '<h5 class="card-title">' . strtoupper($title) . '</h5>';

print
    '<tr valign="top" class="Header">'
    . '<td align="left" >'
    . 'Carian Melalui '
    . '<select name="by" class="form-select-sm">';
if ($by == 1)    print '<option value="1" selected>No./ID Koperasi</option>';
else print '<option value="1">No./ID Koperasi</option>';
if ($by == 2)    print '<option value="2" selected>Nama Koperasi</option>';
else print '<option value="2">Nama Koperasi</option>';
if ($by == 3)    print '<option value="3" selected>Singkatan Koperasi</option>';
else print '<option value="3">Singkatan Koperasi</option>';
print
    '</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm">
 			<input type="submit" class="btn btn-secondary btn-sm" value="Cari">&nbsp;&nbsp;&nbsp;<br><br>		
			<!-- Zon
			<select name="dept" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="">- Semua - -->';
/* for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			} */
print    '</select>
		</td>
	</tr>
	<tr valign="top">
		<td align="left">&nbsp;
			Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
for ($i = 0; $i < count($statusList); $i++) {
    if ($i == 1 || $i == 3) {
        if ($statusVal[$i] < 4) {
            print '	<option value="' . $statusVal[$i] . '" ';
            if ($filter == $statusVal[$i]) print ' selected';
            print '>' . $statusList[$i];
        }
    }
}
print
    '</select>&nbsp
			Pakej
			<select name="pakej" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
for ($i = 0; $i < count($pakejList); $i++) {
    print '	<option value="' . $pakejVal[$i] . '" ';
    if ($pakej == $pakejVal[$i]) print ' selected';
    print '>' . $pakejList[$i];
}

print
    '</select>&nbsp;';

print
    '</select>&nbsp
			Kod
			<select name="jenisCode" class="form-select-sm" onchange="document.MyForm.submit();">;
			<option value="">- Semua -';
for ($i = 0; $i < count($jenisCodeList); $i++) {
    print '	<option value="' . $jenisCodeVal[$i] . '" ';
    if ($jenisCode == $jenisCodeVal[$i]) print ' selected';
    print '>' . $jenisCodeList[$i];
}

print
    '</select>&nbsp;';

print '</select>&nbsp;Kredit <select name="jenis" class="form-select-sm" onchange="document.MyForm.submit();">';
print '<option value="">- Semua -</option>';
print '<option value="0" ' . ($jenis == "0" ? 'selected' : '') . '>Kredit</option>';
print '<option value="1" ' . ($jenis == "1" ? 'selected' : '') . '>Bukan Kredit</option>';
print '</select>&nbsp;';

print '';
if ($filter == 3) {
    print '&nbsp;&nbsp;Status Login Sistem
				<select name="active" class="form-select-xs" onchange="document.MyForm.submit();">';
    for ($i = 0; $i < count($activeList); $i++) {
        if ($activeVal[$i] < 4) {
            print '	<option value="' . $activeVal[$i] . '" ';
            if ($active == $activeVal[$i]) print ' selected';
            print '>' . $activeList[$i];
        }
    }
    if (get_session("Cookie_groupID") <> 2) {
        //print '	</select>&nbsp;&nbsp;<input type="hidden" class="btn btn-primary btn-sm" value="Ubah" onClick="ITRActionButtonClickStatus(\'ubah\');">';
    } else {
        print '	</select>&nbsp;&nbsp;<input type="button" class="btn btn-primary btn-sm" value="Ubah" onClick="ITRActionButtonClickStatus(\'ubah\');">';
    }
} else {

    if (($IDName == 'superadmin') or ($IDName == 'admin') or get_session("Cookie_groupID") == '2') {
        print '&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-primary btn-sm" value="Kemaskini" onClick="ITRActionButtonClickStatus2(\'ubah\');">';
    }
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
						<td nowrap align="center"><b>Caj</b></td>
						<!--td nowrap align="center"><b>Tarikh Daftar</b></td-->
						<td nowrap align="center"><b>Tarikh SST Diterima</b></td>
                        <td nowrap align="center"><b>Tarikh Langganan</b></td>
						<td nowrap align="center"><b>Tamat Langganan</b></td>
                        <td nowrap align="center"><b>Bil Hari Untuk Tamat</b></td>
					</tr>';
    while (!$GetMember->EOF && $cnt <= $pg) {
        $status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));

        $approvedDate = $GetMember->fields(approvedDate);
        if ($approvedDate === null || strtotime($approvedDate) === false) {
            $approvedDate = 'Tiada Tarikh';
        } else {
            $approvedDate = date('Y-m-d', strtotime($approvedDate));
        }

        $langgananDate = $GetMember->fields(langgananDate);
        if ($langgananDate === null || strtotime($langgananDate) === false) {
            $langgananDate = 'Tiada Tarikh';
        } else {
            $langgananDate = date('Y-m-d', strtotime($langgananDate));
        }

        $tempohDate = $GetMember->fields(tempohDate);
        if ($tempohDate === null || strtotime($tempohDate) === false) {
            $tempohDate = 'Tiada Tarikh';
        } else {
            $tempohDate = date('Y-m-d', strtotime($tempohDate));
        }

        $colorStatus = "Data";
        if ($status == 1) $colorStatus = "greenText";
        if ($status == 3) $colorStatus = "redText";

        $MSS = dlookup("userdetails", "statusMSS", "userID=" . tosql($GetMember->fields(userID), "Text"));
        $jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));

        //find permohonan duration

        $datetime1 = strtotime($GetMember->fields(applyDate));
        $datetime2 = strtotime($GetMember->fields(approvedDate));

        $secs = $datetime2 - $datetime1; //differences in seconds 
        $duration = (int) ($secs / 86400); //convert to days 

        $today = date("Y-m-d H:i:s");
        $todayTimestamp = strtotime($today);
        $tempohDateTimestamp = strtotime($tempohDate);
        $diffInSeconds = abs($todayTimestamp - $tempohDateTimestamp);
        $diffInDays = $diffInSeconds / (60 * 60 * 24);
        $diffInDays = round($diffInDays);

        print '<tr>
						<td class="Data" align="right">' . $bil . '&nbsp;</td>
						<td class="Data" style="text-transform:uppercase" >';
        if (get_session("Cookie_groupID") <> 2) {
            //print '<input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
        } else {
            print '<input type="checkbox" class="form-check-input" name="pk[]" value="' . tohtml($GetMember->fields(userID)) . '">';
        }
        print '	<a class="text-danger" href="' . $sFileRef . '&pk=' . tohtml($GetMember->fields(userID)) . '">
							' . $GetMember->fields(kopNum) . ' -
							' . $GetMember->fields(name) . '</a></td>	
						<td class="Data" align="center">&nbsp;' . $GetMember->fields(loginID) . '</td>';

        if ($GetMember->fields(cajID) == NULL) {
            print '<td align="center" class="Data">-</td>';
        } else {
            if ($GetMember->fields(cajID) == 0) {
                print '  <td class="Data" align="center"><i class="mdi mdi-close text-danger" style="font-size: 24px;"></i></td>';
            } else {
                print '  <td class="Data" align="center"><i class="mdi mdi-check text-primary" style="font-size: 24px;"></i></td>';
            }
        }
        print '
                        <td class="Data" align="center">&nbsp;' . $approvedDate . '</td>
                        <td class="Data" align="center">&nbsp;' . $langgananDate . '</td>
                        <td class="Data" align="center">&nbsp;' . $tempohDate . '</td>
						<td class="Data" align="center">' . $diffInDays . '</td>
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
        for ($i = 1; $i <= $numPage; $i++) {
            print '<A class="text-danger" href="' . $sFileName . '&StartRec=' . (($i * $pg) + 1 - $pg) . '&pg=' . $pg . '&q=' . $q . '&by=' . $by . '&dept=' . $dept . '&filter=' . $filter . '">';
            print '<b><u>' . (($i * $pg) - $pg + 1) . '-' . ($i * $pg) . '</u></b></a> &nbsp; &nbsp;';
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
    if ($q == '') {
        print '<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk ' . $title . '  -</b><hr size="1"></td></tr>';
    } else {
        print '<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "' . $q . '" tidak jumpa  -</b><hr size="1"></td></tr>';
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
				  window.location.href = "?vw=langganEdit&pk=" + pkValue;
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
	          window.location.href ="?vw=memberAktif&mn=905&pk=" + strStatus;
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
				e.dept.value = "' . $dept . '";
				e.by.value = "' . $by . '";
				e.q.value = "' . $q . '";
	            e.submit();
	          }
			}
		}
	}


</script>';
