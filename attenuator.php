<?php
  
  /////////////////////////////////////////////////////////////////////////////////
  // Retrieving global session, loading common functions
  /////////////////////////////////////////////////////////////////////////////////

  header('P3P: CP="CAO PSA OUR"');
  session_start();
  if(!isset($_SESSION['att_par_lst'])) {
    $_SESSION['att_par_lst'] = false;
  }
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
      case "att_act_get":
        execute_command($log, "d", "Attenuator Current Position");
        break;
      case "att_act_sdb":
        execute_command_db($log, 0);
        break;
      case "att_act_m001":
        execute_command_db($log, -0.01);
        break;
      case "att_act_p001":
        execute_command_db($log, +0.01);
        break;
      case "att_act_m010":
        execute_command_db($log, -0.10);
        break;
      case "att_act_p010":
        execute_command_db($log, +0.10);
        break;
      case "att_act_m100":
        execute_command_db($log, -1.00);
        break;
      case "att_act_p100":
        execute_command_db($log, +1.00);
        break;
      case "att_act_sst":
        execute_command_step($log, 0);
	break;
      case "att_act_d001":
        execute_command_step($log, -1);
	break;
      case "att_act_u001":
        execute_command_step($log, +1);
	break;
      case "att_act_d010":
        execute_command_step($log, -10);
	break;
      case "att_act_u010":
        execute_command_step($log, +10);
	break;
      case "att_act_d100":
        execute_command_step($log, -100);
	break;
      case "att_act_u100":
        execute_command_step($log, +100);
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
      exec("sudo -u pi /var/www/html/runAttenuator \"$command\" 2>&1", $result);
      $log = $log."Result:\n";
      for ($i=0; $i<count($result); $i++){
        if (strlen($result[$i]) != 0) {
          $log = $log.$result[$i]."\n";
        }
      }
      $log = $log."\nFinished:\n$label\n";
  }

  function execute_command_db(&$log, $var){
    $atten = $_POST['att_par_ndb'];
    if (!is_numeric($atten)) {
      $log = $log."\nRequested attenuation ($atten) is not a numeric value. Aborting";
    } else {
      $atten += $var;
      if ($atten < 0 || $atten > 40) {
        $log = $log."\nRequested atenuation ($atten) out of range [0, 40]. Aborting";
      } else {
        execute_command($log, "a$atten", "Set step");
        execute_command($log, "d", "Get current position");
      }
    }
  }

  function execute_command_step(&$log, $var){
    $atten = $_POST['att_par_stp'];
    if (!ctype_digit($atten)) {
      $log = $log."\nRequested step ($atten) is not an integer value. Aborting";
    } else {
      $atten += $var;
      if ($atten < 0 || $atten > 8191) {
        $log = $log."\nRequested step ($atten) out of range [0, 8191]. Aborting";
      } else {
        execute_command($log, "s$atten", "Set step");
        execute_command($log, "d", "Get current position");
      }
    }
  }

  function log_scraping($log, &$alert) {
    $log = preg_split("/\r\n|\n|\r/", $log);
    foreach ($log as $line) {
      if ($line == "") {
        continue;
      } else {
        if (trim($line) === "TIMEOUT") {
          $alert = "TIMEOUT received, keeping old values. Please check the log";
	} elseif (substr($line, 0, strlen("dPos:")) === "dPos:") {
          $_SESSION['att_par_stp'] = substr($line, strlen("dPos:"));
	} elseif (substr($line, 0, strlen("a")) === "a") {
          $_SESSION['att_par_lst'] = false;
        } elseif (substr($line, 0, strlen("s")) === "s") {
          $_SESSION['att_par_lst'] = true;
	} elseif (substr($line, 0, strlen("ATTEN:")) === "ATTEN:") {
          if ($_SESSION['att_par_lst']) {
            $_SESSION['att_par_ndb'] = "?";
          } else {
            $_SESSION['att_par_ndb'] = substr($line, strlen("ATTEN:"));
	  }
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
      print_body(($alert != "")?"onload=\"alert('$alert');\"":"",
        print_loading().
        print_center("",
          print_table("border=0",
            print_title("Attenuator").
	    print_att_db().
	    print_att_st().
	    print_att_all()
          )
        ).
	print_report($log)
      )
    );
  }

  function print_att_db(){
    return 
    print_actionForm(
      print_input("Current db", "att_par_ndb", "?", "db", false, false, ($_SESSION['att_par_lst'])?"When the position has been set by \'Set step\', the reported db position is not longer valid until the next time it is set with \'Set db\'":"").
      print_button(             "att_act_sdb",      "Set db").
      print_button(             "att_act_m001", "-0.01", "att_act_p001", "+0.01").
      print_button(             "att_act_m010", "-0.10", "att_act_p010", "+0.10").
      print_button(             "att_act_m100", "-1.00", "att_act_p100", "+1.00"));
  }

  function print_att_st(){
    return 
    print_actionForm(
      print_input("Current Step", "att_par_stp", "?", "step").
      print_button(               "att_act_sst",      "Set Step").
      print_button(               "att_act_d001", "  -1", "att_act_u001", "  +1").
      print_button(               "att_act_d010", " -10", "att_act_u010", " +10").
      print_button(               "att_act_d100", "-100", "att_act_u100", "+100"));
  }

  function print_att_all(){
    return
    print_actionForm(
      print_button("att_act_get", "Update all"));
  }

?>
