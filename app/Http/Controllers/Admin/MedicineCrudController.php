<?php

namespace App\Http\Controllers\Admin;

use App\Traits\CrudAuthorization;
use App\Http\Requests\MedicineRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MedicineCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MedicineCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use CrudAuthorization; 

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Medicine::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/medicine');
        CRUD::setEntityNameStrings('medicine', 'medicines');
        $this->determineResourcePermission();
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

        CRUD::column('name');
        CRUD::column('generic_name');
        $this->crud->addColumn(
                [
                    'label' => 'Drug Class',
                    'type' => 'select',
                    'name' => 'drug_class_id',
                    'entity' => 'drugClass',
                    'attribute' => 'name',
                    'model' => "App\Models\DrugClass",
                ]
        );
        $this->crud->addColumn([
            'label' => 'Medicine Form',
            'type' => 'select',
            'name' => 'medicine_form_id',
            'entity' => 'medicineForm',
            'attribute' => 'name',
            'model' => "App\Models\MedicineForm",
        ]);
        CRUD::column('strength');
        $this->crud->addColumn([
            'label' => 'Medicine Route',
            'type' => 'select',
            'name' => 'medicine_route_id',
            'entity' => 'medicineRoute',
            'attribute' => 'name',
            'model' => "App\Models\MedicineRoute",
        ]);
        CRUD::column('unit');
        CRUD::column('stock');
        CRUD::column('price');
        CRUD::column('batch_number');
        CRUD::column('expiry_date');
        CRUD::column('manufacturer');

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
        CRUD::setValidation(MedicineRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        $this->crud->addField([
            'name' => 'drug_class_id',
            'type' => 'select',
            'entity' => 'drugClass'
        ]);
        $this->crud->addField([
            'name' => 'medicine_form_id',
            'type' => 'select',
            'entity' => 'medicineForm',
            'model' => 'App\Models\MedicineForm',
            'attribute' => 'name'
        ]);
        $this->crud->addField([
            'name' => 'medicine_route_id',
            'type' => 'select',
            'entity' => 'medicineRoute',
            'model' => 'App\Models\MedicineRoute',
            'attribute' => 'name'
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
