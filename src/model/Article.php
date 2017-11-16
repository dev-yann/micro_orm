<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 27/10/17
 * Time: 09:48
 */

namespace model;
use model\Model;

class Article extends Model
{

    protected static $table = 'article';
    protected static $pk = 'id';


    public function categorie(){

        return $this->belongs_to('\model\Categorie','id_categ');
    }

}