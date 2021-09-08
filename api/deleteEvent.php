<?php
   // Updating or adding tags for a perticular product
   include 'db.php';

   $data['status'] = false;
   $data['text'] = 'Nothing to delete';

   if(isset($_POST['id']) && !empty($_POST['id'])){
      
      $id = trim($_POST['id']);
      
      $query = $con->prepare("SELECT event_details._id id from event_details where _id = ?");
      $query->bind_param("i", $id);
      $query->execute();

      $result = $query->get_result();
      if($result->num_rows > 0){
         $eventId = $result->fetch_assoc()['id'];
         $eventDetailsDeleteQuery = $con->prepare("DELETE FROM event_details WHERE _id = ?");
         $eventDetailsLogDeleteQuery = $con->prepare("DELETE FROM event_details_log WHERE event_id = ?");

         $eventDetailsLogDeleteQuery->bind_param("i", $eventId);
         $eventDetailsLogDeleteQuery->execute();

         $eventDetailsDeleteQuery->bind_param("i", $eventId);
         $eventDetailsDeleteQuery->execute();

         $data['status'] = true;
      }

      if($data['status']){
         $data['text'] = 'Successfully deleted the event';
      }

   }
   
   echo json_encode($data);
?>