<div class="row">
    <div class="grid-7 grid-smallest-10">
        <h2>Stats</h2>
        <div class="row">
            <div class="grid-10">
                <a class="button button-warning button-size-small" href="<?= $url->path('manager/files/latest'); ?>">latest</a>
                <a class="button button-pink button-size-small" href="<?= $url->path('manager/files/protected'); ?>">protected</a>
                <a class="button button-violet button-size-small" href="<?= $url->path('manager/files/deleted'); ?>">deleted</a>
                <a class="button button-gray button-size-small" href="<?= $url->path('manager/files/non-categorized'); ?>">non categorized</a>
            </div>
        </div>
        <table class="table table-striped table-caption-upper table-hovered">
            <thead>
            <tr>
                <th>name</th>
                <th>okey</th>
                <th>web-site</th>
                <th>asd</th>
                <th>action</th>
            </tr>
            </thead>
            <tr>
                <td>
                    <input class="checkbox" type="checkbox" name="">
                </td>
                <td>4567</td>
                <td>567</td>
                <td>678</td>
                <td class="text-center">
                    <code>.table.table-striped</code>
                </td>
            </tr>
            <tfoot>
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td class="text-center">
                    <input class="checkbox checkbox-color-danger" type="checkbox" name="">
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>