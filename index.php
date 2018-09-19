<!DOCTYPE html>
<html lang="es">
<head>
  <title>Posición de palabras</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="jquery.tablesorter.js"></script>
  <script>
    $("#resultado").html("");
function realizaProceso(id,provincia,numResult,palabra,dominio,pagina){
        var parametros = {"provincia" :provincia,"numResult" : numResult,"palabra" : palabra,"dominio" : dominio,"pagina" : pagina
        };
        console.log(id+" "+provincia+" "+numResult+" "+palabra+" "+dominio+" "+pagina);
        $.ajax({
          data:  parametros,url:   'buscaPosiciones.php',type:  'post',
                beforeSend: function () {
                    $("#resultado").append("<tr id='linea"+id+"'>");
                    $("#linea"+id).addClass("table-warning");
                    $("#linea"+id).html("<td>"+id+"</td><td>Procesando "+palabra+", espere por favor...</td><td><img src='img/831.gif'></td><td></td>");
                    $("#resultado").append("</tr>");
                },
                success:  function (response) {
                  $("#linea"+id).removeClass("table-warning");
                  var enlace;

                    switch (provincia) {
                       case 'españa':
                         enlace= 'https://www.google.es/search?q='+palabra+'&num=100';
                       break;
                       
                       case 'global':
                         enlace= 'https://www.google.com/search?q='+palabra+'&num=100';
                       break;
                    
                       case 'reino-unido':
                         enlace= 'https://www.google.co.uk/search?q='+palabra+'&num=100';
                       break;
                    
                       case 'frances':
                         enlace= 'https://www.google.fr/search?q='+palabra+'&num=100';
                       break;
                    
                       case 'aleman':
                         enlace= 'https://www.google.de/search?q='+palabra+'&num=100';
                       break;
                    
                       default:
                         enlace= 'https://www.google.es/search?q='+palabra+'&near='+provincia+'&num=100';
                         break;
                    }
                   
                    enlace =enlace.replace(/ /gi, "+");
                    if(response==0){
                      console.log(response);
                        $("#linea"+id).addClass("table-danger");
                        $("#linea"+id).html("<td>"+id+"</td><td><a href='"+enlace+"' target='_blank'>"+palabra+"</a> &nbsp &nbsp<a href='"+enlace+"&start=100'>2</td><td><INPUT type=\"text\" name=\""+id+"\" value=\"100\" /></td><td></td>");
                    }else{
                      console.log(response);
                      try { 
                        var arrayresponse = JSON.parse(response);
                        console.log(arrayresponse);
                        console.log(Object.keys(arrayresponse).length);

                        if (Object.keys(arrayresponse).length==1) {
                          Object.keys(arrayresponse).forEach(function (key) {
                            var value = arrayresponse[key];
                            console.log(key+value);
                          $("#linea"+id).html("<td>"+id+"</td><td><a href='"+enlace+"' target='_blank'>"+palabra+"</a></td><td><INPUT type=\"text\" name=\""+id+"\" value=\""+key+"\" /></td><td><a href='"+value+"' target='_blank'>"+value+"</a></td>");
                          });
                        }else{
                           idDominio=0;
                          var html = '<td>'+id+'</td><td><div id="accordion">';
                          html += '<div class="card">';
                          html += '<div class="card-header">';
                          html += '<a class="card-link" data-toggle="collapse" data-parent="#accordion" href="#'+id+'-'+idDominio+'">';
                          html += '<div class="row">';
                          html += '<div class="col-sm-9">'+palabra+'</div>';
                          html += '<div class="col-sm-3"><b>+</b></div>';
                          html += '</div>';
                          html += '</a>';
                          html += '</div>';
                          html += '<div id="'+id+'-'+idDominio+'" class="collapse">';
                          html += '<div class="card-body">';
                          html += '<table class="table table-hover">';
                          html += '<tbody>';
                          Object.keys(arrayresponse).forEach(function (key) {
                            var value = arrayresponse[key];
                            console.log(key+value);
                            html += '<tr>';
                            html += '<td></td>';
                            html += '<td><a href="'+value+'" target="_blank">'+value+'</a></td>';
                            html += '<td><spam style="color: #94D487 !important; font-size: 32px;">'+key+'</spam></td>';
                            html += '</tr>';
                          });
                          html += '</tbody>';
                          html += '</table>';
                          html += '</div>';
                          html += '</div>';
                          html += '</div>';
                          html += '</div></td>';
                          $("#linea"+id).html($(html)); 
                          idDominio++;      
                        }
                      } catch (e) { 
                        $("#linea"+id).addClass("table-danger");
                        $("#linea"+id).html("<td>"+id+"</td><td><a href='"+enlace+"' target='_blank'>"+palabra+"</a> &nbsp &nbsp<a href='"+enlace+"&start=100'>2</td><td><INPUT type=\"text\" name=\""+id+"\" value=\"100\" /></td><td></td>"); 
                      }
                     
                    }
                    $("#resultado").append("</tr>");
                    $('#mi-tabla').tablesorter(); 
                }
        });     
}
function cogePalabras(){
  var  palabras = $("#palabras").val();
  var  provincia = $("#provincia").val();
  var  dominio = $("#dominio").val();
  var numResult =50;
  var pagina =0;



palabras =palabras.replace(/\n/gi, ",");
palabras =palabras.replace(/ , /gi, ",");
palabras =palabras.replace(/ ,/gi, ",");
palabras =palabras.replace(/, /gi, ",");
  var arraypalabras= palabras.split(",");
 $("#resultado").html("");
    arraypalabras.forEach(function (elemento, indice, array) {
      if (elemento.indexOf(" ")==0) {
        elemento =elemento.replace(" ", "");
        console.log(elemento);
      }
        realizaProceso(indice,provincia,numResult,elemento,dominio,pagina);  
    });
}

  

</script>
<?php
$cadena="";
$url="sokalapesca.com";
$pclave="tienda de pesca bilbao,cañas de pescar";

?>
</head>
<body>

<div class="container">
  <h2>Estado de Dominios</h2>
  <div class="form-group">
  	<label for="palabras">Introduce el Palabras:</label>
    <textarea class="form-control" rows="7" id="palabras"><?php echo $pclave; ?></textarea>  
    <label for="provincia">Selecciona lugar:</label>
    <select class="form-control" id="provincia">
      <option value="españa">España</option>
<option value="global">Global</option>
<option value="reino-unido">Reino Unido</option>
<option value="frances">Frances</option>
<option value="aleman">Alemán</option>
      <option value="alava">Álava</option>
<option value="albacete">Albacete</option>
<option value="alicante">Alicante/Alacant</option>
<option value="almeria">Almería</option>
<option value="asturias">Asturias</option>
<option value="avila">Ávila</option>
<option value="badajoz">Badajoz</option>
<option value="barcelona">Barcelona</option>
<option value="bilbao">Bilbao</option>
<option value="burgos">Burgos</option>
<option value="caceres">Cáceres</option>
<option value="cadiz">Cádiz</option>
<option value="cantabria">Cantabria</option>
<option value="castellon">Castellón/Castelló</option>
<option value="ceuta">Ceuta</option>
<option value="ciudad real">Ciudad Real</option>
<option value="cordoba">Córdoba</option>
<option value="cuenca">Cuenca</option>
<option value="girona">Girona</option>
<option value="las palmas">Las Palmas</option>
<option value="granada">Granada</option>
<option value="guadalajara">Guadalajara</option>
<option value="guipuzcoa">Guipúzcoa</option>
<option value="huelva">Huelva</option>
<option value="huesca">Huesca</option>
<option value="illesbalears">Illes Balears</option>
<option value="jaen">Jaén</option>
<option value="coruña">A Coruña</option>
<option value="rioja">La Rioja</option>
<option value="leon">León</option>
<option value="lleida">Lleida</option>
<option value="lugo">Lugo</option>
<option value="madrid">Madrid</option>
<option value="malaga">Málaga</option>
<option value="melilla">Melilla</option>
<option value="murcia">Murcia</option>
<option value="navarra">Navarra</option>
<option value="ourense">Ourense</option>
<option value="palencia">Palencia</option>
<option value="pontevedra">Pontevedra</option>
<option value="salamanca">Salamanca</option>
<option value="san+sebastian">San Sebastian</option>
<option value="segovia">Segovia</option>
<option value="sevilla">Sevilla</option>
<option value="soria">Soria</option>
<option value="tarragona">Tarragona</option>
<option value="tenerife">Santa Cruz de Tenerife</option>
<option value="teruel">Teruel</option>
<option value="toledo">Toledo</option>
<option value="valencia">Valencia/Valéncia</option>
<option value="valladolid">Valladolid</option>
<option value="vizcaya">Vizcaya</option>
<option value="vitoria">Vitoria</option>
<option value="zamora">Zamora</option>
<option value="zaragoza">Zaragoza</option>
    </select>
    <label for="dominio">Introduce Dominio:</label>
    <input type="text" class="form-control" id="dominio" value="<?php echo $url; ?>">
    <button type="button" class="btn btn-primary btn-lg"  href="javascript:;" onclick="cogePalabras();" value="Comprobar">Comprobar Posiciones</button>
	</div>
</div>
<div id ="errores"></div>
<div>
	<form name="form" action="https://www.snsmarketing.es/CRM/modificar_informe.php?id=<?php echo $url; ?>" method= "post" enctype="multipart/form-data">
    <table class="table" id="mi-tabla">
      <thead>
        <tr>
          <th>Número</th>
          <th>Palabra</th>
          <th>Posición</th>
          <th>Url Posicionada</th>
        </tr>
      </thead>
      <tbody id="resultado">
      </tbody>
    </table>

    <INPUT type="submit" value="Modificar"></p>
  </form>
</div>
</body>
</html>