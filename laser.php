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
      case "las_act_pon":
        execute_command($log, "sudo -u pi /var/www/html/runLaser 0 0 \"SSSD 1\\r\"", "Laser ON");
	break;
      case "las_act_pof":
        execute_command($log, "sudo -u pi /var/www/html/runLaser 0 0 \"SSSD 0\\r\"", "Laser OFF");
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
        if (substr($line, 0, strlen("exa")) === "exa") {
          $_SESSION['las_par_exa'] = substr($line, strlen("exa"));
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
          print_table("",             
            print_title("Laser").
	    print_las_emittingTime().
	    print_las_temperatures().
	    print_las_errorStatus().
	    print_las_emittingStatus().
            print_report($log)))));
  }

  function print_las_emittingTime() {
    return
    print_actionForm(
      print_actionFormTitle("Times").
      print_input("Suply",    "las_par_sup", "?", "H:M", true).
      print_input("Emitting", "las_par_emi", "?", "H:M", true).
      print_button(           "las_get_tim",      "Get current"));
  }

  function print_las_temperatures() {
    return
    print_actionForm(
      print_actionFormTitle("Temperatures").
      print_input("Diode",           "las_par_dit", "?", "C", true).
      print_input("Cristal",         "las_par_crt", "?", "C", true).
      print_input("Electronic sink", "las_par_elt", "?", "C", true).
      print_input("Heat sink",       "las_par_het", "?", "C", true).
      print_button(                  "las_get_tem",      "Get current").
      print_input("Control TEC1",    "las_par_tc1", "?", "", true, true).
      print_input("Control TEC2",    "las_par_tc2", "?", "", true, true).
      print_button(                  "las_get_tec",      "Get current"));
  }

  function print_las_errorStatus() {
    return
    print_actionForm(
      print_actionFormTitle("Times").
      print_input("Par1",     "las_par_er1", "?", "", true, true).
      print_input("Par1",     "las_par_er2", "?", "", true, true).
      print_input("Par1",     "las_par_er3", "?", "", true, true).
      print_input("Par1",     "las_par_er4", "?", "", true, true).
      print_input("Par1",     "las_par_er5", "?", "", true, true).
      print_input("Par1",     "las_par_er6", "?", "", true, true).
      print_button(           "las_act_err",      "Get current"));
  }

  function print_las_emittingStatus() {
    return
    print_actionForm(
      print_actionFormTitle("Pulsing").
      print_input("Status",   "las_par_pul", "OFF", "", true, true).
      print_button(           "las_act_puu",        "Get current").
      print_button(           "las_act_pon",        "Turn ON").
      print_button(           "las_act_pof",        "Turn OFF"));
  }

?>
