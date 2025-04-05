<?php

use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use App\Models\SchoolClass;

new class extends Component {
    #[Url('class_id')]
    public ?int $class_id = null;
    public $name;
    public $description;
    public bool $isEditMode = false;

    public function mount(?int $class_id = null)
    {
        if ($class_id) {
            $class = SchoolClass::find($class_id);
            if ($class) {
                $this->class_id = $class->id;
                $this->name = $class->name;
                $this->description = $class->description;
                $this->isEditMode = true;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'nullable|string',
        ]);

        if ($this->isEditMode) {
            $class = SchoolClass::findOrFail($this->class_id);
            $class->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Class Updated Successfully.');
        } else {
            SchoolClass::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Class Created Successfully.');
        }
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
        $this->class_id = null;
        $this->isEditMode = false;
    }

    public function delete($id)
    {
        SchoolClass::findOrFail($id)->delete();
        session()->flash('message', 'Class Deleted Successfully.');
    }

    public function with(): array
    {
        return [
            'classes' => SchoolClass::withCount('students')->latest()->get()
        ];
    }
}; ?>

<div class="container">
    <div class="content">
        <h2>School Class Manager</h2>
        @if (session()->has('message'))
            <div class="alert">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save" class="class-form">
            <div>
                <label for="name">Class Name:</label>
                <input type="text" wire:model="name" id="name">
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea wire:model="description" id="description" rows="3"></textarea>
                @error('description') <span class="error">{{ $message }}</span> @enderror
            </div>

            <button type="submit">{{ $isEditMode ? 'Update' : 'Create' }} Class</button>
            @if($isEditMode)
                <button type="button" wire:click="resetFields">Cancel</button>
            @endif
        </form>

        <table class="classes-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Students Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $class)
                    <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->description }}</td>
                        <td>{{ $class->students_count }}</td>
                        <td>
                            <button wire:click="mount({{ $class->id }})">Edit</button>
                            <button wire:click="delete({{ $class->id }})" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .class-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .class-form div {
            margin-bottom: 15px;
        }

        .class-form label {
            display: block;
            margin-bottom: 5px;
        }

        .class-form input,
        .class-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .classes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .classes-table th,
        .classes-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .classes-table th {
            background: #f5f5f5;
        }

        .alert {
            padding: 10px;
            margin: 10px 0;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
        }

        .error {
            color: red;
            font-size: 0.8em;
        }

        button {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</div>