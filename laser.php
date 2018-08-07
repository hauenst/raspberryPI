<?php

  header('P3P: CP="CAO PSA OUR"');
  session_start();
  include "commons.php";

  /////////////////////////////////////////////////////////////////////////////////
  // Processing requests
  /////////////////////////////////////////////////////////////////////////////////

  function execute_command(&$log, $command, $label){
      $log = $log."\nReceived:\n$label\n\n";
      exec("sudo -u pi /var/www/html/runLaser \"$command\" 2>&1", $result);
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
      case "las_act_tim":
        execute_command($log, "GEMT", "Get times");
	break;
      case "las_act_tem":
        execute_command($log, "GMTE", "Get temperatures");
	break;
      case "las_act_tec":
        execute_command($log, "GTCO", "Get temperatures control");
	break;
      case "las_act_err":
        execute_command($log, "GSER", "Get error and info");
	break;
      case "las_act_puu":
        execute_command($log, "GSSD", "Get emiting status");
	break;
      case "las_act_pon":
        execute_command($log, "SSSD 1", "Laser ON");
        execute_command($log, "GSSD", "Get times");
	break;
      case "las_act_pof":
        execute_command($log, "SSSD 0", "Laser OFF");
        execute_command($log, "GSSD", "Get times");
	break;
      case "las_act_all":
        execute_command($log, "GEMT", "Get times");
        execute_command($log, "GMTE", "Get temperatures");
        execute_command($log, "GTCO", "Get temperatures control");
        execute_command($log, "GSER", "Get error and info");
        execute_command($log, "GSSD", "Get emiting status");
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
        if (substr($line, 0, strlen("GEMT ")) === "GEMT ") {
	  $tmp = substr($line, strlen("GEMT "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_sup'] = (int)$tmp[0].":".$tmp[1];
          $_SESSION['las_par_emi'] = (int)$tmp[2].":".$tmp[3];
        }
        if (substr($line, 0, strlen("GMTE ")) === "GMTE ") {
	  $tmp = substr($line, strlen("GMTE "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_dit'] = (float)$tmp[0]/100;
          $_SESSION['las_par_crt'] = (float)$tmp[1]/100;
          $_SESSION['las_par_elt'] = (float)$tmp[2];
          $_SESSION['las_par_het'] = (float)$tmp[3];
        }
        if (substr($line, 0, strlen("GTCO ")) === "GTCO ") {
	  $tmp = substr($line, strlen("GTCO "));
          $tmp = (int)$tmp;
          $_SESSION['las_par_tc1'] = ($tmp == 0 || $tmp == 2)?"OFF":"ON";
          $_SESSION['las_par_tc2'] = ($tmp == 0 || $tmp == 1)?"OFF":"ON";
        }
        if (substr($line, 0, strlen("GSER ")) === "GSER ") {
	  $tmp = substr($line, strlen("GSER "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_er1'] = hexdec($tmp[0]);
          $_SESSION['las_par_er2'] = hexdec($tmp[1]);
          $_SESSION['las_par_er3'] = hexdec($tmp[2]);
          $_SESSION['las_par_er4'] = hexdec($tmp[3]);
          $_SESSION['las_par_er5'] = hexdec($tmp[4]);
          $_SESSION['las_par_er6'] = hexdec($tmp[5]);
        }
        if (substr($line, 0, strlen("GSSD ")) === "GSSD ") {
	  $tmp = substr($line, strlen("GSSD "));
          $tmp = (int)$tmp;
          $_SESSION['las_par_pul'] = ($tmp == 1)?"ON":"OFF";
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
        print_loading().
        print_center("",
          print_table("",             
            print_title("Laser").
	    print_las_emittingTime().
	    print_las_temperatures().
	    print_las_errorStatus().
	    print_las_emittingStatus().
	    print_las_all().
            print_report($log)))));
  }

  function print_las_emittingTime() {
    return
    print_actionForm(
      print_actionFormTitle("Times").
      print_input("Suply",    "las_par_sup", "?", "H:M", true, true).
      print_input("Emitting", "las_par_emi", "?", "H:M", true, true).
      print_button(           "las_act_tim",      "Get current"));
  }

  function print_las_temperatures() {
    return
    print_actionForm(
      print_actionFormTitle("Temperatures").
      print_input("Diode",           "las_par_dit", "?", "C", true).
      print_input("Cristal",         "las_par_crt", "?", "C", true).
      print_input("Electronic sink", "las_par_elt", "?", "C", true).
      print_input("Heat sink",       "las_par_het", "?", "C", true).
      print_button(                  "las_act_tem",      "Get current").
      print_input("Control TEC1",    "las_par_tc1", "?", "", true, true).
      print_input("Control TEC2",    "las_par_tc2", "?", "", true, true).
      print_button(                  "las_act_tec",      "Get current"));
  }

  function print_las_errorStatus() {
    return
    print_actionForm(
      print_actionFormTitle("Error Report").
      print_input("Error Reg. 1",       "las_par_er1", "?", "", true, true, print_tab_err1()).
      print_input("Error Reg. 2",       "las_par_er2", "?", "", true, true).
      print_input("Error Reg. 3",       "las_par_er3", "?", "", true, true).
      print_input("Information Reg. 1", "las_par_er4", "?", "", true, true).
      print_input("Information Reg. 2", "las_par_er5", "?", "", true, true).
      print_input("Information Reg. 3", "las_par_er6", "?", "", true, true).
      print_button(                     "las_act_err",      "Get current"));
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

  function print_las_all() {
    return
    print_actionForm(
      print_button("las_act_all", "Update all"));
  }

  function print_tab_err1() {
    return '
    <table>
      <tr><td>1</td><td>Heatsink or PCB too hot</td></tr>
      <tr><td>2</td><td>+12V DC Voltage too low (<10V)</td></tr>
      <tr><td>3</td><td>Interlock connector removed or interlock contact opened</td></tr>
      <tr><td>4</td><td>Laser head too hot</td></tr>
      <tr><td>5</td><td>Laser diode temperature too low</td></tr>
      <tr><td>6</td><td>Laser diode temperature too high</td></tr>
      <tr><td>7</td><td>Crystal temperature too low</td></tr>
      <tr><td>8</td><td>Cristal temperature too high</td></tr>
    </table>';
  }


?>
