<div id="contenu">

  <h2>visiteurs Archiver</h2>
    <h3>Visiteur à sélectionner : </h3>

<form method="POST"  action="restaurer">
<div class="corpsForm">
<p>           
<label> Visiteur : </label>
  <select name="visiteurArchiver" > 
    <?php

      foreach($lesVisiteursArchives AS $Archiver)
      {
          
    ?>      
    <option  value="<?php echo $Archiver[0]?>"> <?php echo $Archiver[1].' '.$Archiver[2]?> </option> 
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


  