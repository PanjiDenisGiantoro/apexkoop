<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: resit.php
*			Date 		: 19/10/2006
*********************************************************************************/
include("header.php");
include("koperasiQry.php");
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0 ) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$strHeaderTitle = '&nbsp;</b><a class="maroon" href="?vw=ACCpurchaseInvoiceList&mn='.$mn.'">SENARAI</a><b>'.'&nbsp;>&nbsp;PEMBAYARAN INVOIS (PI)</b>';

if (!isset($mm))	$mm=date("m");
if (!isset($yy))	$yy=date("Y");
$yymm = sprintf("%04d%02d", $yy, $mm);

$display= 0;
if($PINo && $action=="view"){
	$sql = "SELECT *
			FROM   cb_purchaseinv a, generalacc b WHERE  a.companyID = b.ID and a.PINo = '".$PINo."'";

	$rs 			= $conn->Execute($sql);
	$PINo 			= $rs->fields(PINo);
	$tarikh_PI 		= $rs->fields(tarikh_PI);
	$tarikh_PI 		= substr($tarikh_PI,8,2)."/".substr($tarikh_PI,5,2)."/".substr($tarikh_PI,0,4);
	$tarikh_PI 		= toDate("d/m/y",$rs->fields(tarikh_PI));
	$batchNo 		= $rs->fields(batchNo);
	$companyID 	    = $rs->fields(companyID);
	$bayar_nama 	= $rs->fields(bayar_nama);
	$catatan 		= $rs->fields(catatan);
	$createdDate 	= $rs->fields(createdDate);
	$createdBy 		= $rs->fields(createdBy);
	$updatedDate 	= $rs->fields(updatedDate);
	$updatedBy 		= $rs->fields(updatedBy);
	$purcNo			= $rs->fields(purcNo);
	$amt			= $rs->fields(outstandingbalance);
	$cara_byr		= $rs->fields(cara_byr);	
	$accountNo 		= $rs->fields(accountNo);
	$keranisedia	= $rs->fields(keranisedia);
	$keranisemak	= $rs->fields(keranisemak);
	$b_Baddress 	= $rs->fields(b_Baddress);
	$code 			= $rs->fields(code);
	$nama			= $rs->fields(name);
	$kodGL 			= $rs->fields(b_kodGL);


	// kod carta akaun
	//-----------------
	$sql2 = "SELECT * FROM transactionacc WHERE docNo = '". $PINo ."' AND addminus IN (0) ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
	if($rsDetail->RowCount()<1) 
		$noTran = true;


}elseif($action=="new"){  
	$getNo = "SELECT MAX(CAST(right(PINo,6) AS SIGNED INTEGER)) AS nombor FROM cb_purchaseinv";

	
	$rsNo = $conn->Execute($getNo);
	$tarikh_PI = date("d/m/Y");
	$tarikh_batch = date("d/m/Y");
	if($rsNo){
		$nombor = intval($rsNo->fields(nombor)) + 1; 
		$nombor = sprintf("%06s",$nombor);
		$PINo = 'PI'.$nombor;
	}else{
		$PINo = 'PI000001';
	} 
}

if (!isset($tarikh_PI)) $tarikh_PI = date("d/m/Y");
if (!isset($tarikh_batch)) $tarikh_batch = date("d/m/Y");

if($perkara2){
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s");
	$createdBy 	= get_session("Cookie_userName");
	$createdDate = date("Y-m-d H:i:s");
	
		$deductID = $perkara2; 		
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
				  "addminus," . 
				  "price," . 
				  "quantity," . 
				  "pymtID," .
				  "pymtAmt," .				  
				  "desc_akaun," .
				  "status," .
				  "isApproved," .			
				  "approvedDate," . 
				  "createdDate," . 
				  "createdBy," . 
				  "tarikh_batch) " . 

				  " VALUES (" . 
				"'". $PINo . "', ".
				"'". 8 . "', ".
				"'". $batchNo . "', ".
				"'". $deductID . "', ".
				"'". $deductID . "', ".				
				"'". $addminus . "', ".
				"'". $price2 . "', ".
				"'". $quantity2 . "', ".
				"'". 66 . "', ".
				"'". $kredit2 . "', ".
				"'". $desc_akaun2 . "', ".
				"'". $status . "', ".
				"'". $isApproved . "', ".
				"'". $updatedDate . "', ".
				"'". $createdDate . "', ".
				"'". $createdBy . "', ".
				"'". $tarikh_batch . "')";

		if($display) print $sSQL.'<br />';
		else{ $rs = &$conn->Execute($sSQL);
		print '<script>
		window.location = "?vw=ACCpurchaseInvoice&mn='.$mn.'&action=view&PINo='.$PINo.'";
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
	window.location = "?vw=ACCpurchaseInvoice&mn='.$mn.'&action=view&PINo='.$PINo.'";
	</script>';
	}
}

elseif($action == "Kemaskini" || $perkara || $desc_akaun ) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "PINo='" . $PINo ."'";
		$tarikh_PI = saveDateDb($tarikh_PI);
		$tarikh_batch =	saveDateDb($tarikh_batch);	
		$sWhere = " WHERE (" . $sWhere . ")";	
		$sSQL	= "UPDATE cb_purchaseinv SET " .

					"PINo='" .$PINo . "',".
					"tarikh_PI='" .$tarikh_PI . "',".
					"batchNo='" .$batchNo . "',".
					"companyID='" .$companyID . "',".
					"cara_byr='" .$cara_byr . "',".
					"bayar_nama='" .$bayar_nama . "',".
					"catatan='" .$catatan . "',".
					"createdDate='" .$updatedDate . "',".
					"createdBy='" .$updatedBy . "',".
					"updatedDate='" .$updatedDate . "',".
					"updatedBy='" .$updatedBy . "',".
					"purcNo='" .$purcNo . "',".
					"outstandingbalance='" .$amt . "',".					
					"balance='" .$balance . "',".
					"keranisedia='" .$keranisedia . "',".
					"keranisemak='" .$keranisemak . "'";
		$sSQL = $sSQL . $sWhere;

		$sSQL1 = "";
		$sWhere1 = "";		
	 	$sWhere1 = "docNo='".$PINo."' AND addminus='". 1 ."'";
		$sWhere1 = " WHERE (".$sWhere1.")";		
		$sSQL1	= "UPDATE transactionacc SET ".
					"deductID='" .$kodGL."',".
					"batchNo='" .$batchNo."',".
					"pymtAmt='" .$masterAmt . "'";
				
		$sSQL1 = $sSQL1 . $sWhere1;

		$sSQL2 = "";
		$sWhere2 = "";		
	 	$sWhere2 = "docNo='".$PINo."'";
		$sWhere2 = " WHERE (".$sWhere2.")";		
		$sSQL2	= "UPDATE transactionacc SET ".
					"tarikh_doc='" .$tarikh_PI . "'";
					
		$sSQL2 = $sSQL2 . $sWhere2;

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);
			$rs = &$conn->Execute($sSQL2);

	/////////////////////////////////////

	if(count($perkara)>0){
		foreach($perkara as $id =>$value){

		$deductID = $value;

		$priceA = $price[$id];
		$quantityA = $quantity[$id];
		if($debit[$id]){
		$pymtAmt = $debit[$id];
		$addminus = 1;
		}else{
		$pymtAmt = $kredit[$id];
		$addminus = 0;
		}
		//$no_ruj = $ruj[$id];
		$sSQL = "";
		$sWhere = "";		
	    $sWhere = "ID='" . $id ."'";
	    $sSQL	= "UPDATE transactionacc SET " .
	     	"batchNo= '" . $batchNo . "'".
          	",deductID= '" . $deductID . "'".
          	",addminus= '" . $addminus . "'".
			",price= '" . $priceA . "'".
			",quantity= '" . $quantityA . "'".
          	",pymtAmt= '" . $pymtAmt . "'".
		  	",updatedDate= '" .$updatedDate . "'".
          	",updatedBy= '" .  $updatedBy . "'" ;

		$sSQL .= " where " . $sWhere;
		if($display) print $sSQL.'<br />';
		else $rs = &$conn->Execute($sSQL);
		}	
	}
///////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////

	if(!$display){
	print '<script>
	window.location = "?vw=ACCpurchaseInvoice&mn='.$mn.'&action=view&PINo='.$PINo.'";
	</script>';
	}
}

//pilihan simpan
 elseif($action == "Simpan" || $simpan) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$tarikh_PI = saveDateDb($tarikh_PI);
		$tarikh_batch =	saveDateDb($tarikh_batch);
		$sSQL = "";
		$sSQL	= "INSERT INTO cb_purchaseinv (" . 
					
					"PINo, " .
					"tarikh_PI, " .
					"batchNo, " .
					"cara_byr, " .
					"companyID, " .
					"bayar_nama, ".
					"catatan, ".
					"createdDate, " .
					"createdBy, " .
					"updatedDate, " .
					"updatedBy, " .
					"purcNo, ".
					"outstandingbalance, ".
					"keranisedia, ".
					"keranisemak) " .
					
		            " VALUES (" . 					
					"'". $PINo . "', ".
					"'". $tarikh_PI . "', ".
					"'". $batchNo . "', ".
					"'". $cara_byr . "', ".
					"'". $companyID . "', ".
					"'". $bayar_nama . "', ".
					"'". $catatan . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "', ".
					"'". $updatedDate . "', ".
					"'". $updatedBy . "', ".
					"'". $purcNo . "', ".
					"'". $amt . "', ".
					"'". $keranisedia . "', ".
					"'". $keranisemak . "')";

		$sSQL1  = "";
		$sSQL1	= "INSERT INTO transactionacc (" . 	
				  "docNo," . 
				  "tarikh_doc," .
				  "docID," .  
				  "batchNo," . 
				  "deductID," .
				  "MdeductID," . 
				  "addminus," . 
				  "price," . 
				  "quantity," .  
				  "pymtID," .
				  "pymtAmt," .				  
				  "desc_akaun," .
				  "pymtRefer," . 
				  "status," .
				  "isApproved," .			
				  "approvedDate," . 
				  "createdDate," . 
				  "createdBy," . 
				  "tarikh_batch) " . 
				  " VALUES (" . 
				"'". $PINo . "', ".
				"'". $tarikh_PI . "', ".
				"'". 8 . "', ".
				"'". $batchNo . "', ".
				"'". $kodGL . "', ".	
				"'". $kodGL . "', ".			
				"'". 1 . "', ".
				"'". $price2 . "', ".
				"'". $quantity2 . "', ".
				"'". 66 . "', ".
				"'". $masterAmt . "', ".
				"'". $desc_akaun2 . "', ".
				"'". $bayar_nama . "', ".
				"'". $status . "', ".
				"'". $isApproved . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedDate . "', ".
				"'". $updatedBy . "', ".
				"'". $tarikh_batch . "')";					

		if($display) print $sSQL.'<br />';
		else 
			$rs = &$conn->Execute($sSQL);
			$rs = &$conn->Execute($sSQL1);

	$getMax = "SELECT MAX(CAST(right(PINo,6) AS SIGNED INTEGER)) AS no FROM cb_purchaseinv";
	$rsMax = $conn->Execute($getMax);
	$max = sprintf("%06s", $rsMax->fields(no));
	if(!$display){
	print '<script>
	window.location = "?vw=ACCpurchaseInvoice&mn='.$mn.'&action=view&add=1&PINo=PI'.$max.'";
	</script>';
	}
}
 
$strTemp .=
'<div class="table-responsive"><div class="maroon" align="left">'.$strHeaderTitle.'</div>'
.'<div style="width: 100%; text-align:left">'
.'<div>&nbsp;</div>'
.'<form name="MyForm" action="?vw=ACCpurchaseInvoice&mn='.$mn.'" method="post">'
.'<table border="0" cellspacing="0" cellpadding="3" width="100%" align="center">';

print $strTemp;
print 
'<tr>
	<td width="48%">
		<table border="0" cellspacing="1" cellpadding="2">
			
			<tr>
				<td>No. PI</td>
				<td valign="top"></td>
				<td>
					<input  name="PINo" value="'.$PINo.'" type="text" size="20" maxlength="50" class="form-controlx" readonly/>
				</td>
			</tr>

			<tr>
				<td>Batch</td>
				<td valign="top"></td>
				<td>'.selectbatchPI($batchNo,'batchNo').'</td>
			</tr>

			<tr>
				<td>Tarikh</td>
				<td valign="top"></td>
				<td><input class="form-controlx" name="tarikh_PI" value="'.$tarikh_PI.'" type="text" size="20" maxlength="10" ></td>
			</tr>	
			</table>
	</td>
</tr>	

<tr><td colspan="3"><hr class="mt-3" /></td></tr>';

print '
<tr colspan="3">
	<td valign="top"><input name="j" type="hidden" value="tiada">

<table border="0" cellspacing="1" cellpadding="2">

<tr>
<td>* Kod Pemiutang</td><td valign="top"></td>
<td><input name="code" value="'.$code.'" type="text" size="20" maxlength="50"  class="form-controlx" readonly/>&nbsp;';

print '<input type="button" class="btn btn-sm btn-info" value="Pilih" onclick="window.open(\'ACCidpemiutangPI.php?refer=f\',\'sel\',\'top=10,left=10,width=950,height=500,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');">';

print '&nbsp;

</td>
</tr>

<tr>
 <td valign="top">Nama Syarikat</td>
 <td valign="top"></td>
 <td><input name="nama"  value="'.$nama.'" size="40" maxlength="50"  class="form-controlx" readonly /></td>
 </tr>
<tr>
<td valign="top">Alamat Syarikat</td>
<td valign="top"></td>
<td><textarea name="b_Baddress" cols="50" rows="4" class="form-controlx" readonly>'.$b_Baddress.'</textarea></td>
</tr>

   <tr>
 <td valign="top">Amaun Purchase Order (RM)</td>
 <td valign="top"></td>
 <td><input name="amt"  value="'.$amt.'" size="10" maxlength="50"  class="form-controlx" readonly/></td>
 </tr>

  <tr>
 <td valign="top">No. Purchase Order</td>
 <td valign="top"></td>
 <td><input name="purcNo" value="'.$purcNo.'" size="40" maxlength="50"  class="form-controlx" readonly /></td>
 </tr>

 <tr>
 <td valign="top">Cara Bayar</td>
 <td valign="top"></td>
 <td>'.selectbayar($cara_byr,'cara_byr').'</td>
 </tr>

 <tr>
</td><td><input type=hidden name="companyID" value="'.$companyID.'" type="text" size="4" maxlength="50" class="form-controlx" />
</td>
</tr>
<tr>
</td><td><input type=hidden name="kodGL" value="'.$kodGL.'" type="text" size="4" maxlength="50" class="form-controlx" />
</td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>';
	//----------
	if ($action=="view" && !is_int(dlookup("transactionacc", "ID", "docNo='".$PINo."'"))){
print '
	<tr>
		<td align= "right" colspan="3">';
	    if(!$add) print '
			<input type="button" name="add" value="Tambah" class="btn btn-sm btn-primary" onClick="window.location.href=\'?vw=ACCpurchaseInvoice&mn='.$mn.'&action='.$action.'&PINo='.$PINo.'&add=1\';">'; 
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
		<table border="0" cellspacing="1" cellpadding="3" width="100%" class="table table-sm table-striped">
			<tr class="table-primary">
				<td nowrap="nowrap"><b>Bil</b></td>
				<td nowrap="nowrap"><b>Perkara</b></td>
				<td nowrap="nowrap"><b>Keterangan</b></td>
				<td nowrap="nowrap">Kuantiti</td>
				<td nowrap="nowrap">Harga Seunit (RM)</td>
				<td nowrap="nowrap" align="right"><b>Amaun (RM)</b></td>
				<td nowrap="nowrap">&nbsp;</td>
			</tr>';

if ($action=="view"){

	$i = 0;
	while (!$rsDetail->EOF) {

	$id 		= $rsDetail->fields(ID);
	$ruj 		= $rsDetail->fields(pymtRefer);
	$perkara 	= $rsDetail->fields(deductID);

	$kod_akaun 	= dlookup("generalacc", "c_Panel", "ID=" . $perkara);
	$kredit 	= $rsDetail->fields(pymtAmt);
	$desc_akaun =	$rsDetail->fields(desc_akaun);

	$quantity = $rsDetail->fields(quantity);
	$price = $rsDetail->fields(price);

	$a_Keterangan = dlookup("generalacc", "code", "ID=" . $perkara);

		if($rsDetail->fields(addminus)){
			$kredit = $rsDetail->fields(pymtAmt);
		}else{
			$debit = $rsDetail->fields(pymtAmt);
		}

	print	   
			'<tr>
				<td class="Data">&nbsp;'.++$i.'.</td>	

				<td class="Data" nowrap="nowrap">'.strSelect3($id,$perkara).'&nbsp;</td>

				<td class="Data" nowrap="nowrap">
					<input name="desc_akaun['.$id.']" type="text" size="75" maxlength="100" class="form-control-sm" value="'.$desc_akaun.'"/>&nbsp;
				</td>

				<td class="Data" >
					<input name="quantity['.$id.']" type="text" class="form-control-sm" size="10" maxlength="10" value="'.$quantity.'" "/>&nbsp;
				</td>

				<td class="Data">
					<input name="price['.$id.']" type="text" class="form-control-sm" size="10" maxlength="10" value="'.$price.'" "/>&nbsp;
				</td>

				<td class="Data" align="right">
					<input name="kredit['.$id.']" type="text" size="10" maxlength="10" value="'.$kredit.'" class="form-control-sm" style="text-align:right;"/>&nbsp;
				</td>

				<td class="Data" align="left"><input type="checkbox" name="pk[]" class="form-check-input" value="'.$id.'"></td>

			</tr>';

	$totalKt += $kredit;
	$baki=$amt-$totalKt;
	$kredit = '';
	$rsDetail->MoveNext();
	}
}

$strDeductIDList = deductListb2(1);
$strDeductCodeList = deductListb2(2);
$strDeductNameList = deductListb2(3);
$name = 'perkara2';

$strSelect = '<select name="'.$name.'" class="form-select-sm">
			 <option value="">- Pilih -';
			for ($i = 0; $i < count($strDeductIDList); $i++) {
				$strSelect .= '	<option value="'.$strDeductIDList[$i].'" ';
				if ($code == $strDeductIDList[$i]) $strSelect .= ' selected';
				$strSelect .=  '>'.$strDeductCodeList[$i] .'&nbsp;&nbsp;'.$strDeductNameList[$i].'';
			}
$strSelect .= '</select>';

if($add){
print	   '
			<tr>
				<td class="Data" nowrap="nowrap">&nbsp;</td>					

				<td class="Data">'.$strSelect.'</td>

				<td class="Data" align="left">
					<input name="desc_akaun2" type="text" size="75" maxlength="100" class="form-control-sm" value="'.$desc_akaun2.'" align="right"/>&nbsp;
				</td>

				<td class="Data" >
					<input name="quantity2" type="text" class="form-control-sm" size="10" maxlength="100" value="'.$quantity2.'" align="right"/>&nbsp;
				</td>

				<td class="Data" >
					<input name="price2" type="text" class="form-control-sm" size="10" maxlength="100" value="'.$price2.'" align="right"/>&nbsp;
				</td>

				<td class="Data" align="right">
					<input type="hidden" name="ruj2" val="0">
					<input name="kredit2" type="text" size="10" class="form-control-sm" maxlength="10" value="'.$kredit2.'" />&nbsp;
				</td>

				<td class="Data" align="left"></td>
			</tr>';
}

//bahagian bawah skali
if($totalKt<>0){
	$clsRM->setValue($baki);
	$clsRM->setValue($totalKt);
	$strTotal = ucwords($clsRM->getValue()).' Sahaja.';
}

print 		'<tr class="table-secondary">
				<td class="Data" align=""><b>&nbsp;</b></td>
				<td class="Data" colspan="4" align="right"><b>Jumlah (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($totalKt,2).'&nbsp;</b></td>
				<td class="Data" align="right"></td>
			</tr>

			<tr class="table-secondary">

				<td class="Data" align=""><b>&nbsp;</b></td>
				<td class="Data" colspan="4" align="right"><b>Baki Purchase Order (RM)</b></td>
				<td class="Data" align="right"><b>'.number_format($baki,2).'&nbsp;</b></td>
				<td class="Data" align="right"></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr colspan="3">
	<td valign="top">
		<table border="0" cellspacing="1" cellpadding="3">
		
		<tr>
			<td nowrap="nowrap"></td>
			<td>
				<input class="Data" type="hidden" name="masterAmt" value="'.$totalKt.'">				
				<input class="Data" type="hidden" name="balance" value="'.$baki.'">				
				<input class="Data" type="hidden" name="bankparent" value="'.$bankparent.'">
			</td>
		</tr>


		<tr>
				<td nowrap="nowrap">Disediakan Oleh</td><td valign="top"></td>
				<td>'.selectAdmin($keranisedia,'keranisedia').'</td>
			</tr>

			<tr>
				<td nowrap="nowrap">Disemak Oleh</td><td valign="top"></td>
				<td>'.selectAdmin($keranisemak,'keranisemak').'</td>
			</tr>
			
			<tr>
				<td nowrap="nowrap" valign="top">Catatan</td><td valign="top"></td>
				<td valign="top">
					<textarea class="form-controlx" name="catatan" cols="50" rows="4">'.$catatan.'</textarea></td>
			</tr>
		
		</table>
	</td>';
print '<input name="kod_caw" type="hidden" value="321"><input name="no_siri" type="hidden" value="S112"><input name="tarikh" type="hidden" value="01/10/2006"></tr>';


if($PINo) { 
$straction = ($action=='view'?'Kemaskini':'Simpan');
print '
<tr>
	<td>
	<input type="button" name="print" value="Cetak" class="btn btn-secondary" onClick= "print_(\'ACCPurchaseInvoicePrint.php?id='. $PINo .'\')">&nbsp;
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