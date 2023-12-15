@extends('site.layouts.layout')

@php
$sliders = [
  [
    'id' => 1,
    'title' => null,
    'description' => null,
    'link_img' => 'https://tecdn.b-cdn.net/img/Photos/Slides/img%20(15).jpg',
    'alt' => "HHHHH"
  ],
  [
    'id' => 2,
    'title' => 'Tiêu đề 1',
    'description' => "Mô tả 1",
    'link_img' => 'https://tecdn.b-cdn.net/img/Photos/Slides/img%20(22).jpg',
    'alt' => "HHHHH"
  ],
];
$customs = [
  // 'dots' => true,
  "infinite"=> false,
  "slidesToShow"=> 5,
  // "lazyLoad" => 'ondemand',
];

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
    <x-container>
        <x-site.slider :sliders="$sliders"></x-site.slider>
    </x-container>
</section>

<section class="my-3">
  <x-container className="relative">
    <div class="bg-[#fff] p-3">
      <div class="flex flex-row justify-between z-10">
        {{-- <a href="#">Xem thêm</a> --}}
        <h4 class="text-[#333] font-semibold text-[20px] ml-5">Sản phẩm mới</h4>
      </div>
      <x-site.slider2 id="slide1" :customs="$customs">
        @foreach ($newProducts as $newProduct)
        <x-site.product_item className="my-2" :product="$newProduct"   />
        @endforeach
      </x-site.slider>
    </div>
  </x-container>
</section>

<section class="my-3">
  <x-container>
      <div class="grid grid-cols-5"> 
        {{-- <x-site.product_item /> --}}
      </div>
  </x-container>
</section>

@endsection

{{-- @dd(321) --}}