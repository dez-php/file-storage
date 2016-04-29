<?php

use FileStorage\Models\Categories;

/**
 * @var Categories[] $categories
 */

?>
<style>
    #progress-bar {
        transition: width 0.5s, display 1s;
    }
</style>
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

        $('#upload-type input[type=radio]').on('change', function () {
            var uploadType = $(this).val();
            if(uploadType == 'local') {
                $('#local').show();
                $('#direct-link').hide();
            } else {
                $('#local').hide();
                $('#direct-link').show();
            }
        });

        $('#upload-form').on('submit', function(e){
            e.preventDefault();

            var form = $(this);
            var file = $('#input-file');
            var progressBox = $('#progress-box');
            var progressBar = $('#progress-bar');
            var formData = new FormData();

            formData.append('file', file.native(0).files[0]);
            $('.input, .radio:checked, .checkbox:checked, select').each(function (element) {
                element = $(element);
                formData.append(element.attr('name'), element.val());
            });

            progressBox.show();
            app.ajax({
                url: form.attr('action'),
                data: formData,
                progress: function (event) {
                    progressBar.css('width', 100 * (event.loaded / event.total) + '%');
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
            <div class="form-row" id="upload-type">
                <label class="grid-2">upload type</label>
                <div class="grid-8" id="file-secure">
                    <label class="check-item">
                        <input checked class="radio radio-color-warning" name="upload-type" type="radio" value="local">
                        from your computer
                    </label>
                    <label class="check-item">
                        <input class="radio radio-color-warning" name="upload-type" type="radio" value="direct-link">
                        direct link from internet
                    </label>
                </div>
            </div>
            <div class="form-row" id="local">
                <label class="grid-2">file</label>
                <div class="grid-8">
                    <div class="button button-warning button-rounded relative-block" style="position: relative;">
                        <span id="input-file-text">browse file on your computer...</span>
                        <input id="input-file" class="hidden-file" type="file" name="test_file" >
                    </div>
                </div>
            </div>
            <div class="form-row hidden" id="direct-link">
                <label class="grid-2">link on file</label>
                <div class="grid-8">
                    <input placeholder="link on file..." id="file-name" type="text" name="direct-link" class="input input-color-warning input-rounded input-border-default">
                </div>
            </div>
            <div class="form-row">
                <label class="grid-2">name</label>
                <div class="grid-8">
                    <input  placeholder="short file description..." id="file-name" type="text" name="name" class="input input-color-warning input-rounded input-border-default">
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
                        <input class="checkbox checkbox-color-warning" type="checkbox" name="protected" value="1">
                        yes (then access only by auth-token)
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