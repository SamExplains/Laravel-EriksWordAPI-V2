@extends('layouts.app')
@section('content')

<create-new-word :taken="{{ $taken }}" />

@endsection