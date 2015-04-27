<?php 
$API_KEY = "6bafa3926d6f004f8f886b87e4870949109f4b82";
$numbers = ["9808334910"];
$currentTime ;
$eqTime;
$collect;
if(isset($_POST["limit"])){
	$limit = $_POST["limit"];
$data = file_get_contents("http://earthquake.usgs.gov/fdsnws/event/1/query?format=geojson&latitude=27.6431649&longitude=85.3277502&maxradiuskm=100&minmagnitude=4.1&limit=".$limit . "&orderby=time");
$currentTime = round(microtime(true) );
$data = json_decode($data,true);
$i = count($data["features"]);
$mainAry = array();
foreach ($data["features"] as $dat ) {
$collect = array();
$collect["mag"]= $dat["properties"]["mag"];
$collect["place"]= $dat["properties"]["place"];
$eqTime = $dat["properties"]["time"]/1000;
$collect["time"]=date('Y-m-d H:i:s', $dat["properties"]["time"]/1000);
$collect["sig"]=$dat["properties"]["sig"];
$collect["alert"]=$dat["properties"]["alert"];
$collect["lat"]=$dat["geometry"]["coordinates"][1];
$collect["lng"]=$dat["geometry"]["coordinates"][0];
$collect["depth"]=$dat["geometry"]["coordinates"][2];

$interval = $currentTime - $eqTime ;


if( $interval >= (5 * 60 ) && $interval <= (15* 60)){ 
													//If the latest data is in the interval of [5:15] min i.e between 5 to 	30 min
	for ($i=0; $i <count(numbers) ; $i++) { 
		# code...
		$content = $collect["time"] + " : " + $collect["mag"] + " \n " 
				  + $collect["place"];
	    //echo $content
		$result = sendSMS(numbers[i],$content);
	}
}
else 
{
	$result="end";
}
$collect["info"]=$result;
array_push($mainAry, $collect);
}
$mainAry = json_encode( $mainAry );
echo $mainAry;

}



function sendSMS($to,$msg){
try
{
    // Create a Clockwork object using your API key
    $clockwork = new Clockwork( $API_KEY );
 
    // Setup and send a message
    $message = array( 'to' =>  $to, 'message' => $msg);
    $result = $clockwork->send( $message );
 
    // Check if the send was successful
    if($result['success']) {
        return 'Message sent - ID: ' . $result['id'];
    } else {
        return 'Message failed - Error: ' . $result['error_message'];
    }
}
catch (ClockworkException $e)
{
    return 'Exception sending SMS: ' . $e->getMessage();
}
}
?>


