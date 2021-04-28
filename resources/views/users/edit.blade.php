@extends('layouts.app_logged')
@section('content')
    <user-edit :user="{{ $user }}" :user-role="{{ auth()->user()->roles[0]->id }}"/>
@endsection