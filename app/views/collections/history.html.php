
<?php 

$this->title('Collection History');

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Collection','/collection'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
		History
	</li>

	</ul>

</div>

<ul class="nav nav-tabs">
	<li>
		<a href="/collections">Index</a>
	</li>

	<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>
	
		<li><?=$this->html->link('Edit','/collections/edit/'.$collection->slug); ?></li>
	
	<?php endif; ?>

	<li class="active">
		<a href="#">History</a>
	</li>

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

	<?php foreach( $works as $wh ): ?>
		<tr>
			<td style="text-align:center">
				<?php if( $wh->user->id ): ?>
				<strong style="font-size: smaller;">
					<?=$this->html->link($wh->user->initials(),'/users/view/'.$wh->user->username); ?>
				</strong>
				<?php endif; ?>
			</td>
			<td><?=$wh->date_modified ?></td>
			<td>
				<?=$this->html->link($wh->title,'/works/history/'.$wh->slug); ?>
			</td>
		</tr>
	<?php endforeach; ?>

	</tbody>

</table>

