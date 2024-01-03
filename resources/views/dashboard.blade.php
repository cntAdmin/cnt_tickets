@extends('layouts.app_logged')
@section('content')
    <dashboard 
        user_id = "{{ auth()->user()->id }}"
        user_name = "{{ auth()->user()->name }}"
        user_role = "{{ auth()->user()->roles[0]->id }}" 
        customer_id = "{{ auth()->user()->customer->id }}"
    ></dashboard>
@endsection