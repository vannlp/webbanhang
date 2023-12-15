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
            <h3>{{$product->name}}</h3>
        </div>   
    </div>
</section>

<section class="product-main py-2">
    <div class="container">
        <div class="box box--white">
            <div class="row">
                <div class="product-main-slider col-7">
                    <x-site::inc.product_slideshow :product="$product" />
                </div>

                <div class="product-main-info col-5">
                    <div class="product-main-price">
                        <strong class="product-main-price-wrapper">
                            @if (!empty($product->price_down))
                            <span class="product-price-text me-2">{{number_format($product->price_down)}} ₫</span>           
                            <span class="product-price-text product-price-text--old">{{number_format($product->price)}} ₫</span>
                            @else
                            <span class="product-price-text">{{number_format($product->price)}} ₫</span>           
                            @endif
                        </strong>
                    </div>

                    <div class="product-action">
                        <div class="row">
                            <div class="col-6">
                                <button class="product-btn product-btn--buyNow" type="button" id="payNow">Mua ngay</button>
                            </div>
                            <div class="col-6">
                                <button class="product-btn product-btn--addCart" id="addToCart">Thêm vào giỏ hàng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3 product-description">
                    <h3 class="product-description-title">Thông tin sản phẩm</h3>

                    <div class="product-description-content">
                        {!! $product->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

{{-- @dd(321) --}}
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#addToCart').click(function () {
                loadingIPS.loadding.showLoading(true);
                let product_id = {{$product->id}};
                let quantity = 1;
                let params = {
                    product_id,
                    quantity
                }

                $.post("{{route('addToCartForClient')}}", params, function(data, status) {
                    let message = data.message;
                    loadingIPS.loadding.showLoading(false);

                    if(status == 'success') {
                        toastLiveToast.showToast(message, null, 'success');
                        return;

                    }

                    if(status == 'error') {
                        toastLiveToast.showToast(message, null, 'danger');
                    }
                });
            });

            $("#payNow").click(function() {
            })
        })
    </script>
@endpush
