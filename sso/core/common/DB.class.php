<?php

namespace sso\core\common;

use sso\core\common as cm;
use sso\core\config as sc;
use sso\core\db\drivers as sd;

class DB //extends Singleton
{
    protected $dbDSN = NULL;
    protected $db_type = NULL;
    protected $db_username = NULL;
    protected $db_password = NULL;
    protected $db_hostname = NULL;
    protected $db_hostport = NULL;
    protected $db_database = NULL; 

    protected $links = array();
    protected $link = NULL;

    protected $sql = NULL;
    protected $table = NULL;
    protected $field = '*';
    protected $set = NULL;
    protected $where = NULL;
    protected $order = NULL;
    protected $limit = NULL;
    protected $and_where = array();
    protected $or_where = array();
    protected $value = NULL;

    protected function init($dbDSN='')
    {
        $dbDSN or $dbDSN = sc\Config::$dbDSN;
        $this->dbDSN = $dbDSN;

        $dbArgs = parse_url($dbDSN);
        if(count($dbArgs) !== 6)
        {
            Log::error('Database DSN error: '+$dbDSN);
            die('DB DSN ERROR!');
        }
        $this->db_type = $dbArgs['scheme'];
        $this->db_username = $dbArgs['user'];
        $this->db_password = $dbArgs['pass'];
        $this->db_hostname = $dbArgs['host'];
        $this->db_hostport = $dbArgs['port'];
        $this->db_database = trim($dbArgs['path'], '/');
    }

    public static function getInstance()
    {
        $link = new sd\Mysqli();
        return $link;
    }

    public function go()
    {
        return $this->get_all($this->sql);
    }

    public function find($tbl, $field, $val)
    {
        return $this->table($tbl)
                    ->where($field."='".$val."'")
                    ->select();
    }

    protected function insert()
    {
        $this->table($tbl)
             ->field($field)
             ->value($value)
             ->save();
    }

    public function replace($tbl, $mapps)
    {
        $this->table(strtolower($tbl));

        $fields = array_keys($mapps);
        $vals = array_values($mapps);

        $fields = implode(', ', $fields);
        //$vals = array_filter($vals, create_function('$v', 'return "\'$v\'";'));
        $vals = implode("', '", $vals);

        $this->sql = "REPLACE INTO $this->table($fields) VALUES ('$vals')";
        return $this->save();
    }

    public function get_field_names($tbl)
    {
        $this->table(strtolower($tbl));
        $sql = "desc $this->table";
        $fields = $this->get_columns($sql);
        return $fields;
    }

    public function delete($tbl, $mapps)
    {
        $this->table(strtolower($tbl));
        $fields = array_keys($mapps);
        $vals = array_values($mapps);
        
        $fs = count($fields);
        for($i = 0; $i < $fs; $i++)
        {
            $this->and_where[] = $fields[$i]."='".$vals[$i]."'";
        }
        
        $this->sql = "DELETE FROM $this->table WHERE ";
        $this->sql .= implode(' AND ', $this->and_where); 

        $this->del($this->sql);
    }

    protected function select()
    {
        $field = $this->field;
        if(is_array($this->field))
            $field = implode(', ', $this->field);

        $this->sql = "SELECT ".$field;
        $this->sql .= " FROM ".$this->table;

        //where
        $where = '';
        if($this->where)
            $where = $this->where;
        if(count($this->and_where))
            $where .= implode(' AND ', $this->and_where);
        if(count($this->or_where))
            $where .= implode(' OR ', $this->or_where);
        if($where)
            $where = " WHERE ".$where;

        //order
        $order = '';
        if(count($this->order))
            $order .= implode(', ', $this->order);
        else
            $order .= $this->order;
        if($order)
            $order = " ORDER BY ".$order;

        //limit
        $limit = '';
        if($this->limit)
            $limit .= " LIMIT ".$this->limit;

        $this->sql .= $where.$order.$limit;

        return $this->go();
    }

    protected function table($tbl)
    {
        if(empty($tbl))
            return false;
        $this->table = strtolower($tbl);
        return $this;
    }

    protected function field($field)
    {
        $this->field = array();
        if(is_array($field))
            array_merge($this->field, $field);
        else
            array_push($this->field, $field);
        return $this;
    }

    protected function where($where)
    {
        $this->where = '';
        if($where != "=''")
            $this->where = $where;
        return $this;
    }

    protected function and_where($where)
    {
        array_push($this->and_where, $where);
        return $this;
    }

    protected function or_where($where)
    {
        array_push($this->or_where, $where);
        return $this;
    }

    protected function set($kv)
    {
        if(is_array($kv))
            array_merge($this->set, $kv);
        else
            array_push($this->set, $kv);
        return $this;
    }

    protected function order($ors)
    {
        if(is_array($ors))
            array_merge($this->order, $ors);
        else
            array_push($this->order, $ors);
        return $this;
    }

    protected function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    
}

?>
