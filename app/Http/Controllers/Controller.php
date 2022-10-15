<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Product Crud Api",
 *     version="0.1"
 * )
 * 
 * @OA\Get(
 *     path="/",
 *     operationId="",
 *     tags={"Home"},
 *     summary="Home page",
 *     description="Returns Home Page",
 *     @OA\Response(
 *         response=200,
 *         description="Get the home page"
 *      ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *     )
 * )
 */
class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
