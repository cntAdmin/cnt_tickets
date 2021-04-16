@extends('layouts.app_logged')
@section('content')
    <department-edit :department="{{ $department }}" />
@endsection