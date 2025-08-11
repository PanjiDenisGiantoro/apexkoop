<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

include("header.php");
include("koperasiQry.php");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 AND get_session("Cookie_groupID") <> 5 OR get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$primaryK = str_replace(":", "", $pk);
$name =  dlookup("users", "name", "userID=" . tohtml($primaryK, "Text"));
$title     = "Statistik ".$name."";

// // Define the connection parameters
$dbParams = array(
    array('dbtype' => $DB_dbtype1, 'hostname' => $DB_hostname1, 'username' => $DB_username1, 'password' => $DB_password1, 'dbname' => $DB_dbname1, 'name' => 'kppkkkb'), // index 0
    array('dbtype' => $DB_dbtype2, 'hostname' => $DB_hostname2, 'username' => $DB_username2, 'password' => $DB_password2, 'dbname' => $DB_dbname2, 'name' => 'kpktb'), // index 1
    array('dbtype' => $DB_dbtype3, 'hostname' => $DB_hostname3, 'username' => $DB_username3, 'password' => $DB_password3, 'dbname' => $DB_dbname3, 'name' => 'kosite'), // index 2
    array('dbtype' => $DB_dbtype4, 'hostname' => $DB_hostname4, 'username' => $DB_username4, 'password' => $DB_password4, 'dbname' => $DB_dbname4, 'name' => 'kpfspb'), // index 3
    array('dbtype' => $DB_dbtype5, 'hostname' => $DB_hostname5, 'username' => $DB_username5, 'password' => $DB_password5, 'dbname' => $DB_dbname5, 'name' => 'kohidmas'), // index 4
    array('dbtype' => $DB_dbtype6, 'hostname' => $DB_hostname6, 'username' => $DB_username6, 'password' => $DB_password6, 'dbname' => $DB_dbname6, 'name' => 'koguna'), // index 5
    array('dbtype' => $DB_dbtype7, 'hostname' => $DB_hostname7, 'username' => $DB_username7, 'password' => $DB_password7, 'dbname' => $DB_dbname7, 'name' => 'kojpjk'), // index 6
    array('dbtype' => $DB_dbtype8, 'hostname' => $DB_hostname8, 'username' => $DB_username8, 'password' => $DB_password8, 'dbname' => $DB_dbname8, 'name' => 'komppj'), // index 7
    array('dbtype' => $DB_dbtype9, 'hostname' => $DB_hostname9, 'username' => $DB_username9, 'password' => $DB_password9, 'dbname' => $DB_dbname9, 'name' => 'kpfp'), // index 8
    array('dbtype' => $DB_dbtype10, 'hostname' => $DB_hostname10, 'username' => $DB_username10, 'password' => $DB_password10, 'dbname' => $DB_dbname10, 'name' => 'koopbait') // index 9
);

// Pilih database berdasarkan primaryK
switch ($primaryK) {
    case '174': //kppkkkb
        $dbIndex = 0;
        break;
    case '145': //kpktb
        $dbIndex = 1;
        break;
    case '36': //kosite
        $dbIndex = 2;
        break;
    case '177': //kpfpsb
        $dbIndex = 3;
        break;
    case '176': //kohidmas
        $dbIndex = 4;
        break;
    case '114': //koguna
        $dbIndex = 5;
        break;
    case '142': //kojpjk
        $dbIndex = 6;
        break;
    case '173': //komppj
        $dbIndex = 7;
        break;
    case '138': //kpfp
        $dbIndex = 8;
        break;
    case '141': //koopbait
        $dbIndex = 9;
        break;
    default:
        $dbIndex = null;
        break;
}

// ----------------------------- fetch query -------------------------------------

// Total anggota aktif dalam koperasi
if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    $dbParam = $dbParams[$dbIndex];

    // Buat sambungan baru untuk database yang dipilih
    $connn = &ADONewConnection($dbParam['dbtype']);
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {    
   
        $query = "SELECT COUNT(*) AS count FROM userdetails WHERE status = 1";
        $result = $connn->Execute($query);
        
        if ($result) {
            $row = $result->FetchRow();
            $totalUser = $row['count'];
        }
    }
}


// ----------------------------- open yuran -------------------------------------
// Jumlah yuran ikut tahun
$yuranStats = array();
if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];
    
    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);      
    
    // Connect to the database
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        // Define the query to execute
        $query = "SELECT YEAR(a.createdDate) AS year, 
                         ABS(SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END)) AS jumlah 
                  FROM transaction a 
                  JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID IN (1595)                   
                  AND b.status IN (1, 4)
                  AND YEAR(a.createdDate) BETWEEN YEAR(CURDATE()) - 4 AND YEAR(CURDATE())
                  GROUP BY YEAR(a.createdDate)
                  ORDER BY year";            

        // Execute the query
        $result = $connn->Execute($query);
        
        // Check if result is valid and fetch data
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = $row['year'];
                $jumlah = $row['jumlah'];
                $yuranStats[$year] = $jumlah; 
            }
        }
    }
    
    // Ensure the connection is closed
    $connn->Close();    
}
// ----------------------------- close yuran -------------------------------------

// ----------------------------- open syer -------------------------------------
// Jumlah syer ikut tahun
$syerStats = array();
if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];

    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);   

    // Connect to the database
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        // Define the query to execute
        $query = "SELECT YEAR(updatedDate) AS year, 
                         ABS(SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - 
                             SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END)) AS jumlah
                  FROM transaction 
                  WHERE deductID IN (1596, 1780)
                  AND YEAR(updatedDate) BETWEEN YEAR(CURDATE()) - 4 AND YEAR(CURDATE())
                  GROUP BY YEAR(updatedDate)
                  ORDER BY year";

        // Execute the query
        $result = $connn->Execute($query);
        
        // Check if result is valid and fetch data
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = $row['year'];
                $jumlah = $row['jumlah'];
                $syerStats[$year] = $jumlah;
            }
        }
        
        // Close the connection
        $connn->Close();
    }
}
// ----------------------------- close syer -------------------------------------

// ----------------------------- open pembiayaan -------------------------------------
//jumlah pembiayaan
$pDlmProStats = array(
    'DALAM PROSES' => 0,
    'DISEDIAKAN' => 0,
    'DISEMAK' => 0,
    'DILULUSKAN' => 0
);

if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];
    
    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);      
    
    // Connect to the database
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        // Define the query to execute
        $query = " SELECT 
                    COUNT(CASE WHEN status = 0 THEN 1 END) AS dalam_proses,
                    COUNT(CASE WHEN status = 1 THEN 1 END) AS disediakan,
                    COUNT(CASE WHEN status = 2 THEN 1 END) AS disemak,
                    COUNT(CASE WHEN status = 3 THEN 1 END) AS diluluskan
                    FROM loans 
                    WHERE status IN (0,1,2,3)";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pDlmProStats['DALAM PROSES'] += $row['dalam_proses'];
                $pDlmProStats['DISEDIAKAN'] += $row['disediakan'];
                $pDlmProStats['DISEMAK'] += $row['disemak'];
                $pDlmProStats['DILULUSKAN'] += $row['diluluskan'];
            }    
        }
    }
    // Ensure the connection is closed
    $connn->Close();    
}
// ----------------------------- close pembiayaan -------------------------------------

// ----------------------------- open yuran & syer -------------------------------------
//jumlah yuran & syer
$totYSStats = array(
    'yuran' => 0,
    'syer' => 0
);

if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];
    
    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);    
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        
        // Query for yuran
        $queryYuran = "SELECT SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END) AS jumlah 
                       FROM transaction a 
                       JOIN userdetails b ON a.userID = b.userID
                       JOIN users c ON b.userID = c.userID
                       WHERE a.deductID IN (1595)                  
                       AND b.status IN (1,4)";
        $resultYuran = $connn->Execute($queryYuran);
        
        if ($resultYuran) {
            if ($row = $resultYuran->FetchRow()) {
                $jumlahYuran = $row['jumlah'];
                $totYSStats['yuran'] += $jumlahYuran;
            }
        }
        
        $querySyer = "SELECT ABS(SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END)) AS jumlah
                      FROM transaction a 
                      JOIN userdetails b ON a.userID = b.userID
                      JOIN users c ON b.userID = c.userID
                      WHERE a.deductID IN (1596, 1780)
                      AND b.status IN (1, 4)";
        $resultSyer = $connn->Execute($querySyer);
        
        if ($resultSyer) {
            if ($row = $resultSyer->FetchRow()) {
                $jumlahSyer = $row['jumlah'];
                $totYSStats['syer'] += $jumlahSyer;
            }
        }
    }
    // Ensure the connection is closed
    $connn->Close();  
}
// ----------------------------- close yuran & syer -------------------------------------

// ----------------------------- open yuran -------------------------------------
//yuran statistik
$yuranLabels = array();
if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];

    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);     
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        // Define the query to execute
        $query = "SELECT YEAR(a.createdDate) AS year 
                  FROM transaction a 
                  JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID IN (1595)
                  AND b.status IN (1, 4)
                  AND YEAR(a.createdDate) BETWEEN YEAR(CURDATE()) - 4 AND YEAR(CURDATE())
                  GROUP BY YEAR(a.createdDate)
                  ORDER BY year";

        // Execute the query
        $result = $connn->Execute($query);
        
        // Check if result is valid and fetch data
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = strtoupper($row['year']);
                $yuranLabels[$year] = $year;
            }
        }
        
        // Close the connection
        $connn->Close();
    }
}
// Convert the associative array to a regular indexed array
$yuranLabels = array_values($yuranLabels);
// ----------------------------- close yuran -------------------------------------

// ----------------------------- open syer -------------------------------------
//syer statistik
$syerLabels = array();
if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Get the appropriate database parameters
    $dbParam = $dbParams[$dbIndex];

    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);     
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        // Define the query to execute
        $query = "SELECT YEAR(updatedDate) AS year 
                  FROM transaction 
                  WHERE deductID IN (1596, 1780)
                  AND YEAR(updatedDate) BETWEEN YEAR(CURDATE()) - 4 AND YEAR(CURDATE())
                  GROUP BY YEAR(updatedDate)
                  ORDER BY year";

        // Execute the query
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = strtoupper($row['year']);
                $syerLabels[$year] = $year;
            }
        }

        // Close the connection
        $connn->Close();
    } 
}

// Convert the associative array to a regular indexed array
$syerLabels = array_values($syerLabels);
// ----------------------------- close syer -------------------------------------

// ----------------------------- open pembiayaan -------------------------------------
//pembiayaan statistik
$pDlmProLabelsArray = array(
    'DALAM PROSES' => 0,
    'DISEDIAKAN' => 0,
    'DISEMAK' => 0,
    'DILULUSKAN' => 0
);

foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "
            SELECT 
                COUNT(CASE WHEN status = 0 THEN 1 END) AS dalam_proses,
                COUNT(CASE WHEN status = 1 THEN 1 END) AS disediakan,
                COUNT(CASE WHEN status = 2 THEN 1 END) AS disemak,
                COUNT(CASE WHEN status = 3 THEN 1 END) AS diluluskan
            FROM loans 
            WHERE status IN (0,1,2,3)";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pDlmProLabelsArray['DALAM PROSES'] += $row['dalam_proses'];
                $pDlmProLabelsArray['DISEDIAKAN'] += $row['disediakan'];
                $pDlmProLabelsArray['DISEMAK'] += $row['disemak'];
                $pDlmProLabelsArray['DILULUSKAN'] += $row['diluluskan'];
            }    
        }
    } 
    $pDlmProLabels = array_keys($pDlmProLabelsArray);
}
// ----------------------------- close pembiayaan -------------------------------------

// ----------------------------- open yuran & syer -------------------------------------
//yuran & syer stastitik
$totYSLabels = array(
    'yuran' => 0,
    'syer' => 0
);

if ($dbIndex !== null && isset($dbParams[$dbIndex])) {
    // Ambil parameter pangkalan data yang sesuai
    $dbParam = $dbParams[$dbIndex];

    // Initialize a new connection
    $connn = ADONEWConnection($dbParam['dbtype']);  
    if ($connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname'])) {
        
        // Query for yuran
        $queryYuran = "SELECT ABS(SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END)) AS jumlah 
                       FROM transaction a 
                       JOIN userdetails b ON a.userID = b.userID
                       JOIN users c ON b.userID = c.userID
                       WHERE a.deductID IN (1595)                  
                       AND b.status IN (1, 4)";
        $resultYuran = $connn->Execute($queryYuran);
        
        if ($resultYuran) {
            if ($row = $resultYuran->FetchRow()) {
                $totYSLabels['yuran'] += $row['jumlah'];
            }
        }
        
        // Query for syer
        $querySyer = "SELECT ABS(SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END)) AS jumlah
                      FROM transaction a 
                      JOIN userdetails b ON a.userID = b.userID
                      JOIN users c ON b.userID = c.userID
                      WHERE a.deductID IN (1596, 1780)
                    --   AND b.status IN (1, 4)
                      ";
        $resultSyer = $connn->Execute($querySyer);
        
        if ($resultSyer) {
            if ($row = $resultSyer->FetchRow()) {
                $totYSLabels['syer'] += $row['jumlah'];
            }
        }
    
        // Tutup sambungan
        $connn->Close();
    }
}

// Convert the associative array to a numeric array
$numericArray = array_values($totYSLabels);
// ----------------------------- close yuran & syer -------------------------------------

print'<h3 class="title" align="center">'.strtoupper($title).'</h3><hr>';
include("footer.php");

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div class="title">Jumlah Keseluruhan Anggota Koperasi : <?php echo $totalUser; ?></div>
    <style>
    .title {
        text-align: center;
        font-size: 21px;
        color: #495057;
        text-transform: uppercase;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
    }

     .chart {
        width: calc(40% - 20px);
        margin: 10px;
        padding: 10px;
        background-color: #fff;
        /* box-shadow: 0 0 10px rgba(202, 202, 202, 0.7); */
        border-radius: 30px;
    } 
    
    .category-container {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(202, 202, 202, 1);
    }

    .category-title {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    font-weight: bold;
    font-size: 18px;
    background-color: #730b33;
    color: #fff;
    padding: 10px;
    border-radius: 100px;
    margin: 0 auto 10px;
}


    /* .centered-charts {
        display: flex;
        width: 100%;
        justify-content: center;
    } */

    .category-container.chart .centered-charts .chart {
    width: 1000px;
}
</style>
</head>

<body>
<div class="category-container chart">
    <!-- Category 1 -->
    <div class="category-title">Statistik Asas Koperasi</div>
    <div class="centered-charts">
        <div class="chart" >
            <h4 align="center">Yuran Anggota</h4><br>
            <canvas id="yuranChart" style="display: block;"></canvas>
        </div>
        <div class="chart">
        <h4 align="center">Syer Anggota</h4><br>
        <canvas id="syerChart" style="display: block;"></canvas>
        </div>
    </div>

    <div class="chart" style="width: calc(100%);"></div>
    
        <div class="chart" >
            <h4 align="center">Jumlah Yuran & Syer</h4><br>
            <canvas id="totYSChart" width="200" height="200" style="display: block;"></canvas>
        </div>
        <div class="chart" >
            <h4 align="center">Jumlah Pembiayaan</h4><br>
            <canvas id="pDlmProChart" width="200" height="200" style="display: block;"></canvas>
        </div>      
</div>



    <script>
        //yuran
        var yuranData = <?php echo json_encode(array_values($yuranStats)); ?>;
        var yuranLabels = <?php echo json_encode($yuranLabels); ?>;

        //syer
        var syerData = <?php echo json_encode(array_values($syerStats)); ?>;
        var syerLabels = <?php echo json_encode($syerLabels); ?>;

        //jumlah yuran & syer 
        var totYSData = <?php echo json_encode(array_values($totYSStats)); ?>;
        var totYSLabels = <?php echo json_encode($totYSLabels); ?>;

        //pembiayaan 
        var pDlmProData = <?php echo json_encode(array_values($pDlmProStats)); ?>;
        var pDlmProLabels = <?php echo json_encode($pDlmProLabels); ?>;
        
        var yuranChartCanvas = document.getElementById("yuranChart").getContext("2d");
        var syerChartCanvas = document.getElementById("syerChart").getContext("2d");
        var totYSChartCanvas = document.getElementById("totYSChart").getContext("2d");
        var pDlmProChartCanvas = document.getElementById("pDlmProChart").getContext("2d");   
   
        Chart.register(ChartDataLabels);

        //stats yuran
        var yuranChart = new Chart(yuranChartCanvas, {
            type: 'bar',
            data: {
                labels: yuranLabels,
                datasets: [{
                    data: yuranData,
                    backgroundColor: [                        
                        'rgba( 117, 205, 81, 0.9)',
                        'rgba( 205, 81, 113, 1)',                        
                        'rgba(54, 97, 196, 0.7)',
                        'rgba( 54, 196, 177  , 0.7)',
                        'rgba(236, 213, 95 , 0.7)',
                        'rgba(164, 137, 248, 0.7)',
                        'rgba( 186, 186, 186, 0.7)',
                    ],     
                }],
            },
            options: {
                indexAxis: 'y', // This makes the chart horizontal
                plugins: {
                    legend: {
                        display: true,   
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                return [{
                                    text: 'JUMLAH (RM)',
                                    fillStyle: chart.data.datasets[0].backgroundColor[chart.data.datasets[0].backgroundColor.length - 1], // Use the first color for the legend
                                    hidden: false,
                                    lineCap: 'butt',
                                    lineDash: [],
                                    lineDashOffset: 0,
                                    lineJoin: 'miter',
                                    lineWidth: 0,
                                    strokeStyle: chart.data.datasets[0].borderColor,
                                    pointStyle: 'circle',
                                    datasetIndex: 0
                                }];
                            }
                        }
                    },
                    datalabels: {
                        color: 'black',
                        formatter: (value, context) => {
                            return value; // Return only the value, without percentage
                        },
                        font: {
                            weight: 'bold',
                            size: 10,
                        },
                    },
                },
            },
        });

        //stats syer
        var syerChart = new Chart(syerChartCanvas, {
            type: 'bar',
            data: {
                labels: syerLabels,
                datasets: [{
                    data: syerData,
                    backgroundColor: [
                        'rgba(236, 213, 95 , 0.7)',                        
                        'rgba( 117, 205, 81, 0.9)',
                        'rgba( 205, 81, 113, 1)',                        
                        'rgba(54, 97, 196, 0.7)',
                        'rgba( 54, 196, 177  , 0.7)',   
                        'rgba(164, 137, 248, 0.7)', 
                        'rgba( 186, 186, 186, 0.7)',                    
                    ],     
                }],
            },
            options: {
                indexAxis: 'y', // This makes the chart horizontal
                plugins: {
                    legend: {
                        display: true,   
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                return [{
                                    text: 'JUMLAH (RM)',
                                    fillStyle: chart.data.datasets[0].backgroundColor[chart.data.datasets[0].backgroundColor.length - 1], // Use the first color for the legend
                                    hidden: false,
                                    lineCap: 'butt',
                                    lineDash: [],
                                    lineDashOffset: 0,
                                    lineJoin: 'miter',
                                    lineWidth: 0,
                                    strokeStyle: chart.data.datasets[0].borderColor,
                                    pointStyle: 'circle',
                                    datasetIndex: 0
                                }];
                            }
                        }
                    },
                    datalabels: {
                        color: 'black',
                        formatter: (value, context) => {
                            return value; // Return only the value, without percentage
                        },
                        font: {
                            weight: 'bold',
                            size: 10,
                        },
                    },
                },
            },
        });

          //stats yuran & syer
          var totYSChart = new Chart(totYSChartCanvas, {
            type: 'pie',
            data: {
                labels: ['YURAN', 'SYER'],
                datasets: [{
                    data: [<?php echo $totYSLabels['yuran']; ?>, <?php echo $totYSLabels['syer']; ?>], // PHP variables inserted into the JS
                    backgroundColor: [
                        // 'rgba( 186, 186, 186, 0.7)',
                        'rgba( 117, 205, 81, 0.9)',
                        'rgba( 205, 81, 113, 1)',
                        'rgba(164, 137, 248, 0.7)',
                        'rgba(54, 97, 196, 0.7)',
                        'rgba( 54, 196, 177  , 0.7)',
                        'rgba(236, 213, 95 , 0.7)',
                    ],     
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: true,   
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        color: 'black',
                        formatter: (value, context) => {
                            return value; // Return only the value, without percentage
                        },
                        font: {
                            weight: 'bold',
                            size: 10,
                        },
                    },
                },
            },
        });

        //stats pembiayaan
        var pDlmProChart = new Chart(pDlmProChartCanvas, { 
    type: 'line', 
    data: { 
        labels: pDlmProLabels, 
        datasets: [{ 
            data: pDlmProData, 
            backgroundColor: [ 
                'rgba( 117, 205, 81, 0.9)', 
                'rgba( 205, 81, 113, 1)', 
                'rgba(54, 97, 196, 0.7)', 
                'rgba( 54, 196, 177  , 0.7)', 
                'rgba(236, 213, 95 , 0.7)',                   
            ],   
            pointRadius: 9,  
        }], 
    }, 
    options: { 
        plugins: { 
            legend: { 
                display: true,    
                position: 'bottom', 
                labels: { 
                    fontSize: 10, 
                    usePointStyle: true 
                } 
            }, 
            datalabels: { 
                color: 'black', 
                formatter: (value, context) => { 
                    return value; // Return only the value, without percentage 
                }, 
                font: { 
                    weight: 'bold', 
                    size: 10, 
                }, 
            }, 
        }, 
        scales: {
            y: {
                beginAtZero: true, // Ensure the Y-axis starts at 0
                ticks: {
                    min: 0, // Optional: set the minimum value of the Y-axis
                    max: 100, // Optional: set the maximum value of the Y-axis
                    stepSize: 10, // Optional: set the step size for tick marks
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)', // Optional: customize grid line color
                },
            },
        },
    }, 
});

     </script>
    
</body>
</html>
