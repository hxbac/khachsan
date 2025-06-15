@php
    $path = asset('images/about/');
@endphp

<div class="row">
    @foreach($team as $member)
        <div class="col-md-2 mb-3">
            <div class="card bg-dark text-white">
                <img src="{{ $path . '/' . $member->picture }}" class="card-img">
                <div class="card-img-overlay text-end">
                    <button type="button" onclick="rem_member({{ $member->sr_no }})" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i> Xoá
                    </button>
                </div>
                <p class="card-text text-center px-3 py-2">{{ $member->name }}</p>
            </div>
        </div>
    @endforeach
</div>
