<?php

    ///////////////////////////////////////////////////////////////////////////////
    // Useful functions ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    function command_validation($command) {
        return $command;
    }

    function point_validation($point) {
        return $point;
    }

    function window_validation($window) {
        return $window;
    }

    function temps_query($window) {
        return "SELECT `timestamp_diff`, TIME_FORMAT(SEC_TO_TIME(`timestamp_diff`), '%H:%i') as `elapsed`, DATE_FORMAT(FROM_UNIXTIME(`timestamp`), '%H:%i:%s') as `timestamp`, `diode`, `crystal`, `electronicsink`, `heatsink` FROM (SELECT TIMESTAMPDIFF(SECOND, NOW(), `timestamp`) as `timestamp_diff`, UNIX_TIMESTAMP(`timestamp`) as `timestamp`, `diode`, `crystal`, `electronicsink`, `heatsink` FROM `temperatures`) AS `temps` WHERE `temps`.`timestamp_diff` >= -${window};";
        //return "SELECT `timestamp_diff`, `timestamp_diff` as `elapsed`, DATE_FORMAT(FROM_UNIXTIME(`timestamp`), '%H:%i:%s') as `timestamp`, `diode`, `crystal`, `electronicsink`, `heatsink` FROM (SELECT TIMESTAMPDIFF(SECOND, NOW(), `timestamp`) as `timestamp_diff`, UNIX_TIMESTAMP(`timestamp`) as `timestamp`, `diode`, `crystal`, `electronicsink`, `heatsink` FROM `temperatures`) AS `temps` WHERE `temps`.`timestamp_diff` >= -${window};";
    }

    function values_query($point) {
        return "SELECT `name`, `value` FROM `current` WHERE `name` = '${point}';";
    }
    
    ///////////////////////////////////////////////////////////////////////////////
    // Retrieving request /////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    $user = "";
    if (isset($_POST["request"])) {
        $user = json_decode($_POST["request"], true);
    } else {
        if (isset($_GET["request"])) {
            $user = json_decode($_GET["request"], true);
        } else {
            exit;
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    // Accesing data //////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////

    // Creating variables to return
    $ok = 0;
    $values = array();
    $temperatures = array("times" => array(), "label" => array(), "diode" => array(), "crystal" => array(), "electronicsink" => array(), "heatsink" => array());

    // Executing commands
    if (isset($user["commands"])) {
        $commands = $user["commands"];
        foreach ($commands as $command) {
            $command = command_validation($command);
            if ($command === FALSE) {
                exit;
            }
            $request = "./laserClient.py \"${command}\"";
            exec($request, $result);
            if (is_array($result) && substr($result[0], 0, 2) == "OK") {
                $ok++;
            } else {
                break;
            }
            usleep(100000);
        }
    }
    // Retreiving database values
    if (isset($user["values"]) || isset($user["temps"])) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $con = mysqli_connect("localhost", "henlablaser", "inJub6bMZeXQhdUp", "henlablaser");
        $con->query("SET time_zone = 'America/New_York';");
        if (! mysqli_connect_errno()) {
            // Acquiring keyword values
            if (isset($user["values"])) {
            $points = $user["values"];
                foreach ($points as $point) {
                    $point = point_validation($point);
                    if ($point !== FALSE) {
                        $query = values_query($point);
                        $result = $con->query($query);
                        if ($result->num_rows > 0) {
                            $result = $result->fetch_assoc();
                            array_push($values, $result);
                        } else {
                            array_push($values, "");
                        }
                    }
                }
            }
            // Acquiring temperatures
            if (isset($user["temps"])) {
                $window = $user["temps"];
                $window = window_validation($window);
                if($window !== FALSE) {
                    $query = temps_query($window);
                    $result = $con->query($query);
                    $numrows = $result->num_rows;
                    if ($numrows > 0) {
                        $counter = 0;
                        while ($rowtemp = $result->fetch_assoc()) {
                            array_push($temperatures["diode"], floatval($rowtemp["diode"]));
                            array_push($temperatures["crystal"], floatval($rowtemp["crystal"]));
                            array_push($temperatures["electronicsink"], floatval($rowtemp["electronicsink"]));
                            array_push($temperatures["heatsink"], floatval($rowtemp["heatsink"]));
                            array_push($temperatures["times"], intval($rowtemp["timestamp_diff"]));
                            if (++$counter == $numrows) {
                                array_push($temperatures["label"], $rowtemp["timestamp"]);
                            } else {
                                array_push($temperatures["label"], $rowtemp["elapsed"]);
                            }
                        }
                    }
                }
            }
            $con->close();
        } else {
            array_push($responses, "ERROR: Not possible to connect to DB");
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    // Returning result ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////
    $return = array("commands" => $ok, "values" => $values, "temperatures" => $temperatures);
    print(json_encode($return));

?>