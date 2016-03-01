<!-- 6 -->
<div class="row">
  <div class="span12">
  	
    <?php if($nav->getArgs(0) !== false && $nav->getArgs(1) !== false && $nav->getArgs(2) !== false) { ?>
    	<?php $news = $PN->loadNews($nav->getArgs(0), $nav->getArgs(1), $nav->getArgs(2)); ?>
      <?php if($news) { ?>
      <h1><?php echo $news->Title; ?></h1>
      <small><?php echo strftime("%Y-%m-%d",strtotime($news->Date_Added)); ?></small>
      <p>
        <?php echo $news->News; ?>
      </p>
      <?php } ?>

    <?php } else { ?>
    <h1>Nyheter</h1>
      <ul class="unstyled">
    	<?php foreach ($PN->getNews(null, 0, 10) as $news) { ?>
        
         <li>

          <h3>
            <a href="/nyheter/<?php echo strftime("%Y/%m",strtotime($news->Date_Added)) ."/" . $news->Slug; ?>/"><?php echo $news->Title; ?></a>
            <small><?php echo $news->Date_Added; ?></small>
          </h3>
          <p>
            <?php echo $PN->SearchForHTML($news->News); ?>
          </p>
          
         </li>
        
      <?php } ?>
      </ul>
    <?php } ?>
  </div>
</div>
