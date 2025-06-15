@props(['title', 'value', 'color' => 'info'])

<div class="col-md-3 mb-4">
  <a href="#" class="text-decoration-none">
    <div class="card text-center text-{{ $color }} p-3">
      <h6>{{ $title }}</h6>
      <h1 class="mt-2 mb-0">{!! $value !!}</h1>
    </div>
  </a>
</div>
