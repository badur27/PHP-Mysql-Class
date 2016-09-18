<?php

/**
 * @author BADUR ALHARBI
 * @example MysqlClass MysqlClass = new MysqlClass($hostname, $database, $username, $password, $port);
 */
class MysqlClass {

    private $hostname;
    private $database;
    private $username;
    private $password;
    private $port = 3306;
    private $Connection;

    //constructions
    public function __construct($hostname, $database, $username, $password, $port) {
        $this->set_hostname($hostname);
        $this->set_database($database);
        $this->set_username($username);
        $this->set_password($password);
        $this->set_port($port);
        $this->set_Connection($this->open_Connection());
    }

    //GET
    private function get_Connection() {
        return $this->Connection;
    }

    public function get_hostname() {
        return $this->hostname;
    }

    public function get_database() {
        return $this->database;
    }

    public function get_username() {
        return $this->username;
    }

    public function get_password() {
        return $this->password;
    }

    public function get_port() {
        return $this->port;
    }

    //SET
    private function set_Connection($Connection) {
        $this->Connection = $Connection;
    }

    public function set_hostname($hostname) {
        $this->hostname = $hostname;
    }

    public function set_database($database) {
        $this->database = $database;
    }

    public function set_username($username) {
        $this->username = $username;
    }

    public function set_password($password) {
        $this->password = $password;
    }

    public function set_port($port) {
        $this->port = $port;
    }

    //Connections  methods
    /**
     * 
     * @return \mysqli
     * @author BADUR ALHARBI
     */
    private function open_Connection() {
        $Connection = new mysqli($this->get_hostname(), $this->get_username(), $this->get_password(), $this->get_database(), $this->get_port());
        if ($Connection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $Connection->connect_errno . ") " . $Connection->connect_error;
        }
        return $Connection;
    }

    /** sql methods  * */

    /**
     *  
     * @param string $table_name this is table name 
     * @param array $data  it's data will insert into table  array key = cloumn name  , value = value of column
     * @return boolean  true success update flase otherwise 
     * @example MysqlClass.insert('employee',['id'=>1 ,'name' =>badur]); 
     * @author BADUR ALHARBI
     */
    public function insert($table_name, $data) {
        $sql = "INSERT INTO `" . $table_name . "` (" . $this->get_keys($data) . ") VALUES (" . $this->get_values($data) . ");";
        if ($this->get_Connection()->query($sql) === TRUE) {
            //echo "Error: " . $sql . "<br>" . $this->get_Connection()->error;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param string $table_name this is table name 
     * @param array $where  it's condition for delete  array  key = cloumn name , value = value of column 
     * @return boolean  true success update flase otherwise 
     * @example MysqlClass.delete('employee',['id'=>1]); 
     * @author BADUR ALHARBI
     */
    public function delete($table_name, $where) {
        $sql = 'DELETE FROM `' . $table_name . $this->get_where($where);
        if ($this->get_Connection()->query($sql) === TRUE) {
            // echo "Error: " . $sql . "<br>" . $this->get_Connection()->error;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param array or string $table_name this is table name 
     * @param array $where it's condition for delete  array  key = cloumn name , value = value of column 
     * @param array $column_name it's cloumns yuo wont  to get from table  if null select all (*)
     * @return returns a resource on success  (AS array of object) , or FALSE on error.
     * @example MysqlClass.select('employee',['id'=>1])
     *         sql =  select * from employee where id = 1
     * @author BADUR ALHARBI
     */
    public function select($table_name, $where = null, $column_name = null) {
        return $this->get_Connection()->query($this->select_bulide_sql($table_name, $where, $column_name));
    }

    /**
     * @param string $table_name this is table name 
     * @param array $data  it's data will update (add)   array key = cloumn name  , value = value of column
     * @param array $where it's condition for delete  array  key = cloumn name , value = value of column 
     * @return boolean  true success update flase otherwise 
     * @example MysqlClass.update('employee',['salary' = 3500],['id'=>1];
     *          update employee set salary = 3500 where id = 1
     * @author BADUR ALHARBI
     */
    public function update($table_name, $data, $where) {
        if ($this->get_Connection()->query($this->update_bulide_sql($table_name, $data, $where)) == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**  bulide  statemnts methods  * */
    //bulide select statemnt methods
    public function select_bulide_sql($table_name, $where = null, $column_name = null) {
        return "SELECT " . $this->get_column_name($column_name) . " " . $this->get_table_name($table_name) . " " . $this->get_where($where);
        //for shwo sql command use  echo  with  this  method
    }

    private function get_column_name($column_name) {
        if (is_null($column_name)) {
            //$column_name is null  select all
            return ' * ';
        } else {
            return $this->get_values($column_name);
        }
    }

    private function get_table_name($table_name) {

        if (is_array($table_name)) {
            return " FROM " . $this->get_values($table_name);
        } else {
            return " FROM " . $table_name;
        }
    }

    private function get_where($where) {

        if (!is_null($where)) {
            $count = 0;
            $sql = 'where ';
            while ($count <= (count($where) - 1)) {
                $sql = $sql . $this->get_key($where, $count) . " = " . $this->get_value($where, $count) . " and ";
                $count++;
            }
            return rtrim($sql, " and ");
        }
    }

    //bulide update statemnt methods
    public function update_bulide_sql($table_name, $data, $where) {
        $sql = 'UPDATE ' . $table_name . ' SET ';
        $count = 0;
        while ($count <= (count($data) - 1)) {
            $sql = $sql . $this->get_key($data, $count) . " = " . $this->get_value($data, $count) . " , ";
            $count++;
        }
        return rtrim($sql, " , ") . ' WHERE ' . $this->get_key($where, 0) . ' = ' . $this->get_value($where, 0);
    }

    //array methods
    public function get_key($array, $i) {
        return array_keys($array)[$i];
    }

    public function get_value($array, $i) {
        return array_values($array)[$i];
    }

    public function get_keys($array) {
        $keys = array_keys($array);
        $sql = NULL;
        foreach ($keys as $key) {
            $sql = $sql . $key . ',';
        }
        return $sql = rtrim($sql, ",");
    }

    public function get_values($array) {
        $values = $values = array_values($array);
        $sql = NULL;
        foreach ($values as $values) {
            $sql = $sql . $values . ',';
        }
        return $sql = rtrim($sql, ",");
    }

}

?>
