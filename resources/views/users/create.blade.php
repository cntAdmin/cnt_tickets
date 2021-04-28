@extends('layouts.app_logged')
@section('content')
    <user-create :user-role="{{ auth()->user()->roles[0]->id }}"/>
@endsection