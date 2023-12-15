@extends('site::layouts.layout')

@php
$customs = [
    "slidesToShow" => 1,
    "infinite"=> false,
];    
@endphp

@push('css')
    <style>
        .main{
            background: white;
        }
    </style>
@endpush

@section('content')

{{-- <section class="product-breadcrumb">
    <x-container>
        <x-site::breadcrumb />
    </x-container>
</section> --}}

<section>
    <div class="container">
        <div class="product-title" class="py-3">
            <h3>Kết quả tìm kiếm {{request()->search}}</h3>
        </div>   
    </div>
</section>

<section class="product-main py-2">
    <div class="container">
        <div class="box box--white">
            <div class="row">
                @foreach ($products as $product)
                <div class="col-xl-3 col-md-6 col-12 my-3">
                    <x-site::inc.product_item :product="$product" />
                </div>
                @endforeach  
            </div>

            {{$products->links()}}
        </div>
    </div>
</section>

@endsection

{{-- @dd(321) --}}