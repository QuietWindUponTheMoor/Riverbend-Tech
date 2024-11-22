<?php

class Query {

    private $conn = null;
    private $QUERY = "";
    private $bind_data = null;

    // Sets
    public $result = null;

    public function __construct(mysqli $conn, string $type, string $query, ...$bind_data) {
        $this->conn = $conn;
        $this->QUERY = $query;
        $this->bind_data = $bind_data;

        $type = strtolower($type);

        switch ($type) {
            case "i":
                return $this->insert();
                break;
            case "s":
                return $this->select();
                break;
            case "sa":
                return $this->selectAll();
                break;
            case "d":
                return $this->delete();
            default:
                die("WARNING: " + $type + " is not a valid or supported query type. Please try again.");
                break;
        }
    }

    private function insert(): bool {
        $sql = $this->QUERY;
        $stmt = mysqli_stmt_init($this->conn)
            or die("Could not initiate a connection.");
        mysqli_stmt_prepare($stmt, $sql)
            or die("Could not prepare SQL statement.");
        mysqli_stmt_bind_param($stmt, ...$this->bind_data)
            or die("Could not bind SQL parameters.");
        mysqli_stmt_execute($stmt)
            or die("Could not execute SQL sequence.");
        mysqli_stmt_close($stmt)
            or die("Could not close SQL connection.");
        
        return true;
    }
    
    private function select(): bool {
        $sql = $this->QUERY;
        $stmt = mysqli_stmt_init($this->conn)
            or die("Could not initiate a connection.");
        mysqli_stmt_prepare($stmt, $sql)
            or die("Could not prepare SQL statement.");
        mysqli_stmt_bind_param($stmt, ...$this->bind_data)
            or die("Could not bind SQL parameters.");
        mysqli_stmt_execute($stmt)
            or die("Could not execute SQL sequence.");
        $result = mysqli_stmt_get_result($stmt)
            or die("Could not retrieve data with query $sql.");
        mysqli_stmt_close($stmt)
            or die("Could not close SQL connection.");
        
        $this->result = $result;
    
        return true;
    }
    
    private function selectAll(): bool {
        $sql = $this->QUERY;
        $stmt = mysqli_stmt_init($this->conn)
            or die("Could not initiate a connection.");
        mysqli_stmt_prepare($stmt, $sql)
            or die("Could not prepare SQL statement.");
        mysqli_stmt_execute($stmt)
            or die("Could not execute SQL sequence.");
        $result = mysqli_stmt_get_result($stmt)
            or die("Could not retrieve data with query $sql.");
        mysqli_stmt_close($stmt)
            or die("Could not close SQL connection.");
        
        $this->result = $result;
    
        return true;
    }
    
    private function delete(): bool {
        $sql = $this->QUERY;
        $stmt = mysqli_stmt_init($this->conn)
            or die("Could not initiate a connection.");
        mysqli_stmt_prepare($stmt, $sql)
            or die("Could not prepare SQL statement.");
        mysqli_stmt_execute($stmt)
            or die("Could not execute SQL sequence.");
        mysqli_stmt_close($stmt)
            or die("Could not close SQL connection.");
    
        return true;
    }
}