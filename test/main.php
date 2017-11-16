<?php
/**
 * Created by PhpStorm.
 * User: yann
 * Date: 13/10/17
 * Time: 11:28
 */

require_once ('../vendor/autoload.php');
//require_once ('../src/query/Query.php');

use query\Query;
use connection\connectionFactory;

$conf = parse_ini_file('../conf.ini');

// connection
$pdo = connectionFactory::makeConnection($conf);


//$query = new Query();

//print_r($query::table('article')->select(array('nom','descr'))/*->where('iduser','=','1')*/->get());
//print_r($query::table('article')->insert(array("nom" => "Richard", "Prenom" => "Thomas")));
//$query::table('article')->insert(['nom'=> 'scooter','descr'=>'scooter avec un pot bidalo','tarif'=>500,'id_categ'=>1]);
//$query::table('article')->where('id','=','67')->update(['descr'=>"scooter kit 70"]);
//$query::table('article')->where('id','=','67')->delete();


// définition des attributs

/*$b = new model\Article();
$b->nom = 'velo';
$b->descr = 'velo décathlon';
$b->tarif = 300;
$b->id_categ = 1;



$b->insert();*/
echo "<h1>Affiche tous les articles</h1>";
$c = \model\Article::all();

foreach ($c as $article){

    echo '<p>'.$article->nom.'</p>';
}


echo "<h1>find de l'id 64</h1>";

$find1 = \model\Article::find(64);
print_r($find1);

echo "<h1>find de l'id 64 avec choix des collones</h1>";

$l = \model\Article::find(64,['id','tarif']);
print_r($l);

echo "<h1>find avec critère de recherche et choix des collones</h1>";

$pls = \model\Article::find(['tarif','<=',250],['id','tarif']);
print_r($pls);

echo "<h1>find avec pls critères de recherche</h1>";
$k = \model\Article::find([['nom ','like ','%biclou%'],['tarif','<=',220]]);
print_r($k);


echo "<h1>first de l'id 64</h1>";
$first = \model\Article::first(64);
print_r($first);

echo "<h1>first des velo de moins de 400€</h1>";
$first = \model\Article::first(['tarif','<=',400]);
print_r($first);


echo "<h1>Categorie associé à mon article</h1>";

$a= \model\Article::first(64);
$categorie = $a->categorie();
print_r($categorie);

echo "<h1>Articles associés à ma categorie</h1>";
$c = \model\Categorie::first(1);
$articles = $c->articles();
print_r($articles);

echo "<h1>Modification de la méthode magique _get</h1>";
$c = \model\Categorie::first(1);
$list_articles = $c->articles;
print_r($list_articles);

