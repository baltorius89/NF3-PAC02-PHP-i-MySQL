<?php
$db = mysqli_connect('localhost', 'root') or 
    die ('Unable to connect. Check your connection parameters.');
mysqli_select_db($db,'animesite') or die(mysqli_error($db));
// select the movie titles and their genre after 1990
$query = 'select anime_id, anime_name, anime_year, anime_type, animetype_label, caracter_fullname
from anime, animetype, caracter
where (anime_type=animetype_id) and (anime_director=caracter_id)';

$result = mysqli_query($db, $query) or die(mysqli_error($db));

// show the results
while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    echo $anime_id . ' - ' . $anime_name . ' - ' . $anime_year . ' - ' . $anime_type . ' - ' . $animetype_label . ' - ' . $caracter_fullname . '</br>';
}
?>

<?php
$noRegistros = 1; //Registros por página
$pagina = 1; //Por defecto pagina = 1
if($_GET['pagina'])
    $pagina = $_GET['pagina']; //Si hay pagina, lo asigna
$buskr=$_GET['searchs']; //Palabra a buscar
//Utilizo el comando LIMIT para seleccionar un rango de registros
$sSQL = "SELECT * FROM anime WHERE anime_name LIKE '%$buskr%' LIMIT " . ($pagina - 1) * $noRegistros . ",$noRegistros";
$result = mysqli_query($db,$sSQL) or die(mysqli_error($db));
//Exploracion de registros
echo "<table>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td height=80 align=center>$row[anime_id]<br>";
    echo "</td><td align=center>$row[anime_name]</td>";
    echo "</td><td align=center>$row[anime_year]</td>";
    echo "</tr>";
}
//Imprimiendo paginacion
$sSQL = "SELECT count(*) FROM anime WHERE anime_name LIKE '%$buskr%'";

//Cuento el total de registros
$result = mysqli_query($db,$sSQL);
$row = mysqli_fetch_array($result);
$totalRegistros = $row["count(*)"]; //Almaceno el total

$noPaginas = $totalRegistros/$noRegistros; //Determino la cantidad de paginas
?>
<tr>
    <td colspan="2" align="center"><?php echo "<strong>Total registros: </strong>".$totalRegistros.";" ?></td>
    <td colspan="2" align="center"><?php echo "<strong>Pagina: </strong>".$pagina.";" ?></td>
</tr>
    <tr bgcolor="f3f4f1">
    <td colspan="4" align="right"><strong>Pagina:
<?php
for($i=1; $i<$noPaginas+1; $i++) { //Imprimo las paginas
if($i == $pagina)
echo "<font color=red>".$i."</font>"; //No link
else
echo "<a href=\"?pagina=".$i."&searchs=".$buskr."\" style=color:#000;> ".$i."</a>";
}
?>