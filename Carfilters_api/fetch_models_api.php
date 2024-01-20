<?php

header('content-Type: application/json');
header('Acess-control-Allow-Orign: *');
include '../config.php';



//$get_id=json_decode(file_get_contents("php://input"),true); // Getting the Maker_id as Request !
     //use it with the urls 
if (isset($_GET['m_id'])) {
    $maker_id = $_GET['m_id'];
    //echo "Maker id :".$maker_id;
$query="SELECT * FROM tbl_models WHERE maker_id={$maker_id}";

$result=mysqli_query($conn,$query) or die("tbl_Models Query not working !");
if(mysqli_num_rows($result)>0)
{

    $data=mysqli_fetch_all($result,MYSQLI_ASSOC);
    echo json_encode($data);                    
}
else
{
        echo json_encode(array('message'=>'No record found','status'=>false));
}
} else {
    die(json_encode(array('message' => 'Maker ID not provided in the request body', 'status' => false)));
}

mysqli_close($conn);
