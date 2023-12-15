<section class="header-main">
    <div class="container">
        <div class="row align-items-center header-main-row">
            <div class="col-3 header-mobile">
                <div class="header-mobile-menu" id="header_mobile_open">
                    <i class="fa-solid fa-bars"></i>
                </div>  
            </div>

            <div class="col-xl-2 col-md-2 col-4">
                <a class="header-logos" href="/">
                    <img src="https://theme.hstatic.net/1000288298/1001020793/14/logo.png?v=244" alt="">
                </a>
                
            </div>
     

            <div class="col-xl-7 col-7 header-main-center">
                <x-site::inc.search_form />
            </div>

            <div class="col-xl-3 col-3 header-left">
                <div class="header-account header-wp">
                    @if (auth()->check())
                    <div class="header-icon" style="cursor: pointer;">
                        {{-- <i class="fa-regular fa-user"></i> --}}
                        @if (auth()->user()->avatar)
                        <img src="{{asset(auth()->user()->avatar->url)}}" class="rounded-circle shadow-4" style="width: 40px;height: 40px; object-fit: cover" alt="Avatar" />
                        @else
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle shadow-4" style="width: 40px;" alt="Avatar" />
                        @endif
                    </div>
                    <div class="header-content">
                        <a href="#" class="link-underline">{{auth()->user()->name}}</a>
                    </div>
                    @else
                    <div class="header-icon">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="header-content">
                        <div class="header-login">
                            <a href="#">Đăng nhập</a>
                            <span>/</span>
                            <a href="#">Đăng ký</a>
                        </div>
                        <div class="header-tk">
                            <span>Tài khoản của tôi</span>
                        </div>
                    </div>
                    @endif
                </div>
                <a class="header-cart header-wp" href="{{route('cartPage')}}">
                    <div class="header-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="header-content">
                        <span>Giỏ hàng</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="header-mobile mt-3">
        <div class="container">
            <x-site::inc.search_form />
        </div>
    </div>
</section>