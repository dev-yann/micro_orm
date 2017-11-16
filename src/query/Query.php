<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 13/10/17
 * Time: 09:41
 */
namespace query;

use connection\connectionFactory;

class Query {


    // Attribut permettant la construction de la requête
    private $sqlTable;
    private $fields = '*';
    private $where = null;
    private $args = array();
    private $sql = "";


    // Cette méthode définie l'attribut sqltable (le nom de la table) et renvoi l'objet query :
    public static function table(string $table){

        $query = new Query;
        $query->sqlTable = $table;
        return $query;

    }

    // Fonction qui définis la sélection sql, prend en parametre un tableau des colonnes
    public function select(array $fields){

        // implode rassemble les valeur du tableau
        $this->fields = implode(',',$fields);
        return $this;

    }

    // Cette méthode prend en paramètre colonnes, opérateur et valeur, elle construit la condition where
    public function where($col, $op, $val){

        // On met tte les valeurs de la requetes dans le tableau args pour prepare

        // si non null on ajoute
        if(!is_null($this->where)){

            $this->where .= ' AND '.$col.$op.'?';
            $this->args[] = $val;// $this->args permet de récuperer les valeurs qui seront utilisées dans la req préparée

        } else {

            //sinon on ajoute directement la clause
            $this->args[] = $val;
            $this->where = $col.$op.'?';

        }

        return $this;

    }

    public function get(){


        if(isset($this->where)){

            $this->sql = 'SELECT '. $this->fields . ' FROM '. $this->sqlTable.' WHERE '.$this->where;

        } else {

            $this->sql = 'SELECT '. $this->fields . ' FROM '. $this->sqlTable;

        }

        $pdo = connectionFactory::getConnection();

        // Pour débuger si besoin
        echo $this->sql;

        $rq = $pdo->prepare($this->sql);
        $rq->execute($this->args);
        return $rq->fetchAll(\PDO::FETCH_ASSOC);

    }

    // Fonction qui doit suprimer une ligne dans une table en fonction de l'id renseignée dans la mth where
    public function delete(){

        if(!is_null($this->where)){

            $this->sql = 'DELETE FROM '. $this->sqlTable .' WHERE '.$this->where;

            $pdo = connectionFactory::getConnection();
            $rq = $pdo->prepare($this->sql);
            $rq->execute($this->args);

        }



    }

    // insertion d'un tableau sous forme Clé (colonne) => Valeur
    // Ne pas utiliser where avec sinon duplication des arguments
    public function insert($tab){

        $tabKey = array();// tableau des key
        $tabValue = array();// tableau des values

        foreach ($tab as $key => $values){

            $tabKey[]= $key;
            $this->args[]= $values;
            $tabValue[]= '?';

        }

        // transforme en string pour être lisible en sql
        $stringKey = implode(",",$tabKey);
        $stringValue = implode(",",$tabValue);

        $this->sql = "INSERT INTO ".$this->sqlTable." ($stringKey) VALUES ($stringValue)";

        $pdo = connectionFactory::getConnection();
        $rq = $pdo->prepare($this->sql);
        $rq->execute($this->args);

        // On retourne l'id auto incrémenter
        return $pdo->lastInsertId();


        // Note : On ne vérifie pas la similitude entre clé et valeur
        // Note : On ne vérifie si la valeur est int ou string
        // Note : On ne vérifie pas la similitude entre clé et valeur
    }

   public function update($tab){

       $tabKey = array();// tableau des key
       $tabValue = array();// tableau des values

       foreach ($tab as $key => $values){

           $tabKey[]= "$key=?";
           $this->args[]= $values;

       }

        // la clause where se retrouve dans set , necessite reverse
       $tabReverse = array_reverse($this->args);

       $set = implode(',',$tabKey);
       $this->sql = 'UPDATE '.$this->sqlTable.' SET '.$set.' WHERE '.$this->where;


       /*echo $this->sql;
       var_dump($this->args);*/
      $pdo = connectionFactory::getConnection();
       $rq = $pdo->prepare($this->sql);
       $rq->execute($tabReverse);

   }
}