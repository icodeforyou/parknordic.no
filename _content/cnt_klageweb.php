<!-- 3 -->
<div class="row">
  <div class="span8">
    <iframe style="width: 620px; height: 560px; overflow: hidden;" src="https://klage.netpark.no/KredinorReg/ParkNordicklage.aspx" frameborder="0"></iframe>
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
