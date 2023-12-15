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
                {{-- <div class="mb-2 p-1">
                    <button data-toggle="modal" type="button" data-target="#addModal" class="btn btn-primary">Thêm mới</button>
                </div>   --}}
            </div>
        </div>

        <div class="card mb-4 p-2">
            <form action="{{route('admin.role.handleEdit', ['id' => $role->id])}}" method="post">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="form-group col-12">
                        <label for="">Tên</label>
                        <input class="form-control" name="name" value="{{$role->name}}" type="text" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <label for="">Code</label>
                        <input class="form-control" name="code" value="{{$role->code}}"  type="text" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 mt-2">
                        <role-permission-handle></role-permission-handle>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <button class="btn btn-primary">Edit</button>
                    </div>
                </div>
                

            </form>
        </div>   
    </div>

    
@endsection

@push('scripts')
<script type="text/x-template" id="role-permission-handle">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Quyền</h5>
            <div class="mt-2">                
                <div class="mb-2"  v-for="groupPermission in groupPermissions" :key="groupPermission.id">
                    <div class="form-check form-check-inline">
                        <input @change="(event) => handleChecked(event, groupPermission.code)" class="form-check-input" type="checkbox" :checked="groupPermission.checked">
                        <label class="form-check-label" for="inlineCheckbox1">@{{groupPermission.name}}</label>
                    </div>
                    <div class="ml-2" >
                        <div class="form-check form-check-inline" v-for="permission in groupPermission.permissions" :key="permission.id">
                            <input class="form-check-input" :class="`checkbox_${groupPermission.code}`" name="permission_ids[]" type="checkbox"  :value="permission.id" :checked="permission.checkPermission">
                            <label class="form-check-label" >@{{permission.name}}</label>
                        </div>
                    </div>
                </div>

                <div class="form-check form-check-block" v-for="permission in permissions_not_groups" :key="permission.id">
                    <input class="form-check-input"  name="permission_ids[]" type="checkbox"  :value="permission.id" :checked="permission.checkPermission">
                    <label class="form-check-label" >@{{permission.name}}</label>
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    window.app.component('role-permission-handle', {
        template: '#role-permission-handle',
        data() {
            return {
                groupPermissions: @json($group_permissions),
                permissions_not_groups: @json($permissions_not_groups)
            }
        },
        methods: {
            handleChecked: function (event, code) {
                let checked = event.target.checked;
                let className = `checkbox_${code}`;
                let checkboxInput = $(`input.${className}[type="checkbox"]`);
                // console.log(className, checkboxInput);
                if(checked) {
                    checkboxInput.prop('checked' ,true);
                }else{ 
                    checkboxInput.prop('checked' ,false);
                }
                // checkboxInput.forEach(element => {
                //     element.setAttribute("checked", true);
                // });
            }
        },
    });
</script>
@endpush