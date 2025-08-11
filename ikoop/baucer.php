<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: baucer.php
*			Date 		: 19/10/2006
*********************************************************************************/
include("header.php");
include("koperasiQry.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0 ) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$strHeaderTitle = '&nbsp;</b><a class="maroon" href="?vw=vouchersList&mn=908">SENARAI</a><b>'.'&nbsp;>&nbsp;Baucer</b>';

if (!isset($mm))	$mm=date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);

$display= 0;
if($no_baucer && $action=="view"){
	$sql = "SELECT a.*,b.memberID,b.address, b.city, b.postcode, b.stateID, b.departmentID, c.name FROM  vauchers a, userdetails b, users c WHERE b.userID = c.userID and a.no_anggota = b.memberID and no_baucer = '" . $no_baucer ."'";
	$rs = $conn->Execute($sql);

	$no_baucer 		= $rs->fields(no_baucer);
	$tarikh_baucer 	= $rs->fields(tarikh_baucer);
	$tarikh_baucer 	= substr($tarikh_baucer,8,2)."/".substr($tarikh_baucer,5,2)."/".substr($tarikh_baucer,0,4);
	$jenis 			= $rs->fields(jenis);
	$no_bond 		= $rs->fields(no_bond);
	$no_anggota 	= $rs->fields(no_anggota);
	$disediakan 	= $rs->fields(disediakan);

	$kod_bank 		= $rs->fields(kod_bank);
	$bankparent 	= dlookup("generalacc", "parentID", "ID=" . $kod_bank);
	
	$disahkan 		= $rs->fields(disahkan);
	$keterangan 	= $rs->fields(keterangan);
	$kod_caw 		= $rs->fields(kod_caw);
	$no_siri 		= $rs->fields(no_siri);
	$tarikh_bank	= substr($tarikh_bank,8,2)."/".substr($tarikh_bank,5,2)."/".substr($tarikh_bank,0,4);
	$nama 			= $rs->fields(name);
	$deptID			=  $rs->fields('departmentID');
	$departmentAdd	=  dlookup("general", "b_Address", "ID=" . tosql($deptID, "Number"));
	$alamat 		= strtoupper(strip_tags($departmentAdd));

	$masterAmt		= $rs->fields(pymtAmt);
	$batchNo 		= $rs->fields(batchNo);
	//-----------------
	$sql2 = "SELECT * FROM transaction WHERE docNo = '". $no_baucer ."' ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
	if($rsDetail->RowCount()<1) $noTran = true;

}elseif($action=="new"){  
	$getNo = "SELECT MAX(CAST(right(no_baucer,6) AS SIGNED INTEGER)) AS nombor FROM vauchers";
	$rsNo = $conn->Execute($getNo);
	if($rsNo){
		$nombor = intval($rsNo->fields(nombor)) + 1; 
		$nombor = sprintf("%06s",  $nombor);
		$no_baucer = 'PVA'.$nombor;
	}else{
		$no_baucer = 'PVA000001';
	} 
}

if (!isset($tarikh_baucer)) $tarikh_baucer = date("d/m/Y");
if (!isset($tarikh_bank)) $tarikh_bank = date("d/m/Y");

if($perkara2){
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s");               
		if($no_bond) {
		$pk = dlookup("loandocs", "loanID", "rnoBond = '". $no_bond . "'");
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "loanID='" . $pk . "'";
		$sWhere = " WHERE (" . $sWhere . ")";		
		$sSQL	= "UPDATE loandocs SET " .
					" rnoBaucer = '" . $no_baucer . "'". 
					", rcreatedDate = '" . $updatedDate . "'". 
					", rpreparedby = '" . $disediakan . "'".
					", approvedBy = '" . $disahkan . "'";
		$sSQL = $sSQL . $sWhere;
		//print '<br>'.$sSQL;
		$rs = &$conn->Execute($sSQL);
		}

		$userID = dlookup("userdetails","userID","memberID = '". $no_anggota . "'");
		$deductID = $perkara2; //perkara to deduct id value

		if($debit2){ //debit 2 field for money value
		$pymtAmt = $debit2;
		$addminus = 0;
		$cajAmt = 0.0;
		}else{
		$pymtAmt = $kredit2;
		$addminus = 1;
		$cajAmt = 0.0;
		}

		if($pymtAmt == '') $pymtAmt = '0.0';
		$sSQL	= "INSERT INTO transaction (" . 
				  "docNo," . 
				  "userID," . 
				  "yrmth," .			
				  "deductID," . 
				  "transID," .			
				  "addminus," . 
				  "pymtID," . 
				  "pymtRefer," .			
				  "pymtAmt," . 
				  "cajAmt," . 
				  "createdDate," . 
				  "createdBy," . 
				  "updatedDate," . 
				  "updatedBy)" . 
				  " VALUES (" . 
				"'". $no_baucer . "', ".
				"'". $userID . "', ".
				"'". $yymm . "', ".
				"'". $deductID . "', ".
				"'". 79 . "', ".
				"'". $addminus . "', ".
				"'". 66 . "', ".
				"'". $no_bond . "', ".
				"'". $pymtAmt . "', ".
				"'". $cajAmt . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "')";

		if($display) print $sSQL.'<br />';
		else{ 
			$rs = &$conn->Execute($sSQL);
		print '<script>
		window.location = "?vw=baucer&mn=908&action=view&no_baucer='.$no_baucer.'";
		</script>';
		}
}

if($action=="Hapus"){
  if(count($pk)>0){
	$sWhere = "";
	foreach($pk as $val) {
		$sSQL = '';
		$sWhere = "ID='" . $val ."'";
		$sSQL = "DELETE FROM transaction WHERE " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
	}
  }
	if(!$display){
	print '<script>
	window.location = "?vw=baucer&mn=908&action=view&no_baucer='.$no_baucer.'";
	</script>';
	}
}elseif($action == "Kemaskini" || $perkara) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "no_baucer='" . $no_baucer ."'";
		$tarikh_baucer = saveDateDb($tarikh_baucer);
		$tarikh_bank =	saveDateDb($tarikh_bank);
		$sWhere = " WHERE (" . $sWhere . ")";		
		$sSQL	= "UPDATE vauchers SET " .
					"no_anggota='" .$no_anggota . "',".
					"disediakan='" .$disediakan . "',".
					"kod_bank='" .$kod_bank . "',".
					"batchNo='" .$batchNo . "',".
					"disahkan='" .$disahkan . "',".
					"keterangan='" .$keterangan . "',".
					"kod_caw='" .$kod_caw . "',".
					"no_siri='" .$no_siri . "',".
					"pymtAmt='" .$masterAmt . "',".
					"StatusID_Pymt='". 0 . "',".
					"tarikh_bank='" .$tarikh_bank . "',".
					"updatedDate='" .$updatedDate . "',".
					"updatedBy='" .$updatedBy . "'";
		$sSQL = $sSQL . $sWhere;	

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(count($perkara)>0){
		foreach($perkara as $id =>$value){
		$deductID = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 0;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 1;
		}
		$no_ruj = $ruj[$id];
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transaction SET " .
          "deductID= '" . $deductID . "'".
          ",addminus= '" . $addminus . "'".
          ",pymtAmt= '" . $pymtAmt . "'".
		  ",updatedDate= '" .$updatedDate . "'".
          ",updatedBy= '" .  $updatedBy . "'" ;
		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(count($kod_master)>0){
		foreach($kod_master as $id =>$value){
		$MdeductID = $value;
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 0;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 1;
		}
		$no_ruj = $ruj[$id];
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transaction SET " .
          "MdeductID= '" . $MdeductID . "'".
          ",addminus= '" . $addminus . "'".
          ",pymtAmt= '" . $pymtAmt . "'".
		  ",updatedDate= '" .$updatedDate . "'".
          ",updatedBy= '" .  $updatedBy . "'" ;
		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(!$display){
	print '<script>
	window.location = "?vw=baucer&mn=908&action=view&no_baucer='.$no_baucer.'";
	</script>';
	}

} elseif($action == "Simpan" || $simpan) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$tarikh_baucer = saveDateDb($tarikh_baucer);
		$tarikh_bank =	saveDateDb($tarikh_bank);
		$sSQL = "";
		$sSQL	= "INSERT INTO vauchers (" . 
					"no_baucer, " .
					"tarikh_baucer, " .
					"batchNo, " .
					"jenis, " .
					"no_bond, " .
					"no_anggota, " .
					"disediakan, " .
					"kod_bank, " .
					"disahkan, " .
					"keterangan, " .
					"kod_caw, " .
					"no_siri, " .
					"pymtAmt, ".
					"StatusID_Pymt, ".
					"tarikh_bank, " .
					"createdDate, " .
					"createdBy, " .
					"updatedDate, " .
					"updatedBy) " .
		            " VALUES (" . 
					"'". $no_baucer . "', ".
					"'". $tarikh_baucer . "', ".
					"'". $batchNo . "', ".
					"'". $jenis . "', ".
					"'". $no_bond . "', ".
					"'". $no_anggota . "', ".
					"'". $disediakan . "', ".
					"'". $kod_bank . "', ".
					"'". $disahkan . "', ".
					"'". $keterangan . "', ".
					"'". $kod_caw . "', ".
					"'". $no_siri . "', ".
					"'". $masterAmt . "', ".
					"'". 0 . "', ".
					"'". $tarikh_bank . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "')";

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);

	$getMax = "SELECT MAX(CAST(right(no_baucer,6) AS SIGNED INTEGER)) AS no FROM vauchers";
	$rsMax = $conn->Execute($getMax);
	$max = sprintf("%06s", $rsMax->fields(no));
	if(!$display){
	print '<script>
	window.location = "?vw=baucer&mn=908&action=view&add=1&no_baucer=PVA'.$max.'";
	</script>';
	}
}

$strTemp .=
'<div class="maroon" align="left">'.$strHeaderTitle.'</div>'
.'<div style="width: 100%; text-align:left">'
.'<div>&nbsp;</div><div class="table-responsive">'
.'<form name="MyForm" action="?vw=baucer&mn=908" method="post">'
.'<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center">';

print $strTemp;
print 
'<tr>
	<td colspan="3">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr><td>No. Baucer</td><td valign="top"></td><td><input name="no_baucer" value="'.$no_baucer.'" type="text" size="20" maxlength="50" class="form-control-sm" readonly/></td></tr>
			<tr><td>* Tarikh</td><td valign="top"></td><td><input name="tarikh_baucer" value="'.$tarikh_baucer.'" type="text" size="20" maxlength="10" class="form-control-sm"/></td></tr>

			<tr>
				<td>Batch</td>
				<td valign="top"></td>
				<td>'.selectbatch($batchNo,'batchNo').'</td>
			</tr>

			<tr>
				<td>Bank</td>
				<td valign="top"></td>
				<td>'.selectbanks($kod_bank,'kod_bank').'</td>
			</tr>


		</table>
	</td>
</tr>
<tr><td colspan="3"><hr class="mt-3"/></td></tr>';

print '
<tr colspan="3">
	<td valign="top"><input name="j" type="hidden" value="tiada">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr><td valign="top">Bayar Kepada</td></tr>
			<tr>
				<td>* No. Koperasi</td><td valign="top"></td>
				<td><input name="no_anggota" value="'.$no_anggota.'" type="text" size="20" maxlength="50"  class="form-control-sm" readonly/>&nbsp;';				
				if($action=="new" && $jenis == 1) print '<input type="button" class="btn btn-sm btn-info waves-light waves-effect" value="Pilih" onclick="window.open(\'selToMember.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';
				else if($action=="new" && $jenis == 2) print '<input type="button" class="btn btn-sm btn-info waves-light waves-effect" value="Pilih" onclick="window.open(\'selLoanS.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';
				print '&nbsp;<input name="loan_no" type="hidden" value=""></td>
			</tr>
			<tr><td valign="top">Nama</td><td valign="top"></td><td><input name="nama_anggota"  value="'.$nama.'" type="text" size="40" maxlength="50" class="form-control-sm" readonly/>
		    </td></tr>
			<tr><td valign="top">Alamat</td><td valign="top"></td><td><textarea name="alamat" cols="50" rows="4" class="form-control-sm" readonly>'.$alamat.'</textarea></td></tr>
			<tr>
			  <td valign="top">No. Bond / Amaun (RM)</td>
			  <td valign="top"></td>
			  <td><input name="no_bond"  value="'.$no_bond.'" size="10" maxlength="50"  class="form-control-sm" readonly />
		      <input name="amt"  value="'.$amt.'" size="10" maxlength="50"  class="form-control-sm" readonly="readonly" /></td>
		  </tr>
			<tr>
			  <td valign="top">Jenis Pembiayaan</td>
			  <td valign="top"></td>
			  <td><input name="name_type"  value="'.$nametype.'" size="40" maxlength="50"  class="form-control-sm" readonly /></td>
		  </tr>

		<tr>
				<td valign="top" align="left">Master Amaun (RM)</td><td valign="top"></td>
				<td><input class="form-control-sm" value="'.$masterAmt.'" type="text" size="20" maxlength="10"/></td>
			</tr>
		  
		</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>';
//----------
if ($action=="view" && !is_int(dlookup("transaction", "ID", "docNo='" . $no_baucer ."'"))){
print '
<tr>
	<!--td align= "left"><input type="checkbox" onClick="ITRViewSelectAll()" class="Data">Tanda semua</td-->
	<td align= "right" colspan="3">';
if(!$add) print '
		<input type="button" name="add" value="Tambah" class="btn btn-sm btn-primary" onClick="window.location.href=\'?vw=baucer&mn=908&action='.$action.'&no_baucer='.$no_baucer.'&add=1\';">'; 
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
			<tr class="table-danger">
				<td nowrap="nowrap"><b>Bil</b></td>
				<td nowrap="nowrap"><b>* Perkara</b></td>
				<td nowrap="nowrap"><b>Kod Master Akaun</b></td>
				<td nowrap="nowrap"><b>Kod Objek</b></td>
				<td nowrap="nowrap"><b>Kod Akaun</b></td>
				<td nowrap="nowrap" align="right"><b>* Jumlah (RM)</b></td>
				<td nowrap="nowrap">&nbsp;</td>
			</tr>';

if ($action=="view"){
	$i = 0;
	while (!$rsDetail->EOF) {
	$id = $rsDetail->fields(ID);
	$ruj = $rsDetail->fields(pymtRefer);
	$perkara = $rsDetail->fields(deductID);	
	$kod_master = dlookup("general", "c_master", "ID=" . $perkara);
	$namagl = dlookup("generalacc", "name", "ID=" . $kod_master);


	$kod_objek = dlookup("general", "code", "ID=" . $perkara);
	$kod_akaun = dlookup("general", "c_Panel", "ID=" . $perkara);
	$keterangan2 = dlookup("general", "name", "ID=" . $kod_akaun);
		if($rsDetail->fields(addminus)){
		$kredit = $rsDetail->fields(pymtAmt);
		}else{
		$debit = $rsDetail->fields(pymtAmt);
		}
	print	   '
			<tr>
				<td class="Data">&nbsp;'.++$i.'.</td>				
				<td class="form-control-xs" nowrap="nowrap">'.strSelect2($id,$perkara).'&nbsp;</td>

				<td class="Data" nowrap="nowrap">
				<input class="form-control-sm" name="namagl['.$id.']" type="text" size="30" maxlength="30" value="'.$namagl.'"/>
				<input class="form-control-sm" name="kod_master['.$id.']" type="hidden" size="10" maxlength="10" value="'.$kod_master.'"/>
				</td>

				<td class="Data" nowrap="nowrap">
					<input class="form-control-sm" name="kod_objek['.$id.']" type="text" size="8" maxlength="10" value="'.$kod_objek.'" readonly/>&nbsp;
				</td>
				<td class="Data" nowrap="nowrap">
					<input class="form-control-sm" name="kod_akaun['.$id.']" type="text" size="8" maxlength="10" value="'.$kod_akaun.'" readonly/>&nbsp;
				</td>
				<!--td class="Data" align="right">
					<input name="ruj['.$id.']" type="text" size="8" maxlength="10" value="'.$ruj.'" class="form-control-sm" />&nbsp;
				</td-->
				<td class="Data" align="right">
					<input type="hidden" name="ruj['.$id.']" val="0">
					<input name="debit['.$id.']" type="text" size="10" maxlength="10" value="'.$debit.'" class="form-control-sm" style="text-align:right;"/>&nbsp;
				</td>
				<td class="Data" nowrap="nowrap"><input type="checkbox" class="form-check-input" name="pk[]" value="'.$id.'">&nbsp;</td>
			</tr>';
		  $totalDb += $debit;
		  
		  $debit = '';
		  
	$rsDetail->MoveNext();
	}
}
if($no_bond && $noTran){
	$loanID = dlookup("loandocs", "loanID", "rnoBond='" .$no_bond . "'");
	$loanAmt = dlookup("loans", "loanAmt", "loanID=" .$loanID);
	$loanType = dlookup("loans", "loanType", "loanID=" .$loanID);
	$code	= dlookup("general", "c_deduct", "ID=" . $loanType);
	$debit2 = &$loanAmt;
	$add = 1;
}

$strDeductIDList = deductList(1);
$strDeductNameList = deductList(3);
$name = 'perkara2';

$strSelect = '<select class="form-select-sm" name="'.$name.'">
			 <option value="">- Kod -';
			for ($i = 0; $i < count($strDeductIDList); $i++) {
				$strSelect .= '	<option value="'.$strDeductIDList[$i].'" ';
				if ($code == $strDeductIDList[$i]) $strSelect .= ' selected';
				$strSelect .=  '>'.$strDeductNameList[$i];
			}
$strSelect .= '</select>';

if($add){
print	   '<tr>
				<td class="Data" nowrap="nowrap">&nbsp;</td>
				<td class="Data">'.$strSelect.'</td>

				<td class="Data">
				<input name="namagl2" type="text" size="30" maxlength="30" value="'.$namagl2.'" class="form-control-sm">
				<input name="kod_master2" type="hidden" size="10" maxlength="10" value="'.$kod_master2.'" class="form-control-sm">
				</td>


				<td class="Data" nowrap="nowrap">
					<input name="kod_objek2" type="text" size="8" maxlength="10" value="'.$kod_objek2.'"  class="form-control-sm" readonly/>&nbsp;
				</td>
				<td class="Data" nowrap="nowrap">
					<input name="kod_akaun2" type="text" size="8" maxlength="10" value="'.$kod_akaun2.'"  class="form-control-sm" readonly/>&nbsp;
				</td>
				<!--td class="Data" align="right">
					<input name="ruj2" type="text" size="8" maxlength="10" value="'.$ruj2.'"/>&nbsp;
				</td-->
				<td class="Data" align="right">
					<input type="hidden" name="ruj2" val="0">
					<input name="debit2" type="text" size="10" maxlength="10" value="'.$loanAmt.'" class="form-control-sm" style="text-align:right;"/>&nbsp;
				</td>
				<td class="Data" align="right"><b>&nbsp;</b></td>
			</tr>';
}

if($totalDb<>0){
	$clsRM->setValue($totalDb);
	$strTotal = ucwords($clsRM->getValue()).' Sahaja.';
}

print 		'<tr class="table-secondary">
				<td class="Data" colspan="5" align="right"><b>Jumlah (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($totalDb,2).'&nbsp;</b></td>
				<td class="Data" align="right"><b>&nbsp;</b></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr colspan="3">
	<td valign="top">
		<table border="0" cellspacing="1" cellpadding="3">
			<tr><td nowrap="nowrap">Jumlah Dalam Perkataan</td><td valign="top"></td><td>
			<input name="" size="80" class="form-control-sm" maxlength="80" value="'.$strTotal.'" readonly>			
			<input class="Data" type="hidden" name="masterAmt" value="'.$totalDb.'">
			<input class="Data" type="hidden" name="bankparent" value="'.$bankparent.'">


			</td></tr>
			

			<tr><td nowrap="nowrap">Disediakan Oleh</td><td valign="top"></td><td>'.selectAdmin($disediakan,'disediakan').'</td></tr>
			<tr><td nowrap="nowrap">Disahkan Oleh</td><td valign="top"></td><td>'.selectAdmin($disahkan,'disahkan').'</td></tr>
			<tr><td nowrap="nowrap" valign="top">Keterangan</td><td valign="top"></td><td valign="top"><textarea class="form-control-sm" name="keterangan" cols="50" rows="4">'.$keterangan.'</textarea></td></tr>
		</table>
	</td>';
print '<input name="kod_caw" type="hidden" value="321"><input name="no_siri" type="hidden" value="S112"><input name="tarikh_bank" type="hidden" value="01/10/2006"></tr>';

if($no_baucer) { 
$straction = ($action=='view'?'Kemaskini':'Simpan');
print '
<tr>
	<td>
	<input type="button" name="print" value="Cetak" class="btn btn-secondary waves-light waves-effect" onClick= "print_(\'voucherPaymentPrint.php?id='. $no_baucer .'\')">&nbsp;
	<input type="button" name="action" value="'.$straction.'" class="btn btn-primary waves-effect waves-light" onclick="CheckField(\''. $straction. '\')">';
if($straction=='Simpan') print '
	<input type="hidden" name="simpan" value="1">';
print '
	</td>
</tr>';
}

$strTemp = '
	</table>
</form></form>
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
  
		  if(e.elements[c].name=="debit2" && e.elements[c].value==\'\') {
			alert(\'Ruang amaun perlu diisi!\');
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