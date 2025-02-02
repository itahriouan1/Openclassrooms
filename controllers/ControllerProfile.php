<?php
namespace Controllers;
session_start();

use Manager\UserManager;
use View\View;
use Tools\Security;


 class ControllerProfile
 {
    private $usermanager;
    private $_view; 

    public function __construct(){
        if(isset($url) && count($url) > 1){ 
            throw new \Exception("Page introuvable", 1);
        } 
        else{
            $this->myProfile();  
        }       
    }

    //Affiche un contenu simple pas de repository
    private function myProfile(){
        if(($user=Security::retrieveUserObj('MEMBER'))!=null){
            //get User connected ok
            $this->usermanager = new UserManager;
            $data = $this->usermanager->ProfilUser();
            //View
            $this->_view = new View('Profile', 'Profile');
            $this->_view->displayForm('Profile',$data); 
        }else{
             header('Location: accueil&comment=notconnected' );
            }    
    }
    //manque usertype
}