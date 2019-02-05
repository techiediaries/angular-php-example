echo "hello";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$localhost = "127.0.0.1"; 
$username = "root"; 
$password = "root"; 
$dbname = "mydb";

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
//$input = json_decode(file_get_contents('php://input'),true);
$id = ''

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
    $sql = "update policies (number, amount, creationDate, expireDate) ('$number', '$amount', '$creationDate', '$expireDate')"; break;
  case 'POST':
    $sql = "insert into policies (number, amount, creationDate, expireDate) ('$number', '$amount', '$creationDate', '$expireDate')"; break;
  case 'DELETE':
    $id = $_GET['id'];
    $sql = "delete policies where id=$id"; break;
}

// run SQL statement
$result = mysqli_query($conn,$query);
 
// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error());
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



