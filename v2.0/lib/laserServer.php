<?php

    function command_validation($command) {
        return TRUE;
    }

    function get_query($point) {
        return "SELECT `name`, `value` FROM `current` WHERE `name` = '${point}';";
    }
    
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

    $ok = 0;
    if (isset($user["commands"])) {
        $commands = $user["commands"];
        foreach ($commands as $command) {
            if (! command_validation($command)) {
                exit;
            }
            $request = "./laserClient.py \"${command}\"";
            exec($request, $result);
            if (is_array($result) && substr($result[0], 0, 2) == "OK") {
                $ok++;
            } else {
                break;
            }
        }
    }
    $responses = array();
    if (isset($user["values"])) {
        $values = $user["values"];
        $con = mysqli_connect("localhost", "henlablaser", "inJub6bMZeXQhdUp", "henlablaser");
        if (! mysqli_connect_errno()) {
            foreach ($values as $value) {
                $query = get_query($value);
                $result = $con->query($query);
                if ($result->num_rows > 0) {
                    $result = $result->fetch_assoc();
                    array_push($responses, $result);
                } else {
                    array_push($responses, "");
                }
            }
            $con->close();
        } else {
            array_push($responses, "ERROR: Not possible to connect to DB");
        }
    }
    $return = array("commands" => $ok, "values" => $responses);
    print(json_encode($return));

?>