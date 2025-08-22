<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PrescriptionMedicineRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PrescriptionMedicineCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PrescriptionMedicineCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PrescriptionMedicine::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/prescription-medicine');
        CRUD::setEntityNameStrings('prescription medicine', 'prescription medicines');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.        

        CRUD::column('prescription_record_id')
            ->label('Prescription Record')
            ->type('link')
            ->linkTo('prescription-record.show', [
                'id' => function ($entry) {
                    return $entry->prescription_record_id;
                }
            ]);
        CRUD::column('medicine_id')->type('select')->entity('medicine');
        CRUD::column('dose_amount');
        CRUD::column('frequency');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PrescriptionMedicineRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        $this->crud->addField([
            'name' => 'medicine_id',
            'type' => 'select',
            'entity' => 'medicine',
            'attribute' => 'name',
            'model' => "App\Models\Medicine"
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
