@extends('layouts.app')
@section('content')

  <edit-word :word='{{ $word }}' :taken='{{ $dates }}' />

@endsection
