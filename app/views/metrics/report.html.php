<?php 

$this->title('Metrics');

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
