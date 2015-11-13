<?php

namespace EdFi\Model;

class Sessions extends Object {

    protected $_model = 'Sessions';

    /**
     * Arguments
        schoolYear (required) int
        schoolId (required) int
        termDescriptor (required) string
       
     * @see \EdFi\Model\Object::save()
     */
    
    public function save(){
        if (empty($this->schoolId)){
            throw new \InvalidArgumentException('Missing required field "schoolId"');
        }
        
        if (empty($this->schoolYear)){
            throw new \InvalidArgumentException('Missing required field "schoolYear"');
        }
        
        if (empty($this->termDescriptor)){
            throw new \InvalidArgumentException('Missing required field "termDescriptor"');
        }
        
        if (empty($this->name)){
            $this->name = "New Session";
        }
        
        return parent::save();
    }
    
    public function getSessions(array $params = array()){

        $data = $this->getAll($params);

        $tmp = array();
        foreach ($data as $item){
            array_push($tmp, new \EdFi\Model\Session($this->getClient(), $item));
        }

        return $tmp;

    }

    /*public function getSession($session_id, array $params = array()){

        $data = $this->getPath("cards/{$card_id}", $params);

        return new \EdFi\Model\Card($this->getClient(), $data);

    }*/

    /*public function getActions(array $params = array()){

        $data = $this->getPath('actions', $params);

        $tmp = array();
        foreach ($data as $item){
            array_push($tmp, new \Trello\Model\Action($this->getClient(), $item));
        }

        return $tmp;

    }*/

    /*public function getLists(array $params = array()){

        $data = $this->getPath('lists', $params);

        $tmp = array();
        foreach ($data as $item){
            array_push($tmp, new \Trello\Model\Lane($this->getClient(), $item));
        }

        return $tmp;

    }*/

    /*public function copy($new_name = null, array $copy_fields = array()){

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

    }*/

}