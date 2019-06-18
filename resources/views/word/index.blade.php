@extends('layouts.app')
@section('content')

<div class="container d-flex">
  <div class="flex-column">
    <div class="p-2">

      @foreach($word as $w)
        <p>{{ $w->word }}</p>
        <p>
          {{ $w->unserialize() }}
        </p>
      @endforeach


    </div>
  </div>
</div>

@stop