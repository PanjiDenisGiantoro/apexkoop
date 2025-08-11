<?php
$DB_dbtype = 'mysql';
$DB_hostname = 'localhost';
$DB_username = 'root';
$DB_password = '';

// Establish connection to MySQL server
$conn = new mysqli($DB_hostname, $DB_username, $DB_password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mendapatkan semua pangkalan data
$sql = "SHOW DATABASES";
$result = $conn->query($sql);

// Check if the query returned any results
if ($result->num_rows > 0) {
    // Fetch all databases into an array
    $databases = array();
    while ($row = $result->fetch_assoc()) {
        $databases[] = $row['Database'];
    }

    // Loop untuk setiap pangkalan data
    foreach ($databases as $db) {
        // Exclude databases whose name contains 'demo' or 'uat'
        if (strpos($db, 'demo') !== false || strpos($db, 'uat') !== false) {
            continue; // Skip this iteration if the database name contains 'demo' or 'uat'
        }

        // Pilih pangkalan data
        $conn->select_db($db);

        // Check if 'setup' table exists
        $check_table_sql = "SHOW TABLES LIKE 'setup'";
        $check_result = $conn->query($check_table_sql);

        if ($check_result && $check_result->num_rows > 0) {
            // SQL query untuk mendapatkan data yang diinginkan
            $sql_query = "
            SELECT a.*, b.*, g.name AS loanTypeName, u.name AS userName, DATEDIFF(a.applyDate, b.ajkDate2) AS date1
            FROM loans a
            JOIN loandocs b ON a.loanID = b.loanID
            LEFT JOIN general g ON a.loanType = g.ID
            LEFT JOIN users u ON a.userID = u.userID
            WHERE a.status = '3'
            AND b.ajkDate2 BETWEEN '$dtFrom' AND '$dtTo'
            ORDER BY date1 ASC";
            //WHERE b.result = 'lulus'

            // Laksanakan query
            $query_result = $conn->query($sql_query);

            // Check if the query has results 
            if ($query_result && $query_result->num_rows > 0) {
                // Mulakan output HTML jika ada data
                print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
                    <html>
                        <head>
                            <title>' . strtoupper($db) . '</title>
                        </head>
                        <body>
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
                                    <td colspan="9" align="right">' . strtoupper($db) . '</td>
                                </tr>
                                <tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
                                    <th colspan="9" height="40"><font color="#FFFFFF">' . strtoupper($db) . '<br>
                                        From ' . date("d/m/Y", strtotime($dtFrom)) . ' to ' . date("d/m/Y", strtotime($dtTo)) . '</font>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="9"><font size=1>Printed on : ' . date("d/m/Y H:i:s") . '</font></td>
                                </tr>
                                <tr>
                                    <td colspan="9">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="9">
                                        <table border=0 cellpadding="2" cellspacing="1" align="left" width="100%" bgcolor="999999">
                                            <tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
                                                <th nowrap>Bil</th>
                                                <th nowrap>No Rujukan</th>
                                                <th nowrap>Jenis Pembiayaan</th>
                                                <th nowrap>No Anggota</th>
                                                <th nowrap>Nama Anggota</th>
                                                <th nowrap>Jumlah Pembiayaan (RM)</th>
                                                <th nowrap>Tarikh Diluluskan</th>
                                            </tr>';

                $bil = 0;  // Initialize record count 
                $totalsum = 0;  // Initialize total sum 

                while ($row = $query_result->fetch_assoc()) {
                    $bil++;
                    $totalsum += $row['loanAmt'];

                    print '<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF"> 
                    <td align="center">' . $bil . '.</td> 
                    <td align="center">' . htmlspecialchars($row['loanNo']) . '</td> 
                    <td>' . htmlspecialchars($row['loanTypeName']) . '</td> 
                    <td align="center">' . htmlspecialchars($row['userID']) . '</td> 
                    <td>' . htmlspecialchars($row['userName']) . '</td> 
                    <td align="right">' . number_format($row['loanAmt'], 2) . '</td> 
                    <td align="center">' . date("d/m/Y", strtotime($row['ajkDate2'])) . '</td> 
                </tr>';
                }

                print '<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF"> 
                <td colspan="5">Jumlah Keseluruhan</td> 
                <td align="right">' . number_format($totalsum, 2) . '</td> 
                <td colspan="2">&nbsp;</td> 
            </tr>';

                print '</table>
                    </td>
                    </tr>
                    </table>
                    </body>
                    </html>';
            } else {
                // Jika tiada rekod ditemui, tidak memaparkan apa-apa
                // Tidak ada tindakan diperlukan di sini kerana kita tidak mahu memaparkan jadual kosong.
            }
        }
    }
} else {
    die("No databases found.");
}

// Close the connection when done
$conn->close();
