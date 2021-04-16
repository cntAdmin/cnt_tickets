@extends('layouts.app_logged')
@section('content')
    <ticket-view :ticket="{{ $ticket }}" />
@endsection