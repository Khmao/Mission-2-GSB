<?php
require_once __DIR__.'/../modele/class.pdogsb.php';
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

//********************************************Contrôleur connexion*****************//
class ConnexionControleur{

    public function __construct(){
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
    }
    public function accueil(){
        require_once __DIR__.'/../vues/v_connexion.php';
        require_once __DIR__.'/../vues/v_pied.php';
        $view = ob_get_clean(); // récupère le contenu du flux et le vide
        return $view;     // retourne le flux 
    }
    public function verifierUser(Request $request, Application $app){
        session_start();
        //recupere le login et le met en entite ( pour eviter les failles xss)
        $login = htmlentities($request->get('login'));
        $mdp =  htmlentities($request->get('mdp'));
        $pdo = PdoGsb::getPdoGsb();
        $visiteur = $pdo->getInfosVisiteur($login,$mdp);
        $gestionnaire = $pdo->getInfosGestionnaire($login,$mdp);
        if((!is_array( $visiteur)) && (!is_array( $gestionnaire)) ){
            $app['couteauSuisse']->ajouterErreur("Login ou mot de passe incorrect");
            require_once __DIR__.'/../vues/v_erreurs.php';
            require_once __DIR__.'/../vues/v_connexion.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
        }
        else{     
            if(is_array( $visiteur)){
                $id = $visiteur['id'];
                $nom =  $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                $app['couteauSuisse']->connecter($id,$nom,$prenom);
                require_once __DIR__.'/../vues/v_sommaire.php';
                require_once __DIR__.'/../vues/v_pied.php';
                $view = ob_get_clean();}
                else {
                    $id = $gestionnaire['id'];
                    $nom =  $gestionnaire['nom'];
                    $prenom = $gestionnaire['prenom'];
                    $app['couteauSuisse']->connecterGestionnaire($id,$nom,$prenom);
                    require_once __DIR__.'/../vues/v_sommaire.php';
                    require_once __DIR__.'/../vues/v_pied.php';
                    $view = ob_get_clean();}

                }
                return $view;        
            }
            public function deconnecter(Application $app){
                $app['couteauSuisse']->deconnecter();
                return $app->redirect('../public/');
            }
        }
//**************************************Contrôleur EtatFrais**********************

        class EtatFraisControleur {
           private $idVisiteur;
           private $pdo;
           public function init(){
            $this->idVisiteur = $_SESSION['idVisiteur'];
            $this->pdo = PdoGsb::getPdoGsb();
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaire.php';
        
    }
    public function selectionnerMois(Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init();
            $lesMois = $this->pdo->getLesMoisDisponibles($this->idVisiteur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCles = array_keys( $lesMois );
            $moisASelectionner = $lesCles[0];
            require_once __DIR__.'/../vues/v_listeMois.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view;
        }
        else{
            return Response::HTTP_NOT_FOUND;
        }
    }
    public function voirFrais(Request $request,Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init();
            $leMois = htmlentities($request->get('lstMois'));
            $this->pdo = PdoGsb::getPdoGsb();
            $lesMois = $this->pdo->getLesMoisDisponibles($this->idVisiteur);
            $moisASelectionner = $leMois;
            $lesFraisForfait= $this->pdo->getLesFraisForfait($this->idVisiteur,$leMois);
            $lesInfosFicheFrais = $this->pdo->getLesInfosFicheFrais($this->idVisiteur,$leMois);
            $numeroAnnee = substr( $leMois,0,4);
            $numeroMois = substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  $app['couteauSuisse']->dateAnglaisVersFrancais($dateModif);
            require_once __DIR__.'/../vues/v_listeMois.php';
            require_once __DIR__.'/../vues/v_etatFrais.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view;
        }
        else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
    } 
}
//************************************Controleur GererFicheFrais********************

Class GestionFicheFraisControleur{
    private $pdo;
    private $mois;
    private $idVisiteur;
    private $numAnnee;
    private $numMois;
    
    public function init(Application $app){
        $this->idVisiteur = $_SESSION['idVisiteur'];
        ob_start();
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaire.php';
        $this->mois = $app['couteauSuisse']->getMois(date("d/m/Y"));
        $this->numAnnee =substr($this->mois,0,4);
        $this->numMois =substr( $this->mois,4,2);
        $this->pdo = PdoGsb::getPdoGsb();
        
    }

    public function saisirFrais(Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init($app);
            if($this->pdo->estPremierFraisMois($this->idVisiteur,$this->mois)){
                $this->pdo->creeNouvellesLignesFrais($this->idVisiteur,$this->mois);
            }
            $lesFraisForfait = $this->pdo->getLesFraisForfait($this->idVisiteur,$this->mois);
            $numMois = $this->numMois;
            $numAnnee = $this->numAnnee; 
            require_once __DIR__.'/../vues/v_listeFraisForfait.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view; 
        }
        else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
    }
    public function validerFrais(Request $request,Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init($app);
            $lesFrais = $request->get('lesFrais');
            if($app['couteauSuisse']->lesQteFraisValides($lesFrais)){
                $this->pdo->majFraisForfait($this->idVisiteur,$this->mois,$lesFrais);
            }
            else{
                $app['couteauSuisse']->ajouterErreur("Les valeurs des frais doivent être numériques");
                require_once __DIR__.'/../vues/v_erreurs.php';
                require_once __DIR__.'/../vues/v_pied.php';
            }
            $lesFraisForfait= $this->pdo->getLesFraisForfait($this->idVisiteur,$this->mois);
            $numMois = $this->numMois;
            $numAnnee = $this->numAnnee; 
            require_once __DIR__.'/../vues/v_listeFraisForfait.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view; 
        }
        else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
        
    }
}
 //************************************Controleur Supression********************

Class SuppressionVisiteurControleur {

    private $idGestionnaire;
    private $pdo;

    public function init(){
        $this->idGestionnaire = $_SESSION['idGestionnaire'];
        $this->pdo = PdoGsb::getPdoGsb();
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaire.php';
        
    }
    //si la personne est connecté en gestionnaire alors la variable lesVisiteurs prend les données des visiteurs de la base de donnée
    public function selectionnerVisiteur(Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecteG()){
            $this->init();
            $lesVisiteurs = $this->pdo->getPrenomNomVisiteur();
            require_once __DIR__.'/../vues/v_supprimer.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view;
        }
        else{
            return Response::HTTP_NOT_FOUND;
        }
    }

// recup l'id du visiteur et recup les infos de la personne qui a cette id
    public function afficherConfirmation(Request $request, Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecteG()){
          $this->init();
            //recup l'id visiteur selectionner et l'affecte a $suppVIsiteur
          $suppVisiteur = htmlentities($request->get('suppVisiteur'));
            //envoi l'id visiteur dans la fonction pdo , recup les infos de la personne pour l'id visiteur et l'affect a $infovisiteur
          $infoVisiteur = $this->pdo->getInfoVisiteur($suppVisiteur);
          $nbFicheFrais = $this->pdo->getnbFicheFrais($suppVisiteur);
          $nbFicheFraisNonRembourser = $this->pdo->getnbFicheFraisNonRembourser($suppVisiteur);
          $LigneFraisForfaitVisiteur = $this->pdo->getLigneFraisForfait($suppVisiteur);
          require_once __DIR__.'/../vues/v_confirmSupp.php';
          require_once __DIR__.'/../vues/v_pied.php';
          $view = ob_get_clean();
          return $view;
      }
  }

    // si validation de la page v_confirmSupp alors on envoi les données de la personne dans la table archive et on le supprime de la table visiteur, fraisforfait et lignefraisforfait
  public function confirmation(Request $request, Application $app){
    session_start();
    if($app['couteauSuisse']->estConnecteG()){
      $this->init();
          //recup l'id visiteur qu'on affiche et l'affecte a $idVIsiteur ( on aurait pu le faire par session aussi)
      $idVisiteur = htmlentities($request->get('idVisiteur'));
      $this->pdo->archivageVisiteur($idVisiteur);
      $this->pdo->supprimerVisiteur($idVisiteur);
      $lesVisiteurs = $this->pdo->getPrenomNomVisiteur();
      require_once __DIR__.'/../vues/v_supprimer.php';
      require_once __DIR__.'/../vues/v_pied.php';
      $view = ob_get_clean();
      return $view;
  }
}

    //envoie les données des personnes de la table archive dans la variable pour faire une restauration
public function selectionnerVisiteurArchive(Application $app){
    session_start();
    if($app['couteauSuisse']->estConnecteG()){
        $this->init();
        $lesVisiteursArchives = $this->pdo->getPrenomNomVisiteurArchive();
        require_once __DIR__.'/../vues/v_archive.php';
        require_once __DIR__.'/../vues/v_pied.php';
        $view = ob_get_clean();
        return $view;
    }
    else{
        return Response::HTTP_NOT_FOUND;
    }
}
    // annul la suppression et restaure la personne
public function restaurerVisiteur(Request $request, Application $app){
   session_start();
   if($app['couteauSuisse']->estConnecteG())
   {
      $this->init();
      $idVisiteurArchiver = htmlentities($request->get('visiteurArchiver'));
      $this->pdo->restaurer($idVisiteurArchiver);
      $this->pdo->supprimerArchivage($idVisiteurArchiver);

      $lesVisiteurs = $this->pdo->getPrenomNomVisiteur();
      require_once __DIR__.'/../vues/v_supprimer.php';
      require_once __DIR__.'/../vues/v_pied.php';
      $view = ob_get_clean();
      return $view;
  }
}


}
?>

