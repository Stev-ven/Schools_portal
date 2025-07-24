<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Db
{

    //MySQLi
    public function MySQLiConnection(string $db = null)
    {
        return mysqli_connect(DB[0], DB[1], DB[2], $db);
    }

    public function dbQuery($connection, $query)
    {
        return mysqli_query($connection, $query);
    }

    public function dbMultiQuery($connection, $query)
    {
        return mysqli_multi_query($connection, $query);
    }

    public function dbEsc($data)
    {
        return addSlashes($data);
    }

    public function dbClose($connection)
    {
        return mysqli_close($connection);
    }

    //PDO
    public function PDOConnection($db = null)
    {
        $dsn = "mysql:host=" . DB[0] . ";port=" . DB[3] . ";dbname=" . $db;
        try {
            $pdo = new PDO($dsn, DB[1], DB[2]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function selectSingleData($connection, $query)
    {
        if (empty($query)) {
            return array();
        }
        $result = $connection->query($query);
        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        }

    }

    public function selectMultipleData($connection, $query)
    {
        if (empty($query)) {
            return array();
        }
        $result = $connection->query($query);
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
    }

    public function runQuery($connection, $query, $bindData = array(), $returnData = false, $single = false)
    {
        if (empty($query)) {
            return false;
        }

        if ($returnData) {
            $stmt = $connection->prepare($query, array(
                PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
            ));

            $qArr = array();
            if (is_array($bindData)) {
                foreach ($bindData as $key => $value) {
                    $qArr[":" . $key] = $value;
                }
            }

            if ($stmt->execute($qArr)) {
                if ($single) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return false;
        } else {
            $stmt = $connection->prepare($query);

            $qArr = array();
            if (is_array($bindData)) {
                foreach ($bindData as $key => $value) {
                    $qArr[":" . $key] = $value;
                }
            }

            if ($stmt->execute($qArr)) {
                return true;
            }
            return false;
        }
    }

    public function insertData($connection, $query)
    {
        if (empty($query)) {
            return;
        }
        $result = $connection->exec($query);
        if ($result) {
            return $result;
        }

        return false;
    }

    public function lastInsertId($connection)
    {
        return $connection->lastInsertId();
    }

    public function deleteData($connection, $query)
    {
        if (empty($query)) {
            return 0;
        }

        $result = $connection->exec($query);
        if ($result) {
            return $result;
        }

        return false;
    }

    public function updateData($connection, $query)
    {
        if (empty($query)) {
            return array();
        }
        $result = $connection->exec($query);
        if ($result) {
            return $result;
        }

        return false;
    }

    public function countData($connection, $query)
    {
        if (empty($query)) {
            return 0;
        }
        $result = $connection->query($query)->fetchColumn();
        if ($result) {
            return $result;
        }

        return 0;
    }

    public function closeConnection($connection)
    {
        $connection = null;
    }

    public function PDObeginTransaction($connection)
    {
        $connection->beginTransaction();
    }

    public function PDOcommitTransaction($connection)
    {
        $connection->commit();
    }

    public function PDOrollBack($connection)
    {
        $connection->rollBack();
    }

    //Others
    public function queryEscape($data)
    {
        return addSlashes($data);
    }

    public function queryEscapeFilter($data)
    {
        return addSlashes(filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    }

    public function throwConnectionError($return = false){
        $data["status"] = ERROR_CODE;
        $data["data"] = "Could not establish connection to the server. Try again.";
        if($return) return $data;
        die(json_encode($data,true));
    }

}
