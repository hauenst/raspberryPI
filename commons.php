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
        <form method="post">
	  <table style="border: 1px solid black;">'.
            $content.
         '</table>
	</form>
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
        <input class="my_input'.(($center)?' center':'').'" type="text" name="'.$name.'" value="'.$default.'"'.(($disabled)?" readonly":"").(($alert!="")?" onclick=\"alert('$alert');\"":"").'>
      </td>
      <td class="my_unit">'.
        (($unit != "")?"[$unit]":"").
      '</td>
    </tr>';
  }

  function print_button($action, $text){
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
  }

  function session($att, $def) {
    if (isset($_SESSION[$att])) {
      return $_SESSION[$att];
    } else {
      return $def;
    }
  }
?>
