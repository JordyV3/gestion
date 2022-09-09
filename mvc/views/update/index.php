
<div class="row">
    <div class="col-sm-4 ">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-refresh"></i> <?=$this->lang->line('panel_title')?></h3>
            </div>
            <div class="box-body">
                <form role="form" role="form" method="post" enctype="multipart/form-data">
                    <div class="<?=form_error('file') ? 'form-group has-error' : 'form-group' ?>">
                        <label for="file"><?=$this->lang->line("update_file")?> <span class="text-red">*</span></label>
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span>
                                    <?=$this->lang->line('update_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title">
                                    <?=$this->lang->line('update_file_browse')?></span>
                                    <input id="uploadBtn" type="file" name="file"/>
                                </div>
                            </span>
                        </div>
                    </div>

                    <input id="update" type="submit" class="btn btn-success" value="<?=$this->lang->line("update_update")?>" >
                </form>
            </div>
        </div>
    </div> 
</div>

<div class="modal fade" id="log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('update_updatelog')?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="logcontent"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
        </div>
    </div>
</div>

<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-info">
        <p><i class="fa fa-info-circle fa-2x"></i> Asegúrese de que el archivo este en el formato ZIP, al actualizar correctamente la sesión se cerrará.</p>
    </div>
<?php } ?>

<script type="text/javascript">
    $('.getloginfobtn').click(function() {
        var updateID =  $(this).attr('id');
        if(updateID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('update/getloginfo')?>",
                data: {'updateID' : updateID},
                dataType: "html",
                success: function(data) {
                    $('#logcontent').html(data);
                }
            });
        }
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content

        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });
            var file = this.files[0];
            var reader = new FileReader();

            $('.image-preview-clear').click(function(){
                $('.image-preview').attr("data-content","").popover('hide');
                $('.image-preview-filename').val("");
                $('.image-preview-clear').hide();
                $('.image-preview-input input:file').val("");
                $(".image-preview-input-title").text("<?=$this->lang->line('update_file_browse')?>");
            });

            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('update_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });
</script>