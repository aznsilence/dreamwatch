<?php
class Style
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addStyle($style) {
  try
       {
   
          $stmt = $this->db->prepare("INSERT INTO styles(name) 
                                                       VALUES(:style)");
              
           $stmt->bindparam(":style", $style);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

     public function setStyle($style_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE styles SET ".$name_field." = :value WHERE id = :style_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":style_id", $style_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }


    public function existStyle($style){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM styles WHERE name = :name LIMIT 1");
          $stmt->execute(array(':name'=> $style));
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