@extends('layouts.app')
@section('content')

  <move-word :word='{{ $word }}' :taken='{{ $dates }}' />

@endsection