<?php

function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
      return mb_convert_encoding($content, 'UTF-8',
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}


//$url = 'http://pl.trans.eu/index.rst?ctrfrom=88-PL&ctrto=88-PL&filter_sel_1=1&trwg=0-1,5';
$url = 'gielda.html';
//$url = 'rekord.html';
$nl = '';
$sapi_type = php_sapi_name();

if (substr($sapi_type, 0, 3) == 'cli')
{
    $nl = "\n";
}
else
{
    $nl = '<br/>';
}
libxml_use_internal_errors(true);
$dom = new DOMDocument;
//$dom->loadHTMLFile($url);
$raw = file_get_contents($url);
$raw = str_replace('&nbsp;', ' ', $raw);
$dom->loadHTML($raw);
$s = simplexml_import_dom($dom);

$result = $s->xpath("//tr[@class='rst_gieldaresult_cargo  high low']");

$i = 0;

$cena = 0;
$skad = '';
$dokad = '';
$waznosc = '';
$wymagania = '';
//echo "<table border=1>";
//echo "<tr><th>Kwota</th><th>Skąd</th><th>Dokąd</th><th>Wymagania</th><th>Ważność</th></tr>";
while (isset($result[$i]))
{    
    $cena =  utf8_decode(trim(str_replace('PLN', '', $result[$i]->td[1]->div[1]->span->span)));
    $skad = utf8_decode(trim(preg_replace('/\s\s+/', ' ', $result[$i]->td[2])));
    $dokad = utf8_decode(trim(preg_replace('/\s\s+/', ' ', $result[$i]->td[3])));
    $wymagania = utf8_decode(trim(preg_replace('/\s\s+/', ' ', $result[$i]->td[4]->b[0] . ' ' . $result[$i]->td[4]->b[1] . ' ' . $result[$i]->td[4])));
    $waznosc = utf8_decode(trim(preg_replace('/\s\s+/', ' ', $result[$i]->td[6]->b[0] . ' ' . $result[$i]->td[6])));
    echo "$cena|$skad|$dokad|$wymagania|$waznosc $nl";
    //echo "<tr><td>$cena</td><td>$skad</td><td>$dokad</td><td>$wymagania</td><td>$waznosc</td></tr>";
    $i++;
}
//echo "</table>";

?>