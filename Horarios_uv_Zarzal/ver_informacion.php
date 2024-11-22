<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

require_once "config/conexion.php";

// Retrieve all table names from the database
$tablesResult = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $tablesResult->fetch_array()) {
    $tables[] = $row[0];
}

// Initialize an array to hold all data
$allData = [];
$columns = [];

foreach ($tables as $table) {
    if ($table == 'usuarios') continue; // Exclude the 'usuarios' table

    // Get column names
    $columnsResult = $conn->query("SHOW COLUMNS FROM $table");
    $tableColumns = [];
    while ($column = $columnsResult->fetch_assoc()) {
        $tableColumns[] = $column['Field'];
    }

    // Get table data
    $result = $conn->query("SELECT * FROM $table");
    while ($row = $result->fetch_assoc()) {
        $row['table_name'] = $table; // Add table name to each row
        $allData[] = $row;
    }

    // Merge columns
    $columns = array_merge($columns, $tableColumns);
}

// Remove duplicate columns
$columns = array_unique($columns);
$columns[] = 'table_name'; // Add table name column
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Información</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        
        .content {
            padding: 20px;
            text-align: center;
        }
        .table-container {
            margin-bottom: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        h2 {
            color: #007BFF;
        }
        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th, td {
                box-sizing: border-box;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            th {
                background-color: #007BFF;
                color: #fff;
                text-align: left;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                text-align: left;
                font-weight: bold;
            }
        }
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input {
            padding: 10px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <script>
        // Function to sort table columns
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("dataTable");
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }

        // Function to filter table rows based on search input
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toLowerCase();
            table = document.getElementById("dataTable");
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="content">
        <h1>Ver Toda la Información</h1>
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Buscar en la tabla...">
        </div>
        <div class="table-container">
            <table id="dataTable">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                            <th onclick="sortTable(<?php echo array_search($column, $columns); ?>)">
                                <?php echo $column; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allData as $row): ?>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <td data-label="<?php echo $column; ?>"><?php echo isset($row[$column]) ? htmlspecialchars($row[$column]) : ''; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>