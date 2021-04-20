@extends('layouts.app_logged')
@section('content')
    <customers :customer_count="{{ $customer_count }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection