<?php

  /////////////////////////////////////////////////////////////////////////////////
  // Retrieving global session, loading common functions
  /////////////////////////////////////////////////////////////////////////////////

  header('P3P: CP="CAO PSA OUR"');
  session_start();
  include "commons.php";

  /////////////////////////////////////////////////////////////////////////////////
  // Processing requests
  /////////////////////////////////////////////////////////////////////////////////

  $log = "";
  $alert = "";
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
    log_scraping($log, $alert);
  }

  /////////////////////////////////////////////////////////////////////////////////
  // Printing page
  /////////////////////////////////////////////////////////////////////////////////
  
  print_page($log, $alert);

  /////////////////////////////////////////////////////////////////////////////////
  // Functions
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

  function log_scraping($log, &$alert) {
    $log = preg_split("/\r\n|\n|\r/", $log);
    $alert = "";
    foreach ($log as $line) {
      if ($line == "") {
        continue;
      } else {
        if (trim($line) === "TIMEOUT") {
          $alert = "TIMEOUT received, keeping old values. Please check the log";
	} elseif (substr($line, 0, strlen("GEMT ")) === "GEMT ") {
	  $tmp = substr($line, strlen("GEMT "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_sup'] = (int)$tmp[0].":".$tmp[1];
          $_SESSION['las_par_emi'] = (int)$tmp[2].":".$tmp[3];
        } elseif (substr($line, 0, strlen("GMTE ")) === "GMTE ") {
	  $tmp = substr($line, strlen("GMTE "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_dit'] = (float)$tmp[0]/100;
          $_SESSION['las_par_crt'] = (float)$tmp[1]/100;
          $_SESSION['las_par_elt'] = (float)$tmp[2];
          $_SESSION['las_par_het'] = (float)$tmp[3];
        } elseif (substr($line, 0, strlen("GTCO ")) === "GTCO ") {
	  $tmp = substr($line, strlen("GTCO "));
          $tmp = (int)$tmp;
          $_SESSION['las_par_tc1'] = ($tmp == 0 || $tmp == 2)?"OFF":"ON";
          $_SESSION['las_par_tc2'] = ($tmp == 0 || $tmp == 1)?"OFF":"ON";
        } elseif (substr($line, 0, strlen("GSER ")) === "GSER ") {
	  $tmp = substr($line, strlen("GSER "));
          $tmp = explode(" ", $tmp);
          $_SESSION['las_par_er1'] = "0x".$tmp[0];
          $_SESSION['las_par_er2'] = "0x".$tmp[1];
          $_SESSION['las_par_er3'] = "0x".$tmp[2];
          $_SESSION['las_par_er4'] = "0x".$tmp[3];
          $_SESSION['las_par_er5'] = "0x".$tmp[4];
          $_SESSION['las_par_er6'] = "0x".$tmp[5];
        } elseif (substr($line, 0, strlen("GSSD ")) === "GSSD ") {
	  $tmp = substr($line, strlen("GSSD "));
          $tmp = (int)$tmp;
          $_SESSION['las_par_pul'] = ($tmp == 1)?"ON":"OFF";
        }
      }
    }
  }

  function print_page($log, $alert) {
    echo 
    print_html("",
      print_head("",
        print_style()
      ).	   
      print_body(($alert != "")?"onLoad=\"alert('$alert');\"":"",
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
      print_input("Error Reg. 1",       "las_par_er1", "?", " ? ", true, true, print_tab_err1()).
      print_input("Error Reg. 2",       "las_par_er2", "?", " ? ", true, true, print_tab_err2()).
      print_input("Error Reg. 3",       "las_par_er3", "?", " ? ", true, true, print_tab_err3()).
      print_input("Information Reg. 1", "las_par_er4", "?", " ? ", true, true, print_tab_inf1()).
      print_input("Information Reg. 2", "las_par_er5", "?", " ? ", true, true, print_tab_inf2()).
      print_input("Information Reg. 3", "las_par_er6", "?", " ? ", true, true, print_tab_inf3()).
      print_button(                     "las_act_err",      "Get current"));
  }

  function print_las_emittingStatus() {
    return
    print_actionForm(
      print_actionFormTitle("Pulsing").
      print_input("Status",   "las_par_pul", "?", "", true, true).
      print_button(           "las_act_puu",      "Get current").
      print_button(           "las_act_pon",      "Turn ON").
      print_button(           "las_act_pof",      "Turn OFF"));
  }

  function print_las_all() {
    return
    print_actionForm(
      print_button("las_act_all", "Update all"));
  }

  function print_tab_err1() {
    if(!isset($_SESSION['las_par_er1'])) {
      return "";
    }
    $err1    = array();
    $err1[1] = "Heatsink or PCB too hot";
    $err1[2] = "+12V DC Voltage too low (<10V)";
    $err1[3] = "Interlock connector removed or interlock contact opened";
    $err1[4] = "Laser head too hot";
    $err1[5] = "Laser diode temperature too low";
    $err1[6] = "Laser diode temperature too high";
    $err1[7] = "Crystal temperature too low";
    $err1[8] = "Cristal temperature too high";
    $errt = hexdec(substr($_SESSION['las_par_er1'], 2));
    $toReturn = "";
    for ($i=1; $i<9; $i++) {
      if ($errt & (0x1 << ($i-1))) {
        $toReturn = $toReturn.'\nE'.$i.'\t'.$err1[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No error bits reported";
    }
    return $toReturn;
  }

  function print_tab_err2() {
    if(!isset($_SESSION['las_par_er2'])) {
      return "";
    }
    $err2     = array();
    $err2[9]  = "TEC cooler overload for 1 minute";
    $err2[10] = "Wrong data read from the laser head";
    $err2[11] = "Diode temperature out of boundaries";
    $err2[12] = "+12 DC voltage too high (>14V)";
    $err2[13] = "TEC diode open circuit";
    $err2[14] = "TEC diode short circuit";
    $err2[15] = "TEC crystal open circuit";
    $err2[16] = "TEC crystal short circuit";
    $errt = hexdec(substr($_SESSION['las_par_er2'], 2));
    $toReturn = "";
    for ($i=9; $i<17; $i++) {
      if ($errt & (0x1 << ($i-9))) {
        $toReturn = $toReturn.'\nE'.$i.'\t'.$err1[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No error bits reported";
    }
    return $toReturn;
  }

  function print_tab_err3() { 
    if(!isset($_SESSION['las_par_er3'])) {
      return "";
    }
    $err3     = array();
    $err3[17] = "Laser diode open circuit";
    $err3[18] = "Laser diode short circuit";
    $err3[19] = "Failure on emission lamp";
    $err3[20] = "Crossed laser head cables";
    $err3[22] = "Wrong hardware configuration:\\nOption laser head with no option driver or reverse";
    $err3[23] = "Communication error with laser head";
    $err3[24] = "Crystal temperature out of boundaries";
    $errt = hexdec(substr($_SESSION['las_par_er2'], 2));
    $toReturn = "";
    for ($i=17; $i<25; $i++) {
      if ($errt & (0x1 << ($i-17))) {
        $toReturn = $toReturn.'\nE'.$i.'\t'.$err3[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No information bits reported";
    }
    return $toReturn;
  }

  function print_tab_inf1() {
    if(!isset($_SESSION['las_par_er4'])) {
      return "";
    }
    $inf1    = array();
    $inf1[1] = "N/A";
    $inf1[2] = "Diode current not within +-50mA to the spec";
    $inf1[3] = "Diode temperature is OK";
    $inf1[4] = "Crystal temperature is OK";
    $inf1[5] = "Diode temperature higher than setting: TEC is cooling down";
    $inf1[6] = "Diode temperature lower than setting: TEC is heating up";
    $inf1[7] = "Crystal temperature higher than setting: TEC is cooling down";
    $inf1[8] = "Crystal temperature lower than setting: TEC is heating up";
    $inft = hexdec(substr($_SESSION['las_par_er4'], 2));
    $toReturn = "";
    for ($i=1; $i<9; $i++) {
      if ($inft & (0x1 << ($i-1))) {
        $toReturn = $toReturn.'\nI'.$i.'\t'.$inf1[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No information bits reported";
    }
    return $toReturn;
  }

  function print_tab_inf2(){
    if(!isset($_SESSION['las_par_er5'])) {
      return "";
    }
    $inf2    = array();
    $inf2[9] = "Laser diode current is ON";
    $inf2[10] = "Laser diode emission stoped due to an error";
    $inf2[11] = "Temperature regulation is OK - laser is ready for emission";
    $inf2[12] = "Changing settings is authorized";
    $inf2[13] = "Laser in autostart mode";
    $inf2[14] = "Laser in internal mode";
    $inf2[15] = "Laser in free running mode";
    $inf2[16] = "Laser has two TECs";
    $inft = hexdec(substr($_SESSION['las_par_er5'], 2));
    $toReturn = "";
    for ($i=9; $i<17; $i++) {
      if ($inft & (0x1 << ($i-9))) {
        $toReturn = $toReturn.'\nI'.$i.'\t'.$inf2[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No information bits reported";
    }
    return $toReturn;
  }

  function print_tab_inf3(){
    if(!isset($_SESSION['las_par_er6'])) {
      return "";
    }
    $inf3    = array();
    $inf3[17] = "TEC diode is operating";
    $inf3[18] = "TEC crystal is operating";
    $inf3[19] = "Diode temperature regulation is activated";
    $inf3[20] = "Crystal temperature regulation is activated";
    $inf3[21] = "N/A";
    $inf3[22] = "N/A";
    $inf3[23] = "N/A";
    $inf3[24] = "N/A";
    $inft = hexdec(substr($_SESSION['las_par_er6'], 2));
    $toReturn = "";
    for ($i=17; $i<25; $i++) {
      if ($inft & (0x1 << ($i-17))) {
        $toReturn = $toReturn.'\nI'.$i.'\t'.$inf3[$i] ;
      }
    }
    if (strlen($toReturn) == 0) {
      $toReturn = "No information bits reported";
    }
    return $toReturn;
  }

?>
