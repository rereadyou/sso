<?php

namespace sso\core\db\drivers;
use sso\core\common as cm;

class Mysqli extends cm\DB
{
    public function __construct()
    {
        $this->init();
        $this->connect();
    }

    public function __destruct()
    {
    }

    public function connect()
    {
        $link = new \mysqli($this->db_hostname, 
                           $this->db_username,    
                           $this->db_password,
                           $this->db_database,
                           $this->db_hostport);
        if(!$link)
        {
            Log::error(mysqli_connect_error());
            exit;
        }

        array_push($this->links, $link);
        $this->link = $link;
    }

    public function close()
    {
        foreach($links as $link)
        {
            $link->close();
        }
    }

    public function query($sql)
    {
        echo $sql;
        if($result = $this->link->query($sql))
        {
            return $result->fetch_array();
        }
        cm\Log::error('Sql error: #'.$sql.'#->'.$this->link->error);
        return false;
    }

    public function get_all($sql)
    {
        if(empty($sql))
            return false;
        $dbArr = array();
        if($result = $this->link->query($sql))
        {
            while($app = $result->fetch_array())
            {
                array_push($dbArr, $app);
            }
        }
        return $dbArr;
    }

    public function get_columns($sql)
    {
        if(empty($sql))
            return false;
        $arr = array();
        if($result = $this->link->query($sql))
        {
            while($app = $result->fetch_row())
            {
                array_push($arr, array_shift($app));
            }
        }
        return $arr;
    }

    public function insert($sql)
    {
        if(!$result = $this->link->query($sql))
            cm\Log::error('Insert failed: '.$sql);
    }
    
    public function get_row($sql)
    {
        if($result = $this->link->query($sql))
        {
            if($row = $result->fetch_array())
            {
                return $row;
            }
        }
        return false;
    }

    public function get_field($sql)
    {
        if($result = $this->link->query($sql))
        {
            if($row = $result->fetch_row())
            {
                return $row[0];
            }
        }
        return false;
    }

    public function del($sql)
    {
        return $this->link->query($sql); 
    }

    public function save()
    {
        $this->link->query($this->sql);
        return $this->link->insert_id;
    }
}
//end of mysqli declaration
?>
