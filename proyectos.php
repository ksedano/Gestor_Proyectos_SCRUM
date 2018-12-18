<?php
include('config.php');
include('session.php');
$userDetails=$userClass->userDetails($session_uid);
$projectDeveloper=$userClass->projectDeveloper($session_uid);
$nombre_usuario_proyecto=$userDetails->name;
$userRol=$userDetails->rol;
?>
<!DOCTYPE html>
<html>
<head>
	<!--<link  href="http://fonts.googleapis.com/css? family=Reenie+Beanie:regular" rel="stylesheet" type="text/css">-->
	<link rel="stylesheet" type="text/css" href="css/codigo.css">
	<script type="text/javascript" defer src="botonMas.js"></script>
	<link rel="stylesheet" href="css/materialize.min.css">
	<meta charset="utf-8">
	<title></title>


</head>
<body>
	<script src="js/jquery.js"></script>
	<script src="js/materialize.min.js"></script>
<div>	

	<div id="error_proyecto"><h3>ERRORES</h3></div>

	<nav class="blue-grey darken-4">
		<div class="nav-wrapper">
			<a href="" class="brand-logo"><?php echo $v1= $_GET['nom']; ?></a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li id="nombre_usuario"><h3 id="nombeUsuario" style="color:white"><?php echo $userDetails->name; ?></h3></li>
				<li id="icono_logout"><a href="<?php echo BASE_URL; ?>logout.php"><img src="css/images/salida.png"></a></li>
			</ul>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col m12">
				<div class="card-panel blue-grey lighten-4">
					<?php 
					$pdo=getDB();
					$sql="SELECT * from project WHERE cod_project IN (SELECT cod_project FROM proj_users WHERE name_proj='".$v1."' AND username IN (SELECT username FROM users WHERE name='".$nombre_usuario_proyecto."'))";
					foreach ($pdo->query($sql) as $row) {
			        //echo $row['id_project'] . "\t";
			        //echo $row['cod_project'] . "\t";
					$codProject=$row['cod_project'];
			        echo "<b>Nombre de Proyecto: </b>",$row['name_project'] . "\t<br>";
			        echo "<b>Descripción: </b>",$row['description'] . "\t<br>";
			        echo "<b>Product Owner: </b>",$row['product_owner'] . "\t<br>";
			        echo "<b>Scrum Master: </b>",$row['scrum_master'] . "\t<br>";
			        echo "<b>Grupo de desarrollo: </b>",$row['group_name'] . "\t<br>";
			        echo "<b>Fecha de inicio: </b>",$row['date_start'] . "\t<br>";
			        echo "<b>Fecha de finalización: </b>",$row['date_finish'] . "\t<br>";
			        //echo $row['comments'] . "\t";
			    	}
					?>
				</div>
			</div>

			
			<div class="col m12">
				<div class="card-panel blue-grey lighten-4">
					<?php 
					$contar=0;
					$pdo=getDB();
					$sql="SELECT * from sprints WHERE cod_project IN (SELECT cod_project FROM proj_users WHERE name_proj='".$v1."' AND username IN (SELECT username FROM users WHERE name='".$nombre_usuario_proyecto."'))";

					$array=array();

					foreach ($pdo->query($sql) as $row) {
				    
					echo "<button class='acordeon' style='background-color:#4f986c;color: #fff; cursor: pointer;  padding: 18px; width:100%; text-align: left; border: 1px solid white; transition: 0.4s;font: 20px Lato, sans-serif;'>".$row['name_sprint']."</button>";
					echo "<div class='panel' style='padding: 0.18px;background-color: white;display: none;overflow: hidden;'>";
					echo	"<p style='font: 16px Lato, sans-serif;'>";
					echo "<b>",$row['name_sprint'] . "\t</b><br>";
					echo "Fecha: ",$row['date_start'] . "\t - ";
					echo $row['date_finish'] . "\t";
					echo "<b style='float:right'>";
					echo "Horas totales: ",$row['total_hours'] . "\t <br>";
					echo "Horas restantes: ",$row['hours_left'] . "\t";
					echo "</b>";
					echo '<br><br>';
					echo "</p>";

					$fechaInicio=strtotime($row['date_start']);
					$fechaFin=strtotime($row['date_finish']);
					$fechaHoy=strtotime(date('Y-m-d'));
					
					$js_array = json_encode($fechaInicio);
					array_push($array, $js_array);
					$js_array = json_encode($fechaFin);
					array_push($array, $js_array);
					$js_array = json_encode($fechaHoy);
					array_push($array, $js_array);

					$contar=$contar+1;

					$sql1="SELECT * from specifications WHERE number_sprint=".$contar." AND cod_project IN (SELECT cod_project FROM proj_users WHERE name_proj='".$v1."' AND username IN (SELECT username FROM users WHERE name='".$nombre_usuario_proyecto."'))";
					foreach ($pdo->query($sql1) as $row1) {
			        echo "<b><font size='4'>",$row1['name_specification'] . "\t</font></b><br>";
			        echo $row1['description'] . "\t";
			        echo "<b style='float:right'>";
			        echo $row1['hours'] . " hours\t <br>";
			        echo $row1['date'] . "\t ";
			        echo "</b>";
			        echo '<br><br>';
			   		 }	
			   		 echo"</div>";			
					}
					?>
					
						</div>

				</div>

			</div>


			

			<div id="ultimoDiv" class="col m12">
				<div id="divEspe" class="card-panel blue-grey lighten-4">
					
					<?php 
					$pdo=getDB();
					$sql="SELECT * from specifications WHERE cod_project IN (SELECT cod_project FROM proj_users WHERE name_proj='".$v1."' AND username IN (SELECT username FROM users WHERE name='".$nombre_usuario_proyecto."'))";
					foreach ($pdo->query($sql) as $row) {
					echo "<div onclick='cambiarColor(this)' id='mover_div' style='margin-bottom: 10px;border: solid yellowgreen;'>";
			        echo "<b><font size='4'>",$row['name_specification'] . "\t</font></b><br>";
			        echo $row['description'] . "\t";
			        echo "<b style='float:right'>";
			        echo $row['hours'] . " hours\t <br>";
			        echo $row['date'] . "\t ";
			        echo "</b>";
			        echo '<br><br>';
			       	echo '</div>';
			    	}

					?>
			
				</div>
			</div>
		</div>
	</div>

</div>

</body>
</html>
<script type="text/javascript">var typeUser = '<?php echo $userRol ?>' </script>
<script type="text/javascript">
		var acc= document.getElementsByClassName('acordeon');

		for(i=0; i< acc.length; i++){
			acc[i].addEventListener("click",function(){
				this.classList.toggle("active");

				var panel =this.nextElementSibling;
				if (panel.style.display==="block"){
					panel.style.display="none";
				}else{
					panel.style.display="block";
				}
			});

		}

		x=0
		for(i=0; i<=acc.length; i+=3){
			var arrayFechas = '<?php echo $array ?>';

			console.log(arrayFechas);

			if(arrayFechas[i]>arrayFechas[i+2]){
				acc[x].style.backgroundColor='black';
			}
			x+=1;
		}

</script>