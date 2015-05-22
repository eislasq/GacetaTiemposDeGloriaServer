<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Trayectos extends PDO {

    var $sucursalesColumnsEditables = array(
        'nombre'
        , 'telefonos'
        , 'celulares'
        , 'emails'
        , 'calle'
        , 'numero_exterior'
        , 'numero_interior'
        , 'entre_calles'
        , 'localidad'
        , 'municipio'
        , 'estado'
        , 'codigo_postal'
        , 'otras_referencias'
    );
    var $negociosColumnsEditables = array('categoria_id'
        , 'nombre'
        , 'duenio'
        , 'descripcion'
        , 'logo'
        , 'banner');

    function __construct() {
        $dsn = 'mysql:host=localhost;dbname=u944467267_gacet';
//        $dsn = 'mysql:unix_socket=/cloudsql/resolute-oxygen-95315:gaceta;dbname=Negocios';
        $username = 'root';
        $passwd = 'toor';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        parent::__construct($dsn, $username, $passwd, $options);
    }

    function miNegocio($request) {
        $statement = $this->prepare("SELECT n.* 
            FROM negocios n
            RIGHT JOIN llaves ll
            ON n.negocio_id=ll.negocio_id
            WHERE ll.llave=:llave");
        $statement->bindParam(':llave', $request->llave);
        $statement->execute();
        $negocio = $statement->fetchObject();
        if ($negocio) {
            $statementSucursales = $this->prepare("SELECT * 
                FROM sucursales
                WHERE negocio_id=:negocio_id");
            $statementSucursales->bindParam(':negocio_id', $negocio->negocio_id);
            $statementSucursales->execute();
            $sucursales = $statementSucursales->fetchAll(PDO::FETCH_ASSOC);
            $negocio->sucursales = $sucursales;
            return $negocio;
        }
        return false;
    }

    function guardarMiNegocio($request) {
        //Consultar la llave
        $statement = $this->prepare("SELECT * 
            FROM llaves
            WHERE llave=:llave");
        $statement->bindParam(':llave', $request->llave);
        $statement->execute();
        $llave = $statement->fetchObject();
        // si el negocio ya existe y pertenece a esta llave, se actualiza
        if ($request->negocio->negocio_id && $llave->negocio_id == $request->negocio->negocio_id) {
            echo "Actualizar negocio \n";
            return $this->actualizarNegocio($request->negocio);
            //si la llave esta desocupada y es un nuevo negocio se inserta
        } elseif ($llave->negocio_id == NULL && !$request->negocio->negocio_id) {
            echo "nuevo negocio \n";
            return $this->nuevoNegocio($llave->llave, $request->negocio);
        } else {
            return 'Cuidado con lo que haces!';
        }
    }

    function actualizarNegocio($negocio) {
        $keysToUpdate = array_intersect($this->negociosColumnsEditables, array_keys(get_object_vars($negocio)));
        $setPlaceholders = array("fecha_actualizacion=CURRENT_TIMESTAMP");
        foreach ($keysToUpdate as $keyToUpdate) {
            $setPlaceholders[] = "$keyToUpdate=:$keyToUpdate";
        }
        $setPlaceholdersStr = implode(', ', $setPlaceholders);
        $statementUpdate = $this->prepare("UPDATE negocios
            SET $setPlaceholdersStr
            WHERE negocio_id=:negocio_id");
        foreach ($keysToUpdate as $keyToUpdate) {
            $statementUpdate->bindParam(":$keyToUpdate", $negocio->$keyToUpdate);
        }
        $statementUpdate->bindParam(":negocio_id", $negocio->negocio_id);
        $updated = $statementUpdate->execute();

        $sucursalesNoEliminadasIds = array();
        $nuevasSucursales = array();
        $sucursalesActualizadas = array();
        foreach ($negocio->sucursales as $sucursal) {
            if ($sucursal->sucursal_id) {
                $sucursalesActualizadas[] = $sucursal;
                $sucursalesNoEliminadasIds[] = $sucursal->sucursal_id;
            } else {
                $nuevasSucursales[] = $sucursal;
            }
        }
        $this->depurarSucursales($negocio->negocio_id, $sucursalesNoEliminadasIds);
        $this->actualizarSucursales($sucursalesActualizadas);
        $this->insertarSucursales($negocio->negocio_id, $nuevasSucursales);

        return $updated;
    }

    function insertarSucursales($negocioId, $sucursales) {
        foreach ($sucursales as $sucursal) {
            $keysToInsert = array_intersect($this->sucursalesColumnsEditables, array_keys(get_object_vars($sucursal)));
            $valuesPlaceholders = array();
            foreach ($keysToInsert as $keyToInsert) {
                $valuesPlaceholders[] = ":$keyToInsert";
            }
            $valuesPlaceholdersStr = implode(', ', $valuesPlaceholders);
            $columnsStr = implode(', ', $keysToInsert);
            $statementInsert = $this->prepare("INSERT INTO sucursales(negocio_id, $columnsStr) VALUES(:negocio_id, $valuesPlaceholdersStr)");
            $statementInsert->bindParam(':negocio_id', $negocioId);
            foreach ($keysToInsert as $keyToInsert) {
                $statementInsert->bindParam(":$keyToInsert", $sucursal->$keyToInsert);
            }
            $sucursalInsertada = $statementInsert->execute();
            echo " Sucursal $sucursal->nombre insertada: " . ($sucursalInsertada ? 'si' : 'no') . ";  ";
            echo!$sucursalInsertada ? $statementInsert->queryString : '';
        }
    }

    function actualizarSucursales($sucursales) {
        foreach ($sucursales as $sucursal) {
            $keysToInsert = array_intersect($this->sucursalesColumnsEditables, array_keys(get_object_vars($sucursal)));
            $setPlaceholders = array();
            foreach ($keysToInsert as $keyToInsert) {
                $setPlaceholders[] = "$keyToInsert = :$keyToInsert";
            }
            $setPlaceholdersStr = implode(', ', $setPlaceholders);
            $statementUpdate = $this->prepare("UPDATE sucursales
                    SET $setPlaceholdersStr
                    WHERE sucursal_id=:sucursal_id ");
            foreach ($keysToInsert as $keyToInsert) {
                $statementUpdate->bindParam(":$keyToInsert", $sucursal->$keyToInsert);
            }
            $statementUpdate->bindParam(":sucursal_id", $sucursal->sucursal_id);
            $sucursalActualizada = $statementUpdate->execute();
            echo " Sucursal $sucursal->nombre actualizada: " . ($sucursalActualizada ? 'si' : 'no') . ";  ";
            echo!$sucursalActualizada ? $statementUpdate->queryString : '';
        }
    }

    function depurarSucursales($negocioId, $sucursalesQueQuedanIds) {
        array_unshift($sucursalesQueQuedanIds, 0);
        $questionMarks = implode(", ", array_pad(array(), count($sucursalesQueQuedanIds), "?"));
        $statement = $this->prepare("DELETE FROM sucursales WHERE sucursal_id NOT IN($questionMarks) AND negocio_id=?");
        $sucursalesQueQuedanIds[] = $negocioId;
        return $statement->execute($sucursalesQueQuedanIds);
    }

    function nuevoNegocio($llave, $negocio) {
        $keysToInsert = array_intersect($this->negociosColumnsEditables, array_keys(get_object_vars($negocio)));
        $valuesPlaceholders = array();
        foreach ($keysToInsert as $keyToInsert) {
            $valuesPlaceholders[] = ":$keyToInsert";
        }
        $valuesPlaceholdersStr = implode(', ', $valuesPlaceholders);
        $columnsStr = implode(', ', $keysToInsert);
        $this->beginTransaction();
        $statementInsert = $this->prepare("INSERT INTO negocios($columnsStr) VALUES($valuesPlaceholdersStr)");
        foreach ($keysToInsert as $keyToInsert) {
            $statementInsert->bindParam(":$keyToInsert", $negocio->$keyToInsert);
        }
        $negocioInsertado = $statementInsert->execute();
        $insertedNegocioId = $this->lastInsertId();
        if ($negocioInsertado) {
            $this->insertarSucursales($insertedNegocioId, $negocio->sucursales);
            $statementUpdateKey = $this->prepare("UPDATE llaves SET negocio_id=:negocio_id WHERE llave=:llave");
            $keyUpdated = $statementUpdateKey->execute(array(":negocio_id" => $insertedNegocioId, ":llave" => $llave));
            if ($keyUpdated) {
                $this->commit();
            }
            return $keyUpdated;
        } else {
            echo PHP_EOL . 'No se pudo insertar el nuevo negocio' . PHP_EOL;
            print_r($statementInsert->errorInfo());
            echo $statementInsert->queryString;
        }
        return false;
    }

    function obtenerCategorias() {
        $statement = $this->prepare('SELECT * FROM categorias');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
ob_start();
$t = new Trayectos();
$response = false;
$request_body = json_decode(file_get_contents('php://input'));
$action = $_GET['action'];
switch ($action) {
    case 'miNegocio':
        $response = $t->miNegocio($request_body);
        break;
    case 'guardarMiNegocio':
        $response = $t->guardarMiNegocio($request_body);
        break;
    case 'obtenerCategorias':
        $response = $t->obtenerCategorias();
        break;
    default:
        break;
}
$output = ob_get_clean();
//if (!empty($output)) {
//    error_log(str_repeat('#', 10) . PHP_EOL . date('H:i:s') . PHP_EOL . $output
//            . PHP_EOL, 3, 'output-' . date('Y-m-d') . '.log');
//}
echo json_encode(array('response' => $response, 'output' => $output));
exit();
