
<?php 

$this->title('Album History');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Albums', $this->url(array('Albums::index'))); ?>
	<span class="divider">/</span>
	</li>

	<li>
	<?=$this->html->link($album->title, $this->url(array('Albums::view', 'slug' => $album->slug))); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<?=$this->html->link('View', $this->url(array('Albums::view', 'slug' => $album->slug))); ?>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit', $this->url(array('Albums::edit', 'slug' => $album->slug))); ?></li>
	
	<?php endif; ?>

	<li class="active">
		<a href="#">History</a>
	</li>

	<li><?=$this->html->link('Packages', $this->url(array('Albums::package', 'slug' => $album->slug))); ?></li>
</ul>

<table class="table">

	<thead>
		<tr>
			<th>User</th>
			<th style="min-width:150px">Date</th>
			<th>Title</th>
		<tr>
	</thead>
	<tbody>

	<?php foreach( $archives_histories as $ah ): ?>
		<?php
			$start_date_string = date("Y-m-d H:i:s", $ah->start_date);
			$start_date_time = new DateTime($start_date_string);

			if (isset($tz)) {
				$start_date_time->setTimeZone($tz);
			}
			$start_date_display = $start_date_time->format("Y-m-d H:i:s");
		?>

		<tr>
			<td style="text-align:center">
				<?php if( $ah->user->id ): ?>
				<strong style="font-size: smaller;">
					<?php if($ah->user): ?>
						<?=$this->html->link($ah->user->initials(),'/users/view/'.$ah->user->username); ?>
					<?php endif; ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$start_date_display ?></td>
			<td>
				<?=$this->html->link($ah->name,"/$ah->controller/history/".$ah->slug); ?>
			</td>
		</tr>
	<?php endforeach; ?>

	</tbody>

</table>


