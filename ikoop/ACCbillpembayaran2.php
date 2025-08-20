<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: ACCbillpembayaran.php
*			Date 		: 19/10/2006
*********************************************************************************/
include("header.php");
include("koperasiQry.php");
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$strHeaderTitle = '&nbsp;</b><a class="maroon" href="?vw=ACCbillList&mn='.$mn.'">SENARAI</a><b>'.'&nbsp;>&nbsp;PEMBAYARAN BIL</b>';

if (!isset($mm))	$mm=date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);

$display= 0;
if($no_bill && $action=="view"){

	$sql = "SELECT A.*,B.* FROM billacc a, generalacc b WHERE a.diterima_drpd = b.ID and a.no_bill = '".$no_bill."'";

	$rs 			= $conn->Execute($sql);
	$no_bill 		= $rs->fields(no_bill);
	$tarikh_bill 	= $rs->fields(tarikh_bill);
	$tarikh_bill 	= substr($tarikh_bill,8,2)."/".substr($tarikh_bill,5,2)."/".substr($tarikh_bill,0,4);

	$kod_bank 		= $rs->fields(kod_bank);
	$bankparent 	= dlookup("generalacc", "parentID", "ID=" . $kod_bank);

	$kerani 		= $rs->fields(kerani);
	$keterangan 	= $rs->fields(keterangan);
	$maklumat 		= $rs->fields(maklumat);
	$tarikh_bill 	= toDate("d/m/y",$rs->fields(tarikh_bill));
	$nama 			= $rs->fields(name);
	$batchNo 		= $rs->fields(batchNo);
	$accountNo 		= $rs->fields(accountNo);
	$kod_project 	= $rs->fields(kod_project);
	$keterangan		= $rs->fields(keterangan);
	$companyID		= $rs->fields(diterima_drpd);
	$cara_byr		= $rs->fields(cara_byr);
	$amt			= $rs->fields(pymtAmt);
	$code			= $rs->fields(code);
	$b_Baddress 	= $rs->fields(b_Baddress);
	$name			= $rs->fields(name);
	$PINo			= $rs->fields(PINo);
	$kodGL 			= $rs->fields(b_kodGL);
	//-----------------
	$sql2 = "SELECT * FROM transactionacc WHERE addminus IN (0) AND docNo = '".$no_bill."' ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
	if($rsDetail->RowCount()<1) 
		$noTran = true;


}elseif($action=="new"){  
	$getNo = "SELECT MAX(CAST(right(no_bill,6) AS SIGNED INTEGER)) AS nombor FROM billacc";
	$rsNo = $conn->Execute($getNo);
	$tarikh_bill = date("d/m/Y");
	$tarikh = date("d/m/Y");
	if($rsNo){
		$nombor = intval($rsNo->fields(nombor)) + 1; 
		$nombor = sprintf("%06s",  $nombor);
		$no_bill = 'BL'.$nombor;
	}else{
		$no_bill = 'BL000001';
	} 
}

if (!isset($tarikh_bill)) $tarikh_bill = date("d/m/Y");

if($kod_jabatan){
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s");
	$createdBy 	= get_session("Cookie_userName");
	$createdDate = date("Y-m-d H:i:s");
		
		$accountNo = $kodGL;  //perkara to deduct id value
		
		$addminus = 0;
		$cajAmt = 0.0;

		if($pymtAmt == '') 
			$pymtAmt = '0.0';
		$sSQL	= "INSERT INTO transactionacc (" . 					
				  "docNo," . 
				  "docID," . 
				  "batchNo," . 
				  "deductID," .
				  "MdeductID," .		
				  "kod_project," .	
				  "kod_jabatan," . 			
				  "addminus," . 
				  "pymtID," .			
				  "pymtAmt," .
				  "desc_akaun," . 	
				  "status," .
				  "isApproved," .
				  "approvedDate," .
				  "updatedBy," . 
				  "updatedDate	," . 
				  "createdBy," . 
				  "createdDate) " . 

				  " VALUES (" . 
				"'". $no_bill . "', ".
				"'". 7 . "', ".
				"'". $batchNo . "', ".
				"'". $accountNo . "', ".
				"'". $accountNo . "', ".
				"'". $kod_project . "', ".
				"'". $kod_jabatan . "', ".
				"'". $addminus . "', ".
				"'". 66 . "', ".
				"'". $kredit2 . "', ".
				"'". $desc_akaun2 . "', ".
				"'". $status . "', ".
				"'". $isApproved . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $createdBy . "', ".
				"'". $createdDate . "')";
				
		if($display) print $sSQL.'<br />';
		else{

			$rs = &$conn->Execute($sSQL);
		print '<script>
		window.location = "?vw=ACCbillpembayaran&mn='.$mn.'&action=view&no_bill='.$no_bill.'";
		</script>';
		}
}

if($action=="Hapus"){
  if(count($pk)>0){
	$sWhere = "";
	foreach($pk as $val) {
		$sSQL = '';
		$sWhere = "ID='" . $val ."'";
		$sSQL = "DELETE FROM transactionacc WHERE " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
	}
  }
	if(!$display){
	print '<script>
	window.location = "?vw=ACCbillpembayaran&mn='.$mn.'&action=view&no_bill='.$no_bill.'";
	</script>';
	}
}


elseif($action == "Kemaskini" || $jabatan1 || $desc_akaun || $projecting ) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "no_bill='" . $no_bill ."'";
		$tarikh_bill = saveDateDb($tarikh_bill);
		$tarikh =	saveDateDb($tarikh);	
		$sWhere = " WHERE (" . $sWhere . ")";	
		$sSQL	= "UPDATE billacc SET " .
					"no_bill='" .$no_bill . "',".
					"tarikh_bill='" .$tarikh_bill . "',".
					"batchNo='" .$batchNo . "',".
					"cara_byr='" .$cara_byr . "',".
					"kod_bank='" .$kod_bank . "',".
					"kerani='" .$kerani . "',".
					"keterangan='" .$keterangan . "',".
					"diterima_drpd='" .$companyID . "',".					
					"PINo='" .$PINo . "',".
					"maklumat='" .$maklumat . "',".
					"pymtAmt='" .$amt . "',".					
					"balance='" .$balance . "',".
					"StatusID_Pymt='0',".
					"createdDate='" .$updatedDate . "',".
					"createdBy='" .$updatedBy . "',".
					"updatedDate='" .$updatedDate . "',".
					"updatedBy='" .$updatedBy . "'";
				
		$sSQL = $sSQL . $sWhere;

		$sSQL1 = "";
		$sWhere1 = "";		
	 	$sWhere1 = "docNo='" . $no_bill ."' AND addminus='" . 1 ."'";
		$sWhere1 = " WHERE (" . $sWhere1 . ")";		
		$sSQL1	= "UPDATE transactionacc SET ".
					"deductID='" .$kod_bank. "',".
					"MdeductID='" .$bankparent. "',".
					"batchNo='" .$batchNo. "',".
					"pymtAmt='" .$masterAmt. "'";
					
		$sSQL1 = $sSQL1 . $sWhere1;

		$sSQL2 = "";
		$sWhere2 = "";		
	 	$sWhere2 = "docNo='" . $no_bill ."'";
		$sWhere2 = " WHERE (" . $sWhere2 . ")";		
		$sSQL2	= "UPDATE transactionacc SET ".
					"tarikh_doc='" .$tarikh_bill . "'";
					
		$sSQL2 = $sSQL2 . $sWhere2;

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
			$rs = &$conn->Execute($sSQL2);
//////////////////////////PROJEK//////////////////////////////////////////////////////////////
/*	if(count($perkara)>0){
		foreach($perkara as $id =>$value){

		$accountNo = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}

		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .
	     	"batchNo= '" . $batchNo . "'".
          	",deductID= '" . $accountNo . "'".
          	",pymtAmt= '" . $pymtAmt . "'".      	
		  	",updatedDate= '" .$updatedDate . "'".
          	",updatedBy= '" .  $updatedBy . "'" ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}*/
//////////////////////////PROJEK//////////////////////////////////////////////////////////////
		if(count($kod_akaunM)>0){
		foreach($kod_akaunM as $id =>$value){

		$MdeductID = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .
	    
	      "batchNo= '" . $batchNo . "',".
          "MdeductID= '" . $MdeductID . "',".
		  "updatedDate= '" .$updatedDate . "',".
          "updatedBy= '" .  $updatedBy . "'" ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}

	if(count($desc_akaun)>0){
		foreach($desc_akaun as $id =>$value){
		
		$desc_akaun = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .
	      "batchNo=" . tosql($batchNo, "Number").
          ",desc_akaun=" . tosql($desc_akaun, "Text").
          ",addminus=" . $addminus.
          ",pymtAmt=" . tosql($pymtAmt, "Number").
		  ",updatedDate=" . tosql($updatedDate, "Text") .
          ",updatedBy=" . tosql($updatedBy, "Text") ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}
	/////////////////////////////////////////////////////////

	if(count($projecting)>0){
		foreach($projecting as $id =>$value){

		$kod_project = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .
	    	"batchNo= '" . $batchNo . "'".
          	",kod_project= '" . $kod_project . "'".
          	",addminus= '" . $addminus . "'".
          	",pymtAmt= '" . $pymtAmt . "'".
		  	",updatedDate= '" .$updatedDate . "'".
          	",updatedBy= '" .  $updatedBy . "'" ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}


	if(count($jabatan1)>0){
		foreach($jabatan1 as $id =>$value){

		$kod_jabatan = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}

		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .

   			"batchNo= '" . $batchNo . "'".
          	",kod_jabatan= '" . $kod_jabatan . "'".
          	",addminus= '" . $addminus . "'".
          	",pymtAmt= '" . $pymtAmt . "'".
  			",updatedDate= '" .$updatedDate . "'".
          	",updatedBy= '" .  $updatedBy . "'" ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}
	/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////	
	if(!$display){
	print '<script>
	window.location = "?vw=ACCbillpembayaran&mn='.$mn.'&action=view&no_bill='.$no_bill.'";
	</script>';
	}
}
//pilihan simpan
 elseif($action == "Simpan" || $simpan) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$tarikh_bill = saveDateDb($tarikh_bill);
		$tarikh =	saveDateDb($tarikh);
		$sSQL = "";
		$sSQL	= "INSERT INTO billacc (" . 
					"no_bill, " .
					"tarikh_bill, " .
					"batchNo, " .
					"cara_byr, ".
					"kod_bank, " .
					"kerani, ".
					"keterangan, ".
					"diterima_drpd, ".
					"PINo, ".
					"maklumat, " .
					"pymtAmt, ".
					"StatusID_Pymt, ".
					"createdDate, " .
					"createdBy, " .
					"updatedDate, " .
					"updatedBy) " .				
					
		            " VALUES (" . 

					"'". $no_bill . "', ".
					"'". $tarikh_bill . "', ".
					"'". $batchNo . "', ".
					"'". $cara_byr . "', ".
					"'". $kod_bank . "', ".
					"'". $kerani . "', ".
					"'". $keterangan . "', ".
					"'". $companyID . "', ".
					"'". $PINo . "', ".
					"'". $maklumat . "', ".
					"'". $amt . "', ".
					"'". 0 . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "')";

		$sSQL1 = "";
		$sSQL1	= "INSERT INTO transactionacc (" . 
					
				  "docNo," . 
				  "tarikh_doc," .
				  "docID," . 
				  "batchNo," . 
				  "deductID," .		
				  "kod_project," .
				  "kod_jabatan," . 				
				  "addminus," . 
				  "pymtID," .					  		
				  "pymtAmt," .	
				  "desc_akaun," . 
				  "status," .
				  "isApproved," .
				  "approvedDate," .
				  "updatedBy," . 
				  "updatedDate	," . 
				  "createdBy," . 
				  "createdDate) " . 

				  " VALUES (" . 
				"'". $no_bill . "', ".
				"'". $tarikh_bill . "', ".
				"'". 7 . "', ".
				"'". $batchNo . "', ".
				"'". $kod_bank . "', ".
				"'". $kod_project . "', ".
				"'". $kod_jabatan . "', ".
				"'". 1 . "', ".
				"'". 66 . "', ".
				"'". $masterAmt . "', ".
				"'". $desc_akaun2 . "', ".
				"'". $status . "', ".
				"'". $isApproved . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "')";
					
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		$rs = &$conn->Execute($sSQL1);

	$getMax = "SELECT MAX(CAST(right(no_bill,6) AS SIGNED INTEGER)) AS no FROM billacc";
	$rsMax = $conn->Execute($getMax);
	$max = sprintf("%06s", $rsMax->fields(no));
	if(!$display){
	print '<script>
	window.location = "?vw=ACCbillpembayaran&mn='.$mn.'&action=view&add=1&no_bill=BL'.$max.'";
	</script>';
	}
}

$strTemp .=
'<div class="maroon" align="left">'.$strHeaderTitle.'</div>'
.'<div style="width: 100%; text-align:left">'
.'<div>&nbsp;</div>'
.'<form name="MyForm" action="?vw=ACCbillpembayaran&mn='.$mn.'" method="post">'
.'<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center">';

print $strTemp;
print 
'<tr>
	<td width="48%">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td>No. Bill</td>
				<td valign="top"></td>
				<td>
					<input  name="no_bill" value="'.$no_bill.'" type="text" size="20" maxlength="50" class="form-controlx" readonly/>
				</td>
			</tr>

			<tr>
				<td>Batch</td>
				<td valign="top"></td>
				<td>'.selectbatchBILL($batchNo,'batchNo').'</td>
			</tr>

			<tr>
				<td>Bank</td>
				<td valign="top"></td>
				<td>'.selectbanks($kod_bank,'kod_bank').'</td>
			</tr>

		</table>
	</td>
	<td valign="top">&nbsp;</td>
	<td width="48%" align="right">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td valign="top" align="right">Tarikh</td>
				<td valign="top"></td>
				<td><input class="form-controlx" name="tarikh_bill" value="'.$tarikh_bill.'" type="text" size="20" maxlength="10" /></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td colspan="3"><hr class="mt-3"/></td></tr>';


print '
<tr colspan="3">
	<td valign="top"><input name="j" type="hidden" value="tiada">

<table border="0" cellspacing="1" cellpadding="2">

<tr>
<td>* Kod Pemiutang</td><td valign="top"></td>
<td><input name="code" value="'.$code.'" type="text" size="20" maxlength="50"  class="form-controlx" readonly/>&nbsp;';

print '<input type="button" class="btn btn-sm btn-info" value="Pilih" onclick="window.open(\'ACCidpemiutangBILL.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';
print '&nbsp;

</td>
</tr>

<tr>
 <td valign="top">Nama Syarikat</td>
 <td valign="top"></td>
 <td><input name="name" value="'.$name.'" size="40" maxlength="50"  class="form-controlx" readonly /></td>
 </tr>
<tr>
<td valign="top">Alamat Syarikat</td>
<td valign="top"></td>
<td><textarea name="b_Baddress" cols="50" rows="4" class="form-controlx" readonly>'.$b_Baddress.'</textarea></td>
</tr>

 <tr>
 <td valign="top">Amaun Purchase Invoice (RM)</td>
 <td valign="top"></td>
 <td><input name="amt"  value="'.$amt.'" size="10" maxlength="50"  class="form-controlx" readonly/></td>
 </tr>

 <tr>
 <td valign="top">No. Purchase Invoice</td>
 <td valign="top"></td>
 <td><input name="PINo" value="'.$PINo.'" size="40" maxlength="50"  class="form-controlx" readonly /></td>
 </tr>

<tr>
 <td valign="top"></td>
 <td valign="top"></td>
 <td><input name="kodGL" value="'.$kodGL.'" size="40" maxlength="50"  class="form-controlx" hidden /></td>
 </tr>
<tr>

<tr>
 <td valign="top"></td>
 <td valign="top"></td>
 <td><input name="companyID" value="'.$companyID.'" size="40" maxlength="50"  class="form-controlx" hidden /></td>
 </tr>
<tr>

<tr>
	<td valign="top">Keterangan Bayaran</td>
	<td valign="top"></td>
	<td>
		<textarea name="keterangan" cols="50" rows="4" class="form-controlx">'.$keterangan.'</textarea>
	</td>
</tr>

 <tr>
 <td valign="top">Cara Bayar</td>
 <td valign="top"></td>
 <td>'.selectbayar($cara_byr,'cara_byr').'</td>
 </tr>

</table>
</td>

<tr>
	<td>&nbsp;</td>
</tr>';
	//----------
	if ($action=="view" && !is_int(dlookup("transactionacc", "ID", "docNo='" . $no_bill ."'"))){
	print '
	<tr>
			<td align= "right" colspan="3">';
	    if(!$add) print '
			<input type="button" name="add" value="Tambah" class="btn btn-sm btn-primary" onClick="window.location.href=\'?vw=ACCbillpembayaran&mn='.$mn.'&action='.$action.'&no_bill='.$no_bill.'&add=1\';">'; 
	    else print '
			<input type="button" name="action" value="Simpan" class="btn btn-sm btn-primary" onclick="CheckField(\'Kemaskini\')">';
		print '&nbsp;<input type="submit" name="action" value="Hapus" class="btn btn-sm btn-danger">
		</td>
	</tr>';
}
//----------
print 
'<tr>
	<td colspan="3">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" class="table table-sm table-striped">
<tr class="table-primary">
				<td nowrap="nowrap"><b>Bil</b></td>
				<td nowrap="nowrap"><b>Jabatan</b></td>
				<td nowrap="nowrap"><b>Projek</b></td>
				<td nowrap="nowrap"><b>Keterangan</b></td>
				<td nowrap="nowrap" align="right" ><b>Jumlah (RM)</b></td>
				<td nowrap="nowrap">&nbsp;</td>
			</tr>'; 

if ($action=="view"){
	$i = 0;
	while (!$rsDetail->EOF) {

	$id 		= $rsDetail->fields(ID);
	$projecting = $rsDetail->fields(kod_project);
	$jabatan1 	= $rsDetail->fields(kod_jabatan);
	$desc_akaun =	$rsDetail->fields(desc_akaun);


	$kod_project 		= dlookup("generalacc", "name", "ID=" . $projecting);
	$kod_jabatan 		= dlookup("generalacc", "name", "ID=" . $jabatan1);
	$kredit 			= $rsDetail->fields(pymtAmt);
	print	   
			'<tr>
				<td class="Data">'.++$i.'.</td>	

				<td class="Data" nowrap="nowrap">'.strjabatan($id,$jabatan1).'</td>

				<td class="Data" nowrap="nowrap">'.strproject($id,$projecting).'</td>

				<td class="Data" nowrap="nowrap">
					<input name="desc_akaun['.$id.']" type="text" size="35" maxlength="35" class="form-control-sm" value="'.$desc_akaun.'"/>
				</td>

				

				<td class="Data" align="right">
					<input name="kredit['.$id.']" type="text" size="10" maxlength="10" value="'.$kredit.'" class="form-control-sm" style="text-align:right;"/>
				</td>

				<td class="Data" nowrap="nowrap"><input type="checkbox" class="form-check-input" name="pk[]" value="'.$id.'"></td>

			</tr>';
		  $totalKt += $kredit;
		  $baki=$amt-$totalKt;
		  $kredit = '';
	$rsDetail->MoveNext();
	}
}

if($add){
print	   '
			<tr>
				<td class="Data" nowrap="nowrap">&nbsp;</td>	

				<td class="Data" size="20" maxlength="10">'.selectjabatan($kod_jabatan,'kod_jabatan').'</td>

				<td class="Data" size="20" maxlength="10">'.selectproject($kod_project,'kod_project').'</td>

				<td class="Data" align="left">
					<input name="desc_akaun2" type="text" class="form-control-sm" size="35" maxlength="100" value="'.$desc_akaun2.'" align="right"/>&nbsp;
				</td>

				<td class="Data" align="right">
					<input type="hidden" name="ruj2" val="0">
					<input name="kredit2" type="text" size="10" class="form-control-sm" maxlength="10" value="'.$kredit2.'" />&nbsp;
				</td>



				<td class="Data" align="right"></td>
				
			</tr>';
}
//bahagian bawah skali
if($totalKt<>0){
	$clsRM->setValue($baki);
	$clsRM->setValue($totalKt);
	$strTotal = ucwords($clsRM->getValue()).' Sahaja.';
}

$kerani = get_session('Cookie_fullName');

print 		'<tr class="table-secondary">
				<td class="Data" colspan="4" align="right"><b>Jumlah (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($totalKt,2).'&nbsp;	
				</b></td>
				<td class="Data" align=""><b>&nbsp;</b></td>
			</tr>

			<tr class="table-secondary">
				<td class="Data" colspan="4" align="right"><b>Baki (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($baki,2).'&nbsp;	
				</b></td>
				<td class="Data" align=""><b>&nbsp;</b></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td width="60%" valign="top" colspan="3">
		<table border="0" cellspacing="1" cellpadding="3">

	<tr><td colspan="3" nowrap="nowrap">Jumlah Dalam Perkataan<br />
		<input name="" size="100" maxlength="100" class="form-controlx" value="'.$strTotal.'" readonly>
		<input class="form-controlx" type="hidden" name="masterAmt" value="'.$totalKt.'">
		<input class="form-controlx" type="hidden" name="balance" value="'.$baki.'">	
		<input class="form-controlx" type="hidden" name="bankparent" value="'.$bankparent.'">
	</td></tr>

		
			<tr><td nowrap="nowrap">Dimasukkan Oleh</td><td valign="top"></td><td><input class="form-controlx" name="kerani" value="'.$kerani.'" type="text" size="20" maxlength="15"/></td></tr>
			<tr><td nowrap="nowrap" valign="top">Catatan</td><td valign="top"></td><td valign="top"><textarea class="form-controlx" name="maklumat" cols="50" rows="4">'.$maklumat.'</textarea></td></tr>
		</table>
	</td>
</tr>';
print '<input name="kod_caw" type="hidden" value="321"><input name="no_siri" type="hidden" value="S112"><input name="tarikh" type="hidden" value="01/10/2006"></tr>';


if($no_bill) { 
$straction = ($action=='view'?'Kemaskini':'Simpan');
print '
<tr>
	<td>
	<input type="button" name="print" value="Cetak" class="btn btn-secondary" onClick= "print_(\'ACCBillPrintCustomer.php?id='. $no_bill .'\')">&nbsp;
	<input type="button" name="action" value="'.$straction.'" class="btn btn-primary" onclick="CheckField(\''. $straction. '\')">';
if($straction=='Simpan') print '
	<input type="hidden" name="simpan" value="1">';
print '
	</td>
</tr>';

}

$strTemp = '
	</table>
</form>
</div>';

print $strTemp;
print '
<script language="JavaScript">
	function print_(url) {
		window.open(url,"pop","top=100, left=100, width=600, height=400, scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=yes");					
	}

	function CheckField(act) {
	    e = document.MyForm;
		count = 0;	
		for(c=0; c<e.elements.length; c++) {
		  //if(!e.debit2.value == \'\') alert(e.nama_anggota.value);
		  if(e.elements[c].name=="no_anggota" && e.elements[c].value==\'\') {
			alert(\'Sila pilih anggota!\');
            count++;
		  }
		  
		  if(act == \'Kemaskini\') {
  
		  if(e.elements[c].name=="kredit2" && e.elements[c].value==\'\') {
			alert(\'Ruang amaun perlu diisi!\');
            count++;
		  }
		  }

		  if(act == \'Simpan\') {
  
		  if(e.elements[c].name=="batchNo" && e.elements[c].value==\'\') 
		  	{
			alert(\'Ruang batch perlu diisi!\');
            count++;
		 	}
		  }
		}
		if(count==0) {
			e.submit();
		}
	}
</script>';
include("footer.php");
?>