@extends('layouts.admin')

@section('content')
    <div>
        <h3>Hello</h3>
        
        {{-- <text-component></text-component> --}}
        <select class="selectpicker form-control" data-style="btn-outline-secondary" data-live-search="true">
            <option data-tokens="ketchup mustard">Hot Dog, Fries and a Soda</option>
            <option data-tokens="mustard">Burger, Shake and a Smile</option>
            <option data-tokens="frosting">Sugar, Spice and all things nice</option>
        </select>
          
    </div>
@endsection


@push('scripts')

@endpush