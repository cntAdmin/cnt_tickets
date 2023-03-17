@extends('layouts.app_logged')
@section('content')
    <tickets :statuses="{{ $ticket_statuses }}"
        :permissions="{{ auth()->user()->getPermissionsViaRoles() }}"
        :user="{{ auth()->user() }}" 
        :user-role="{{ auth()->user()->roles[0]->id }}" />
@endsection