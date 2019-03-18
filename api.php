<?php
require_once("Rest.inc.php");
header("Access-Control-Allow-Origin: *");

class API extends REST {
	public $data = "";
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "root";
	const DB = "payment_app";

	private $db = NULL;

	public function __construct() {
		parent::__construct();// Init parent contructor
		$this->dbConnect();// Initiate Database connection
	}

	//Database connection
	private function dbConnect() {
		$this->db = mysqli_connect('localhost', 'root', 'root', 'payment_app');
	}
	public function processApi() {
	  $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
      if ((int)method_exists($this, $func) > 0) $this->$func();
      else $this->response('Error code 404, Page not found', 404);

		// If the method not exist with in this class, response would be "Page not found".
	}

	private function login() {
		 if($this->get_request_method() != "POST"){ 
            $this->response('',406);
         }
          $email= $_POST['username'];
          $pass= $_POST['password'];
          $sql = mysqli_query($this->db,"SELECT * FROM user_info WHERE email='$email' AND password ='$pass'");  
          // print_r(mysqli_fetch_array($sql)); 
          $result =array(); 
          while($rlt = mysqli_fetch_assoc($sql))
            {
               $result[] = $rlt['id'];
            }

            $this->response($this->JSON($result), 200);
    
	}
	
		private function get() {
		 if($this->get_request_method() != "GET"){ 
            $this->response('',406);
         }
         
          $sql = mysqli_query($this->db,"SELECT * FROM user_info");  
          // print_r(mysqli_fetch_array($sql)); 
          $result =array(); 
          while($rlt = mysqli_fetch_assoc($sql))
            {
               $result[] = $rlt;
            }
            $this->response($this->JSON($result), 200);
    
	}
	//Encode array into JSON
	private function json($data) {
		if (is_array($data)) {

			return json_encode($data);
		}
	}
}

// Initiiate Library
$api = new API;
$api->processApi();
?>