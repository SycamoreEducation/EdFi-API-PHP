<?php

namespace EdFi\Model;

class Students extends Object {

    protected $_model = 'students';
    
    public function getStudents(array $params = array()){

        $data = $this->getAll($params);

        $tmp = array();
        foreach ($data as $item){
            //echo "<br><br>";
            //print_r($item);
            //echo "<br><br>";
            array_push($tmp, new \EdFi\Model\Students($this->getClient(), $item));
        }

        return $tmp;

    }

    public function getStudent($id, array $params = array()){

        $params['studentUniqueId'] = $id;
    
        $data = $this->getAll($params);

        return new \EdFi\Model\Students($this->getClient(), $data);

    }

}