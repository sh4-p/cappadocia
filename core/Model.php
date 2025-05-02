<?php
/**
 * Base Model Class
 * 
 * All models will extend this class
 */
class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fields = ['*'];
    
    /**
     * Constructor
     * 
     * @param Database $db Database instance
     */
    public function __construct($db = null)
    {
        $this->db = $db ?? new Database();
    }
    
    /**
     * Get all records
     * 
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Records
     */
    public function getAll($conditions = [], $orderBy = null, $limit = null, $offset = null)
    {
        $sql = "SELECT " . implode(', ', $this->fields) . " FROM {$this->table}";
        
        // Add where conditions
        if (!empty($conditions)) {
            $sql .= ' WHERE';
            $i = 0;
            
            foreach ($conditions as $field => $value) {
                if ($i > 0) {
                    $sql .= ' AND';
                }
                
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $sql .= " {$field} {$value['operator']} :{$field}";
                    } else {
                        $placeholders = [];
                        foreach ($value as $j => $val) {
                            $placeholders[] = ":{$field}_{$j}";
                        }
                        $sql .= " {$field} IN (" . implode(', ', $placeholders) . ")";
                    }
                } else if ($value === null) {
                    $sql .= " {$field} IS NULL";
                } else {
                    $sql .= " {$field} = :{$field}";
                }
                
                $i++;
            }
        }
        
        // Add order by
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Add limit and offset
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        // Prepare query
        $this->db->query($sql);
        
        // Bind parameters
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $this->db->bind(":{$field}", $value['value']);
                    } else {
                        foreach ($value as $j => $val) {
                            $this->db->bind(":{$field}_{$j}", $val);
                        }
                    }
                } else if ($value !== null) {
                    $this->db->bind(":{$field}", $value);
                }
            }
        }
        
        // Execute query
        return $this->db->resultSet();
    }
    
    /**
     * Get record by ID
     * 
     * @param mixed $id ID value
     * @return array|false Record
     */
    public function getById($id)
    {
        $sql = "SELECT " . implode(', ', $this->fields) . " FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    /**
     * Get single record
     * 
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @return array|false Record
     */
    public function getOne($conditions = [], $orderBy = null)
    {
        $sql = "SELECT " . implode(', ', $this->fields) . " FROM {$this->table}";
        
        // Add where conditions
        if (!empty($conditions)) {
            $sql .= ' WHERE';
            $i = 0;
            
            foreach ($conditions as $field => $value) {
                if ($i > 0) {
                    $sql .= ' AND';
                }
                
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $sql .= " {$field} {$value['operator']} :{$field}";
                    } else {
                        $placeholders = [];
                        foreach ($value as $j => $val) {
                            $placeholders[] = ":{$field}_{$j}";
                        }
                        $sql .= " {$field} IN (" . implode(', ', $placeholders) . ")";
                    }
                } else if ($value === null) {
                    $sql .= " {$field} IS NULL";
                } else {
                    $sql .= " {$field} = :{$field}";
                }
                
                $i++;
            }
        }
        
        // Add order by
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Limit to 1
        $sql .= " LIMIT 1";
        
        // Prepare query
        $this->db->query($sql);
        
        // Bind parameters
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $this->db->bind(":{$field}", $value['value']);
                    } else {
                        foreach ($value as $j => $val) {
                            $this->db->bind(":{$field}_{$j}", $val);
                        }
                    }
                } else if ($value !== null) {
                    $this->db->bind(":{$field}", $value);
                }
            }
        }
        
        // Execute query
        return $this->db->single();
    }
    
    /**
     * Count records
     * 
     * @param array $conditions Where conditions
     * @return int Count
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        
        // Add where conditions
        if (!empty($conditions)) {
            $sql .= ' WHERE';
            $i = 0;
            
            foreach ($conditions as $field => $value) {
                if ($i > 0) {
                    $sql .= ' AND';
                }
                
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $sql .= " {$field} {$value['operator']} :{$field}";
                    } else {
                        $placeholders = [];
                        foreach ($value as $j => $val) {
                            $placeholders[] = ":{$field}_{$j}";
                        }
                        $sql .= " {$field} IN (" . implode(', ', $placeholders) . ")";
                    }
                } else if ($value === null) {
                    $sql .= " {$field} IS NULL";
                } else {
                    $sql .= " {$field} = :{$field}";
                }
                
                $i++;
            }
        }
        
        // Prepare query
        $this->db->query($sql);
        
        // Bind parameters
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (is_array($value)) {
                    if (isset($value['operator'])) {
                        $this->db->bind(":{$field}", $value['value']);
                    } else {
                        foreach ($value as $j => $val) {
                            $this->db->bind(":{$field}_{$j}", $val);
                        }
                    }
                } else if ($value !== null) {
                    $this->db->bind(":{$field}", $value);
                }
            }
        }
        
        // Execute query
        // Execute query and return the value
        $result = $this->db->single();
        return $result ? reset($result) : 0;
    }
    
    /**
     * Insert record
     * 
     * @param array $data Data to insert
     * @return int|false Last insert ID or false
     */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * Update record
     * 
     * @param array $data Data to update
     * @param array $conditions Where conditions
     * @return bool Success
     */
    public function update($data, $conditions)
    {
        return $this->db->update($this->table, $data, $conditions);
    }
    
    /**
     * Delete record
     * 
     * @param array $conditions Where conditions
     * @return bool Success
     */
    public function delete($conditions)
    {
        return $this->db->delete($this->table, $conditions);
    }
    
    /**
     * Execute custom query
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false Result
     */
    public function query($sql, $params = [])
    {
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Execute custom query and return single record
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false Result
     */
    public function querySingle($sql, $params = [])
    {
        return $this->db->getRow($sql, $params);
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }
    
    /**
     * End transaction
     */
    public function endTransaction()
    {
        return $this->db->endTransaction();
    }
    
    /**
     * Cancel transaction
     */
    public function cancelTransaction()
    {
        return $this->db->cancelTransaction();
    }
}