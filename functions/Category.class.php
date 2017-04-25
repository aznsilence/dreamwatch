<?php
class Category
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }

public function addCategory($category) {
	try
       {
   
          $stmt = $this->db->prepare("INSERT INTO categories(name) 
                                                       VALUES(:category)");
              
           $stmt->bindparam(":category", $category);
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

     public function setCategory($category_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE categories SET ".$name_field." = :value WHERE id = :category_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":category_id", $category_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

}
?> 