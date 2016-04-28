<?php

use FileStorage\Models\Categories;

/**
 * @var Categories[] $categories
 */

?>
<script>

    app.DOM.extend({
        native: function(index) {
            return app.DOM.isElement(this[index]) ? this[index] : null;
        }
    });

    app.DOM.ready(function($){

        $('#input-file').on('change', function(e){
            $('#input-file-text').html($(this).val().split(/[\\\/]+/).slice(-1).pop());
        });

        $('#upload-form').on('submit', function(e){
            e.preventDefault();

            var form = $(this);
            var file = $('#input-file');
            var formData = new FormData();
            var progressBox = $('#progress-box');
            var progressBar = $('#progress-bar');

            formData.append('file', file.native(0).files[0]);
            formData.append('name', $('#file-name').val());
            formData.append('category', $('#file-category').val());
            formData.append('protected', $('#file-secure input[type=radio]:checked').val());

            progressBox.show();

            app.ajax({
                url: form.attr('action'),
                data: formData,
                progress: function (event) {
                    progressBar.css('width', (100 * (event.loaded / event.total)) + '%');
                }
            }).then(function(response){
                progressBox.hide();
                $('#response-body pre').html(response);
                if(JSON.parse(response).status == 'success') {
                    form.native(0).reset();
                }
            });
        });
    });
</script>
<div class="row">
    <div class="grid-4 grid-middle-5 grid-small-7 grid-smallest-10">
        <h2>Upload new file</h2>
        <form id="upload-form" class="form-horizontal form-label-right bg-color-dark" action="<?= $url->path('upload/index'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <label class="grid-2">file</label>
                <div class="grid-8">
                    <div class="button button-warning button-rounded relative-block" style="position: relative;">
                        <span id="input-file-text">select file...</span>
                        <input id="input-file" class="hidden-file" type="file" name="test_file" >
                    </div>
                </div>
            </div>
            <div class="form-row">
                <label class="grid-2">name</label>
                <div class="grid-8">
                    <input id="file-name" type="text" name="name" class="input input-color-warning input-rounded input-border-default">
                </div>
            </div>
            <div class="form-row">
                <label class="grid-2">category</label>
                <div class="grid-8">
                    <select id="file-category" class="button button-warning input-rounded input-border-default" name="category_id">
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category->id() ?>"><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label class="grid-2">protected</label>
                <div class="grid-8" id="file-secure">
                    <label class="check-item">
                        <input class="radio radio-color-warning" type="radio" name="protected" value="1">
                        yes (then access only by auth-token)
                    </label>
                    <label class="check-item">
                        <input checked class="radio radio-color-warning" type="radio" name="protected" value="0">
                        no
                    </label>
                </div>
            </div>
            <div class="form-row">
                <label class="grid-2"></label>
                <div class="grid-8">
                    <input type="submit" value="upload" class="button button-warning button-rounded">
                </div>
            </div>
            <div id="progress-box" class="form-row hidden">
                <div class="grid-10">
                    <div class="input-rounded input-rounded bg-color-dark-gray">
                        <div id="progress-bar" style="height: 10px; width: 0%;" class="bg-color-warning input-rounded"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="grid-10" id="response-body">
        <h2>Response</h2>
        <pre></pre>
    </div>
</div>