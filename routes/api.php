<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// PHP CRUD API Dependencies:
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;
// Import Customer Controller:
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\UserController;
// Import API Externa Controller:
use App\Http\Controllers\API\EcomapController;
// Import Token Controller:
use App\Http\Controllers\API\TokenController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
// --------------------- Customers API Dependencies + Autorization Gates / Policies:
Route::apiResource('customers', CustomerController::class)->middleware('auth:sanctum');
Route::apiResource('users', UserController::class);
// --------------------- API Externa Here Maps:
Route::get('ecomaps', [EcomapController::class, 'index']);
// --------------------- Auth Token Dependencies:
// emite un nuevo token
Route::post('tokens', [TokenController::class, 'store']);
// elimina el token del usuario autenticado
Route::delete('tokens', [TokenController::class, 'destroy'])->middleware('auth:sanctum');
// Proteccion Rutas:
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();
    $user->fullName = $user->name;
    return $user;
});
// --------------------- PHP CRUD API Dependencies:
Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'basePath' => '/api',
    ]);
    $api = new Api($config);
    $response = $api->handle($request);

    // Rested:
    //return $response;

    // React:
    //$records = json_decode($response->getBody()->getContents())->records;
    //return response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);

    // React Con Edit y Add:
    try {
        $records = json_decode($response->getBody()->getContents())->records;
        $response = response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);
    } catch (\Throwable $th) {}
    return $response;
})->where('any', '.*');
