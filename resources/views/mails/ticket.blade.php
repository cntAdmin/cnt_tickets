@component('mail::message')

Hola <b>{{ $ticket->customer ? $ticket->customer->name : $ticket->user->name }}</b>,

Se ha creado un ticket con los siguientes datos.

Ticket: #{{ $ticket->id }}<br/>
Asunto: {{ $ticket->title }}<br/>
Departamento: {{ $ticket->department_type->department->name }}<br/>
Servicio: {{ $ticket->department_type->name }}<br/>

Puede comprobar el estado de la incidencia o actualizarlo en línea pulsando el siguiente botón.
@component('mail::button', ['url' => config('app.url') . '/ticket/ticket/' . $ticket->_token])
    Ver Incidencia
@endcomponent

Atentamente,<br/>
{{ config('app.name') }}

@endcomponent