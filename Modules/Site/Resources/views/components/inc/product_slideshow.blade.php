@props([
    'product'
])

@push('css')
    <style>
        .slide-btn{
            background-color: transparent;
        }
    </style>
@endpush

@php
    $photo_collections = $product->photo_collections();    
@endphp

<div class="product-slideshow">
    @foreach ($photo_collections as $photo_collection)
        <div class="product-slideshow-item">
            <img src="{{asset($photo_collection->url)}}" alt="">
        </div>
    @endforeach
</div>

<div class="a" style="display: none"></div>

