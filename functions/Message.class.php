<?php
class Message
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addMessage($message,$receive_id,$author_id,$not_read=1) {
	try
       {
   
          $stmt = $this->db->prepare("INSERT INTO messages(message,author_id,receive_id,not_read) 
                                                       VALUES(:message,:author_id,:receive_id,:not_read)");
              
           $stmt->bindparam(":message", $message);
           $stmt->bindparam(":receive_id", $receive_id);
           $stmt->bindparam(":author_id", $author_id);
           $stmt->bindparam(":not_read", $not_read);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }


  public function readMessage($user_id)
  {
  try
    {
   
          $stmt = $this->db->prepare("SELECT message, users.first_name as send_firstname, users.last_name as send_lastname FROM messages INNER JOIN users WHERE messages.author_id = users.id AND messages.receive_id = :receive_id");
           $stmt->bindparam(":receive_id", $user_id);
          $stmt->execute(); 
          
          if($stmt->rowCount()>0)
          {
            while($message=$stmt->fetch())
            {
              echo "<li>".$message['message']." send by ".$message['send_firstname']." ".$message['send_lastname']."</li>";
              
            }
          }
          else
          {
            return false;
          } 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }      
  }

}
?> 