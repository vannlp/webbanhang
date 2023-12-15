<a class="card card-product" href="{{route('detailProduct', ['slug' => $product->slug])}}">
    <div class="card-header">
        <img src="{{asset($product->avatar->url)}}" class="card-img-top" alt="...">
    </div>
    <div class="card-body">
      <h5 class="card-title" title="{{$product->name}}">{{$product->name}}</h5>
      <strong class="card-price">
        @if (!empty($product->price_down))
        <span class="card-price--old me-2">{{number_format($product->price)}} ₫</span>
        <span>{{number_format($product->price_down)}} ₫</span>           
        @else
        {{number_format($product->price)}} ₫
        @endif
      </strong>
    </div>
</a>