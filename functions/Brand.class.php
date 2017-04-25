<?php
class Brand
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addBrand($brand,$logo,$description) {
  try
       {
   
          $stmt = $this->db->prepare("INSERT INTO brands(name,logo,description) 
                                                       VALUES(:brand,:logo,:description)");
              
           $stmt->bindparam(":brand", $brand);
           $stmt->bindparam(":logo", $logo);
           $stmt->bindparam(":description", $description);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

     public function setBrand($brand_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE brands SET ".$name_field." = :value WHERE id = :brand_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":brand_id", $brand_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

  public function existBrand($brand){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM brands WHERE name = :name LIMIT 1");
          $stmt->execute(array(':name'=> $brand));
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