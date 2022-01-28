<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

use Playground\CookiejarBundle\Entity;
use Playground\CookiejarBundle\Cookie;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use aharen\OMDbAPI;


class Description
{   
    private static $api_key_ceif= 'f8b2f3c7';
    
    public function getDescriptionFromApi(){
        $omdb = new OMDbAPI(self::$api_key_ceif);
        $filmExist = $omdb->fetch('t', $_POST["movie"]["name"]);
        if($filmExist->data->Response == 'False'){
            return "Le film n\'existe pas";
        }
        else{
            return $filmExist;
        }
        
    }
}