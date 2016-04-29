<div class="row">
    <div class="grid-10">
        <h2>Server info</h2>
        <table class="table table-striped table-caption-upper table-hovered">
            <thead>
            <tr>
                <th>name</th>
                <th>value</th>
            </tr>
            </thead>
            <tr>
                <td>system</td>
                <td><code><?= $os ?></code></td>
            </tr>
            <tr>
                <td>server api</td>
                <td><code><?= $sapi ?></code></td>
            </tr>
            <tr>
                <td>php version</td>
                <td><code><?= $php_version ?></code></td>
            </tr>
            <tr>
                <td>free disk space</td>
                <td><code><?= $free_disk_space ?></code></td>
            </tr>
            <tr>
                <td>upload max size</td>
                <td><code><?= $upload_max_filesize ?></code></td>
            </tr>
            <tr>
                <td>post max size</td>
                <td><code><?= $post_max_size ?></code></td>
            </tr>
            <tr>
                <td>public directory</td>
                <td><code><?= $public_directory ?></code> <code><?= $free_disk_space_public ?></code></td>
            </tr>
            <tr>
                <td>private directory</td>
                <td><code><?= $private_directory ?></code> <code><?= $free_disk_space_private ?></code></td>
            </tr>
            <tr>
                <td>allowed mimes</td>
                <td><code><?= ($validation_mimes->get('white')->count() ? implode(', ', $validation_mimes->get('white')->toArray()) : 'no set'); ?></code></td>
            </tr>
            <tr>
                <td>disallowed mimes</td>
                <td><code><?= ($validation_mimes->get('black')->count() ? implode(', ', $validation_mimes->get('black')->toArray()) : 'no set'); ?></code></td>
            </tr>
            <tr>
                <td>allowed extensions</td>
                <td><code><?= ($validation_extensions->get('white')->count() ? implode(', ', $validation_extensions->get('white')->toArray()) : 'no set'); ?></code></td>
            </tr>
            <tr>
                <td>disallowed extensions</td>
                <td><code><?= ($validation_extensions->get('black')->count() ? implode(', ', $validation_extensions->get('black')->toArray()) : 'no set'); ?></code></td>
            </tr>
            <tr>
                <td>count uploaded files</td>
                <td><code><?= $uploaded_files ?></code></td>
            </tr>
            <tr>
                <td>author</td>
                <td><code>Ivan Gontarenko &lt;stewie.dev@gmail.com&gt;</code> <a class="button button-notice button-size-extra-small" target="_blank" href="https://github.com/dez-php/file-storage">github</a></td>
            </tr>
        </table>
    </div>
</div>