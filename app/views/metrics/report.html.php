<?php 

$this->title('Metrics');

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Metrics','/metrics'); ?>
	<span class="divider">/</span>
	</li>

	<li>
		Report
	</li>

	</ul>

</div>

<div class="actions">

<ul class="nav nav-tabs">
	<li>
		<a href="/metrics">Data</a>
	</li>

	<li>
		<?=$this->html->link('Usage','/metrics/usage'); ?>
	</li>

	<li class="active">
		<?=$this->html->link('Report','/metrics/report'); ?>
	</li>

</ul>

</div>

<h1>Monthly Report</h2>

<p class="lead"><?=$dates['now'] ?></p>

<?php if ($updates->count()): ?>
<div class="row">
	<div class="span10">
		<table class="table">
			<thead>
				<tr>
					<td class="meta">
						<strong>Updates</strong>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($updates as $update): ?>
					<tr>
						<td>
				<strong><?=$update->subject ?></strong> &mdash; <?=$update->body ;?>
				<small class="meta"><?=$update->date_created ?>
						</td>
					</tr>

				<?php endforeach; ?>
			</tbody>

		</table>
	</div>
</div>
<?php endif; ?>

<?php if ($archives->count()): ?>
<div class="row">
	<div class="span10">
		<table class="table">
			<thead>
				<tr>
					<td class="meta">
						<strong>Contributions</strong>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($archives as $archive): ?>
				<?php
					$start_date_time = new DateTime($archive->date_modified);

					if (isset($tz)) {
						$start_date_time->setTimeZone($tz);
					}
					$start_date_display = $start_date_time->format("Y-m-d");
				?>
		<tr>
			<td class="meta">
				<?=$archive->controller ?>
			</td>
			<td>
				<strong>
				<?=$this->html->link($archive->name,"/$archive->controller/history/".$archive->slug); ?>
				</strong>
			</td>
			<td><?=$start_date_display ?></td>
			<td>
				<?php if( $archive->user->id ): ?>
				<span style="font-size: smaller;">
					<?=$this->html->link($archive->user->name,'/users/view/'.$archive->user->username); ?>
				</span>
				<?php endif; ?>
			</td>
		</tr>
				<?php endforeach; ?>
			</tbody>

		</table>
	</div>
</div>

<?php endif; ?>
