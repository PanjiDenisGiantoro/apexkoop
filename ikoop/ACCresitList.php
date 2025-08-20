<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	resitList.php
*          Date 		: 	04/8/2006
*********************************************************************************/
if (!isset($mm))	$mm="ALL";//date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($jenis_cari))	$jenis_cari="";
if (!isset($code))		$code="ALL";
if (!isset($filter))	$filter="0";

include("header.php");	
include("koperasiQry.php");	
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$sFileName = "?vw=ACCresitList&mn=$mn";
$sFileRef  = "?vw=ACCresitpembayaran&mn=$mn";
$title     =  "Resit Akaun";

$IDName = get_session("Cookie_userName");
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$sWhere = "no_resit=" . tosql($pk[$i], "Text");
		$sSQL = "DELETE FROM resitacc WHERE " . $sWhere;
		//print $sSQL.'<br />';
		$rs = &$conn->Execute($sSQL);
		$sWhere = "docNo=" . tosql($pk[$i], "Text"); //new 
		$sSQL = "DELETE FROM transactionacc WHERE " . $sWhere;
		//$sSQL = "DELETE FROM baucer_keterangan WHERE " . $sWhere; move
		//print $sSQL.'<br />';
		$rs = &$conn->Execute($sSQL);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

//--- Prepare deduct list
$deductList = Array();
$deductVal  = Array();
$sSQL = "	SELECT B.ID, B.code , B.name 
			FROM transaction A, general B
			WHERE A.deductID= B.ID
			AND   A.yrmth = " .tosql($yymm,"Text")."	
			AND   A.status = " .tosql($filter,"Number")."	
			GROUP BY A.deductID";
$GetDeduct = &$conn->Execute($sSQL);
if ($GetDeduct->RowCount() <> 0){
	while (!$GetDeduct->EOF) {
		array_push ($deductList, $GetDeduct->fields(code).' - '.$GetDeduct->fields(name));
		array_push ($deductVal, $GetDeduct->fields(ID));
		$GetDeduct->MoveNext();
	}
}		

	if ($q <> "") 	{
		if ($by == 1) {
			$getQ .= " AND a.keterangan like '%" . $q. "%'";		
		} else if ($by == 2) {
			$getQ .= " AND a.no_resit like '%" . $q. "%'";
		} 
	}
$sSQL = "SELECT a.*,c.* FROM  resitacc a, transactionacc c WHERE a.no_resit=c.docNo AND c.addminus IN (0) AND year(a.tarikh_resit) = " . $yy . $getQ ;

if($mm <> "ALL") $sSQL .= " AND month(a.tarikh_resit) =" .$mm;
$sSQL .= $getQ." GROUP BY a.no_resit ORDER BY a.no_resit DESC";
$GetReceipts 	= &$conn->Execute($sSQL);
$GetReceipts->Move($StartRec-1);

$TotalRec 		= $GetReceipts->RowCount();
$TotalPage		=  ($TotalRec/$pg);
$jenisList_cari = array('No Anggota','Nama');
$jenisVal_cari 	= array(1, 2);
print '<div class="table-responsive">
<form name="MyForm" action=' .$sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="code" value="'.$code.'">
<input type="hidden" name="filter" value="'.$filter.'">
<h5 class="card-title">'.strtoupper($title).' &nbsp;</h5>
    <div clas="row">
    Bulan  
			<select name="mm" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="ALL"';
				if ($mm == "ALL") print 'selected';
				print '>- Semua -';
			for ($j = 1; $j < 13; $j++) {
				print '	<option value="'.$j.'"';
				if ($mm == $j) print 'selected';
				print '>'.$j;
			} 
print '		</select>
			Tahun 
			<select name="yy" class="form-select-sm" onchange="document.MyForm.submit();">';
			for ($j = 1989; $j <= 2079; $j++) {
				print '	<option value="'.$j.'"';
				if ($yy == $j) print 'selected';
				print '>'.$j;
			}
print '		</select>
			<input type="submit" name="action1" value="Capai" class="btn btn-sm btn-secondary">
    </div><br/>
    <div clas="row">
    Carian Melalui
				<select name="by" class="form-select-sm">'; 
if ($by == 2)	print '<option value="2" selected>No. Resit</option>'; 	else print '<option value="2">No. Resit</option>';				
if ($by == 1)	print '<option value="1" selected>Keterangan</option>'; else print '<option value="1">Keterangan</option>';				
				
print '		</select>
				<input type="text" name="q" value="" maxlength="50" size="30" class="form-control-sm">
           	 <input type="submit" class="btn btn-sm btn-secondary" value="Cari">
			&nbsp;&nbsp;			
			<!--Kod Potongan
			<select name="code" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="ALL">- Semua -';
			for ($i = 0; $i < count($deductList); $i++) {
				print '	<option value="'.$deductVal[$i].'" ';
				if ($code == $deductVal[$i]) print ' selected';
				print '>'.$deductList[$i];
			}
print '		</select>&nbsp;
			Status
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			for ($i = 0; $i < count($statusList); $i++) {
				if ($statusVal[$i] < 3) {
					print '	<option value="'.$statusVal[$i].'" ';
					if ($filter == $statusVal[$i]) print ' selected';
					print '>'.$statusList[$i];
				}
			}
	print '	</select-->&nbsp;&nbsp;';	

print '</select> &nbsp;&nbsp;';
	if(get_session("Cookie_groupID") == '2'){
		print'<input type="button" class="btn btn-sm btn-primary" value="Tambah" onClick="location.href=\''.$sFileRef.'&action=new&jenis='.$jenis.'\';">';
	}
			
			if (($IDName == 'admin') OR ($IDName == 'superadmin') OR get_session("Cookie_groupID") == '2'){
print '&nbsp; <input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');"> ';

}
print '
			<!--input type="button" class="but" value="Status" onClick="ITRActionButtonStatus();"-->
		
    </div>
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
';
	if ($GetReceipts->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
		<tr valign="top" class="textFont">
			<td>
				<table width="100%"><br>
					<tr>
						<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>
						<td align="right" class="textFont">';
                                                                                            echo papar_ms($pg);
                                                                    print '</td>
					</tr>
				</table>
			</td>
		</tr>
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-primary">
						<td nowrap align="center"><b>Bil</b></td>
						<td nowrap align="left"><b>No. Resit</b></td>
						<td nowrap align="center"><b>Tarikh</b></td>

						<td nowrap align="left"><b>Bank</b></td>
						<td nowrap align="left"><b>Keterangan</b></td>
						<td nowrap align="right"><b>Jumlah (RM)</b></td>

						
					</tr>';	
		$DRTotal = 0;
		$CRTotal = 0;
		while (!$GetReceipts->EOF && $cnt <= $pg) {
			$status = $GetReceipts->fields(status);
			$colorStatus = "Data";
			if ($status == 1) $colorStatus = "greenText";
			if ($status == 2) $colorStatus = "redText";
			
			$totalAmt = $GetReceipts->fields(pymtAmt) + $GetReceipts->fields(cajAmt);
			
			if ($GetReceipts->fields(addminus) == 0) {
				$addMinus = 'Debit';
				$DRTotal += $totalAmt;
			} else {
				$addMinus = 'Kredit';
				$CRTotal += $totalAmt;
			}
			 $jumlah = 0;
			$tarikh_resit = toDate("d/m/y",$GetReceipts->fields(tarikh_resit));
			$namabatch = dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(batchNo), "Text"));
			$bank = dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(kod_bank), "Text"));
			//$addminus = $GetLoan->fields(addminus);

			$akaun = dlookup("generalacc", "name", "ID=" . tosql($GetReceipts->fields(deductID), "Text"));
			$akaunno = dlookup("generalacc", "code", "ID=" . tosql($GetReceipts->fields(deductID), "Text"));

		$sSQL2 = "SELECT g_lockstat FROM generalacc WHERE ID = ".$GetReceipts->fields(batchNo)." ORDER BY ID";
		$rsDetail =&$conn->Execute($sSQL2);


		print ' <tr>
				<td class="Data" align="center">' . $bil . '</td>';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($rsDetail->fields(g_lockstat) == 1)
		{			
		
		print '
		<td class="Data"><input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetReceipts->fields(no_resit)).'">
		'.$GetReceipts->fields(no_resit).'</td>';
		
		}else {

		print '
		<td class="Data"><input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetReceipts->fields(no_resit)).'">
		<a href="'.$sFileRef.'&action=view&no_resit='.tohtml($GetReceipts->fields(no_resit)).'&yy='.$yy.'&mm='.$mm.'">'.$GetReceipts->fields(no_resit).'</td>';
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print '
		<td class="Data" align="center">'.$tarikh_resit.'</td>
		<td class="Data">'.$bank.'</td>';
		
		print'	<td class="Data">'.$GetReceipts->fields(keterangan).'</td>		
				<td class="Data" align="right">'.$GetReceipts->fields(pymtAmt).'</td>	';
			
		print'	</tr>';
		$cnt++;
		$bil++;
		$GetReceipts->MoveNext();
		}
		$GetReceipts->Close();

		print '	</table>
			</td>
		</tr>	
		<!--tr>
			<td class="textFont" align="right">
			<b>Debit&nbsp;:&nbsp;'.number_format($DRTotal, 2, '.', ',').'&nbsp;&nbsp;&nbsp;
			Kredit&nbsp;:&nbsp;'.number_format($CRTotal, 2, '.', ',').'&nbsp;&nbsp;&nbsp;</b>
			</td>
		</tr-->	
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
						if(is_int($i/10)) print '<br />';
						print '<A href="'.$sFileName.'&yy='.$yy.'&mm='.$mm.'&code='.$code.'&filter='.$filter.'&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a>&nbsp;&nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Baucer : <b>' . $GetReceipts->RowCount() . '</b></td>
		</tr>';
	} else {
		if ($q == "") {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.' Bagi Bulan/Tahun - '.$mm.'/'.$yy.' -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	}
print ' 
</table>
</form></div>';

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
