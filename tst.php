<?php
  $provincia= "bilbao";
  $palabra= "tienda+de+pesca+bilbao";
  $dominio= "sokalapesca.com";
  $cadena= $dominio;
$palabra=str_replace(" ","+",$palabra);
  $cadena=str_replace("www.","",$cadena);
  $enlace1= 'https://www.google.es/search?q='.$palabra.'&near='.$provincia.'&num=100';
  
  
  
echo DamePosiciones($enlace1,$dominio);


function DamePosiciones($url,$url_buscada)
{ //codigo Jorge
  //$url_buscada="http://www.podologiahermosilla.es/";
/*
IP: 81.24.171.149///46.226.147.127
USER:seronop10
PASS:y4dxgdr4Yc
PORT http/s:58542
PORT socksv5:8088
*/

  $doc = new DOMDocument();
  libxml_use_internal_errors(true);
  $doc->loadHTMLFile($url);

  $elements = $doc->getElementsByTagName('h3');
  $j=0;
  $resultado= array();;
  $laurl1="";
  if (!is_null($elements))
  {
    foreach ($elements as $element)
    {
      $nodes = $element->getElementsByTagName('a');
      foreach ($nodes as $node)
      {
        if(strpos($node->getAttribute('href'),"&ai")==FALSE)
        {
          $j=$j+1;
          $laurl=$node->getAttribute('href');
          $laurl=substr($laurl,stripos($laurl,"=")+1,stripos($laurl,"&sa=")-7);

          if(strpos($laurl,$url_buscada)!==FALSE){
            $resultado += [ $j => $laurl ];
          }
        }
      }
    }
  }
  if (empty($resultado)) {
    return 0;
  } else {
    return json_encode($resultado);
  }
  
  
}

?>