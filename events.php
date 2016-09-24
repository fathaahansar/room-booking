<?php
require_once("includes/class.user.php");

$data = new USER();

if(isset($_POST['approvedRooms'])) {
	
	$room_num = $_POST['approvedRooms'];

	if($room_num=="0") {
		echo json_encode(array());
	}

	$stmt = $data->runQuery("SELECT room_num FROM rooms ORDER BY room_id");
	$stmt->execute();
	$rooms_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$room_array = array();
	$i = 1;

	foreach($rooms_data as $rooms) {
		foreach($rooms as $room) {
			$room_array[$i++] = $room;
		}
	}

	$stmt = $data->runQuery("SELECT * FROM room_requests WHERE room_id = :room_num AND id_authorized = 1 ORDER BY req_id"); 
	$stmt->execute(array(':room_num'=>$room_num));
	$data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$i = 0;
	$response = array(array());
	foreach($data_array as $data_row) {
		$response[$i]['id']    = $data_row['req_id'];
		$response[$i]['title'] = $room_array[$data_row['room_id']];
		$response[$i]['start'] = $data_row['req_date']." ".$data_row['req_time_in'];
		$response[$i++]['end'] = $data_row['req_date']." ".$data_row['req_time_out'];
	}

	if($response == array(array())) {
		$response = array("id"=>"", "title"=>"", "start"=>"", "end"=>"");
	}

	// Send the result to success
	echo json_encode ($response);
}

if(isset($_POST['pendingRooms'])) {
	$room_num = $_POST['approvedRooms'];

	if($room_num=="0") {
		echo json_encode(array());
	}

	$stmt = $data->runQuery("SELECT room_num FROM rooms ORDER BY room_id");
	$stmt->execute();
	$rooms_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$room_array = array();
	$i = 1;

	foreach($rooms_data as $rooms) {
		foreach($rooms as $room) {
			$room_array[$i++] = $room;
		}
	}

	$stmt = $data->runQuery("SELECT * FROM room_requests WHERE room_id = :room_num AND id_authorized = 0 ORDER BY req_id"); 
	$stmt->execute(array(':room_num'=>$room_num));
	$data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$i = 0;
	$response = array(array());
	foreach($data_array as $data_row) {
		$response[$i]['id']    = $data_row['req_id'];
		$response[$i]['title'] = $room_array[$data_row['room_id']];
		$response[$i]['start'] = $data_row['req_date']." ".$data_row['req_time_in'];
		$response[$i++]['end'] = $data_row['req_date']." ".$data_row['req_time_out'];
	}

	if($response == array(array())) {
		$response = array("id"=>"", "title"=>"", "start"=>"", "end"=>"");
	}

	// Send the result to success
	echo json_encode ($response);

}
	
 
?>