@extends('layouts.app_logged')
@section('content')
    <department-type-edit :department-type="{{ $department_type }}" />
@endsection