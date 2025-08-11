<? 
/*********************************************************************************
*          Project    : iKOOP.com.my
*          Filename   : Edit_memberStmtPotongan.php
*          Date       : 04/12/2018
*********************************************************************************/
include("header.php");  
include("koperasiQry.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

$title          = "Pembuka Akaun";
$sFileName      = "?vw=ACClejerListEdit&mn=$mn"; 
$sFileRef       = "?vw=ACClejerListEdit&mn=$mn";
$sActionFileName= "?vw=ACClejerList&mn=$mn";

$IDName = get_session("Cookie_userName");

$Kemaskini  = $_POST['Kemaskini'];
$Tambah     = $_POST['Tambah'];
$code = $_REQUEST['code'];

if (get_session("Cookie_groupID") <> 2 AND 
    get_session("Cookie_groupID") <> 4 OR 
    get_session("Cookie_koperasiID") <> 0
   ) 
  {
    print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
  }

$sSQL = "SELECT * FROM generalacc WHERE ID = ".tosql($ID,"Text")."";
$rs = &$conn->Execute($sSQL);

$sSQL1 = "SELECT * FROM transactionacc WHERE deductID = ".tosql($ID,"Text")." AND yrmth = ".tosql($yrmth,"Text")."";
$rs1 = &$conn->Execute($sSQL1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($Tambah)
  {
  $applyDate  = date("Y-m-d H:i:s");
  
  $docT       = 'OP';
  $docNo      = $_POST['yrmth'];
  $dest       = $docT.$docNo;


  $pymtAmt    = $_POST['pymtAmt'];
  $yrmth      = $_POST['yrmth'];
  $ID         = $_POST['ID'];
  $MdeductID  = $_POST['MdeductID'];
  $addminus   = $_POST['addminus'];

    $sSQLUpd  = "INSERT INTO transactionacc (" . 
              "docID," . 
              "docNo," . 
              "tarikh_doc," . 
              "yrmth,".
              "deductID,".
              "MdeductID,".
              "addminus,".
              "pymtAmt," . 
              "createdBy,".
              "createdDate)" . 
              " VALUES (" . 
              "15". ",".
              tosql($dest, "Text") . ",".
              tosql($applyDate, "Text") . ",".
              tosql($yrmth, "Text") . ",".
              tosql($ID, "Number") . ",".
              tosql($MdeductID, "Text") . ",".
              tosql($addminus, "Text") . ",".
              tosql($pymtAmt, "Number") . ",".
              tosql($IDName, "Text") . ",".
              tosql($applyDate, "Text") . ")";
    $rsUpd    = &$conn->Execute($sSQLUpd);

    print '<script>
              alert("Pembuka Carta Akaun Dimasukkan");
              window.location.href = "'.$sActionFileName.'";
            </script>';
  }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($Kemaskini)
  {
    $updatedDate= date("Y-m-d H:i:s");
    $pymtAmt    = $_POST['pymtAmt'];
    $IDtype     = $_POST['IDtype'];
    $ID         = $_POST['ID'];
    $addminus   = $_POST['addminus'];

    $sSQLUpd1   = "UPDATE transactionacc SET" .
                " pymtAmt= '" .$pymtAmt. "'" . 
                " ,addminus= '" .$addminus. "'" .       
                " ,deductID= '" .$ID. "'" .          
                " ,updatedBy= '" .$IDName. "'" .         
                " ,updatedDate= '" .$updatedDate. "'" .
                " WHERE ID  = '".$IDtype."'";
    $rsUpd1     = &$conn->Execute($sSQLUpd1);

    print '<script>alert("Pembuka Carta Akaun Telah Dikemaskini");
            window.location.href = "'.$sActionFileName.'";
          </script>';
  }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($code == 1)
  {
    $sSQLdel  = "DELETE FROM transactionacc WHERE ID =".$IDtype."";
    $rsdel    = &$conn->Execute($sSQLdel);

    print '<script>alert("Pembuka Carta Akaun Telah Dihapuskan");
            window.location.href = "'.$sActionFileName.'";
          </script>';
  }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<head>
<title>iKOOP</title>
</head>
<body>
<?

$kodkump      = $rs->fields(a_Kodkump);
$namakodkump  = dlookup("generalacc", "name", "ID=" . $kodkump);

$kodclass     = $rs->fields(a_class);
$namaclass    = dlookup("generalacc", "name", "ID=" . $kodclass);

$MdeductID    = $rs->fields(parentID);

print '
<form id="Edittrans" name="Edittrans" method="POST" action='.$sFileName.'>
<input type="hidden" name="action">
<input type="hidden" name="StartRec" value="'.$StartRec.'">
<input type="hidden" name="by" value="'.$by.'">

<table >
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Nama Carta Akaun</td>
      <td>:</td>
      <td>'.$rs->fields(name).'</td>
    </tr>
    <tr>
      <td>Kod Kumpulan</td>
      <td>:</td>
      <td>'.$namakodkump.'</td>
    </tr>
    <tr>
      <td>Klasifikasi</td>
      <td>:</td>
      <td>'.strtoupper($namaclass).'</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
    <tr valign="top" class="table-danger">
      <td colspan="9" class="Header"><strong>'.strtoupper($title).'</strong></td>
    </tr>
    <tr valign="top" class="table-danger">
      <td width="2%" align="center"  nowrap rowspan="1" ><b>Bil</b></td>
      <td width="11%"align="left" nowrap><b>Kod Akaun - Nama Carta Akaun</b></td>
      <td width="8%" align="center" nowrap><b>Tahun & Bulan</b></td>
      <td width="11%"align="right" nowrap><div align="right"><b>Amaun (RM)</b></div></td>
      <td width="8%" align="center"nowrap><div align="center"><b>Pilihan</b></div></td>
      <td colspan="3"align="center" nowrap><div align="center"></div></td>
    </tr>';

   if ($rs->RowCount() <> 0) {
   $count =1;

   while(!$rs->EOF) {

    $IDtype   = $rs1->fields(ID);
    $tahun    = $rs1->fields(tahun);
    $pymtAmt    = $rs1->fields(pymtAmt);

print'
  <tr>
      <td class="Data" align="center">'.$count.'</td>
      <td class="Data" >'.$rs->fields(code).'&nbsp;- &nbsp;'.$rs->fields(name).'</td>      
      <td class="Data" align="center">'.$yrmth.'</td>

    <td class="Data" align="right">&nbsp;'; 


    if ($IDtype == $rs1->fields(ID)) 
      { 

        print '&nbsp;<input size="15" class="form-control-sm" name="pymtAmt" value="'.$pymtAmt.'" >';
      }
      else
        { 
          print '&nbsp;'.$pymtAmt.''; 
        }
    print' </td>
  
    <td class="Data" align="center" width="8%">';

    if (($IDtype == $rs1->fields(ID)) && ($rs1->fields(addminus) == 0)) 
    {
        print '
        <input type="radio" name="addminus" value="0" checked="checked">Debit
        <input type="radio" name="addminus" value="1">Kredit 
        ';
    }

     if (($IDtype == $rs1->fields(ID)) && ($rs1->fields(addminus) == 1)) 
     {
        print '
        <input type="radio" name="addminus" value="0">Debit
        <input type="radio" name="addminus" value="1" checked="checked">Kredit 
        ';
    }



    print'</td>      

    <a href="'.$sFileName.'?IDtype='.$IDtype.'&ID='.$ID.'&yrmth='.$yrmth.'" title="Kemaskini"></a> 

    <input size="7" type="hidden" name="IDtype" value="'.$IDtype.'">
    <input size="7" type="hidden" name="ID" value="'.$ID.'" >
    <input size="7" type="hidden" name="yrmth" value="'.$yrmth.'" >
    <input size="7" type="hidden" name="MdeductID" value="'.$MdeductID.'" ></td>';
      
  print '<td class="Data" align="center" width="5%">'; 
    
if ($pymtAmt != "")
 {

    if ($IDtype == $rs1->fields(ID)) 
      { 
        print '<input type="submit" size="3" onClick="if(!confirm(\'Adakah anda pasti untuk mengemaskini amaun tersebut?\')) {return false} else {window.Edittrans.submit();};" name="Kemaskini" class="btn btn-sm btn-primary" value="Kemaskini" />';
      }
 }

  print'</td>';

  print '   <td class="Data" align="center" width="5%">'; 
if ($pymtAmt == "")
  {
    if ($IDtype == $rs1->fields(ID)) 
      { 
        print '<input type="submit" size="3" onClick="if(!confirm(\'Adakah anda pasti untuk menambah amaun baru tersebut?\')) {return false} else {window.Edittrans.submit();};" name="Tambah" class="btn btn-primary btn-sm" value="Tambah" />';
      }
 }
  print'</td>';


  print'   <td class="Data" align="center"  width="5%">&nbsp;<a href="'.$sFileNameDel.'?IDtype='.$rs1->fields(ID).'&ID='.$ID.'&code=1" title="Hapus" onClick="if(!confirm(\'Adakah ada pasti untuk hapus file ini?\')) {return false} else {window.Edittrans.submit();};"><i class="mdi mdi-close text-danger"></i></td>';

 

  print'</tr>';
  $count++;
  $rs->MoveNext();

}
  }else {
          print '
          <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
            <td colspan="8" align="center"><b>- Tiada Rekod </b></td>
          </tr>';
        }
  
print'
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p> '; ?>
</body>
</html>