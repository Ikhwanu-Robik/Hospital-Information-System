<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MedicalRecordRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MedicalRecordCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MedicalRecordCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MedicalRecord::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/medical-record');
        CRUD::setEntityNameStrings('medical record', 'medical records');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'patient_profile_id',
                'type' => 'select',
                'entity' => 'patientProfile',
                'attribute' => 'full_name',
                'model' => "App\Models\PatientProfile"
            ],
            [
                'name' => 'doctor_profile_id',
                'type' => 'select',
                'entity' => 'doctorProfile',
                'attribute' => 'full_name',
                'model' => "App\Models\DoctorProfile"
            ],
            [
                'name' => 'complaint'
            ],
            [
                'name' => 'diagnosis'
            ]
        ]);

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
        CRUD::setValidation(MedicalRecordRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('patient_profile_id')->type('select')->entity('patientProfile');
        CRUD::field('doctor_profile_id')->type('select')->entity('doctorProfile');
        CRUD::field('complaint');
        CRUD::field('diagnosis');

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
