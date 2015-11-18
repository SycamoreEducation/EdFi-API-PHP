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
    
    /*public function getActions(array $params = array()){

        $data = $this->getPath('actions', $params);

        $tmp = array();
        foreach ($data as $item){
            array_push($tmp, new \Trello\Model\Action($this->getClient(), $item));
        }

        return $tmp;

    }

    public function getLists(array $params = array()){

        $data = $this->getPath('lists', $params);

        $tmp = array();
        foreach ($data as $item){
            array_push($tmp, new \Trello\Model\Lane($this->getClient(), $item));
        }

        return $tmp;

    }

    public function copy($new_name = null, array $copy_fields = array()){

        if ($this->getId()){

            $tmp = new self($this->getClient());
            if (!$new_name){
                $tmp->name = $this->name . ' Copy';
            }else{
                $tmp->name = $new_name;
            }
            $tmp->idBoardSource = $this->getId();

            if (!empty($copy_fields)){
                $tmp->keepFromSource = implode(',', $copy_fields);
            }

            return $tmp->save();

        }

        return false;
    }
*/

}