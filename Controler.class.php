<?php
/**
 * Class Controler
 * Gère les requêtes HTTP
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */
class Controler 
{
    /**
     * Traite la requête
     * @return void
     */
    public function gerer()
    {
        switch ($_GET['requete']) {
            case 'listeBouteilleCellier':
                if (isset($_SESSION['user_pseudo'])){
                    $this->afficheBouteillesCellier();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'getBouteilleSaq':
                if (isset($_SESSION['user_pseudo'])){

                    $this->getBouteilleSAQ();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'autocompleteBouteille':
                $this->autocompleteBouteille();
                break;
            case 'nouvelleBouteilleCellier':
                if (isset($_SESSION['user_pseudo'])){
                    $this->nouvelleBouteilleCellier();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'modifierBouteille':
                if (isset($_SESSION['user_pseudo'])){
                    $this->modifierBouteille();
                }
                else{
                    header("Location: index.php?requete=login");
                }
                break;
            case 'supprimerBouteille':
                if (isset($_SESSION['user_pseudo'])){
                    $this->supprimerBouteille();
                }
                else{
                    header("Location: index.php?requete=login");
                }
                break;
            case 'ajouterBouteilleCellier':
                if (isset($_SESSION['user_pseudo'])){
                    $this->ajouterBouteilleCellier();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'boireBouteilleCellier':
                if (isset($_SESSION['user_pseudo'])){
                    $this->boireBouteilleCellier();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'inscription':
                $this->formInscription();
                break;
            case 'ajoutUsager':
                if (isset($_POST['ajouterUsager'])){
                     if (trim($_POST['nom']) != "" || trim($_POST['prenom'])||trim($_POST['mail']) != "" || trim($_POST['password']) != "" || trim($_POST['pseudo']) != "") {
                         $this->ajoutUsager($_POST["nom"], $_POST["prenom"], $_POST['mail'], $_POST['password'], $_POST['pseudo']);
                         $this->formlogin();
                     }
                     else{
                         $this->formInscription();
                     }
                }
                else{
                    header("Location:index.php?requete=login");
                }
//					$this->ajoutUsager();
                break;
            case 'login':
                    $this->formlogin();
                break;
            case 'logedin':
                if (isset($_POST['btnLogin'])){
                    if (trim($_POST['identifiant']) != "" || trim($_POST['password'])){
                        $this->connexion($_POST['identifiant'],$_POST['password']);
                        if (isset($_SESSION['user_pseudo'])){

                            $this->accueil();
                        }
                        else{
                            $errorMessage = 'Identifiant ou mot de passe incorrect';
                            $this->formlogin($errorMessage);
                        }

                    }
                }
                else{
                    header("Location:index.php?requete=login");
                }

//
                break;
            case 'accueil':
                if (isset($_SESSION['user_pseudo'])){
//					var_dump($_SESSION['user_pseudo']);
                    $this->accueil();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            case 'logout':
                $_SESSION = array();

                // Delete la session en lui assignant un tableau vide et le cookie de session en créant
                // un nouveau cookie avec la date d'expiration dans le passé
                if(isset($_COOKIE[session_name()]))
                {
                    setcookie(session_name(), '', time() - 3600);
                }
                session_destroy();
                header('location:index.php?requete=login');
                break;
            case 'ajoutCellier':
                if (isset($_SESSION['user_pseudo'])) {
                    $this->ajoutCellier();
                }
                else{
                    header("Location:index.php?requete=login");
                }
                break;
            default:
                $this->formlogin();
                break;
        }
    }

    private function accueil()
    {
        $cellier = new Cellier();
        $data = $cellier->getUsagerCellier($_SESSION['user_id']);
        include("vues/entete.php");
        include("vues/listeCelliers.php");
        include("vues/pied.php");

    }

    private function afficheBouteillesCellier($idCellier){
        $usr = new Usager();

        if ($usr->estProprietaireCellier($_SESSION['user_pseudo'], $_GET['idCellier'])) {
            $bte = new Bouteille();  
            $data['idCellier'] = $_GET['idCellier'];
            $data['listeBouteilles'] = $bte->getListeBouteillesCellier($_GET['idCellier']);
            include("vues/entete.php");
            include("vues/listeBouteilles.php");
            include("vues/pied.php");
        }
    }

    private function getBouteilleSAQ()
    {
        $bte = new Bouteille();
        $body = json_decode(file_get_contents('php://input'));
        $bouteilleSaq = $bte->getBouteilleSaq($body->id_bouteille_saq);
        echo json_encode($bouteilleSaq);
    }

    private function autocompleteBouteille()
    {
        $bte = new Bouteille();
        $body = json_decode(file_get_contents('php://input'));
        $listeBouteille = $bte->autocomplete($body->nom);            
        echo json_encode($listeBouteille);

    }

    private function nouvelleBouteilleCellier()
    {
        $bte = new Bouteille();
        $usr = new Usager();

        if ($usr->estProprietaireCellier($_SESSION['user_pseudo'], $_GET['idCellier'])) {
            switch($_SERVER['REQUEST_METHOD']){
                case 'GET':
                    $data['bouteille'] = [];                    
                    $data['idCellier'] = $_GET['idCellier'];
                    $data['types'] = $bte->getTypes();
                    include("vues/entete.php");
                    include("vues/formBouteille.php");
                    include("vues/pied.php");
                    break;

                case 'POST':
                    $bouteille = [];

                    foreach ($_POST as $cle => $valeur) {
                        $bouteille[$cle] = !empty($valeur) ? $valeur : null;
                    }

                    $bte->ajouterBouteilleCellier($bouteille);
                    header("Location: index.php?requete=listeBouteilleCellier&idCellier=" . $bouteille['id_cellier']);
                    break;
            }
        }
    }

    private function modifierBouteille()
    {
        $bte = new Bouteille();
        $usr = new Usager();

        if ($usr->estProprietaireBouteille($_SESSION['user_pseudo'], $_GET['idBouteille'])) {
            switch($_SERVER['REQUEST_METHOD']){
                case 'GET':
                    $data['bouteille'] = $bte->getBouteille($_GET['idBouteille']);  
                    $data['types'] = $bte->getTypes();                    
                    include("vues/entete.php");
                    include("vues/formBouteille.php");
                    include("vues/pied.php");
                    break;

                case 'POST':
                    $bouteille = [];

                    foreach ($_POST as $cle => $valeur) {
                        $bouteille[$cle] = !empty($valeur) ? $valeur : null;
                    }

                    $bte->modifierBouteille($bouteille);
                    header("Location: index.php?requete=listeBouteilleCellier&idCellier=" . $bouteille['id_cellier']);
                    break;
            }
        }    
    }

    private function supprimerBouteille()
    {
        $body = json_decode(file_get_contents('php://input'));
        $usr = new Usager();

        if ($usr->estProprietaireBouteille($_SESSION['user_pseudo'], $body->id)) {
            $bte = new Bouteille();
            $res = $bte->supprimerBouteille($body->id);
        }
        else {
            $res = false;
        }

        echo json_encode($res);
    }

    private function boireBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));
        $usr = new Usager();

        if ($usr->estProprietaireBouteille($_SESSION['user_pseudo'], $body->id)) {
            $bte = new Bouteille();
            $resultat = $bte->modifierQuantiteBouteilleCellier($body->id, -1);
            echo json_encode($resultat);
        }
    }

    private function ajouterBouteilleCellier()
    {
        $body = json_decode(file_get_contents('php://input'));
        $usr = new Usager();

        if ($usr->estProprietaireBouteille($_SESSION['user_pseudo'], $body->id)) {
            $bte = new Bouteille();
            $resultat = $bte->modifierQuantiteBouteilleCellier($body->id, 1);
            echo json_encode($resultat);
        }
    }

    private function formInscription(){
        include ('vues/entete.php');
        include ('vues/ajoutUsager.php');
        include ('vues/pied.php');

    }

    private function ajoutUsager($nom,$prenom,$mail,$password,$pseudo){
//            $body = json_decode(file_get_contents('php://input'));
//
//            if(!empty($body)){
//                $usager = new Usager();
//                //var_dump($_POST['data']);
//
//                //var_dump($data);
//                $resultat = $usager->ajoutNouveauUsager($body);
//                echo json_encode($resultat);
//            }

        $usager = new Usager();
        $usager->ajoutNouveauUsager($nom,$prenom,$mail,$password,$pseudo);



    }

    private function formlogin($errorMessage =""){
        $dataMessage = $errorMessage;
        include ('vues/entete.php');
        include ('vues/login.php');
        include ('vues/pied.php');
    }

    private function connexion($identifiant,$mdp){
//            $body = json_decode(file_get_contents('php://input'));
//            if(!empty($body)){
//                $usager = new Usager();
//                $resultat = $usager->login($body);
//                echo json_encode($resultat);
//            }

        $usager = new Usager();
        $usager->login($identifiant,$mdp);
    }

    private function ajoutCellier(){
        $body = json_decode(file_get_contents('php://input'));
        if(!empty($body)){
            $cellier = new Cellier();
            $resultat = $cellier->ajoutCellierUsager($body);
            echo json_encode($resultat);
        }
    }
}
?>















