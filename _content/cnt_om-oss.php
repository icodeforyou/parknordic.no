<!-- 2 -->
<div class="row">
  <div class="span8">
    <h1>Om oss</h1>
    <p>
      Park Nordic AS ble etablert i 2008. Våre eiere og kunnskapsbase er fra Norge der vi har lengst erfaring fra parkeringsbransjen. Erfaringen omfatter også internasjonale prosjekter.
    </p>
    <p>
      Vår erfaring omfatter alt fra offentlig regulering og drift av parkering i bysentra til drift av små og store parkeringshus og plasser tilknyttet kjøpesentra, terminaler, messeområder, sykehus, næringsbygg, sameier eller rene frittstående anlegg. 
    </p>
    <p>
      Våre tjenester inkluderer alt fra råd i prosjektfasen, ulike drifts og vedlikeholdstjenester tilpasset oppdragsgivers behov, administrasjon, rapportering, markedsføring, utleie av faste plasser og avgiftsparkering.
    </p>
    <p>
      Vår profil, design og drift skal til enhver tid kunne tilpasses våre oppdragsgiveres profil og behov. Vi anser dette som vesentlig for å videreutvikle parkeringskonsepter og relasjonene til våre oppdrags-
givere.
    </p>
    <p>
      Vi har i dag oppdragsgivere som eksempelvis Steen & Strøm Norge AS, Schage Eiendom AS, Norges Gruppen AS, Exporama Senteret AS, ForvaltningsCompagniet AS, Diakonhjemmets Sykehus og Statsbygg.
    </p>
    <strong>Vår kunnskap din trygghet!</strong>
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
