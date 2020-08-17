<?php

namespace Swarovsky\Core\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
            'model' => $model,
            'items' => $class::paginate(15),
            'schema' => $class::getSchema('.index')
        ]);
    }

    public function create(): View
    {
        $model = $this->getModel('.create');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.create', [
            'model' => $model,
            'item' => new $class,
            'schema' => $class::getSchema('.create')
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $model = $this->getModel('.store');
        $class = $this->getClass($model);
        $class::create($request->all());
        CacheHelper::clear($model);
        SessionHelper::add_message('Successfully created!', 'success');
        return redirect()->back();
    }

    public function show($id): View
    {
        $model = $this->getModel('.show');
        $class = $this->getClass($model);
        return view('swarovsky-core::crud.show', [
            'model' => $model,
            'item' => $class::find($id),
            'schema' => $class::getSchema('.show')
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
            'schema' => $class::getSchema('.edit')
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $model = $this->getModel('.update');
        $class = $this->getClass($model);
        $class::find($id)->update($request->all());
        CacheHelper::clear($class);
        SessionHelper::add_message('Successfully updated!', 'success');
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


    private function getModel($page): string
    {
        $word = str_replace(array('admin::', $page), array('', ''), Route::currentRouteName());
        return StrHelper::singular($word);
    }

    /**
     * @param string $model
     * @return AdvancedModel|string
     */
    private function getClass(string $model)
    {
        return str_replace('$', '', 'App\Models\$') . ucFirst(Str::camel($model));
    }
}
