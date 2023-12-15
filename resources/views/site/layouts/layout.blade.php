<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="jwt_token" content="{{ session('jwt_token') }}">
    <meta name="url" content="{{ url('/') }}">

    {{-- <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" /> --}}
    <script defer src="{{mix('js/app.js')}}"></script>
    
    {{-- <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap"
    rel="stylesheet" />
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />   
    <link href="{{asset('css/slick-carousel1.8.1_slick_slick.min.css')}}" rel="stylesheet">
    <link rel="stylesheet"  href="{{mix('css/app.css')}}">
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('js/slick-carousel_slick_slick.min.js')}}"></script>

    <script>
        let custom = {
            prevArrow:  `<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i>  </button>`,
            nextArrow:  `<button type="button" class="slick-next"> <i class="fa-solid fa-chevron-right"></i> </button>`,
        };


        $(".slider-banner").slick({
            slidesToShow: 2,
            responsive: [
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 1,
                        infinite: true,
                        dots: true
                    }
                }
            ],
            ...custom
        });

        $(".slick-slide-product").slick({
            slidesToShow: 5,
            responsive: [
                {
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 3,
                        infinite: true,
                        // dots: true
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 2,
                        infinite: true,
                        // dots: true
                    }
                }
            ],
            ...custom
        });
    </script>
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
    @include('site.layouts.header')
    <main class="main" id="content">
        @yield('content')
    </main>

    <footer class="footer">

    </footer>
</body>
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

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
    
    
</script>

</html>
