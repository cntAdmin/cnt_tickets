@extends('layouts.app_logged')
@section('content')
    <ticket-view :ticket="{{ $ticket }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection