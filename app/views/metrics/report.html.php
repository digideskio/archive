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

	<?php if($li3_pdf): ?>
	<div class="btn-toolbar">
		<a class="btn btn-inverse" href="<?=$this->url(array('Metrics::report')); ?>/<?=$pdf ?>"><i class="icon-print icon-white"></i> Print</a>
	</div>
	<?php endif; ?>

</div>

<h1>Progress Report</h2>

<p class="lead">
	<?=$dates['month'] ?>
	::
	<?=$dates['now'] ?>
</p>

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

			<?php $last_controller = ''; ?>

			<?php foreach ($archives as $archive): ?>
				<?php
					$start_date_time = new DateTime($archive->date_modified);

					if (isset($tz)) {
						$start_date_time->setTimeZone($tz);
					}
					$start_date_display = $start_date_time->format("Y-m-d");
				?>
		<tr>
				<?php if ($archive->controller != $last_controller): ?> 
				<td class="meta">
					<?=$archive->controller ?>
				</td>
				<?php else: ?>
					<td style="border-top: 1px solid #fff;">
					</td>
				<?php endif; ?>

				<?php $last_controller = $archive->controller; ?>
			<td>
				<strong>
				<?=$this->html->link($archive->name,"/$archive->controller/view/".$archive->slug); ?>
				</strong>
				<span class="muted" style="font-size: smaller;">
					<?=$archive->classification ?>
				</span>
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