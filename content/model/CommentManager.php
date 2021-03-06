<?php

namespace blog\model;

use blog\classes\Manager;
use blog\classes\Comment;
use PDO;

class CommentManager extends Manager
{
    public function findComment($id)
    {
        $req = $this->bdd->prepare("SELECT * , DATE_FORMAT(create_date, '%d/%m/%Y %Hh%imin%ss') AS create_date, DATE_FORMAT(edit_date, '%d/%m/%Y %Hh%imin%ss') AS edit_date FROM comments WHERE id = :id ");
        //$req = $this->bdd->prepare("SELECT DATE_FORMAT(create_date, '%d/%m/%Y %Hh%imin%ss') AS create_date FROM comments WHERE id = :id ");
        $req->bindValue(':id', $id, PDO::PARAM_INT); // définition de la valeur de :id soit le param $id de la fonction en var int
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC); //stock le résultat de la requête dans la var result
        $currentComment = new Comment();
        // hydratation du commentaire demandé
        $currentComment->setId($result['id']);
        $currentComment->setPseudo($result['pseudo']);
        $currentComment->setContent($result['content']);
        $currentComment->setCreateDate($result['create_date']);
        $currentComment->setEditDate($result['edit_date']);
        $currentComment->setReported($result['reported']);
        $currentComment->setAcquit($result['acquit']);
        $currentComment->setChapterId($result['chapter_id']);
        return $currentComment;
    }
    public function findAllComment()
    {
        $req = $this->bdd->prepare("SELECT *,DATE_FORMAT(create_date, '%d/%m/%Y à %Hh%i') AS create_date,DATE_FORMAT(edit_date, '%d/%m/%Y à %Hh%i') AS edit_date FROM comments ORDER BY id DESC");
        $req->execute();
        $comments = $req->fetchAll();
        return $comments;
    }
    public function findAllCommentPerChapter($id)
    {
        $req = $this->bdd->prepare("SELECT *, DATE_FORMAT(create_date, '%d/%m/%Y à %Hh%i') AS create_date, DATE_FORMAT(edit_date, '%d/%m/%Y à %Hh%i') AS edit_date FROM comments WHERE chapter_id = :chapter_id ORDER BY id DESC");
        $req->bindValue(':chapter_id', $id, PDO::PARAM_STR);
        $req->execute();
        $comments = $req->fetchAll();
        return $comments;
    }
    public function addComment($dataComment)
    {
        $pseudo = $dataComment['pseudo'];
        $content = $dataComment['values']['content'];
        $chapterId = $dataComment['id'];
        $req = $this->bdd->prepare('INSERT INTO comments (pseudo, content, chapter_id) VALUES(:pseudo, :content, :chapter_id)');
        $req->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $req->bindValue(':content', $content, PDO::PARAM_STR);
        $req->bindValue(':chapter_id', $chapterId, PDO::PARAM_INT);
        $req->execute();
    }
    public function updateComment($dataComment)
    {
        $content = $dataComment['content'];
        $id = $dataComment['id'];
        $req = $this->bdd->prepare('UPDATE comments SET  content = :content, edit_date = NOW() WHERE id = :id');
        $req->bindValue(':content', $content, PDO::PARAM_STR);
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
    }
    public function deleteComment($id)
    {
        $req = $this->bdd->prepare('DELETE FROM comments WHERE id = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
    }
    public function reportComment($currentComment)
    {
        $reported = 1;
        $id = $currentComment->getId();
        $req = $this->bdd->prepare('UPDATE comments SET reported = :reported WHERE id = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':reported', $reported, PDO::PARAM_INT);
        $req->execute();
    }
    public function acquitComment($currentComment)
    {
        $acquit = 1;
        $reported = 0;
        $id = $currentComment->getId();
        $req = $this->bdd->prepare('UPDATE comments SET acquit = :acquit, reported = :reported WHERE id = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':acquit', $acquit, PDO::PARAM_INT);
        $req->bindValue(':reported', $reported, PDO::PARAM_INT);
        $req->execute();
    }
}
