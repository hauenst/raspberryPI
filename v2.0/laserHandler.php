<?php

    if (isset($_GET["request"])) {
        $result = "dummy";
        $request = $_GET["request"];
        $request = explode(";", $request)[0];
        //$request = escapeshellcmd($request);
        $request = "./laserClient.py \"${request}\" 2>&1";
        //print $request."\n";
        exec($request, $result);
        //print_r($result);
        if (substr($result[0], 0, 2) === "OK") {
            echo 1;
        } else {
            echo 0;
        }
    }

?>