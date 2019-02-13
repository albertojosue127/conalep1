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

//echo count($matri) . "<br>";
//ESTO ES POR QUE EN UN GRUPO HAY ALUMNOS QUE LLEVAN MAS MATERIAS QUE OTROS Y AUNQUE PERTENECEN A ESE GRUPO NO TOMAN ESA CLASE Y NO LES ASIGNAN CALIFICACION... ENTONCES EN MIS ARRAY DE "$mn" QUEDADN UNOS ARRAY MAS CORTOS QUE OTROS Y AL MOMENTO DE MOSTRARLOS MANDA ERROR DE OFFSET.
//ENTONCES PARA COMPENSAR LOS ARRAYS CORTOS LE AGREGO MAS VALORES... ESTO NO AFECTA POR QUE SOLO AGO EL --FOR-- DE RECORRIDO HASTA EL TOTAL DE MATRICULAS Y LOS QUE SOBRAN LOS IGNORA.
$totala = count($matri);
if (count($m1) < $totala) {
    array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); 
    array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); array_push($m1, "n/c"); 
}
if (count($m2) < $totala) {
    array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); 
    array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); array_push($m2, "n/c"); 
}
if (count($m3) < $totala) {
    array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); 
    array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); array_push($m3, "n/c"); 
}
if (count($m4) < $totala) {
    array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); 
    array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); array_push($m4, "n/c"); 
}
if (count($m5) < $totala) {
    array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); 
    array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); array_push($m5, "n/c"); 
}
if (count($m6) < $totala) {
    array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); 
    array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); array_push($m6, "n/c"); 
}
if (count($m7) < $totala) {
    array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); 
    array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); array_push($m7, "n/c"); 
}
if (count($m8) < $totala) {
    array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); 
    array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); array_push($m8, "n/c"); 
}
/*
echo count($m1) . "<br>";
echo count($m2) . "<br>";
echo count($m3) . "<br>";
echo count($m4) . "<br>";
echo count($m5) . "<br>";
echo count($m6) . "<br>";
echo count($m7) . "<br>";
echo count($m8) . "<br>";
*/
$llenavacio;
for ($i=0; $i < count($matri); $i++) { 
    $cadena = $cadena . "&" . $matri[$i] . "|" . $nombres[$i] . "|" . $m1[$i] . "|" . $m2[$i] . "|" . $m3[$i] . "|" . $m4[$i] . "|" . $m5[$i] . "|" . $m6[$i] . "|" . $m7[$i] . "|" . $m8[$i];
}
echo ($caden);
//echo "<br><br><br>";
echo ($cadena);

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
        where cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
            and cveunidadmin = {$_GET["cveunidadmin"]}   
            and periodoescolar={$_GET["periodoescolar"]} 
            and seccion='{$_GET["cvegrupo"]}'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo ($row['cvemodulo'] . "|" . $row['desmodulo'] . "|" . $row['cvegrupo'] . "|" . $row['despsp'] . "</br>");
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
            where cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                And cveunidadmin = {$_GET["cveunidadmin"]}   
                and periodoescolar={$_GET["periodoescolar"]} 
                and matricula='{$_GET["dat_matricula"]}'
                and cvegrupo in (select 
                                    cvegrupo 
                                from grupomodulo 
                                where 
                                    cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                                    And cveunidadmin = {$_GET["cveunidadmin"]}                       
                                    and periodoescolar={$_GET["periodoescolar"]}  
                                    and seccion='{$_GET["cvegrupo"]}'
                                    and matricula='{$_GET["dat_matricula"]}'
                                ) 
            group by matricula order by matricula;";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo ($row['Matricula'] . "|" . $row['Alumno'] . "<br>");
        array_push($matri, $row['Matricula']);
        array_push($nombres, $row['Alumno']);
    }
    sqlsrv_free_stmt($getResults);
}

function modulos($e_modulo){
    global $conn;
    global $mact;
    global $m1, $m2, $m3, $m4, $m5, $m6, $m7, $m8;
    $acum = "n/c";
    $tsql = "SELECT 
                matricula,
                cvemodulo,
                dbo.fnEv_AcumxMdlo(matricula,cveunidadmin,cvemodeloeducativo,1,periodoescolar,calendario,cvemodulo) as [%Acum] 
            from alumnomodulo 
            where 
                cvemodeloeducativo = {$_GET["cvemodeloeducativo"]}
                And cveunidadmin = {$_GET["cveunidadmin"]}
                and periodoescolar={$_GET["periodoescolar"]}  
                and cvemodulo='{$e_modulo}'
                and matricula='{$_GET["dat_matricula"]}'
                and cvegrupo in (select cvegrupo 
                                from grupomodulo                     
                                where 
                                    cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                                    And cveunidadmin = {$_GET["cveunidadmin"]}                       
                                    and periodoescolar={$_GET["periodoescolar"]}  
                                    and seccion='{$_GET["cvegrupo"]}'
                                    and matricula='{$_GET["dat_matricula"]}'
                                );";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        //echo ($row['matricula'] . "|" . $row['cvemodulo'] . "|" . $row['%Acum'] . "</br>");
        if ($row['%Acum'] == "") {
            $acum = "n/c";
        }else{
            $acum = $row['%Acum'];
        }
        switch ($mact) {
            case 1:
                array_push($m1, $acum);
                break;
            case 2:
                array_push($m2, $acum);
                break;
            case 3:
                array_push($m3, $acum);
                break;
            case 4:
                array_push($m4, $acum);
                break;
            case 5:
                array_push($m5, $acum);
                break;
            case 6:
                array_push($m6, $acum);
                break;
            case 7:
                array_push($m7, $acum);
                break;
            case 8:
                array_push($m8, $acum);
                break;
            default:
                array_push($m1, $acum);
                break;
        }
    }
    $mact++;
    sqlsrv_free_stmt($getResults);
}



sqlsrv_close($conn);