<div class="row">
    @foreach ($carousels as $carousel)
        <div class="col-md-4 mb-3">
            <div class="card bg-dark text-white">
                <img src="{{ asset('images/carousel/' . $carousel->image) }}" class="card-img">
                <div class="card-img-overlay text-end">
                    <button type="button" onclick="rem_image({{ $carousel->sr_no }})"
                        class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Xoá
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
