<?php
date_default_timezone_set("Asia/Jakarta");

include("header.php");
include("koperasiQry.php");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 AND get_session("Cookie_groupID") <> 5 OR get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$primaryK = str_replace(":", "", $pk);
$name =  dlookup("users", "name", "userID=" . tohtml($primaryK, "Text"));

$title     = "Statistik Koperasi ".$name."";

// Define the connection parameters
$dbParams = array(
    array('dbtype' => $DB_dbtype11, 'hostname' => $DB_hostname11, 'username' => $DB_username11, 'password' => $DB_password11, 'dbname' => $DB_dbname11, 'name' => 'sekata')
);

// ----------------------------- fetch query -------------------------------------

// Total anggota aktif dalam koperasi
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(*) AS count FROM userdetails WHERE status = 1";
        $result = $connn->Execute($query);
        
        if ($result) {
            $row = $result->FetchRow();
            $totalUser = $row['count'];
        }
    }
}

// Jumlah yuran ikut tahun
$yuranStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT YEAR(a.createdDate) AS year, SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END ) AS jumlah FROM transaction a JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID in (1595)                  
                  AND b.status in (1,4)
                  GROUP BY YEAR(a.createdDate)
                  order by year";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = $row['year'];
                $jumlah = $row['jumlah'];
                $yuranStats[] = $jumlah;
            }
        }
    }
}

// Jumlah syer ikut tahun
$syerStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT YEAR(updatedDate) AS year, 
                    ABS(SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END)) AS jumlah
                    FROM transaction 
                    WHERE deductID IN (1596,1780)
                    GROUP BY YEAR(updatedDate)
                    ORDER BY year";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $year = $row['year'];
                $jumlah = $row['jumlah'];
                $syerStats[] = $jumlah;
            }
        }
    }
}

$pDlmProStats = array(
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
}

// Initialize arrays to hold total stats for yuran and syer
$totYSStats = array(
    'yuran' => 0,
    'syer' => 0
);

foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
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
        
        // Query for syer
        $querySyer = "SELECT ABS(SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END)) AS jumlah
                      FROM transaction 
                      WHERE deductID IN (1596,1780)";
        $resultSyer = $connn->Execute($querySyer);
        
        if ($resultSyer) {
            if ($row = $resultSyer->FetchRow()) {
                $jumlahSyer = $row['jumlah'];
                $totYSStats['syer'] += $jumlahSyer;
            }
        }
    }
}

// ----------------------------- store in array -------------------------------------

//yuran statistik
$yuranLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT YEAR(a.createdDate) AS year FROM transaction a JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID IN (1595)
                  AND b.status IN (1, 4)
                  GROUP BY YEAR(a.createdDate)
                  ORDER BY year";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $yuranLabels[] = strtoupper($row['year']);
            }
        }
    } 
}

//syer statistik
$syerLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT YEAR(updatedDate) AS year 
                    FROM transaction 
                    WHERE deductID IN (1596,1780)
                    GROUP BY YEAR(updatedDate)
                    ORDER BY year";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $syerLabels[] = strtoupper($row['year']);
            }
        }
    } 
}

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
                -- COUNT(CASE WHEN status = 1 THEN 1 END) AS disediakan,
                -- COUNT(CASE WHEN status = 2 THEN 1 END) AS disemak,
                COUNT(CASE WHEN status = 3 THEN 1 END) AS diluluskan
            FROM loans 
            WHERE status IN (0,1,2,3)";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pDlmProLabelsArray['DALAM PROSES'] += $row['dalam_proses'];
                // $pDlmProLabelsArray['DISEDIAKAN'] += $row['disediakan'];
                // $pDlmProLabelsArray['DISEMAK'] += $row['disemak'];
                $pDlmProLabelsArray['DILULUSKAN'] += $row['diluluskan'];
            }    
        }
    } 
    $pDlmProLabels = array_keys($pDlmProLabelsArray);
}

// Initialize arrays to hold counts for yuran and syer
$totYSLabels = array(
    'yuran' => 0,
    'syer' => 0
);

foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        // Query for yuran
        $queryYuran = "SELECT SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END) AS jumlah 
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
                      AND b.status IN (1, 4)";
        $resultSyer = $connn->Execute($querySyer);
        
        if ($resultSyer) {
            if ($row = $resultSyer->FetchRow()) {
                $totYSLabels['syer'] += $row['jumlah'];
            }
        }
    }
}

// Convert the associative array to a numeric array
$numericArray = array_values($totYSLabels);

// -------------------------- jumlah keseluruhan ---------------------------

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

    .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .chart {
        width: calc(33.33% - 20px);
        margin: 10px;
        padding: 10px;
        background-color: #fff;
        /* box-shadow: 0 0 10px rgba(202, 202, 202, 0.7); */
        border-radius: 30px;
    }

    .chart canvas {
        width: 100%;
        height: 100%;
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
            <canvas id="totYSChart" style="display: block;"></canvas>
        </div>
        <div class="chart" >
            <h4 align="center">Jumlah Pembiayaan</h4><br>
            <canvas id="pDlmProChart" style="display: block;"></canvas>
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
                        'rgba( 186, 186, 186, 0.7)',
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
                                    fillStyle: chart.data.datasets[0].backgroundColor[0], // Use the first color for the legend
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
                            // Convert array of strings to an array of numbers and then calculate the total
                            const total = yuranData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            //zonTotalElement.innerHTML = "Jumlah: " + total + " Koperasi";
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            // return value + ' (' + percentage + ')';    
                            return (value / 1e6).toFixed(1) + ' M'; // Format to millions
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
                        'rgba( 186, 186, 186, 0.7)',
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
                                    fillStyle: chart.data.datasets[0].backgroundColor[0], // Use the first color for the legend
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
                            // Convert array of strings to an array of numbers and then calculate the total
                            const total = syerData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            //zonTotalElement.innerHTML = "Jumlah: " + total + " Koperasi";
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            // return value + ' (' + percentage + ')'; 
                            return (value / 1e6).toFixed(1) + ' M'; // Format to millions   
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
                            // Convert array of strings to an array of numbers and then calculate the total
                            const total = totYSData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            //zonTotalElement.innerHTML = "Jumlah: " + total + " Koperasi";
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            // return value + ' (' + percentage + ')';  
                            return (value / 1e6).toFixed(1) + ' M'; // Format to millions

                        },
                        font: {
                            weight: 'bold',
                            size: 13,
                        },
                    },
                },
            },
        });

        //stats pembiayaan dalam proses
        var pDlmProChart = new Chart(pDlmProChartCanvas, {
            type: 'pie',
            data: {
                labels: pDlmProLabels,
                datasets: [{
                    data: pDlmProData,
                    backgroundColor: [
                        'rgba( 186, 186, 186, 0.7)',
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
                            // Convert array of strings to an array of numbers and then calculate the total
                            const total = pDlmProData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            //zonTotalElement.innerHTML = "Jumlah: " + total + " Koperasi";
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return value + ' (' + percentage + ')';    
                            
                        },
                        font: {
                            weight: 'bold',
                            size: 13,
                        },
                    },
                },
            },
        });

     </script>
    
</body>
</html>
