<?php
   include 'db.php';
   include 'writeData.php';

   // Query for getting all the events
   $query = "SELECT event_details._id id, event_title FROM event_details";

   $statement = $con->prepare($query);
   $statement->execute();

   // Getting all the results
   $result = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

   $data = array();
   
   $counter = 0;

   foreach($result as $row)
   {
      $sub_array = array();
      $query = $con->prepare('SELECT event_date from event_details_log where event_id = '.$row['id']);
      $query->execute();
      $eventDate = $query->get_result()->fetch_assoc()['event_date'];
      
      $sub_array["sl"] = ++$counter;
      $sub_array["title"] = $row['event_title'];
      $sub_array["sDate"] = $eventDate;
      $sub_array["actions"] = "<button id='".$row['id']."' class='btn btn-outline-warning viewEvent'><i class='fas fa-eye'></i></button>&nbsp;<button id='".$row['id']."' class='btn btn-outline-danger deleteEvent'><i class='fas fa-trash'></i></button>";
      $data[] = $sub_array;
   }
   echo writeData($data) == true ? 'All data loaded' : 'Something went wrong';
?>