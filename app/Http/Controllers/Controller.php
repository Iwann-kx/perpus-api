<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Perpus API Documentation',
    description: 'Dokumentasi API untuk aplikasi perpustakaan'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum'
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}