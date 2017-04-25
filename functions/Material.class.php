<?php
class Material
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addMaterial($material) {
	try
       {
   
          $stmt = $this->db->prepare("INSERT INTO materials(name) 
                                                       VALUES(:material)");
              
           $stmt->bindparam(":material", $material);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

     public function setMaterial($material_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE materials SET ".$name_field." = :value WHERE id = :material_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":material_id", $material_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

  public function existMaterial($material){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM materials WHERE name = :name LIMIT 1");
          $stmt->execute(array(':name'=> $material));
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