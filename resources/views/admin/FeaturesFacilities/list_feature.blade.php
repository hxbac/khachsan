@php $i = 1; @endphp

@foreach($features as $feature)
  <tr>
    <td>{{ $i++ }}</td>
    <td>{{ $feature->name }}</td>
    <td>
      <button type="button" onclick="rem_feature({{ $feature->id }})" class="btn btn-danger btn-sm shadow-none">
        <i class="bi bi-trash"></i> Xoá
      </button>
    </td>
  </tr>
@endforeach
