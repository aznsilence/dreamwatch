<?php
class Functionality
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addFunctionality($functionality) {
  try
       {
   
          $stmt = $this->db->prepare("INSERT INTO functionalities(name) 
                                                       VALUES(:functionality)");
              
           $stmt->bindparam(":functionality", $functionality);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
  }

     public function setFunctionality($functionality_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE functionalities SET ".$name_field." = :value WHERE id = :functionality_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":functionality_id", $functionality_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }


  public function existFunctionality($functionality){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM functionalities WHERE name = :name LIMIT 1");
          $stmt->execute(array(':name'=> $functionality));
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

     public function getFunctionality($functionality){

       try
       {
          $stmt = $this->db->prepare("SELECT name FROM functionalities WHERE id = :functionality_id LIMIT 1");
          $stmt->execute(array(':functionality_id'=> $functionality));
          
          if($stmt->rowCount() > 0)
          {

            while($productRow=$stmt->fetch(PDO::FETCH_ASSOC))
            {
              echo $productRow['name'];
            }
          
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }

   }

}
?> 