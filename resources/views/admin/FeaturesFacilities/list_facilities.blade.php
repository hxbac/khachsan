@php $i = 1; @endphp

@foreach($facilities as $facility)
<tr class="align-middle">
  <td>{{ $i++ }}</td>
  <td><img src="{{ asset($facility->icon) }}" width="100px" alt="icon"></td>
  <td>{{ $facility->name }}</td>
  <td>{{ $facility->description }}</td>
  <td>
    <button type="button" onclick="rem_facility({{ $facility->id }})" class="btn btn-danger btn-sm shadow-none">
      <i class="bi bi-trash"></i> Xoá
    </button>
  </td>
</tr>
@endforeach
