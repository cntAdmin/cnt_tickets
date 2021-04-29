@extends('layouts.app_logged')
@section('content')
    <ticket-view :ticket="{{ $ticket }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() ?? null }}"
        :user-role="{{ auth()->user()->roles[0]->id ?? null }}"
    />
@endsection