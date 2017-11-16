<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 12/11/17
 * Time: 15:42
 */

namespace model;


class Categorie extends Model
{

    protected static $table = 'categorie';
    protected static $pk = 'id';

    public function articles(){

        return $this->has_many('\model\Article','id_categ');
    }

}