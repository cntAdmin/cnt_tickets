@extends('layouts.app_logged')
@section('content')
    <origin-types :origin-types-count="{{ $origin_types_count }}" :permissions="{{ auth()->user()->getPermissionsViaRoles() }}" />
@endsection