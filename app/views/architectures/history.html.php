<?php

$this->title($architecture->title);

$auth = $this->authority->auth();

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

$authority_can_edit = $this->authority->canEdit();

?>

<div id="location" class="row-fluid">

    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		History
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li><?=$this->html->link('View','/architectures/view/'.$architecture->archive->slug); ?></li>

	<?php if($authority_can_edit): ?>
	
		<li><?=$this->html->link('Edit','/architectures/edit/'.$architecture->archive->slug); ?></li>
	
	<?php endif; ?>

	<li class="active">
		<?=$this->html->link('History', $this->url(array('Architectures::history', 'slug' => $architecture->archive->slug))); ?>
	</li>

</ul>

<?php $rh = $architectures_histories->first(); ?>

<div class="row">
	<div class="span10">
	<?php foreach($archives_histories as $ah): ?>

		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}

			$start_date_display = $start_date_time->format("Y-m-d H:i:s T");
		?>

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td colspan="2">
						<strong><?=$start_date_display ?></strong> 
						<?php if( $ah->user->id ): ?>
						<small style="font-size: smaller;">by <?=$this->html->link($ah->user->name,'/users/view/'.$ah->user->username); ?></small>
						<?php endif; ?>
					</td>
				</tr>
			</thead>

			<tbody>

				<?php if( $rh->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$rh->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->title ): ?>
					<tr>
					<td class="meta">Title</td>
					<td><?=$rh->title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$rh->remarks ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->architect ): ?>
					<tr>
					<td class="meta">Architect</td>
					<td><?=$rh->architect ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->client ): ?>
					<tr>
					<td class="meta">Client</td>
					<td><?=$rh->client ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->project_lead ): ?>
					<tr>
					<td class="meta">Project Lead</td>
					<td><?=$rh->project_lead ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->consultants ): ?>
					<tr>
					<td class="meta">Consultants</td>
					<td><?=$rh->consultants ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->area ): ?>
					<tr>
					<td class="meta">Area</td>
					<td><?=$rh->dimensions() ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $rh->materials ): ?>
					<tr>
					<td class="meta">Materials</td>
					<td><?=$rh->materials ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->start_date() ): ?>
					<tr>
					<td class="meta">Design Date</td>
					<td><?=$ah->start_date_formatted() ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->end_date() ): ?>
					<tr>
					<td class="meta">Completion Date</td>
					<td><?=$ah->end_date_formatted() ?></td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

	<?php $rh = $architectures_histories->next(); ?>
	<?php endforeach; ?>
	</div>
</div>
