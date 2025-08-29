{{-- This file is used for menu items by any Backpack v6 theme --}}

@if (backpack_user()->hasRole('super admin'))
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
            {{ trans('backpack::base.dashboard') }}</a></li>
    <x-backpack::menu-dropdown title="Doctor" icons="la la-group">
        <x-backpack::menu-dropdown-item title="Doctor profiles" icon="la la-question"
            :link="backpack_url('doctor-profile')" />
        <x-backpack::menu-dropdown-item title="Doctor schedules" icon="la la-question"
            :link="backpack_url('doctor-schedule')" />
        <x-backpack::menu-dropdown-item title="Specializations" icon="la la-question"
            :link="backpack_url('specialization')" />
    </x-backpack::menu-dropdown>
    <x-backpack::menu-dropdown title="Medical Records" icons="la la-group">
        <x-backpack::menu-dropdown-item title="Medical records" icon="la la-question"
            :link="backpack_url('medical-record')" />
        <x-backpack::menu-dropdown-item title="Prescription medicines" icon="la la-question"
            :link="backpack_url('prescription-medicine')" />
        <x-backpack::menu-dropdown-item title="Prescription records" icon="la la-question"
            :link="backpack_url('prescription-record')" />
    </x-backpack::menu-dropdown>
    <x-backpack::menu-dropdown title="Medicines" icons="la la-group">
        <x-backpack::menu-dropdown-item title="Drug classes" icon="la la-question" :link="backpack_url('drug-class')" />
        <x-backpack::menu-dropdown-item title="Medicines" icon="la la-question" :link="backpack_url('medicine')" />
        <x-backpack::menu-dropdown-item title="Medicine forms" icon="la la-question"
            :link="backpack_url('medicine-form')" />
        <x-backpack::menu-dropdown-item title="Medicine routes" icon="la la-question"
            :link="backpack_url('medicine-route')" />
    </x-backpack::menu-dropdown>
    <x-backpack::menu-item title="Patient profiles" icon="la la-question" :link="backpack_url('patient-profile')" />
    <x-backpack::menu-item title="Pharmacist profiles" icon="la la-question" :link="backpack_url('pharmacist-profile')" />
    <x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
    <x-backpack::menu-item title="Roles" icon="la la-question" :link="backpack_url('role')" />
    <x-backpack::menu-item title="Permissions" icon="la la-question" :link="backpack_url('permission')" />
@elseif (backpack_user()->hasRole('pharmacist'))
    <x-backpack::menu-item title="Drug classes" icon="la la-question" :link="backpack_url('drug-class')" />
    <x-backpack::menu-item title="Medicines" icon="la la-question" :link="backpack_url('medicine')" />
    <x-backpack::menu-item title="Medicine forms" icon="la la-question" :link="backpack_url('medicine-form')" />
    <x-backpack::menu-item title="Medicine routes" icon="la la-question" :link="backpack_url('medicine-route')" />
    <li class="nav-item"><a class="nav-link" href="{{ route('sell-medicine') }}"><i class="la la-question nav-icon"></i>
            Dispense Medicines</a></li>
    <x-backpack::menu-item title="Sell Medicines" icon="la la-question" :link="route("medicine.sell")" />
@endif