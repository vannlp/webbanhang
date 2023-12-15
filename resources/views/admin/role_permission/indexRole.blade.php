@extends('layouts.admin')

@section('content')
    <div class="p-2">
        <h3 class="mb-3">Quản lý role</h3>

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
                    <button data-toggle="modal" type="button" data-target="#addModal" class="btn btn-primary">Thêm mới</button>
                </div>  
            </div>
        </div>

        <div class="card mb-4 p-2">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                      <th scope="col">#</th>
                      <th>Name</th>
                      <th>Code</th>
                      <th>Kích hoạt</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)    
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>
                            <a href="{{route('admin.role.edit', ['id' => $role->id])}}">{{$role->name}}</a>
                        </td>
                        <td>{{$role->code}}</td>
                        <td>
                            @if ($role->is_active)
                                <span class="text-success">Đang kích hoạt</span>
                            @else
                                <span class="text-danger">Dừng kích hoạt</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$roles->links()}}
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
                        <form action="{{route('admin.role.create')}}" id="addForm" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="">Tên role</label>
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
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                             name="is_active"
                                             class="custom-control-input" id="is_active">
                                        <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                    </div>
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
    </div>
@endsection

@push('scripts')

@endpush