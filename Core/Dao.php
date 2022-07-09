<?php

declare(strict_types=1);

namespace Core;

class Dao extends Connection
{
    private $table = '';
    public $pdo = null;

    public function __construct($table)
    {
        $this->table = $table;
        try {
            self::openDb();
        } catch (PDOException $e) {
            exit('A conexão com o banco de dados não pôde ser estabelecida.');
        }
    }

    // Nome da coluna que armazena a Primary Key
    public function primaryKey()
    {
        $sql = 'show columns from ' . $this->table . ' where `Key` = "PRI";';
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetch()->Field;
    }

    // Número de colunas da tabela atual
    public function columnCount()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->pdo->query($sql);
        $num_campos = $sth->columnCount();
        return $num_campos;
    }

    // Nome da coluna pelo número $x
    public function columnName($x)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' LIMIT 1';
        $sth = $this->pdo->query($sql);
        $meta = $sth->getColumnMeta($x);
        $field = $meta['name'];
        return $field;
    }

    // Nome de todas colunas
    public function allColumns()
    {
        $fld = '';
        for ($x = 1; $x < $this->columnCount(); $x++) {
            $field = $this->columnName($x);
            if ($x < $this->columnCount() - 1) {
                $fld .= $field . ',';
            } else {
                $fld .= $field;
            }
        }
        $fld = explode(',', $fld);
        return $fld;
    }

    // Recuperar todos os registros
    public function allValues()
    {
        $pk = $this->primaryKey();
        $sql = "SELECT * FROM  $this->table ORDER BY $pk DESC";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    // Recuperar um unico registro
    public function getByKey($id)
    {
        $pk = $this->primaryKey();
        $sql = "SELECT * FROM $this->table WHERE $pk = :id";
        $query = $this->pdo->prepare($sql);
        $query->execute(["id" => $id]);
        return $query->fetchAll();
    }

    //Deletar registro
    public function delete($field_id)
    {
        $pk = $this->primaryKey();
        $sql = "DELETE FROM $this->table WHERE $pk = :field_id";
        $query = $this->pdo->prepare($sql);
        $parameters = array(':field_id' => $field_id);
        $query->execute($parameters);
    }

    // Contar registros
    public function countRegs()
    {
        $pk = $this->primaryKey();
        $sql = "SELECT COUNT($pk) AS soma FROM $this->table";
        $query = $this->pdo->prepare($sql);
        $query->execute();
        return $query->fetch()->soma;
    }
}
