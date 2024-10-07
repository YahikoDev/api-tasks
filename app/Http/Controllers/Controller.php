<?php

namespace App\Http\Controllers;

/**
* @OA\Info(
*   title="API tasks", 
*   version="1.0",
*   description="Management tasks"
* )
*  @OA\SecurityScheme(
*      securityScheme="bearer_token",
*      type="http",
*      scheme="bearer"
* )
*
* @OA\Server(url="http://127.0.0.1:8000/")
*/

abstract class Controller
{
    //
}
