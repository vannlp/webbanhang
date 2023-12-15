@extends('layouts.admin')

@section('content')
    <div class="p-2">
        
        <h3 class="mb-3">Danh mục 
            @if ($type == 'post')
            bài viết
            @elseif($type == 'product')
            sản phẩm
            @endif
        </h3>
         
        <div class="card mb-4 p-2">
            <div class="row">
                <div class="col-12">
                    <button 
                        class="btn btn-primary"
                        type="button"
                        data-toggle="modal"
                        data-target="#addModal"
                    >Thêm mới</button>
                </div>
            </div>
        </div>

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

        <div class="card mb-3 p-2">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Thuộc danh mục</th>
                            <th>Kích hoạt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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
                        <form action="{{route('admin.'.$type.'.category.create')}}" method="post" id="formUpload">
                            @csrf
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="">Code</label>
                                    <input type="text" class="form-control" placeholder="Mã code" name="code">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" placeholder="Tên danh mục" name="name">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Slug</label>
                                    <input type="text" class="form-control" placeholder="slug" name="slug">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Parent</label>
                                    <select name="parent_id" class="form-control" id="">
                                        <option value="">-- Không --</option>
                                        @foreach ($categories_parent as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>    
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 form-group">
                                    <label for="">Mô tả</label>
                                    <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="formUpload" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- EditModal --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Chỉnh sửa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="editForm">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="">Code</label>
                                    <input type="text" readonly id="code" class="form-control" placeholder="Mã code" name="code">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Tên danh mục" name="name">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Slug</label>
                                    <input type="text" class="form-control" id="slug" placeholder="slug" name="slug">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Parent</label>
                                    <select name="parent_id" class="form-control" id="parent_id">
                                        
                                    </select>
                                </div>

                                <div class="col-12 form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                             name="is_active" 
                                             {{-- value="true" --}}
                                             {{-- checked="true" --}}
                                             class="custom-control-input" id="is_active">
                                        <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>

                                <div class="col-12 form-group">
                                    <label for="">Mô tả</label>
                                    <textarea name="description" class="form-control" id="description" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="editForm" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('css')
    <style>
        .custom_spacing>button {
            margin: 0 5px;
        }
    </style>
@endpush

@push("js_head")
<script>
    function handleEdit(id) {
        const editModel = document.getElementById('editModal');
        const modal = new bootstrap.Modal(editModel);
        window.apis.get(`{{url('admin/'.$type.'/category/edit')}}/${id}`).then(res => res.data)
            .then(data => {
                let category = data.category;
                let categories_parent = data.categories_parent;

                // xử lý dữ liệu gán vào form
                $("#code").val(category.code);
                $("#name").val(category.name);
                $("#slug").val(category.slug);
                let html = '';
                html += '<option value="">-- Không --</option>';
                categories_parent.forEach(element => {
                    html += `<option value="${element.id}">${element.name}</option>`;
                });
                $("#parent_id").html(html);
                $("#parent_id").val(category.parent_id ?? '');
                $("#description").html(category.description);
                $("#editForm").prop('action', `{{url('admin/'.$type.'/category/update')}}/${id}`)
                let is_active = category.is_active == 1 ? true : false;
                $("#is_active").prop('checked', is_active);
                modal.show();
            }).catch(function(error) {
                    console.log(error);
                    alert("Có lỗi xảy ra")
            });
    }

    function handleDelete() {
        return confirm("Bạn có thực sự muốn xóa");
    }
</script>
@endpush

@push('scripts')
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $(document).ready(function() {
        
        $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.'.$type.'.category') }}',
            columns: [
                { data: 'id', name: 'id' },
                { 
                    data: 'code', 
                    name: 'code'
                },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'parent_name', name: 'parent_name' },
                { 
                    data: 'is_active', 
                    name: 'is_active',  
                    render: function(data, type, row) {
                        let html = '';
                        if(data == 1) {
                            html = `<span class="text-success">Đang kích hoạt</span>`;
                        }else{
                            html = `<span class="text-danger">Dừng kích hoạt</span>`;
                        }
                        return html;
                    },
                },
                { 
                    // data: "id", 
                    title: "action" ,
                    render: function(data, type, row) {
                        // checkPermison
                        let editBtn = `<button class="btn btn-warning" onclick="handleEdit(${row.id})">Edit</button>`;
                        let deleteBtn =  `<button class="btn btn-danger" type="submit" form="delete_form_${row.id}">Xóa</button>`;
                        let btn = '';
                        if({{Auth::checkPermission('EDIT_CATEGORY_POST')}}) {
                            btn += editBtn;
                        }
                        if({{Auth::checkPermission('DELETE_CATEGORY_POST')}}) {
                            btn += deleteBtn;
                        }

                        let html = `
                            <form method="post" onsubmit="return confirm('Bạn có thật sự muốn xóa')" id="delete_form_${row.id}" action="{{url('/admin/post/category/delete')}}/${row.id}">
                                @method("DELETE")
                                @csrf    
                            </form>
                            <div class="custom_spacing">${btn}</div>
                        `;

                        return html;
                    }
                }
                // Thêm các cột khác tùy ý
            ]
        });

        
    })
</script>
@endpush
