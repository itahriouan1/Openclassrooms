<?php
/**
 *
*/
//require_once('framework/models/Entity/Article.php'); // à enlever bizarre

abstract class Model
{
    protected static $_bdd;



    private static function setBdd(){
        self::$_bdd = new PDO('mysql:host=localhost;dbname=app_blog_mvc;charset=utf8','root','');
        self::$_bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        echo('ok fin fonction setBdd');
    }

    protected function getBdd(){
        if(self::$_bdd == null){
            self::setBdd();
            return self::$_bdd;
        }
        echo('ok fin fonction getBdd');
    }




    //Cette function est appele par une fonction de xxxxManager.php qui lui passe 2 arguments.
    protected function getAll($table, $obj){

        echo('ok function getAll'); 
        echo($table);
        echo($obj);
        

        $this->getBdd();
        $var = [];

        // order by id desc    
        $id = 'id' . '_' . $table;
        echo($id);
         


        //definir id_article pour variable
        $req  = self::$_bdd->prepare('SELECT * FROM '. $table.' ORDER BY id_article desc');  
        $req->execute();


        while ($data = $req->fetch(PDO::FETCH_ASSOC)){
            $var[] = new $obj($data); //le tableau vas instancie un nouvel Article.php en lui passant
        }

        return $var;
        $req->closeCursor();
        echo('Le code passe ici');
    }




    // ancienne version id standard pour toute les tables
    //personnaliser id par table
    protected function getOne($table, $obj, $id){
        
        var_dump($id);

        $this->getBdd();
        $var = [];
        $req = self::$_bdd->prepare("SELECT id, title, content, DATE_FORMAT(date, '%d/%m/%Y à %Hh%imin%ss') AS date FROM " .$table. " WHERE id = ?");
        $req->execute(array($id));

        while ($data = $req->fetch(PDO::FETCH_ASSOC)){
            $var[] = new $obj($data);  
            
        }
        return $var;
        $req->closeCursor();  
        
        
    }
    protected function deleteOne($table, $id){
        $this->getBdd();  
    }   
    
    protected function createOne($table, $obj){
        $this->getBdd();
        $req = self::$_bdd->prepare("INSERT INTO ".$table." (title, content, date) VALUES (?, ?, ?)");
        $req->execute(array($_POST['title'], $_POST['content'], date("d.m.Y")));

        $req->closeCursor();
    }

    
    

}