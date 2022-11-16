<div class="field">
    <label>Show Data</label>
    <select id="limit-checkpoint" class="ui dropdown">
        <option value="10">10 rows</option>
        <option value="25">20 rows</option>
        <option value="50">50 rows</option>
        <option value="100">100 rows</option>
    </select>
</div>
<table class="ui very compact celled blue table">
    <thead class="blue">
        <tr>
            <th class='seven wide'>Kode Manifest</th>
            <th class='four wide'>Tujuan</th>
            <th class='six wide'>Aksi</th>
        </tr>
    </thead>
    <tbody id="table-list-manifest">
        <tr>
            <td>John</td>
            <td>No Action</td>
            <td>None</td>
        </tr>
        <tr>
            <td>Jamie</td>
            <td>Approved</td>
            <td>Requires call</td>
        </tr>
        <tr>
            <td>Denied</td>
            <td>Denied</td>
            <td>None</td>
        </tr>
        <tr class="warning">
            <td>John</td>
            <td>No Action</td>
            <td>None</td>
        </tr>
        <tr>
            <td>Jamie</td>
            <td class="positive">Approved</td>
            <td class="warning">Requires call</td>
        </tr>
        <tr>
            <td>Jill</td>
            <td class="negative">Denied</td>
            <td>None</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">
                <div class="ui right floated pagination menu">
                    <a class="icon left item">
                        <i class="left chevron icon"></i>
                    </a>
                    <a class="icon right item">
                        <i class="right chevron icon"></i>
                    </a>
                </div>
            </th>
        </tr>
    </tfoot>
</table>
