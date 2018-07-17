<?php
  // Processing requests
  $log = "";
  if (isset($_POST["req"])) {
    $log = "    = Execution =============================\n      <br>\n";
    $log = $log."      <br>Request: ${_POST['req']}\n";
    $log = $log."      <br>\n      <br>= Log ===================================\n      <br>\n";
    $request = $_POST["req"];
    switch ($request) {
      case "gen-on":
        execute_command($log, cmwrap("C2:OUTP ON").";".cmwrap("C2:OUTPut?"), "Generator ON");
	break;
      case "gen-off":
        execute_command($log, cmwrap("C2:OUTP OFF").";".cmwrap("C2:OUTPut?"), "Generator OFF");
	break;
      case "gen-status":
        execute_command($log, cmwrap("C2:OUTPut?"), "Generator Status Request");
	break;
      case "att-setattenuation":
	$atten = $_POST['attenuation_db'];
        if (is_numeric($atten) ) {
          execute_command($log, "sudo -u pi /var/www/html/runSerial 2 1 \"a".$atten."\\n\"", "Attenuator Current Position");
	} else {
          $log = $log."      <br>Requested attenuation ($atten) is not numeric. Aborting";
	}
	break;
      case "att-position":
        execute_command($log, "sudo -u pi /var/www/html/runSerial 0 1 d", "Attenuator Current Position");
	break;
      case "att-status":
        execute_command($log, "sudo -u pi /var/www/html/runSerial 0 1 cd", "Attenuator Status Request");
	break;
      default:
	$log = $log."      <br>Non recognized request received\n";
    }
  }

  // Printing page
  print_page($log);

  function cmwrap($command) {
    return "sudo -u pi /snap/bin/lxi scpi -a 192.168.42.11 \"$command\"";
  }

  function execute_command(&$log, $command, $label){
      $log = $log."      <br>Received:\n      <br>$label\n      <br>\n";
      exec($command." 2>&1", $result);
      $log = $log."      <br>Result:\n";
      for ($i=0; $i<count($result); $i++){
        $log = $log."      <br>".$result[$i]."\n";
      }
      $log = $log."      <br>\n      <br>Finished:\n      <br>$label";
  }

  function print_page($log) {
    echo '
<html>
  <head>
  </head>
  <body>
    <center>
      <table>
        <tr>
          <td colspan="3" width="100px" align="center">
            Signal Generator
          </td>
        </tr>
        <tr>
          <td align="center">
            <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="gen-on">Triggering Signal ON</button>
            </form>
          </td>
          <td align="center">
            <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="gen-off">Triggering Signal Off</button>
            </form>
          </td>  
          <td align="center">
            <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="gen-status">Signal Status</button>
            </form>
          </td>  
        </tr>       
        <tr>
          <td colspan="3" width="100px" align="center">
            Attenuator
          </td>
        </tr>
        <tr valign="top">
          <td align="center">
	    <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="att-setattenuation">Set Attenuation [db]</button><br>
              <input type="text" name="attenuation_db">
            </form>
          </td>
          <td align="center">
            <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="att-position">Get Current Attenuation</button>
            </form>
          </td>  
          <td align="center">
            <form action"index.php" method="post">
              <button style="width:150px;" type="submit" name="req" value="att-status">Attenuator Status</button>
            </form>
          </td>  
        </tr>       
      </table>
    </center>';
    if ($log != "") {
      echo "    <div style=\"font-family:courier new;width:400px;background-color:#D2D2D2;padding:10px;margin-left:auto;margin-right:auto;\">\n      $log\n    </div>";
    }
    echo '  </body>
</html>
    ';
  }
?>
