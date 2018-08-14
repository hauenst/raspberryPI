<?php
  
  /////////////////////////////////////////////////////////////////////////////////
  // Retrieving global session, loading common functions
  /////////////////////////////////////////////////////////////////////////////////

  header('P3P: CP="CAO PSA OUR"');
  session_start();
  include "commons.php";

  /////////////////////////////////////////////////////////////////////////////////
  // Printing page
  /////////////////////////////////////////////////////////////////////////////////

  print_page();

  /////////////////////////////////////////////////////////////////////////////////
  // Functions
  /////////////////////////////////////////////////////////////////////////////////
  
  function print_page() {
    echo 
    print_html("",
      print_head("",
        print_style()
      ).	   
      print_body("",
        print_loading().
        print_center("",
          print_table("border=0",
            print_title("Laser Temperatures")
          ).
	  print_table("boder=0 style=\"border: 1px solid black;\"",
	    print_image("laserTemps_dio_m01.png", "Diode (last hour)")
	  ).
	  print_table("boder=0 style=\"border: 1px solid black;margin-top:-1px;\"",
	    print_image("laserTemps_cry_m01.png", "Crystal (last hour)")
	  ).
	  print_table("boder=0 style=\"border: 1px solid black;margin-top:19px;\"",
	    print_image("laserTemps_sin_m01.png", "Sinks (last hour)")
	  ).
	  print_table("boder=0 style=\"border: 1px solid black;margin-top:-1px;\"",
	    print_image("laserTemps_sin_m12.png", "Sinks (last 12 hours)")
	  )
        )
      )
    );
  }

?>
