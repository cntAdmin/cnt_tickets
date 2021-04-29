@component('mail::message')
<b>#{{ $comment->ticket->custom_id }}</b>
<p>
    Hola {{ $comment->ticket->customer ? $comment->ticket->customer->name : $comment->ticket->user->name }},

    Nuestros técnicos han respondido tu incidencia, si quieres responder rápidamente puedes pinchar
    en el enlace de abajo dentro de las próximas 72 horas,
    en caso de exceder ese tiempo podrás responder desde el panel de incidencias.
</p>
@component('mail::panel')
<p>ID Incidencia: {{ $comment->ticket->custom_id }}</p>
<p>Asunto: {{ $comment->ticket->title }}</p>
<p>Departamento: {{ $comment->ticket->department_type->department->name }}</p>
<p>Servicio: {{ $comment->ticket->department_type->name }}</p>
@endcomponent
<p>
    Puede comprobar el estado de la incidenciao actualizarlo en línea pulsando aquí.
</p>
@component('mail::button', ['url' => config('app.url') . 'ticket/comment/' . $comment->_token])
Ver Incidencia
@endcomponent
<p>
    Atentamente,<br />
    {{ config('app.name') }}
</p>
@endcomponent