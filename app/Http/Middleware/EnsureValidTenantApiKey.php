<?php

namespace App\Http\Middleware;

use App\Models\TenantApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidTenantApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // tenant must be resolved by domain
            $tenant = tenant();
            if (! $tenant) {
                return response()->json(['error' => 'tenant_not_resolved'], 401);
            }

            $token = $this->getTokenFromRequest($request);
            if (! $token) {
                return response()->json(['error' => 'missing_api_key'], 401);
            }

            // central table
            $apiKey = TenantApiKey::where('tenant_id', (string) $tenant->id)
                ->where('key', $token)
                ->first();

            if (! $apiKey) {
                return response()->json(['error' => 'invalid_api_key'], 401);
            }

            $apiKey->forceFill(['last_used_at' => now()])->save();

            return $next($request);
        } catch (\Throwable $e) {
            // temporary visibility to diagnose issues
            return response()->json([
                'error' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function getTokenFromRequest(Request $request): ?string
    {
        if ($v = $request->header('X-Api-Key')) {
            return trim($v);
        }

        $auth = $request->header('Authorization');
        if (is_string($auth) && str_starts_with($auth, 'Bearer ')) {
            return trim(substr($auth, 7));
        }

        return null;
    }
}
