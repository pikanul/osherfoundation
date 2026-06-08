<table class="table talbe-bordered border table-striped table-hover">
    <tr colspan="2">
        <td>faq Info : </td>
    </tr>


    <tr>
        <td>Title</td>
        <td>{{ $faq->title }}</td>
    </tr>
    <tr>
        <td>Short Description</td>
        <td>{{ $faq->answer }}</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>{{ $faq->status ? 'Active' : 'Inactive' }}</td>

    </tr>
    
</table>
