<?php
class Filter
{
  private $db;
  function __construct($DB_con)
  {
    $this->db = $DB_con;
  }

  public function getBrandfilter($brand_id){

    try
    {
      $req = $this->db->prepare("SELECT products.name, products.reference, products.price, products.description FROM products INNER JOIN brands ON products.brand_id = brands.id WHERE brands.id = :brand_id ORDER BY products.name ASC");
      $req->execute(array(':brand_id' => $brand_id));
      $data = $req->fetchAll();
      var_dump($data);
      if($req->rowCount() > 0)
      {
            return $data[$brand_id];
      }
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  }
}
?>
