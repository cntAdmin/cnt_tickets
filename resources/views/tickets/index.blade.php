@extends('layouts.app_logged')
@section('content')
    <tickets 
        :permissions="{{ auth()->user()->getPermissionsViaRoles() }}"
        :user="{{ auth()->user() }}" 
        :user-role="{{ auth()->user()->roles[0]->id }}"
    />
@endsection