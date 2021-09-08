<?php
   // Get details for a single event
   include 'db.php';
	$data['status'] = false;

	if(isset($_POST['id']) && !empty($_POST['id'])){
		$id = trim($_POST['id']);
      $data = array();
      $data['status'] = true;

      $query = $con->prepare("SELECT event_title FROM event_details WHERE _id = ?");
		$query->bind_param("i", $id);
		$query->execute();
      $data['title'] = trim(htmlspecialchars($query->get_result()->fetch_assoc()['event_title']));

		$query = $con->prepare("SELECT event_title, event_date FROM event_details INNER JOIN event_details_log ON event_details._id = event_details_log.event_id WHERE event_details._id = ?");
		$query->bind_param("i", $id);
		$query->execute();

      $result = $query->get_result();
      $data['count'] = $result->num_rows;

		// Getting all the results
      $result = $result->fetch_all(MYSQLI_ASSOC);
      $counter = 0;

      foreach($result as $row){
         $data['event_dates'][$counter][0] = $row['event_date'];
         $data['event_dates'][$counter++][1] = date('l', strtotime($row['event_date']));
      }

	}
	echo json_encode($data);
?>