@props([
    'backGroupImage' => null,
    'products' => [],
    'backGroupContent' => null
])

<div class="slider-product">
    <div class="slider-product-header position-relative">
      @if ($backGroupImage)
        <img
        src="{{$backGroupImage}}"
        class="img-fluid " alt="" 
        />
      @endif
    </div>

    <div class="slider-product-content"
        style="background: {{$backGroupContent ?? "#fff"}};"
    >
      <div class="slick-slide-product">
        @foreach ($products as $product)
        <div class="">
          <x-site::inc.product_item :product="$product" />
        </div>
        @endforeach  
      </div>
    </div>
</div>