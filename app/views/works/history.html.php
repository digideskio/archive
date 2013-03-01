<?php 

$this->title($work->title);

if($auth->timezone_id) {
	$tz = new DateTimeZone($auth->timezone_id);
}

?>

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->archive->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		History
	</li>

	</ul>
<ul class="nav nav-tabs">
	<li class="active">
		<li><?=$this->html->link('View','/works/view/'.$work->archive->slug); ?></li>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/works/edit/'.$work->archive->slug); ?></li>
	
	<?php endif; ?>

		<li class="active"><?=$this->html->link('History','/works/history/'.$work->archive->slug); ?></li>

</ul>

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

				<?php if( $ah->works_history->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$ah->works_history->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->works_history->artist ): ?>
					<tr>
					<td class="meta">Artist</td>
					<td><?=$ah->works_history->artist ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->works_history->title ): ?>
					<tr>
					<td class="meta">Title</td>
					<td><?=$ah->works_history->title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->classification ): ?>
					<tr>
					<td class="meta">Classification</td>
					<td><?=$ah->classification ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->works_history->materials ): ?>
					<tr>
					<td class="meta">Materials</td>
					<td><?=$ah->works_history->materials ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $ah->works_history->quantity ): ?>
					<tr>
					<td class="meta">Quantity</td>
					<td><?=$ah->works_history->quantity ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$ah->works_history->remarks ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->start_date() ): ?>
					<tr>
					<td class="meta">Earliest Date</td>
					<td><?=$ah->start_date_formatted() ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->end_date() ): ?>
					<tr>
					<td class="meta">Latest Date</td>
					<td><?=$ah->end_date_formatted() ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->creation_number ): ?>
					<tr>
					<td class="meta">Artwork ID</td>
					<td><?=$ah->works_history->creation_number ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->height ): ?>
					<tr>
					<td class="meta">Height</td>
					<td><?=$ah->works_history->height ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->width ): ?>
					<tr>
					<td class="meta">Width</td>
					<td><?=$ah->works_history->width ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->depth ): ?>
					<tr>
					<td class="meta">Depth</td>
					<td><?=$ah->works_history->depth ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->diameter ): ?>
					<tr>
					<td class="meta">Diameter</td>
					<td><?=$ah->works_history->diameter ?> cm</td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->weight ): ?>
					<tr>
					<td class="meta">Weight</td>
					<td><?=$ah->works_history->weight ?> kg</td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->running_time ): ?>
					<tr>
					<td class="meta">Running Time</td>
					<td><?=$ah->works_history->running_time ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $ah->works_history->measurement_remarks ): ?>
					<tr>
					<td class="meta">Measurement Remarks</td>
					<td><?=$ah->works_history->measurement_remarks ?></td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

	<?php endforeach; ?>
	</div>
</div>

