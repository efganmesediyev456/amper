<div class="d-flex">
    <a href="{{ route('admin.order_cancellation_reasons.edit', $row->id) }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.order_cancellation_reasons.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Bu sosial bağlantını silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
