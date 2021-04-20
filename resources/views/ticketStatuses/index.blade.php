@extends('layouts.app_logged')
@section('content')
    <ticket-statuses :ticket_statuses_count="{{ $ticket_statuses_count }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection