<?php 

$this->title('Metrics');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Metrics','/metrics'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		Usage
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<a href="/metrics">Data</a>
	</li>

	<li class="active">
		<?=$this->html->link('Usage','/metrics/usage'); ?>
	</li>
</ul>

<?php if(sizeof($daily_creates) > 0): ?>

<div class="hero-unit">

<h1>Records</h1>

<hr/>

<p>The archive has been maintained over a <strong><?php echo sizeof($monthly_edits); ?></strong> month period. The following graph shows the daily growth of the number of records in the archive.</p>

<div id="creates" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	<?php $total = 0; ?>

	var records = [<?php foreach ($daily_creates as $record): $total += $record['records']; echo '[' . $record['milliseconds'] . ', ' . $total . '], '; endforeach; ?>];

	var options = {
		xaxis: {
			mode: "time",
			tickLength: 5
		}
	};

	var plot = $.plot("#creates", [records], options);


	});

</script>

</div>

<?php endif; ?>

<?php if(sizeof($monthly_edits) > 0): ?>

<div class="hero-unit">

<h1>Edits</h1>

<hr/>

<p>The archive has been edited over <strong><?=$archives_histories_count; ?></strong> Times. Here is the daily activity for the last three months:</p>

<div id="edits" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	var recordsEdits = [<?php foreach ($daily_edits_last_three_months as $edits): echo '[' . $edits['milliseconds'] . ', ' . $edits['records'] . '], '; endforeach; ?>];

	var options = {
		xaxis: {
			mode: "time",
			tickLength: 5
		}
	};

	var plot = $.plot("#edits", [recordsEdits], options);


	});

</script>

</div>

<?php endif; ?>
