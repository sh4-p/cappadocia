
<?php
/**
 * Database Class
 * 
 * PDO database abstraction layer
 */
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    private $port = DB_PORT;
    
    private $pdo;
    private $stmt;
    private $error;
    
    /**
     * Constructor - Creates a PDO connection
     */
    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';charset=' . $this->charset;
        
        // Set options
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // Create PDO instance
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Database Error: ' . $this->error;
            die();
        }
    }
    
    /**
     * Prepare statement with query
     * 
     * @param string $sql SQL query
     */
    public function query($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
    }
    
    /**
     * Bind values to prepared statement using named parameters
     * 
     * @param string $param Parameter name
     * @param mixed $value Parameter value
     * @param mixed $type Parameter type
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Execute the prepared statement
     * 
     * @return bool True on success
     */
    public function execute()
    {
        return $this->stmt->execute();
    }
    
    /**
     * Get result set as array of objects
     * 
     * @param string $class Name of class to map results to
     * @return array Result array
     */
    public function resultSet($class = null)
    {
        $this->execute();
        
        if ($class) {
            return $this->stmt->fetchAll(PDO::FETCH_CLASS, $class);
        }
        
        return $this->stmt->fetchAll();
    }
    
    /**
     * Get single record as object
     * 
     * @param string $class Name of class to map result to
     * @return object|array Single result
     */
    public function single($class = null)
    {
        $this->execute();
        
        if ($class) {
            $this->stmt->setFetchMode(PDO::FETCH_CLASS, $class);
            return $this->stmt->fetch();
        }
        
        return $this->stmt->fetch();
    }
    
    /**
     * Get row count
     * 
     * @return int Row count
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
    
    /**
     * Get last insert ID
     * 
     * @return int Last insert ID
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * End transaction and commit
     */
    public function endTransaction()
    {
        return $this->pdo->commit();
    }
    
    /**
     * Cancel transaction
     */
    public function cancelTransaction()
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * Debug dump parameters
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
    
    /**
     * Execute simple query
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return bool True on success
     */
    public function executeQuery($sql, $params = [])
    {
        $this->query($sql);
        
        foreach ($params as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        return $this->execute();
    }
    
    /**
     * Get multiple rows
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @param string $class Class name
     * @return array Result array
     */
    public function getRows($sql, $params = [], $class = null)
    {
        $this->query($sql);
        
        foreach ($params as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        return $this->resultSet($class);
    }
    
    /**
     * Get single row
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @param string $class Class name
     * @return object|array Single result
     */
    public function getRow($sql, $params = [], $class = null)
    {
        $this->query($sql);
        
        foreach ($params as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        return $this->single($class);
    }
    
    /**
     * Get single value
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return mixed Single value
     */
    public function getValue($sql, $params = [])
    {
        $row = $this->getRow($sql, $params);
        
        if ($row) {
            return reset($row);
        }
        
        return null;
    }
    
    /**
     * Count rows
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return int Count
     */
    public function count($sql, $params = [])
    {
        $this->query($sql);
        
        foreach ($params as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        $this->execute();
        
        return $this->rowCount();
    }
    
    /**
     * Insert row
     * 
     * @param string $table Table name
     * @param array $data Data to insert
     * @return int|bool Last insert ID or false
     */
    public function insert($table, $data)
    {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);
        
        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        if ($this->execute()) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update row
     * 
     * @param string $table Table name
     * @param array $data Data to update
     * @param array $where Where conditions
     * @return bool True on success
     */
    public function update($table, $data, $where)
    {
        $fields = array_map(function($field) {
            return $field . ' = :' . $field;
        }, array_keys($data));
        
        $whereFields = array_map(function($field) {
            return $field . ' = :where_' . $field;
        }, array_keys($where));
        
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $fields) . ' WHERE ' . implode(' AND ', $whereFields);
        
        $this->query($sql);
        
        foreach ($data as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        foreach ($where as $key => $value) {
            $this->bind(':where_' . $key, $value);
        }
        
        return $this->execute();
    }
    
    /**
     * Delete row
     * 
     * @param string $table Table name
     * @param array $where Where conditions
     * @return bool True on success
     */
    public function delete($table, $where)
    {
        $whereFields = array_map(function($field) {
            return $field . ' = :' . $field;
        }, array_keys($where));
        
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . implode(' AND ', $whereFields);
        
        $this->query($sql);
        
        foreach ($where as $key => $value) {
            $this->bind(':' . $key, $value);
        }
        
        return $this->execute();
    }
}