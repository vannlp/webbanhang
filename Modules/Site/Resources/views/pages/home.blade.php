@extends('site::layouts.layout')

@php

@endphp
@push('css')
    <style>
      .slider_img{
        background-image: url("https://img.tgdd.vn/imgt/f_webp,fit_outside,quality_100/https://cdn.tgdd.vn/2023/08/campaign/Frame-1-1200x120-1.png");
        background-position: center;
        background-repeat: no-repeat;
        padding: 30px 5px;
      }
    </style>
@endpush

@section('content')
<section class="slider py-3">
  <div class="container">
    <x-site::inc.slider_banner >
      <div>
        <div class="rounded overflow-hidden">
          <img  src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:80/plain/https://dashboard.cellphones.com.vn/storage/sliding-ip15-th10.png" alt="">
        </div>
      </div>
      <div>
        <div class="rounded overflow-hidden">
          <img  src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:80/plain/https://dashboard.cellphones.com.vn/storage/s23-ultra-th10-sli.png" alt="">
        </div>
      </div>
      <div>
        <div class="rounded overflow-hidden">
          <img  src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:80/plain/https://dashboard.cellphones.com.vn/storage/sliding-ip15-th10.png" alt="">
        </div>
      </div>
      <div>
        <div class="rounded overflow-hidden">
          <img  src="https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:80/plain/https://dashboard.cellphones.com.vn/storage/s23-ultra-th10-sli.png" alt="">
        </div>
      </div>

      
    </x-site::inc.slider_banner>
  </div>
</section>

<section class="py-3">
  <div class="container">
    <x-site::inc.slider_product
      backGroupImage="https://img.tgdd.vn/imgt/f_webp,fit_outside,quality_100/https://cdn.tgdd.vn/mwgcart/mwgcore/ContentMwg/images/homev2/fs-0210-1010-theme/desk/tgdd/banner-title.png"
      :products="$newProducts"
      backGroupContent="#FF9F16"
    />
  </div>
</section>

<section>
  <div class="container">
    {!! view_render_event('test_view_render') !!}

    {!! Blade::render($test) !!}

  </div>
</section>


@endsection