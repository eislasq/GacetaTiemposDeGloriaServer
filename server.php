<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
setlocale(LC_ALL, 'es_MX');
date_default_timezone_set('America/Mexico_City');

class Servicios extends PDO {

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
//        $dsn = 'mysql:host=localhost;dbname=u944467267_gacet';
        $dsn = 'mysql:unix_socket=/cloudsql/resolute-oxygen-95315:gaceta;dbname=Negocios';
        $username = 'root';
        $passwd = '';
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

    function obtenerProveedoresActualizadosDespuesDe($request_body) {
        $statement = $this->prepare('SELECT * FROM negocios WHERE fecha_actualizacion > :fecha_actualizacion');
        $statement->bindParam(':fecha_actualizacion', $request_body->fecha);
        $statement->execute();
        $negocios = array();
        while ($negocio = $statement->fetchObject()) {
            $statementSucursales = $this->prepare("SELECT * 
                FROM sucursales
                WHERE negocio_id=:negocio_id");
            $statementSucursales->bindParam(':negocio_id', $negocio->negocio_id);
            $statementSucursales->execute();
            $sucursales = $statementSucursales->fetchAll(PDO::FETCH_ASSOC);
            $negocio->sucursales = $sucursales;
            $negocios[] = $negocio;
        }
        return array('providers' => $negocios, 'lastUpdate' => date('Y-m-d H:i:s'));
    }

    function adminAccess($request_body) {
        session_start();
        if ($_SESSION['admin-access'] === TRUE) {
            return TRUE;
        }
        if ('f995c9f8aeeca58b6d0647ea005b4094' == md5($request_body->adminPasswd)) {
            $_SESSION['admin-access'] = true;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function verificarLogin() {
        session_start();
        return $_SESSION['admin-access'] === TRUE ? TRUE : FALSE;
    }

    function adminLogout($request_body) {
        session_start();
        $_SESSION['admin-access'] = FALSE;
        session_destroy();
        return TRUE;
    }

    function neuevaCategoria($request_body) {
        if (!$this->verificarLogin()) {
            return false;
        }
        $statement = $this->prepare('INSERT INTO categorias(nombre) VALUES(:categoryName)');
        $statement->bindParam(':categoryName', $request_body->categoryName);
        $insertada = $statement->execute();
        if ($insertada) {
            $insertedId = $this->lastInsertId();
            return array('categoria_id' => $insertedId, 'nombre' => $request_body->categoryName);
        }
        return FALSE;
    }

    function modificarCategoria($request_body) {
        if (!$this->verificarLogin()) {
            return false;
        }
        $statement = $this->prepare('UPDATE categorias set nombre=:categoryName WHERE categoria_id=:categoryId');
        $statement->bindParam(':categoryId', $request_body->categoryId);
        $statement->bindParam(':categoryName', $request_body->categoryName);
        $actualizada = $statement->execute();
        return $actualizada;
    }

    function eliminarCategoria($request_body) {
        if (!$this->verificarLogin()) {
            return false;
        }
        $statement = $this->prepare('DELETE FROM categorias WHERE categoria_id=:categoryId');
        $statement->bindParam(':categoryId', $request_body->categoryId);
        $eliminada = $statement->execute();
        return $eliminada;
    }

    function obtenerLlaves($request_body) {
        if (!$this->verificarLogin()) {
            return false;
        }
        $statement = $this->prepare("SELECT ll.*, n.nombre, n.duenio, n.logo
                FROM llaves ll
                LEFT JOIN negocios n
                ON ll.negocio_id=n.negocio_id
                ");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function generarLlaves($request_body) {
        if (!$this->verificarLogin()) {
            return false;
        }
        require_once './luhnModN.php';
        $luhn = new luhnModN;
        $llavesArray = array();
        $returnArray = array();
        for ($i = 1; $i <= $request_body->cantidad; $i++) {
            $llave = $luhn->getLinkString();
            $llavesArray[] = $llave;
            $returnArray[] = array('llave' => $llave);
        }
        $valuesForQuery = implode("'), ('", $llavesArray);


        $statement = $this->prepare("
        INSERT INTO llaves (llave)
        VALUES ('$valuesForQuery')");
        $insertadas = $statement->execute();
        if ($insertadas) {
            return $returnArray;
        }
        echo $statement->queryString;
        return false;
    }

}

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    ob_start();
    $s = new Servicios();
    $response = false;
    $request_body = json_decode(file_get_contents('php://input'));
    $action = $_GET['action'];
    if (method_exists($s, $action)) {
        $response = $s->$action($request_body);
    }
    $output = ob_get_clean();
//if (!empty($output)) {
//    error_log(str_repeat('#', 10) . PHP_EOL . date('H:i:s') . PHP_EOL . $output
//            . PHP_EOL, 3, 'output-' . date('Y-m-d') . '.log');
//}
    echo json_encode(array('response' => $response, 'output' => $output));
    exit();
} else {
    include_once 'default.php';
}