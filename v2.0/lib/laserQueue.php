<?php

    ///////////////////////////////////////////////////////////////////////////////
    // Useful functions ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////
    function compare_timestamps($a, $b)
    {
        return $a[0] - $b[0];
    }

    function queue_query($entry) {
        return("INSERT INTO `queue` (`id`, `timestamp`, `device`, `action`, `parameter`, `message`, `completed`) VALUES (NULL, '{$entry[0]}', '{$entry[2]}', '{$entry[3]}', '{$entry[4]}', '{$entry[5]}', '0');");
    }

    ///////////////////////////////////////////////////////////////////////////////
    // Request handling ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    $to_return = array();
    $to_table   = "";
    $new_script = null;
    $log = [];
    // Reading script file
    if(isset($_FILES["script"])){
        // Checking for uploading errors
        if ($_FILES["script"]["error"] != 0) {
            array_push($log, "New script file received with error: ".$_FILES["script"]["error"]);
            return;
        }
        // Reading file to csv
        $new_script = array();
        $fh = fopen($_FILES["script"]["tmp_name"], 'r');
        while ($line = fgets($fh)) {
            array_push($new_script, str_getcsv($line));
        }
        fclose($fh);
        // Deleting temporal file
        unlink($_FILES["script"]["tmp_name"]);
        // Calculating timestamps
        // If starting point in the pass, set to zero
        if ($new_script[0][1] < time()) {
            $new_script[0][0] = time();
            array_push($log, "First script line in the past, queue will be disabled and the script will start when enabling queue");
        } else {
            $new_script[0][0] = $new_script[0][1];
        }
        // Inserting following lines
        for ($i=1; $i<sizeof($new_script); $i++) {
            if($new_script[$i][1] < 35880960){
                $new_script[$i][0] = $new_script[$i-1][0]+ $new_script[$i][1];
            } else if($new_script[$i][1] > time()){
                $new_script[$i][0] = $new_script[$i][1];
            } else {
                //array_push($log, "");
                array_push($log, "There is a middle instruction dated on the past. This is nor supported");
                return;
            }
        }
        // Sorting the array by calculared timestamps
        usort($new_script, 'compare_timestamps');
        // Sending to database
        // Connecting to database
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $con = mysqli_connect("localhost", "henlablaser", "inJub6bMZeXQhdUp", "henlablaser");
        $con->query("SET time_zone = 'America/New_York';");
        if (! mysqli_connect_errno()) {
            $con->query("TRUNCATE TABLE `queue`");
            $con->query("ALTER TABLE `queue` AUTO_INCREMENT = 1;");
            for($i = 0; $i < sizeof($new_script); $i++) {
                $con->query(queue_query($new_script[$i]));
            }
            // Acquiring temperatures
            $con->close();
        } else {
            array_push($info, "ERROR: Not possible to connect to DB");
        }
        $to_return["loaded_script"] = $new_script;
    }
    // Generating table
    exec("./laserQueue.py", $lines);
    foreach ($lines as $line) {
        $to_table .= $line;
    }
    $to_return["table"] = $to_table;
    print(json_encode($to_return, JSON_PRETTY_PRINT));

?>