<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard</h2>
    </x-slot>

    <div class="container">
        <div class="content">
            <h3>Management Options</h3>
            <div class="dashboard-buttons">
                <a href="{{ route('admin.students') }}" class="dashboard-button">
                    Manage Students
                </a>
                <a href="{{ route('admin.school_classes') }}" class="dashboard-button green">
                    Manage School Classes
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
