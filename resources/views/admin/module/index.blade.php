@extends('layouts.admin')

@section('content')
    <div class="p-2">
        <h3 class="mb-3">Quản lý module</h3>

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
            </div>
        </div>

        <div class="card shadow mb-4">
            <a href="#list_user" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="list_user">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách</h6>
            </a>
            <div class="collapse show" id="list_user">
                <div class="card-body">
                    <div class="table-responsive">
                        <button type="submit" form="form_update_status" class="btn btn-primary">Xác nhận</button>
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr style="white-space: nowrap">
                                    <th>id</th>
                                    <th>Name</th>
                                    <th>Kích hoạt</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="{{route('admin.module.update')}}" method="post" id="form_update_status">
                                    @csrf
                                    @method("PUT")
                                </form>
                                @foreach ($modules as $key => $module)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$module->getName()}}</td>
                                        <td>
                                            @if ($module->isEnabled())
                                                <span class="text-success">Đang kích hoạt</span>
                                            @else
                                                <span class="text-danger">Chưa kích hoạt</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" form="form_update_status" {{$module->isEnabled() ? 'checked': ''}} name="status[]" value="{{$module->getName()}}" id="checkbox_{{$key}}" />
                                                <label class="custom-control-label" for="checkbox_{{$key}}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
    
})
</script>
@endpush