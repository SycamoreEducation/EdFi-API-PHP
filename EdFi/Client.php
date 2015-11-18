<?php
        
namespace EdFi;

class Client {

    private $_Client_id; //private $_api_key;
    private $_Client_secret; //private $_api_secret;
    private $_Response_type = "code"; 
    private $_access_token;
    private $_api_url = 'https://uawisedataapi.dpi.wi.gov/EdFiWebApi/api/v2.0/2015';
    private $_oauth_url = 'https://uawisedataapi.dpi.wi.gov/EdFiWebApi/oauth';
    private $_debug_info;
    private $_raw_response;
    private $_curl_handle;

    const MODEL_STUDENTS = 'students';
    const MODEL_SESSIONS = 'sessions';
    const MODEL_STUDENTSCHOOLASSOCIATIONS = 'studentSchoolAssociations';
    /*const MODEL_CARDS = 'cards';
    const MODEL_CHECKLISTS = 'checklists';
    const MODEL_LISTS = 'lists';
    const MODEL_MEMBERS = 'members';
    const MODEL_NOTIFICATIONS = 'notifications';
    const MODEL_ORGANIZATIONS = 'organizations';
    const MODEL_SEARCH = 'search';
    const MODEL_TOKEN = 'tokens';
    const MODEL_TYPE = 'types';
    const MODEL_WEBHOOKS = 'webhooks';*/

    /**
     * @param string $Client_id
     * @param string $secret
     * @param string $access_token
     * @throws \InvalidArgumentException
     */
    public function __construct($Client_id, $Client_secret, $access_token = null){
    
        if (empty($Client_id)){
            throw new \InvalidArgumentException('Invalid Client_id');
        }

        $this->_Client_id = trim($Client_id);

        if (empty($Client_secret)){
            throw new \InvalidArgumentException('Invalid Client_secret');
        }
        
        $this->_Client_secret = trim($Client_secret);
        
        if (!empty($access_token)){
            //check to see if its the token is still valid and if its not, get it again
            if(shitIsValid)
                $this->setAccessToken($access_token);
            else
                $this->authorize($Client_id, $Client_secret);
        
        }else{
            $this->authorize($Client_id, $Client_secret);
            
        }
        
        $this->setApiBaseUrl($this->_api_url);

    }

    /**
     * Get's an authorization code from the EdFi API to trade in for an access token
     *
     * @param string $Client_id
     * @param string $Response_type
     *
     * @return string
     */
    public function authorize($Client_id, $Client_secret){   

        $oauthUrl = $this->getOAuthBaseUrl() . "/authorize?Client_id={$Client_id}&Response_type={$this->_Response_type}";

        $codeData = $this->_makeRequest($oauthUrl, array(), 'GET');
        
        //print_r($codeData);
        
        if(!empty($codeData)){
        
            $code = $codeData['code'];
        
            $this->authenticate($code);
            
        }else{
            throw new \RuntimeException('Could not decode response JSON - Response: ' . $this->_raw_response, $this->_debug_info['http_code']);
            
        }
        
    }

    /**
     * Get's an access code from the EdFi API from an authorization code
     *
     * @param string $Client_id
     * @param string $Response_type
     *
     * @return string
     */
    public function authenticate($code){   

        $oauthUrl  = $this->getOAuthBaseUrl() . "/token";
        $oauthUrl .= "?Client_id={$this->_Client_id}";
        $oauthUrl .= "&Response_type={$this->_Response_type}";
        $oauthUrl .= "&Code={$code}";
        $oauthUrl .= "&Grant_type=authorization_code";

        $tokenData = $this->_makeRequest($oauthUrl, array(), 'GET');
        
        //print_r($tokenData);
        
        if(!empty($tokenData)){
        
            //update the database
        
            $token = $tokenData['access_token'];
        
            $this->setAccessToken($token);
            
        }else{
            throw new \RuntimeException('Could not decode response JSON - Response: ' . $this->_raw_response, $this->_debug_info['http_code']);
            
        }
        
    }
    
    /**
     * After a user has authenticated and approved your app, they're presented with an access token. Set it here
     *
     * @param string $token
     *
     * @return \Trello\Client
     */
    public function setAccessToken($token){

        $this->_access_token = trim($token);

        return $this;

    }

    /**
     * Get the APIs base url
     *
     * @return string
     */
    public function getApiBaseUrl(){

        return $this->_api_url;

    }
    
      /**
     * Get the OAuth base url
     *
     * @return string
     */
    public function getOAuthBaseUrl(){

        return $this->_oauth_url;

    }

    /**
     * Set the APIs base url
     *
     * @param string $url
     *
     * @return \Trello\Client
     */
    public function setApiBaseUrl($url){

        $this->_api_url = rtrim($url, ' /');

        return $this;

    }

     /**
     * Set the OAuth base url
     *
     * @param string $url
     *
     * @return \Trello\Client
     */
    public function setOAuthBaseUrl($url){

        $this->_oauth_url = rtrim($url, ' /');

        return $this;

    }
    
    /**
     * Get the access token
     *
     * @return string
     */
    public function getAccessToken(){

        return $this->_access_token;

    }

    /**
     * Get the Client Secret
     *
     * @return string
     */
    public function getClientSecret(){

        return $this->_Client_secret;

    }

    /**
     * Get the Client Id
     *
     * @return string
     */
    public function getClientId(){

        return $this->_Client_id;

    }

    /**
     * Make a GET request
     *
     * @param string $path
     * @param array $payload
     *
     * @return array
     */
    public function get($path, array $payload = array()){
        //echo $path . '<BR><BR>';
        return $this->_makeRequest($path, $payload);

    }

    /**
     * Make a POST request
     *
     * @param string $path
     * @param array $payload
     * @param array $headers
     *
     * @return array
     */
    public function post($path, array $payload = array(), array $headers = array()){

        return $this->_makeRequest($path, $payload, 'POST', $headers);

    }

    /**
     * Make a PUT request
     *
     * @param string $path
     * @param array $payload
     * @param array $headers
     *
     * @return array
     */
    public function put($path, array $payload = array(), array $headers = array()){

        return $this->_makeRequest($path, $payload, 'PUT', $headers);

    }

    /**
     * Make a DELETE request
     *
     * @param string $path
     * @param array $payload
     * @param array $headers
     *
     * @return array
     */
    public function delete($path){

        return $this->_makeRequest($path, array(), 'DELETE');

    }

    /**
     * Make a CURL request
     *
     * @param string $url
     * @param array $payload
     * @param string $method
     * @param array $headers
     * @param array $curl_options
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function _makeRequest($url, array $payload = array(), $method = 'GET', array $headers = array(), array $curl_options = array()){

        //echo $url . "<BR><BR>";
        //echo "GetAccessToken " . $this->getAccessToken();
    
        //if this URL is not an oauth url used for authentication, 
        if(strpos($url, "oauth") == false){
            
            $url = $this->getApiBaseUrl() . '/' . $url;
                
            //else if there is an access token loaded, append it to request
            //$url .= '?access_token=' . $this->getAccessToken();
            //echo "Adding access token to outgoing request";
            $headers[] = 'Authorization: Bearer ' . $this->getAccessToken(); 
        
        }
        
        //echo "$url <br><br>";
        
        $ch = $this->_getCurlHandle();
        $method = strtoupper($method);

        $options = array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        );

        if ($method === 'GET'){

            if (!empty($payload)){
                $options[CURLOPT_URL] = $options[CURLOPT_URL] . '?' . http_build_query($payload, '&');
            }

        }else if (!empty($payload)){

            $payload = json_encode($payload);
        
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $payload;
            $headers[] = 'Content-Length: ' . strlen($options[CURLOPT_POSTFIELDS]);
            $headers[] = 'Content-Type: application/json';
            $options[CURLOPT_HTTPHEADER] = $headers;

        }

        if (!empty($curl_options)){
            $options = array_merge($options, $curl_options);
        }

        //print_r($headers);

        //print_r($options);
        
        curl_setopt_array($ch, $options);
        $this->_raw_response = curl_exec($ch);
        $this->_debug_info = curl_getinfo($ch);

        //echo "<br><br>";
        //print_r($this->_raw_response);
        //echo "<br><br>";
        
        if ($this->_raw_response === false){
            throw new \RuntimeException('Request Error: ' . curl_error($ch));
        }

        if ($this->_debug_info['http_code'] < 200 || $this->_debug_info['http_code'] >= 400){
            throw new \RuntimeException('API Request failed - Response: ' . $this->_raw_response, $this->_debug_info['http_code']);
        }

        $response = json_decode($this->_raw_response, true);

        //echo "<br><br>";
        //print_r($response);
        //echo "<br><br>";
        
        /*if ( ($response === null || !is_array($response)) && $this->_debug_info['http_code'] != 200){
            throw new \RuntimeException('Could not decode response JSON - Response: ' . $this->_raw_response, $this->_debug_info['http_code']);
        }*/

        return $response;

    }

    /**
     * Get the raw unparsed response returned from the CURL request
     *
     * @return string
     */
    public function getRawResponse(){

        return $this->_raw_response;

    }

    public function getDebugInfo(){

        return $this->_debug_info;

    }

    /**
     * Singleton to get a CURL handle
     *
     * @return resource
     */
    protected function _getCurlHandle(){

        if (!$this->_curl_handle){
            $this->_curl_handle = curl_init();
        }

        return $this->_curl_handle;

    }

    /**
     * Closes the currently open CURL handle
     */
    public function __destruct(){

        if ($this->_curl_handle){
            curl_close($this->_curl_handle);
        }

    }
}