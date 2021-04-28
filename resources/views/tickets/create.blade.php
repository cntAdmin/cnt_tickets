@extends('layouts.app_logged')
@section('content')
    <ticket-create @if(isset($customer)) :customer="{{ $customer }}" @endif :ticket-type="{{ $ticket_type }}" />
@endsection