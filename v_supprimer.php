<div id="contenu">

  <h2>Visiteur à supprimer</h2>
    <h3>Visiteur à sélectionner : </h3>

<form method="POST"  action="afficherConfirmation">
<div class="corpsForm">
<p>           
<label> Visiteur : </label>
  <select name="suppVisiteur" > 
    <?php

      foreach($lesVisiteurs AS $Visiteur)
      {
          
    ?>      
    <option  value="<?php echo $Visiteur[0]?>"> <?php echo $Visiteur[1].' '.$Visiteur[2]?> </option> 
    <?php   
      }     
    ?>
  </select>
  </p>
  </div>

  <div class="piedForm">
        <p>
            <input id="ok" type="submit" value="Valider" size="20" />
        </p> 
  </div>

</form>
</div>


  