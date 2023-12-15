
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? "Web baán hàng"}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="jwt_token" content="{{ session('jwt_token') }}">
    <meta name="url" content="{{ url('/') }}">

    {{-- <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" /> --}}
    <script defer src="{{mix('js/app.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    {{-- <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap"
    rel="stylesheet" />
    {{-- <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />    --}}
    <link href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css" rel="stylesheet">
    <link rel="stylesheet"  href="{{mix('css/app.css')}}">
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('js/slick-carousel_slick_slick.min.js')}}"></script>


    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }
        .main{
            background-color: #e8e8e9;
        }

        
    </style>

    
    
    @livewireStyles

    @stack('css')
    @stack("js_head")
    @yield('js_header')
    
</head>
<body x-data='body'>
    @include('site::layouts.header')
    <main class="main" id="content">
        @yield('content')
    </main>

    <footer class="footer">

    </footer>
    <div class="purdah" id='purdahLoading'>
        <x-site::loading />
    </div>
    <x-site::toast id="LiveToast" />
    <div class="loading_wrapper" id="loadingIPS">

    </div>
    
</body>
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script src="{{mix('js/library.js')}}"></script>



<script>
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var loadingIPS = $("#loadingIPS").loadding();

</script>
{{-- <script>
    var myToastEl = document.getElementById('myToastEl')
    var myToast = bootstrap.Toast.getInstance(myToastEl) // Returns a Bootstrap toast instance
</script> --}}

@stack('setupAfter')
@stack('scripts')

@livewireScripts
<script >
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('body', () => ({
            handleClickVerticalMenu: function() {
                let site_overlay = this.$refs.site_overlay;
                let virtical_menu = this.$refs.virtical_menu;
                if(virtical_menu.style.display == 'none') {
                    site_overlay.style.display = "block";
                    virtical_menu.style.display = "block";
                }else{
                    site_overlay.style.display = "none";
                    virtical_menu.style.display = "none";
                }     
            },

            handleClickSiteOverlay() {
                let site_overlay = this.$refs.site_overlay;
                let virtical_menu = this.$refs.virtical_menu;
                if(site_overlay.style.display == 'block') {
                    site_overlay.style.display = "none";
                    virtical_menu.style.display = "none";
                }
            }
        }))

    })
    $(document).ready(function () {
        $('#purdahLoading').css('display', 'none');
    })
    
</script>

</html>
