<div class="position-fixed top-0 end-0 p-3" style="z-index:10000">
    <div id="{{$id}}" class="toast  bg-gradient hide" role="alert" aria-live="assertive" aria-atomic="true">
      {{-- <div class="toast-header">
        <img src="..." class="rounded me-2" alt="...">
        <strong class="me-auto">Bootstrap</strong>
        <small>11 mins ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div> --}}
      <div class="toast-body text-light">
        Hello, world! This is a toast message.
      </div>

    </div>
</div>

@push('setupAfter')
  <script>
      var toast{{$id}} = $('#{{$id}}').toast('', 3000);
  </script>
@endpush