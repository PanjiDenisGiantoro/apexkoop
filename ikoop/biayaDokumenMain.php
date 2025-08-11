<?php
if($action<>'print') //$bgcolor = 'bgcolor="#f0f0f0"';
print	'<form name="MyForm" action='.$sFileName.' method=post>
		<table cellpadding="0" cellspacing="0" width="100%" align="center" '.$bgcolor.' class="table">

			<tr>
				<td valign="top" width="70%">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>Kepada</td>
							<td>&nbsp;:&nbsp;</td>
							<td>Komuniti Pembiayaan</td>
						</tr>
						<tr>
							<td>Nama Pemohon</td>
							<td>&nbsp;:&nbsp;</td>
							<td>'.$memberName.'</td>
						</tr>
						<tr>
							<td>Jenis Pembiayaan Dipohon</td>
							<td>&nbsp;:&nbsp;</td>
							<td>'.$loanType.'</td>
						</tr>
						<tr>
							<td>Jumlah</td>
							<td>&nbsp;:&nbsp;</td>
							<td>RM&nbsp;'.number_format($amtLoan,2).'</td>
						</tr>
					</table>
				</td>
				<td align="left" valign="top" width="30%">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>No.Anggota</td>
							<td>&nbsp;:&nbsp;</td>
							<td>'.$memberNo.'</td>
						</tr>
						<tr>
							<td>Tempoh</td>
							<td>&nbsp;:&nbsp;</td>
							<td>'.$loanPeriod.'&nbsp;Bulan</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan=2>&nbsp;</td></tr>';
print		'<tr>
				<td colspan="2"><table><tr>
				<td valign="top" width="60%">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3"><u><b><i>Keperluan Dokumen Permohonan Pembiayaan</i></b></u>&nbsp;</td>
						</tr>						
						<tr>
							<td valign="0">1.</td>
							<td>&nbsp;</td>
							<td>Salinan K.P (Depan & Belakang)</td>
						</tr>
						<tr>
							<td valign="0">2.</td>
							<td>&nbsp;</td>
							<td>Salinan Slip Gaji Terbaru</td>
						</tr>
						<tr>
							<td valign="0">3.</td>
							<td>&nbsp;</td>
							<td>Salinan Surat Pengesahan Jawatan <br>(untuk permohon yang pertama kali memohon pembiayaan)</td>
						</tr>
					</table>
				</td>

				
				<td align="center" valign="top" width="40%">
					<table cellpadding="0" cellspacing="0"><tr><td>
						<table cellpadding="0" cellspacing="0" border="1">
							<tr>
								<td align="center" width="80">Pemohon</td>
								<td align="center" width="80">Penjamin 1</td>
								<td align="center" width="80">Penjamin 2</td>
								<td align="center" width="80">Penjamin 3</td>
							</tr>';
$able = '';
if($chkgurrantor) $able= " disabled ";

$styleH1 = 'style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px" align="center"';
$styleH2 = 'style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px" align="center"';
$styleD1 = 'style="border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px" align="center"';
$styleD2 = 'style="border-bottom:#000000 solid 1px;border-right:#000000 solid 1px" align="center"';


if($action <> 'print') {

$style1 = $styleH1;
$style2 = $styleH2;
print '	
	<tr>
		<td '.$style1.'><input type="checkbox" class="form-check-input" name="a1" '.$a1.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="a2" '.$a2.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="a3" '.$a3.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="a4" '.$a4.' '.$able.'>&nbsp;</td>
	</tr>';

$style1 = $styleD1;
$style2 = $styleD2;
print '	
	<tr>
		<td '.$style1.'><input type="checkbox" class="form-check-input" name="b1" '.$b1.' >&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="b2" '.$b2.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="b3" '.$b3.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="b4" '.$b4.' '.$able.'>&nbsp;</td>
	</tr>';

print '	
	<tr>
		<td '.$style1.'><input type="checkbox" class="form-check-input" name="c1" '.$c1.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="c2" '.$c2.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="c3" '.$c3.' '.$able.'>&nbsp;</td>
		<td '.$style2.'><input type="checkbox" class="form-check-input" name="c4" '.$c4.' '.$able.'>&nbsp;</td>
	</tr>';

}else{

$style1 = $styleH1;
$style2 = $styleH2;
print '	
	<tr>
		<td '.$style1.'>'.($a1=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($a2=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($a3=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($a4=="checked"?"X":"").'&nbsp;</td>
	</tr>';

$style1 = $styleD1;
$style2 = $styleD2;
print '	
	<tr>
		<td '.$style1.'>'.($b1=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($b2=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($b3=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($b4=="checked"?"X":"").'&nbsp;</td>
	</tr>';

print '	
	<tr>
		<td '.$style1.'>'.($c1=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($c2=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($c3=="checked"?"X":"").'&nbsp;</td>
		<td '.$style2.'>'.($c4=="checked"?"X":"").'&nbsp;</td>
	</tr>';

}

				print '	</table>
					</td></tr></table>
				</td>
				<tr></table></td>
			</tr>';

if($pid1 && $pid){ 
	$app = dlookup("loans", "statuspID1", "loanID=" . tosql($pk, "Number"));
	if($app) 
		$approve1 = '<img src="images/sym-tick-red-bkrm-01.gif" height="20" width="20">'; 
	else 
		$approve1 = '<img src="images/delete.jpg" height="15" width="15">';

	$detailpid1 = $approve1.'<a href="biayaDetail.php?pk='.$pid1.'&id='.$pk.'"> Penjamin 1'.$pid1.'</a>'; 
}else $detailpid1 = 'Penjamin 1';


if($pid2 && $pid) { 
	$app = dlookup("loans", "statuspID2", "loanID=" . tosql($pk, "Number"));
	if($app)
		$approve2 = '<img src="images/sym-tick-red-bkrm-01.gif" height="20" width="20">';
	else 
		$approve2 = '<img src="images/delete.jpg" height="15" width="15">';

	$detailpid2 = $approve2.'<a href="biayaDetail.php?pk='.$pid2.'&id='.$pk.'">Penjamin 2'.$pid2.'</a>'; 
}else $detailpid2 = 'Penjamin 2';


if($pid3 && $pid) {
	$app = dlookup("loans", "statuspID3", "loanID=" . tosql($pk, "Number"));
	if($app) 
		$approve3 = '<img src="images/sym-tick-red-bkrm-01.gif" height="20" width="20">';
	else 
		$approve3 = '<img src="images/delete.jpg" height="15" width="15">';

	$detailpid3 = $approve3.'<a href="biayaDetail.php?pk='.$pid3.'&id='.$pk.'">Penjamin 3'.$pid3.'</a>';
} else $detailpid3 = 'Penjamin 3';


function  biayaPenjaminIcon($jaminID, $jaminStat, $loanID, $jaminNo){
global $pk;

	if($jaminID && $jaminStat) { 
		$jaminStatus = "statuspID".$jaminNo; //get which jaminan status in loans table
		$getjaminStatus = dlookup("loans", $jaminStatus, "loanID=" . tosql($loanID, "Number"));
		
		if($jaminNo == "1")	$jaminDate = "updatedDateJmn";
		else $jaminDate = "updatedDateJmn".$jaminNo;
			
		if($getjaminStatus == "1"){
			$approve = '<img src="images/sym-tick-red-bkrm-01.gif" height="20" width="20">';
			$dateJmn =  dlookup("loans", $jaminDate, "loanID=" . tosql($pk, "Number"));
			$dateJmnU = toDate("d/m/yy", $dateJmn);
		}
		else 
		{
			$approve = '<img src="images/delete.jpg" height="15" width="15">';
            $dateJmn =  dlookup("loans", $jaminDate, "loanID=" . tosql($pk, "Number"));
			$dateJmnU = toDate("d/m/yy", $dateJmn);
        }    
		
		$jaminDetail = $approve.'<a href="?vw=biayaDetail&pk='.$jaminID.'&id='.$pk.'">'.$jaminID.'</a><br>'.$dateJmnU.'<br><a href="?vw=biayaMohonJaminandel&pk='.$pk.'">Hapus Penjamin </a>'; 
	}elseif (!$jaminID) $jaminDetail = 'Penjamin '.$jaminNo;	
	else $jaminDetail = $jaminID;
	
	return $jaminDetail;
}

// functions var(id penjamin , status jaminan, current loan id, penjamin no)	
$detailpid1 = biayaPenjaminIcon($pid1, $pid, $pk, 1); 
$detailpid2 = biayaPenjaminIcon($pid2, $pid, $pk, 2);
$detailpid3 = biayaPenjaminIcon($pid3, $pid, $pk, 3); 

function writeDataVal($val){
print '
	<tr>
	<td>RM</td>
	<td align="right">&nbsp;'. formatVal($val).'</td>
	</tr>';
}

print 		'<tr><td class="padding1" colspan="2"><u><b><i>Semakan Kelayakan Permohonan & Penjamin-penjamin</i></b></u></td></tr>
			<tr>
				<td valign="top" width="100%" colspan="2">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td valign="0">1.</td>
							<td>&nbsp;</td>
							<td>Kelayakan Pemohon gaji pokok + elaun x 15</td>
							<td>&nbsp;</td>
							<td colspan="2" rowspan="3" align="left">
								<table cellpadding="0" cellspacing="0">';
									writeDataVal($totalA15);
									writeDataVal($totalFee);
									writeDataVal($total);
print '							</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Campur Yuran dan Syer Terkumpul</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Jumlah yang boleh dipohon</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td valign="0">2.</td>
							<td>&nbsp;</td> 
							<td>Kelayakan Penjamin-penjamin</td>
							<td >&nbsp;</td>
							<td colspan="2" rowspan="4" align="left">
								<table cellpadding="0" cellspacing="0">';
function penjaminDetail($detail1,$detail2,$detail3,$row){
$styleH1 = 'align="center" width="100" style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px"';
$styleH2 = 'align="center" width="100" style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px"';
$styleD1 = 'align="right" style="border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px"';
$styleD2 = ' align="right" style="border-bottom:#000000 solid 1px;border-right:#000000 solid 1px"';

if($row){
	$style1 = $styleH1;
	$style2 = $styleH2;
}else{
	$style1 = $styleD1;
	$style2 = $styleD2;
	$detail1 = formatRm($detail1);	
	$detail2 = formatRm($detail2);	
	$detail3 = formatRm($detail3);	
}

print '
	<tr>
		<td '.$style1.'>&nbsp;'.$detail1.'</td>
		<td '.$style2.'>&nbsp;'.$detail2.'</td>
		<td '.$style2.'>&nbsp;'.$detail3.'</td>
	</tr>';

}

function formatRm($val){
 return number_format($val,2);
}

function formatLength($val){
 return sprintf("%11s",$val);
}

function formatStr($val){
 return strval($val);
}

function formatVal($val){
 $val = formatRm($val);
 $val = formatStr($val);
 $vallen = 10 - strlen($val);
 if($vallen){
	for($i=0;$i<=$vallen;$i++){
		$prestr.= '&nbsp;';
	} 
 }
	return $prestr.$val;
//return writeVal($val);
}

function writeVal($val){
$str = '<table><tr><td width="20%" align="right">&nbsp;'.$val.'</td><td width="*">&nbsp;</td></tr></table>';
return $str;
}
									penjaminDetail($detailpid1,$detailpid2,$detailpid3,1);
									penjaminDetail($totalFeePA1,$totalFeePA2,$totalFeePA3,0);
									penjaminDetail($totalPB1,$totalPB2,$totalPB3,0);
									penjaminDetail($balPA1,$balPA2,$balPA3,0);

						print '
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>15 x gaji pokok + yuran terkumpul</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Tolak tanggungan sedia ada</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Baki tanggungan yang boleh dijamin</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td valign="0">3.</td>
							<td>&nbsp;</td>
							<td>Jika dijamin oleh 80% atas yuran terkumpul</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Jumlah yuran terkumpul</td>
							<td>&nbsp;</td>
							<td colspan="2" rowspan="5" align="left">
								<table cellpadding="0" cellspacing="0">';
									writeDataVal($jamin80yuran);
									writeDataVal($jaminTot);
									writeDataVal($biaya);
									writeDataVal($biayayuran);
									writeDataVal($biayaTot);
print '							</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- 80% adalah</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td valign="0">4.</td>
							<td>&nbsp;</td>
							<td>Jumlah pembiayaan dipohon</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Tolak 80% daripada yuran terkumpul pemohon</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- Jumlah yang perlu dijamin oleh penjamin</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>

						
						<tr>
							<td valign="0">5.</td>
							<td>&nbsp;</td>
							<td>Penyata gaji pemohon</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Jumlah gaji pokok + elaun rumah/sara hidup</td>
							<td>&nbsp;</td>
							<td colspan="2" rowspan="6" align="left">
								<table cellpadding="0" cellspacing="0">';
									writeDataVal($gajiTot);
									writeDataVal($gajiPot);
									writeDataVal($gajiPotB);
									writeDataVal($gajiBersih);
									writeDataVal($potBenar);
									writeDataVal($potBaru);
print '							</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Tolak potongan sedia ada</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Tolak potongan pembiayaan baru (pokok + untung)&nbsp;&nbsp;</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Baki gaji bersih</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td valign="0">6.</td>
							<td>&nbsp;</td>
							<td>% potongan dibenarkan <u>'.$psen.'</u></td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>Jumlah potongan baru</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						
						
						<tr>
							<td valign="0">7.</td>
							<td>&nbsp;</td>
							<td>Jika pembiayaan bertindih, dikenakan</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- jumlah asal pembiayaan lama</td>
							<td>&nbsp;</td>
							<td colspan="2" rowspan="4" align="left">
								<table cellpadding="0" cellspacing="0">';
									writeDataVal($btindih);
									writeDataVal($btindihUntung);
									writeDataVal($btindihCaj);
									writeDataVal($btindihBal);
print '							</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- sebulan keuntungan pembiayaan lama</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- bayaran caj perkhidmatan</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- baki pembiayaan lama</td>
							<td>&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td valign="0">8.</td>
							<td>&nbsp;</td>
							<td>Potongan yuran bulanan anggota</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td>- sedia ada</td>
							<td>&nbsp;</td>
							<td>RM&nbsp;'.formatVal($yuranSedia).'</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
							<td colspan="4">
								<table cellpadding="0" cellspacing="0">';
function biayaYuran($index,$name,$check,$action,$row=0){
global $amtLoan;
global $yuranSedia;
global $newYuranVal;
$style1 = 'style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px" width="30" align="center"';
$style2 = 'style="border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;border-left:#000000 solid 1px" width="30" align="center"';
$arrYuran = array(20,30,50,80,100);
$arrLoan1 = array(0,5,10,30,40);
$arrLoan2 = array(5,10,30,40,50);

if($index) $val1 = $arrLoan1[$index]*1000 + 1; else $val1 = '0.00';  
$val2 = $arrLoan2[$index]*1000;
$name = 'yuran'.$name;

$newYuran = '';
if($amtLoan >= $val1 && $amtLoan <= $val2){
//print 'check '.$val1.'-'.$amtLoan.'-'.$val2.' '.$yuranSedia;
  if($arrYuran[$index] > $yuranSedia) { 
	  $newYuran = 'X';
	  $newYuranVal = $arrYuran[$index];
  }	
}

$val1 = number_format($val1,2);
$val2 = number_format($val2,2);

if($row) $style=&$style1; else $style= &$style2;
print '
	<tr>
		<td>&nbsp;&nbsp;</td><td>Pembiayaan dipohon</td><td>&nbsp;-&nbsp;</td>
		<td>RM&nbsp;</td><td align="right">'.$val1.'</td><td>&nbsp;-&nbsp;</td><td>RM&nbsp;</td><td align="right">'.$val2.'</td>
		<td>&nbsp;</td><td>RM&nbsp;</td><td>'.number_format($arrYuran[$index],2).'</td><td>&nbsp;</td>
		<td '.$style.'>&nbsp;';
			print $newYuran; //selectCheck2($name,$check,$action)
		print '</td>
	</tr>';

} 

function selectCheck2($name,$type,$action){
$val = '';
if(!$action) $val = '<input type="radio" name="yuran" value="'.$name.'" '.$type.'">'; 
elseif($type) $val = 'X';
return $val;
}						
						biayaYuran(0,'a',$yurana,$action,1);
						biayaYuran(1,'b',$yuranb,$action);
						biayaYuran(2,'c',$yuranc,$action);
						biayaYuran(3,'d',$yurand,$action);
						biayaYuran(4,'e',$yurane,$action);
		
				print '</table></td></tr></table></td></tr><tr><td>&nbsp;</td></tr>';
		
		if(dlookup("loandocs", "prepare", "loanID=" . tosql($pk, "Text"))==1){
			$prepareBy= dlookup("loandocs", "prepareBy", "loanID=" . tosql($pk, "Text"));
			$prepareDate= todate("d/m/y",dlookup("loandocs", "prepareDate", "loanID=" . tosql($pk, "Text")));
			$approved= "";
		}else{
			$approved= "disabled";
		}

		if(dlookup("loandocs", "review", "loanID=" . tosql($pk, "Text"))==1){
			$reviewBy= dlookup("loandocs", "reviewBy", "loanID=" . tosql($pk, "Text"));
			$reviewDate= todate("d/m/y",dlookup("loandocs", "reviewDate", "loanID=" . tosql($pk, "Text")));
		}
		
print   	'<tr><td class="padding1" colspan="2"><u><b><i>Disokong Untuk Kelulusan</i></b></u></td></tr>
			<tr align="center">
				<td colspan="2" align="center">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="34%">Disediakan Oleh:'.dlookup("users", "name", "loginID=" . tosql($prepareBy, "Text")).'</td>
							<td width="33%">Disemak Oleh:'.dlookup("users", "name", "loginID=" . tosql($reviewBy  , "Text")).'</td>
						</tr>
						<tr>
							<td>Tarikh:'.$prepareDate.'</td>
							<td>Tarikh:'.$reviewDate.'</td>
						</tr>';
if($action<>'print'){
print '					<tr>
							<td><input type="Submit" name="SubmitForm" class="btn btn-sm btn-secondary" value="Disediakan" '.$ctlprepare.'></td>
							<td><input type=Submit name=SubmitForm class="btn btn-sm btn-secondary" value=Disemak  '.$ctlreview.'></td>
						</tr>';
}						

if($newYuranVal > $yuranSedia){
	$lpotBulan = $lpotBulan - $yuranSedia + $newYuranVal;
	$lpotBulanM = $lpotBulanM - $yuranSedia + $newYuranVal;
}

print '				</table>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
                        <td class="padding1" colspan="2" style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;"><b>Untuk Penyediaan Surat Tawaran</b></td></tr>
			<tr>
				<td colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>&nbsp;</td>
							<td width="100" align="center" valign="top" style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;">Bulan 1- '.($loanPeriod - 1).'</td>
							<td width="100" align="center" valign="top" style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;">Bulan <br>Seterusnya</td>
							<td width="100" align="center" valign="top" style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;">Jumlah <br>Bayaran Balik</td>
							<td style="border-left:#000000 solid 1px;">&nbsp;</td>
						</tr>
						<tr>
					<td>Potongan Wang Asal</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotAsal,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotAsalM,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotAsalN,2).'&nbsp;</td>
							<td style="border-left:#000000 solid 1px;">&nbsp;</td>
						</tr>
						<tr>
					<td>Potongan Untung</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotUntung,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotUntungM,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotUntungN,2).'&nbsp;</td>
							<td style="border-left:#000000 solid 1px;">&nbsp;</td>
						</tr>
						<tr>
							<td>Jumlah Potongan Pembiayaan</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBiaya,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBiayaM,2).'&nbsp;</td>
					<td style="border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBiayaN,2).'&nbsp;</td>
							<td style="border-left:#000000 solid 1px;">&nbsp;</td>
						</tr>
						<tr>
							<td>Jumlah Potongan Sebulan (p+y)</td>
					<td style="border-bottom:#000000 solid 1px;border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBulan,2).'&nbsp;</td>
					<td style="border-bottom:#000000 solid 1px;border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBulanM,2).'&nbsp;</td>
					<td style="border-bottom:#000000 solid 1px;border-top:#000000 solid 1px;border-left:#000000 solid 1px;" align="right">'.number_format($lpotBulanN,2).'&nbsp;</td>
							<td style="border-left:#000000 solid 1px;">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>';

$yuranBul =  $newYuranVal;
if($action<>'print'){
print		'<input type="hidden" name = "totalA15" value = "'.$totalA15.'">
			<input type="hidden" name = "totalFee" value = "'.$totalFee.'">
			<input type="hidden" name = "total" value = "'.$total.'">
			<input type="hidden" name = "totalFeePA1" value = "'.$totalFeePA1.'">
			<input type="hidden" name = "totalPB1" value = "'.$totalPB1.'">
			<input type="hidden" name = "balPA1" value = "'.$balPA1.'">
			<input type="hidden" name = "totalFeePA2" value = "'.$totalFeePA2.'">
			<input type="hidden" name = "totalPB2" value = "'.$totalPB2.'">
			<input type="hidden" name = "balPA2" value = "'.$balPA2.'">
			<input type="hidden" name = "totalFeePA3" value = "'.$totalFeePA3.'">
			<input type="hidden" name = "totalPB3" value = "'.$totalPB3.'">
			<input type="hidden" name = "balPA3" value = "'.$balPA3.'">
			<input type="hidden" name = "jamin80yuran" value = "'.$jamin80yuran.'">
			<input type="hidden" name = "jaminTot" value = "'.$jaminTot.'">
			<input type="hidden" name = "biaya" value = "'.$biaya.'">
			<input type="hidden" name = "biayayuran" value = "'.$biayayuran.'">
			<input type="hidden" name = "biayaTot" value = "'.$biayaTot.'">
			<input type="hidden" name = "gajiTot" value = "'.$gajiTot.'">
			<input type="hidden" name = "gajiPot" value = "'.$gajiPot.'">
			<input type="hidden" name = "gajiPotB" value = "'.$gajiPotB.'">
			<input type="hidden" name = "gajiBersih" value = "'.$gajiBersih.'">
			<input type="hidden" name = "potBenar" value = "'.$potBenar.'">
			<input type="hidden" name = "potBaru" value = "'.$potBaru.'">
			<input type="hidden" name = "btindih" value = "'.$btindih.'">
			<input type="hidden" name = "btindihUntung" value = "'.$btindihUntung.'">
			<input type="hidden" name = "btindihCaj" value = "'.$btindihCaj.'">
			<input type="hidden" name = "btindihBal" value = "'.$btindihBal.'">
			<input type="hidden" name = "yuranBul" value = "'.$newYuranVal.'">
			<input type="hidden" name = "yuranSedia" value = "'.$yuranSedia.'">
			<input type="hidden" name = "status" value = "'.$status.'">
			<input type="hidden" name = "lpotAsal" value = "'.$lpotAsal.'">
			<input type="hidden" name = "lpotUntung" value = "'.$lpotUntung.'">
			<input type="hidden" name = "lpotBiaya" value = "'.$lpotBiaya.'">
			<input type="hidden" name = "lpotBulan" value = "'.$lpotBulan.'">
			<input type="hidden" name = "lpotAsalM" value = "'.$lpotAsalM.'">
			<input type="hidden" name = "lpotUntungM" value = "'.$lpotUntungM.'">
			<input type="hidden" name = "lpotBiayaM" value = "'.$lpotBiayaM.'">
			<input type="hidden" name = "lpotBulanM" value = "'.$lpotBulanM.'">
			<input type="hidden" name = "lpotAsalN" value = "'.$lpotAsalN.'">
			<input type="hidden" name = "lpotUntungN" value = "'.$lpotUntungN.'">
			<input type="hidden" name = "lpotBiayaN" value = "'.$lpotBiayaN.'">
			<input type="hidden" name = "lpotBulanN" value = "'.$lpotBulanN.'">
			<input type="hidden" name = "updatedBy" value = "'.$updatedBy.'">
			<input type="hidden" name = "updatedDate" value = "'.$updatedDate.'">
			<input type="hidden" name = "remark" value = "'.$remark.'">
			</form>';
}
if($action<>'print'){
$rno1=  '<input type="text" name="rnoBaucer" class="form-control-sm" value="'.$rnoBaucer.'" size="10">';
$rno2 = '<input type="text" name="rnoBond" class="form-control-sm" value="'.$rnoBond.'" size="10" readonly>';
$rno3 = '<input type="text" name="rcreatedDate" class="form-control-sm" value="'.$rcreatedDate.'"  size="10" maxlength="10">';
}else{
$rno1 = $rnoBaucer;
$rno2 = $rnoBond;
$rno3 = $rcreatedDate;
}

print '
<tr><td class="padding1" colspan="2" style="border-top:#000000 solid 1px;border-bottom:#000000 solid 1px;"><b>Untuk Rekod Bayaran</b></td></tr>
			<tr>
				<td colspan="2">
					<form name="MyForm2" action='.$sFileName.' method=post>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
                                                                                                                                    <table width="100%"><tr><td width="40%">No. Baucer:&nbsp;</td><td>'.$rno1.'</td></tr></table>
							</td>
                                                                                                                                <td colspan="2"></td>
						</tr>
						<tr>
							<td>
                                                                                                                                    <table width="100%"><tr><td width="40%">No. Bond:&nbsp;</td><td>'.$rno2.'</td></tr></table>
                                                                                                                                </td>
                                                                                                                                <td colspan="2"></td>
						</tr>
						<tr>
							<td width="34%">
                                                                                                                              <table width="100%"><tr><td width="40%">Tarikh Dikeluarkan:&nbsp;</td><td>'.$rno3.'</td></tr></table>
                                                                                                                                  </td>
							<td width="33%">Disediakan Oleh:&nbsp;'.$rpreparedby.'</td>
							<td width="33%">Disahkan Oleh:&nbsp;'.$approvedBy.'</td>';
print '</table></form></td></tr></table>';
?>