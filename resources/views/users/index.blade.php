@extends('layouts.app_logged')
@section('content')
    <users :users_count="{{ $users_count }}"/>
@endsection