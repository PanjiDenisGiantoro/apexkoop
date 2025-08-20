<?php 
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	Edit_memberStmtPotongan.php
*          Date 		: 	04/12/2018
*********************************************************************************/
session_start();
include("header.php");	
include("koperasiQry.php"); 
date_default_timezone_set("Asia/Jakarta");
$title     = "Kemaskini Potongan Pembiayaan Bulanan";

$sFileName = "?vw=Edit_memberStmtPotongan&mn=$mn";
$sFileNameDel = "?vw=Edit_memberStmtPotongan&mn=$mn";
$sFileRef  = "?vw=Edit_memberStmtPotonganPokok&mn=$mn";

$IDName = get_session("Cookie_userName");

$ID = $_REQUEST['ID'];
$code = $_REQUEST['code'];
$edit = $_POST['edit'];
if (!isset($mth)) $mth	= date("n");                 		
if (!isset($yr)) $yr	= date("Y");
if (!isset($mm))	$mm=date("m");
if (!isset($yy))	$yy=date("Y");

$yrmthNow = sprintf("%04d%02d", $yr, $mth);
$yymm = $yy.$mm;
if ($code == 2){
$ID = $_REQUEST['ID'];

$sSQL = "select * from potbulan 	 
		 WHERE  userID = " . tosql($ID,"Text") . "
		 AND status IN (1) AND yrmth < '".$yrmthNow."'
  		 Group BY bondNo";
$rs = &$conn->Execute($sSQL);

$sSQL2 = "SELECT	DISTINCT a.*, b.*
		  FROM 	users a, userdetails b
		  WHERE a.UserID =".tosql($ID,"Text")."
		  AND a.UserID = b.UserID";

$rs1 =&$conn->Execute($sSQL2); 
}

if ($edit){

$updatedDate = date("Y-m-d H:i:s");
$IDtype = $_POST['IDtype'];
$pymt = $_POST['noAmt'];
$Fee = $_POST['YuranP'];
$ID = $_REQUEST['ID'];

$sSQLUpd	= "UPDATE potbulan SET" .
		          " jumBlnP= '" .$pymt. "'" .
		          " Where ID  = '".$IDtype."'";
$rsUpd = &$conn->Execute($sSQLUpd);

$sSQLUpd2	= "UPDATE userdetails SET" .
		          " monthFee= '" .$Fee. "'" .
		          " Where userID  = '".$ID."'";
$rsUpd2 = &$conn->Execute($sSQLUpd2);

print '<script>alert("Kemaskini Potongan Gaji Berjaya !");</script>';

}

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

if (get_session("Cookie_groupID") == 0) {
	$ID = get_session("Cookie_userID");
	$dept = dlookup("userdetails", "departmentID", "userID=" . $ID);
	$pk[0] = $ID;
	//$objchk = " checked disabled ";
}


if ($code == 1){
$sSQLdel = "delete from potbulan Where ID =".$IDtype."";
$rsdel = &$conn->Execute($sSQLdel);

$sSQLdel2 = "delete from potbulanlook Where potID =".$IDtype."";
$rsdel2 = &$conn->Execute($sSQLdel2);

print '<script>alert("Potongan Gaji Berjaya Dihapuskan !");</script>';
}



$sSQL = "select * from potbulan 	 
		 WHERE  userID = " . tosql($ID,"Text") . "
		 AND status IN (1) 
  		 ";
		 
		 //AND (lastyrmthPymt >= '".$yrmthNow."' AND yrmth <= '".$yrmthNow."')
$rs = &$conn->Execute($sSQL);

$sSQL2 = "SELECT	DISTINCT a.*, b.*
		  FROM 	users a, userdetails b
		  WHERE a.UserID =".tosql($ID,"Text")."
		  AND a.UserID = b.UserID";

$rs1 =&$conn->Execute($sSQL2); 
?>

<head>
<title>iKOOP</title>
</head>

<body>

<?php
print '<div class="table-responsive">
<form id="Edittrans" name="Edittrans" method="post" action='.$sFileName .'>
<input type="hidden" name="action">
<input type="hidden" name="StartRec" value="'.$StartRec.'">
<input type="hidden" name="by" value="'.$by.'">

<table width="100%" >
    <tr>
      <td width="183">&nbsp;</td>
      <td width="908">&nbsp;</td>
    </tr>
    <tr>
      <td>Nama Anggota :</td>
      <td><b>'.$rs1->fields(name).'</b></td>
    </tr>
    <tr>
      <td>No. Anggota :</td>
      <td><b>'.$ID.'</b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table border="0" cellspacing="1" cellpadding="2" width="100%">
  <tr bgcolor="#008080" style="font-family: Arial, Helvetica, sans-serif; color: #FFFFFF; font-size: 10pt; font-weight: bold;">
  <td>'.strtoupper($title).'
  </td>
</tr></table>
<table border="0" cellspacing="1" cellpadding="2" width="100%">
<tr bgcolor="#008080" style="font-family: Arial, Helvetica, sans-serif; color: #FFFFFF; font-size: 10pt; font-weight: bold;"><font class="text-white">
      <td nowrap><b>Bil</b></td>
      <td nowrap><b>Mula Potongan (Bulan/Tahun)</b></td>
      <td nowrap><b>Jenis Pembiayaan</b></td>
      <td nowrap><b>No. Bond</b></td>
	  <td nowrap align="right"><b>Jumlah Yuran (RM)</b></td>
      <td nowrap align="right"><b>Jumlah Potongan Bulan Pembiayaan (RM)</b></td>
      <td nowrap align="center"><b>Edit</b></td>
    </tr>';
   if ($rs->RowCount() <> 0) {
   $count =1;
   while(!$rs->EOF) {
$sSQL3 = "select * from general
		 WHERE  ID = " . $rs->fields(loanType) . "
  		 ORDER BY ID";
$rs3 = &$conn->Execute($sSQL3);

$monthFee 		= $rs1->fields(monthFee);
$syerbulan 		= $rs1->fields(unitShare);


print'
	<tr>
      <td class="Data" >'.$count.'</td>
      <td class="Data" >'.$rs->fields(yrmth).'</td>
      <td class="Data"><a href="'.$sFileRef.'&ID='.tohtml($rs->fields(ID)).'">'.$rs3->fields(name).'</a></td>
	  <td class="Data" nowrap >'.$rs->fields(bondNo).'</td>
	  <td class="Data" >'; if ($IDtype == $rs->fields(ID)) { print '<input size="7" class="form-control-sm" name="YuranP" value="'.$rs1->fields(monthFee).'" >';
	  }else{ print ''.$rs1->fields(monthFee).''; }
	  print' </td>
	        <td class="Data" align="right" >'; if ($IDtype == $rs->fields(ID)) { print '<input size="15" class="form-control-sm" name="noAmt" value="'.$rs->fields(jumBlnP).'" >';
	  }else{ print ''.$rs->fields(jumBlnP).''; }
	  print'</td>
      <td class="Data" align="center" width="5%"><a href="'.$sFileName.'&IDtype='.$rs->fields(ID).'&ID='.$ID.'&code=2" title="kemaskini"><img src="b_edit.png"></a> <input size="7" type="hidden" name="IDtype" value="'.$IDtype.'" ><input size="7" type="hidden" name="ID" value="'.$ID.'" ></td>';
      
if (($IDName == 'admin') OR ($IDName == 'superadmin')){ 
    print'  <td class="Data" align="center"  width="5%"><a href="'.$sFileNameDel.'&IDtype='.$rs->fields(ID).'&ID='.$ID.'&code=1" title="Hapus" onClick="if(!confirm(\'Adakah ada pasti untuk hapus file ini?\')) {return false} else {window.Edittrans.submit();};"><img src="b_drop.png"></td>';
      
}
   print '   <td class="Data" align="center" width="5%">'; if ($IDtype == $rs->fields(ID)) { print '<input type="submit" class="btn btn-sm btn-secondary" size="3" onClick="if(!confirm(\'Adakah ada pasti untuk Kemaskini file ini?\')) {return false} else {window.Edittrans.submit();};" name="edit" value="edit" />';
	  }
	  print'</td>
    </tr>';
	$count++;
	$rs->MoveNext();

}
	}else {
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt;" bgcolor="FFFFFF">
						<td colspan="8" align="center"><b>- Tiada Rekod </b></td>
					</tr>';
				}
	
print'
  </table>
  <p>&nbsp;</p>
</form></div>
<p>&nbsp;</p> '; ?>
</body>
</html>
