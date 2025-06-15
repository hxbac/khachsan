@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            list: `{{ route('admin.rooms.list') }}`,
            addNumber: `{{ route('admin.rooms.addNumber') }}`,
            deleteNumber: `{{ route('admin.rooms.deleteNumber') }}`,
        };
    </script>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h2 class="mb-4">Danh Sách Phòng</h2>
                <div id="room-list-data">

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/room_list.js') }}"></script>
@endsection
