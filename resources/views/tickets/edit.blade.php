@extends('layouts.app_logged')
@section('content')
    <ticket-edit :ticket="{{ $ticket }}" :user-role="{{ auth()->user()->roles[0]->id }}" />
@endsection