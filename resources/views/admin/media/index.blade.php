@extends('layouts.admin')

@section('content')
    <div class="p-2">
        
        <h3 class="mb-3">Quản lý media (Hình ảnh, video)</h3>

           
        <div class="card mb-4 p-2">
            <form action="" method="get">
                <div class="row">
                    <div class="col-6">
                        <select name="type" class="form-control-sm mr-1" id="">
                            <option value="">Tất cả</option>
                            <option value="image">Hình ảnh</option>
                        </select>
                        <select name="month" class="form-control-sm mr-1" id="">
                            <option value="">Tất cả các ngày</option>
                            @foreach ($monthsWithFile as $month)
                                <option value="{{$month->month}}">{{$month->month}}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-primary btn-sm">Tìm kiếm</button>
                    </div>
                
                </div> 
            </form>
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
        
        <div class="card mb-4 p-2">
            <img-event ></img-event>
        </div>

        {{-- Modal --}}
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Hình ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="p-1" style="cursor: pointer;">
                                    <img src="https://images.unsplash.com/photo-1688920556232-321bd176d0b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" class="img-fluid" alt="" id="img">
                                </div>
                            </div>
                            <div class="col-7">
                                <form action="" method="post" id="formUpload">
                                    @method("PUT")
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="">Alt</label>
                                            <input type="text" id="alt" placeholder="alt" name="alt" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="">Link</label>
                                            <input type="text" readonly id="url" name="url" value="" class="form-control">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" form="formUpload" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </div>
        </div>


    </div>
@endsection

@push('scripts')

<script type="text/x-template" id="img-event">
    <div class="row">    
        <div class="col-2" v-for="file in files">
            <div class="p-1" style="cursor: pointer;" @click="handleEvent(file.id)">
                <img :src="file.url" class="img-fluid" :alt="file.alt">
            </div>
        </div>
    </div>
</script>

<script>
    window.app.component('img-event', {
        template: '#img-event',
        data() {
            return {
                files: @json($files),
                baseUrl: "{{url('/')}}"
            }
        },
        methods: {
            handleEvent: function (id) {
                const myModal = document.getElementById('editModal');
                const modal = new bootstrap.Modal(myModal);
                let url = "{{url('admin/media/get-one')}}";
                window.apis.get(`${url}/${id}`).then(function(res) {
                    return res.data
                })
                .then((data) => {
                    let file = data.file;
                    $("#img").prop('src', `${this.baseUrl}/${file.url}`);
                    $("#alt").val(file.alt);
                    $("#url").val(this.baseUrl + `/${file.url}`);
                    $("#formUpload").prop('action', '{{url("admin/media/update")}}' + `/${id}`);
                    modal.show();
                })
                .catch(function(error) {
                    console.log(error);
                    alert("Có lỗi xảy ra")
                });
            }
        },
    });
</script>
<script>
    // function handleEditModal(id) {
        
        
    //     // $("#alt").val()
    //     // modal.show();
    // }
</script>
@endpush