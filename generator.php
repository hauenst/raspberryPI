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
    $log = "= Execution ===================<br>";
    $log = $log."<br>Request: ${_POST['req']}";
    $log = $log."<br><br>= Log =========================<br>";
    $request = $_POST["req"];
    switch ($request) {
      case "gen_ch1_act_get":
	      execute_command($log, "C1:BSWV WVTP,PULSE", "Set generator wave");
        execute_command($log, "C1:OUTPut?",         "Generator STATUS");
        execute_command($log, "C1:BSWV?",           "Generator wave information");
	      break;
      case "gen_ch1_act_def":
	      $_SESSION['gen_ch1_par_wav'] = "PULSE";
      	$_SESSION['gen_ch1_par_amp'] = 5.0;
	      $_SESSION['gen_ch1_par_ofs'] = 2.5;
      	$_SESSION['gen_ch1_par_frq'] = 1000;
      	$_SESSION['gen_ch1_par_dut'] = 10;
      	$_SESSION['gen_ch1_par_red'] = 20;
        break;
      case "gen_ch1_act_set":
        if (isset($_POST['gen_ch1_par_wav']) &&
            isset($_POST['gen_ch1_par_amp']) &&
            isset($_POST['gen_ch1_par_ofs']) &&
            isset($_POST['gen_ch1_par_frq']) &&
            isset($_POST['gen_ch1_par_dut']) &&
            isset($_POST['gen_ch1_par_red'])) {
          $wav = $_POST['gen_ch1_par_wav'];
          $amp = $_POST['gen_ch1_par_amp'];
          $ofs = $_POST['gen_ch1_par_ofs'];
          $frq = $_POST['gen_ch1_par_frq'];
          $dut = $_POST['gen_ch1_par_dut'];
          $red = $_POST['gen_ch1_par_red'];
	        if (is_numeric($amp) &&
              is_numeric($ofs) &&
              is_numeric($frq) &&
              is_numeric($dut) &&
	            is_numeric($red)) {
	          execute_command($log, "C1:BSWV WVTP,".$wav, "Set generator wave");
	          execute_command($log, "C1:BSWV AMP,".$amp."V", "Set generator amplitude");
	          execute_command($log, "C1:BSWV OFST,".$ofs."V", "Set generator offset");
	          execute_command($log, "C1:BSWV FRQ,".$frq."HZ", "Set generator frequency");
	          execute_command($log, "C1:BSWV DUTY,".$dut."%", "Set generator duty");
	          execute_command($log, "C1:BSWV RISE,".(((float)$red)/1000000000)."S", "Set generator rise");
	          execute_command($log, "C1:BSWV FALL,".(((float)$red)/1000000000)."S", "Set generator fall");
          } else {
            $log = $log."\nUnexpected not numeric input. Aborting";
	        }
	      } else {
          $log = $log."\nUnexpected missing parameter";
	      }
        execute_command($log, "C1:OUTPut?",  "Generator STATUS");
        execute_command($log, "C1:BSWV?",    "Generator wave information");
	      break;
      case "gen_ch1_act_onn":
        execute_command($log, "C1:OUTP ON",  "Generator ON");
        execute_command($log, "C1:OUTPut?",  "Generator STATUS");
        execute_command($log, "C1:BSWV?",    "Generator wave information");
	      break;
      case "gen_ch1_act_off":
        execute_command($log, "C1:OUTP OFF", "Generator OFF");
        execute_command($log, "C1:OUTPut?",  "Generator STATUS");
        execute_command($log, "C1:BSWV?",    "Generator wave information");
	      break;
      case "gen_ch2_act_get":
      	execute_command($log, "C2:BSWV WVTP,PULSE", "Set generator wave");
        execute_command($log, "C2:OUTPut?",         "Generator STATUS");
        execute_command($log, "C2:BSWV?",           "Generator wave information");
	      break;
      case "gen_ch2_act_def":
        $_SESSION['gen_ch2_par_wav'] = "PULSE";
        $_SESSION['gen_ch2_par_amp'] = 5.0;
        $_SESSION['gen_ch2_par_ofs'] = 2.5;
        $_SESSION['gen_ch2_par_frq'] = 1000;
        $_SESSION['gen_ch2_par_dut'] = 10;
        $_SESSION['gen_ch2_par_red'] = 20;
        break;
      case "gen_ch2_act_set":
        if (isset($_POST['gen_ch2_par_wav']) &&
            isset($_POST['gen_ch2_par_amp']) &&
            isset($_POST['gen_ch2_par_ofs']) &&
            isset($_POST['gen_ch2_par_frq']) &&
            isset($_POST['gen_ch2_par_dut']) &&
            isset($_POST['gen_ch2_par_red'])) {
          $wav = $_POST['gen_ch2_par_wav'];
          $amp = $_POST['gen_ch2_par_amp'];
          $ofs = $_POST['gen_ch2_par_ofs'];
          $frq = $_POST['gen_ch2_par_frq'];
          $dut = $_POST['gen_ch2_par_dut'];
          $red = $_POST['gen_ch2_par_red'];
          if (is_numeric($amp) &&
                    is_numeric($ofs) &&
                    is_numeric($frq) &&
                    is_numeric($dut) &&
              is_numeric($red)) {
            execute_command($log, "C2:BSWV WVTP,".$wav, "Set generator wave");
            execute_command($log, "C2:BSWV AMP,".$amp."V", "Set generator amplitude");
            execute_command($log, "C2:BSWV OFST,".$ofs."V", "Set generator offset");
            execute_command($log, "C2:BSWV FRQ,".$frq."HZ", "Set generator frequency");
            execute_command($log, "C2:BSWV DUTY,".$dut."%", "Set generator duty");
            execute_command($log, "C2:BSWV RISE,".(((float)$red)/1000000000)."S", "Set generator rise");
            execute_command($log, "C2:BSWV FALL,".(((float)$red)/1000000000)."S", "Set generator fall");
                } else {
                  $log = $log."\nUnexpected not numeric input. Aborting";
          }
        } else {
                $log = $log."\nUnexpected missing parameter";
        }
        execute_command($log, "C2:OUTPut?",  "Generator STATUS");
        execute_command($log, "C2:BSWV?",    "Generator wave information");
	      break;
      case "gen_ch2_act_onn":
        execute_command($log, "C2:OUTP ON",  "Generator ON");
        execute_command($log, "C2:OUTPut?",  "Generator STATUS");
        execute_command($log, "C2:BSWV?",    "Generator wave information");
      	break;
      case "gen_ch2_act_off":
        execute_command($log, "C2:OUTP OFF", "Generator OFF");
        execute_command($log, "C2:OUTPut?",  "Generator STATUS");
        execute_command($log, "C2:BSWV?",    "Generator wave information");
	      break;
      case "gen_act_all":
	      execute_command($log, "C1:BSWV WVTP,PULSE", "Set generator wave");
        execute_command($log, "C1:OUTPut?",         "Generator STATUS");
        execute_command($log, "C1:BSWV?",           "Generator wave information");
	      execute_command($log, "C2:BSWV WVTP,PULSE", "Set generator wave");
        execute_command($log, "C2:OUTPut?",         "Generator STATUS");
        execute_command($log, "C2:BSWV?",           "Generator wave information");
      	break;
      default:
        $log = $log."<br>Non recognized request received";
    }
    log_scraping($log, $alert);
  }

  /////////////////////////////////////////////////////////////////////////////////
  // Printing page
  /////////////////////////////////////////////////////////////////////////////////
  
  print_page($log, $alert);

  /////////////////////////////////////////////////////////////////////////////////
  // Printing page
  /////////////////////////////////////////////////////////////////////////////////

  function execute_command(&$log, $command, $label){
    $log = $log."\nReceived:\n$label\n";
    exec("sudo -u pi /var/www/html/runGenerator \"192.168.42.11\" \"$command\" 2>&1", $result);
    $log = $log."\nResult:";
    for ($i=0; $i<count($result); $i++){
      if (strlen($result[$i]) != 0) {
        $log = $log."\n".$result[$i];
      }
    }
    $log = $log."\n\nFinished:\n$label\n";
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
        } elseif (substr($line, 0, strlen("C1:OUTP")) === "C1:OUTP") {
          $tmp = substr($line, strlen("C1:OUTP"));
          $tmp = explode(",", $tmp);
          $_SESSION['gen_ch1_par_sta'] = trim($tmp[0]);
        } elseif (substr($line, 0, strlen("C1:BSWV")) === "C1:BSWV") {
          $tmp = substr($line, strlen("C1:BSWV"));
          $tmp = explode(",", $tmp);
          $_SESSION['gen_ch1_par_wav'] =        $tmp[1];
          $_SESSION['gen_ch1_par_amp'] = substr($tmp[7] , 0, -1);
          $_SESSION['gen_ch1_par_ofs'] = substr($tmp[11], 0, -1);
          $_SESSION['gen_ch1_par_frq'] = substr($tmp[3] , 0, -2);
          $_SESSION['gen_ch1_par_dut'] = ((float)$tmp[17]);
          $_SESSION['gen_ch1_par_red'] = ((float)substr($tmp[21], 0, -1))*1000000000;
        } elseif (substr($line, 0, strlen("C2:OUTP")) === "C2:OUTP") {
          $tmp = substr($line, strlen("C2:OUTP"));
          $tmp = explode(",", $tmp);
          $_SESSION['gen_ch2_par_sta'] = trim($tmp[0]);
        } elseif (substr($line, 0, strlen("C2:BSWV")) === "C2:BSWV") {
          $tmp = substr($line, strlen("C2:BSWV"));
          $tmp = explode(",", $tmp);
          $_SESSION['gen_ch2_par_wav'] =        $tmp[1];
          $_SESSION['gen_ch2_par_amp'] = substr($tmp[7] , 0, -1);
          $_SESSION['gen_ch2_par_ofs'] = substr($tmp[11], 0, -1);
          $_SESSION['gen_ch2_par_frq'] = substr($tmp[3] , 0, -2);
          $_SESSION['gen_ch2_par_dut'] = ((float)$tmp[17])*1000;
          $_SESSION['gen_ch2_par_red'] = ((float)substr($tmp[21], 0, -1))*1000000000;
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
            print_title("Signal Generator").
            print_gen_ch1_current().
            print_gen_ch2_current().
            print_gen_all()
          )
        ).
	print_report($log)
      )
    );
  }

  function print_gen_ch1_current(){
    return 
      print_actionForm(
        print_actionFormTitle("Channel 1").
        print_input("Wave",         "gen_ch1_par_wav", "?", "", true, true).
        print_input("Amplitude",    "gen_ch1_par_amp", "?", "V").
        print_input("Offset",       "gen_ch1_par_ofs", "?", "V").
        print_input("Frequency",    "gen_ch1_par_frq", "?", "Hz").
        print_input("Duty",         "gen_ch1_par_dut", "?", "%").
        print_input("Raise/Fall",   "gen_ch1_par_red", "?", "ns").
        print_input("Signal",       "gen_ch1_par_sta", "?", "", true, true).
        print_button(               "gen_ch1_act_get",      "Get current").
        print_button(               "gen_ch1_act_def",      "Load default").
        print_button(               "gen_ch1_act_set",      "Set parameters").
        print_button(               "gen_ch1_act_onn",      "Turn ON").
        print_button(               "gen_ch1_act_off",      "Turn OFF"));
  }

  function print_gen_ch2_current(){
    return 
      print_actionForm(
	print_actionFormTitle("Channel 2").
        print_input("Wave",         "gen_ch2_par_wav", "?", "", true, true).
        print_input("Amplitude",    "gen_ch2_par_amp", "?", "V").
        print_input("Offset",       "gen_ch2_par_ofs", "?", "V").
        print_input("Frequency",    "gen_ch2_par_frq", "?", "Hz").
        print_input("Duty",         "gen_ch2_par_dut", "?", "%").
        print_input("Raise/Fall",   "gen_ch2_par_red", "?", "ns").
        print_input("Signal",       "gen_ch2_par_sta", "?", "", true, true).
        print_button(               "gen_ch2_act_get",      "Get current").
        print_button(               "gen_ch2_act_def",      "Load default").
        print_button(               "gen_ch2_act_set",      "Set parameters").
        print_button(               "gen_ch2_act_onn",      "Turn ON").
        print_button(               "gen_ch2_act_off",      "Turn OFF"));
  }

  function print_gen_all() {
    return
    print_actionForm(
      print_button("gen_act_all", "Update all"));
  }

?>
