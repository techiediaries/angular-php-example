<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$localhost = "127.0.0.1"; 
$username = "root"; 
$password = "jb395566"; 
$dbname = "mydb";


// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);




// create connection to mysql
$conn = new mysqli($localhost, $username, $password, $dbname); 
mysqli_set_charset($conn ,'utf8');

if($conn->connect_error) {
    die("Error : " . $conn->connect_error);
} 

switch ($method) {
  case 'GET':
    $id = $_GET['id'];
    $sql = "select * from policies".($id?" where id=$id":''); break;
  case 'PUT':
    $id = $input["id"];	
    $number = $input["number"];
    $amount = $input["amount"];

    $sql = "update policies set number = '$number', amount = $amount where id=$id"; break;
  case 'POST':
    $number = $input["number"];
    $amount = $input["amount"];

    $sql = "insert into policies (number, amount) values ('$number', $amount)"; break;
  case 'DELETE':
    $id = $_GET['id'];
    $sql = "delete from policies where id=$id"; break;
}



// run SQL statement
$result = mysqli_query($conn,$sql);

  
// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error($conn));
}

// print results, insert id or affected row count
if ($method == 'GET') {
  if (!$id) echo '[';
  for ($i=0;$i<mysqli_num_rows($result);$i++) {
    echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
  }
  if (!$id) echo ']';
} elseif ($method == 'POST') {
  echo mysqli_insert_id($conn);
} else {
  echo mysqli_affected_rows($conn);
}

$conn->close();


?>
