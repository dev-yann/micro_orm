<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 15/10/17
 * Time: 15:45
 */

namespace connection;


class connectionFactory
{

    protected static $connect = null;
    private $dsn;

    /*

    Cette méthode reçoit un tableau contenant les
    paramètres de connexion, établit cette connexion en créant un objet PDO, stocke cet objet
    dans une variable statique et la retourne en résultat. Elle est utilisée 1 seule fois au
    démarrage d'une application pour configurer la connexion à la base

    */

    public static function makeConnection(array $conf)
    {
        $dsn = 'mysql:host='.$conf["host"].';dbname='.$conf["base"];

        try{

            self::$connect = new \PDO($dsn, $conf['user'], $conf['pass'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING, \PDO::ATTR_PERSISTENT => true, \PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_STRINGIFY_FETCHES => false));
/* Beaucoup d'applications web utilisent des connexions persistantes aux serveurs de base de données. Les connexions
 persistantes ne sont pas fermées à la fin du script, mais sont mises en cache et réutilisées lorsqu'un autre script demande
 une connexion en utilisant les mêmes paramètres. Le cache des connexions persistantes vous permet d'éviter d'établir une
nouvelle connexion à chaque fois qu'un script doit accéder à une base de données, rendant l'application web plus rapide. */

// Emulate prepare : non pour l'émulation de php quand les pilotes ne sont pas à jour
/* PDO::ATTR_EMULATE_PREPARES Active ou désactive la simulation des requêtes préparées. Certainspilotes ne supportent pas nativement les requêtes préparées ou en ont un support limité. Ce paramètre force PDO à émuler (TRUE) les requêtes préparées ou (FALSE) à utiliser l'interface native. Il tentera toujours une émulation si le pilote n'a pas de support natif. bool requis.*/
// Convertit une valeur numérique en chaîne lors de la lecture.

            return self::$connect;

        } catch (\PDOException $e){

            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    public static function getConnection(){

            return self::$connect;
    }

    /*
     * Permet d'obtenir une connexion à condition qu'elle ait été créée
    auparavant. Cette méthode retourne le contenu de la variable statique, et s'utilise chaque fois
    que cela est nécessaire d'exécuter une requête sur la base de données – par exemple la
    classe Query.
    */



}