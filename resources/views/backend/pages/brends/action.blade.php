<div class="d-flex">
    <a href="{{ route('admin.brends.edit', ['item'=>$row->id]) }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.brends.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Bu brendi silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>