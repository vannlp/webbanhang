@extends('layouts.admin')

@section('content')
    <div class="p-2">
        
        <h3 class="mb-3">Bài viết</h3>
         

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
                            <th>Danh mục</th>
                            <th>Kích hoạt</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('css')
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
            ajax: '{{ route('admin.post.index') }}',

            columns: [
                { data: 'id', name: 'id' },
                { 
                    data: 'code', 
                    name: 'code',
                    render: function(data, type, row) {
                        let html = `<a href="{{url('admin/post/edit')}}/${row.id}">${data}</a>`;
                        
                        return html;
                    },
                },
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'category_name', name: 'category_name' },
                { data: 'is_active', name: 'is_active', render: function(data, type, row) {
                    let html = '';
                    if(data == 1) {
                        html = `<span class="text-success">Đang kích hoạt</span>`;
                    }else{
                        html = `<span class="text-danger">Dừng kích hoạt</span>`;
                    }
                    return html;
                },  }
            ]
        })
        
    })
</script>
@endpush
