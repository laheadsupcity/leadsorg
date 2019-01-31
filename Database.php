<?php

/**
 * Exception helper for the Database class
 */
class DatabaseException extends Exception
{
    // Default Exception class handles everything
}

/**
 * A basic database interface using MySQLi
 */
class Database
{
    private $sql;
    private $mysql;
    private $result;
    private $result_rows;
    private $database_name;
    private static $instance;

    /**
     * Query history
     *
     * @var array
     */
    public static $queries = array();

    /**
     * Database() constructor
     *
     * @param string $database_name
     * @param string $username
     * @param string $password
     * @param string $host
     * @throws DatabaseException
     */
    public function __construct($database_name, $username, $password, $host = 'localhost')
    {
        self::$instance = $this;

        $this->database_name = $database_name;
        $this->mysql = mysqli_connect($host, $username, $password, $database_name);

        if (!$this->mysql) {
            throw new DatabaseException('Database connection error: ' . mysqli_connect_error());
        }
    }

    /**
     * Get instance
     *
     * @param string $database_name
     * @param string $username
     * @param string $password
     * @param string $host
     * @return Database
     */
    final public static function instance($database_name = null, $username = null, $password = null, $host = 'localhost')
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database($database_name, $username, $password, $host);
        }

        return self::$instance;
    }

    /**
     * Helper for throwing exceptions
     *
     * @param $error
     * @throws Exception
     */
    private function _error($error)
    {
        throw new DatabaseException('Database error: ' . $error);
    }

    /**
     * Turn an array into a where statement
     *
     * @param mixed $where
     * @param string $where_mode
     * @return string
     * @throws Exception
     */
    public function process_where($where, $where_mode = 'AND')
    {
        $query = '';
        if (is_array($where)) {
            $num = 0;
            $where_count = count($where);
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    $w = array_keys($v);
                    if (reset($w) != 0) {
                        throw new Exception('Can not handle associative arrays');
                    }
                    $query .= " `" . $k . "` IN (" . $this->join_array($v) . ")";
                } elseif (!is_integer($k)) {
                    $query .= ' `' . $k . "`='" . $this->escape($v) . "'";
                } else {
                    $query .= ' ' . $v;
                }
                $num++;
                if ($num != $where_count) {
                    $query .= ' ' . $where_mode;
                }
            }
        } else {
            $query .= ' ' . $where;
        }
        return $query;
    }

    /**
     * Perform a SELECT operation
     *
     * @param string $table
     * @param array $where
     * @param bool $limit
     * @param bool $order
     * @param string $where_mode
     * @param string $select_fields
     * @return Database
     * @throws DatabaseException
     */
    public function select($table, $where = array(), $limit = false, $order = false, $where_mode = "AND", $select_fields = '*')
    {
        $this->result = null;
        $this->sql = null;

        if (is_array($select_fields)) {
            $fields = '';
            foreach ($select_fields as $s) {
                $fields .= '`' . $s . '`, ';
            }
            $select_fields = rtrim($fields, ', ');
        }

        $query = 'SELECT ' . $select_fields . ' FROM `' . $table . '`';
        if (!empty($where)) {
            $query .= ' WHERE' . $this->process_where($where, $where_mode);
        }
        if ($order) {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        error_log("==========selectquery=========>".print_r($query, true));
        return $this->query($query);
    }

    /**
     * Perform a query
     *
     * @param string $query
     * @return $this|Database
     * @throws Exception
     */
    public function query($query)
    {
        self::$queries[] = $query;
        $this->sql = $query;

        $this->result_rows = null;
        $this->result = mysqli_query($this->mysql, $query);

        if (mysqli_error($this->mysql) != '') {
            $this->_error(mysqli_error($this->mysql));
            $this->result = null;
            return $this;
        }

        return $this;
    }

    /**
     * Get last executed query
     *
     * @return string|null
     */
    public function sql()
    {
        return $this->sql;
    }

    /**
     * Get an array of objects with the query result
     *
     * @param string|null $key_field
     * @return array
     */
    public function result($key_field = null)
    {
        if (!$this->result_rows) {
            $this->result_rows = array();
            while ($row = mysqli_fetch_assoc($this->result)) {
                $this->result_rows[] = $row;
            }
        }

        $result = array();
        $index = 0;

        foreach ($this->result_rows as $row) {
            $key = $index;
            if (!empty($key_field) && isset($row[$key_field])) {
                $key = $row[$key_field];
            }
            $result[$key] = new stdClass();
            foreach ($row as $column => $value) {
                $this->is_serialized($value, $value);
                $result[$key]->{$column} = $this->clean($value);
            }
            $index++;
        }
        return $result;
    }

    /**
     * Get an array of arrays with the query result
     *
     * @return array
     */
    public function result_array()
    {
        if (!$this->result_rows) {
            $this->result_rows = array();
            while ($row = mysqli_fetch_assoc($this->result)) {
                $this->result_rows[] = $row;
            }
        }
        $result = array();
        $n = 0;
        foreach ($this->result_rows as $row) {
            $result[$n] = array();
            foreach ($row as $k => $v) {
                $this->is_serialized($v, $v);
                $result[$n][$k] = $this->clean($v);
            }
            $n++;
        }
        return $result;
    }

    /**
     * Get a specific row from the result as an object
     *
     * @param int $index
     * @return stdClass
     */
    public function row($index = 0)
    {
        if (!$this->result_rows) {
            $this->result_rows = array();
            while ($row = mysqli_fetch_assoc($this->result)) {
                $this->result_rows[] = $row;
            }
        }

        $num = 0;
        foreach ($this->result_rows as $column) {
            if ($num == $index) {
                $row = new stdClass();
                foreach ($column as $key => $value) {
                    $this->is_serialized($value, $value);
                    $row->{$key} = $this->clean($value);
                }
                return $row;
            }
            $num++;
        }

        return new stdClass();
    }

    /**
     * Get a specific row from the result as an array
     *
     * @param int $index
     * @return array
     */
    public function row_array($index = 0)
    {
        if (!$this->result_rows) {
            $this->result_rows = array();
            while ($row = mysqli_fetch_assoc($this->result)) {
                $this->result_rows[] = $row;
            }
        }

        $num = 0;
        foreach ($this->result_rows as $column) {
            if ($num == $index) {
                $row = array();
                foreach ($column as $key => $value) {
                    $this->is_serialized($value, $value);
                    $row[$key] = $this->clean($value);
                }
                return $row;
            }
            $num++;
        }

        return array();
    }

    /**
     * Get the number of result rows
     *
     * @return bool|int
     */
    public function count()
    {
        if ($this->result) {
            return mysqli_num_rows($this->result);
        } elseif (isset($this->result_rows)) {
            return count($this->result_rows);
        } else {
            return false;
        }
    }

    /**
     * Execute a SELECT COUNT(*) query on a table
     *
     * @param null $table
     * @param array $where
     * @param bool $limit
     * @param bool $order
     * @param string $where_mode
     * @return mixed
     */
    public function num($table = null, $where = array(), $limit = false, $order = false, $where_mode = "AND")
    {
        if (!empty($table)) {
            $this->select($table, $where, $limit, $order, $where_mode, 'COUNT(*)');
        }

        $res = $this->row();
        return $res->{'COUNT(*)'};
    }

    /**
     * Check if a table with a specific name exists
     *
     * @param $name
     * @return bool
     */
    public function table_exists($name)
    {
        $res = mysqli_query($this->mysql, "SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '" . $this->escape($this->database_name) . "' AND table_name = '" . $this->escape($name) . "'");
        return ($this->mysqli_result($res, 0) == 1);
    }

    /**
     * Helper function for process_where
     *
     * @param $array
     * @return string
     */
    private function join_array($array)
    {
        $nr = 0;
        $query = '';
        foreach ($array as $key => $value) {
            if (is_object($value) || is_array($value) || is_bool($value)) {
                $value = serialize($value);
            }
            $query .= " '" . $this->escape($value) . "'";
            $nr++;
            if ($nr != count($array)) {
                $query .= ',';
            }
        }
        return trim($query);
    }

    /* Insert/update functions */

    /**
     * Insert a row in a table
     *
     * @param $table
     * @param array $fields
     * @param bool|false $appendix
     * @param bool|false $ret
     * @return bool|Database
     * @throws Exception
     */
    public function insert($table, $fields = array(), $appendix = false, $ret = false)
    {
        $this->result = null;
        $this->sql = null;

        $query = 'INSERT INTO';
        $query .= ' `' . $this->escape($table) . "`";

        if (is_array($fields)) {
            $query .= ' (';
            $num = 0;
            foreach ($fields as $key => $value) {
                $query .= ' `' . $key . '`';
                $num++;
                if ($num != count($fields)) {
                    $query .= ',';
                }
            }
            $query .= ' ) VALUES ( ' . $this->join_array($fields) . ' )';
        } else {
            $query .= ' ' . $fields;
        }
        if ($appendix) {
            $query .= ' ' . $appendix;
        }
        if ($ret) {
            return $query;
        }
        $this->sql = $query;
        $this->result = mysqli_query($this->mysql, $query);
        if (mysqli_error($this->mysql) != '') {
            $this->_error(mysqli_error($this->mysql));
            $this->result = null;
            return false;
        } else {
            return $this;
        }
    }

    /**
     * Execute an UPDATE statement
     *
     * @param $table
     * @param array $fields
     * @param array $where
     * @param bool $limit
     * @param bool $order
     * @return $this|bool
     * @throws DatabaseException
     */
    public function update($table, $fields = array(), $where = array(), $limit = false, $order = false)
    {
        if (empty($where)) {
            throw new DatabaseException('Where clause is empty for update method');
        }

        $this->result = null;
        $this->sql = null;
        $query = 'UPDATE `' . $table . '` SET';
        if (is_array($fields)) {
            $nr = 0;
            foreach ($fields as $k => $v) {
                if (is_object($v) || is_array($v) || is_bool($v)) {
                    $v = serialize($v);
                }
                $query .= ' `' . $k . "`='" . $this->escape($v) . "'";
                $nr++;
                if ($nr != count($fields)) {
                    $query .= ',';
                }
            }
        } else {
            $query .= ' ' . $fields;
        }
        if (!empty($where)) {
            $query .= ' WHERE' . $this->process_where($where);
        }
        if ($order) {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        $this->sql = $query;
        $this->result = mysqli_query($this->mysql, $query);
        if (mysqli_error($this->mysql) != '') {
            $this->_error(mysqli_error($this->mysql));
            $this->result = null;
            return false;
        } else {
            return $this;
        }
    }

    /**
     * Execute a DELETE statement
     *
     * @param $table
     * @param array $where
     * @param string $where_mode
     * @param bool $limit
     * @param bool $order
     * @return $this|bool
     * @throws DatabaseException
     * @throws Exception
     */
    public function delete($table, $where = array(), $where_mode = "AND", $limit = false, $order = false)
    {
        if (empty($where)) {
            throw new DatabaseException('Where clause is empty for update method');
        }

        // Notice: different syntax to keep backwards compatibility
        $this->result = null;
        $this->sql = null;
        $query = 'DELETE FROM `' . $table . '`';
        if (!empty($where)) {
            $query .= ' WHERE' . $this->process_where($where, $where_mode);
        }
        if ($order) {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        $this->sql = $query;

        $this->result = mysqli_query($this->mysql, $query);
        if (mysqli_error($this->mysql) != '') {
            $this->_error(mysqli_error($this->mysql));
            $this->result = null;
            return false;
        } else {
            return $this;
        }
    }

    /**
     * Get the primary key of the last inserted row
     *
     * @return int|string
     */
    public function id()
    {
        return mysqli_insert_id($this->mysql);
    }

    /**
     * Get the number of rows affected by your last query
     *
     * @return int
     */
    public function affected()
    {
        return mysqli_affected_rows($this->mysql);
    }

    /**
     * Escape a parameter
     *
     * @param $str
     * @return string
     */
    public function escape($str)
    {
        return mysqli_real_escape_string($this->mysql, $str);
    }

    /**
     * Get the last error message
     *
     * @return string
     */
    public function error()
    {
        return mysqli_error($this->mysql);
    }

    /**
     * Fix UTF-8 encoding problems
     *
     * @param $str
     * @return string
     */
    private function clean($str)
    {
        if (is_string($str)) {
            if (!mb_detect_encoding($str, 'UTF-8', true)) {
                $str = utf8_encode($str);
            }
        }
        return $str;
    }

    /**
     * Check if a variable is serialized
     *
     * @param mixed $data
     * @param null $result
     * @return bool
     */
    public function is_serialized($data, &$result = null)
    {
        if (!is_string($data)) {
            return false;
        }

        $data = trim($data);

        if (empty($data)) {
            return false;
        }
        if ($data === 'b:0;') {
            $result = false;
            return true;
        }
        if ($data === 'b:1;') {
            $result = true;
            return true;
        }
        if ($data === 'N;') {
            $result = null;
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if ($data[1] !== ':') {
            return false;
        }
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }

        $token = $data[0];
        switch ($token) {
            case 's':
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
                break;
            case 'a':
            case 'O':
                if (!preg_match("/^{$token}:[0-9]+:/s", $data)) {
                    return false;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if (!preg_match("/^{$token}:[0-9.E-]+;/", $data)) {
                    return false;
                }
        }

        try {
            if (($res = @unserialize($data)) !== false) {
                $result = $res;
                return true;
            }
            if (($res = @unserialize(utf8_encode($data))) !== false) {
                $result = $res;
                return true;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * MySQL compatibility method mysqli_result
     * http://www.php.net/manual/en/class.mysqli-result.php#109782
     *
     * @param mysqli_result $res
     * @param int $row
     * @param int $field
     */
    private function mysqli_result($res, $row, $field = 0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    public function schedulelivesearch($arr, $limit)
    {
        error_log("=======arr============".print_r(is_array($arr['city']), true));
        error_log("=======arr============".print_r(is_array($arr['zip']), true));
        $num_units_min=$arr['num_units_min'];
        $num_units_max=$arr['num_units_max'];
        if (is_array($arr['zip'])) {
            $zip=array_filter($arr['zip']);
            $zip_count=count($zip);
        } else {
            $zip=array();
            $zip_count=count($zip);
        }
        if (is_array($arr['city'])) {
            $city=array_filter($arr['city']);
            $city_count=count($city);
        } else {
            $city=array();
            $city_count=count($city);
        }

        $num_beds_min=$arr['num_bedrooms_min'];
        $num_beds_max=$arr['num_bedrooms_max'];
        $num_baths_min=$arr['num_baths_min'];
        $num_baths_max=$arr['num_baths_max'];
        $num_stories_min=$arr['num_stories_min'];
        $num_stories_max=$arr['num_stories_max'];
        $select_fields="*";
        $table="property";
        $condition = array();
        $where = "";

        if ($num_units_min !='' && $num_units_max =='') {
            $condition[]= '(number_of_units >='.$num_units_min.')' ;
        } elseif ($num_units_min =='' && $num_units_max !='') {
            $condition[]= '(number_of_units <='.$num_units_max.')';
        } elseif (!($num_units_min =='' && $num_units_max =='')) {
            $condition[]=  '(number_of_units >='.$num_units_min.' and number_of_units <='.$num_units_max.')';
        }
        if (!empty($zip) && $zip_count!=0) {
            $condition[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zip)) . '))';
        }
        if (!empty($city) && $city_count!=0) {
            $condition[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $city)) . '"))';
        }
        if ($num_beds_min !='' && $num_beds_max =='') {
            $condition[]= '(bedrooms >='.$num_beds_min.')' ;
        } elseif ($num_beds_min =='' && $num_beds_max !='') {
            $condition[]= '(bedrooms <='.$num_beds_max.')';
        } elseif (!($num_beds_min =='' && $num_beds_max =='')) {
            $condition[]=  '(bedrooms >='.$num_beds_min.' and bedrooms <='.$num_beds_max.')';
        }
        if ($num_baths_min !='' && $num_baths_max =='') {
            $condition[]= '(bathrooms >='.$num_baths_min.')' ;
        } elseif ($num_baths_min =='' && $num_baths_max !='') {
            $condition[]= '(bathrooms <='.$num_baths_max.')';
        } elseif (!($num_baths_min =='' && $num_baths_max =='')) {
            $condition[]=  '(bathrooms >='.$num_baths_min.' and bathrooms <='.$num_baths_max.')';
        }
        if ($num_stories_min !='' && $num_stories_max =='') {
            $condition[]= '(number_of_stories >='.$num_stories_min.')' ;
        } elseif ($num_stories_min =='' && $num_stories_max !='') {
            $condition[]= '(number_of_stories <='.$num_stories_max.')';
        } elseif (!($num_stories_min =='' && $num_stories_max =='')) {
            $condition[]=  '(number_of_stories >='.$num_stories_min.' and number_of_stories <='.$num_stories_max.')';
        }
        if (count($condition) > 0) {
            $where = implode(' AND ', $condition);
        }
        $select='SELECT ' . $select_fields . ' FROM `' . $table . '`';

        $query1 = "SELECT count(id) FROM " . $table . ($where != "" ? " WHERE $where" : "") .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1");
        $totNo = $this->query($query1);
        $rslt = $this->result_array();
        $totNo = $rslt[0]['count(id)'];//die;

        $query = $select  . ($where != "" ? " WHERE $where" : "") .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1");
        $query = $query . ($limit != "" ? $limit : "");
        $result = $this->query($query);

        error_log("==================750========>".print_r($query, true));
        error_log("==================7502========>".print_r($query1, true));
        return array($result , $totNo);
    }

    public function schedulelivesearchcron($arr)
    {
        error_log("=======arr============".print_r(is_array($arr['city']), true));
        error_log("=======arr============".print_r(is_array($arr['zip']), true));
        $num_units_min=$arr['num_units_min'];
        $num_units_max=$arr['num_units_max'];
        if (is_array($arr['zip'])) {
            $zip=array_filter($arr['zip']);
            $zip_count=count($zip);
        } else {
            $zip=array();
            $zip_count=count($zip);
        }
        if (is_array($arr['city'])) {
            $city=array_filter($arr['city']);
            $city_count=count($city);
        } else {
            $city=array();
            $city_count=count($city);
        }
        $num_beds_min=$arr['num_bedrooms_min'];
        $num_beds_max=$arr['num_bedrooms_max'];
        $num_baths_min=$arr['num_baths_min'];
        $num_baths_max=$arr['num_baths_max'];
        $num_stories_min=$arr['num_stories_min'];
        $num_stories_max=$arr['num_stories_max'];
        $select_fields="parcel_number";
        $table="property";
        $condition = array();
        $where = "";

        if ($num_units_min !='' && $num_units_max =='') {
            $condition[]= '(number_of_units >='.$num_units_min.')' ;
        } elseif ($num_units_min =='' && $num_units_max !='') {
            $condition[]= '(number_of_units <='.$num_units_max.')';
        } elseif (!($num_units_min =='' && $num_units_max =='')) {
            $condition[]=  '(number_of_units >='.$num_units_min.' and number_of_units <='.$num_units_max.')';
        }
        if (!empty($zip) && $zip_count!=0) {
            $condition[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zip)) . '))';
        }
        if (!empty($city) && $city_count!=0) {
            $condition[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $city)) . '"))';
        }
        if ($num_beds_min !='' && $num_beds_max =='') {
            $condition[]= '(bedrooms >='.$num_beds_min.')' ;
        } elseif ($num_beds_min =='' && $num_beds_max !='') {
            $condition[]= '(bedrooms <='.$num_beds_max.')';
        } elseif (!($num_beds_min =='' && $num_beds_max =='')) {
            $condition[]=  '(bedrooms >='.$num_beds_min.' and bedrooms <='.$num_beds_max.')';
        }
        if ($num_baths_min !='' && $num_baths_max =='') {
            $condition[]= '(bathrooms >='.$num_baths_min.')' ;
        } elseif ($num_baths_min =='' && $num_baths_max !='') {
            $condition[]= '(bathrooms <='.$num_baths_max.')';
        } elseif (!($num_baths_min =='' && $num_baths_max =='')) {
            $condition[]=  '(bathrooms >='.$num_baths_min.' and bathrooms <='.$num_baths_max.')';
        }
        if ($num_stories_min !='' && $num_stories_max =='') {
            $condition[]= '(number_of_stories >='.$num_stories_min.')' ;
        } elseif ($num_stories_min =='' && $num_stories_max !='') {
            $condition[]= '(number_of_stories <='.$num_stories_max.')';
        } elseif (!($num_stories_min =='' && $num_stories_max =='')) {
            $condition[]=  '(number_of_stories >='.$num_stories_min.' and number_of_stories <='.$num_stories_max.')';
        }
        if (count($condition) > 0) {
            $where = implode(' AND ', $condition);
        }
        $select='SELECT ' . $select_fields . ' FROM `' . $table . '`';

        $query1 = "SELECT count(id) FROM " . $table . ($where != "" ? " WHERE $where" : "") .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1");
        $totNo = $this->query($query1);
        $rslt = $this->result_array();
        $totNo = $rslt[0]['count(id)'];//die;

        $query = $select  . ($where != "" ? " WHERE $where" : "") .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1");

        $result = $this->query($query);

        //error_log("==================750========>".print_r($result,true));
        //error_log("==================7501========>".print_r($query1,true));
        return array($result , $totNo);
    }

    public function getConditionsForCustomSearchResults($search_params_parser, $search_params) {
      $conditions = array();

      $num_units_min = $search_params['num_units_min'];
      $num_units_max = $search_params['num_units_max'];

      $zips = $search_params_parser->getZips();
      $cities = $search_params_parser->getCities();
      $zoning = $search_params_parser->getZoning();
      $exemption = $search_params_parser->getExemption();

      $num_beds_min = $search_params['num_bedrooms_min'];
      $num_beds_max = $search_params['num_bedrooms_max'];

      $num_baths_min = $search_params['num_baths_min'];
      $num_baths_max = $search_params['num_baths_max'];

      $num_stories_min = $search_params['num_stories_min'];
      $num_stories_max = $search_params['num_stories_max'];

      $cost_per_sq_ft_min = $search_params['cost_per_sq_ft_min'];
      $cost_per_sq_ft_max = $search_params['cost_per_sq_ft_max'];

      $lot_area_sq_ft_min = $search_params['lot_area_sq_ft_min'];
      $lot_area_sq_ft_max = $search_params['lot_area_sq_ft_max'];

      $sales_price_min = $search_params['sales_price_min'];
      $sales_price_max = $search_params['sales_price_max'];

      $is_owner_occupied = $search_params['is_owner_occupied'];

      $year_built_min = $search_params['year_built_min'];
      $year_built_max = $search_params['year_built_max'];

      $sales_date_min = $search_params['sales_date_from'];
      $sales_date_max = $search_params['sales_date_to'];

      if ($num_units_min != '' && $num_units_max == '') {
          $conditions[]= '(number_of_units >='.$num_units_min.')' ;
      } elseif ($num_units_min == '' && $num_units_max != '') {
          $conditions[]= '(number_of_units <='.$num_units_max.')';
      } elseif (!($num_units_min =='' && $num_units_max =='')) {
          $conditions[]=  '(number_of_units >='.$num_units_min.' and number_of_units <='.$num_units_max.')';
      }

      if (!empty($zips)) {
        $conditions[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zips)) . '))';
      }

      if (!empty($cities)) {
        $conditions[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $cities)) . '"))';
      }

      if (!empty($zoning)) {
        $conditions[]=  '(zoning IN ("' . implode('","', array_map('strval', $zoning)) . '"))';
      }

      if (!empty($exemption)) {
        $conditions[]=  '(tax_exemption_code IN ("' . implode(',', array_map('strval', $exemption)) . '"))';
      }

      if ($num_beds_min !='' && $num_beds_max =='') {
        $conditions[]= '(bedrooms >='.$num_beds_min.')' ;
      } elseif ($num_beds_min =='' && $num_beds_max !='') {
        $conditions[]= '(bedrooms <='.$num_beds_max.')';
      } elseif (!($num_beds_min =='' && $num_beds_max =='')) {
        $conditions[]=  '(bedrooms >='.$num_beds_min.' and bedrooms <='.$num_beds_max.')';
      }

      if ($is_owner_occupied) {
        $conditions[]=  '(owner_occupied ="'.$is_owner_occupied.'")';
      }

      if ($num_baths_min !='' && $num_baths_max =='') {
        $conditions[]= '(bathrooms >='.$num_baths_min.')' ;
      } elseif ($num_baths_min =='' && $num_baths_max !='') {
        $conditions[]= '(bathrooms <='.$num_baths_max.')';
      } elseif (!($num_baths_min =='' && $num_baths_max =='')) {
        $conditions[]=  '(bathrooms >='.$num_baths_min.' and bathrooms <='.$num_baths_max.')';
      }

      if ($num_stories_min !='' && $num_stories_max =='') {
        $conditions[]= '(number_of_stories >='.$num_stories_min.')' ;
      } elseif ($num_stories_min =='' && $num_stories_max !='') {
        $conditions[]= '(number_of_stories <='.$num_stories_max.')';
      } elseif (!($num_stories_min =='' && $num_stories_max =='')) {
        $conditions[]=  '(number_of_stories >='.$num_stories_min.' and number_of_stories <='.$num_stories_max.')';
      }

      if ($cost_per_sq_ft_min !='' && $cost_per_sq_ft_max =='') {
        $conditions[]= '(cost_per_sq_ft >='.$cost_per_sq_ft_min.')' ;
      } elseif ($cost_per_sq_ft_min =='' && $cost_per_sq_ft_max !='') {
        $conditions[]= '(cost_per_sq_ft <='.$cost_per_sq_ft_max.')';
      } elseif (!($cost_per_sq_ft_min =='' && $cost_per_sq_ft_max =='')) {
        $conditions[]=  '(cost_per_sq_ft >='.$cost_per_sq_ft_min.' and cost_per_sq_ft <='.$cost_per_sq_ft_max.')';
      }

      if ($lot_area_sq_ft_min !='' && $lot_area_sq_ft_max =='') {
        $conditions[]= '(lot_area_sqft >='.$lot_area_sq_ft_min.')' ;
      } elseif ($lot_area_sq_ft_min =='' && $lot_area_sq_ft_max!='') {
        $conditions[]= '(lot_area_sqft <='.$lot_area_sq_ft_max.')';
      } elseif (!($lot_area_sq_ft_min =='' && $lot_area_sq_ft_max =='')) {
        $conditions[]=  '(lot_area_sqft >='.$lot_area_sq_ft_min.' and lot_area_sqft <='.$lot_area_sq_ft_max.')';
      }

      if ($sales_price_min !='' && $sales_price_max =='') {
        $conditions[]= '(sales_price >='.$sales_price_min.')' ;
      } elseif ($sales_price_min =='' && $sales_price_max!='') {
        $conditions[]= '(sales_price <='.$sales_price_max.')';
      } elseif (!($sales_price_min =='' && $sales_price_max =='')) {
        $conditions[]=  '(sales_price >='.$sales_price_min.' and sales_price <='.$sales_price_max.')';
      }

      if ($year_built_min !='' && $year_built_max =='') {
        $conditions[]= '(year_built >="'.$year_built_min.'")' ;
      } elseif ($year_built_min =='' && $year_built_max!='') {
        $conditions[]= '(year_built <="'.$year_built_max.'")';
      } elseif (!($year_built_min =='' && $year_built_max =='')) {
        $conditions[]=  '(year_built >="'.$year_built_min.'" and year_built <="'.$year_built_max.'")';
      }

      if ($sales_date_min !='' && $sales_date_max =='') {
        $sdatefirst=date('Y-m-d', strtotime($sales_date_min));
        $conditions[]= '(sales_date >="'.$sdatefirst.'")' ;
      } elseif ($sales_date_min =='' && $sales_date_max!='') {
        $sdatesecond=date('Y-m-d', strtotime($sales_date_max));
        $conditions[]= '(sales_date <="'.$sdatesecond.'")';
      } elseif (!($sales_date_min =='' && $sales_date_max =='')) {
        $sdatefirst=date('Y-m-d', strtotime($sales_date_min));
        $sdatesecond=date('Y-m-d', strtotime($sales_date_max));
        $conditions[]=  '(sales_date >="'.$sdatefirst.'" and sales_date <="'.$sdatesecond.'")';
      }

      $case_type_filters = $search_params_parser->getCaseTypeFilters();
      if (!empty($case_type_filters)) {
        $case_type_filter_builder = new CaseTypeFilters($case_type_filters);

        $case_types_condition = $case_type_filter_builder->getCaseTypesCondition();
        if (!empty($case_types_condition)) {
          $conditions[] = $case_types_condition;
        }
      }

      return $conditions;
    }

    public function getCustomSearchResults($search_params, $limit)
    {
        $search_params_parser = new SearchParameters($search_params);

        $select_fields = "*";
        $property_table = "property";
        $condition = $this->getConditionsForCustomSearchResults($search_params_parser, $search_params);

        $where = "";

        if (count($condition) > 0) {
          $where = implode(' AND ', $condition);
        }

        $select ='SELECT DISTINCT p.parcel_number FROM property AS p
          JOIN property_cases AS c
          ON p.parcel_number=c.APN
          JOIN property_inspection AS pi
          ON pi.lblCaseNo=c.case_id';

        $query = $select . ($where != "" ? " WHERE $where" : "") . ";";

        $this->query($query);


        if (empty($this->result_array())) {
          $apns_to_return_expression = "\"\"";
        } else {
          $apns_to_return_expression = implode(
            ",",
            array_map(
              create_function('$entry', 'return $entry["parcel_number"];'),
              array_splice($this->result_array(), 0, $limit)
            )
          );
        }

        // set total count
        $resultcount = count($this->result_array());

        $query = "SELECT * FROM property WHERE parcel_number in ($apns_to_return_expression);";

        $this->query($query);

        return array($this->result_array(), $resultcount);
    }

    public function getCaseStatusTypes() {
      $case_type_statuses = array();

      $query = "SELECT DISTINCT `staus`, `case_type_id` FROM property_inspection GROUP BY `case_type_id`, `staus`";

      $this->query($query);

      foreach ($this->result_array() as $entry) {
        $status_type = $entry['staus'];
        $case_type_id = $entry['case_type_id'];

        $case_type_statuses[$case_type_id][] = $status_type;
      }

      return $case_type_statuses;
    }

    public function leadbatchdata($arr)
    {
        //$case_type=$arr['casetype'];
        $casetyp= '(T1.parcel_number IN (' . implode(',', array_map('strval', $arr)) . '))';
        //error_log("======casetype===================>".print_r($casetyp,true));
        //$query ='Select *  FROM property where '.$casetyp.' ';
        //$query ='SELECT T1.parcel_number, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent, T2.census_tract, T2.address, T2.rent_registration_number, T2.exemption, T2.rentoffice, T2.coderegionalaea, T2.council_district FROM property as T1 JOIN property_detail as T2 ON T1.parcel_number = T2.apn WHERE  '.$casetyp.'';
        $query ='SELECT T1.id,T1.parcel_number,T1.property_status, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent FROM property as T1  WHERE  '.$casetyp.'';

        return $this->query($query);
    }

    public function leadbatchdatasearch($arr)
    {
        $casetyp= '( parcel_number IN (' . implode(',', array_map('strval', $arr)) . '))';
        $query ='Select *  FROM property where '.$casetyp.' ';
        return $this->query($query);
    }

    public function getexpotresult($id)
    {
        //$query ='Select *  FROM property';
        $query ='SELECT T1.parcel_number, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent, T2.census_tract, T2.address, T2.rent_registration_number, T2.exemption, T2.rentoffice, T2.coderegionalaea, T2.council_district FROM property as T1 JOIN property_detail as T2 ON T1.parcel_number = T2.apn WHERE T1.parcel_number = '.$id.'';
        //error_log("-----exportresult--------------->".print_r($query,true));

        return $this->query($query);
    }

    public function getschedulealllist()
    {
        $query ='SELECT * FROM lead_scheduler where status="pending" and scheduleat <=NOW()';
        return $this->query($query);
    }

    public function getapnlistcron()
    {
        $query ='SELECT id,data,schedule_date FROM schedule_search where cstatus="0" and schedule_date <=NOW() ORDER BY id ASC';
        return $this->query($query);
    }

    public function getschedulearunlist()
    {
        $query ='SELECT count(*) as count FROM lead_scheduler where status="running" and scheduleat <=NOW()';
        return $this->query($query);
    }

    public function getapnlistall()
    {
        $query="SELECT parcel_number,site_address_zip FROM  `property` WHERE `site_address_zip` NOT IN(90001,90002,90003,90004,90005,90006,90007,90008,90010,90011,90012,90013,90014,90015,90016,90017,90018,90019,90020,90021,90023,90024,90025,90026,90027,90028,90029,90031,90032,90033,90034,90035,90036,90037,90038,90039,90041,90042,90043,90044,90045,90046,90047,90048,90049,90056,90057,90058,90059,90061,90062,90063,90064,90065,90066,90067,90068,90069,90071,90077,90089,90094,90095,90210,90211,90212,90230,90232,90245,90247,90248,90272,90290,90291,90292,90293,90302,90402,90501,90502,90710,90717,90731,90732,90744,90810,91040,91042,91214,91303,91304,91306,91307,91311,91316,31324,91325,91326,91330,91331,91335,91340,91342,91343,91344,91345,91352,91356,91364,91367,91401,91402,91403,91405,91406,91411,91423,91436,91504,91505,91601,91602,91604,91605,91606,91607,91608) ORDER BY id ASC";
        return $this->query($query);
    }
}
