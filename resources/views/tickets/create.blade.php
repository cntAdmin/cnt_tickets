@extends('layouts.app_logged')
@section('content')
    <ticket-create @if(isset($customer)) :customer="{{ $customer }}" @endif />
@endsection