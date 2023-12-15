<header id="header" class="header  bg-[#E2E2E2] z-50 relative">
    {{-- <x-container>
        <section class="header-top"></section>
        <section class="header-main">
            <h3>Haha</h3>
        </section>
        <section class="header-bottom"></section>
    </x-container> --}}
    <section class="header-top bg-[#00000A] py-2 hidden md:block">
        <x-container>
            <p class="text-lime-50">
                Hotline: <span class="font-semibold">0968 239 497 - 097 221 6881</span> * Tư vấn build PC: <span class="font-semibold">0986552233</span> * Địa chỉ: <span class="font-semibold">180D Thái Thịnh - Đống Đa - HN</span>
            </p>
        </x-container>
    </section>
    
    {{-- header main --}}
    <x-site.header_main></x-site.header_main>

    {{-- header bottom --}}
    <x-site.header_bottom></x-site.header_bottom>
</header>
<div id="site_overlay" x-ref="site_overlay" class="site-overlay fixed top-0 left-0 w-[100%] h-[100vh] bg-black/25 z-10 transition duration-200 ease-linear" style="display: none" @click="handleClickSiteOverlay"></div>



