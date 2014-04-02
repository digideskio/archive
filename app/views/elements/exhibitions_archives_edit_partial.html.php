<div class="well">
	<legend>Exhibitions</legend>
	<table class="table">

		<?php foreach($exhibitions as $exhibition): ?>
		<?php $component = $exhibition->components->first(); ?>
			<tr>
				<td>
					<strong><?=$this->html->link($exhibition->archive->name, $this->url(array('Exhibitions::view', 'slug' => $exhibition->archive->slug))); ?></strong>
					<?php if ($exhibition->archive->years()): ?>
						<small><?=$exhibition->archive->dates(); ?></small>
					<?php endif; ?>
				</td>
				<td align="right" style="text-align:right">
		<?=$this->form->create($component, array('url' => $this->url(array('Components::delete', 'id' => $component->id)), 'method' => 'post')); ?>
		<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
		<?=$this->form->end(); ?>
				</td>
			</tr>

		<?php endforeach; ?>

		<?php if(sizeof($other_exhibitions) > 0): ?>

		<tr>
			<td></td>
			<td align="right" style="text-align:right">
				<a data-toggle="modal" href="#exhibitionModal" class="btn btn-mini btn-inverse">Add an Exhibition</a>
			</td>
		</tr>

		<?php endif; ?>

		</table>

</div>

<div class="modal fade hide" id="exhibitionModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add this Record to an Exhibition</h3>
		</div>
		<div class="modal-body">
			<table class="table"><tbody>
			<?php foreach($other_exhibitions as $oe): ?>
				<tr>
					<td>
						<a href="/exhibitions/view/<?=$oe->archive->slug ?>">
							<strong><?=$oe->archive->name ?></a></strong><br/>
							<?=$oe->venue ?><br/>
							<?=$oe->archive->dates() ?>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($oe, array('url' => $this->url(array('Components::add')), 'method' => 'post')); ?>
			<input type="hidden" name="archive_id1" value="<?=$oe->id ?>" />
			<input type="hidden" name="archive_id2" value="<?=$archive->id ?>" />
			<input type="hidden" name="type" value="<?=$component_type ?>" />
			<?=$this->form->submit('Add', array('class' => 'btn btn-mini btn-success')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody></table>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</div>
