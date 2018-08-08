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
        print_style()
      ).	   
      print_body("",
        print_loading().
        print_center("",
          print_table("",             
            print_title("Laser").
	    print_las_emittingTime().
	    print_las_temperatures().
	    print_las_errorStatus().
	    print_las_emittingStatus().
	    print_las_all()
          )
        ).
	print_report($log)
      )
    );
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
      print_input("Error Reg. 1",       "las_par_er1", "?", "info", true, true, print_tab_err1()).
      print_input("Error Reg. 2",       "las_par_er2", "?", "info", true, true, print_tab_err2()).
      print_input("Error Reg. 3",       "las_par_er3", "?", "info", true, true, print_tab_err3()).
      print_input("Information Reg. 1", "las_par_er4", "?", "info", true, true, print_tab_inf1()).
      print_input("Information Reg. 2", "las_par_er5", "?", "info", true, true, print_tab_inf2()).
      print_input("Information Reg. 3", "las_par_er6", "?", "info", true, true, print_tab_inf3()).
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
    return '0\tNo error\n1\tHeatsink or PCB too hot\n2\t+12V DC Voltage too low (<10V)\n3\tInterlock connector removed or interlock contact opened\n4\tLaser head too hot\n5\tLaser diode temperature too low\n6\tLaser diode temperature too high\n7\tCrystal temperature too low\n8\tCristal temperature too high';
  }

  function print_tab_err2() {
    return '9\tTEC cooler overload for 1 minute\n10\tWrong data read from the laser head\n11\tDiode temperature out of boundaries\n12\t+12 DC voltage too high (>14V)\n13\tTEC diode open circuit\n14\tTEC diode short circuit\n15\tTEC crystal open circuit\n16\tTEC crystal short circuit';
  }

  function print_tab_err3() { 
    return '17\tLaser diode open circuit\n18\tLaser diode short circuit\n19\tFailure on emission lamp\n20\tCrossed laser head cables\n22\tWrong hardware configuration:\n\tOption laser head with no option driver or reverse\n23\tCommunication error with laser head\n24\tCrystal temperature out of boundaries';
  }

  function print_tab_inf1(){
    return '1\tN/A\n2\tDiode current not within +-50mA to the spec\n3\tDiode temperature is OK\n4\tCrystal temperature is OK\n5\tDiode temperature higher than setting: TEC is cooling down\n6\tDiode temperature lower than setting: TEC is heating up\n7\tCrystal temperature higher than setting: TEC is cooling down\n8\tCrystal temperature lower than setting: TEC is heating up';
  }

  function print_tab_inf2(){
    return '9\tLaser diode current is ON\n10\tLaser diode emission stoped due to an error\n11\tTemperature regulation is OK - laser is ready for emission\n12\tChanging settings is authorized\n13\tLaser in autostart mode\n14\tLaser in internal mode\n15\tLaser in free running mode\n16\tLaser has two TECs';
  }

  function print_tab_inf3(){
    return '17\tTEC diode is operating\n18\tTEC crystal is operating\n19\tDiode temperature regulation is activated\n20\tCrystal temperature regulation is activated\n21\tN/A\n22\tN/A\n23\tN/A\n24\tN/A';
  }

?>
