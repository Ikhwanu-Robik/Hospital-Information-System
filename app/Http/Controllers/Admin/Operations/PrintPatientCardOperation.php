<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait PrintPatientCardOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupPrintPatientCardRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/print-patient-card', [
            'as'        => $routeName.'.printPatientCard',
            'uses'      => $controller.'@printPatientCard',
            'operation' => 'printPatientCard',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupPrintPatientCardDefaults()
    {
        CRUD::allowAccess('printPatientCard');

        CRUD::operation('printPatientCard', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'print_patient_card', 'view', 'vendor.backpack.crud.buttons.print-patient-card-button');
            CRUD::addButton('line', 'print_patient_card', 'view', 'vendor.backpack.crud.buttons.print-patient-card-button');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function printPatientCard(Request $request)
    {
        CRUD::hasAccessOrFail('printPatientCard');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Print Patient Card '.$this->crud->entity_name;
        $this->data['patient'] = Patient::find($request->route('id'));

        // load the view
        // return view('crud::operations.print_patient_card', $this->data);
        return view('admin.print-patient-card', $this->data);
    }
}