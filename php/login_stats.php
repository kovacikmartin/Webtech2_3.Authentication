<?php
    include_once("db_connect.php");
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    try{
        $sqlUserLogs = 
            "SELECT
                l.login_time AS 'Time'
               ,l.login_type AS 'Type'
            FROM
                t_login_log l
            WHERE l.email = ?";

        $stmntUserLogs = $conn->prepare($sqlUserLogs);
        $stmntUserLogs->execute([$_SESSION["email"]]);

        $resultUserLogs = $stmntUserLogs->fetchAll(PDO::FETCH_ASSOC);

        $colsUserLogs = array_keys($resultUserLogs[0]);
    }
    catch(PDOException $e){
        echo "<div class='alert alert-danger' role='alert'>
                Sorry, there was an error retrieving your data.
            </div>";
    }

    try{
        $sqlStats = 
            "SELECT
                 DISTINCT(l.login_type) AS 'type'
                ,COUNT(l.login_type)    AS 'type_count'
            FROM
                t_login_log l
            GROUP BY l.login_type";

        $stmntStats = $conn->query($sqlStats);

        $resultStats = $stmntStats->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        echo "<div class='alert alert-danger' role='alert'>
                Sorry, there was an error retrieving general stats.
            </div>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <title>Login stats</title>
</head>
<body>
    <?php include_once("../partials/header.php"); ?>

    <h3 class="heading">Your past visits :)</h3>
    <table id="userLogsTable">
        <thead>
        <tr>
            <?php
            foreach($colsUserLogs as $colName){
                echo "<th>" . $colName . "</th>";
            }
            ?>
        </tr>
        </thead>

        <tbody>
        <?php
            foreach($resultUserLogs as $row){
            echo "<tr>";

            foreach($colsUserLogs as $index => $colName){      // $index is column index
                echo "<td>".$row[$colName] . "</td>";
            }

            echo "</tr>";
            }
        ?>
        </tbody>
    </table>

    <div class="genStats">
        <h3 class="heading">Total visits</h3>
        <?php
            foreach($resultStats as $row){
                echo "<b>" . ucfirst($row["type"]) . ": </b><span>" . $row["type_count"] . "</span><br>";
            }
        ?>
    </div>
    <?php include_once("../partials/footer.php"); ?>
    <script src="../js/script.js"></script>
</body>
</html>