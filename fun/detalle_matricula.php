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

$tsql = "
SELECT
    modul.cvemodulo
    , dbo.fndesModulo(modul.cvemodeloeducativo, modul.cvemodulo) as desmodulo
    , alue.cveUE
    , alue.cveRA
    , alue.cveAE
    , ade.porcentaje '%ActividadEvaluacion'
    , alue.cveIndicador
    , indicador.porcentaje '%Indicador'
    , case alue.cvepondera
        when 3 then 'E'
        when 2 then 'S'
        when 1 then 'I'
        else 'n/c'
    end calificacionindicador
from
    alumnoevaluacion alue
    inner join ctEvAE ade on alue.cveUE = ade.cveUE
        and alue.cveRA = ade.cveRA
        and alue.cveAE = ade.cveAE
        and alue.cvemodeloeducativo = ade.cvemodeloeducativo
        and alue.cvemodulo = ade.cvemodulo
        and alue.cvemodoacredito = ade.cvemodoacredito
    inner join ctevindicador indicador on alue.cveUE = indicador.cveUE
        and alue.cveRA = indicador.cveRA
        and alue.cveAE = indicador.cveAE
        and alue.cvemodeloeducativo = indicador.cvemodeloeducativo
        and alue.cvemodulo = indicador.cvemodulo
        and alue.cvemodoacredito = indicador.cvemodoacredito
        and alue.cveIndicador = indicador.cveindicador
    inner join alumnomodulo modul on alue.cvemodulo = modul.cvemodulo
        and alue.cvemodeloeducativo = modul.cvemodeloeducativo
        and alue.cveunidadmin = modul.cveunidadmin
        and alue.cvemodoacredito = modul.cvemodoacredito
        and alue.cveplanestudio = modul.cveplanestudio
        and alue.periodoescolar = modul.periodoescolar
        and alue.calendario = modul.calendario
        and alue.matricula = modul.matricula
where
    alue.cveunidadmin = 3302520
    and alue.cvemodeloeducativo = 2018
    and alue.periodoescolar = 11819
    and alue.cveplanestudio = 'EMEC18'
    and alue.matricula = '182520012-2'
    ";
$cadena = "0";
$getResults= sqlsrv_query($conn, $tsql);
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    $cadena = $cadena . "&" . $row['cvemodulo'] . "|" . $row['desmodulo'] . "|" . $row['cveUE'] . "|" . $row['cveRA'] . "|" . $row['cveAE'] . "|" . $row['%ActividadEvaluacion'] . "|" . $row['cveIndicador'] . "|" . $row['%Indicador']  . "|" . $row['calificacionindicador'];
}
echo ($cadena);
sqlsrv_free_stmt($getResults);

sqlsrv_close($conn);