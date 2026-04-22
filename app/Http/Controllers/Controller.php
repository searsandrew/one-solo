<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    protected function respondWithRedirect(
        FormRequest $request,
        string $routeName,
        mixed $parameters,
        string $statusMessage,
        int $statusCode = 200,
    ): JsonResponse|RedirectResponse {
        $redirectUrl = route($routeName, $parameters, false);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect' => $redirectUrl,
                'status' => $statusMessage,
            ], $statusCode);
        }

        return redirect()
            ->to($redirectUrl)
            ->with('status', $statusMessage);
    }
}
