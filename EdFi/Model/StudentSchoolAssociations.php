<?php

namespace EdFi\Model;

class StudentSchoolAssociations extends Object {

    protected $_model = 'studentSchoolAssociations';

    public function getAssociations(){

        $data = $this->getAll();

        $tmp = array();
        foreach ($data as $item){
            //echo "<br><br>";
            //print_r($item);
            //echo "<br><br>";
            array_push($tmp, new \EdFi\Model\StudentSchoolAssociations($this->getClient(), $item));
        }

        return $tmp;

    }
    
    public function getAssociation($studentUniqueId, $entryDate){

        $params['studentUniqueId'] = $studentUniqueId;
        $params['entryDate'] = $entryDate;
        $params['schoolId'] = $this->getSchoolId();
    
        $data = $this->getAll($params);

        return new \EdFi\Model\StudentSchoolAssociations($this->getClient(), $data);

    }

}