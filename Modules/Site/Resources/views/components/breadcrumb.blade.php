<nav >
    <ol class="breadcrumb list-none flex flex-row ">
        <li class="breadcrumb-item">
            <a href="#" class="inline-block p-2 text-blue-600 font-semibold">Điện thoại</a>
        </li>
    </ol>
</nav>
@push('css')
    <style>
        .breadcrumb-item:not(:first-child)::before{
            content: "/";
            color: #9e9e9e ;
            line-height: 1;
        }
    </style>
@endpush