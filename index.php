
<?php
  session_start();
  if (isset($_POST['mai_upd'])) {
    session_destroy();
    session_id(uniqid());
    session_start();     
  }
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  </head>
  </body>
    <div class="my_mainDiv">
      <iframe id="laser" src="laser.php" class="my_iframe"></iframe><iframe id="laserPlots" src="laserPlots.php" class="my_iframe"></iframe><iframe id="generator" src="generator.php" class="my_iframe"></iframe><iframe id="attenuator" src="attenuator.php" class="my_iframe"></iframe>
    </div>
    <center>
      <form method="POST">
        <button action="submit" name="mai_upd">Restart Session</button>
      </form>
    </center>
  </body>
</html>

