<div class="row">
  <div class="span8">
    <?php if(isset($_SESSION['authed_kredinor']) && $_SESSION['authed_kredinor'] === true) { ?>

      <?php if($nav->getArgs(0) === false) { ?>
        <ul class="unstyled">
          <?php foreach ($PN->Kredinor() as $obj) { ?>
            <li><a href="/kredinor/<?php echo $obj->Slug; ?>/"><?php echo $obj->City." - " .$obj->Address; ?></a></li>
          <?php } ?>
        </ul>
      <?php } else { ?>

        <?php if(isset($kredinor_obj) && $kredinor_obj !== false) { ?>
          <h1><?php echo $kredinor_obj->Address; ?></h1>
          <p>
            <strong>Avdelingskode:</strong> <?php echo $kredinor_obj->DepartmentCode; ?>
          </p>
          <p>
            <strong>Antall automater:</strong> <?php echo $kredinor_obj->Machines; ?>
          </p>
          <p>
            <strong>Adresse:</strong> <?php echo $kredinor_obj->Address; ?>
          </p>
          <p>
            <strong>Postnr og sted:</strong> <?php echo $kredinor_obj->Location; ?>
          </p>
          <p>
            <strong>Type:</strong> <?php echo $kredinor_obj->Type; ?>
          </p>
          <p>
            <strong>Informasjon:</strong> <?php echo $kredinor_obj->Information; ?>
          </p>
          <p>
            <strong>Dokumenter:</strong>
       
            <?php if(isset($documents) && $documents !== false && count($documents)>0) { ?>
              <ul class="unstyled">
                <?php foreach ($documents as $document) { ?>
                  <li><a target="_blank" href="/media/<?php echo $document->KredinorID; ?>/<?php echo $document->FileName; ?>"><?php echo $document->FileName; ?></a></li>
                <?php } ?>
              </ul>
            <?php } ?>
          </p>
     
         
        <?php } else { ?>
          Inget hittades
        <?php } ?>

  
      <?php } ?>

    <?php } else { ?>

      <form action="<?php echo $nav->getPath(); ?>" method="post">
        <div class="control-group">
          <label class="control-label" for="Brukernavn">Brukernavn</label>
          <div class="controls">
            <input type="text" id="Brukernavn" name="Brukernavn">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="inputPassword">Passord</label>
          <div class="controls">
            <input type="password" id="Passord" name="Passord">
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <label class="checkbox">
              <input type="checkbox" name="rememeber-me" value="1"> Husk med
            </label>
            <input type="hidden" name="-action" value="kredinor-inlogg">
            <button type="submit" class="btn">Logg inn</button>
          </div>
        </div>
      </form>

    <?php } ?>
  </div>
  <div id="lots" class="span4">
    <?php $PN->getCities(); ?>
  </div>
</div>
