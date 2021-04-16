@extends('layouts.app_logged')
@section('content')
    <user-edit :user="{{ $user }}"/>
@endsection