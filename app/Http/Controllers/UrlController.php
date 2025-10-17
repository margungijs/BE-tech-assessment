<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UrlController extends Controller
{
    const BASE62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    private function generateKey($length = 8): string
    {
        $key = '';
        $max = strlen(self::BASE62) - 1;
        for ($i = 0; $i < $length; $i++) {
            $key .= self::BASE62[random_int(0, $max)];
        }
        return $key;
    }

    public function shorten(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url|max:2048'
        ]);

        for ($i = 0; $i < 5; $i++) {
            $key = $this->generateKey();
            if (!Url::where('short_key', $key)->exists()) {
                $url = Url::create([
                    'original_url' => $request->url,
                    'short_key' => $key
                ]);
                return response()->json([
                    'short_url' => url($key)
                ], 200, [], JSON_UNESCAPED_SLASHES);
            }
        }

        return response()->json(['error' => 'Failed to generate unique key'], 500);
    }

    public function redirect(string $shortKey): RedirectResponse
    {
        $url = Cache::remember("url:$shortKey", 24 * 60 * 60, function () use ($shortKey) {
            return Url::where('short_key', $shortKey)->first();
        });

        if (!$url) {
            abort(404, 'URL not found');
        }

        return redirect()->to($url->original_url);
    }
}
