<?php 

$this->title('Metrics');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Metrics','/metrics'); ?>
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#">Data</a>
	</li>

	<li>
		<?=$this->html->link('Usage','/metrics/usage'); ?>
	</li>

	<li>
		<?=$this->html->link('Report','/metrics/report'); ?>
	</li>
</ul>

<div class="hero-unit">

	<h1>The Archive holds:</h1>

	<hr/>

	<!--<p><strong><?=$albums ?></strong> <?php echo $albums == '1' ? 'album' : 'albums'; ?> comprising <strong><?=$albums_works ?></strong> artworks.</p>-->

	<p><strong><?=$works ?></strong> <?php echo $works == '1' ? 'artwork' : 'artworks'; ?><?php if ($architecture): ?> and <strong><?=$architectures ?></strong> architecture <?php echo $architectures == '1' ? 'project' : 'projects'; ?><?php endif; ?>.</p>

	<p><strong><?=$exhibitions ?></strong> <?php echo $exhibitions == '1' ? 'exhibition' : 'exhibitions'; ?> which featured <strong><?=$exhibitions_works ?></strong> <?php echo $exhibitions_works == '1' ? 'artwork' : 'artworks'; ?>, including <strong><?=$solo_shows ?></strong> solo <?php echo $solo_shows= '1' ? 'show' : 'shows'; ?> and <strong><?=$group_shows ?></strong> group <?php echo $group_shows == '1' ? 'show' : 'shows'; ?>.</p>

	<p><strong><?=$publications ?></strong> <?php echo $publications == '1' ? 'publication' : 'publications'; ?> with <strong><?=$publications_documents ?></strong> <?php echo $publications_documents == '1' ? 'attachment' : 'attachments'; ?>.</p>

	<p>The total number of uploaded files is  <strong><?=$documents ?></strong>.</p>


</div>

<?php if(sizeof($works_years) > 0): ?>

<div class="hero-unit">

<h1>Artworks</h1>

<hr/>

<p>The archive contains artwork made over a <strong><?php echo sizeof($works_years); ?></strong> year period.</p>

<div id="worksYears" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	var recordsYears = [<?php foreach ($works_years as $years): echo '[' . $years['year'] . ', ' . $years['records'] . '], '; endforeach; ?>];

	$.plot($("#worksYears"), [

	{
		data: recordsYears,
		bars: { show: true }
	},

	]);

	});

</script>

</div>

<?php endif; ?>

<?php if($architecture && sizeof($architectures_years) > 0): ?>

<div class="hero-unit">

<h1>Architecture</h1>

<hr/>

<p>The archive contains architecture designed or built over a <strong><?php echo sizeof($architectures_years); ?></strong> year period.</p>

<div id="architecturesYears" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	var recordsYears = [<?php foreach ($architectures_years as $years): echo '[' . $years['year'] . ', ' . $years['records'] . '], '; endforeach; ?>];

	$.plot($("#architecturesYears"), [

	{
		data: recordsYears,
		bars: { show: true }
	},

	]);

	});

</script>

</div>

<?php endif; ?>

<?php if(sizeof($exhibitions_years) > 0): ?>

<div class="hero-unit">

<h1>Exhibitions</h1>

<hr/>

<p>The archive contains exhibitions held over a <strong><?php echo sizeof($exhibitions_years); ?></strong> year period.</p>

<div id="exhibitionsYears" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	var recordsYears = [<?php foreach ($exhibitions_years as $years): echo '[' . $years['year'] . ', ' . $years['records'] . '], '; endforeach; ?>];

	$.plot($("#exhibitionsYears"), [

	{
		data: recordsYears,
		bars: { show: true }
	},

	]);

});

</script>

</div>

<?php endif; ?>

<?php if(sizeof($publications_years) > 0): ?>

<div class="hero-unit">

<h1>Publications</h1>

<hr/>

<p>The archive contains books and articles published over a <strong><?php echo sizeof($publications_years); ?></strong> year period.</p>

<div id="publicationsYears" style="width:100%;height:300px"></div>

<script type="text/javascript">
$(function () {

var recordsYears = [<?php foreach ($publications_years as $years): echo '[' . $years['year'] . ', ' . $years['records'] . '], '; endforeach; ?>];

$.plot($("#publicationsYears"), [

{
	data: recordsYears,
	bars: { show: true }
},

]);

});

</script>

<hr/>

<p>The archive includes publications in <strong><?php echo sizeof($publications_languages); ?></strong> languages.</p> 

<div id="publicationsLanguages" style="width:500px;height:300px"></div>

<script type="text/javascript">
$(function () {

	var data = [
		<?php foreach ($publications_languages as $langs): 
			$num = $langs['records'];
			$lang = $langs['language'];
			echo "{ label: '$lang',  data: $num}, "; 
		endforeach; ?>
	];

	$.plot($("#publicationsLanguages"), data,
	{
		series: {
			pie: { 
				show: true
			}
		}
	});

});

</script>

</div>

<?php endif; ?>
