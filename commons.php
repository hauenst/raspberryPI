<?php

  function print_html($options, $content) {
    return "
    <html $options>
      $content
    </html>";
  }

  function print_head($options, $content) {
    return "
    <head $options>
      $content
    <head>";
  }

  function print_style() {
    return '<link rel="stylesheet" href="style.css">';
  }

  function print_body($options, $content) {
    return "
    <body $options>
      $content
    </body>";
  }

  function print_loading() {
    return '
      <div class="my_overLayer" id="loading">
        <div class="my_overLayerContent">
	  <img src="images/loading.svg"/>
        </div>
      </div>';
  }
  function print_center($options, $content) {
    return "
    <center $options>
      $content
    </center>";
  }

  function print_table($options, $content) {
    return "
    <table $options>
      $content
    </table>";
  }

  function print_title($content) {
    return "
    <tr>
      <td class=\"my_title\">
        $content
      </td>
    </tr>";
  }

  function print_actionFormTitle($content) {
    return "
    <tr>
      <td align=\"center\" colspan=3>
        $content
      </td>
    </tr>";
  }

  function print_report($log) {
    $log = str_replace("\n", "<br>", $log);
    return '
    <tr>
      <td>
	<div class="my_report">'.(($log != "")?$log:"").'  </div>
      </td>
    </tr>';
  }

  function print_actionForm($content){
    return '<tr>
      <td align="center"> 
        <form method="post" onsubmit="document.getElementById(\'loading\').style.visibility=\'visible\';">
	  <table style="border: 1px solid black;">'.
            $content.
         '</table>
	</form>
      </td>
    </tr>';
  }

  function print_image($image, $title){
    return '
    <tr>
      <td class="my_title">
        '.$title.'
      </td>
    </tr>
    <tr>
      <td align="center">
        <img style="cursor: pointer;" src="images/'.$image.'" onclick="document.getElementById(\'loading\').style.visibility=\'visible\';this.src=\'images/'.$image.'\'+\'?\'+new Date().getTime();function delay() {document.getElementById(\'loading\').style.visibility=\'hidden\';}; setTimeout(delay,200);">
      </td>
    </tr>';
  }

  function print_input($label, $name, $default, $unit, $disabled=false, $center=false, $alert=""){
    $default = session($name, $default);
    return '              
    <tr>
      <td class="my_label">
        '.$label.'
      </td>
      <td>
        <input class="my_input'.((!strcmp($default, "OFF"))?" my_OFF":((!strcmp($default, "ON"))?" my_ON":((!strcmp($default, "?"))?" my_WARN":""))).(($center)?' center':'').'" type="text" name="'.$name.'" value="'.$default.'"'.(($disabled)?" readonly":"").'>
      </td>
      <td class="my_unit'.(($alert!="")?" my_info":"").'"'.(($alert!="")?" onclick=\"alert('$alert');\"":"").'>'.
        (($unit != "")?"[$unit]":"").
      '</td>
    </tr>';
  }

  function print_button($action, $text, $action2="", $text2="") {
    if ($action2=="") {
      return'
      <tr>
        <td class="my_label">
        </td>
        <td>
          <button class="my_button" type="submit" name="req" value="'.$action.'">'.$text.'</button>
        </td>
        <td class="my_unit">
        </td>
      </tr>';
    } else {
      return'
      <tr>
        <td class="my_label">
        </td>
        <td>
          <button class="my_buttonHalf" type="submit" name="req" value="'.$action.'">'.$text.'</button>
          <button class="my_buttonHalf" type="submit" name="req" value="'.$action2.'">'.$text2.'</button>
        </td>
        <td class="my_unit">
        </td>
      </tr>';
    }
  }

  function session($att, $def) {
    if (isset($_SESSION[$att])) {
      return $_SESSION[$att];
    } else {
      return $def;
    }
  }
?>
