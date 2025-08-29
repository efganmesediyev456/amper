<div class="d-flex">
    <a href="{{ $editRoute }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
     <a href="{{ $showBrends }}" class="btn btn-sm btn-primary me-2 d-flex align-items-center gap-1">
        <i class="fas fa-eye"></i> 
        <span>Brends</span>
    </a>
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Bu mövzunu silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>