<?php
  
  header('P3P: CP="CAO PSA OUR"');
  session_start();
  include "commons.php";

  /////////////////////////////////////////////////////////////////////////////////
  // Processing requests
  /////////////////////////////////////////////////////////////////////////////////

  function execute_command(&$log, $command, $label){
      $log = $log."\nReceived:\n$label\n\n";
      exec("sudo -u pi /var/www/html/runAttenuator 1 \"".$command."\\n\" 2>&1", $result);
      $log = $log."Result:\n";
      for ($i=0; $i<count($result); $i++){
        if (strlen($result[$i]) != 0) {
          $log = $log.$result[$i]."\n";
        }
      }
      $log = $log."\nFinished:\n$label\n";
  }

  $log = "";
  if (isset($_POST["req"])) {
    $log = "= Execution ===================\n\n";
    $log = $log."Request: ${_POST['req']}\n";
    $log = $log."\n= Log =========================\n";
    $request = $_POST["req"];
    switch ($request) {
      case "att_act_get":
        execute_command($log, "d", "Attenuator Current Position");
        break;
      case "att_act_sdb":
        $atten = $_POST['att_par_ndb'];
        if (is_numeric($atten) ) {
          execute_command($log, "a$atten", "Attenuator Current Position");
          execute_command($log, "d", "Attenuator Current Position");
        } else {
          $log = $log."\nRequested attenuation ($atten) is not numeric. Aborting";
        }
        break;
      default:
        $log = $log."\nNon recognized request received\n";
    }
    log_scraping($log);
  }

  function log_scraping($log) {
    $log = preg_split("/\r\n|\n|\r/", $log);
    foreach ($log as $line) {
      if ($line == "") {
        continue;
      } else {
        if (substr($line, 0, strlen("dPos:")) === "dPos:") {
          $_SESSION['att_par_stp'] = substr($line, strlen("dPos:"));
        }
        if (substr($line, 0, strlen("ATTEN:")) === "ATTEN:") {
          $_SESSION['att_par_ndb'] = substr($line, strlen("ATTEN:"));
          $_SESSION['att_par_idb'] = substr($line, strlen("ATTEN:"));
        }
      }
    }
  }

  /////////////////////////////////////////////////////////////////////////////////
  // Printing page
  /////////////////////////////////////////////////////////////////////////////////
  
  print_page($log);

  function print_page($log) {
    echo 
    print_html("",
      print_head("",
        print_style()).	   
      print_body("",
        print_center("",
          print_table("border=0",             
            print_title("Attenuator").
	    print_att_current().
            print_report($log)))));
  }

  function print_att_current(){
    return 
    print_actionForm(
      print_input("Current position", "att_par_ndb", "?", "db").
      print_input("",                 "att_par_stp", "?", "step", true).
      print_button(                   "att_act_get",      "Get current").
      print_button(                   "att_act_sdb",      "Set Attenuation"));
  }

?>
