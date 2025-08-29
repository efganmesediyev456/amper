<div class="d-flex">
    <a href="{{ route('admin.vacancy-receipents.edit', $row->id) }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.vacancy-receipents.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Bu vakansiya alıcısını silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>