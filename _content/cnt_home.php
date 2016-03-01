<div class="row">
  <div class="span8">
    <div>
      <!--
      <h2>Leie 3 måneder for 1 mnd gratis.</h2>
      <p>
      	Gjelder følgende områder: Rosenbergveien 15, Oslo. Marieboesgt. 16, Oslo og Kanalveien, Lillestrøm.
      </p>
    -->
      <!--
      <a href="http://parknordic-permit.mspace.giantleap.no/user.html#/login"><img style="border:none" src="/img/banner_front_park_tilbud.jpg" alt="Leie 3 måneder for 1 mnd gratis - Gjelder følgende områder: Rosenbergveien 15, Oslo. Marieboesgt. 16, Oslo og Kanalveien, Lillestrøm."></a>
      -->
      <img src ="/img/banner_nett_drammen.jpg" width="619" height="189" alt="Bestill plats" usemap="#bestill-map">        
      <map name="bestill-map">
        <area shape="rect" coords="20,95,223,135" href="https://parknordic-permit.giantleap.no/embedded-user-shop.html#/shop/pre-selected-product/eef13fab-02cb-40cc-975f-b4838d7e94c0" alt="Dr. Hansteinsgate 20">
        <area shape="rect" coords="266,95,398,135" href="https://parknordic-permit.giantleap.no/embedded-user-shop.html#/shop/pre-selected-product/73d8b67f-6867-47d3-bc3d-a0d49affbcea" alt="Langesgt. 1">
        <area shape="rect" coords="449,95,592,135" href="https://parknordic-permit.giantleap.no/embedded-user-shop.html#/shop/pre-selected-product/0a000f01-dbb5-413c-be6d-8da4203ddc9d" alt="Tollbugata 26">
      </map>

      <p class="home-p-text">
        Her kan du bestille, betale, endre kjøretøy og avslutte leieforholdet. Du trenger ikke lenger å tenke på parkeringsbevis! Klikk deg inn via banneren over eller i toppmenyen og logg på og registrer deg, finn stedet du ønsker å leie og fyll inn ønsket informasjon. Så kan du starte å parkere.
      </p>
      <p class="home-p-text">
        Har du spørsmål, ring oss på tlf. 21 42 20 00 eller e-post til <a href="mailto:utleie@parknordic.no">utleie@parknordic.no</a>
      </p>
    </div>
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
    <p>
      <a href="/assets/Produktark_ParkNordic_A4.pdf">FORENKLET OG EFFEKTIV PARKERINGSADMINISTRASJON!</a>
    </p>
  </div>
</div>
