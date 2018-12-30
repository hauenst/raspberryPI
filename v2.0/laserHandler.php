<?php

    if (isset($_GET["request"])) {
        $result = "dummy";
        $request = $_GET["request"];
        //$request = escapeshellcmd($request);
        $request = "./laserClient.py \"${request}\"";
        exec($request, $result);
        if (substr($result[0], 0, 2) === "OK") {
            echo 1;
        } else {
            echo 0;
        }

    }

?>