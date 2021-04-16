@extends('layouts.app_logged')
@section('content')
    <ticket-status-edit :ticket-status="{{ $ticket_status }}" />
@endsection