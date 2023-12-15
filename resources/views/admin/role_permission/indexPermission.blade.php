@extends('layouts.admin')

@section('content')
    <div class="p-2">
        <h3 class="mb-3">Quản lý permission</h3>

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
                <div class="mb-2 p-1">
                    <button data-toggle="modal" type="button" data-target="#addModal" class="btn btn-primary m-2">Thêm mới</button>
                    <button data-toggle="modal" type="button" data-target="#addModalGroup" class="btn btn-primary">Thêm mới Group</button>
                </div>  
            </div>
        </div>

        <div class="card mb-4 p-2">
            <div class="row mb-2">
                <div class="col-3">
                    <select id="action-select" class="form-control">
                        <option value="">Chọn hành động</option>
                        <option value="delete">Xóa</option>
                    </select>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="action-button">Chọn</button>
                    </div>
                </div>
            </div>
            <table class="table" id="dataTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Kích hoạt</th>
                    </tr>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </div>
        

        {{-- Modal --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Thêm mới quyền</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.permission.create')}}" id="addForm" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Tên quyền</label>
                                    <input type="text" class="form-control" name="name" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Code</label>
                                    <input type="text" class="form-control" name="code" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Mô tả</label>
                                    <input type="text" class="form-control" name="description" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Group</label>
                                    <select name="group_permission_id" class="form-control js-example-basic-single" id="group_permission_id" >
                                        <option value="">__Không__</option>
                                        @foreach ($group_permissions as $group_permission)
                                            <option value="{{$group_permission->id}}">{{$group_permission->name}}</option>
                                        @endforeach
                                    </select>
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

        <div class="modal fade" id="addModalGroup" tabindex="-1" aria-labelledby="addModalGroupLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalGroupLabel">Thêm mới group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.permission.group.create')}}" id="addGroupForm" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Tên</label>
                                    <input type="text" class="form-control" name="name" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Code</label>
                                    <input type="text" class="form-control" name="code" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Mô tả</label>
                                    <input type="text" class="form-control" name="description" />
                                </div>
                            </div>
                            

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="addGroupForm" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
              </div>
        </div>
    </div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $("#group_permission_id").select2({
            width: "100%"
        });
        const dataTable = $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.permission') }}',
            columns: [
                { 
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="delete-checkbox" value="${row.id}">`;
                    }
                },
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'code', name: 'code' },
                {   
                    data: 'is_active', 
                    name: 'is_active', 
                    render: function(data, type, row) {
                        if(data == 1) {
                            return `<span class="text-success">Đã kích hoạt</span>`;
                        }else{
                            return `<span class="text-danger">Chưa kích hoạt</span>`;
                        }
                    }, 
                }
                // Thêm các cột khác tùy ý
            ],
            // select: {
            //     style: 'multi', // Cho phép chọn hàng loạt
            //     selector: 'td:first-child' // Chỉ áp dụng chọn vào cột đầu tiên (checkbox)
            // },
        });

        $("#action-button").click(function() {
            let action = $("#action-select").val();
            if(action == 'delete') {
                let records = getAllIdCheckBox($('input.delete-checkbox[type="checkbox"]:checked'));
                window.apis.delete("{{route('admin.permission.delete')}}", {
                    data: {
                        records: records,
                        action_type: 'many'
                    }
                }).then(res => {
                    dataTable.ajax.reload();
                }).catch(errors => {
                    console.log(errors);
                });
            }
        })

        function getAllIdCheckBox(queryInput) {
            let selectedRecords  = [];
            queryInput.each(function() {
                selectedRecords.push($(this).val());
            })

            return selectedRecords;
        }
    })
</script>
@endpush