@extends('layouts.app_logged')
@section('content')
    <department-types :department-types-count="{{ $department_types_count}}" />
@endsection