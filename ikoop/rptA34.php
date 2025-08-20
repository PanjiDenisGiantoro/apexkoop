<?php
/*********************************************************************************
*          Project        :    iKOOP.com.my
*          Filename        :  rptA34.php
*          Description    :    Report Senarai Koperasi Terlatih
*          Date            :  12/12/2021
*********************************************************************************/
session_start();
if (!isset($fasa)) $fasa = "ALL";
if (!isset($dept)) $dept = "";

include("common.php");    
date_default_timezone_set("Asia/Jakarta");
$today = date("F j, Y, g:i a");     

$koperasi = isset($_POST['koperasi']) ? $_POST['koperasi'] : 'ALL';

if (get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}
$title  = 'Senarai Kemaskini Koperasi Terlatih';

$deptList = Array();
$deptVal  = Array();

$fasaList = Array();
$fasaVal  = Array();

// Query for deptList
$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID
             FROM userdetails a
             INNER JOIN general b ON a.departmentID = b.ID
             INNER JOIN users c ON a.userID = c.userID
             GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RecordCount() <> 0){
    while (!$rsDept->EOF) {
        array_push ($deptList, $rsDept->fields('deptName'));
        array_push ($deptVal, $rsDept->fields('departmentID'));
        $rsDept->MoveNext();
    }
}

// Query for koperasi list
$sSQLKoperasi = "SELECT userID, name, loginID FROM users";
$rsKoperasi = &$conn->Execute($sSQLKoperasi);

$koperasiList = array();
$koperasiVal = array();

if ($rsKoperasi) {
    if ($rsKoperasi->RecordCount() > 0) {
        while (!$rsKoperasi->EOF) {
            // Get userID and koperasi name
            $userID = $rsKoperasi->fields('userID');
            $namaKoperasi = $rsKoperasi->fields('name');
            $singkatanKoperasi = $rsKoperasi->fields('loginID');
            
            // Add userID and name to dropdown
            $koperasiList[] = $userID . ' - ' . $namaKoperasi. ' - ' . $singkatanKoperasi;
            $koperasiVal[] = $userID;
            
            $rsKoperasi->MoveNext();
        }
    } else {
        echo "No records found in users table.";
    }
} else {
    echo "Error executing query.";
}
$koperasi = isset($_GET['koperasi']) ? $_GET['koperasi'] : 'ALL'; // Changed from $_POST to $_GET
$dtFrom = isset($_GET['dtFrom']) ? $_GET['dtFrom'] : ''; // Ensure to get dtFrom
$dtTo = isset($_GET['dtTo']) ? $_GET['dtTo'] : ''; // Ensure to get dtTo
$q = isset($_GET['q']) ? $_GET['q'] : ''; // Added search query
$by = isset($_GET['by']) ? $_GET['by'] : ''; // Added search type
$dept = isset($_GET['dept']) ? $_GET['dept'] : ''; // Added department filter

// Get date parameters from $_GET
$dtFrom = isset($_GET['dtFrom']) ? $_GET['dtFrom'] : ''; // Get the value of dtFrom
$dtTo = isset($_GET['dtTo']) ? $_GET['dtTo'] : ''; // Get the value of dtTo

// Start with the basic SQL statement
$sSQL = "SELECT a.userID, a.name, CAST(b.kopNum AS SIGNED INTEGER) AS kopNum, b.approvedDate, b.jenis, 
         c.name AS department, b.training, b.departmentID, a.email, 
         t.tarikh_latihan, t.person_in_charge, t.modul, t.online_offsite, t.catatan
         FROM users a
         LEFT JOIN userdetails b ON a.userID = b.userID AND b.training = 1
         LEFT JOIN general c ON c.ID = b.departmentID
         LEFT JOIN training t ON t.userID = a.userID
         WHERE a.userID = b.userID"; 

// Check for duplicate records
if (!empty($dtFrom) && !empty($dtTo)) {
    $sSQL .= " AND t.tarikh_latihan BETWEEN " . tosql($dtFrom, "Text") . " AND " . tosql($dtTo, "Text");
}

// Filtering based on the selected cooperative
if ($koperasi != "ALL") {
    $sSQL .= " AND a.userID = " . tosql($koperasi, "Number");
}


// Add search filtering
if (!empty($q)) {
    switch ($by) {
        case 1: // No./ID Koperasi
            $sSQL .= " AND a.userID LIKE '%" . addslashes($q) . "%'";
            break;
        case 2: // Nama Koperasi
            $sSQL .= " AND a.name LIKE '%" . addslashes($q) . "%'";
            break;
        case 3: // Singkatan Koperasi
            $sSQL .= " AND a.loginID LIKE '%" . addslashes($q) . "%'"; // Corrected line to use a.loginID
            break;
    }
}
if (!empty($dept)) {
    $sSQL .= " AND b.departmentID = " . tosql($dept, "Number");
}
$sSQL .= " AND a.userID NOT REGEXP '^a[1-9]'";
$sSQL .= " ORDER BY t.tarikh_latihan ASC";
$rs = &$conn->Execute($sSQL);
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="images/default.css">        
</head>
<body>
<form name="MyForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <div>
        Carian Melalui
        <select name="by" class="form-select-sm">
            <option value="1" <?php if ($by == 1) echo 'selected'; ?>>No./ID Koperasi</option>
            <option value="2" <?php if ($by == 2) echo 'selected'; ?>>Nama Koperasi</option>
            <option value="3" <?php if ($by == 3) echo 'selected'; ?>>Singkatan Koperasi</option>
        </select>
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control-sm" maxlength="50" size="20">
        <input type="submit" class="btn btn-sm btn-secondary" value="Cari">
        <p>Pilihan Koperasi</p>
        <form method="get" action="rptA34.php">
            <select name="koperasi" class="textFont" onchange="this.form.submit()">
                    <option value="ALL">- Semua Koperasi -</option>
            <?php foreach ($koperasiList as $i => $name): ?>
                <?php if (substr($koperasiVal[$i], 0, 1) !== 'a'): ?>
                    <option value="<?php echo htmlspecialchars($koperasiVal[$i]); ?>" <?php if ($koperasi == $koperasiVal[$i]) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($name); ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        </form>
    </p>
    <input type="hidden" name="dtFrom" value="<?php echo htmlspecialchars($dtFrom); ?>">
    <input type="hidden" name="dtTo" value="<?php echo htmlspecialchars($dtTo); ?>">
</form>
<!-- Results Table -->
<table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr bgcolor="#730b33">
        <th colspan="7" style="color: #FFF;"><?php echo htmlspecialchars($title); ?> Pada <?php echo date("d/m/Y"); ?></th>
    </tr>
    <tr>
        <td><font size=1>Cetak Pada : <?php echo $today; ?><br />Oleh : <?php echo get_session('Cookie_fullName'); ?></font></td>
    </tr>
    <?php if ($rs && $rs->RecordCount() > 0): ?>
        <table border=0  cellpadding="2" cellspacing="1" align=left width="100%">
                <tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
                    <th nowrap align="center">Bil</th>
                    <th nowrap align="left">No./Nama Koperasi</th>
                    <th nowrap align="left">Orang Yang Bertugas(PIC)</th>
                    <th nowrap align="left">Modul</th>
                    <th nowrap align="center">Online/Offsite</th>
                    <th nowrap align="left">Catatan</th> 
                    <th nowrap align="center">Tarikh Latihan</th>
                </tr>
        <?php $bil = 0; ?>
        <?php while (!$rs->EOF): $bil++; ?>
    <tr bgcolor="#FFFFFF">
        <td style="text-align: center;"><?php echo $bil; ?></td>
        <td style="text-align: left;"><?php echo htmlspecialchars($rs->fields('userID') . ' - ' . $rs->fields('name')); ?></td>
        <td style="text-align: left;"><?php echo htmlspecialchars($rs->fields('person_in_charge')); ?></td>
        <td style="text-align: left;"><?php echo htmlspecialchars($rs->fields('modul')); ?></td>
        <td style="text-align: center;"><?php echo ($rs->fields('online_offsite') == '1') ? 'Online' : 'Offsite'; ?></td>
        <td style="text-align: left;"><?php echo htmlspecialchars($rs->fields('catatan')); ?></td>
        <td style="text-align: center;"><?php echo toDate("d/m/Y", $rs->fields('tarikh_latihan')); ?></td>
    </tr>
    <?php $rs->MoveNext(); ?>
    <?php endwhile; ?>
    <tr bgcolor="#FFFFFF">
    <td colspan="7"><br>Jumlah Koperasi: <b><?php echo $bil; ?></b></td>
    </tr>

    <?php else: ?>
        <tr bgcolor="#FFFFFF">
            <td colspan="7" align="center"><b>- Tiada Rekod Dicetak -</b></td>
        </tr>
    <?php endif;?>  

    <table border="0" cellpadding="5" cellspacing="2" width="100%">
    <tr>
        <td>
            <br><br>&nbsp;<font size="1" color="#999999"><b><?php echo $retooFetis; ?></b></font>
        </td>
    </tr>
</table>
</table>
</body>
</html>