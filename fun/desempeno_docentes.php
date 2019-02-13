<?php
header("Access-Control-Allow-Origin: *");
//******************************************CONEXION******************************************
$serverName = '10.10.12.137';
$connectionOptions = array(
    'Database' => 'sae_productivo',
    'Uid' => 'sa',
    'PWD' => 'conalep2018*'
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if(!$conn)
    echo 'NO Connected!';

//******************************************INICIO******************************************

$caden = "0";
encabezado();
echo $caden;
/*echo "##################################";
$ddate = "10/09/18";
$date = new DateTime($ddate);
$week = $date->format("W");
echo "Weeknummer: $week";*/

function encabezado(){
    global $conn;
    global $caden;
    $tsql = "
        SELECT 
            --fechaactsis,
            convert(varchar, fechaactsis, 1) as 'fechas'
        from alumnoevaluacion                     
        where 
            cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
            and cveunidadmin = {$_GET["cveunidadmin"]}
            and periodoescolar={$_GET["periodoescolar"]} 
            and calendario='S' 
            and cveplanestudio = '{$_GET["cveplanestudio"]}'
            and cvemodulo = '{$_GET["cvemodulo"]}'
            and idpersonal='{$_GET["dat_cvepsp"]}'
        group by convert(varchar, fechaactsis, 1)";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $date = new DateTime($row['fechas']);
        $week = $date->format("W");
        //echo ($row['fechas'] . "</br>");
        $caden = $caden . "&" . $row['fechas'] . "|" . ($week - 32);
    }
    sqlsrv_free_stmt($getResults);
}

sqlsrv_close($conn);