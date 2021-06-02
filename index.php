<!DOCTYPE html>
<html class="client-nojs" lang="de">
<head>
<meta charset="UTF-8"/>
<title>Online-Fragebogen</title>
</head>
<body>
<?php

// (C) 2021 by Tjabo Vierbuecher: Licence: GPL 3
// TODO: absichern gegen mehrfaches Speichern
// TODO: die Ergebnisse irgendwo hingießen
// TODO: korreggtes Errorhandling einbauen, also mit Exceptions werfen und diese catchen

session_start();
if (! array_key_exists("formkey", $_SESSION)) {
   $_SESSION["formkey"] = uniqid();
}

if (array_key_exists( "formkey", $_POST))
{
    if ($_POST["formkey"] === $_SESSION["formkey"])
    {
       $foo = $_POST;
       unset( $foo["formkey"] );
       // print "<pre>" . var_export($foo, 1) . "</pre>\n";
       error_log(json_encode( ["surveyresult" => $foo] ));
       print "Vielen Dank für deine Teilnahme!";
    }
    exit();
}

$uid = $_GET[ "uid" ];
if (preg_match( "/^[0-9a-f\-]+$/", $uid))
{
   if (file_exists( $uid . ".yml" ))
   {
      error_log("the file exists");
      $seitennummer = 0;
      $def = yaml_parse_file( $uid . ".yml" );
      if ($def === false) { exit(); } // TODO: exception usw
      print "<form action=\"\" method=\"POST\">\n";
      foreach( $def["seiten"] as $seite )
      {
         $seitennummer++;
         if ($seitennummer > 1) { print "<hr>\n<br>"; }
         print "<h2>" . htmlentities( $seite["titel"], ENT_QUOTES | ENT_IGNORE, "utf-8" ) . "</h2>\n";
         foreach( $seite[ "fragen" ] as $frage )
         {
            if (! array_key_exists( "id", $frage ))
            {
                print "<strong>Fehler: keine ID</strong>\n<br>";
                continue;
            }
            print "<h3>" . htmlentities( $frage["titel"], ENT_QUOTES | ENT_IGNORE, "utf-8" ) . "</h3>\n";
            print "<div class=\"frage\" id=\"" . $frage["id"] . "\">\n";

            if ($frage["typ"] === "jn") {
               render_jn( $frage["id"] );   
            }
            elseif ($frage["typ"] === "jne") {
               render_jne( $frage["id"] );   
            }
            elseif ($frage["typ"] === "schlecht_gut") {
               render_schlecht_gut( $frage["id"], array_key_exists( "variations", $frage ) ? $frage["variations"] : 5 );   
            }
            elseif ($frage["typ"] === "radio") {
               render_radio( $frage["id"], $frage["options"] );
            }
            elseif ($frage["typ"] === "multi") {
               render_multi( $frage["id"], $frage["options"] );
            }
            else {
               print "Unbekannter Typ bei Frage " . $frage["id"] . "\n<br>";
            }
            print "</div>\n";

         }
      }
      print "<input type=\"hidden\" name=\"formkey\" value=\"" . $_SESSION["formkey"] . "\" />\n";
      print "<button type=\"submit\">Absenden</button>\n";
      print "</form>\n";
      // print "<pre>"; var_export( $def ); print "</pre>";
   }
}
else {
  error_log("no match: '$uid");
}

// ------------------------------------------------
function render_jn($id)
{
   //print "Typ_ jn<br>\n";
   print "<select name=\"$id\"><option value=\"0\">Wähle...</option><option value=\"j\">Ja</option><option value=\"n\">Nein</option></select>\n";
}

// ------------------------------------------------
function render_jne($id)
{
   //print "Typ_ jne<br>\n";
   print "<select name=\"$id\"><option value=\"0\">Wähle...</option><option value=\"j\">Ja</option><option value=\"n\">Nein</option><option value=\"e\">Egal</option></select>\n";
}

// ------------------------------------------------
function render_schlecht_gut($id, $variations)
{
   //print "Typ_ schlecht gut mit $variations Variationen<br>\n";
   print "eher schlecht";
   for ($i = 1; $i <= $variations; $i++)
   {
      print "&nbsp;<input type=\"radio\" name=\"$id\" value=\"$i\">\n";
   }
   print "&nbsp; eher gut<br>\n";
}

// ------------------------------------------------
function render_radio( $id, $options )
{
   $num = 0;
   foreach ($options as $option) {
      $num = $num + 1;
      $oid = $id . "-$num";
      print "<input type=\"radio\" name=\"$id\" id=\"$oid\" value=\"$num\">\n";
      print "<label for=\"$oid\">" . htmlentities( $option, ENT_QUOTES | ENT_IGNORE, "utf-8" ) . "</label>\n";
      print "<br>\n";
   }
}

// ------------------------------------------------
function render_multi( $id, $options )
{
   $num = 0;
   foreach ($options as $option) {
      $num = $num + 1;
      $oid = $id . "-$num";
      print "<input type=\"checkbox\" name=\"" . $id . "[]\" id=\"$oid\" value=\"" . urlencode($option) . "\">";
      print "<label for=\"$oid\">" . htmlentities( $option, ENT_QUOTES | ENT_IGNORE, "utf-8" ) . "</label><br>\n";
      print "<br>\n";
   }
}
?>
</body>
</html>

