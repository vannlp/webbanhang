@php
$productCategory = core()->getCategoryProduct();    
@endphp

@props([
    'isMobile' => false,
    'openButtonId' => false
])

@if ($isMobile)
<div class="category-mobile" id="category_mobile">
    <button class="category-mobile-close" id="category_close">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <ul class="category-mobile-list mt-5" >
      @foreach ($productCategory as $category)
      <li class="category-mobile-item">
        <a href="#" class="category-mobile-link">{{$category->name}}</a>
      </li>
      @endforeach
    </ul>
</div>

@if ($openButtonId)
@push('scripts')
<script>
    const buttonOpen = $("#{{$openButtonId}}");
    const category_mobile = $("#category_mobile");
    const category_close = $("#category_close");
    buttonOpen.click(() => {
        category_mobile.addClass('open');
    });
    category_close.click(() => {
        category_mobile.removeClass('open');
    })
</script>
@endpush
@endif

@else
<div class="category">
    <div class="category-header" @click="handleClickVerticalMenu()">
        <div class="category-icon">
            <i class="fa-solid fa-list"></i>
        </div>
        <div class="category-text">
            Danh mục sản phẩm
        </div>
    </div>

    <ul class="category-submenu" x-ref='virtical_menu' style="display: none;">
        @foreach ($productCategory as $category)
        <li class="category-submenu-item">
            <a href="{{route('categoryPage', ['slug' => $category->slug])}}" class="category-submenu-link">{{$category->name}}</a>

            @if ($category->children)
            <ul class="category-submenu-list">
                @foreach ($category->children as $categoryChildren)
                <li class="category-submenu-item">
                    <a href="{{route('categoryPage', ['slug' => $categoryChildren->slug])}}" class="category-submenu-link">{{$categoryChildren->name}}</a>
                </li>
                @endforeach
            </ul> 
            @endif
        </li>
        @endforeach
    </ul>
</div>  
@endif



