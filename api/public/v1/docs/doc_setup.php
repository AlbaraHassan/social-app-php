<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *   title="API Docs",
 *   description="Social App API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="albara.m.hassan@gmail.com",
 *     name="Albara"
 *   )
 * ),
 * @OA\OpenApi(
 *   @OA\Server(
 *       url="http://localhost:8888/web/api/"
 *   )
 * )
 * @OA\SecurityScheme(
 * *      securityScheme="bearerAuth",
 * *      in="header",
 * *      name="bearerAuth",
 * *      type="http",
 * *      scheme="bearer",
 * *      bearerFormat="JWT",
 * * ),
 */
