<?php
namespace Tools;
use Entity\Article;


/**
 * require_once('models/Entity/Article.php');
 */

abstract class Model
{
    protected static $_bdd;

    private static function setBdd(){   
        try {
                $filePath = 'config.json';
                //$filePath = __DIR__.'/config.json';

                $configfile = fopen($filePath, 'r');
                $config= json_decode(fread($configfile, filesize($filePath)));

                self::$_bdd = new \PDO('mysql:host='.$config->host.';dbname='.$config->dbname.';charset=utf8', $config->user, $config->pass);
                self::$_bdd->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);

            } catch
                (\PDOException $e) {
                    echo "<p>Erreur: " . $e->getMessage();
                    die();
                }
    }

    protected function getBdd(){
        if(self::$_bdd == null){
            self::setBdd();
            return self::$_bdd;
        }
        echo('ok fin fonction getBdd');
    }

    //ERREUR Entité
    protected function getAll($table, $obj){
        $this->getBdd();
        $var = [];
        $req  = self::$_bdd->prepare('SELECT * FROM '. $table.' ORDER BY id_article desc');  
        $req->execute();

        while ($data = $req->fetch(\PDO::FETCH_ASSOC)){
            //$var[] = new $obj($data);
            $var[] = new Article($data); //fonctionne !!!
        }
        return $var;
        $req->closeCursor();
    }
 
    
    protected function createOne($table, $obj){
        $this->getBdd();
        $req = self::$_bdd->prepare("INSERT INTO ".$table." (title, chapo, content, updateat) VALUES (?, ?, ?, ?)");
        $req->execute(array($_POST['title'], $_POST['chapo'], $_POST['content'], date("d.m.Y")));
        $req->closeCursor();
    }

    //ERREUR Entité
    protected function getOne($table, $obj, $id){ //Article
        $this->getBdd();
        $var = [];

        $req = self::$_bdd->prepare("SELECT id_article, title, content, DATE_FORMAT(updateat, '%d/%m/%Y à %Hh%imin%ss') AS date FROM " .$table. " WHERE id_article = ?");
        $req->execute(array($id));

        while ($data = $req->fetch(\PDO::FETCH_ASSOC)){
            //$var[] = new $obj($data);  // eg: Article || ERREUR
            $var[] = new Article($data);
        }
        return $var;
        $req->closeCursor();  
    }

    protected function deleteOne($table, $id){
        $this->getBdd();  
        $req = self::$_bdd->prepare("DELETE FROM $table WHERE id_article = $id");
        $req->execute(array());

    } 

    protected function updateOne($table, $id, $title, $chapo, $content){
        echo('Model.php updateOne');
        $this->getBdd();  
        $req = self::$_bdd->prepare("UPDATE $table SET title = $title, chapo = $chapo, content = $content WHERE id_article = $id");
        $req->execute(array());

    }   
}