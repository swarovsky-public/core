<?php

namespace Swarovsky\Core\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper {

    public static function get(string $model, array $options = [])
    {
        $baseClass = class_basename($model);
        if (Cache::has($baseClass)) {
            $value = collect(Cache::get($baseClass));
            if(isset($options['where'])){
                foreach($options['where'] as $key => $val){
                    $value = $value->where($key, $val);
                }
            }
            return $value;
        }
        return self::set($model, $options);
    }


    public static function set(string $model, array $options)
    {
        $baseClass = class_basename($model);
        $model = app($model);
        if(array_key_exists('order', $options)) {
            $model = $model->orderBy($options['order']);
        }
        if(array_key_exists('with', $options)){
            $all = $model->with($options['with'])->get();
        } else {
            $all = $model->get();
        }
        Cache::put($baseClass, $all, 60 * 60 * 24);
        return $all;
    }

    public static function clear(string $model): void
    {
        $baseClass = class_basename($model);
        Cache::forget($baseClass);
    }

}
