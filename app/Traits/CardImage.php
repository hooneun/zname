<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait CardImage
{
    public function imageCreates($id, array $data)
    {
        if (!Storage::exists('/public/cards/' . $id)) {
            Storage::makeDirectory('/public/cards/' . $id, 0775, true); //creates directory
        }

        $paths = [];
        foreach ($data as $name => $_data) {
            if (empty($_data)) {
                continue;
            }
            $paths[$name] = Storage::put('/public/cards/'. $id, $_data);
        }

        return $paths;
    }
}