@props([
  'className' => null
])

<div class="container max-w-[1280px] mx-auto px-4 {{$className}}">
  {{$slot}}
</div>