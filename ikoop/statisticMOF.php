<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

include("header.php");
include("koperasiQry.php");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 AND get_session("Cookie_groupID") <> 5 OR get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$title     = "Statistik Koperasi";

// Define the connection parameters
$dbParams = array(
    array('dbtype' => $DB_dbtype1, 'hostname' => $DB_hostname1, 'username' => $DB_username1, 'password' => $DB_password1, 'dbname' => $DB_dbname1, 'name' => 'kpkkb'),
    array('dbtype' => $DB_dbtype2, 'hostname' => $DB_hostname2, 'username' => $DB_username2, 'password' => $DB_password2, 'dbname' => $DB_dbname2, 'name' => 'kpktb'),
    array('dbtype' => $DB_dbtype3, 'hostname' => $DB_hostname3, 'username' => $DB_username3, 'password' => $DB_password3, 'dbname' => $DB_dbname3, 'name' => 'kosite'),
    array('dbtype' => $DB_dbtype4, 'hostname' => $DB_hostname4, 'username' => $DB_username4, 'password' => $DB_password4, 'dbname' => $DB_dbname4, 'name' => 'kpfspb'),
    array('dbtype' => $DB_dbtype5, 'hostname' => $DB_hostname5, 'username' => $DB_username5, 'password' => $DB_password5, 'dbname' => $DB_dbname5, 'name' => 'kohidmas'),
    array('dbtype' => $DB_dbtype6, 'hostname' => $DB_hostname6, 'username' => $DB_username6, 'password' => $DB_password6, 'dbname' => $DB_dbname6, 'name' => 'koguna'),
    array('dbtype' => $DB_dbtype7, 'hostname' => $DB_hostname7, 'username' => $DB_username7, 'password' => $DB_password7, 'dbname' => $DB_dbname7, 'name' => 'kojpjk'),
    array('dbtype' => $DB_dbtype8, 'hostname' => $DB_hostname8, 'username' => $DB_username8, 'password' => $DB_password8, 'dbname' => $DB_dbname8, 'name' => 'kppmppjdpknsb'), //kppmppjdpknsb
    array('dbtype' => $DB_dbtype9, 'hostname' => $DB_hostname9, 'username' => $DB_username9, 'password' => $DB_password9, 'dbname' => $DB_dbname9, 'name' => 'kpfp'),
    array('dbtype' => $DB_dbtype10, 'hostname' => $DB_hostname10, 'username' => $DB_username10, 'password' => $DB_password10, 'dbname' => $DB_dbname10, 'name' => 'koopbait')
);

// ----------------------------- fetch query -------------------------------------
// Total Koperasi terlibat
$totalCooperatives = $conn->Execute("SELECT COUNT(*) AS total FROM userdetails WHERE jenisCode = 2019")->fields['total'];

// Count based on department
$departmentStats = array();
$departmentQuery = $conn->Execute("SELECT departmentID, COUNT(*) AS count FROM userdetails WHERE jenisCode = 2019 GROUP BY departmentID");
while (!$departmentQuery->EOF) {
    $departmentID = $departmentQuery->fields['departmentID'];
    $count = $departmentQuery->fields['count'];
    $departmentStats[$departmentID] = $count;
    $departmentQuery->MoveNext();
}


// Count anggota aktif based on koperasi
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(*) AS count FROM userdetails WHERE status = 1";
        $result = $connn->Execute($query);
        
        if ($result) {
            $row = $result->FetchRow();
            $count = $row['count'];
            $stateStats[] = $dbParam['name'] = $count;
        }
    }
}

// Jumlah yuran based on koperasi
$yuranStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END ) AS jumlah FROM transaction a JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID in (1595)                  
                  AND b.status in (1,4)";
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

// Jumlah syer based on koperasi
$syerStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END) AS jumlah
                    FROM transaction 
                    WHERE deductID IN (1596,1780)";
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

// Jumlah pembiayaan dalam proses
$pDlmProStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 0 THEN 1 END) AS dalam_proses            
                  FROM loans 
                  WHERE status = 0";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $total = $row['dalam_proses'];
                $pDlmProStats[] = $total;             
            }    
        }
    }
}

// Jumlah pembiayaan disediakan
$pSediaStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 1 THEN 1 END) AS disediakan            
                  FROM loans 
                  WHERE status = 1";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $total = $row['disediakan'];
                $pSediaStats[] = $total;             
            }    
        }
    }
}

// Jumlah pembiayaan disemak
$pSemakStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 2 THEN 1 END) AS disemak            
                  FROM loans 
                  WHERE status = 2";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $total = $row['disemak'];
                $pSemakStats[] = $total;             
            }    
        }
    }
}

// Jumlah pembiayaan diluluskan
$pLulusStats = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 3 THEN 1 END) AS diluluskan            
                  FROM loans 
                  WHERE status = 3";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $total = $row['diluluskan'];
                $pLulusStats[] = $total;             
            }    
        }
    }
}


// ----------------------------- store in array -------------------------------------

// List department
$zonLabels = array();
foreach ($departmentStats as $departmentID => $count) {
    $departmentName = dlookup("general", "name", "ID=" . tosql($departmentID, "Number"));
    if ($departmentName === null) {
        $departmentName = "Unknown";
    } 
    $zonLabels[] = $departmentName;
}

// List koperasi
$stateLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(*) AS count FROM userdetails WHERE status = 1";
        $result = $connn->Execute($query);
        if ($result) {
            $row = $result->FetchRow();
            $count1 = $row['count'];
            $stateLabels[] = strtoupper($dbParam['name']);
        }
    } 
}

// List yuran
$yuranLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT SUM(CASE WHEN a.addminus = '0' THEN -a.pymtAmt ELSE a.pymtAmt END ) AS jumlah FROM transaction a JOIN userdetails b ON a.userID = b.userID
                  JOIN users c ON b.userID = c.userID
                  WHERE a.deductID in (1595)                  
                  AND b.status in (1,4)";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $yuranLabels[] = strtoupper($dbParam['name']);
            }
        }
    } 
}

// List syer
$syerLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT SUM(CASE WHEN addminus = '1' THEN pymtAmt ELSE 0 END) - SUM(CASE WHEN addminus = '0' THEN pymtAmt ELSE 0 END) AS jumlah
                    FROM transaction 
                    WHERE deductID IN (1596,1780)";
        $result = $connn->Execute($query);
        if ($result) {
            while ($row = $result->FetchRow()) {
                $syerLabels[] = strtoupper($dbParam['name']);
            }
        }
    } 
}

// List pembiayaan dalam proses
$pDlmProLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 0 THEN 1 END) AS dalam_proses            
                  FROM loans 
                  WHERE status = 0";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pDlmProLabels[] = strtoupper($dbParam['name']);      
            }    
        }
    }
}

// List pembiayaan disediakan
$pSediaLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 1 THEN 1 END) AS disediakan            
                  FROM loans 
                  WHERE status = 1";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pSediaLabels[] = strtoupper($dbParam['name']);      
            }    
        }
    }
}

// List pembiayaan disemak
$pSemakLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 2 THEN 1 END) AS disemak            
                  FROM loans 
                  WHERE status = 2";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pSemakLabels[] = strtoupper($dbParam['name']);      
            }    
        }
    }
}

// List pembiayaan diluluskan
$pLulusLabels = array();
foreach ($dbParams as $dbParam) {
    // Create a new connection for each database
    $connn = &ADONEWConnection($dbParam['dbtype']);
    $connected = $connn->Connect($dbParam['hostname'], $dbParam['username'], $dbParam['password'], $dbParam['dbname']);
    
    if ($connected) {
        $query = "SELECT COUNT(CASE WHEN status = 3 THEN 1 END) AS diluluskan            
                  FROM loans 
                  WHERE status = 3";
        $result = $connn->Execute($query);
        
        if ($result) {
            while ($row = $result->FetchRow()) {
                $pLulusLabels[] = strtoupper($dbParam['name']);      
            }    
        }
    }
}

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
    <div class="title">Jumlah Koperasi CODE : <?php echo $totalCooperatives; ?></div>
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
        /* width: 45%; */
        margin: 10px;
        padding: 10px;
        background-color: #fff;
        /* box-shadow: 0 0 10px rgba(202, 202, 202, 0.7); */
        border-radius: 30px;
    }

    .chart canvas {
        width: 100% !important;
        /* height: 100%; */
        height: auto !important;
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


    .centered-charts {
        display: flex;
        width: 100%;
        justify-content: center;
    }

    .category-container.chart .centered-charts .chart {
    width:  600px;
}
</style>
</head>

<body>
<div class="category-container chart">
    <!-- Category 1 -->
    <div class="category-title">Statistik Asas Koperasi CODE</div>
    <div class="centered-charts">
        <div class="chart" >
            <h4 align="center">Zon Koperasi</h4><br>
            <canvas id="zonPieChart" style="display: block;"></canvas>
        </div>
        <div class="chart">
            <h4 align="center">Anggota Koperasi Aktif</h4><br>
            <canvas id="statePieChart" style="display: block;"></canvas>
        </div>
    </div>

    <div class="chart" style="width: calc(100%);"></div>
    <!-- Category 2 -->
    <div class="category-title">Jumlah Keseluruhan Yuran & Syer Mengikut Koperasi</div>
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
    <!-- Category 3 -->
    <div class="category-title">Jumlah Pembiayaan Mengikut Status</div>
    <div class="centered-charts">
        <div class="chart" >
            <h4 align="center">Pembiayaan Dalam Proses</h4><br>
            <canvas id="pDlmProChart" style="display: block;"></canvas>
        </div>      
        <div class="chart" >
            <h4 align="center">Pembiayaan Disediakan</h4><br>
            <canvas id="pSediaChart" style="display: block;"></canvas>
        </div>   
    </div>
    <div class="chart" style="width: calc(100%);"></div>
    <div class="centered-charts">
        <div class="chart" >
            <h4 align="center">Pembiayaan Disemak</h4><br>
            <canvas id="pSemakChart" style="display: block;"></canvas>
        </div>      
        <div class="chart" >
            <h4 align="center">Pembiayaan Diluluskan</h4><br>
            <canvas id="pLulusChart" style="display: block;"></canvas>
        </div>    
    </div>
</div>
 


    <script>
        //zon
        var zonData = <?php echo json_encode(array_values($departmentStats)); ?>;
        var zonLabels = <?php echo json_encode($zonLabels); ?>;

        //state
        var stateData = <?php echo json_encode(array_values($stateStats)); ?>;
        var stateLabels = <?php echo json_encode($stateLabels); ?>;

        //yuran
        var yuranData = <?php echo json_encode(array_values($yuranStats)); ?>;
        var yuranLabels = <?php echo json_encode($yuranLabels); ?>;

        //syer
        var syerData = <?php echo json_encode(array_values($syerStats)); ?>;
        var syerLabels = <?php echo json_encode($syerLabels); ?>;

        //pembiayaan dalam proses
        var pDlmProData = <?php echo json_encode(array_values($pDlmProStats)); ?>;
        var pDlmProLabels = <?php echo json_encode($pDlmProLabels); ?>;

        //pembiayaan disediakan
        var pSediaData = <?php echo json_encode(array_values($pSediaStats)); ?>;
        var pSediaLabels = <?php echo json_encode($pSediaLabels); ?>;

        //pembiayaan disediakan
        var pSemakData = <?php echo json_encode(array_values($pSemakStats)); ?>;
        var pSemakLabels = <?php echo json_encode($pSemakLabels); ?>;

        //pembiayaan disediakan
        var pLulusData = <?php echo json_encode(array_values($pLulusStats)); ?>;
        var pLulusLabels = <?php echo json_encode($pLulusLabels); ?>;

        
        var zonPieChartCanvas = document.getElementById("zonPieChart").getContext("2d");
        var statePieChartCanvas = document.getElementById("statePieChart").getContext("2d");
        var yuranChartCanvas = document.getElementById("yuranChart").getContext("2d");
        var syerChartCanvas = document.getElementById("syerChart").getContext("2d");
        var pDlmProChartCanvas = document.getElementById("pDlmProChart").getContext("2d");   
        var pSediaChartCanvas = document.getElementById("pSediaChart").getContext("2d");  
        var pSemakChartCanvas = document.getElementById("pSemakChart").getContext("2d");  
        var pLulusChartCanvas = document.getElementById("pLulusChart").getContext("2d");  


        Chart.register(ChartDataLabels);

        //stats zon
        var zonPieChart = new Chart(zonPieChartCanvas, {
    type: 'bar',
    data: {
        labels: zonLabels,
        datasets: [{
            data: zonData,
            backgroundColor: [
                'rgba(186, 186, 186, 0.7)',
                'rgba(117, 205, 81, 0.9)',
                'rgba(205, 81, 113, 1)',
                'rgba(164, 137, 248, 0.7)',
                'rgba(54, 97, 196, 0.7)',
                'rgba(54, 196, 177, 0.7)',
                'rgba(236, 213, 95, 0.7)',
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
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                return [{
                                    text: 'Zon',
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
                formatter: (value) => {
                    const num = Number(value);
                    if (isNaN(num) || num < 100000) return ''; // Hide labels under 100k
                    if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
                    if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
                    return num.toString();
                },
                color: (context) => {
                    const value = context.dataset.data[context.dataIndex];
                    return value < 1000000 ? 'black' : 'white';
                },
                align: (context) => {
                    const value = context.dataset.data[context.dataIndex];
                    return value < 1000000 ? 'end' : 'center';
                },
                anchor: (context) => {
                    const value = context.dataset.data[context.dataIndex];
                    return value < 1000000 ? 'end' : 'center';
                },
                font: {
                    weight: 'bold',
                    size: 13,
                },
                clamp: true
            }
        }
    },
    plugins: [ChartDataLabels]
});

         // stats anggota koperasi aktif
         var statePieChart = new Chart(statePieChartCanvas, {
            type: 'bar',
            data: {
                labels: stateLabels,
                datasets: [{
                    data: stateData,
                    backgroundColor: [
                        // 'rgba( 186, 186, 186, 0.7)', //unknown
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
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
                            fontSize: 5,
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                return [{
                                    text: 'Koperasi Aktif',
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
                            return value; // Return only the value, without percentage
                        },
                        color: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'black' : 'black';
    },
    align: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    anchor: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    font: {
        weight: 'bold',
        size: 10,
    },
    clamp: true,
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        },
                        categoryPercentage: 0.5, // Adjust space allocated for bars
                    }
                }
            },
        });

         //stats yuran
         var yuranChart = new Chart(yuranChartCanvas, {
            type: 'bar',
            data: {
                labels: yuranLabels,
                datasets: [{
                    data: yuranData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
                    ],     
                }],
            },
            options: {
                indexAxis: 'x', // This makes the chart horizontal
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
    formatter: (value) => {
        const num = Number(value);
        if (isNaN(num) || num < 100000) return ''; // Hide label if under 100k
        if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M'; // e.g. 1.5M
        if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K'; // e.g. 850K
        return num.toString();
    },
    color: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'black' : 'black';
    },
    align: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    anchor: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    font: {
        weight: 'bold',
        size: 8,
    },
    clamp: true // Keeps labels from overflowing chart area
},
                },
                scales: {
                    x: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        },
                        categoryPercentage: 0.5, // Adjust space allocated for bars
                    }
                }
            },
        });

        //syer chart
        var syerChart = new Chart(syerChartCanvas, {
            type: 'bar',
            data: {
                labels: syerLabels,
                datasets: [{
                    data: syerData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',     // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
                    ],
                }],
            },
            options: {
                indexAxis: 'x', // This makes the chart horizontal
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
    formatter: (value) => {
        const num = Number(value);
        if (isNaN(num) || num < 100000) return ''; // Hide label if under 100k
        if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M'; // e.g. 1.5M
        if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K'; // e.g. 850K
        return num.toString();
    },
    color: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'black' : 'black';
    },
    align: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    anchor: (context) => {
        const value = context.dataset.data[context.dataIndex];
        return value < 1000000 ? 'end' : 'end';
    },
    font: {
        weight: 'bold',
        size: 8,
    },
    clamp: true // Keeps labels from overflowing chart area
},
                },
                scales: {
                    x: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: false,
                        },
                        grid: {
                            display: false,
                        },
                        categoryPercentage: 0.5, // Adjust space allocated for bars
                    }
                }
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
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
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
                            size: 13,
                        },
                    },
                },
            },
        });

        //stats pembiayaan disediakan
        var pSediaChart = new Chart(pSediaChartCanvas, {
            type: 'pie',
            data: {
                labels: pSediaLabels,
                datasets: [{
                    data: pSediaData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
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
                            size: 13,
                        },
                    },
                },
            },
        });

         //stats pembiayaan disemak
         var pSemakChart = new Chart(pSemakChartCanvas, {
            type: 'pie',
            data: {
                labels: pSemakLabels,
                datasets: [{
                    data: pSemakData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
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
                            size: 13,
                        },
                    },
                },
            },
        });

         //stats pembiayaan diluluskan
         var pLulusChart = new Chart(pLulusChartCanvas, {
            type: 'pie',
            data: {
                labels: pLulusLabels,
                datasets: [{
                    data: pLulusData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
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
                            size: 13,
                        },
                    },
                },
            },
        });


    </script>
    
</body>
</html>
