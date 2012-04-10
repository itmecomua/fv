<div class="table_body">
    <table class="text">
        <tr>
            <th style="text-align:center;">Кол-во новых</th>
            <th style="text-align:center;">Кол-во подтвержденных</th>
            <th style="text-align:center;">Кол-во удаленных</th>
            <th style="text-align:center;">Кол-во ошибок</th>            
        </tr>
        <tr>
            <td class="mixed">{$res->cnt_new}</td>
            <td class="mixed">{$res->cnt_confirm}</td>
            <td class="mixed">{$res->cnt_delete}</td>
            <td class="mixed">{$res->cnt_error}</td>
        </tr>
    </table>
    {$res->msg}
</div>

