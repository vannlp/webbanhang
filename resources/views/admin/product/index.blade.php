@extends('layouts.admin')

@section('content')
    <div class="p-2">
        <h3 class="mb-3">Quản lý sản phẩm</h3>

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
                @if (Auth::checkPermission('CREATE_PRODUCT'))
                <a class="btn btn-primary btn-icon-split" href="{{route('admin.product.create')}}">
                    <span class="icon text-white-50">
                        <i class="bi bi-plus-lg"></i>
                    </span>
                    <span class="text">Thêm mới</span>
                </a>
                @endif    
            </div>
        </div>

        <div class="card shadow mb-4">
            <a href="#list_user" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="list_user">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
            </a>
            <div class="collapse show" id="list_user">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr style="white-space: nowrap">
                                    <th>id</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Danh mục sản phẩm</th>
                                    <th>Ảnh đại diện</th>
                                    <th>Giá</th>
                                    <th>Kích hoạt</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
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
$(document).ready(function() {
    $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.product.index') }}',

        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'code', 
                name: 'code',
                render: function(data, type, row) {
                    let html = `<a   href="{{url('admin/product/edit')}}/${row.id}">${data}</a>`;
                    
                    return html;
                },
            },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'category_name', name: 'category_name' },
            { data: 'avatar_link', name: 'avatar_link', render(data, type, row) {
                let html  = `<img src="${data}" class="img-thumbnail" width="50px" />`;

                return html;
            } },

            { data: 'price', name: 'price', render(data, type, row) {
                let html  = `${data.toLocaleString()} <span class="font-weight-bold">VND</span>`;

                return html;
            } },
            { data: 'is_active', name: 'is_active', render: function(data, type, row) {
                let html = '';
                if(data == 1) {
                    html = `<span class="text-success">Đang kích hoạt</span>`;
                }else{
                    html = `<span class="text-danger">Dừng kích hoạt</span>`;
                }
                return html;
            },  },
            { title: "action", render(data, type, row) {
                // checkPermison
                let editBtn = `<a href="{{url('admin/product/edit')}}/${row.id}" class="btn btn-warning">Edit</a>`;
                let deleteBtn =  `<button class="btn btn-danger" type="submit" form="delete_form_${row.id}">Xóa</button>`;
                let btn = '';
                if({{Auth::checkPermission('EDIT_CATEGORY_POST')}}) {
                    btn += editBtn;
                }
                if({{Auth::checkPermission('DELETE_CATEGORY_POST')}}) {
                    btn += deleteBtn;
                }

                let html = `
                    <form method="post" onsubmit="return confirm('Bạn có thật sự muốn xóa')" id="delete_form_${row.id}" action="{{url('/admin/product/delete')}}/${row.id}">
                        @method("DELETE")
                        @csrf    
                    </form>
                    <div class="custom_spacing">${btn}</div>
                `;

                return html;
            }, }
        ]
    })
    
})
</script>
@endpush