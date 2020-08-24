<?php

namespace Swarovsky\Core\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Swarovsky\Core\Helpers\CacheHelper;
use Swarovsky\Core\Helpers\SessionHelper;
use Swarovsky\Core\Helpers\StrHelper;
use Swarovsky\Core\Models\AdvancedModel;


/**
 * Class CrudController
 * @package Swarovsky\Core\Http\Controllers
 */
class CrudController extends Controller
{


    public function __construct()
    {
        $model = StrHelper::plural($this->getModel('.index'));
        $this->middleware("advanced_permission:edit {$model}");
    }


    public function index(): View
    {
        $model = $this->getModel('.index');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.index', [
            'route' => str_replace('.index', '', Route::currentRouteName()),
            'item' => app($class),
            'objects' => $class::paginate(15)
        ]);
    }

    public function create(): View
    {
        $model = $this->getModel('.create');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.create', [
            'model' => $model,
            'item' => new $class,
            'schema' => app($class)->getSchema()
        ]);
    }


    protected function validator(array $data, array $schema, string $table): \Illuminate\Contracts\Validation\Validator
    {

        $validator = [];
        foreach ($schema as $columnName => $options) {
            $attributes = [];
            // Accepted
            // Active URL
            // After (Date)
            // After Or Equal (Date)
            // Alpha
            // Alpha Dash
            // Alpha Numeric
            // Array
            // Bail
            // Before (Date)
            // Before Or Equal (Date)
            // Between
            // Boolean
            if (in_array($options['type'], ['tinyint', 'bit'], true)) {
                $attributes[] = 'boolean';
            }
            // Confirmed
            // Date
            // Date Equals
            // Date Format
            if (in_array($options['type'], ['date', 'datetime', 'timestamp'], true)) {
                $format = ''; // TODO
                $attributes[] = "date_format:{$format}";
            }
            // Different
            // Digits
            // Digits Between
            // Dimensions (Image Files)
            // Distinct
            // Email
            // Ends With
            // Exclude If
            // Exclude Unless
            // Exists (Database)
            if (!empty($options['foreign_key']) &&
                array_key_exists('table', $options['foreign_key']) &&
                array_key_exists('column', $options['foreign_key'])
            ) {
                $attributes[] = 'exists:' . $options['foreign_key']['table'] . ',' . $options['foreign_key']['column'];
            }
            // File
            // Filled
            // Greater Than
            // Greater Than Or Equal
            // Image (File)
            // In
            // In Array
            // Integer
            if (
            in_array($options['type'], ['int', 'integer', 'smallint', 'mediumint', 'bigint'], true)
            ) {
                $attributes[] = 'integer';
            }
            // IP Address
            // JSON
            // Less Than
            // Less Than Or Equal
            // Max
            if (
                (int)$options['max-length'] > 0 &&
                in_array($options['type'], ['varchar', 'text', 'blob', 'enum', 'char', 'set'], true)
            ) {
                $attributes[] = 'max:' . $options['max-length'];
            }

            // MIME Types
            // MIME Type By File Extension
            // Min
            // Not In
            // Not Regex
            // Nullable
            if (!$options['required']) {
                $attributes[] = 'nullable';
            }
            // Numeric
            if (in_array($options['type'], ['decimal', 'numeric', 'float', 'double'], true)) {
                $attributes[] = 'numeric';
            }
            // Password
            // Present
            // Regular Expression
            // Required
            if ($options['required'] && !$options['ai']) {
                $attributes[] = 'required';
            }
            // Required If
            // Required Unless
            // Required With
            // Required With All
            // Required Without
            // Required Without All
            // Same
            // Size
            // Sometimes
            // Starts With
            // String
            if (in_array($options['type'], ['varchar', 'text', 'blob', 'enum', 'char', 'set'], true)) {
                $attributes[] = 'string';
            }
            // Timezone
            // Unique (Database)
            if ($options['unique']) {
                if(isset($data['id'])){
                    $attributes[] = "unique:{$table},{$columnName},".$data['id'];
                } else {
                    $attributes[] = "unique:{$table},{$columnName}";
                }
            }
            // URL
            // UUID


            $validator[$columnName] = $attributes;
        }
   //     dd($schema, $validator);

        return Validator::make($data, $validator);
    }


    public function store(Request $request): RedirectResponse
    {
        $model = $this->getModel('.store');
        $class = $this->getClass($model);
        $app_class = app($class);
        $validator = $this->validator($request->all(), $app_class->getSchema(), $app_class->getTable());

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->getMessages() as $error_message) {
                SessionHelper::add_message($error_message[0], 'danger');
            }
            return redirect()->back()->withInput();
        }
        $data = $validator->getData();

        $class::create($data);
        CacheHelper::clear($model);
        SessionHelper::add_message('Successfully created!', 'success');

        foreach (app($class)->getHasManyRelations() as $relation) {
            $this->syncHasMany(app($class), app($relation),  $request);
        }
        app($class)->custom_sync($request);
        return redirect()->back();
    }

    public function show($id): View
    {
        $model = $this->getModel('.show');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.show', [
            'model' => $model,
            'item' => $class::find($id),
            'schema' => app($class)->getSchema()
        ]);
    }

    public function edit($id): View
    {
        $model = $this->getModel('.edit');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.edit', [
            'route' => str_replace('.edit', '', Route::currentRouteName()),
            'model' => $model,
            'item' => $class::find($id),
            'schema' => app($class)->getSchema()
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $model = $this->getModel('.update');
        $class = $this->getClass($model);
        $object =  $class::find($id);

        $requestData = $request->all();
        $requestData['id'] = (int) $id;
        $validator = $this->validator($requestData, $object->getSchema(), $object->getTable());
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->getMessages() as $error_message) {
                SessionHelper::add_message($error_message[0], 'danger');
            }
            return redirect()->back()->withInput();
        }
        $data = $validator->getData();

        $object->update($data);
        CacheHelper::clear($class);
        SessionHelper::add_message('Successfully updated!', 'success');
        foreach ($object->getHasManyRelations() as $relation) {
            $this->syncHasMany($object, app($relation),  $request);
        }
        $object->custom_sync($request);
        return redirect()->back();
    }

    public function destroy($id): RedirectResponse
    {
        $model = $this->getModel('.destroy');
        $class = $this->getClass($model);
        $class::find($id)->delete();
        CacheHelper::clear($class);
        SessionHelper::add_message('Successfully deleted!', 'success');
        return redirect()->back();
    }


    protected function syncHasMany(AdvancedModel $model, AdvancedModel $relation, Request $request)
    {
        $column = StrHelper::getClassModelName($relation);
        if ($request->has($column)) {
            $function = "sync_{$column}";
            $model->$function($request[$column]);
            SessionHelper::add_message($column. ' successfully synced!', 'success');
        }
    }


    private function getModel($page): string
    {
        $word = str_replace($page, '', Route::currentRouteName());
        return StrHelper::singular($word);
    }

    /**
     * @param string $model
     * @return AdvancedModel|string
     */
    private function getClass(string $model)
    {
        $swarovsky_class = str_replace('$', '', 'Swarovsky\Core\Models\$') . ucFirst(Str::camel($model));
        $app_class = str_replace('$', '', 'App\Models\$') . ucFirst(Str::camel($model));
        if (class_exists($swarovsky_class)) {
            return $swarovsky_class;
        }

        if (app()->getProvider('Swarovsky\Subscriptions\Providers\SubscriptionsServiceProvider')) {
            $qoh_class = str_replace('$', '', 'Swarovsky\Subscriptions\Models\$') . ucFirst(Str::camel($model));
            if (class_exists($qoh_class)) {
                return $qoh_class;
            }
        }

        if (app()->getProvider('Swarovsky\Queendomofhera\Providers\QueendomofheraServiceProvider')) {
            $qoh_class = str_replace('$', '', 'Swarovsky\Queendomofhera\Models\$') . ucFirst(Str::camel($model));
            if (class_exists($qoh_class)) {
                return $qoh_class;
            }
        }
        return $app_class;
    }
}
