    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
       </div>  
       <?php if($_SESSION['verif']=="0"){?>
        <ul id="menuList">
      <li >
          Visiteur :<br>
        <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
      </li>
           <li class="smenu">
              <a href="saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
     <li class="smenu">
              <a href="deconnecter" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
        <?php } if($_SESSION['verif']=="1"){?>

        <ul id="menuList">
      <li >
          Gestionnaire :<br>
        <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
      </li>
<li class="smenu">
              <a href="demandeSupprimer" title="faire une suppression">Supprimer un visiteur</a>
           </li>
<li class="smenu">
              <a href="vueArchive" title="vue archive">Archive</a>
           </li>

<li class="smenu">
              <a href="deconnecter" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
         <?php }?>
    </div>
    