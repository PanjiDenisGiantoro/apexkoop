<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberStmt.php
*          Date 		: 	15/6/2006
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($dept))		$dept="";
if (!isset($mth)) 		$mth=date("n");                 		
if (!isset($yr)) 		$yr=date("Y");
if (!isset($mm))		$mm=date("m");
if (!isset($yy))		$yy=date("Y");

include("header.php");	
include("koperasiQry.php");	
include("koperasiList.php");	
date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session("Cookie_groupID") <> 0 AND get_session("Cookie_groupID") <> 1 
AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4  
OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$sFileName = "?vw=memberStmt&mn=$mn"; 
$sFileRef  = "?vw=memberStmt&mn=$mn";
$title     = "Senarai Penyata";

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

$sSQL = "";
$sWhere = "b.category = 'AC'";

if ($q !== "") {
    if ($by == 1) {
        $sWhere .= " AND b.memberID LIKE '%" . tosql($q, "Text") . "%'";            
    } elseif ($by == 2) {
        $sWhere .= " AND a.name LIKE '%" . tosql($q, "Text") . "%'";
    } elseif ($by == 8) {
        $sWhere .= " AND a.loginID LIKE '%" . tosql($q, "Text") . "%'";        
    }
}

$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT b.*, 
                 (SELECT SUM(pymtAmt) 
                  FROM transactionacc 
                  WHERE docNo LIKE 'RV%' AND pymtRefer = b.ID) AS dahBayar,
                 SUM(a.pymtAmt) AS invois 
          FROM generalacc b 
          LEFT JOIN transactionacc a ON a.pymtRefer = b.ID 
          AND a.docNo LIKE 'INV%' AND a.addminus = 0" . 
          $sWhere . 
          " GROUP BY b.ID 
          ORDER BY CAST(b.ID AS SIGNED INTEGER) DESC";

$GetMember = &$conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action='.$sFileName.' method="post">
<input type="hidden" name="action">
<input type="hidden" name="StartRec" value="'.$StartRec.'">
<input type="hidden" name="by" value="'.$by.'">
<h5 class="card-title">'.strtoupper($title).' &nbsp;</h5>';
if (get_session("Cookie_groupID") > 0) {
    $opt = array(1,2,8);
    carianheader($by, $opt, $dept, $deptList,$deptVal);
}
// echo '
// <table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">';

if (get_session("Cookie_groupID") > 0) {
/* print '<tr valign="top" class="Header"><td align="left" >
Carian melalui <select name="by" class="Data">'; 
if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Koperasi</option>'; 	else print '<option value="2">Nama Koperasi</option>';				
if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';							
print '</select>
		<input type="text" name="q" value="" maxlength="50" size="30" class="Data">
 		<input type="submit" class="but" value="Cari">&nbsp;&nbsp;&nbsp;		
		Zon
		<select name="dept" class="Data" onchange="document.MyForm.submit();">
		<option value="">- Semua -';
		for ($i = 0; $i < count($deptList); $i++) {
			print '	<option value="'.$deptVal[$i].'" ';
			if ($dept == $deptVal[$i]) print ' selected';
			print '>'.$deptList[$i];
}
print '</select></td></tr>'; */
print'<tr valign="top">
    <td align="left">
        <table cellpadding="3">
	<tr>
	<td class="textFont">Pilihan Bulan/Tahun</td>
	<td class="textFont">
	Bulan    
			<select name="mm" class="form-select-sm" onchange="document.MyForm.submit();">
			<option value="ALL"';
				if ($mm == "ALL") print 'selected';
				for ($j = 1; $j < 13; $j++) {
				print '	<option value="'.$j.'"';
				if ($mm == $j) print 'selected';
					print '>'.$j;
						}
				print '</select>
				Tahun  
				<select name="yy" class="form-select-sm" onchange="document.MyForm.submit();">';
				for ($j = 1989; $j <= 2079; $j++) {
					print '	<option value="'.$j.'"';
					if ($yy == $j) print 'selected';
					print '>'.$j;
				}
				print '</select></td>
				</tr> 

				   
				<tr>
					<td class="textFont">Penyata Bayaran Pakej</td>
					<td class="textFont"> 
			        <input type="button" class="btn btn-sm btn-secondary" value="Bulanan" onClick="ITRActionButtonClick(\'feesMonthly\');" style="width:100px;">
					<input type="button" class="btn btn-sm btn-secondary" value="Tahunan" onClick="ITRActionButtonClick(\'feesYearly\');" style="width:100px;">
					<input type="button" class="btn btn-sm btn-secondary" value="Keseluruhan" onClick="ITRActionButtonClick(\'feesYearlyAll\');" style="width:100px;">            
					</td> 
				</tr> 
				<tr>
				<!-- <td class="textFont">Penyata Syer</td>
					<td class="textFont">
			        <input type="button" class="btn btn-sm btn-secondary" value="Bulanan" onClick="ITRActionButtonClick(\'shareMonthly\');" style="width:100px;">            
	   			    <input type="button" class="btn btn-sm btn-secondary" value="Tahunan" onClick="ITRActionButtonClick(\'shareYearly\');" style="width:100px;">            
					</td> -->
				</tr>
				<tr>
					<td class="textFont">Penyata Urusniaga</td>
					<td class="textFont">
	    		    <input type="button" class="btn btn-sm btn-secondary" value="Bulanan" onClick="ITRActionButtonClick(\'memberMonthly\');" style="width:100px;">            
	        		<input type="button" class="btn btn-sm btn-secondary" value="Tahunan" onClick="ITRActionButtonClick(\'memberYearly\');" style="width:100px;">            
					</td>
				</tr>
				

			</table>
		</td>
	</tr>';
if ($q == "" AND $dept == "ALL") {
	print '		
	<tr><td	class="Label" align="center" height=50 valign=middle>
		<hr size="1"><b>- Sila masukkan No / Nama Koperasi ATAU pilih Jabatan  -</b><hr size="1">
	</td></tr>';
} else {					
	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;

		print '
		<tr valign="top" class="textFont">
			<td>
				<table width="100%">
					<tr>
						<td  class="textFont">&nbsp;</td>
						<td align="right" class="textFont">';
                                                                                                                echo papar_ms($pg);
                                                                                        print '</td>
					</tr>
				</table>
			</td>
		</tr>';

print '  <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					
					<tr class="table-danger">
						<td nowrap rowspan="1" height="20">&nbsp;</td>
						<td nowrap><b>ID-Nama Penghutang</b></td>
						<td nowrap align="left"><b>Kod</b></td>
						<td nowrap colspan="2" align="left"><b>No. Telefon</b></td>
						<td nowrap align="right">&nbsp;<b>Jumlah Invois (RM)</b></td>
						<td nowrap align="right">&nbsp;<b>Jumlah Bayaran (RM)</b></td>
						<!-- <td nowrap align="center">&nbsp;<b>Syer</b></td> -->
					</tr>';	
		
		while (!$GetMember->EOF && $cnt <= $pg) {
			
			print ' <tr>
				<td class="Data" align="center">' . $bil . '</td>
				<td class="Data">
				<input type="checkbox" name="pk[]" class="form-check-input" value="' . tohtml($GetMember->fields(ID)) . ' " ' . $objchk . '>
				<a href="' . $sFileRef . '&ID=' . tohtml($GetMember->fields(ID)) . '">
				<span class="text-danger">' . $GetMember->fields(ID) . ' - ' . $GetMember->fields(name) . '</span>
				</a>
				</td>
				<td class="Data" align="left">' . $GetMember->fields(code) . '</td>						
				<td class="Data" colspan="2" align="left">' . $GetMember->fields(b_contact) . '</td>						
				<td class="Data" align="right">' . number_format($GetMember->fields(invois), 2). '</td>
				<td class="Data" align="right">' . number_format($GetMember->fields(dahBayar), 2). '</td>
			</tr>';
			$cnt++;
			$bil++;
			$GetMember->MoveNext();
		}
		$GetMember->Close();

		print '</table></td></tr><tr><td>';
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
   					print '<A href="' . $sFileName . '&StartRec=' . (($i * $pg) + 1 - $pg) . '&pg=' . $pg . '&q=' . $q . '&by=' . $by . '&dept=' . $dept . '">';
    				print '<span class="text-danger"><b><u>' . (($i * $pg) - $pg + 1) . '-' . ($i * $pg) . '</u></b></span></a> &nbsp; &nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>';
	} else {
		if ($q == "") {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	} // end of ($GetMember->RowCount() <> 0)
} // end of ($q == "" AND $dept == "")

}else{
	
include("memberStmtUser.php");	

/*
	<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'feesMonthly\')">Penyata Yuran Bulanan</a>
	<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'shareMonthly\')">Penyata Syer Bulanan</a>
	<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'memberMonthly\')">Penyata Urusniaga Bulanan</a>
*/

print '
	<tr>
		<td class="Label" valign="top">
		<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'feesYearly\')">Penyata Yuran Tahunan</a>
		</td>
	</tr>
    ';

print '
	<tr>
		<td class="Label" valign="top">
		<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'shareYearly\')">Penyata Syer Tahunan</a>
		</td>
	</tr>
	';

print '
	<tr>
		<td class="Label" valign="top">
		<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'loanUserYearly\')">Penyata Pembiayaan Tahunan</a>
		</td>
	</tr>
	';

print '
	<tr>
		<td class="Label" valign="top">
		<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'memberYearly\')">Penyata Urusniaga Tahunan</a>
		</td>
	</tr>
	';

print '
	<tr>
		<td class="Label" valign="top">
		<li id="print" class="textFont">&nbsp;&nbsp;<a href="#" onclick="selectPenyata(\'memberPenyataYearly\')">Penyata Tahunan Anggota</a>
		</td>
	</tr>';

print '
	<tr>
		<td class="Label" valign="top">
		<p class="textFont">Sila UNBLOCK POPUP di Chrome atau Opera terlebih dahulu.
		</td>
	</tr>';
}
print ' 
</table>
</form>';

include("footer.php");	

print '
<script language="JavaScript">
	function ITRActionButtonClick(rpt) {
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
				alert(\'Sila pilih satu anggota sahaja \');
			} else {
				if (rpt == "memberMonthly" )  {
					url = "memberMonthly.php?yrmth='.$yy.$mm.'&id=" + pk;
				} else if (rpt == "memberYearly" )  {
					url = "memberYearly.php?xt=9&yr='.$yy.'&id=" + pk;
				} else if (rpt == "memberLoan" )  {
					url = "memberLoan.php?pk=" + pk;
				} else if (rpt == "loanUserYearly" )  {
					url = "loanUserYearly.php?xt=9&pk="+ pk +"&yr='.$yy.'";
				} else if (rpt == "shareMonthly" )  {
					url = "shareMonthly.php?yrmth='.$yy.$mm.'&id=" + pk;
				} else if (rpt == "shareYearly" )  {
					url = "shareYearly.php?xt=9&yr='.$yy.'&id=" + pk;
				} else if (rpt == "feesMonthly" )  {
					url = "feesMonthly.php?yrmth='.$yy.$mm.'&id=" + pk;
				} else if (rpt == "feesYearly" )  {
					url = "feesYearly.php?xt=9&yr='.$yy.'&id=" + pk;
				}else if (rpt == "memberPenyataYearly" )  {
					url = "memberPenyataYearly.php?xt=9&pk="+ pk +"&yr='.$yy.'&id=" + pk;
				} else if (rpt == "feesYearlyAll" )  {
					url = "feesYearlyAll.php?yr='.$yy.'&id=" + pk;
				}
				
				window.open (url, "mthyear","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
			}
		}
	}

	function selectPenyata(rpt) {
		if (rpt == "feesMonthly" || rpt == "shareMonthly" || rpt == "memberMonthly") {
			url = "selMthYear.php?rpt="+rpt+"&id='.$ID.'";
		} else if (rpt == "rptG2Dept") {
			url = "selYear.php?rpt="+rpt+"&id='.$ID.'";
		} else {
			url = "selYear.php?rpt="+rpt+"&id='.$ID.'";
		}
		
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}

	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "'.$sFileName.'&StartRec=1&pg=" + c.options[c.selectedIndex].value + "&dept='.$dept.'";
	}

	function selectPop(rpt) {
		if (rpt == "greImportBul") {
			url = "selMthYear.php?rpt="+rpt+"&id=ALL";
		} else {
			url = "selYear.php?rpt="+rpt+"&id=ALL";
		}
		window.open(url ,"pop","top=100,left=100,width=500,height=100,scrollbars=no,resizable=no,toolbars=no,location=no,menubar=no");		
	}	  

	function ITRActionButtonClick_old(rpt) {
		if (rpt == "BulananU") {
			url = "memberMonthly.php?yrmth='.sprintf("%04d%02d",$yrS,$mthS).'&id='.$pk[0].'";
		} else if (rpt == "TahunanU") {
			url = "memberYearly.php?yr='.$yrS.'&id='.$pk[0].'";
		} else if (rpt == "SenaraiP") {
			url = "memberLoan.php?pk='.$pk[0].'";
		} else if (rpt == "TahunanP") {
			url = "loanUserYearly.php?pk='.$pk[0].'&yr='.$yrS.'";
		} else if (rpt == "BulananS") {
			url = "shareMonthly.php?yrmth='.sprintf("%04d%02d",$yrS,$mthS).'&id='.$pk[0].'";
		} else if (rpt == "TahunanS") {
			url = "shareYearly.php?yr='.$yrS.'&id='.$pk[0].'";
		} else if (rpt == "TahunanY") {
			url = "feesYearly.php?yr='.$yrS.'&id='.$pk[0].'";
		} else if (rpt == "PenyataTahunan") {
			url = "memberPenyataYearly.php?pk='.$pk[0].'&yr='.$yrS.'&id='.$pk[0].'";
		} else if (rpt == "Import") {
			url = "greImportPot.php?yrmth='.sprintf("%04d%02d",$yrS,$mthS).'&id='.$pk[0].'";
		} else if (rpt == "Eksport") {
			url = "greEksportPot.php?yrmth='.sprintf("%04d%02d",$yrS,$mthS).'&id='.$pk[0].'";
		}
		window.open (rptURL, "mthyear","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
	}
</script>';
?>