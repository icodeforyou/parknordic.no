<!-- 1 -->
<div class="row">
	<div class="span8">
		<h1>Bestille plass</h1>
		<?php if(isset($_GET['recieved'])) { ?>
	      <div class="alert alert-success">
	        Takk for din søknad, vil vi behandle den så snart som mulig
	      </div>
	    <?php } ?>
		<p>
			Her finner du oversikt over våres områder. Se oversikt ved å velge aktuelt område.
		</p>
		<div class="clearfix">
			
			<form action="/bestille-plass/" method="post" style="margin: 0px;" id="bestill-plats-form-non-js">
				<div class="row">
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="navn">Navn</label>
							<div class="controls">
								<input type="text" id="navn" name="Navn" class="required" placeholder="Navn" style="width: 95%"></div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Postadresse">Postadresse</label>
							<div class="controls">
								<input type="text" id="Postadresse" name="Postadresse" class="required" placeholder="Postadresse" style="width: 95%"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Postnr">Postnr og sted</label>
							<div class="controls">
								<input type="text" id="Postnr" name="Postnr" class="required" placeholder="Postnr og sted" style="width: 95%"></div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Orgnr">Orgnr / fødselsnr</label>
							<div class="controls">
								<input type="text" id="Orgnr" name="Orgnr" class="required number" placeholder="Orgnr / fødselsnr" style="width: 95%"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Epost">Epost</label>
							<div class="controls">
								<input type="text" id="Epost" name="Epost" class="required email" placeholder="Din Epost" style="width: 95%"></div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Telefon">Telefon</label>
							<div class="controls">
								<input type="text" id="Telefon" name="Telefon" class="required" placeholder="Telefon" style="width: 95%"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Sted">Sted</label>
							<div class="controls">
								<input type="text" id="Sted" name="Sted" placeholder="Sted" value="" style="width: 95%"></div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group">
							<label class="control-label" for="Kommentar">Kommentar</label>
							<div class="controls">
								<textarea name="Kommentar" cols="10" style="width:95%"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span4">
						<div class="control-group">
							<label class="radio">
								<input class="required" id="Betalingstype" name="Betalingstype" value="Måned" type="radio" checked="checked">Måned faktura</label>
						</div>
					</div>
					<div class="span4">
						<div class="control-group">
							<label class="radio">
								<input class="required" id="Betalingstype" name="Betalingstype" value="Kvartalsvis" type="radio">Kvartalsvis faktura</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span8">
						<div class="control-group">
							<label class="checkbox">
								<input class="required" id="terms" name="terms" value="1" type="checkbox">
								Ja, jeg har lest og aksepterer
								<a title="Klikk for å laste ned og lese våre vilkår og betingelser" href="/doc/ParkNordic_Vilkaar_revidert_140113.pdf" target="_blank">vilkårene</a>
								for leie av parkeringsplass
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="span8">
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn btn-primary pull-right">Send Inn</button>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="-action" value="apply">
				<input type="hidden" name="uri" value="bestille-plass">
			</form>
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
		<?php $PN->getCities(); ?></div>
</div>
