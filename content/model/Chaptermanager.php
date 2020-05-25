<?php

namespace blog\model;

use blog\classes\Manager;
use PDO;

class ChapterManager extends Manager //gère la connection à la bdd par son parent et à la table chapter
{

    public function findAllChapter()
    {
        $bdd = $this->bdd;
        $query = "SELECT * FROM chapters ORDER BY id";
        $req = $bdd->prepare($query);
        $req->execute();
        $chapters = $req->fetchAll();
        return $chapters;
    }

    public function findChapter($id)
    {
        $bdd = $this->bdd;
        $query = "SELECT * FROM chapters WHERE id = :id ";
        $req = $bdd->prepare($query);
        $req->bindValue(':id', $id, PDO::PARAM_INT); // définition de la valeur de :id soit le param $id de la fonction en var int
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC); //stock le résultat de la requête dans la var result
        $currentChapter = new Chapter();
        // hydratation du chapitre demandé
        $currentChapter->setId($result['id']);
        $currentChapter->setTitle($result['title']);
        $currentChapter->setContent($result['content']);
        $currentChapter->setCreateDate($result['create_date']);
        $currentChapter->seteditDate($result['edit_date']);
        return $currentChapter;
    }

    public function addChapter($dataChapter)
    {
  
        $bdd = $this->bdd;
        $title = $dataChapter['title'];
        $content = $dataChapter['content'];
        $req = $bdd->prepare('INSERT INTO chapters (title, content) VALUES(:title, :content)');
$req->execute(array(
    'title' => $title,
    'content' => $content,   
));

    }
}
