<?php

namespace DL\CvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CvController extends Controller
{
    public function indexAction()
    {
        return $this->render('DLCvBundle::accueil.html.twig');
    }

    public function competAction(){
        return $this->render('DLCvBundle:competences:compet.html.twig');
    }

    public function profilAction(){
        return $this->render('DLCvBundle:profil:profil.html.twig');
    }

    public function advertAction(){
        return $this->render('DLCvBundle:annonces:annonces.html.twig');
    }

    public function chatAction(){

        try {
            $bdd = new \PDO('mysql:host=db592158816.db.1and1.com;dbname=db592158816', 'dbo592158816', 'dbDlamcht972');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $reponse = $bdd->query(
            'SELECT pseudo, message, DATE_FORMAT(date_message, "%d/%m/%Y-%T") AS date_fr FROM discussions ORDER BY date_message ASC'
        );

        $donnees=$reponse->fetchAll();

        //while ($donnees = $reponse->fetch()) {
        //    echo '<p>[' . htmlspecialchars($donnees['date_fr']) . '] ' . htmlspecialchars(
        //            $donnees['pseudo']
         //       ) . ' : ' . htmlspecialchars($donnees['message']) . ' </p>';
        //}
        //$reponse->closeCursor();


        return $this->render('DLCvBundle:discussion:discussion.html.twig', array(
                'donnees'=>$donnees
        ));
    }

    public function traitementAction(){

        If (isset($_POST['nom'])) {
            // on définit une durée de vie de notre cookie (en secondes), donc un an dans notre cas
            $temps = 365*24*3600;

            // on envoie un cookie de nom pseudo portant la valeur de la variable $nom, c'est-à-dire la valeur qu'a saisi la personne qui a rempli le formulaire
            setcookie ("pseudoc", $_POST['nom'], time() + $temps);
        }
        // Puis on redirige vers la page de tchat
        return $this->redirect($this->generateUrl('dl_cv_discussion'));
    }

    public function writeAction(){

        try
        {
            $bdd = new \PDO('mysql:host=db592158816.db.1and1.com;dbname=db592158816', 'dbo592158816', 'dbDlamcht972');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        $req = $bdd->prepare('INSERT INTO discussions(pseudo, message, date_message) VALUES(?, ?, NOW())');
        $req->execute(array($_COOKIE['pseudoc'],$_POST['message'],));

        return $this->redirect($this->generateUrl('dl_cv_discussion'));
    }
}
