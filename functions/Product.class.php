<?php
class Product
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }


    public function addProduct($name,$reference,$serial_number,$price,$description,$quantity,$category,$brand,$style,$material,$shape,$origin_country,$functionality="",$image_src="")
    {
       try
       {
   
           $stmt = $this->db->prepare("INSERT INTO products(name,reference,serial_number,price,description,quantity,category_id,brand_id,style_id,material_id,shape_id,origin_country_id,functionality_id,image_src) 
                                                       VALUES(:name,:reference,:serial_number,:price,:description,:quantity,:category,:brand,:style,:material,:shape,:origin_country,:functionality,:image_src)");
              
           $stmt->bindparam(":name", $name);
           $stmt->bindparam(":reference", $reference);
           $stmt->bindparam(":serial_number", $serial_number);
           $stmt->bindparam(":price", $price);
           $stmt->bindparam(":description", $description);
           $stmt->bindparam(":quantity", $quantity);
           $stmt->bindparam(":category", $category);
           $stmt->bindparam(":brand", $brand);
           $stmt->bindparam(":style", $style);
           $stmt->bindparam(":material", $material);
           $stmt->bindparam(":shape", $shape);
           $stmt->bindparam(":origin_country", $origin_country);
           $stmt->bindparam(":functionality", $functionality);
           $stmt->bindparam(":image_src", $image_src);
          
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }


   public function setProduct($product_id,$value,$name_field){
    try
      {       
       $stmt = $this->db->prepare("UPDATE products SET ".$name_field." = :value WHERE id = :product_id");  
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":product_id", $product_id);          
       $stmt->execute(); 

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

    public function deleteProduct($product_id){
    try
      {       
       $stmt = $this->db->prepare("DELETE FROM products WHERE id = :product_id");  
       $stmt->bindparam(":product_id", $product_id);          
       $stmt->execute(); 

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

    public function getProduct($product_id,$name_field)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :product_id LIMIT 1");
          $stmt->execute(array(':product_id'=>$product_id));
          $productRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {

              return $productRow[$name_field];
          
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }


   	public function existProduct($serial_number){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM products WHERE serial_number = :serial_number LIMIT 1");
          $stmt->execute(array(':serial_number'=> $serial_number));
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


   public function searchProduct($search){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM products WHERE serial_number LIKE %:serial_number% OR  name LIKE % :name % OR  reference LIKE % :reference %");
          $stmt->execute(array(':serial_number'=> $serial_number,':name'=> $name,':reference'=> $reference));
          $productRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {

              return $productRow;
          
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }

   }

   public function getMenu($name_table,$category_id)
   {
        try
       {
          $req = $this->db->prepare("SELECT name,id FROM ".$name_table."");
          $req->execute();
          
          if($req->rowCount() > 0)
          {
                while($productRow=$req->fetch())
                {
                  echo "<li><a href='liste-product.php?brand-id=".$productRow['id']."&category_id=".$category_id."'>".$productRow['name']."</a></li>";
                }
        }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }


   public function getSelecteur($name_table,$value_id)
   {
    try
    {
      $req = $this->db->prepare("SELECT name,id FROM ".$name_table."");
      $req->execute();

      if($req->rowCount() > 0)
      {
        $i=0;
        while($productRow=$req->fetch())
        {


          if($value_id!="")
          {
            if(strpos($value_id,",")==true)
            {
              $tab = explode(",",$value_id);
              $nb_res = count($tab);



              if($productRow['id']==$tab[$i] && $value_id!=NULL)
                $selected = " selected";
              else
                $selected = "";

              echo "<option value='".$productRow['id']."' ".$selected." >".$productRow['name']."</option>";

              $i++;
              
            }
            else
            {

              if($productRow['id']==$value_id && $value_id!=NULL)
                $selected = " selected";
              else
                $selected = "";

              echo "<option value='".$productRow['id']."' ".$selected." >".$productRow['name']."</option>";

            }
          }
          else
          {
            echo "<option value='".$productRow['id']."' >".$productRow['name']."</option>";   
          }
        }
      }
    }
    catch(PDOException $e)
    {
     echo $e->getMessage();
   }
 }

   public function getBrandID($brand_name)
  {
        try
       {
          $req = $this->db->prepare("SELECT id FROM brands WHERE name LIKE :brand_name");
          $req->execute(array('brand_name' => ''.$brand_name.'%'));
          $data = $req->fetch();
          
          if($req->rowCount() > 0)
          {
              return $data['id'];   
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
  }


  public function getBrandName($brand_id)
  {
        try
       {
          $req = $this->db->prepare("SELECT br.name FROM products as pr
                                      INNER JOIN brands as br on pr.brand_id = br.id
                                      WHERE pr.brand_id = br.id AND br.id = :brand_id");
          $req->execute(array('brand_id' => $brand_id));
          $data = $req->fetch();
          //var_dump($req);
          if($req->rowCount() > 0)
          {
              return $data['name'];   
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
  }

  public function existReference($reference){
  
  try
       {
          $req = $this->db->prepare("SELECT id FROM products WHERE reference = :reference");
          $req->execute(array('reference' => $reference));
          $data = $req->fetch();
          
          if($req->rowCount() > 0)
          {
              return true;   
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       } 
  }


  public function getSubCategoryname($item_id,$name_table){
  
  try
       {
          $stmt = $this->db->prepare("SELECT name FROM ".$name_table." WHERE id = :item_id LIMIT 1");
          $stmt->execute(array(':item_id'=> $item_id));
          $productRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {

              return $productRow['name'];
          
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
  }

}
?> 