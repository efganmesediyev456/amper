<div class="d-flex">
    <a href="{{ route('admin.weekly_selections.edit', $row->id) }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.weekly_selections.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Bu həftənin seçimini silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>