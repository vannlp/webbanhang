@extends('layouts.admin')

@section('content')
    <div class="p-2" x-data="create_product">
        <h3 class="mb-3">Chỉnh sửa sản phẩm</h3>

        <div class="card mb-4">
            <div class="card-header">
                Hành động
            </div>
            <div class="card-body">
                <x-message />
            </div>
        </div>

        <form action="{{route('admin.product.update', ['id' => $product->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="card shadow mb-4">
                <a href="#product_info" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="product_info">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                </a>
                <div class="collapse show" id="product_info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="code">Mã sản phẩm</label>
                                <input type="text" class="form-control" value="{{$product->code}}" readonly name="code" id="code" placeholder="Mã code"> 
                            </div>
                            <div class="col-6 form-group">
                                <label for="slug">Đường dẫn</label>
                                <input type="text" class="form-control" value="{{$product->slug}}" name="slug" id="slug" placeholder="Slug"> 
                            </div>
                            <div class="col-12 form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" class="form-control" value="{{$product->name}}" name="name" id="name" placeholder="Tên sản phẩm"> 
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

                            <div class="col-6 d-flex justify-content-center flex-column">
                                <div class="custom-control custom-switch">
                                    <input {{$product->is_active == 1 ? "checked": ''}} type="checkbox" class="custom-control-input" name="is_active" id="is_active">
                                    <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                </div>
                            </div>

                            <div class="col-6 form-group">
                                <div class="d-flex flex-column">
                                    <label for="">Ảnh đại diện</label>
                                    <div class="row m-0"  >
                                        @if ($product->avatar_id)
                                        <img src="{{asset($product->avatar->url)}}" x-ref="img_avatar" class="img-thumbnail" width='100' alt="{{asset($product->avatar->alt)}}" style="cursor: pointer;" 
                                        @click="handleClickImg"
                                        >
                                        @else
                                        <img src="https://e7.pngegg.com/pngimages/480/73/png-clipart-white-paper-illustration-post-it-note-paper-post-it-note-angle-white-thumbnail.png" x-ref="img_avatar" class="img-thumbnail" width='100' alt="" style="cursor: pointer;" 
                                        @click="handleClickImg"
                                        >
                                        @endif
                                        
                                    </div>
                                    <input type="file" style="display:none;"  x-ref="avatar_file" class="form-control" id="avatar_link" name="avatar_link" @change="onChangeImg" />
                                </div> 
                            </div>

                            <div class="col-6 form-group">
                                <label for="slug">Bộ sưu tập ảnh</label>
                                <div class="row m-0"  >
                                    <template x-for="photo in photo_collection">
                                        <div class="col-2">
                                            <img :src="photo" class="img-thumbnail" width='100' alt="" style="cursor: pointer;" 
                                            >
                                        </div>
                                    </template>
                                </div>
                                <button class="btn btn-primary btn-sm mt-2" type="button" @click="handleClickPhotoCollection">Chọn</button>
                                <input type="file" style="display: none;"  x-ref="input_photo_collection" class="form-control" id="photo_collection" multiple="multiple" name="photo_collection[]" @change="handleOnChangePhotoCollection" />
                            </div>
                            <div class="col-6 form-group">
                                <label for="price">Giá</label>
                                <input type="number" value="{{$product->price}}" class="form-control" id="price" name="price">
                            </div>
                            <div class="col-6 form-group">
                                <label for="price_down">Giá giảm</label>
                                <input type="number" value="{{$product->price_down}}" class="form-control" id="price_down" name="price_down">
                            </div>

                            <div class="col-12 form-group">
                                <label for="short_description">Mô tả ngắn</label>
                                <textarea name="short_description" placeholder="mô tả ngắn" class="form-control" id="short_description" cols="30" rows="10">{{$product->short_description}}</textarea>
                            </div>

                            <div class="col-12 form-group">
                                <label for="">Mô tả</label>
                                <textarea id="editer" name="description">{!!$product->description!!}</textarea>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <button class="btn btn-primary" type="submit">Chỉnh sửa</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js_head')
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script >

    Alpine.data('create_product', () => ({
        photo_collection: @json($product->photo_collection_link),
        handleClickImg() {
            let refs = this.$refs;
            let self = this;
            refs.avatar_file.click();
        },

        onChangeImg() {
            let refs = this.$refs;
            let self = this;
            const [file] = refs.avatar_file.files;
            if (file) {
                refs.img_avatar.src = URL.createObjectURL(file)
            }
        },

        handleClickPhotoCollection() {
            let refs = this.$refs;
            let self = this;
            refs.input_photo_collection.click();
        },

        handleOnChangePhotoCollection() {
            let refs = this.$refs;
            let self = this;
            const files = refs.input_photo_collection.files;
            if (files) {
                let fileSrcs = [];
                // Lặp qua từng tệp tin trong mảng files
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const url = URL.createObjectURL(file); // Tạo URL cho từng tệp tin

                    // Lưu URL vào mảng fileSrcs nếu bạn cần
                    fileSrcs.push(url);
                }

                this.photo_collection = fileSrcs;
            }
        },

    }))
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="/js/ckfinder/ckfinder.js"></script>
<script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>
@include('ckfinder::setup')

<script>
$(document).ready(function () {
    let category_ids = $('#category_ids').select2({
        placeholder: "Chọn danh mục",
        width: "100%",
        selectionCssClass: "from-control"
    });

    category_ids.val(@json($product->category_ids)).trigger("change");

    const editor = CKEDITOR.replace( 'editer', {
        height: 700
    } );
    CKFinder.setupCKEditor( editor );

    
});
</script>
@endpush