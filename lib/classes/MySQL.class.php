<?php
class MySQL implements IDatabase {

    public $log = false;
    public $module = "";
    public $submodule = "";

    private $conn;
    private static $instance;

    public function __construct($host, $user, $pass, $name, $charset) {
        $this->connect($host, $user, $pass, $name);
        $this->setNames($charset);
    }

    public function __destruct() {
        mysql_close($this->conn);
    }

    /**
     * Singleton design pattern method
     * @access public
     * @return Database
     */
    public static function getInstance($host, $user, $pass, $name, $charset) {
        if(!isset(self::$instance)) {
            $classname = __CLASS__;
            self::$instance = new $classname($host, $user, $pass, $name, $charset);
        }
        return self::$instance;
    }

    public function setNames($charset) {
        mysql_query("SET NAMES '$charset'", $this->conn);
    }

	public function execute($query) {
        if(!mysql_query($query, $this->conn)) {
            $this->error($query);
        } else {
			return true;
		}
	}

    public function insert($query, $logger = false) {
        $this->execute($query);

        $lastId = $this->lastId();

        if($this->log === true && $logger === false) {
            $this->log("INSERT", $query);
        }

        return $lastId;
    }

    public function update($query) {
        $this->execute($query);

        $affected = $this->affected();

        if($this->log === true) {
            $this->log("UPDATE", $query);
        }

        return $affected;
    }

    public function replace($query) {
        $this->execute($query);

        $lastId = $this->lastId();

        if($this->log === true) {
            $this->log("REPLACE", $query);
        }

        return $lastId;
    }

    public function delete($query) {
        $this->execute($query);

        $affected = $this->affected();

        if($this->log === true) {
            $this->log("DELETE", $query);
        }

        return $affected;
    }

    public function select($query, $one=false, $col="") {
        $return = array();
        $result = mysql_query($query, $this->conn);
/*
$f = fopen(LOG."log.txt", "ab");
fwrite($f, date('Y-m-d H:i:s')." - ".preg_replace('/[\s]{2,}/', ' ', $query)."\n");
fclose($f);
*/
        if(!is_resource($result)) {
            $this->error($query);
        }

        while($row = mysql_fetch_assoc($result)) {
            if($one === true) {
                if(!empty($col) && isset($row[$col])) {
                    $return = $row[$col];
                } else {
                    $return = $row;      
                }
            } else {
                $return[] = $row;
            }
        }

        $this->free($result);
        
        return $return;
    }

    public function lastId() {
        return mysql_insert_id($this->conn);
    }

    public function begin() {
        mysql_query("BEGIN", $this->conn);
    }

    public function commit() {
        mysql_query("COMMIT", $this->conn);
    }

    public function rollback() {
        mysql_query("ROLLBACK", $this->conn);
    }

    public function connect($host, $user, $pass, $name) {
        $this->conn = mysql_connect($host, $user, $pass);
        if(!$this->conn) {
            trigger_error("DB connection failed", E_USER_ERROR);
        }
        if(!mysql_select_db($name, $this->conn)) {
            $this->error();
        }
    }

    public function affected() {
        return mysql_affected_rows($this->conn);
    }

    public function log($action, $query) {
        $this->session = Session::getInstance(MODE);
        $query = "INSERT INTO ".Config::db_table_log_action()."
                  (domain_id, lang_id, user_id, module, submodule, action, inserted, query)
                  VALUES
                  (".$this->session->domain_id.", ".$this->session->lang_id.", ".$this->session->user_id.", '".$this->module."', '".$this->submodule."', '".$action."', NOW(), '".addslashes($query)."')";
        $this->insert($query, true);
    }

    public function free($result) {
        return mysql_free_result($result);
    }

    public function error($query) {
        die("Error ".mysql_errno($this->conn).": ".mysql_error($this->conn)."<br />".$query);
    }

}
