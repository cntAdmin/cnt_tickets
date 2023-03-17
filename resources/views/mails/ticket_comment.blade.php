@component('mail::message')
Hola {{ $comment->ticket->customer ? $comment->ticket->customer->name : $comment->ticket->user->name }},

Nuestros técnicos han respondido tu incidencia, si quieres responder rápidamente puedes pinchar
en el enlace de abajo dentro de las próximas 72 horas,
en caso de exceder ese tiempo podrás responder desde el panel de incidencias.

@component('mail::panel')
Ticket: #{{ $comment->ticket->id }}<br/>
Asunto: {{ $comment->ticket->title }}<br/>
Departamento: {{ $comment->ticket->department_type->department->name }}<br/>
Servicio: {{ $comment->ticket->department_type->name }}<br/>

Puede comprobar el estado de la incidenciao actualizarlo en línea pulsando aquí.
@endcomponent
@component('mail::button', ['url' => config('app.url') . '/ticket/comment/' . $comment->_token])
Ver Incidencia
@endcomponent
Atentamente,<br />
{{ config('app.name') }}
@endcomponent