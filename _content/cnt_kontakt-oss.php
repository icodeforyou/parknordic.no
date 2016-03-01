<!-- 3 -->
<div class="row">
  <div class="span8">
    <h1>Kontakt oss</h1>
    <form id="contact" action="<?php echo $nav->getPath(); ?>" method="post">
        <p>
          <strong>Ønsker du å klage på en kontrollavgift, trykk <a href="/klageweb/">her</a></strong>
        </p>        

        <div class="row">
          <div class="span4">
            <label for="navn">Navn</label>
            <input id="navn" type="text" name="navn" class="span4 required" value="<?php echo strlen($filter->RF("navn"))>0 ? $filter->RF("navn") : ''; ?>" />
          </div>
          <div class="span4">
            <label for="bedrift">Bedrift</label>
            <input id="bedrift" type="text" name="bedrift" class="span4" value="<?php echo strlen($filter->RF("bedrift"))>0 ? $filter->RF("bedrift") : ''; ?>" />
          </div>  
        </div>
        <div class="row">
          <div class="span4">
            <label for="epost">Epost</label>
            <input id="epost" type="text" name="epost" class="span4 required email" value="<?php echo strlen($filter->RF("epost"))>0 ? $filter->RF("epost") : ''; ?>" />
          </div>
          <div class="span4">
            <label for="telefon">Telefon</label>
            <input id="telefon" type="text" name="telefon" class="span4" value="<?php echo strlen($filter->RF("telefon"))>0 ? $filter->RF("telefon") : ''; ?>" />
          </div>
        </div>
        <div class="row">
          <div class="span4">
            <label for="emne">Emne</label>
            <input id="emne" type="text" name="emne" class="span4 required" value="<?php echo strlen($filter->RF("emne"))>0 ? $filter->RF("emne") : ''; ?>" />
          </div>
          <div class="span4">
            <label for="beskjed">Beskjed</label>
           <textarea id="beskjed" name="beskjed" class="span4 required"><?php echo strlen($filter->RF("beskjed"))>0 ? $filter->RF("beskjed") : ''; ?></textarea>
          </div>
        </div>
        <input type="hidden" name="-action" value="send_contact">
        <button type="submit" class="btn">Send Inn</button>

        <?php echo isset($response) ? "<h3>" .$response ."</h3>" : ""; ?>
    </form>
  </div>
  <div id="lots" class="span4">
    <div id="news-frame">
        <h4><a href="/nyheter/">Siste nytt</a></h4>
        <p style="padding: 5px 0; color: #111111">
            <?php if($news !== false) { ?>
                <?php echo $PN->substrwords($news[0]->News,190, "… <a href=\"/nyheter/".strftime("%Y/%m",strtotime($news[0]->Date_Added)) ."/" .$news[0]->Slug ."/\">Les mer</a>"); ?>
            <?php } ?>
        </p>
    </div>
    <?php $PN->getCities(); ?>
  </div>
</div>
<script type="text/javascript">
  $("#contact").validate();
</script>
