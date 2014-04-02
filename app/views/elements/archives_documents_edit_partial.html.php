<?php

	//Get 'Works' from 'app\models\Works', etc.
	$model_name = basename(str_replace('\\', '/', $model->model()));
	$model_name_sing = lithium\util\Inflector::singularize($model_name);

	$add_url = $this->url(array("ArchivesDocuments::add"));

	//Get upload endpoint
	$upload_document_url = $this->url(array('Documents::upload')) .
		'?archive_id=' . $model->archive->id;

	//Get 'work_slug', etc.
	$model_slug_name = lithium\util\Inflector::underscore("$model_name_sing slug");
	$model_slug_value = $model->slug ?: $model->archive->slug;

	$view_model_url = $this->url(array("$model_name::view", 'slug' => $model_slug_value));

?>

     <div id="filelink"></div>

<div class="error alert alert-error" style="display:none;">
	<p id="errorMessage"></p>
	</div>
	<div class="uploadWrapper">
		<div id="FileContainer">

		<div id="dropArea">

		<legend>Upload a Document</legend>

			<h6 style="letter-spacing:.2em; text-align:center;">Drop Files Here</h6>

			<hr/>

		<div id="filelist"></div>

		<div id="fileMessage">
		</div>

		<h3 id="fileSelectMsg">Choose File(s) to Upload</h3>
		<a class="btn btn-inverse" id="pickfiles" href="#">Add Files</a>

		</div>

	</div>
</div>

<table id="FileTypes" runat="server" class="list" cellspacing="2" cellpadding="2" width="100%" border="0">
	<tr>
	<th style="white-space: nowrap;">
	<asp:Literal ID="lblFilter" runat="server" />
	</th>
	<td width="100%">
	<asp:Literal ID="lblSupported" runat="server" />
	</td>
	</tr>
</table>

<script type="text/javascript">
            $(document).ready(function(){
                var uploader = new plupload.Uploader({

                    runtimes : 'gears,html5,flash,silverlight,browserplus',
                    url:"<?php echo $upload_document_url; ?>",
                    browse_button : "pickfiles",
                    button_browse_hover : true,
                    drop_element : "dropArea",
                    autostart : true,
                    max_file_size: '250mb',
                    container: "FileContainer",
                    chunk_size: '1mb',
                    unique_names: false,
                    // Flash settings
                    flash_swf_url: "js/plupload.flash.swf",
                    // Silverlight settings
                    silverlight_xap_url: "js/plupload.silverlight.xap"
                });

                var fileTypes = '';
                var fileTypesFilter = 'allow';
                var $body = $("body");
                var $dropArea = $("#dropArea");

                $body.bind("dragenter", function(e){
                    $dropArea.addClass("draggingFile");
                    e.stopPropagation();
                    e.preventDefault();
                });

                $body.bind("dragleave", function(e){ $dropArea.removeClass("draggingFile"); });

                $body.bind("dragover", function(e){
                    $dropArea.addClass("draggingFile");
                    e.stopPropagation();
                    e.preventDefault();
                });

                $body.bind("drop", function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    $dropArea.removeClass();
                });

                $dropArea.bind("dragenter", function(e){
                    $dropArea.addClass("draggingFileHover");
                    e.stopPropagation();
                    e.preventDefault();
                });
                $dropArea.bind("dragleave", function(e){ $dropArea.removeClass("draggingFileHover"); });
                $dropArea.bind("dragover", function(e){
                    $dropArea.addClass("draggingFileHover");
                    e.stopPropagation();
                    e.preventDefault();
                });

                //Checks to make sure the browser supports drag and drop uploads
                uploader.bind('Init', function(up, params){
                    if(window.FileReader && $.browser.webkit && !( (params.runtime == "flash") || (params.runtime == "silverlight") ) )
                    {
                        $("#dropArea").show();
                        $("#fileSelectMsg").hide();
                    }
                });

                uploader.init();
                uploader.bind('FilesAdded', function(up, files) {
                    $dropArea.removeClass();
                    $.each(files, function(i, file) {

                        //Checks a comma delimted list for allowable file types set file types to allow for all

                        var fileExtension = file.name.substring(file.name.lastIndexOf(".")+1, file.name.length).toLowerCase();
                        var supportedExtensions = fileTypes.split(",");

                        var supportedFileExtension = ($.inArray(fileExtension, supportedExtensions) >= 0);
                        if(fileTypesFilter == "allow")
                        {
                            supportedFileExtension = !supportedFileExtension
                        }

                        if( (fileTypes == "all") || supportedFileExtension)
                        {
                            var filename = file.name;
                            if(filename.length > 50)
                            {
                                filename = filename.substring(0,50)+"...";
                            }

                             $('#filelist').append(
	                            '<div id="' + file.id + '" class="fileItem"><div class="filename data"><p>' +
	                            filename + '</p></div><div class="sizing data"><p>' + plupload.formatSize(file.size) +
	                            ' / ' + '<span class="percentComplete">0%</span></p></div>' +
	                            '<div class="plupload_progress"><div class="progress progress-striped active">' +
	                            '<div class="bar"></div></div></div><hr>'
                            );

                            //Fire Upload Event
                            up.refresh(); // Reposition Flash/Silverlight
                            uploader.start();

                            //Bind cancel click event
                            $('#cancel'+file.id).click(function(){
                                $fileItem = $('#' + file.id);
                                $fileItem.addClass("cancelled");
                                uploader.removeFile(file);
                                currentStorage -= ( (file.size)/(1024*1024) );
                                $(this).remove();
                            });
                        }
                        else
                        {
                            //Not a supported file extension
                            $errorPanel = $('div.error:first');
                            $errorPanel.show().html('<p>The file you selected is not supported in this section.');
                        }
                    });
                });

                uploader.bind('UploadProgress', function(up, file) {
                    var  $fileWrapper = $('#' + file.id);
                    $fileWrapper.find(".plupload_progress").show();
                    $fileWrapper.find(".bar").attr("style", "width:"+ file.percent + "%");
                    $fileWrapper.find(".percentComplete").html(file.percent+"%");
                    $fileWrapper.find('#cancel'+file.id).addClass('hide');

					$('#fileMessage').html('');
                });


                uploader.bind('Error', function(up, err) {
                    $errorPanel = $("div.error:first");
                    //-600 means the file is larger than the max allowable file size on the uploader thats set in the options above.
                    if(err.code == "-600")
                    {
                        $errorPanel.show().html('<p>The file you are trying to upload exceeds the single file size limit of 250MB</p>');
                    }
                    else
                    {
                        $errorPanel.show().html('<p>There was an error uploading your file '+ err.file.name + '.</p>');
                    }

                    $('#' + err.file.id).addClass("cancelled");
                    uploader.stop();
                    uploader.refresh(); // Reposition Flash/Silverlight
                });

                uploader.bind('FileUploaded', function(up, file, response) {
                    $fileItem = $('#' + file.id);
                    $fileItem.find('.progress').removeClass('active progress-striped');
                    $fileItem.find('.progress').addClass('progress-success');
                    $('#cancel'+file.id).remove();

                    response = jQuery.parseJSON( response.response );

                    $('#fileMessage').html('<p class="muted">Your files have finished uploading. Please <a href="<?php echo $view_model_url; ?>">continue</a>.</p>');

                });


            });

    </script>

<script type="text/javascript">

$(document).ready(function() {

	$('#documentModal').on('shown', function() {
		$('#add-document-search-title').focus();
	});

	docHandles('/documents/pages/1.json?');

	$('#documentModal').on("click", ".paging", function(event) {
		event.preventDefault();

		docHandles(this.href);

		return false;
	});

	$('#documentModal').on("submit", "form#add-document-search", function(event) {
		event.preventDefault();

		var title = $('#add-document-search-title').val();

		docHandles('/documents/pages/1.json?search=' + title);

		return false;

	});

	function docHandles(href) {

		var source = $("#add-document-items").html();
		var template = Handlebars.compile(source);

		$.ajax({
			url: href + '&limit=5',
			dataType: 'json',
			success: function(data) {
				var context = data;
				var html = template(context);
				$("#add-document-list").html(html);
			}
		});
	}

});

Handlebars.registerHelper('previous_page', function() {
	if (this.page > 1) {
		return new Handlebars.SafeString(
			"<li><a id='page-left' class='paging' href='/documents/pages/" + (parseInt(this.page) - 1) + ".json?search=" + this.search + "'>«</a></li>"
		);
	} else {
		return new Handlebars.SafeString(
			"<li class='active'><a href='#' disabled='disabled'>«</a></li>"
		);
	}
});

Handlebars.registerHelper('next_page', function() {
	if (this.total > (this.limit * this.page)) {
		return new Handlebars.SafeString(
			"<li><a class='paging' href='/documents/pages/" + (parseInt(this.page) + 1) + ".json?search=" + this.search + "'>»</a></li>"
		);
	}
});

Handlebars.registerHelper('document_rows', function() {
	var out = '';

	var docs = this.documents;

	for (key in docs) {
		var doc = docs[key];
		out +=
			"<tr><td width='30px'>"
			+ "<img width='30px' src='/files/thumb/"
			+ doc['slug']
			+ ".jpeg'/>"
			+ '</td><td>'
			+ doc['title']
			+ '</td>'
			+ '<td>'
			+ "<td align='right' style='text-align:right'>"
			+ "<form action='<?=$add_url ?>' method='post'>"
			+ "<input type='hidden' name='document_id' value='" + doc['id'] + "'>"
			+ "<input type='hidden' name='archive_id' value='<?=$model->archive->id ?>'>"
			+ "<input type='submit' value='Add' class='btn btn-mini btn-success'>"
			+ '</form>'
			+ '</td>'
			+ '</tr>';
	}

	return new Handlebars.SafeString(out);
});

</script>

<script id="add-document-items" type="text/x-handlebars">
	<table class="table">

		<tbody>
			{{document_rows}}
			{{#each documents}}
				<tr><td>{{title}}</td></tr>
			{{/each}}
		</tbody>

	</table>
	<div class="pagination" style="margin-top: 0">
		<ul>
			{{previous_page}}
			<li class="active"><a href="#">{{page}}</a></li>
			{{next_page}}
		</ul>
	</div>

</script>

	<div class="well">
		<legend>Images</legend>
		<table class="table">

			<?php foreach($archives_documents as $ad): ?>

<?php
	$delete_url = $this->url(array("ArchivesDocuments::delete", 'id'=> $ad->id));

	$view_document_url = $this->url(array('Documents::view', 'slug' => $ad->document->slug));
?>

				<tr>
					<td align="center" valign="center" style="text-align: center; vertical-align: center; width: 125px;">
						<?php $px = '260'; ?>
						<a href="/documents/view/<?=$ad->document->slug ?>" title="<?=$ad->document->title ?>">
						<img width="125" height="125" src="/files/<?=$ad->document->view(); ?>" alt="<?=$ad->document->title ?>">
						</a>
					</td>
					<td align="right" style="text-align:right">
			<?=$this->form->create($ad, array('url' => $delete_url, 'method' => 'post')); ?>
			<?=$this->form->submit('Remove', array('class' => 'btn btn-mini btn-danger')); ?>
			<?=$this->form->end(); ?>
					</td>
				</tr>

			<?php endforeach; ?>
			<tr>
				<td></td>
				<td align="right" style="text-align:right">
					<a data-toggle="modal" href="#documentModal" class="btn btn-mini btn-inverse">Add a Document</a>
				</td>
			</tr>

			</table>
	</div>


<div class="modal fade hide" id="documentModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add a Document</h3>
		</div>
		<div class="modal-body">
			<form id="add-document-search" class="form-search">
			  <input type="text" id="add-document-search-title" name="title" class="input-medium search-query" placeholder="Search..." style="width: 90%">
			</form>
			<div id="add-document-list"></div>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
	</div>
</div>
