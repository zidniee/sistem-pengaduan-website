<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Platforms;

class PlatformApiController extends BaseApiController
{
    public function index()
    {
        $platforms = Platforms::query()
            ->select('id', 'name', 'url', 'warna')
            ->orderBy('name')
            ->get();

        return $this->success($platforms, 'Daftar platform berhasil diambil');
    }
}
