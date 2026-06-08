<div class="d-flex justify-content-center">
    <a href="{{ route('admin.processes.edit',encrypt($row->id))}}?process_type=process" title="Edit">
        <i class="fas fa-edit ml-1"></i>
    </a>
    <form action="{{ route('admin.processes.destroy', encrypt($row->id)) }}" method="POST">
        @csrf
        <input type="hidden" name="process_type" value="process">
        @method('DELETE')
        <a href="javascript:void(0)" id="delete_confirm"> <i class="fas fa-trash text-danger ml-1"></i></a>
    </form>
</div>
<script>
    confirmAlert('#delete_confirm', "If you delete this Item, it cannot be reverted")
</script>




