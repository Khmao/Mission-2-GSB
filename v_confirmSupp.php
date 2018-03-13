<script type="text/javascript">
  function AfficherMasquer()
  {
    divInfo = document.getElementById('tableLigne');

    if (divInfo.style.display == 'none')
      divInfo.style.display = 'block';
    else
      divInfo.style.display = 'none';

  }
</script>

<div id="contenu">

  <h2>Information visiteur selectionner</h2>
  <h3>Demande de suppression : </h3>

  <form method="POST"  action="confirmation">
    <div class="corpsForm">
      <p>           
        <table style="margin-left:100px"> 
          <tr>
            <td> Nom </td> 
            <td> Prenom </td> 
            <td> Adresse </td> 
            <td> Code Postal </td> 
            <td> Ville </td> 
            <td> Date d'embauche </td> 
          </tr>
          <tr>
            <?php

            foreach($infoVisiteur AS $Visiteur)
            {

              ?>      
              <td> <?php echo $Visiteur[1] ?> </td> 
              <td> <?php echo $Visiteur[2] ?> </td> 
              <td> <?php echo $Visiteur[3] ?> </td> 
              <td> <?php echo $Visiteur[4] ?> </td> 
              <td> <?php echo $Visiteur[5] ?> </td> 
              <td> <?php echo $Visiteur[6] ?> </td> 
              <input type="hidden" name="idVisiteur" value="<?php echo $Visiteur[0]?>">
              <?php   
            }     
            ?>
          </tr>
        </table>
      </p>
    </div>
    <h3> Nombre de fiche de frais :  <?php echo $nbFicheFrais[0]?> dont <?php echo 
    $nbFicheFraisNonRembourser[0]?> pas rembourser
      <?php if($nbFicheFraisNonRembourser[0]!="0") { ?>
      <input type="button" onClick="AfficherMasquer()" value="Plus de details"></h3><?php }?>
      <input type="hidden" id="idVisiteur" value="<?php echo $nbFicheFraisNonRembourser[0]?>">
      <div id="tableLigne" style="display:none">
        <table>
          <tr>
            <td>idVisiteur</td>
            <td>mois</td>
            <td>idFraisForfait</td>
            <td>quantite</td>
          </tr>
          <?php
          foreach($LigneFraisForfaitVisiteur AS $LigneFraisForfait)
          {  
            ?> 
            <tr>
              <td><?php echo $LigneFraisForfait[0] ?></td>
              <td><?php echo $LigneFraisForfait[1] ?></td>
              <td><?php echo $LigneFraisForfait[2] ?></td>
              <td><?php echo $LigneFraisForfait[3] ?></td>
            </tr>
            <?php   
          }     
          ?>   
        </table>
      </div>




      <div class="piedForm">
        <p>
          <?php if($nbFicheFraisNonRembourser[0]=="0") { ?>
          <input id="ok" type="submit" value="Valider"  /> <?php }?>
          <input id="annuler" type="button" value="Retour" onclick="window.location='demandeSupprimer'""  />
        </p> 
      </div>

    </form>
  </div>


  