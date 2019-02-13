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
//*************************CONDICIONES SI EXISTEN VARIABLES**************************************
//******************INICIO******************
if (isset($_GET["psp_login"])) {
    psp_login();
}
if (isset($_GET["tec_login"])) {
    tec_login();
}
//******************DESCRIPCIONES******************
if (isset($_GET["descip_cveunidadmin"])) {
    descip_cveunidadmin();
}
if (isset($_GET["descip_cveplanestudio"])) {
    descip_cveplanestudio();
}
if (isset($_GET["descip_cvemodulo"])) {
    descip_cvemodulo();
}
//******************DATOS******************
if (isset($_GET["dat_cveentidad"])) {
    l_cveunidadmin();
}
if (isset($_GET["dat_cvemodeloeducativo"])) {
    l_cveplanestudio();
}
if (isset($_GET["dat_cveplanestudio"])) {
    l_cvegrupo();
}
if (isset($_GET["dat_cvegrupo"])) {
    l_matricula();
}
if (isset($_GET["dat_unidadmin_rm"])) {
    l_cvegrupo_rm();
}
//******************************************DESCRIPCIONES********************************************
function psp_login(){
    /*global $conn;
    $tsql = "SELECT
                contrasenia
            from psp 
            where cvepsp = '{$_GET["psp_login"]}'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        if($row['contrasenia'] !== ""){
            psp_datos();
        }else{
            echo $cadena;
        }
    }
    sqlsrv_free_stmt($getResults);
    */
    psp_datos();
}
function psp_datos(){
    global $conn;
    $tsql = "SELECT
                cveunidadmin,
                dbo.fndescunidadmin(cveunidadmin) as plantel,
                cvemodeloeducativo,
                cveplanestudio,
                dbo.fndesplanestudio(cvemodeloeducativo, cveplanestudio) as carrera
            from grupomodulo 
            where 
                 periodoescolar=11819 
                and calendario='S' 
                and cvepsp = '{$_GET["psp_login"]}'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $cadena . "&" . $row['cveunidadmin'] . "|" . $row['plantel'] . $row['cvemodeloeducativo'] . "|" . $row['cveplanestudio'] . $row['carrera'];
        //$cadena = $cadena . "&" . $row['cveunidadmin'] . "|" . $row['plantel'] . $row['cvemodeloeducativo'] . "|" . $row['cveplanestudio'] . $row['carrera'] . "|" . $row['cvemodulo'] . "|" . $row['desmodulo'] . "|" . $row['seccion'] . "|" . $row['cvepsp'] . "|" . $row['despsp'];
    } 
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function tec_login(){
    global $conn;
    $tsql = "SELECT
                cveunidadmin,
                descorta
            from ctunidadmin 
            where cveunidadmin = '{$_GET["tec_login"]}'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        echo $row['cveunidadmin'] . "|" . $row['descorta'];
    }
    sqlsrv_free_stmt($getResults);
}
function descip_cveunidadmin(){
    global $conn;
    $cadena = "0";
    $tsql = "SELECT descorta FROM ctunidadmin WHERE cveunidadmin = {$_GET["descip_cveunidadmin"]}";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $row['descorta'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function descip_cveplanestudio(){
    global $conn;
    $cadena = "0";
    $tsql = "SELECT descripcion FROM ctplanestudio WHERE cveplanestudio = '{$_GET["descip_cveplanestudio"]}'";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $row['descripcion'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function descip_cvemodulo(){
    global $conn;
    $cadena = "0";
    $tsql = "SELECT descripcion FROM ctmodulo WHERE cvemodulo = '{$_GET["descip_cvemodulo"]}'";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $row['descripcion'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function descrip_matricula(){
    global $conn;
    $cadena = "0";
    $tsql = "SELECT CONCAT(nombre, ' ',apprimer, ' ',apsegundo) nombre from alumno where matricula = '{$_GET["dat_matricula"]}'";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $row['nombre'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}

//******************************************FUNCIONES********************************************
function l_cveunidadmin(){
    global $conn;
    $tsql = "SELECT cveunidadmin, descorta FROM ctunidadmin WHERE cveentidad = {$_GET["dat_cveentidad"]} and tipounidadmin = 1";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $siesplantel = substr($row['cveunidadmin'], 0, 1);
        if ($siesplantel != "1") {
            $cadena = $cadena . "&" . $row['cveunidadmin'] . "|" . $row['descorta'];
        }
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function l_cveplanestudio(){
    global $conn;
    $tsql = "SELECT a.cveplanestudio, a.descorta
            FROM ctplanestudio a
            INNER JOIN ctunidadminplanestudio b
                ON a.cveplanestudio = b.cveplanestudio
            WHERE b.cvemodeloeducativo = {$_GET["dat_cvemodeloeducativo"]} 
                AND b.cveunidadmin = {$_GET["cveunidadmin"]}";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $cadena . "&" . $row['cveplanestudio'] . "|" . $row['descorta'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function l_cvegrupo(){
    global $conn;
    $tsql = "SELECT
                distinct(seccion)
            from grupomodulo 
            where cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                and cveunidadmin = {$_GET["cveunidadmin"]}   
                and periodoescolar={$_GET["periodoescolar"]} 
                and cveplanestudio='{$_GET["dat_cveplanestudio"]}'
                and calendario = 'S'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $cadena . "|" . $row['seccion'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function l_matricula(){
    global $conn;
    $tsql = "SELECT 
                Matricula,
                dbo.fnalumnonombre(matricula) as Alumno 
            from alumnomodulo 
            where cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                and cveunidadmin = {$_GET["cveunidadmin"]}   
                and periodoescolar={$_GET["periodoescolar"]}
                and calendario = 'S' 
                and cvegrupo in (select 
                                    cvegrupo 
                                from grupomodulo 
                                where 
                                    cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                                    and cveunidadmin = {$_GET["cveunidadmin"]}   
                                    and periodoescolar={$_GET["periodoescolar"]} 
                                    and seccion='{$_GET["dat_cvegrupo"]}'
                                    and calendario = 'S'
                                ) 
            group by matricula order by matricula;";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $cadena . "|" . $row['Matricula'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
function l_cvegrupo_rm(){
    global $conn;
    $tsql = "SELECT
                distinct(seccion)
            from grupomodulo 
            where cvemodeloeducativo = {$_GET["cvemodeloeducativo"]} 
                and cveunidadmin = {$_GET["dat_unidadmin_rm"]}   
                and periodoescolar={$_GET["periodoescolar"]}
                and calendario = 'S'";
    $cadena = "0";
    $getResults= sqlsrv_query($conn, $tsql);
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $cadena = $cadena . "|" . $row['seccion'];
    }
    echo utf8_encode($cadena);
    sqlsrv_free_stmt($getResults);
}
/*****************PARA ERRORES**********************
    if( $getResults === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
    }
*/
sqlsrv_close($conn);

