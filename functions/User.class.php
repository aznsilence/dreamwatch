<?php
class User
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function register($first_name,$last_name,$email,$password,$gender, $zipcode, $birthdate,$avatar="",$is_admin=0)
    {
       try
       {
           $password_hash = password_hash($password, PASSWORD_DEFAULT);
   
           $stmt = $this->db->prepare("INSERT INTO users (first_name,last_name,email,password,gender, zipcode_id, birthdate,avatar,is_admin) 
                                                       VALUES(:firstname, :lastname, :email, :password, :gender, :zipcode, :birthdate,:avatar,:is_admin)");
              
           $stmt->bindparam(":firstname", $first_name, PDO::PARAM_STR);
           $stmt->bindparam(":lastname", $last_name, PDO::PARAM_STR);
           $stmt->bindparam(":password", $password_hash, PDO::PARAM_STR);            
           $stmt->bindparam(":gender", $gender, PDO::PARAM_STR);            
           $stmt->bindparam(":email", $email, PDO::PARAM_STR);            
           $stmt->bindparam(":zipcode", $zipcode, PDO::PARAM_INT);                                 
           $stmt->bindparam(":birthdate", $birthdate); 
           $stmt->bindparam(":avatar", $avatar);
           $stmt->bindparam(":is_admin", $is_admin);      
           $stmt->execute(); 
   
           return $stmt;
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
 
    public function login($email,$password,$is_cookie)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
          $stmt->execute(array(':email'=>$email));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($password, $userRow['password']))
             {
                
             	if($is_cookie==1)
             	{
             		setcookie("user_id", $userRow['id'], time() + (86400 * 30), "/");	
                //echo "cookie";  
             	}
             	else
             	{
                	$_SESSION['user_id'] = $userRow['id'];
                 //echo "session";             		
             	}

                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function logout()
   {
        
   		if(isset($_SESSION['user_id']))
   		{
   			session_destroy();
        	unset($_SESSION['user_id']);
   		}


   		if(isset($_COOKIE['user_id']))
   		{
   			setcookie('user_id', null, -1, '/');
   		}

        header('Location:login.php');
        return true;
   }


   public function setUser($user_id,$value,$name_field){

    try
    {
       $stmt = $this->db->prepare("UPDATE users SET ".$name_field." = :value WHERE id = :user_id");
          
       $stmt->bindparam(":value", $value);
       $stmt->bindparam(":user_id", $user_id);          
       $stmt->execute();

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }

    public function deleteUser($user_id){
    try
      {       
       $stmt = $this->db->prepare("DELETE FROM users WHERE id = :user_id");  
       $stmt->bindparam(":user_id", $user_id);          
       $stmt->execute(); 

           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }   
   }


    public function getUser($user_id,$name_field)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :user_id LIMIT 1");
          $stmt->execute(array(':user_id'=>$user_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
              return $userRow[$name_field];  
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }

       public function getAdmin($user_id)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE id = :user_id LIMIT 1");
          $stmt->execute(array(':user_id'=>$user_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

          if($stmt->rowCount() > 0)
          {
              return $userRow["is_admin"];
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }


   public function existUser($email){

       try
       {
          $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
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

   public function getTown($zipcode_id){

       try
       {
          $stmt = $this->db->prepare("SELECT Nom_commune FROM zipcode WHERE Code_postal = :zipcode LIMIT 1");
          $stmt->execute(array(':zipcode'=> $zipcode_id));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
              return $userRow['Nom_commune'];
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }

   public function getSelectUser($user_id)
   {

    try
    {
      $req = $this->db->prepare("SELECT first_name,last_name,id FROM users WHERE id <> :user_id ");
      $req->execute(array(':user_id'=> $user_id));
      $req->execute();

      if($req->rowCount() > 0)
      {
        while($userRow=$req->fetch())
        {
          echo "<option value='".$userRow['id']."' >".ucfirst($userRow['first_name'])." ".strtoupper($userRow['last_name'])."</option>";   
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