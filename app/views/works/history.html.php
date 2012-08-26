<?php 

$this->title($work->title);

?>

	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($work->title,'/works/view/'.$work->slug); ?>
	<span class="divider">/</span>
	</li>
	
	<li class="active">
		History
	</li>

	</ul>
<ul class="nav nav-tabs">
	<li class="active">
		<li><?=$this->html->link('View','/works/view/'.$work->slug); ?></li>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/works/edit/'.$work->slug); ?></li>
	
	<?php endif; ?>

		<li class="active"><?=$this->html->link('History','/works/history/'.$work->slug); ?></li>

</ul>

<div class="row">
	<div class="span10">
	<?php foreach($works_histories as $wh): ?>
		
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td colspan="2">
						<strong><?=$wh->start_date ?></strong> 
						<?php if( $wh->user->id ): ?>
						<small style="font-size: smaller;">by <?=$this->html->link($wh->user->name,'/users/view/'.$wh->user->username); ?></small>
						<?php endif; ?>
					</td>
				</tr>
			</thead>

			<tbody>

				<?php if( $wh->annotation ): ?>
					<tr>
					<td style="width:200px"><span class="label">Annotation</a></td>
					<td><em><?=$wh->annotation ?></em></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->artist ): ?>
					<tr>
					<td class="meta">Artist</td>
					<td><?=$wh->artist ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->title ): ?>
					<tr>
					<td class="meta">Title</td>
					<td><?=$wh->title ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->classification ): ?>
					<tr>
					<td class="meta">Classification</td>
					<td><?=$wh->classification ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->materials ): ?>
					<tr>
					<td class="meta">Materials</td>
					<td><?=$wh->materials ?></td>
					</tr>
				<?php endif; ?>

				<?php if( $wh->quantity ): ?>
					<tr>
					<td class="meta">Quantity</td>
					<td><?=$wh->quantity ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->remarks ): ?>
					<tr>
					<td class="meta">Remarks</td>
					<td><?=$wh->remarks ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->start_date() ): ?>
					<tr>
					<td class="meta">Earliest Date</td>
					<td><?=$wh->start_date() ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->end_date() ): ?>
					<tr>
					<td class="meta">Latest Date</td>
					<td><?=$wh->end_date() ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->creation_number ): ?>
					<tr>
					<td class="meta">Artwork ID</td>
					<td><?=$wh->creation_number ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->height ): ?>
					<tr>
					<td class="meta">Height</td>
					<td><?=$wh->height ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->width ): ?>
					<tr>
					<td class="meta">Width</td>
					<td><?=$wh->width ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->depth ): ?>
					<tr>
					<td class="meta">Depth</td>
					<td><?=$wh->depth ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->diameter ): ?>
					<tr>
					<td class="meta">Diameter</td>
					<td><?=$wh->diameter ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->weight ): ?>
					<tr>
					<td class="meta">Weight</td>
					<td><?=$wh->weight ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->running_time ): ?>
					<tr>
					<td class="meta">Running Time</td>
					<td><?=$wh->running_time ?></td>
					</tr>
				<?php endif; ?>


				<?php if( $wh->measurement_remarks ): ?>
					<tr>
					<td class="meta">Measurement Remarks</td>
					<td><?=$wh->measurement_remarks ?></td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

	<?php endforeach; ?>
	</div>
</div>

