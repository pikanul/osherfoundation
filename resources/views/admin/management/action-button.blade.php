<div class="d-flex justity-content-center">
    {{-- <a href="{{ route('admin.managements.show',encrypt($row->id))}}" title="Show">
        <i class="fas fa-eye ml-1"></i>
    </a> --}}
    <a href="{{ route('admin.managements.edit',encrypt($row->id))}}" title="Edit">
        <i class="fas fa-edit ml-1"></i>
    </a>
    <form action="{{ route('admin.managements.destroy', encrypt($row->id)) }}" method="POST">
        @csrf
        @method('DELETE')
        <a href="javascript:void(0)" id="delete_confirm"> <i class="fas fa-trash text-danger ml-1"></i></a>
    </form>
</div>
<script>
    confirmAlert('#delete_confirm', "If you delete this Item, it cannot be reverted")
</script>




