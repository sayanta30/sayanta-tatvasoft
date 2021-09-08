<?php

$data = false;

if($_POST){
   include 'db.php';
   $formData = $_POST['items'];
   $titleName = trim(htmlspecialchars($formData[0]['newEventTitle']));
   $startDate = trim(htmlspecialchars($formData[0]['newEventStartDate']));
   $recurrenceNumber = trim(htmlspecialchars($formData[0]['recurrenceNumber']));
   $recurrenceType = trim(htmlspecialchars($formData[0]['newEventRecurrenceType']));

   $endNoOfOcurrence = isset($formData[0]['newEventEndOccurence']) && !empty($formData[0]['newEventEndOccurence']) ? $formData[0]['newEventEndOccurence'] : '';
   $endDate = isset($formData[0]['newEventEndDate']) && !empty($formData[0]['newEventEndDate']) ? $formData[0]['newEventEndDate'] : '';

   $query = $con->prepare("INSERT INTO event_details(event_title) VALUES ('$titleName')");
   $query->execute();
   $eid = $con->insert_id;

   $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$startDate')");
   $query->execute();

   if($endNoOfOcurrence){
      if($recurrenceType == 'Day'){
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." days"));
         for($i = 0; $i < $endNoOfOcurrence-1; $i++){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." days"));
            $data = true;
         }
      }
      elseif($recurrenceType == 'Week'){
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." week"));
         for($i = 0; $i < $endNoOfOcurrence-1; $i++){
            if(in_array(date('l', strtotime($newDate)), $formData[2]['dayNames'])){
               $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
               $query->execute();
               
               $data = true;
            }
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." week"));
         }
      }
      elseif($recurrenceType == 'Month'){
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." month"));
         for($i = 0; $i < $endNoOfOcurrence-1; $i++){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." month"));
            $data = true;
         }
      }
      else{
         // year
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." year"));
         for($i = 0; $i < $endNoOfOcurrence-1; $i++){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." year"));
            $data = true;
         }

      }
   }
   else{
      if($recurrenceType == 'Day'){
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." days"));
         while(strtotime($newDate) < strtotime($endDate)){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." days"));
            $data = true;
         }
      }
      elseif($recurrenceType == 'Week'){
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." week"));
         while(strtotime($newDate) < strtotime($endDate)){
            if(in_array(date('l', strtotime($newDate)), $formData[2]['dayNames'])){
               $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
               $query->execute();
               $data = true;
            }
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." week"));
         }
      }
      elseif($recurrenceType == 'Month'){

         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." month"));
         while(strtotime($newDate) < strtotime($endDate)){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." month"));
            $data = true;
         }
         
      }
      else{
         $newDate = date('Y-m-d', strtotime(  $startDate . "+".$recurrenceNumber." year"));
         while(strtotime($newDate) < strtotime($endDate)){
            $query = $con->prepare("INSERT INTO event_details_log(event_id, event_date) VALUES ('$eid','$newDate')");
            $query->execute();
            $newDate = date('Y-m-d', strtotime(  $newDate . "+".$recurrenceNumber." year"));
            $data = true;
         }
      }
   }
}
echo $data;
?>