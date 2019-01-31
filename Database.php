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
    static $queries = array();

    /**
     * Database() constructor
     *
     * @param string $database_name
     * @param string $username
     * @param string $password
     * @param string $host
     * @throws DatabaseException
     */
    function __construct($database_name, $username, $password, $host = 'localhost')
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
        error_log("==========selectquery=========>".print_r($query,true));
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
    function table_exists($name)
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
    function insert($table, $fields = array(), $appendix = false, $ret = false)
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
    function update($table, $fields = array(), $where = array(), $limit = false, $order = false)
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
    function delete($table, $where = array(), $where_mode = "AND", $limit = false, $order = false)
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
            if (!mb_detect_encoding($str, 'UTF-8', TRUE)) {
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
            case 's' :
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
                break;
            case 'a' :
            case 'O' :
                if (!preg_match("/^{$token}:[0-9]+:/s", $data)) {
                    return false;
                }
                break;
            case 'b' :
            case 'i' :
            case 'd' :
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

    function   schedulelivesearch($arr, $limit){
        error_log("=======arr============".print_r(is_array($arr['city']),true));
        error_log("=======arr============".print_r(is_array($arr['zip']),true));
        $nouf=$arr['nouf'];
        $nout=$arr['nout'];
        if(is_array($arr['zip'])){
        $zip=array_filter($arr['zip']);
        $zicount=count($zip);
        }
        else {
            $zip=array();
            $zicount=count($zip);
        
        }
        if(is_array($arr['city'])){
        $city=array_filter($arr['city']);
        $ccount=count($city);
        }else {

            $city=array();
            $ccount=count($city);

        }
        $fmlytype=$arr['fmlytype'];
        $sfmlytype=$arr['sfmlytype'];
        $nbedf=$arr['nbedf'];
        $nbedt=$arr['nbedt'];
        $nbathf=$arr['nbathf'];
        $nbatht=$arr['nbatht'];
        $nstrf=$arr['nstrf'];
        $nstrt=$arr['nstrt'];
        $select_fields="*";
        $table="property";
        $condition = array();
        $where = "";
        
        if($nouf !='' && $nout ==''){
            $condition[]= '(number_of_units >='.$nouf.')' ;
           }
           else if($nouf =='' && $nout !='' ){
            $condition[]= '(number_of_units <='.$nout.')';
           }
           else if( !($nouf =='' && $nout =='') ){
            $condition[]=  '(number_of_units >='.$nouf.' and number_of_units <='.$nout.')';
           }
        if(!empty($zip) && $zicount!=0){
            $condition[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zip)) . '))';
           }
        if(!empty($city) && $ccount!=0){
            $condition[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $city)) . '"))';
           }
        if($nbedf !='' && $nbedt ==''){
            $condition[]= '(bedrooms >='.$nbedf.')' ;
           }
           else if($nbedf =='' && $nbedt !='' ){
            $condition[]= '(bedrooms <='.$nbedt.')';
           }
           else if( !($nbedf =='' && $nbedt =='') ){
            $condition[]=  '(bedrooms >='.$nbedf.' and bedrooms <='.$nbedt.')';
           }
       if($nbathf !='' && $nbatht ==''){
            $condition[]= '(bathrooms >='.$nbathf.')' ;
           }
           else if($nbathf =='' && $nbatht !='' ){
            $condition[]= '(bathrooms <='.$nbatht.')';
           }
           else if( !($nbathf =='' && $nbatht =='') ){
            $condition[]=  '(bathrooms >='.$nbathf.' and bathrooms <='.$nbatht.')';
           }
        if($nstrf !='' && $nstrt ==''){
            $condition[]= '(number_of_stories >='.$nstrf.')' ;
           }
           else if($nstrf =='' && $nstrt !='' ){
            $condition[]= '(number_of_stories <='.$nstrt.')';
           }
           else if( !($nstrf =='' && $nstrt =='') ){
            $condition[]=  '(number_of_stories >='.$nstrf.' and number_of_stories <='.$nstrt.')';
           }
           if (count($condition) > 0)
           {
             $where = implode(' AND ', $condition);
            }
           $select='SELECT ' . $select_fields . ' FROM `' . $table . '`';

        $query1 = "SELECT count(id) FROM " . $table . ($where != "" ? " WHERE $where" : "" ) .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1" );
        $totNo = $this->query($query1);
        $rslt = $this->result_array();
        $totNo = $rslt[0]['count(id)'];//die;
         
        $query = $select  . ($where != "" ? " WHERE $where" : "" ) .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1" );
        $query = $query . ($limit != "" ? $limit : "");
        $result = $this->query($query);

        error_log("==================750========>".print_r($query,true));
        error_log("==================7502========>".print_r($query1,true));
        return array($result , $totNo);

    }

    function   schedulelivesearchcron($arr){
        error_log("=======arr============".print_r(is_array($arr['city']),true));
        error_log("=======arr============".print_r(is_array($arr['zip']),true));
        $nouf=$arr['nouf'];
        $nout=$arr['nout'];
        if(is_array($arr['zip'])){
        $zip=array_filter($arr['zip']);
        $zicount=count($zip);
        }
        else {
            $zip=array();
            $zicount=count($zip);
        
        }
        if(is_array($arr['city'])){
        $city=array_filter($arr['city']);
        $ccount=count($city);
        }else {

            $city=array();
            $ccount=count($city);

        }
        $fmlytype=$arr['fmlytype'];
        $sfmlytype=$arr['sfmlytype'];
        $nbedf=$arr['nbedf'];
        $nbedt=$arr['nbedt'];
        $nbathf=$arr['nbathf'];
        $nbatht=$arr['nbatht'];
        $nstrf=$arr['nstrf'];
        $nstrt=$arr['nstrt'];
        $select_fields="parcel_number";
        $table="property";
        $condition = array();
        $where = "";
        
        if($nouf !='' && $nout ==''){
            $condition[]= '(number_of_units >='.$nouf.')' ;
           }
           else if($nouf =='' && $nout !='' ){
            $condition[]= '(number_of_units <='.$nout.')';
           }
           else if( !($nouf =='' && $nout =='') ){
            $condition[]=  '(number_of_units >='.$nouf.' and number_of_units <='.$nout.')';
           }
        if(!empty($zip) && $zicount!=0){
            $condition[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zip)) . '))';
           }
        if(!empty($city) && $ccount!=0){
            $condition[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $city)) . '"))';
           }
        if($nbedf !='' && $nbedt ==''){
            $condition[]= '(bedrooms >='.$nbedf.')' ;
           }
           else if($nbedf =='' && $nbedt !='' ){
            $condition[]= '(bedrooms <='.$nbedt.')';
           }
           else if( !($nbedf =='' && $nbedt =='') ){
            $condition[]=  '(bedrooms >='.$nbedf.' and bedrooms <='.$nbedt.')';
           }
       if($nbathf !='' && $nbatht ==''){
            $condition[]= '(bathrooms >='.$nbathf.')' ;
           }
           else if($nbathf =='' && $nbatht !='' ){
            $condition[]= '(bathrooms <='.$nbatht.')';
           }
           else if( !($nbathf =='' && $nbatht =='') ){
            $condition[]=  '(bathrooms >='.$nbathf.' and bathrooms <='.$nbatht.')';
           }
        if($nstrf !='' && $nstrt ==''){
            $condition[]= '(number_of_stories >='.$nstrf.')' ;
           }
           else if($nstrf =='' && $nstrt !='' ){
            $condition[]= '(number_of_stories <='.$nstrt.')';
           }
           else if( !($nstrf =='' && $nstrt =='') ){
            $condition[]=  '(number_of_stories >='.$nstrf.' and number_of_stories <='.$nstrt.')';
           }
           if (count($condition) > 0)
           {
             $where = implode(' AND ', $condition);
            }
           $select='SELECT ' . $select_fields . ' FROM `' . $table . '`';

        $query1 = "SELECT count(id) FROM " . $table . ($where != "" ? " WHERE $where" : "" ) .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1" );
        $totNo = $this->query($query1);
        $rslt = $this->result_array();
        $totNo = $rslt[0]['count(id)'];//die;
         
        $query = $select  . ($where != "" ? " WHERE $where" : "" ) .($where != "" ? " and (impstatus=1)" : " WHERE impstatus=1" );
        
        $result = $this->query($query);

        //error_log("==================750========>".print_r($result,true));
        //error_log("==================7501========>".print_r($query1,true));
        return array($result , $totNo);

    }

    function searchresult($arr, $limit){
       $nouf=$arr['nouf'];
       $nout=$arr['nout'];
    if(is_array($arr['zip'])){
        $zip=array_filter($arr['zip']);
        $zicount=count($zip);
        }
    else {
            $zip=array();
            $zicount=count($zip);
    }
    if(is_array($arr['city'])){
        $city=array_filter($arr['city']);
        $ccount=count($city);
    }else {

            $city=array();
            $ccount=count($city);

    }
    if(is_array($arr['zoning'])){
        $zoning=array_filter($arr['zoning']);
        $zocount=count($zoning);
    }else {

            $zoning=array();
            $zocount=count($zoning);

    }

    if(is_array($arr['exemption'])){
        $exemption=array_filter($arr['exemption']);
        $ecount=count($exemption);
    }else {

            $exemption=array();
            $ecount=count($exemption);

    }
    if(is_array($arr['casetype'])){
        $casetype=array_filter($arr['casetype']);
        $casecount=count($casetype);
    }else {

            $casetype=array();
            $casecount=count($casetype);

    }

       $nbedf=$arr['nbedf'];
       $nbedt=$arr['nbedt'];
       $nbathf=$arr['nbathf'];
       $nbatht=$arr['nbatht'];
       $nstrf=$arr['nstrf'];
       $nstrt=$arr['nstrt'];
       $cpsf=$arr['cpsf'];
       $cpst=$arr['cpst'];
       $lasqf=$arr['lasqf'];
       $lasqt=$arr['lasqt'];
       $sprf=$arr['sprf'];
       $ooc=$arr['ooc'];
       $sprt=$arr['sprt'];
       $ybrf=$arr['ybrf'];
       $ybrt=$arr['ybrt'];
       $sdrf=$arr['sdrf'];
       $sdrt=$arr['sdrt'];
       $fmlytype=$arr['fmlytype'];
       $sfmlytype=$arr['sfmlytype'];
       $count=count($arr);
       $select_fields="*";
       $table="property";
       $table12="property_cases";
       $condition = array();
       $where = "";
       $bool=false;
       $case1=false;
       $case2=false;
       $case3=false;
       $mergapn=array();
       $listapn=array(); 
       $tabledata=array();
       error_log("===========zicount==============>".print_r($zicount,true));
       if($nouf !='' && $nout ==''){
        $condition[]= '(number_of_units >='.$nouf.')' ;
       }
       else if($nouf =='' && $nout !='' ){
        $condition[]= '(number_of_units <='.$nout.')';
       }
       else if( !($nouf =='' && $nout =='') ){
        $condition[]=  '(number_of_units >='.$nouf.' and number_of_units <='.$nout.')';
       }
       if(!empty($zip) && $zicount!=0){
        $condition[]=  '(site_address_zip IN (' . implode(',', array_map('strval', $zip)) . '))';
       }
       if(!empty($city) && $ccount!=0){
        $condition[]=  '(site_address_city_state IN ("' . implode('","', array_map('strval', $city)) . '"))';
       }
       if(!empty($zoning) && $zocount!=0){
        $condition[]=  '(zoning IN ("' . implode('","', array_map('strval', $zoning)) . '"))';
       }
       if(!empty($casetype) && $casecount!=0){
       $bool = true;

        $casename=array();

        $arrapsh=array();

        $lmergapn=array();

        $datalist=array();

        $my_array=array();

        $cdatesql=array();

        $newarray=array();

        $order=array();
        foreach($casetype as $key =>$vlac){
        
         if($vlac['casetype'] && !$vlac['cdate'] && !$vlac['ctime']){



                $order[]=1;

                $case1=true;

                $casename[]=$vlac['casetype'];

                $cdate=date('Y-m-d');

                $ctime[]=date('Y-m-d'); 
                //$cdatesql[]='select * from (select * from property_inspection order by STR_TO_DATE(date, "%m/%d/%Y %h:%i:%s %p") ASC) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';

                //$cdatesql[]='select * from (select * from property_inspection order by id desc) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';

            }else if ($vlac['casetype'] && $vlac['cdate']  ){

                $cdate=date('Y-m-d',strtotime($vlac['cdate']));

                $ctime[]=date('Y-m-d',strtotime($vlac['cdate']));

                $casename[]=$vlac['casetype'];

                $order[]=2;
                //$cdatesql[]='select * from (select * from property_inspection order by STR_TO_DATE(date, "%m/%d/%Y %h:%i:%s %p") ASC) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';
                //$cdatesql[]='select * from (select * from property_inspection order by id desc) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';                   

            }

            else if($vlac['casetype']  && $vlac['ctime']){

                $time= $vlac['ctime'];

                $cdate=date('Y-m-d');

                $order[]=3;

                $casename[]=$vlac['casetype'];

                $newdate = strtotime ( "-$time day" , strtotime ( $cdate ) ) ;

                $ctime[]=date('Y-m-d',$newdate);
               // $cdatesql[]='select * from (select * from property_inspection order by STR_TO_DATE(date, "%m/%d/%Y %h:%i:%s %p") ASC) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';
                //$cdatesql[]='select * from (select * from property_inspection order by id desc) as temp left join property_cases on temp.lblCaseNo=property_cases.case_id where  temp.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'") and property_cases.case_date="" group by temp.lblCaseNo, temp.APN';
               
                

            }
	    $cdatesql[]='select * from property_inspection as pi left join property_cases as pc
                on pi.lblCaseNo=pc.case_id where  pi.case_type_id=(select id from case_type_master where name="'.$vlac['casetype'].'")
                and pc.case_date="" group by pi.lblCaseNo, pi.APN order by STR_TO_DATE(pi.date, "%m/%d/%Y %h:%i:%s %p") ASC'; 
               

        }


        error_log("==============valuelist==============>".print_r($cdatesql,true));
        

        foreach($cdatesql as $ky=>$csql){

           $newarray=$this->getarraylist($csql);

           

           foreach ($newarray as $key => $value) 

           {



            foreach ($value as $k => $valuelist) {





               



               if($order[$ky]==1){

                error_log("==============valuelist==============>".print_r($ctime[$ky],true));



                if(strtotime($value['date'])<=strtotime($ctime[$ky])){



                    if($k=='APN'){

                        $listapn[]=$value[$k];



                 }



                }





                }else if($order[$ky]==2){

                    error_log("==============valuelist==============>".print_r($ctime[$ky],true));

                    if(strtotime($value['date'])<=strtotime($ctime[$ky])){



                        if($k=='APN'){

                            $listapn[]=$value[$k];

    

                     }

    

                    }





                } else if($order[$ky]==3){



                    if(strtotime($value['date'])>=strtotime($ctime[$ky])){



                        if($k=='APN'){

                            $listapn[]=$value[$k];

    

                     }

    

                    }



                }

               

              

            }

            

           }

        }
 
         if($case1)$condition[]='(property_cases.case_type IN ("' . implode('","', array_map('strval',$casename)) . '")) AND (property.parcel_number IN ("' . implode('","', array_map('strval',$listapn)) . '")) AND ( property_cases.case_date="" ) AND (property.impstatus =0) GROUP BY property_cases.APN '; 
        else  $condition[]='(property.parcel_number IN ("' . implode('","', array_map('strval',$listapn)) . '")) AND (property_cases.case_type IN ("' . implode('","', array_map('strval',$casename)) . '"))  AND ( property_cases.case_date="" ) AND (property.impstatus =0) GROUP BY property_cases.APN '; 
      
    

      //        $condition[]='(property_cases.case_type IN ("' . implode('","', array_map('strval', $casetype)) . '")) AND ( property_cases.case_date="" or DATE_FORMAT(STR_TO_DATE(`case_date`, "%m/%d/%Y"), "%Y-%m-%d") >= CURDATE()) AND (property.impstatus =0)  GROUP BY property_cases.APN '; 
       }
       if(!empty($exemption) && $ecount !=0){
        $condition[]=  '(tax_exemption_code IN ("' . implode(',', array_map('strval', $exemption)) . '"))';
       }
       if($nbedf !='' && $nbedt ==''){
        $condition[]= '(bedrooms >='.$nbedf.')' ;
       }
       else if($nbedf =='' && $nbedt !='' ){
        $condition[]= '(bedrooms <='.$nbedt.')';
       }
       else if( !($nbedf =='' && $nbedt =='') ){
        $condition[]=  '(bedrooms >='.$nbedf.' and bedrooms <='.$nbedt.')';
       }
       if($ooc){

        $condition[]=  '(owner_occupied ="'.$ooc.'")';
       }

      if($nbathf !='' && $nbatht ==''){
        $condition[]= '(bathrooms >='.$nbathf.')' ;
       }
       else if($nbathf =='' && $nbatht !='' ){
        $condition[]= '(bathrooms <='.$nbatht.')';
       }
       else if( !($nbathf =='' && $nbatht =='') ){
        $condition[]=  '(bathrooms >='.$nbathf.' and bathrooms <='.$nbatht.')';
       }
       if($nstrf !='' && $nstrt ==''){
        $condition[]= '(number_of_stories >='.$nstrf.')' ;
       }
       else if($nstrf =='' && $nstrt !='' ){
        $condition[]= '(number_of_stories <='.$nstrt.')';
       }
       else if( !($nstrf =='' && $nstrt =='') ){
        $condition[]=  '(number_of_stories >='.$nstrf.' and number_of_stories <='.$nstrt.')';
       }
       if($cpsf !='' && $cpst ==''){
        $condition[]= '(cost_per_sq_ft >='.$cpsf.')' ;
       }
       else if($cpsf =='' && $cpst !='' ){
        $condition[]= '(cost_per_sq_ft <='.$cpst.')';
       }
       else if( !($cpsf =='' && $cpst =='') ){
        $condition[]=  '(cost_per_sq_ft >='.$cpsf.' and cost_per_sq_ft <='.$cpst.')';
       }
       if($lasqf !='' && $lasqt ==''){
        $condition[]= '(lot_area_sqft >='.$lasqf.')' ;
       }
       else if($lasqf =='' && $lasqt!='' ){
        $condition[]= '(lot_area_sqft <='.$lasqt.')';
       }
       else if( !($lasqf =='' && $lasqt =='') ){
        $condition[]=  '(lot_area_sqft >='.$lasqf.' and lot_area_sqft <='.$lasqt.')';
       }
       if($sprf !='' && $sprt ==''){
        $condition[]= '(sales_price >='.$sprf.')' ;
       }
       else if($sprf =='' && $sprt!='' ){
        $condition[]= '(sales_price <='.$sprt.')';
       }
       else if( !($sprf =='' && $sprt =='') ){
        $condition[]=  '(sales_price >='.$sprf.' and sales_price <='.$sprt.')';
       }

       if($ybrf !='' && $ybrt ==''){
        $condition[]= '(year_built >="'.$ybrf.'")' ;
       }
       else if($ybrf =='' && $ybrt!='' ){
        $condition[]= '(year_built <="'.$ybrt.'")';
       }
       else if( !($ybrf =='' && $ybrt =='') ){
        $condition[]=  '(year_built >="'.$ybrf.'" and year_built <="'.$ybrt.'")';
       }

       if($sdrf !='' && $sdrt ==''){
        $sdatefirst=date('Y-m-d',strtotime($sdrf));   
        $condition[]= '(sales_date >="'.$sdatefirst.'")' ;
       }
       else if($sdrf =='' && $sdrt!='' ){
        $sdatesecond=date('Y-m-d',strtotime($sdrt));      
        $condition[]= '(sales_date <="'.$sdatesecond.'")';
       }
       else if(!($sdrf =='' && $sdrt =='') ){
        $sdatefirst=date('Y-m-d',strtotime($sdrf));   
        $sdatesecond=date('Y-m-d',strtotime($sdrt));      
        $condition[]=  '(sales_date >="'.$sdatefirst.'" and sales_date <="'.$sdatesecond.'")';
       }
       if($bool==false){
       $condition[]=  '(impstatus =0)';
       }


       
       if (count($condition) > 0)
       {
         $where = implode(' AND ', $condition);
        }

        
      if($bool) $select='SELECT ' . $select_fields . ' FROM `' . $table . '` left join property_cases on property.parcel_number = property_cases.APN  '; 
      else $select='SELECT ' . $select_fields . ' FROM `' . $table . '`'; 

     //if($bool) $query1 = "SELECT count(property.id) as counts FROM " . $table.','.$table12. ($where != "" ? " WHERE $where" : "");
     // else $query1 = "SELECT count($table.id) as counts FROM " . $table . ($where != "" ? " WHERE $where" : "");		
     // $query1 = "SELECT count(id) FROM " . $table . ($where != "" ? " WHERE $where" : "");
     if($bool) $query1 ="SELECT * FROM " . $table.' left join property_cases on property.parcel_number = property_cases.APN '. ($where != "" ? " WHERE $where" : ""); 
     else $query1 = "SELECT count($table.id) as counts FROM " . $table . ($where != "" ? " WHERE $where" : "");

      $totNo = $this->query($query1);
      $rslt = $this->result_array();
       if($bool) $resultcount = count($rslt);
      else $resultcount = $rslt[0]['counts'];
   //    $totNo = $rslt[0]['counts'];//die;


      $query = $select  . ($where != "" ? " WHERE $where" : "");
           
       $query = $query . ($limit != "" ? $limit : "");
      $result = $this->query($query);
      
       //error_log("-----query22--------------->".print_r($query,true));
       return array($result ,$resultcount);
       
       
    }
    
    function leadbatchdata($arr){
        //$casetype=$arr['casetype'];
        $casetyp= '(T1.parcel_number IN (' . implode(',', array_map('strval', $arr)) . '))';
         //error_log("======casetype===================>".print_r($casetyp,true));
        //$query ='Select *  FROM property where '.$casetyp.' ';
//$query ='SELECT T1.parcel_number, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent, T2.census_tract, T2.address, T2.rent_registration_number, T2.exemption, T2.rentoffice, T2.coderegionalaea, T2.council_district FROM property as T1 JOIN property_detail as T2 ON T1.parcel_number = T2.apn WHERE  '.$casetyp.'';
 $query ='SELECT T1.id,T1.parcel_number,T1.property_status, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent FROM property as T1  WHERE  '.$casetyp.'';

        return $this->query($query);
  
      }

function leadbatchdatasearch($arr){
 $casetyp= '( parcel_number IN (' . implode(',', array_map('strval', $arr)) . '))';
 $query ='Select *  FROM property where '.$casetyp.' ';
 return $this->query($query);
}


	 function getexpotresult($id){
        //$query ='Select *  FROM property';
        $query ='SELECT T1.parcel_number, T1.owner_name2, T1.owner1_first_name, T1.owner1_middle_name, T1.owner1_last_name, T1.owner1_spouse_first_name, T1.owner2_first_name, T1.owner2_middle_name, T1.owner2_last_name, T1.owner2_spouse_first_name, T1.site_address_street_prefix, T1.street_number, T1.street_name, T1.site_address_zip, T1.site_address_city_state, T1.full_mail_address, T1.mail_address_city_state, T1.mail_address_zip, T1.site_address_unit_number, T1.use_code, T1.use_code_descrition, T1.building_area, T1.bedrooms, T1.bathrooms, T1.tract, T1.lot_area_sqft, T1.lot_area_acres, T1.year_built, T1.pool, T1.year_built, T1.garage_type, T1.sales_date, T1.sales_price, T1.sales_price_code, T1.sales_document_number, T1.tax_exemption_code, T1.fireplace, T1.number_of_units, T1.number_of_stories, T1.owner_occupied, T1.zoning, T1.mail_flag, T1.cost_per_sq_ft, T1.total_assessed_value, T1.total_market_value, T1.assessed_improvement_value, T1.assessed_land_value,T1.assessed_improve_percent, T2.census_tract, T2.address, T2.rent_registration_number, T2.exemption, T2.rentoffice, T2.coderegionalaea, T2.council_district FROM property as T1 JOIN property_detail as T2 ON T1.parcel_number = T2.apn WHERE T1.parcel_number = '.$id.'';
		//error_log("-----exportresult--------------->".print_r($query,true));

		return $this->query($query);
        }

    function getschedulealllist(){

        $query ='SELECT * FROM lead_scheduler where status="pending" and scheduleat <=NOW()';
        return $this->query($query);
    }

    function getapnlistcron(){
        $query ='SELECT id,data,schedule_date FROM schedule_search where cstatus="0" and schedule_date <=NOW() ORDER BY id ASC';
        return $this->query($query);

    }

    function getschedulearunlist(){

        $query ='SELECT count(*) as count FROM lead_scheduler where status="running" and scheduleat <=NOW()';
        return $this->query($query);
    }

    function getapnlistall(){

        $query="SELECT parcel_number,site_address_zip FROM  `property` WHERE `site_address_zip` NOT IN(90001,90002,90003,90004,90005,90006,90007,90008,90010,90011,90012,90013,90014,90015,90016,90017,90018,90019,90020,90021,90023,90024,90025,90026,90027,90028,90029,90031,90032,90033,90034,90035,90036,90037,90038,90039,90041,90042,90043,90044,90045,90046,90047,90048,90049,90056,90057,90058,90059,90061,90062,90063,90064,90065,90066,90067,90068,90069,90071,90077,90089,90094,90095,90210,90211,90212,90230,90232,90245,90247,90248,90272,90290,90291,90292,90293,90302,90402,90501,90502,90710,90717,90731,90732,90744,90810,91040,91042,91214,91303,91304,91306,91307,91311,91316,31324,91325,91326,91330,91331,91335,91340,91342,91343,91344,91345,91352,91356,91364,91367,91401,91402,91403,91405,91406,91411,91423,91436,91504,91505,91601,91602,91604,91605,91606,91607,91608) ORDER BY id ASC";
        return $this->query($query);
    }
    function getarraylist($sqlquery){

        $db = Database::instance();

        $db->query($sqlquery);

        $narray=array();



        $result = $db->result_array();

        foreach ($result as $key => $val) {



            $narray[] = array('APN'=>$val['APN'],'date'=>$val['date']);

            

        }

     

       return $narray;

    }


}


