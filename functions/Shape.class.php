<?php
class Shape
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addShape($shape) {
  try
       {
   
          $stmt = $this->db->prepare("INSERT INTO shapes(name) 
                                                       VALUES(:shape)");
              
           $stmt->bindparam(":shape", $shape);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

     public function setShape($shape_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE shapes SET ".$name_field." = :value WHERE id = :shape_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":shape_id", $shape_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

     public function existShape($shape){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM shapes WHERE name = :name LIMIT 1");
          $stmt->execute(array(':name'=> $shape));
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