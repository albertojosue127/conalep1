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

$mact = 1;
$matri = array(); $nombres = array(); 
$m1 = array(); $m2 = array(); $m3 = array(); $m4 = array(); $m5 = array(); $m6 = array(); $m7 = array(); $m8 = array(); 
$caden = "0";
$cadena = "%0";
matriculas();
encabezado();
for ($i=0; $i < count($m1); $i++) { 
    $cadena = $cadena . "&" . $matri[$i] . "|" . $nombres[$i] . "|" . $m1[$i] . "|" . $m2[$i] . "|" . $m3[$i] . "|" . $m4[$i] . "|" . $m5[$i] . "|" . $m6[$i] . "|" . $m7[$i] . "|" . $m8[$i];
}
echo utf8_encode($caden);
//echo "<br><br><br>";
echo utf8_encode($cadena);

function encabezado(){
    global $conn;
    global $m1;
    global $caden;
    $tsql = "
        SELECT
            cvemodulo,
            dbo.fndesmodulo(cvemodeloeducativo,cvemodulo) as desmodulo,
            cvegrupo,
            dbo.fndespsp(cvepsp,cveunidadmin) as despsp  
        from grupomodulo 
        where cvemodeloeducativo = 2018 
            and cveunidadmin = 3150790   
            and periodoescolar=11819 
            and calendario='S' 
            and seccion='101-PROI18'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo utf8_encode($row['cvemodulo'] . "|" . $row['desmodulo'] . "|" . $row['cvegrupo'] . "|" . $row['despsp'] . "</br>");
        $caden = $caden . "&" . $row['cvemodulo'] . "|" . $row['desmodulo'] . "|" . $row['cvegrupo'] . "|" . $row['despsp'];
        modulos($row['cvemodulo']);
    }
    sqlsrv_free_stmt($getResults);
}

function matriculas(){
    global $conn;
    global $matri, $nombres;
    $tsql = "SELECT 
                Matricula,
                dbo.fnalumnonombre(matricula) as Alumno 
            from alumnomodulo 
            where cvemodeloeducativo = 2018 
                And cveunidadmin = 3150790   
                and periodoescolar=11819 and calendario='S'   
                and cvegrupo in (select 
                                    cvegrupo 
                                from grupomodulo 
                                where 
                                    cvemodeloeducativo = 2018 
                                    And cveunidadmin = 3150790                       
                                    and periodoescolar=11819 
                                    and calendario='S' 
                                    and seccion='101-PROI18'
                                ) 
            group by matricula order by matricula;";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo utf8_encode($row['Matricula'] . "|" . $row['Alumno'] . "<br>");
        array_push($matri, $row['Matricula']);
        array_push($nombres, $row['Alumno']);
    }
    sqlsrv_free_stmt($getResults);
}

function modulos($e_modulo){
    global $conn;
    global $mact;
    global $m1, $m2, $m3, $m4, $m5, $m6, $m7, $m8;
    $tsql = "SELECT 
                matricula,
                cvemodulo,
                dbo.fnEv_AcumxMdlo(matricula,cveunidadmin,cvemodeloeducativo,1,periodoescolar,calendario,cvemodulo) as [%Acum] 
            from alumnomodulo 
            where 
                cvemodeloeducativo = 2018 
                And cveunidadmin = 3150790   
                and periodoescolar=11819 
                and calendario='S' 
                and cvemodulo='{$e_modulo}'   
                and cvegrupo in (select cvegrupo 
                                from grupomodulo                     
                                where 
                                    cvemodeloeducativo = 2018 
                                    And cveunidadmin = 3150790                       
                                    and periodoescolar=11819 
                                    and calendario='S' 
                                    and seccion='101-PROI18'
                                );";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo utf8_encode($row['matricula'] . "|" . $row['cvemodulo'] . "|" . $row['%Acum'] . "</br>");
        switch ($mact) {
            case 1:
                array_push($m1, $row['%Acum']);
                break;
            case 2:
                array_push($m2, $row['%Acum']);
                break;
            case 3:
                array_push($m3, $row['%Acum']);
                break;
            case 4:
                array_push($m4, $row['%Acum']);
                break;
            case 5:
                array_push($m5, $row['%Acum']);
                break;
            case 6:
                array_push($m6, $row['%Acum']);
                break;
            case 7:
                array_push($m7, $row['%Acum']);
                break;
            case 8:
                array_push($m8, $row['%Acum']);
                break;
            default:
                array_push($m1, $row['%Acum']);
                break;
        }
    }
    $mact++;
    sqlsrv_free_stmt($getResults);
}



sqlsrv_close($conn);

