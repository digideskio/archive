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

<style>
	td.stat {
		text-align: right;
	}
</style>

<h1>Stats</h1>

<p class="lead"><?=$dates['now'] ?></p>

<div class="row">

	<div class="span3">
		<table class="table table-bordered">
			<caption><strong>All Time</strong></caption>
			<caption class="meta"><?=$dates['all_time'] . ' :: ' . $dates['now'] ?></caption>
			<tbody>
				<tr>
					<td class="meta">
						Reporting Period
					</td>
					<td class="stat">
						<?=$total_days ?> days
					</td>
				</tr>
				<tr>
					<td class="meta">
						Contributors
					</td>
					<td class="stat">
						<?=$contributors['total'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Artworks
					</td>
					<td class="stat">
						<?=$works['total'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Exhibitions
					</td>
					<td class="stat">
						<?=$exhibitions['total'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Publications
					</td>
					<td class="stat">
						<?=$publications['total'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Uploads
					</td>
					<td class="stat">
						<?=$documents['total'] ?>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td class="meta">Name</td>
					<td class="meta">Edits</td>
				</tr>
				<?php foreach ($contributions['total'] as $contribution): ?>
					<tr>
						<td>
							<?=$this->html->link($contribution['name'], $this->url(array("Users::view", "username" => $contribution['username']))); ?>
						</td>
						<td class="stat">
							<?=$contribution['records'] ?>
						</td>
					</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
			<tbody>
				
			</tbody>
		</table>
	</div>

	<div class="span3">
		<table class="table table-bordered">
			<caption><strong>This Month</strong></caption>
			<caption class="meta"><?=$dates['month'] . ' :: ' . $dates['now'] ?></caption>
			<tbody>
				<tr>
					<td class="meta">
						Reporting Period
					</td>
					<td class="stat">
						<?=$intervals['month'] ?> days
					</td>
				</tr>
				<tr>
					<td class="meta">
						Contributors
					</td>
					<td class="stat">
						<?=$contributors['month'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Artworks
					</td>
					<td class="stat">
						<?=$works['month'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Exhibitions
					</td>
					<td class="stat">
						<?=$exhibitions['month'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Publications
					</td>
					<td class="stat">
						<?=$publications['month'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Uploads
					</td>
					<td class="stat">
						<?=$documents['month'] ?>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td class="meta">Name</td>
					<td class="meta">Edits</td>
				</tr>
				<?php foreach ($contributions['month'] as $contribution): ?>
					<tr>
						<td>
							<?=$this->html->link($contribution['name'], $this->url(array("Users::view", "username" => $contribution['username']))); ?>
						</td>
						<td class="stat">
							<?=$contribution['records'] ?>
						</td>
					</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="span3">
		<table class="table table-bordered">
			<caption><strong>This Week</strong></caption>
			<caption class="meta"><?=$dates['week'] . ' :: ' . $dates['now'] ?></caption>
			<tbody>
				<tr>
					<td class="meta">
						Reporting Period
					</td>
					<td class="stat">
						7 days
					</td>
				</tr>
				<tr>
					<td class="meta">
						Contributors
					</td>
					<td class="stat">
						<?=$contributors['week'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Artworks
					</td>
					<td class="stat">
						<?=$works['week'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Exhibitions
					</td>
					<td class="stat">
						<?=$exhibitions['week'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Publications
					</td>
					<td class="stat">
						<?=$publications['week'] ?>
					</td>
				</tr>
				<tr>
					<td class="meta">
						Uploads
					</td>
					<td class="stat">
						<?=$documents['week'] ?>
					</td>
				</tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td class="meta">Name</td>
					<td class="meta">Edits</td>
				</tr>
				<?php foreach ($contributions['week'] as $contribution): ?>
					<tr>
						<td>
							<?=$this->html->link($contribution['name'], $this->url(array("Users::view", "username" => $contribution['username']))); ?>
						</td>
						<td class="stat">
							<?=$contribution['records'] ?>
						</td>
					</tr>

				<?php endforeach; ?>
			</tbody>
			</table>
	</div>

</div>

<?php endif; ?>

<?php if(sizeof($daily_views) > 0): ?>

<div class="hero-unit">

<h1>Views</h1>

<hr/>

<p>The following graph shows the daily growth of the number of page views.</p>

<div id="views" style="width:100%;height:300px"></div>

<script type="text/javascript">
	$(function () {

	<?php $total = 0; ?>

	var records = [<?php foreach ($daily_views as $record): $total += $record['records']; echo '[' . $record['milliseconds'] . ', ' . $total . '], '; endforeach; ?>];

	var options = {
		xaxis: {
			mode: "time",
			tickLength: 5
		}
	};

	var plot = $.plot("#views", [records], options);


	});

</script>

</div>

<?php endif; ?>

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

<p>The archive has been edited over <strong><?=$archives_histories_count; ?></strong> times. Here is the daily activity for the last three months:</p>

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
