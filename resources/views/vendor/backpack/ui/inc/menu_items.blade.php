{{-- This file is used for menu items by any Backpack v6 theme --}}

@if (backpack_user()->hasRole('super admin'))
    <li class="nav-item">
        <a class="nav-link" href="{{ backpack_url('dashboard') }}">
            <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
        </a>
    </li>

    <x-backpack::menu-dropdown title="Doctor" icon="la la-user-md">
        <x-backpack::menu-dropdown-item title="Doctor profiles" icon="la la-id-card"
            :link="backpack_url('doctor-profile')" />
        <x-backpack::menu-dropdown-item title="Doctor schedules" icon="la la-calendar"
            :link="backpack_url('doctor-schedule')" />
        <x-backpack::menu-dropdown-item title="Specializations" icon="la la-stethoscope"
            :link="backpack_url('specialization')" />
    </x-backpack::menu-dropdown>

    <x-backpack::menu-item title="Patients" icon="la la-procedures" :link="backpack_url('patient')" />

    <x-backpack::menu-dropdown title="Medical Records" icon="la la-notes-medical">
        <x-backpack::menu-dropdown-item title="Medical records" icon="la la-file-medical"
            :link="backpack_url('medical-record')" />
        <x-backpack::menu-dropdown-item title="Prescription medicines" icon="la la-pills"
            :link="backpack_url('prescription-medicine')" />
        <x-backpack::menu-dropdown-item title="Prescription records" icon="la la-prescription"
            :link="backpack_url('prescription-record')" />
    </x-backpack::menu-dropdown>

    <x-backpack::menu-dropdown title="Medicines" icon="la la-capsules">
        <x-backpack::menu-dropdown-item title="Drug classes" icon="la la-sitemap" :link="backpack_url('drug-class')" />
        <x-backpack::menu-dropdown-item title="Medicines" icon="la la-pills" :link="backpack_url('medicine')" />
        <x-backpack::menu-dropdown-item title="Medicine forms" icon="la la-flask" :link="backpack_url('medicine-form')" />
        <x-backpack::menu-dropdown-item title="Medicine routes" icon="la la-road" :link="backpack_url('medicine-route')" />
    </x-backpack::menu-dropdown>

    <x-backpack::menu-item title="Pharmacist profiles" icon="la la-user-nurse" :link="backpack_url('pharmacist-profile')" />
    <x-backpack::menu-item title="Users" icon="la la-users" :link="backpack_url('user')" />
    <x-backpack::menu-item title="Roles" icon="la la-user-tag" :link="backpack_url('role')" />
    <x-backpack::menu-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />

    <li class="nav-item">
        <a class="nav-link" href="{{ route('queue.printer.form') }}">
            <i class="la la-print nav-icon"></i> Queue Number Printer
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctors.ping-interval-form') }}">
            <i class="la la-clock nav-icon"></i> Doctor Ping Interval
        </a>
    </li>
    <x-backpack::menu-dropdown title="Reports" icon="la la-file-alt">
        <x-backpack::menu-dropdown-item title="Patient Visit" icon="la la-calendar-check" :link="route('report.patient-visit')" />
    </x-backpack::menu-dropdown>

@elseif (backpack_user()->hasRole('pharmacist'))
    <x-backpack::menu-item title="Drug classes" icon="la la-sitemap" :link="backpack_url('drug-class')" />
    <x-backpack::menu-item title="Medicines" icon="la la-pills" :link="backpack_url('medicine')" />
    <x-backpack::menu-item title="Medicine forms" icon="la la-flask" :link="backpack_url('medicine-form')" />
    <x-backpack::menu-item title="Medicine routes" icon="la la-road" :link="backpack_url('medicine-route')" />
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sell-medicine') }}">
            <i class="la la-cash-register nav-icon"></i> Dispense Medicines
        </a>
    </li>

@elseif (backpack_user()->hasRole('administration officer'))
    <x-backpack::menu-item title="Patients" icon="la la-procedures" :link="backpack_url('patient')" />
@endif