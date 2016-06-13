<?php

use FileStorage\Models\Categories;

/**
 * @var Categories[] $categories
 */

?>
<!--suppress ALL -->
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

    var uploadType = 'local';

    var progressFileUrl = '<?= $this->url->path('upload/download-file-progress'); ?>';
    var getAuthTokenUrl = '<?= $this->url->path('protected-auth/get-token'); ?>';

    var progressFile = null;
    var authToken = null;

    app.DOM.ready(function($){

        var responseBody = $('#response-body');
        var errorBox = $('#error-response');
        var successBox = $('#success-response');

        $('#input-file').on('change', function(e){
            $('#input-file-text').html($(this).val().split(/[\\\/]+/).slice(-1).pop());
        });

        $('#upload-type input[type=radio]').on('change', function () {
            uploadType = $(this).val();
            if(uploadType == 'local') {
                $('#local').show();
                $('#direct-link').hide();
            } else {
                $('#local').hide();
                $('#direct-link').show();
            }
            app.get(progressFileUrl).then(function(response){
                response = JSON.parse(response);
                progressFile = response.response.url;
            });
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

            var intervalId = 0;

            progressBox.show();
            app.ajax({
                url: form.attr('action'),
                data: formData,
                progress: function (event) {
                    if(uploadType == 'local') {
                        progressBar.css('width', 100 * (event.loaded / event.total) + '%');
                    }
                }
            }).then(function(response){
                progressBox.hide();
                clearInterval(intervalId);

                responseBody.show();

                var data = JSON.parse(response);

                if(data.status == 'success') {
                    var hash = data.response.uploaded_file_uid;
                    errorBox.hide();
                    successBox.show().find('div').html(hash);
                } else {
                    successBox.hide();
                    errorBox.show().find('div').html(data.response.message);
                }
            });

            if(uploadType == 'direct-link') {
                intervalId = setInterval(function () {
                    app.get(progressFile).then(function(response){
                        progressBar.css('width', response + '%');
                    });
                }, 500);
            }

        });
    });
</script>
<div class="row">
    <div class="grid-5 grid-small-10 grid-smallest-10">
        <h2>Upload new file</h2>
        <form id="upload-form" class="form-horizontal form-label-right bg-color-dark" action="<?= $url->path('upload/index', ['token' => $token,]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-row" id="upload-type">
                <label class="grid-3">upload type</label>
                <div class="grid-7" id="file-secure">
                    <label class="check-item">
                        <input checked class="radio radio-color-warning" name="upload_type" type="radio" value="local">
                        from your computer
                    </label>
                    <label class="check-item">
                        <input class="radio radio-color-warning" name="upload_type" type="radio" value="direct_link">
                        direct link from internet
                    </label>
                </div>
            </div>
            <div class="form-row" id="local">
                <label class="grid-3">file</label>
                <div class="grid-7">
                    <div class="button button-warning relative-block" style="position: relative;">
                        <span id="input-file-text">browse...</span>
                        <input id="input-file" class="hidden-file" type="file" name="test_file" >
                    </div>
                </div>
            </div>
            <div class="form-row hidden" id="direct-link">
                <label class="grid-3">link on file</label>
                <div class="grid-7">
                    <input placeholder="link on file..." id="file-name" type="text" name="direct_link" class="input input-color-warning input-border-default">
                </div>
            </div>
            <div class="form-row">
                <label class="grid-3">name</label>
                <div class="grid-7">
                    <input placeholder="short file description..." id="file-name" type="text" name="name" class="input input-color-warning input-border-default">
                </div>
            </div>
            <div class="form-row">
                <label class="grid-3">category</label>
                <div class="grid-7">
                    <select id="file-category" class="button button-warning input-border-default" name="category_id">
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category->id() ?>"><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label class="grid-3">protected</label>
                <div class="grid-7" id="file-secure">
                    <label class="check-item">
                        <input class="checkbox checkbox-color-warning" type="checkbox" name="protected" value="1">
                        yes (without direct link)
                    </label>
                </div>
            </div>
            <div class="form-row">
                <label class="grid-3"></label>
                <div class="grid-7">
                    <input type="submit" value="upload" class="button button-warning">
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
    <div class="grid-5 grid-small-10 grid-smallest-10 hidden" id="response-body">
        <h2>Response</h2>
        <div id="error-response" class="flash-messages flash-messages-warning hidden">
            <div></div>
        </div>
        <div id="success-response" class="flash-messages flash-messages-notice hidden">
            <div></div>
        </div>
    </div>
</div>