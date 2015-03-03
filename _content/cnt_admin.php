<div class="row">
  <div class="span12">
    <?php if(isset($_SESSION['authed_pn']) && $_SESSION['authed_pn'] === true) { ?>
    <?php if(isset($_error)) { var_dump($_error); } ?>
      <div class="tabbable">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#plasser" data-toggle="tab">Parkeringsplasser</a>
          </li>
          <li>
            <a href="#kredinor" data-toggle="tab">Kredinor</a>
          </li>
          <li>
            <a href="#news" data-toggle="tab">Lag Nyheter</a>
          </li>
					<li>
						<a href="#banner" data-toggle="tab">Banner</a>
					</li>
        </ul>
        <div class="tab-content">

          <div class="tab-pane active" id="plasser">

            <div class="lot-modal clearfix" style="display:none; margin-bottom: 20px;">
              <h2>Oprett ny parkeringplass</h2>
              <p>Her kan du opprette en ny parkeringsplass. Vær oppmerksom på at du må angi lengde-og breddegrad for oss å være i stand til å sette markøren på kartet riktig. Disse kan du enkelt finne ved å søke på adressen på google maps.</p>
              <div class="controls" id="new-lot-inputs">
                <form action="<?php echo $nav->getPath(); ?>" method="post" id="parking-lot-form" style="margin: 0px; ">
                  <div class="row">
                    <div class="span4">
                      <label for="address">Adresse</label>
                      <input id="address" type="text" name="address" class="span4 required" value="">
                    </div>
                    <div class="span4">
                      <label for="city">Sted</label>
                      <input id="city" type="text" name="city" class="span4 required" value="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="span4">
                      <label for="lng">Longitude</label>
                      <input id="lng" type="text" name="lng" class="span4 required" value="">
                    </div>
                    <div class="span4">
                      <label for="lat">Latitude</label>
                      <input id="lat" type="text" name="lat" class="span4 required" value="" data-source="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="span4">
                      <label for="description">Beskrivning</label>
                      <textarea name="description" id="description" rows="5" style="width: 97%" class="required"></textarea>
                    </div>
                    <div class="span4" style="margin-top: 20px">
                      <label class="checkbox">
                        <input id="longtime" name="longtime" value="1" type="checkbox"> Denne parkeringsplassen, kan brukerne søke om langtidsparkering
                      </label>
                    </div>
                  </div>
                  <!--
                  <div class="row">
                    <div class="span8">
                      <div id="map-preview" style="height: 200px; width: 500px;"></div>
                    </div>
                  </div>
                -->
                  <input type="hidden" id="-mode" name="-mode" value="new">
                  <input type="hidden" id="-edit" name="-edit" value="">
                  <input type="hidden" name="-action" value="save-new-lot">
                </form>
              </div>
              <button type="submit" onclick="preCheckLot(); return false;" class="btn btn-primary pull-right">Skapa</button>
              <button type="submit" onclick="slideModal('lot-modal'); return false;" class="btn pull-right" style="margin-right:5px;">Avbryt</button>
              <script>
      $(function(){
        
        var options = {
         // map: "#map-preview",
         // location : [60.46805, 8.459473],
          country: 'no',
          details: "form",
          types: ["geocode", "establishment"],
         // mapOptions: {
         //   zoom: 6
         // },
         // markerOptions: {
         //   draggable: true
         // },
        };
        
        var map = $("#address")
                    .geocomplete(options)
                    .bind("geocode:result", function(event, result){
                      console.log(result);
                    });

      $('#city').typeahead({
          source: function (query, process) {
              return $.get('/ajax/ajax_city_typeahead.php', { query: query }, function (data) {
                  return process(data.options);
              });
          }
      });
        
      });
    </script>
            </div>
            
            <p>Disse parkeringsplassene blir lagret i databasen. Hvis du vil endre et eksisterende element klikk på sin rad i tabellen. Å endre en parkeringsplass detaljer, klikk på rad i tabellen.</p>
           
            <a href="#" class="create-new-lot badge" data-modal="lot-modal"><i class="icon-plus icon-white"></i> Opprett ny</a>
           
            <table id="lot-admin-table" class="table table-striped table-hover table-condensed">
              <thead>
                <tr>
                  <th>City</th>
                  <th>Addresse</th>
                  <th>Beskrivning</th>
                  <th>Fjern</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($PN->getCityLots() as $lot) { ?>
                  <tr>
                    <td><a href="#" class="admin-city-lot" data-lotid="<?php echo $lot->ID; ?>"><?php echo $lot->Name; ?></a></td>
                    <td><?php echo $lot->Address; ?></td>
                    <td><?php echo $lot->Description; ?></td>
                    <td>
                      <a href="?remove=<?php echo $lot->ID; ?>" class="btn btn-small btn-warning confirm-removal">Fjern</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>


          </div>

          <div class="tab-pane" id="news">

            <div class="tabbable">
              <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#ny" data-toggle="tab">Ny Nyhet</a>
                </li>
                <li>
                  <a href="#edit" data-toggle="tab">Lista / Ändra Nyheter</a>
                </li>
                
              </ul>
              <div class="tab-content">

                <div class="tab-pane active" id="ny">

                  <h2>Lag Nyheter</h2>
                  <p>Her kan du opprette nyheter til hjemmesiden</p>

                  <form action="<?php echo $nav->getPath(); ?>#ny" method="post" id="news-form" style="margin: 0px; ">
                    
                    <?php if(isset($_GET['newsid'])) { ?>
                    <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      Nyheter opprettet
                    </div>
                    <?php } ?>

                    <div class="row">
                      <div class="span4">
                        <label for="headline">Overskrift</label>
                        <input id="headline" type="text" name="headline" class="span4 required" value="">
                      </div>
                      <div class="span8">
                        <label for="news">Nyheter</label>
                        <textarea class="span8" id="news" rows="10" name="news" class="span4 required"></textarea>
                      </div>
                    </div>

                    <input type="hidden" name="-action" value="save-news">
                    <button type="submit" class="btn btn-primary pull-right">Skapa</button>
                  </form>
                </div>

                <div class="tab-pane" id="edit">

                  <?php if(isset($_GET['newsid'])) { ?>
                    <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      Nyhet oppdatert
                    </div>
                  <?php } ?>
                  
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Overskrift</th>
                        <th>Nyhet</th>
                        <th>Dato</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($PN->getNews('', 0, 50) as $news) { ?>
                        <form action="<?php echo $nav->getPath(); ?>/#edit" method="post">
                          <tr>
                            <td>
                              <?php echo $news->id; ?>
                            </td>
                            <td style="width:200px">
                              <input type="text" name="headline" value="<?php echo $news->Title; ?>" />
                            </td>
                            <td style="width: 400px">
                              <textarea name="news" rows="3" style="width: 95%"><?php echo $news->News; ?></textarea>
                            </td>
                            <td>
                              <?php echo $news->Date_Added; ?>
                            </td>
                            <td>
                              <input type="hidden" name="-action" value="edit-news" />
                              <input type="hidden" name="-edit" value="<?php echo $news->id; ?>" />
                              <button type="submit" class="btn btn-small btn-primary">Lagre</button>
                            </td>
                          </tr>
                        </form>
                        

                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
          
          <div class="tab-pane" id="kredinor">

            <div class="kredinor-modal clearfix" style="display:none; margin-bottom: 20px;">
              <h2>Opprett ny Kredinor post</h2>
              <p>Her kan du opprette en ny Kredinor. Du kan også laste opp flere PDF-dokumenter til enhver Kredinor side</p>
              <div class="controls" id="new-kredinor-inputs">
                <form action="<?php echo $nav->getPath(); ?>" enctype="multipart/form-data" method="post" id="kredinor-lot-form" style="margin: 0px; ">
                  
                  <div class="row">
                    <div class="span4">
                      <label for="kredinor_city">Postnr og sted</label>
                      <input id="kredinor_city" type="text" name="kredinor_city" class="span4 required" value="">
                    </div>
                    <div class="span4">
                      <label for="address">Adresse</label>
                      <input id="kredinor_address" type="text" name="address" class="span4 required" value="">
                    </div>
                  </div>

                  <div class="row">
                    <div class="span3">
                      <label for="depcode">Avdelingskode</label>
                      <input id="depcode" type="text" name="depcode" class="span3 required" value="">
                    </div>
                    <div class="span3">
                      <label for="type">Type</label>
                      <input id="type" type="text" name="type" class="span3 required" value="" data-source="">
                    </div>
                    <div class="span2">
                      <label for="machines">Antall automater</label>
                      <input id="machines" type="text" name="machines" class="span2 required number" value="" data-source="">
                    </div>
                  </div>

                  <div class="row">
                    <div class="span4">
                      <label for="information">Informasjon</label>
                      <textarea name="information" id="information" rows="5" style="width: 97%" class="required"></textarea>
                    </div>
                    <div class="span4">
                      <label for="Dokumenter">Dokumenter</label>
                      <span class="prettyFile">
                          <input type="file" name="files[]" multiple="multiple">
                          <div class="input-append">
                             <input class="input-large" type="text">
                             <a href="#" class="btn">Velge filer</a>
                          </div>
                      </span>
                      <div id="Dokumenter">
                        <ul></ul>
                      </div>
                      <!--
                      <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                          <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="document" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                      </div>
                    -->
                    </div>
                  </div>
                  <!--
                  <div class="row">
                    <div class="span8">
                      <div id="map-preview" style="height: 200px; width: 500px;"></div>
                    </div>
                  </div>
                -->
                  <input type="hidden" id="-mode" name="-mode" value="new">
                  <input type="hidden" id="-edit" name="-edit" value="">
                  <input type="hidden" name="-action" value="save-new-kredinor">
                </form>
              </div>
              <button type="submit" onclick="preCheckKredinor();" class="btn btn-primary pull-right">Skapa</button>
              <button type="submit" onclick="slideModal('kredinor-modal'); return false;" class="btn pull-right" style="margin-right:5px;">Avbryt</button>
              <script>
                $(function(){
                  
                  

                  $('#kredinor_city').typeahead({
                      source: function (query, process) {
                          return $.get('/ajax/ajax_city_typeahead.php?mode=kredinor', { query: query }, function (data) {
                              return process(data.options);
                          });
                      }
                  });
                  
                });
              </script>
            </div>

            <a href="#" class="create-new-lot badge" data-modal="kredinor-modal"><i class="icon-plus icon-white"></i> Opprett ny</a>
           
            <table id="lot-admin-table" class="table table-striped table-hover table-condensed">
              <thead>
                <tr>
                  <th>Postnr og sted</th>
                  <th>Addresse</th>
                  <th>Avdelingskode</th>
                  <th>Antall automater</th>
                  <th>Type</th>
                  <th>Informasjon</th>
                  <th>Dokumenter</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($PN->Kredinor() as $k_lot) { ?>
                <?php 

                $files = $PN->getKredinorFiles($k_lot->KredinorID);

                ?>
                  <tr class="admin-kredinor-lot" data-lotid="<?php echo $k_lot->KredinorID; ?>">
                    <td><?php echo $k_lot->Location; ?></td>
                    <td><?php echo $k_lot->Address; ?></td>
                    <td><?php echo $k_lot->DepartmentCode; ?></td>
                    <td style="text-align: center"><?php echo $k_lot->Machines; ?></td>
                    <td><?php echo $k_lot->Type; ?></td>
                    <td><?php echo $k_lot->Information; ?></td>
                    <td style="text-align: center"><?php echo $files !== false ? count($files) : "0"; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

					<div class="tab-pane" id="banner">

						<h2>Laste up ny Banner</h2>
						<p>Her kan du laste opp nytt banner til hjemmesiden</p>
						<div class="controls">
							<form action="<?php echo $nav->getPath(); ?>" enctype="multipart/form-data" method="post" style="margin: 0px; ">

								<div class="row">
									<div class="span8">
										<img src="/img/banner_front_park_tilbud.jpg" alt=""/>
									</div>
								</div>
								<div class="row">
									<div class="span4">
										<label for="banner-file">Velg fil</label>
										<input id="banner-file" type="file" name="banner-file" class="span4 required">
									</div>
									<!--
									<div class="span4">
										<label for="banner-url">Link til bilde</label>
										<input id="banner-file" type="text" name="banner-url" class="span4 required">
									</div>
									-->
								</div>

								<div class="row">
									<div class="span8">
										<button type="submit" class="btn btn-primary">Upload</button>
									</div>
								</div>

								<input type="hidden" name="-action" value="upload-new-banner">
							</form>

					</div>
        </div>

      </div>

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
            <input type="hidden" name="-action" value="admin-inlogg">
            <button type="submit" class="btn">Logg inn</button>
          </div>
        </div>
      </form>

    <?php } ?>
  </div>
</div>
