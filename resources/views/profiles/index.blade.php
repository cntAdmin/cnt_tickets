@extends('layouts.app_logged')
@section('content')
    <profile :userrole="{{ $user->roles[0]->id }}" :user="{{ $user }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection