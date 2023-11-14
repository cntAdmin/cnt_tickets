<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">

            @page {
                margin: 0cm 0cm;
                font-family: "Nunito", sans-serif;
            }
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                color: #212529;
                text-align: right;
                line-height: 30px;
                border-bottom: 1px solid #cccccc;
                /* background-color: #44A2BC; */
            }
            body {
                /* margin: 0;
                font-family: "Nunito", sans-serif;
                font-size: 0.7rem;
                font-weight: 400;
                line-height: 1.6;
                color: #212529;
                text-align: left; */
                font-size: 0.75rem;
                margin-top: 75px;
                margin-left: 15px;
                margin-right: 15px;
                color: #212529;
                /* background-color: yellow; */
            }
            footer {
                position: fixed; 
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 1cm; 
                text-align: center;
                line-height: 25px;
                font-size: 0.75rem;
                color: #212529;
                /* background-color: #44A2BC;                */
            }
            ol, ul, dl {
                margin-top: 0;
            }
            ul li {
                margin-top: 0.5rem;
            }

            .cabecera-img {
                width: 85px;
                height: auto;
                margin-top: 10px;
                margin-left: 15px;
            }
            .cabecera-text{
                width: 200px;
                height: auto;
                margin-top: 20px;
                margin-right: 15px;
            }
            .float-left{
                float: left;
            }
            .float-right{
                float: right;
            }
            .text-left {
                text-align: left;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
            .text-justify {
                text-align: justify;
            }
            .text-uppercase {
                text-transform: uppercase;
            }
            .font-weight-bold {
                font-weight: 700 !important;
            }
            .border-top {
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }   
            .list-unstyled {
                padding-left: 0;
                list-style: none;
                margin-bottom: 0px;
            }
            .col-12 {
                -webkit-box-flex: 0;
                flex: 0 0 100%;
                width: 100%;
            }
            .mt-1 {
                margin-top: 0.1rem;
            }
            .mt-2 {
                margin-top: 0.5rem;
            }
            .mt-3 {
                margin-top: 1rem;
            }
            .mt-4 {
                margin-top: 1.5rem;
            }
            .mt-5 {
                margin-top: 2rem;
            }
            .pl-2 {
                padding-left: 0.5rem;
            }
            .h1, h1 {
                font-size: 2.5rem;
            }
            .h2, h2 {
                font-size: 2rem;
            }
            .h3, h3 {
                font-size: 1.75rem;
            }
            .h4, h4 {
                font-size: 1.5rem;
            }
            .h6, h6 {
                font-size: 1rem;
            }
            .h5, h5 {
                font-size: 1.25rem;
            }
            .ticket-description {
                /* display: block; */
                /* width: 100%; */
                height: calc(1.5em + .75rem + 2px);
                font-family: "Nunito", sans-serif;
                padding: .375rem .75rem;
                font-size: 0.75rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
            }
            .firma {
                width: 120px;
                height: auto;
                margin-top: 10px;
                margin-left: 15px;
            }
        </style>
        <title>Contrato Prestación de Servicios</title>
    </head>
    <body>

        <header>
            <div class="float-left">
                <img src="{{ asset('costanetworks_logo_cuadrado.png') }}" class="cabecera-img"/>
            </div>
            <div class="float-right">
                <h5 class="cabecera-text">Parte de trabajo</h5>
            </div>
        </header> 
        
        <main>
            <div class="col-12">
                <ul class="list-unstyled">
                    <li><span class="font-weight-bold">Empresa: </span>{{ $ticket->customer->name }}</li>
                    <li><span class="font-weight-bold">CIF: </span>{{ $ticket->customer->cif }}</li>
                    <li><span class="font-weight-bold">Dirección: </span>{{ $ticket->customer->address }}</li>
                    <li><span class="font-weight-bold">Cidudad/CP: </span>{{ $ticket->customer->town }} / {{ $ticket->customer->postcode }}</li>
                    <li><span class="font-weight-bold">Email: </span>{{ $ticket->customer->email }}</li>
                    <li><span class="font-weight-bold">Teléfono: </span>{{ $ticket->customer->phone }}</li>
                </ul>
            </div>

            <div class="col-12">
                <h4 style="margin-bottom: 0px;">Descripción del Parte</h4>
                <ul class="list-unstyled">
                    <li><span class="font-weight-bold">Estado: </span>{{ $ticket->invoiceable_type->name }}</li>
                    <li><span class="font-weight-bold">Servicio: </span>{{ $ticket->department_type->name }}</li>
                    <li><span class="font-weight-bold">Horas trabajadas: </span>{{ $tiempodetrabajo }}hs</li>
                </ul>
                <table>
                    @foreach ($ticket_timeslots as $timeslot)
                    <tr>
                        <td class="pl-2"><span class="font-weight-bold">Día: </span>{{ $timeslot->start_date_time->format("d/m/Y") }}</td>
                        <td class="pl-2"><span class="font-weight-bold">Inicio: </span>{{ $timeslot->start_date_time->format("H:m:s") }}hs</td>
                        <td class="pl-2"><span class="font-weight-bold">Trabajado: </span>{{ $timeslot->work_time }}hs</td>
                    </tr>
                    @endforeach
                </table>
                <div class="mt-5">
                    <span class="font-weight-bold">Descripción del trabajo</span>
                </div>
                <div class="mt-2 ticket-description">
                    {{ strip_tags($ticket->description) }}
                </div>
            </div>

            <div class="col-12 mt-5" style="margin-left: -150px;">
                <div class="float-right">
                    <img src="{{ asset('storage/'. $ticket->signature)  }}" class="firma"/>
                    <span style="margin-top: 65px; position: fixed;">{{ $ticket->updated_at->locale('es_ES')->isoFormat("d MMMM Y H:m:s") }}</span>
                </div>
            </div>
        </main>

        <footer>
            <span class="text-center">www.costanetworks.es / Teléfono 952 665 750 / info@costanetworks.es</span>
        </footer>
    </body>
</html>