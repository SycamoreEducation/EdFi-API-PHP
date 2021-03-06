<?php
    
namespace EdFi\Model;

abstract class Object implements \ArrayAccess, \Countable, \Iterator{

    protected $_client;
    protected $_model;

    private $_position = 0;
    protected $_data;

    public function __construct(\EdFi\Client $client, array $data = array()){
        $this->_client = $client;
        $this->_data = $data;
    }

    /**
     * Save an object
     *
     * @return \EdFi\Model\Object
     */
    public function save(){

        //echo $this->getId();
    
        if ($this->getId()){
            //echo "<br><br> UPDATE <br><br>";
            return $this->update();
        }else{
            //echo "<br><br> POST <br><br>";
            //if you dont have an id, how can you USE IT IN THE URL?
            //$response = $this->getClient()->post($this->getModel() . '/' . $this->getId(), $this->toArray());
            $response = $this->getClient()->post($this->getModel(), $this->toArray());
        }

        $child = get_class($this);

        return new $child($this->getClient(), $this->toArray());

    }

    public function update(){

        if (!$this->getId()){
            throw new \InvalidArgumentException('There is no ID set for this object - Please call setId before calling update');
        }

        $response = $this->getClient()->put($this->getModel() . '/' . $this->getId(), $this->toArray());

        $child = get_class($this);

        return new $child($this->getClient(), $this->toArray());

    }

    /**
     * Get an item by id ($this->id)
     *
     * @throws \InvalidArgumentException
     * @return \EdFi\Model\Object
     */
    public function get(){

        /*if (!$this->getId()){
            throw new \InvalidArgumentException('There is no ID set for this object - Please call setId before calling get');
        }*/

        $child = get_class($this);
        $response = $this->getClient()->get($this->getModel() . '/' . $this->getId());

        return new $child($this->getClient(), $response);

    }

    /**
     * Delete an item by id ($this->id)
     *
     * @throws \InvalidArgumentException
     * @return boolean
     */
    public function delete(){

        if (!$this->getId()){
            throw new \InvalidArgumentException('There is no ID set for this object - Please call setId before calling get');
        }

        $child = get_class($this);
        $response = $this->getClient()->delete($this->getModel() . '/' . $this->getId());

        return true;

    }

    /**
     * Get relative data
     *
     * @param string $path
     * @param array $payload
     *
     * @return array
     */
    public function getPath($path, array $payload = array()){

        return $this->getClient()->get($this->getModel() . '/' . $this->getId() . '/' . $path, $payload);

    }
    
    /**
     * Get all data (no ids present) from endpoint
     *
     * @param string $path
     * @param array $payload
     *
     * @return array
     */
    public function getAll(array $payload = array()){

        return $this->getClient()->get( $this->getModel(), $payload);

    }

    public function getModel(){

        //echo $this->_model;
    
        return $this->_model;

    }

    /**
     *
     * @return \EdFi\Client
     */
    public function getClient(){

        return $this->_client;

    }

    public function setId($id){

        $this->id = $id;

        return $this;

    }

    public function getId(){

        return $this->id;

    }

    public function __get($key){

        return $this->offsetExists($key)? $this->offsetGet($key) : null;

    }

    public function __set($key, $value){

        $this->offsetSet($key, $value);

    }

    public function __isset($key){

        return $this->offsetExists($key);

    }

    public function __unset($key){

        return $this->offsetUnset($key);

    }

    public function toArray(){

        return $this->_data;

    }

    public function offsetSet($offset, $value){

        if (is_null($offset)){
            $this->_data[] = $value;
        }else{
            $this->_data[$offset] = $value;
        }

    }

    public function offsetExists($offset){

        return isset($this->_data[$offset]);

    }

    public function offsetUnset($offset){

        unset($this->_data[$offset]);

    }

    public function offsetGet($offset){

        return isset($this->_data[$offset])? $this->_data[$offset] : null;

    }

    public function count(){

        return count($this->_data);

    }

    public function rewind(){

        $this->_position = 0;

    }

    public function current(){

        return $this->_data[$this->_position];

    }

    public function key(){

        return $this->_position;

    }

    public function next(){

        ++$this->_position;

    }

    public function valid(){

        return isset($this->_data[$this->_position]);

    }

}
