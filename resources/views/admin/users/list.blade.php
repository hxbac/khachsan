@php $i = 1; @endphp

@foreach ($users as $user)
    @php
        $del_btn = "<button type='button' onclick='remove_user($user->id)' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>";

        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

        if ($user->is_verified) {
            $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            $del_btn = '';
        }

        $status = "<button onclick='toggle_status($user->id,0)' class='btn btn-dark btn-sm shadow-none'>
                        Hoạt Động
                    </button>";

        if (!$user->status) {
            $status = "<button onclick='toggle_status($user->id,1)' class='btn btn-danger btn-sm shadow-none'>
                            Vô Hiệu Hoá
                        </button>";
        }

        $date = \Carbon\Carbon::parse($user->datentime)->format('d-m-Y');
    @endphp

    <tr>
        <td>{{ $i++ }}</td>
        <td>
            <img src="{{ asset($user->profile) }}" width="55px">
            <br>
            {{ $user->name }}
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phonenum }}</td>
        <td>{{ $user->address }} | {{ $user->pincode }}</td>
        <td>{{ $user->dob }}</td>
        <td>{!! $status !!}</td>
        <td>{{ $date }}</td>
        <td>{!! $del_btn !!}</td>
    </tr>
@endforeach
