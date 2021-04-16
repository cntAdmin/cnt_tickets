@extends('layouts.app_logged')
@section('content')
    <ticket-edit :ticket="{{ $ticket }}" />
@endsection