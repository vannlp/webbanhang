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
            <form action="{{route('admin.post.handleCreate')}}" enctype="multipart/form-data" method="post">
                @csrf()
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" readonly value="{{$ramdomCode}}">
                    </div>
                    <div class="col-4 form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Tên bài post" name="name" >
                    </div>
                    <div class="col-4 form-group">
                        <label for="Slug">Slug</label>
                        <input type="text" id="Slug" class="form-control" placeholder="Slug" name="slug" >
                    </div>
                    <div class="col-6 form-group ">

                        {{-- <example-component :name='{
                            getInitData: "{{route('admin.media.index')}}"
                        }'></example-component> --}}
                        <label for="">Chọn ảnh đại diện</label>
                        <div class="d-flex align-items-center">
                            <input type="text" readonly class="form-control" id="file-url" name="avatar_link" />
                            <button class="btn btn-primary ml-3" type="button" id="file_select">Chọn</button>
                        </div>
                            
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Danh mục</label>
                            <select class="select-multiple" id="category_ids" name="category_ids[]" multiple="multiple">
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" >{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_active" id="is_active">
                            <label class="custom-control-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                    <div class="col-12 form-group">
                        <label for="short_description">Mô tả ngắn</label>
                        <textarea name="short_description" placeholder="mô tả ngắn" class="form-control" id="short_description" cols="30" rows="10"></textarea>
                    </div>

                    
                    <div class="col-12 form-group">
                        <label for="">Mô tả</label>
                        <textarea id="editer" name="description"></textarea>
                        
                    </div>

                    <div class="col-12 form-group">
                        <button class="btn btn-primary" type="submit">Thêm mới</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush


@push('js_head')
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
@endpush


@push('scripts')
<!-- Page level plugins -->
{{-- <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
<script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>
@include('ckfinder::setup')

<script>
    $(document).ready(() => {
        const editor = CKEDITOR.replace( 'editer', {
            height: 700
        } );
        CKFinder.setupCKEditor( editor );

        $('#category_ids').select2({
            placeholder: "Chọn danh mục",
            width: "100%",
            selectionCssClass: "from-control"
        });

        var inputText = document.getElementById( 'file-url' );
        var buttonSelect = document.getElementById( 'file_select' );

        buttonSelect.onclick = function() {
            selectFileWithCKFinder( 'file-url' );
        };
        

        function selectFileWithCKFinder( elementId ) {
            CKFinder.modal( {
                chooseFiles: true,
                width: 800,
                height: 600,
                onInit: function( finder ) {
                    finder.on( 'files:choose', function( evt ) {
                        var file = evt.data.files.first();
                        var output = document.getElementById( elementId );
                        output.value = file.getUrl();
                    } );

                    finder.on( 'file:choose:resizedImage', function( evt ) {
                        var output = document.getElementById( elementId );
                        output.value = evt.data.resizedUrl;
                    } );
                }
            } );
        }
    })
</script>
@endpush
