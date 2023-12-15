@extends('site.layouts.layout')

@section('content')
<section style="background-color: #eee;">
    <div class="container py-3">
        <div class="row">
            <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                </ol>
            </nav>
            </div>
        </div>
    
        <form class="row" method="post" action="{{route('profile.edit')}}" enctype='multipart/form-data'>
            @method('PUT')
            @csrf
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <input type="file" style="display:none;" id="file" name="file">
                        <img src="{{ isset($user->avatar) ? asset($user->avatar->url) : "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" }}" alt="avatar"
                            class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit:cover;" id="img">
                        <h5 class="my-3">{{ $user->name }}</h5>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" class="btn btn-primary">Follow</button>
                            <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                <div class="card-body">
                    <profile-content
                        :user='@json($user)'
                    ></profile-content>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#img").click(function () {
                $("#file").click();
            })

            $("#file").on('change', function () {
                $('#img').prop('src', window.URL.createObjectURL($("#file").prop("files")[0]));
            });
        })
    </script>
@endpush