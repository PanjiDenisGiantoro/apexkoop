<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: resit.php
*			Date 		: 19/10/2006
*********************************************************************************/
//include("header.php");
include("koperasiQry.php");	
date_default_timezone_set("Asia/Kuala_Lumpur");

if (get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 4 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$strHeaderTitle = '&nbsp;</b><a class="maroon" href="?vw=resitList&mn=908">SENARAI</a><b>'.'&nbsp;>&nbsp;RESIT KOPERASI</b>';

if (!isset($mm))	$mm=date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);

$display= 0;
if($no_resit && $action=="view"){
	$sql = "SELECT a.*,b.memberID,b.address, b.city, b.postcode, b.stateID, b.departmentID, c.name FROM  resit a, userdetails b, users c WHERE b.userID = c.userID and a.bayar_nama = b.memberID and a.no_resit = '". $no_resit ."'";
	$rs = $conn->Execute($sql);


	$no_resit = $rs->fields(no_resit);
	$tarikh_resit 	= $rs->fields(tarikh_resit);
	$tarikh_resit 	= substr($tarikh_resit,8,2)."/".substr($tarikh_resit,5,2)."/".substr($tarikh_resit,0,4);
	$tarikh_resit 	= toDate("d/m/y",$rs->fields(tarikh_resit));
	
	$no_bond = $rs->fields(bayar_kod);
	$bayar_nama = $rs->fields(name);
	$no_anggota = $rs->fields(memberID);
//---
	$deptID			=  $rs->fields('departmentID');
	$departmentAdd	=  dlookup("general", "b_Address", "ID=" . tosql($deptID, "Number"));
	$alamat = strtoupper(strip_tags($departmentAdd));
//-----------------
	$cara_bayar = $rs->fields(cara_bayar);
	$kod_siri = $rs->fields(kod_siri);
	$tarikh = toDate("d/m/y",$rs->fields(tarikh));
	$akaun_bank = $rs->fields(akaun_bank);
	$kerani = $rs->fields(kerani);

	$kod_bank = $rs->fields(kod_bank);
	$bankparent 	= dlookup("generalacc", "parentID", "ID=" . $kod_bank);

	$catatan = $rs->fields(catatan);
	$masterAmt		= $rs->fields(pymtAmt);
	$batchNo 		= $rs->fields(batchNo);

	$sql2 = "SELECT * FROM transaction WHERE docNo = '".$no_resit."' ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
 
}elseif($action=="new"){  
	$getNo = "SELECT MAX(CAST(right(no_resit,6) AS SIGNED INTEGER)) AS nombor FROM resit";
	$rsNo = $conn->Execute($getNo);
	$tarikh_resit = date("d/m/Y");
	$tarikh = date("d/m/Y");
	if($rsNo){
		$nombor = intval($rsNo->fields(nombor)) + 1; 
		$nombor = sprintf("%06s",  $nombor);
		$no_resit = 'RT'.$nombor;
	}else{
		$no_resit = 'R000001';
	} 
}

if (!isset($tarikh_resit)) $tarikh_resit = date("d/m/Y");

if($perkara2){
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s");     
	$tarikh_resit = saveDateDb($tarikh_resit);          

		$deductID = &$perkara2;
		$addminus = 1;
		$cajAmt = 0.0;
		$userID = dlookup("userdetails","userID","memberID = '". $no_anggota . "'");
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
				"'". $no_resit . "', ".
				"'". $userID . "', ".
				"'". $yymm . "', ".
				"'". $deductID . "', ".
				"'". 79 . "', ".
				"'". $addminus . "', ".
				"'". 66 . "', ".
				"'". $ruj2 . "', ".
				"'". $kredit2 . "', ".
				"'". $cajAmt . "', ".
				"'". $tarikh_resit . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "')";

		$last_id = mysqli_insert_id();		
			

		$sSQL1	= "INSERT INTO transactionacc (" . 
				  "docNo," . 
				  "docID," . 
				  "IDtrans," . 
				  "tarikh_doc," . 
				  "batchNo," . 
				  "userID," . 
				  "yrmth," .			
				  "deductID," . 
				  "MdeductID," . 		
				  "addminus," . 
				  "pymtID," . 
				  "pymtRefer," .			
				  "pymtAmt," . 
				  "createdDate," . 
				  "createdBy," . 
				  "updatedDate," . 
				  "updatedBy)" . 
				  " VALUES (" . 
				"'". $no_resit . "', ".
				"'". 10 . "', ".
				"'". $last_id . "', ".
				"'". $tarikh_resit . "', ".
				"'". $batchNo . "', ".
				"'". $userID . "', ".
				"'". $yymm . "', ".
				"'". $deductID . "', ".				
				"'". $bankparent . "', ".
				"'". $addminus . "', ".
				"'". 66 . "', ".
				"'". $ruj2 . "', ".
				"'". $kredit2 . "', ".
				"'". $tarikh_resit . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "')";


		if($display) print $sSQL.'<br />';
		else{ 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
		print '<script>
		window.location = "?vw=resit&mn=908&action=view&no_resit='.$no_resit.'";
		</script>';
		}
}

if($action=="Hapus"){
  if(count($pk)>0){
	$sWhere = "";
	foreach($pk as $val) {
		

		$sSQL = '';
		$sWhere = "ID='" . $val. "'";
		$sSQL = "DELETE FROM transaction WHERE " . $sWhere;

		$sSQL1 = '';
		$sWhere = "IDtrans='" . $val. "'";
		$sSQL1 = "DELETE FROM transactionacc WHERE " . $sWhere;
		

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
	}
  }
	if(!$display){
	print '<script>
	window.location = "?vw=resit&mn=908&action=view&no_resit='.$no_resit.'";
	</script>';
	}
}elseif($action == "Kemaskini" || $perkara) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "no_resit='" . $no_resit ."'";
		$sWhere = " WHERE (" . $sWhere . ")";		
		$tarikh_resit = saveDateDb($tarikh_resit);
		$tarikh = saveDateDb($tarikh);
		$sSQL	= "UPDATE resit SET " .
					"alamat='" . $alamat . "',".
					"cara_bayar='" . $cara_bayar . "',".
					"kod_siri='" . $kod_siri . "',".
					"tarikh='" . $tarikh . "',".
					"tarikh_resit='" . $tarikh_resit . "',".
					"batchNo='" .$batchNo . "',".
					"akaun_bank='" . $akaun_bank . "',".
					"kod_bank='" . $kod_bank . "',".
					"kerani='" . $kerani . "',".
					"catatan='" . $catatan . "',".
					"pymtAmt='" .$masterAmt . "',".
					"StatusID_Pymt='". 0 . "',".
					"updatedDate='" . $updatedDate . "',".
					"updatedBy='" . $updatedBy ."'";		
		$sSQL = $sSQL . $sWhere;

		$sSQL1 = "";
		$sWhere1 = "";		
	 	$sWhere1 = "docNo='" . $no_resit ."' AND addminus='" . 0 ."'";
		$sWhere1 = " WHERE (" . $sWhere1 . ")";		
		$sSQL1	= "UPDATE transactionacc SET 
					"."deductID='" .$kod_bank . "',
					"."MdeductID='" .$bankparent . "',
					"."docID='". 10 . "',
					"."pymtAmt='" .$masterAmt . "'";
					
		$sSQL1 = $sSQL1 . $sWhere1;

		$sSQL2 = "";
		$sWhere2 = "";		
	 	$sWhere2 = "docNo='" . $no_resit ."'";
		$sWhere2 = " WHERE (" . $sWhere2 . ")";		
		$sSQL2	= "UPDATE transaction SET 
					"."createdDate='" .$tarikh_resit. "'";
					
		$sSQL2 = $sSQL2 . $sWhere2;

		$sSQL3 = "";
		$sWhere3 = "";		
	 	$sWhere3 = "docNo='" . $no_resit ."' AND addminus='" . 1 ."'";
		$sWhere3 = " WHERE (" . $sWhere3 . ")";		
		$sSQL3	= "UPDATE transactionacc SET 
					"."tarikh_doc='" .$tarikh_resit. "'";
					
		$sSQL3 = $sSQL3 . $sWhere3;

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
			$rs = &$conn->Execute($sSQL2);
			$rs = &$conn->Execute($sSQL3);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(count($perkara)>0){
		foreach($perkara as $id =>$value){
		$deductID = $value;
		$pymtAmt = $kredit[$id];
		$addminus = 1;
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

		$sSQL1 = "";
		$sWhere1 = "";		
	  $sWhere1 = "IDtrans='" . $id ."'";
	  $sSQL1	= "UPDATE transactionacc SET " .
          "deductID= '" . $deductID . "'".
          ",addminus= '" . $addminus . "'".
          ",pymtAmt= '" . $pymtAmt . "'".
		 	 		",updatedDate= '" .$updatedDate . "'".
          ",updatedBy= '" .  $updatedBy . "'" ;
		$sSQL1 .= " where " . $sWhere1;

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
		}	
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(count($kod_objek)>0){
		foreach($kod_objek as $id =>$value){
	
		$MdeductID = $value;
		$pymtAmt = $kredit[$id];
		$addminus = 1;
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
		else 
			$rs = &$conn->Execute($sSQL);
		}	
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(!$display){
	print '<script>
	window.location = "?vw=resit&mn=908&action=view&no_resit='.$no_resit.'";
	</script>';
	}

} elseif($action == "Simpan" || $simpan) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$tarikh_resit = saveDateDb($tarikh_resit);
		$tarikh = saveDateDb($tarikh);
		$sSQL = "";
		$sSQL = "";
		$sSQL	= "INSERT INTO resit (" . 
					"no_resit, " .
					"tarikh_resit, " .
					"batchNo, " .
					"bayar_kod, " .
					"bayar_nama, " .
					"alamat, " .
					"cara_bayar, " .
					"kod_siri, " .
					"tarikh, " .
					"akaun_bank, " .
					"kod_bank, " .
					"kerani, " .
					"pymtAmt, ".
					"StatusID_Pymt, ".
					"catatan, " .
					"createdDate, " .
					"createdBy, " .
					"updatedDate, " .
					"updatedBy) " .
		            " VALUES (".
					"'". $no_resit . "', ".
					"'". $tarikh_resit . "', ".
					"'". $batchNo . "', ".
					"'". $no_bond . "', ".
					"'". $no_anggota . "', ".
					"'". $alamat . "', ".
					"'". $cara_bayar . "', ".
					"'". $kod_siri . "', ".
					"'". $tarikh . "', ".
					"'". $akaun_bank . "', ".
					"'". $kod_bank . "', ".
					"'". $kerani . "', ".
					"'". $masterAmt . "', ".
					"'". 0 . "', ".
					"'". $catatan . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy  . "') ";	   

		$sSQL1 = "";
		$sSQL1	= "INSERT INTO transactionacc (" . 
					
				  "docNo," . 
				  "tarikh_doc," . 
				  "batchNo," .
				  "userID," . 
				  "yrmth," . 
				  "deductID," .				
				  "addminus," . 			  		
				  "pymtAmt," .	
				  "updatedBy," . 
				  "updatedDate	," . 
				  "createdBy," . 
				  "createdDate) " . 

				  " VALUES (" . 
				"'". $no_resit . "', ".
				"'". $tarikh_resit . "', ".
				"'". $batchNo . "', ".
				"'". $no_anggota . "', ".
				"'". $yymm . "', ".
				"'". $kod_bank . "', ".
				"'". 0 . "', ".
				"'". $masterAmt . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $updatedDate . "')";

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);

	$getMax = "SELECT MAX(CAST(right(no_resit,6) AS SIGNED INTEGER )) as no FROM resit";
	$rsMax = $conn->Execute($getMax);
	$max = sprintf("%06s", $rsMax->fields(no));
	if(!$display){
	print '<script>
	window.location = "?vw=resit&mn=908&action=view&add=1&no_resit=RT'.$max.'";
	</script>';
	}
}

$strTemp .=
'<div class="maroon" align="left">'.$strHeaderTitle.'</div>'
.'<div style="width: 100%; text-align:left">'
.'<div>&nbsp;</div><div class="table-responsive">'
.'<form name="MyForm" action="?vw=resit&mn=908" method="post">'
.'<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center">';

print $strTemp;
print '
<tr>
	<td width="48%">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td valign="top">No. Resit</td>
				<td valign="top"></td><td><input class="form-control-sm" name="no_resit" value="'.$no_resit.'" type="text" size="20" maxlength="50" readonly/></td>
			</tr>

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
	<td valign="top">&nbsp;</td>
	<td width="48%" align="right">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td valign="top" align="right">Tarikh</td><td valign="top"></td><td><input name="tarikh_resit" value="'.$tarikh_resit.'" class="form-control-sm" type="text" size="20" maxlength="10" /></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td colspan="3"><hr class="mt-3"/></td></tr>
<tr><td colspan="3">Diterima daripada </td></tr>
<tr>
	<td valign="top">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td>* No. Koperasi</td><td valign="top"></td>
				<td><input name="no_anggota" value="'.$no_anggota.'" type="text" size="20" maxlength="50"  class="form-control-sm" readonly/>&nbsp;'; 
				if($action=="new" && $jenis == 1) print '<input type="button" class="btn btn-sm btn-info waves-light waves-effect" value="Pilih" onclick="window.open(\'selToMember.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';
				else if($action=="new" && $jenis == 2) print '<input type="button" class="btn btn-sm btn-info waves-light waves-effect" value="Pilih" onclick="window.open(\'selLoanS.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';
				print '&nbsp;<input name="loan_no" type="hidden" value="">&nbsp;</td>
			</tr>
			<tr><td valign="top">Nama</td><td valign="top"></td><td><input name="nama_anggota"  value="'.$bayar_nama.'" type="text" size="40" maxlength="50" class="form-control-sm" readonly/>
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
		</table>
	</td>
	<td valign="top">&nbsp;</td>
	<td width="48%" align="right" valign="top">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td valign="top" align="right">Cara Bayaran</td><td valign="top"></td>
				<td><input name="cara_bayar" value="'.$cara_bayar.'" class="form-control-sm" type="text" size="20" maxlength="10" /></td>
			</tr>
			<tr>
				<td valign="top" align="right">Kod & No. Siri</td><td valign="top"></td>
				<td><input name="kod_siri" value="'.$kod_siri.'" class="form-control-sm" type="text" size="20" maxlength="10" /></td>
			</tr>
			<tr>
				<td valign="top" align="right">Tarikh Bayaran</td><td valign="top"></td>
				<td><input name="tarikh" value="'.$tarikh.'" type="text" class="form-control-sm" size="20" maxlength="10" /></td>
			</tr>
			<tr>
				<td valign="top" align="right">Master Amaun (RM)</td><td valign="top"></td>
				<td><input value="'.$masterAmt.'" type="text" class="form-control-sm" size="20" maxlength="10"/></td>
			</tr>
			
			
		<tr>
<td align="right"><input type="button" class="btn btn-sm btn-secondary" name="GetPicture" value="Muat Naik Resit"  onclick= "Javascript:(window.location.href=\'?vw=uploadwinresit&mn=908&no_resit='.$no_resit.'\')"></td><td valign="top" align="right"></td><td><input name="pic" value="'.$pic.'" type="text" size="20" class="form-control-sm" maxlength="50" class="data" readonly /></td>
			</tr>
		</table>
	</td>
</tr>		
<tr><td>&nbsp;</td></tr>';		
if ($action=="view" && !is_int(dlookup("transaction", "ID", "docNo='".$no_resit."'"))){
print '
<tr>
	<!--td align= "left"><input type="checkbox" onClick="ITRViewSelectAll()" class="Data">Tanda semua</td-->
	<td align= "right" colspan="3">';
if(!$add) print '
		<input type="button" name="add" value="Tambah" class="btn btn-sm btn-primary" onClick="window.location.href=\'?vw=resit&mn=908&action='.$action.'&no_resit='.$no_resit.'&add=1\';">'; 
else print '
		<input type="button" name="action" value="Simpan" class="btn btn-sm btn-primary" onclick="CheckField(\'Kemaskini\')">';
print '&nbsp;<input type="submit" name="action" value="Hapus" class="btn btn-sm btn-danger">
	</td>
</tr>';
}
print 
'<tr>
	<td colspan="3">
		<table border="0" cellspacing="1" cellpadding="4" width="100%" class="table table-sm table-striped">
			<tr class="table-danger">
				<td nowrap="nowrap"><b>Bil</b></td>
				<td nowrap="nowrap"><b>* Perkara</b></td>
				<td nowrap="nowrap"><b>Kod Master Akaun</b></td>
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
	
	$kod_objek = dlookup("general", "c_master", "ID=" . $perkara);
	$namagl = dlookup("generalacc", "name", "ID=" . $kod_objek);

	$kod_akaun = dlookup("general", "c_Panel", "ID=" . $perkara);
	$keterangan2 = dlookup("general", "name", "ID=" . $kod_akaun);
	$kredit = $rsDetail->fields(pymtAmt);

print	   '
			<tr>
				<td class="Data">&nbsp;'.++$i.'.</td>				
				<td class="Data" nowrap="nowrap">'.strSelect2($id,$perkara).'&nbsp;
				<input class="form-control-sm" name="kod_objek['.$id.']" type="hidden" size="10" maxlength="10" value="'.$kod_objek.'"/>
				</td>

				<td class="Data" nowrap="nowrap">
				<input class="form-control-sm" name="namagl['.$id.']" type="text" size="30" maxlength="30" value="'.$namagl.'"/>
				<input class="form-control-sm" name="kod_objek['.$id.']" type="hidden" size="10" maxlength="10" value="'.$kod_objek.'"/>
				</td>

				<td class="Data" nowrap="nowrap">
					<input name="kod_akaun['.$id.']" type="text" size="8" maxlength="10" value="'.$kod_akaun.'"  class="form-control-sm" readonly/>&nbsp;
				</td>
				<td class="Data" align="right">
					<input name="ruj['.$id.']" type="hidden" value="'.$no_anggota.'"/>
					<input name="kredit['.$id.']" type="text" size="10" maxlength="10" value="'.$kredit.'" class="form-control-sm" style="text-align:right;"/>&nbsp;
				</td>
				<td class="Data" nowrap="nowrap"><input type="checkbox" class="form-check-input" name="pk[]" value="'.$id.'">&nbsp;</td>
			</tr>';
		  $totalKt += $kredit;
		  $kredit = '';
	$rsDetail->MoveNext();
	}
}

$strDeductIDList = deductList(1);
$strDeductCodeList = deductList(2);
$strDeductNameList = deductList(3);
$name = 'perkara2';

$strSelect = '<select class="form-select-sm" name="'.$name.'">
				<option value="">- Kod -';
			for ($i = 0; $i < count($strDeductIDList); $i++) {
				$strSelect .= '	<option value="'.$strDeductIDList[$i].'" ';
				if ($code == $strDeductIDList[$i]) $strSelect .= ' selected';
				$strSelect .=  '>'.$strDeductCodeList[$i] .'&nbsp;-&nbsp;'.$strDeductNameList[$i].'';
			}
$strSelect .= '</select>';

if($add){
print	   '<tr>
				<td class="Data" nowrap="nowrap">&nbsp;</td>
				<td class="Data">'.$strSelect.'
				<input name="kod_objek2" type="hidden" size="10" maxlength="10" value="'.$kod_objek2.'" class="form-control-sm"/>
				</td>

				<td class="Data">
				<input name="namagl2" type="text" size="30" maxlength="30" value="'.$namagl2.'" class="form-control-sm"/>
				<input name="kod_objek2" type="hidden" size="10" maxlength="10" value="'.$kod_objek2.'" class="form-control-sm"/>
				</td>
				
				<td class="Data" nowrap="nowrap">
					<input name="kod_akaun2" type="text" size="8" maxlength="10" value="'.$kod_akaun2.'"  class="form-control-sm" readonly/>&nbsp;
				</td>
				<td class="Data" align="right">
					<input name="ruj2" type="hidden" value="'.$no_bond.'"/>
					<input name="kredit2" type="text" size="10" maxlength="10" value="'.$kredit2.'" class="form-control-sm" style="text-align:right;"/>&nbsp;
				</td>
				<td class="Data" align="right"><b>&nbsp;</b></td>
			</tr>';
}

if($totalKt<>0){
	$clsRM->setValue($totalKt);
	$strTotal = ucwords($clsRM->getValue()).'Sahaja.';
}
print 		'<tr class="table-secondary">
				<td class="Data" colspan="4" align="right"><b>Jumlah (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($totalKt,2).'&nbsp;</b></td>
				<td class="Data" align="right"><b>&nbsp;</b></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td width="60%" valign="top" colspan="3">
		<table border="0" cellspacing="1" cellpadding="3">
			<tr>
			<td colspan="3" nowrap="nowrap">Jumlah Dalam Perkataan<br />
			<input name="" size="100" maxlength="100" value="'.$strTotal.'" class="form-control-sm" readonly>
			<input class="Data" type="hidden" name="masterAmt" value="'.$totalKt.'">
			<input class="Data" type="hidden" name="bankparent" value="'.$bankparent.'">
					</td>
			</tr>
			<tr>
			<td nowrap="nowrap">Kerani Kewangan</td><td valign="top"></td><td>'.selectAdmin($kerani,'kerani').'</td>
			</tr>
			<tr>
			<td nowrap="nowrap" valign="top">Catatan</td><td valign="top"></td><td valign="top"><textarea name="catatan" class="form-control-sm" cols="50" rows="4">'.$catatan.'</textarea></td>
			</tr>
		</table>
	</td>
</tr>';

if($no_resit) { 
$straction = ($action=='view'?'Kemaskini':'Simpan');
print '
<tr>
	<td>
	<input type="button" name="print" value="Cetak" class="btn btn-secondary" onClick= "print_(\'resitPaymentPrint.php?ID='. $no_resit .'\')">&nbsp;
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
</div></div>';

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
		  if(e.elements[c].name=="nama_anggota" && e.elements[c].value==\'\') {
			alert(\'Sila pilih anggota!\');
            count++;
		  }

		  if(act == \'Kemaskini\') {
		  //if(e.elements[c].name=="ruj2" && e.elements[c].value==\'\') {
		  //  alert(\'Ruang rujukan perlu diisi!\');
          //  count++;
		  //}
		  
		  if(e.elements[c].name=="kredit2" && e.elements[c].value==\'\') {
			alert(\'Ruang amaun perlu diisi!\');
            count++;
		  }
		  if(e.elements[c].name=="kod_bank" && e.elements[c].value==\'\') {
			alert(\'Pilih Bank!\');
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
</script>
';
include("footer.php");
?>