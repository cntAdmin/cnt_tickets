<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait PeticionesSiptizeTrait {

    protected $xAccountID = '163';
    protected $xAccountApiKey = 'Ahjai0rish2oorei5ro1ohlo';
    protected $xApiKey = 'Iebeishahrais1aic1xaegeivok5ju';
    protected $baseURL = 'https://ssl.siptize.com/vmapi/';
    protected $tarifURL = 'https://ssl.siptize.com/satmakapi';

    // Obtener todos los clientes de Siptize
    public function apiGetCustomers(){
        $data = [
            'domain' => 'Cliente',
            'rules' => [],
            'responseFields' => ['nombre', 'cif', 'cuenta', 'cuentas', 'tarifa','cuotas'],
            'order' => 'nombre',
            'orderDirection' => 'asc'
        ];

        // Petición Http
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->post($this->baseURL . '/domain/search', $data);

        // Parseamos respuesta
        $customers = $response->json();
        return $customers;
    }

    // Obtener todos los clientes de Siptize pero for rango de fecha específico
    public function apiGetCustomersByDates($dates)
    {
        $value = explode('&', $dates);
        $dateFrom  = $value[0];
        $dateTo = $value[1];

        $dateFrom = str_replace('-', '/', $dateFrom);
        $dateTo = str_replace('-', '/', $dateTo);
        
        $data = [
            'domain' => 'Cliente',
            'rules' => [[
                'field' => 'hutanCreated',
                'op' => 'GREAT_EQUALS',
                'data' => $dateFrom
                ],
                [
                'field' => 'hutanCreated',
                'op' => 'LESS_EQUALS',
                'data' => $dateTo
                ],],
            'responseFields' => ['nombre', 'cif', 'cuenta', 'cuentas', 'tarifa','cuotas'],
            'order' => 'nombre',
            'orderDirection' => 'asc'
        ];

        // Lanzamos la petición
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->post($this->baseURL . '/domain/search', $data);

        // Parseamos respuesta
        $customers = $response->json();

        return $customers;
    }

    // Comprobar datos de un cliente
    public function apiGetCustomerById($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/Cliente/' . $id);

        $data = $response->json();

        return $data;
    }

    // Obtenemos datos de cuenta bancaria del cliente
    public function apiGetCuentaBancaria($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/CuentaBancariaCliente/' . $id);

        $data = $response->json();

        return $data;
    }

    // Obtener código ISO-2 de país
    public function apiISOcodePais($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/Pais/' . $id);

        $data = $response->json();

        return $data;
    }

    // obtener metadata de PDF por ID
    public function apiGetInvoicePdfId($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/document/searchDomainMetadata/Factura/' .$id);

        $response = json_decode($response->getBody(), true);

        return collect($response);
    }

    // Obtener facturas
    public function apiGetInvoices($data)
    {   
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseURL . '/domain/search',  $data);

        $data = $response->json();

        return $data;
    }

    // Obtener factura por ID
    public function apiGetInvoicesById($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/Factura/' .$id);

        $data = $response->json();

        return $data;
    }

    // Obtener llamdas de un periodo de tiempo
    public function apiGetCallsByDate($data)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->get($this->tarifURL . '/api/v-1.0/' . $data);

        $data = $response->json();

        return $data;
    }

    // Obtener llamdas de un periodo de tiempo
    public function apiGetCuenta($tarification_id)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->get($this->tarifURL . '/api/v-1.0/account/' . $tarification_id);

        $data = $response->json();

        return $data;
    }

    // Obtener llamdas de un periodo de tiempo
    public function apiGetDireccionById($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/Direccion/' .$id);

        $data = $response->json();

        return $data;
    }

    // Obtener los datos de un artículo (Telefonía, Servicio, Producto)
    public function apiGetArticleData($id)
    {
        $response = Http::withHeaders([
            'X-Account-Id' => $this->xAccountID,
            'X-Account-Api-Key' => $this->xAccountApiKey,
        ])->get($this->baseURL . '/domain/Articulo/' .$id);

        $data = $response->json();

        return $data;
    }




    /*
    * FUNCIONES DE TARIFICAIÓN
    */

    // Obtener balance de SIPTIZE
    public function getBalance($tarif_id)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->get($this->tarifURL . '/api/v-1.0/account/' . $tarif_id);

        $dato = $response->json();

        return $dato;
    }

    // Añadir balance a SIPTIZE
    public function addBalance($tarification_id, $amount, $reference, $comment)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->post($this->tarifURL . '/api/v-1.0/account/' . $tarification_id . "/addbalance", [
            "amount" => $amount,
            "reference" => $reference,
            "comment" => $comment,
        ]);

        return $response->getStatusCode();
    }

    public function click2call($number, $apikey, $extension, $outboundid)
    {
        // Generar llamada a un número exterior
        $response = Http::withHeaders([
            'X-Api-Key' => $apikey,
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
        ])->get('https://vpbx.me/api/c2cexternal/' .$number . '/' . $extension . '?timeout=20');

        $dato = $response->json();

        return $dato;
    }

    public function apiGetTarifas()
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->get($this->tarifURL . '/api/v-1.0/rategroup');

        $dato = $response->json();

        return $dato;
    }

    public function apiGetTarifasByCountry($rateGroupId, $countryISO2)
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->xApiKey,
        ])->get($this->tarifURL . '/api/v-1.0/rategroup/' .$rateGroupId . '/country/' . $countryISO2);

        $dato = $response->json();
        
        return $dato;
    }
}