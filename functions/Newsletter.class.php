<?php
class Newsletter
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addNewsletter($email) {
	try
       {
   
          $stmt = $this->db->prepare("INSERT INTO newsletter(email) 
                                                       VALUES(:email)");
              
           $stmt->bindparam(":email", $email);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }


  public function existNewsletter($email){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM newsletter WHERE email LIKE :email LIMIT 1");
          $stmt->execute(array(':email'=> $email));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {

              return true;
          
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }

   }

}
?> 