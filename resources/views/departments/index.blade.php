@extends('layouts.app_logged')
@section('content')
    <departments :departments-count="{{ $departments_count }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}"/>
@endsection