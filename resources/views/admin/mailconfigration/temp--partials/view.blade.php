<table class="table talbe-bordered border table-striped table-hover">
    <tr>
        <td>Key</td>
        <td>{{ $Translation->key }}</td>
    </tr>
    <tr>
        <td>Type</td>
        <td>{{ $Translation->type }}</td>
    </tr>
    <tr>
        <td>Language - {{ $Translation->lang->name }}</td>
        <td>{{ $Translation->value }}</td>
    </tr>


    <x-quick.table_action_info :info='$Translation' />
</table>
