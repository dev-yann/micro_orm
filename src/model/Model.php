<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 27/10/17
 * Time: 09:47
 */
namespace model;
use query\Query;
abstract class Model
{
    private $attrTab = array();


    public function __construct($tab = [])
    {

        $this->attrTab = $tab;
        var_dump($this->attrTab);


    }

    public function __get($attr_nom){

        if(array_key_exists($attr_nom,$this->attrTab)){

            return $this->attrTab[$attr_nom];

        } elseif (method_exists($this,$attr_nom)){
            // Pour chercher la méthode sans ses parenthèses
            return $this->$attr_nom();

        } else {

            throw new \Exception('L\'attribut n\'éxiste pas');
        }

    }

    public function __set($attr_nom, $valeur_nom)
    {

        $this->attrTab[$attr_nom] = $valeur_nom;

    }

    public function delete(){

        // static:: représente la classe courante
        Query::table(static::$table)->where(static::$pk,'=',$this->id)->delete();

    }

    public function insert(){

        $this->id = Query::table(static::$table)->insert($this->attrTab);
    }


    // retourne un tableau d'objet
    public static function objetModel($tab){

        $objectTab = array();

        foreach ($tab as $key => $value){

            $object = new static();

            foreach ($value as $key2 => $value2){

                $object->$key2 = $value2;
            }

            $objectTab[]=$object;
        }

        // on renvoit un tableau d'objet modele
        return $objectTab;

    }

    public static function all(){

        $tab = Query::table(static::$table)->get();
        return self::objetModel($tab);


    }

    public static function find($id, array $tab = []){

        // id est la condition
        // tab contient les colonnes

        if(empty($tab)){

            if(is_array($id)){

                // si id est un tab et index 0 = array alors
                if(is_array($id[0])){

                    $search = Query::table(static::$table);
                    foreach ($id as $key => $value){

                        $search = $search->where($value[0],$value[1],$value[2]);

                    }
                $search = $search->get();
                return self::objetModel($search);

                } else {

                    $search = Query::table(static::$table)->where($id[0],$id[1],$id[2])->get();

                }


            } else {

                $search = Query::table(static::$table)->where('id','=',$id)->get();

            }

        } else {

            if(is_array($id)){


                $search = Query::table(static::$table)->select($tab)->where($id[0],$id[1],$id[2])->get();


            } else {

                $search = Query::table(static::$table)->select($tab)->where('id', '=', $id)->get();


            }


        }

        $tab = $search;
        return self::objetModel($tab);
    }

    public static function first($id, array $tab = []){


        $search = self::find($id,$tab);
        return $search[0];

    }

    protected function belongs_to($model,$foreignKey){

        $id = static::$pk;
        // je recup la valeur de id_categ
        $search = self::first([static::$pk,'=',$this->$id],[$foreignKey]);


        //echo "<h1>".$search->$foreignKey."</h1>";
        //maintenant que j'ai l'id categ j'instancie la categorie
        $instance = new $model();
        $resultInstance = $instance::first([$instance::$pk,"=",$search->$foreignKey]);

        return $resultInstance;
    }

    protected function has_many($model,$foreignKey){

        $id = static::$pk;
        // On récupere l'id de la categorie
        // static $pk = 'id'; $this->$id = $this->id
        $search = self::first([static::$pk,'=',$this->$id],[static::$pk]);

        $articles = new $model();
        $resultArticles = $articles::find([$foreignKey,'=',$search->$id]);

        return $resultArticles;
    }
}