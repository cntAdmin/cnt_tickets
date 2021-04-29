@extends('layouts.app_logged')
@section('content')
    <profile :user-role="{{ $user->roles[0]->id }}" :user="{{ $user }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection