<?php 

$this->title($architecture->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Architecture','/architectures'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($architecture->title,'/architectures/view/'.$architecture->slug); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/architectures/edit/<?=$architecture->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Project
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/architectures/edit/<?=$architecture->slug ?>">
					<i class="icon-pencil"></i> Edit
				</a>
			</li>
			<li>
				<a data-toggle="modal" href="#deleteModal">
					<i class="icon-trash"></i> Delete
				</a>
			</li>
		</ul>

	</div>
	
<?php endif; ?>

</div>

<div class="row">
	<div class="span6">
	
		<ul class="thumbnails">
			
				<?php
					$num_thumbs = sizeof($architecture_documents);
					$span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($architecture_documents as $ad): ?>
			
				<li class="<?=$span?>">
					<a href="/documents/view/<?=$ad->document->slug?>" class="thumbnail">
						<img src="/files/<?=$size?>/<?=$ad->document->slug?>.jpeg" alt="<?=$ad->document->title ?>">
					</a>
				</li>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($architecture_documents) == 0): ?>
				<li class="<?=$span?>">
				<div class="thumbnail">
				<span class="label label-warning">No Image</span>
				</div>
				</li>
			<?php endif; ?>
		
		</ul>
		
	</div>
	
	<div class="span4">
	
		<div class="alert alert-block">
    	<p>
    		<?php echo $architecture->caption(); ?>
    	</p>
		</div>
	
		<table class="table">
			<tbody>
				<tr>
					<td><i class="icon-user"></i></td>
					<td class="meta">Client</td>
					<td><?=$architecture->client ?></td>
				</tr>
				<tr>
					<td><i class="icon-hand-right"></i></td>
					<td class="meta">Project Lead</td>
					<td><?=$architecture->project_lead ?></td>
				</tr>
			</tbody>
		
		</table>
	
	</div>
</div>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Architecture</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$architecture->title; ?></strong>? This will not delete any associated documents. It will only remove this Project and its information from the listings.</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($architecture, array('url' => "/architectures/delete/$architecture->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
