<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	ACCGeneralejer.php
*          Date 		: 	04/8/2006
*********************************************************************************/
if (!isset($mm))	$mm="ALL";//date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);
date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 100;
if (!isset($q))			$q="";
if (!isset($code))		$code="ALL";
if (!isset($filter))	$filter="0";

include("header.php");	
include("koperasiQry.php");	

if (get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';//dari mana file ni
}

$sFileName = "?vw=ACCGeneralejer&mn=$mn";//file name
$sFileRef2  = "?vw=ACCSingleEntry&mn=$mn";
$sFileRef3  = "?vw=ACCbaucerpembayaran&mn=$mn";
$sFileRef4  = "?vw=ACCresitpembayaran&mn=$mn";// file ni pergi mane
$sFileRef5  = "?vw=ACCinvoicedebtor&mn=$mn";// file ni pergi mane
$sFileRef6  = "?vw=ACCDebtorPayment&mn=$mn";// file ni pergi mane
$sFileRef8  = "?vw=ACCpurchaseInvoice&mn=$mn";// file ni pergi mane
$sFileRef9  = "?vw=ACCbillpembayaran&mn=$mn";// file ni pergi mane
$sFileRef10  = "?vw=resit&mn=$mn";// file ni pergi mane
$sFileRef12  = "?vw=journals&mn=$mn";// file ni pergi mane
$title     =  "General Lejer";//Title 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sSQL = "";
	$sWhere = " A.docID NOT IN (0,15) AND YEAR(A.tarikh_doc) =".$yy;
	
	if ($q <> "") 	{
		if ($by == 1) {
			$sWhere .= " AND A.docNo like '%".$q."%'";		
		} else if ($by == 2) {
			$sWhere .= " AND A.batchNo = B.ID";
			$sWhere .= " AND B.name like '%".$q."%'";				
		} else if ($by == 3) {
			$sWhere .= " AND A.deductID = B.ID";
			$sWhere .= " AND B.name like '%".$q."%'";		
		}
	}

	$sWhere = " WHERE (".$sWhere.")";
	
	if ($q <> "") 	{
		if ($by == 2 OR $by == 3) {
			$sSQL = "SELECT	A.*,B.* FROM transactionacc A, generalacc B";
		} else if ($by == 1) {
			$sSQL = "SELECT	DISTINCT A.* FROM transactionacc A";
		}
	} else {
		$sSQL = "SELECT	*,ID as transID FROM transactionacc A ";
	}
	if($mm <> "ALL") $sWhere .= " AND month( A.tarikh_doc ) =" .$mm;
	$sSQL = $sSQL.$sWhere. ' ORDER BY A.docNo ASC,A.tarikh_doc';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$GetBaucers = &$conn->Execute($sSQL);
$GetBaucers->Move($StartRec-1);




$batchName = dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(batchNo), "Text"));
$glname = dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(deductID), "Text"));

$TotalRec = $GetBaucers->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '<div class="table-responsive">
<form name="MyForm" action='.$sFileName.' method="post">
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
			for ($j = 2005; $j <= 2030; $j++) {
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
if ($by == 1)	print '<option value="1" selected>No. Rujukan</option>'; 	else print '<option value="1">No. Rujukan</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Batch</option>'; 	else print '<option value="2">Nama Batch</option>';					
if ($by == 3)	print '<option value="3" selected>Nama Akaun</option>'; 	else print '<option value="3">Nama Akaun</option>';				
				
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
print '</select> &nbsp;&nbsp;
</div>
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
';
	if ($GetBaucers->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
		<tr valign="top" class="textFont">
			<td>
				<table width="100%">
					<tr>
						
						<td align="right" class="textFont"><br>';
                                                                                            echo papar_ms($pg);
                                                                    print ' </td>
					</tr>
				</table>
			</td>
		</tr>';
		print '
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-primary">
						<td nowrap align="center"><b>Bil</b></td>
						<td nowrap><b>No. Rujukan</b></td>
						<td nowrap align="center"><b>Nama Batch</b></td>
						<td nowrap align="center"><b>Tarikh</b></td>
						<td nowrap><b>Akaun GL</b></td>
						<td nowrap align ="right"><b>Debit (RM)</b></td>
						<td nowrap align ="right"><b>Kredit (RM)</b></td>						
					</tr>';

		$DRTotal = 0;
		$CRTotal = 0;
		while (!$GetBaucers->EOF && $cnt <= $pg) {
			$jumlah = 0;
			$tarikh_baucer = toDate("d/m/y",$GetBaucers->fields(tarikh_doc));
			
		$sSQL2 = "SELECT g_lockstat FROM generalacc WHERE ID = ".$GetBaucers->fields(batchNo)." ORDER BY ID";
		$rsDetail =&$conn->Execute($sSQL2);

		if ($GetBaucers->fields(batchNo) == 0){
			$batchName = "TIADA BATCH";
		}else{
			$batchName = dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(batchNo), "Text"));
		}

			$glname = dlookup("generalacc", "name", "ID=" . tosql($GetBaucers->fields(deductID), "Text"));
			$glname1 = dlookup("general", "name", "ID=" . tosql($GetBaucers->fields(deductID), "Text"));

			print ' <tr>
						<td class="Data" align="center">'.$bil.'</td>';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 2){//SINGLE ENTRY

	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
			<a href="'.$sFileRef2.'&action=view&SENO='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'
			</td>';
		}


	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 3){//BAUCER

	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef3.'&action=view&no_baucer='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'
			</td>';
	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
		}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 4){//RESIT

	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef4.'&action=view&no_resit='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'
			</td>';
	}
	
	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 5){//INVOICE
	
	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef5.'&action=view&invNo='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 6){//BAYAR INVOICE
	
	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef6.'&action=view&RVNo='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
//////////////////////////////////////////////////////////////////DEFAULT/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 8){//PURCHASE INVOICE
	
	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef8.'&action=view&PINo='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 7){//PEMBAYARAN BIL
	
	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef9.'&action=view&no_bill='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($GetBaucers->fields(docID) == 10){//RESIT ANGGOTA
	
	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {
	print 	'<td class="Data">
				<a href="'.$sFileRef10.'&action=view&no_resit='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
	}
	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">;'.$tarikh_baucer.'</td>';

	print'	<td class="Data">(ANGGOTA)&nbsp;'.$glname.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($GetBaucers->fields(docID) == 11){//JURNAL ANGGOTA
	
/*	if ($rsDetail->fields(g_lockstat) == 1){
	print 	'<td class="Data">&nbsp;'.$GetBaucers->fields(docNo).'
			</td>';
		}
	else {*/
	
	print 	'<td class="Data">
				<a href="'.$sFileRef12.'&action=view&no_jurnal='.tohtml($GetBaucers->fields(docNo)).'&yy='.$yy.'&mm='.$mm.'">
				'.$GetBaucers->fields(docNo).'</td>';
		//	}

	print'	<td class="Data" align="center">'.$batchName.'</td>';
	print'	<td class="Data" align="center">'.$tarikh_baucer.'</td>';

	print'	<td class="Data">(ANGGOTA)&nbsp;'.$glname1.'</td>';

	if ($GetBaucers->fields(addminus) == 0){
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';	
		print'	<td class="Data" align ="right">0.00</td>';
	}
	if ($GetBaucers->fields(addminus) == 1){
		print'	<td class="Data" align ="right">0.00</td>';
		print'	<td class="Data" align ="right">'.$GetBaucers->fields(pymtAmt).'</td>';
	}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	print'	</tr>';
	$cnt++;
	$bil++;
			$GetBaucers->MoveNext();
		}
		$GetBaucers->Close();

		print '	</table>
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
			<td class="textFont">Jumlah Rujukan : <b>' . $GetBaucers->RowCount() . '</b></td>
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
