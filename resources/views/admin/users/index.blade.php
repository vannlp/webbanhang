@extends('layouts.admin')

@section('content')
    <div class="p-2">
        <h3 class="mb-3">Quản lý người dùng</h3>

        <div class="card mb-4">
            <div class="card-header">
                Hành động
            </div>
            <div class="card-body">
                <div class="mb-2 p-2">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('message'))
                        <div class="alert alert-success">
                            <span>{{session('message')}}</span>
                        </div>
                    @endif
                </div>
                <button class="btn btn-primary btn-icon-split" type="button" data-toggle="modal" data-target="#addModal">
                    <span class="icon text-white-50">
                        <i class="bi bi-plus-lg"></i>
                    </span>
                    <span class="text">Thêm mới</span>
                </button>    
            </div>
        </div>

        <div class="card shadow mb-4">
            <a href="#list_user" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="list_user">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
            </a>
            <div class="collapse show" id="list_user">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Quyền</th>
                                    <th>Phone</th>
                                    <th>Thời gian cập nhập</th>
                                    <th>Cập nhập bởi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         {{-- Modal --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.user.create')}}" id="addForm" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Tên đăng nhập</label>
                                    <input type="text" class="form-control" name="username" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Họ và tên</label>
                                    <input type="text" class="form-control" name="name" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" name="email" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Quyền</label>
                                    <select name="role_id" class="form-control">
                                        <option value="0">--Chọn--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="re_password" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="addForm" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
              </div>
        </div>

        {{-- Modal Edit --}}

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Chi tiết người dùng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form 
                            action="#"
                            id="editForm" method="post"
                        >
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Tên đăng nhập</label>
                                    <input type="text" id="username" class="form-control" name="username" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Họ và tên</label>
                                    <input type="text" id="name" class="form-control" name="name" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Quyền</label>
                                    <select name="role_id" class="form-control" id="role_id">
                                        <option value="0">--Chọn--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                             name="is_active" 
                                             {{-- value="true" --}}
                                             {{-- checked="true" --}}
                                             class="custom-control-input" id="is_active">
                                        <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>
                                
                            </div>

                            {{-- <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password" />
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="re_password" />
                                </div>
                            </div> --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="editForm" class="btn btn-primary">Chỉnh sửa</button>
                    </div>
                </div>
              </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
     function handleEdit(id) {
        const myModal = document.getElementById('editModal');
        const modal = new bootstrap.Modal(myModal);
        window.apis.get(`{{url('admin/user/get-one')}}/${id}`).then(function(response) {
            let res = response.data;
            let user = res.user;
            let roles = res.roles;

            // xử lý dữ liệu gán vào form
            $("#username").val(user.username);
            $("#name").val(user.name);
            $("#email").val(user.email);
            $("#role_id").val(user.role_id);
            let is_active = user.is_active == 1 ? true : false;
            $("#is_active").prop('checked', is_active);
            $("#editForm").prop('action', '{{url("admin/user/edit")}}' + `/${id}`);

            modal.show();
        });
    }

    $(document).ready(function() {
        
        $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.user.index') }}',
            columns: [
                { data: 'id', name: 'id' },
                { 
                    data: 'username', 
                    name: 'username', 
                    render: function (data, type, row) {
                        return `<button 
                                class="btn btn-link" id="username_${row.id}"
                                type="button" 
                                onClick="handleEdit(${row.id})"
                                >${data}</button>`;
                    }
                },
                { data: 'name', name: 'name' },
                {data: 'email', name: 'email'},
                {data: 'role_name', name: 'role_name'},
                {data: 'phone', name: 'phone'},
                {
                    data: 'updated_at', 
                    name: 'updated_at',
                    render: function (data, type, row) {
                        return `<span>${moment(data).format('DD/MM/YYYY h:mm:ss a')}</span>`;
                    }
                },
                {data: "updated_by_name", name: "updated_by_name"}
                // Thêm các cột khác tùy ý
            ]
        });
    })

</script>
@endpush