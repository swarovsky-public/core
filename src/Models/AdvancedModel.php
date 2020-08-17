<?php

namespace Swarovsky\Core\Models;

use App\Helpers\StrHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AdvancedModel extends Model
{
    /**
     * @param string $page_name
     * @return array
     */
    public static function getSchema(string $page_name): array
    {

        $fieldAndTypeList = [];
        foreach (DB::select("describe " . StrHelper::plural(explode('.', Route::currentRouteName())[0])) as $field) {
            $type = explode(' ',$field->Type)[0];
            $max_len = -1;
            if( strpos($type, '(') !== false ){
                [$type, $max_len] = str_replace(')', '', explode('(', $type));
            }
            $required = $field->Null === 'NO';
            $fieldAndTypeList[$field->Field] = [
                'type' => $type,
                'max-length' => $max_len,
                'required' => $required
            ];
        }

        return $fieldAndTypeList;
    }

}
