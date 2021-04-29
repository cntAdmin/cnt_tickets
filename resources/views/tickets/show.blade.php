@extends('layouts.app_logged')
@section('content')
    <ticket-view :ticket="{{ $ticket }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}"
        :user-role="{{ auth()->user()->roles[0]->id }}"
    />
@endsection