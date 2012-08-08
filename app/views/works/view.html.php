<?php 

$this->title($work->title);

?>

<div id="location" class="row-fluid">
    
	<ul class="breadcrumb">

	<li>
	<?=$this->html->link('Artwork','/works'); ?>
	<span class="divider">/</span>
	</li>

	<li class="active">
	<?=$this->html->link($work->title,'/works/view/'.$work->slug); ?>
	</li>

	</ul>

</div>

<div id="tools" class="btn-toolbar">

<?php if($auth->role->name == 'Admin' || $auth->role->name == 'Editor'): ?>

	<div class="action btn-group">

		<a class="btn btn-inverse" href="/works/edit/<?=$work->slug ?>">
			<i class="icon-pencil icon-white"></i> Edit Artwork
		</a>
		<a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="">
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li>
				<a href="/works/edit/<?=$work->slug ?>">
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
					$num_thumbs = sizeof($work_documents);
					$span = $num_thumbs > 1 ? 'span3' : 'span6';
					$size = $num_thumbs > 1 ? 'thumb' : 'small';
				?>
		
			<?php foreach($work_documents as $wd): ?>
			
				<li class="<?=$span?>">
					<a href="/documents/view/<?=$wd->document->slug?>" class="thumbnail">
						<img src="/files/<?=$size?>/<?=$wd->document->slug?>.jpeg" alt="<?=$wd->document->title ?>">
					</a>
				</li>
			
			<?php endforeach; ?>
			
			<?php if(sizeof($work_documents) == 0): ?>
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
    		<?php echo $work->caption(); ?>
    	</p>
		</div>
	
		<table class="table">
			<tbody>
				<tr>
					<td><i class="icon-barcode"></i></td>
					<td class="meta">Artwork ID</td>
					<td>
						<?php 
						
						if($work->creation_number) {
							echo $work->creation_number;
						} else {
							echo '<span class="label label-important">Missing</span>';
						}
						
						?>
					</td>
				</tr>
				<tr>
					<td><i class="icon-tag"></i></td>
					<td class="meta">Classification</td>
					<td><?=$work->classification ?></td>
				</tr>
				<tr>
					<td><i class="icon-book"></i></td>
					<td class="meta">Collections</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
						
							<?php foreach($collections as $collection): ?>
							<li><strong><?=$this->html->link(
								$collection->title,
								'/collections/view/'.$collection->slug
							);?></strong></li>
							<?php endforeach; ?>
						
						</ul>
					</td>
				</tr>
				<tr>
					<td><i class="icon-eye-open"></i></td>
					<td class="meta">Exhibitions</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
						
							<?php foreach($exhibitions as $collection): ?>
							<li><strong><?=$this->html->link(
								$collection->title,
								'/exhibitions/view/'.$collection->slug
							);?></strong></li>
							<?php endforeach; ?>
						
						</ul>
					</td>
				</tr>
				<tr>
					<td><i class=" icon-info-sign"></i></td>
					<td class="meta">Info</td>
					<td>
						<?php 
						
						echo $work->notes();
						
						?>
					
				</tr>
				<tr>
					<td><i class="icon-folder-open"></i></td>
					<td class="meta">Documents</td>
					<td>
						<ul class="unstyled" style="margin-bottom:0">
					
		
					<?php foreach($work_documents as $wd): ?>
			
							<li><a href="/documents/view/<?=$wd->document->slug?>">
								<strong><?=$wd->document->slug?>.<?=$wd->format->extension?></strong>
							</a></li>
			
					<?php endforeach; ?>
					</ul>
					<td>
				</tr>
			</tbody>
		
		</table>
	
	</div>
</div>



<div class="modal fade hide" id="deleteModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Delete Artwork</h3>
		</div>
		<div class="modal-body">
			<p>Are you sure you want to permanently delete <strong><?=$work->title; ?></strong>? This will not delete any associated documents. It will only remove this Artwork and its information from the listings.</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($work, array('url' => "/works/delete/$work->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>
