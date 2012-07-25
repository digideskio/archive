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
						</ul>
					</td>
				</tr>
				<tr>
					<td><i class="icon-eye-open"></i></td>
					<td class="meta">Exhibitions</td>
					<td>
						None
					</td>
				</tr>
				<tr>
					<td><i class=" icon-info-sign"></i></td>
					<td class="meta">Info</td>
					<td>
						<?php 
						
						$remarks =  $work->remarks ? $work->remarks : '';
						$quantity = $work->quantity ? 'Quantity: ' . $work->quantity : '';
						$measurement_remarks = $work->measurement_remarks ? $work->measurement_remarks : '';
						$location = $work->location ? 'Location: ' . $work->location : '';
						$lender = $work->lender ? 'Lender: ' . $work->lender : '';
						
						$info = array_filter(array(
							$remarks,
							$quantity,
							$measurement_remarks,
							$location,
							$lender
						));
						
						echo implode('<br/>', $info);
						
						?>
					
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
			<p>Are you sure you want to permanently delete <strong><?=$work->title; ?></strong>?</p>
			</div>
			<div class="modal-footer">
			<?=$this->form->create($work, array('url' => "/works/delete/$work->slug", 'method' => 'post')); ?>
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<?=$this->form->submit('Delete', array('class' => 'btn btn-danger')); ?>
			<?=$this->form->end(); ?>
	</div>
</div>