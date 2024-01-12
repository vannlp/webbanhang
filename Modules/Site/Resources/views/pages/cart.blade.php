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

<section class="cart" id="cart">
  <div class="container">
    <div class="box box--white cart-header mb-3">
      <div class="row">
        <div class="col-4">
          <span class="me-2">
            <input type="checkbox">
          </span>
          <span>Sản phẩm</span>
        </div>

        <div class="col-2">
          <span>Đơn Giá</span>
        </div>

        <div class="col-2">
          <span>Số Lượng</span>
        </div>

        <div class="col-2">
          <span>Số Tiền</span>
        </div>

        <div class="col-2">
          <span>Thao Tác</span>
        </div>
      </div>
    </div>

    <div class="box box--white cart-body mb-3">
      <form action="" method="post" id="form-cart">
        @csrf
        @method("PUT")
      </form>
      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-primary" form="form-cart" id="updateCart">Cập nhập</button>
        </div>
      </div>
      <div class="row" id="cartDetails">

        @php
          $cartDetails = $cart->cartDetails ?? null ;
        @endphp
        
        @foreach ($cartDetails as $key => $cartDetail)
          <div  class="col-12 cart-item">

            <div class="row">
              <div class="col-4 d-flex">
                <div class="d-flex align-items-center">
                  <span class="me-2">
                    <input type="checkbox">
                  </span>
                  <div >
                    <img src="{{$cartDetail->product->avatar->url}}" style="max-width: 70px; height: auto;" class="rounded  d-block" alt="">
                  </div>
                  <span>{{$cartDetail->product->name}}</span>
                </div>
              </div>
      
              <div class="col-2">
                <span class="fw-bolder">{{number_format($cartDetail->product->price)}} đ</span>
              </div>
      
              <div class="col-2">
                <input type="hidden" form="form-cart" name="cartDetail[{{$key}}][id]" value="{{$cartDetail->id}}">
                <div class="input-group quantity_group" id="quantity_group_{{$cartDetail->id}}">
                  <button style="border-color: #dee2e6;" class="btn input-group-text btn-sm btn-outline quantity_down btn-outline-secondary" id="quantity_down">-</button>
                  <input name="cartDetail[{{$key}}][quantity]" form="form-cart" type="number" style="max-width: 50px" min="1" max="10" class="form-control form-control-sm quantity_number" value="{{number_format($cartDetail->quantity)}}" id="quantity_number">
                  <button style="border-color: #dee2e6;" class="btn input-group-text btn-sm btn-outline quantity_up btn-outline-secondary" id="quantity_up">+</button> 
                </div>
              </div>
      
              <div class="col-2">
                <span class="text-danger fw-bolder">{{number_format($cartDetail->handled_price)}} đ</span>
              </div>
      
              <div class="col-2">
                <form action="{{route('removeCartDetail', ['idCartDetail' => $cartDetail->id])}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button class="btn" type="submit">Xóa</button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        Thanh toán
      </div>
      <div class="card-body">
        <div>
          Tổng thanh toán: <span class="total-price text-decoration-line-through fw-bolder">{{number_format($cart->price)}} đ</span> <span class="text-danger fw-bolder total-price-handler">{{number_format($cart->handled_price)}} đ</span>
        </div>

        <div class="mt-3">
          <button class="btn btn-success">Thanh toán</button>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
  <script>
    $(document).ready(function () {

      $.fn.cart = function () {
        this.cart = null;
        this.start = async () => {
          loadingIPS.loadding.showLoading(true);
          await this.getCart();
          this.renderCartDetail();
          this.handleQuantity();
          this.eventUpdateCart();
          loadingIPS.loadding.showLoading(false);
        }

        this.renderCartDetail = () => {
          if(!this.cart) {
            return;
          }

          let cartDetails = this.cart.cartDetails ?? [];

          let htmlArr = cartDetails.map((cartDetail, index) => {
            return `
            <div  class="col-12 cart-item">
              <div class="row">
                <div class="col-4 d-flex">
                  <div class="d-flex align-items-center">
                    <span class="me-2">
                      <input type="checkbox">
                    </span>
                    <div >
                      <img src="${cartDetail.product_avatar_link}" style="max-width: 70px; height: auto;" class="rounded  d-block" alt="">
                    </div>
                    <span>${cartDetail.product_name}</span>
                  </div>
                </div>

                <div class="col-2">
                  <span class="fw-bolder">${cartDetail.price} đ</span>
                </div>

                <div class="col-2">
                  <input type="hidden" form="form-cart" name="cartDetail[${index}][id]" value="${cartDetail.id}">
                  <div class="input-group quantity_group" id="quantity_group_${cartDetail.id}">
                    <button style="border-color: #dee2e6;" class="btn input-group-text btn-sm btn-outline quantity_down btn-outline-secondary" id="quantity_down">-</button>
                    <input name="cartDetail[${index}][quantity]" form="form-cart" type="number" style="max-width: 50px" min="1" max="10" class="form-control form-control-sm quantity_number" value="${cartDetail.quantity}" id="quantity_number">
                    <button style="border-color: #dee2e6;" class="btn input-group-text btn-sm btn-outline quantity_up btn-outline-secondary" id="quantity_up">+</button> 
                  </div>
                </div>

                <div class="col-2">
                  <span class="text-danger fw-bolder">${cartDetail.handled_price} đ</span>
                </div>

                <div class="col-2">
                  <form action="{{url('/remove-cart-detail/${cartDetail.id}')}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn" type="submit">Xóa</button>
                  </form>
                </div>
              </div>
            </div>
            `;
          });
          
          this.find('#cartDetails').html(htmlArr.join(''));
        }

        this.updateInfoCartUi = () => {
          let totalPrice = this.find('.total-price');
          let totalPriceHandler = this.find('.total-price-handler');

          totalPrice.text(this.cart.price);
          totalPriceHandler.text(this.cart.handled_price);
        }

        this.getCart = async () => {
          let params = {};
          let res = await $.get("{{route('getCartApi')}}", params);
          let data = res.data;

          this.cart = data;
        }

        this.handleQuantity = () => {
          let quantity_group = this.find('.quantity_group');

          quantity_group.find('.quantity_down').click(function() {
            // get input quantity
            let quantityInput = $(this).siblings('.quantity_number');
            let currentQuantity = parseInt(quantityInput.val(), 10);

            if(currentQuantity > 1) {
              quantityInput.val(currentQuantity - 1);

              return;
            }

          });

          quantity_group.find('.quantity_up').click(function() {
            // get input quantity
            let quantityInput = $(this).siblings('.quantity_number');
            let currentQuantity = parseInt(quantityInput.val(), 10);

            if(currentQuantity < 10) {
              quantityInput.val(currentQuantity + 1);

              return;
            }
          });
        }

        this.eventUpdateCart =  () => {
          let formCart = this.find('#form-cart');

          formCart.submit((e) => {
            e.preventDefault();
            loadingIPS.loadding.showLoading(true);

            let formData = new FormData(document.getElementById("form-cart"));

            $.ajax({
                url: "{{route('updateCart')}}",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success:  (response) => {
                    let message = response.message;

                    let params = {};
                    $.get("{{route('getCartApi')}}", params, (res) => {
                      loadingIPS.loadding.showLoading(false);
                      let data = res.data;
                      this.cart = data;
                      this.renderCartDetail();
                      this.handleQuantity();
                      this.updateInfoCartUi();
                      toastLiveToast.showToast(message, null, 'success');
                    });
                }
            });
          });
        }

        this.start();

      }

      let cart = $('#cart').cart();

    })
  </script>
@endpush