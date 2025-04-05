<?php

use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use App\Models\Student;
use App\Models\SchoolClass;

new class extends Component {
    #[Url('student_id')]
    public ?int $student_id = null;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $date_of_birth;
    public $school_class_id;
    public bool $isEditMode = false;

    public function mount(?int $student_id = null)
    {
        if ($student_id) {
            $student = Student::find($student_id);
            if ($student) {
                $this->student_id = $student->id;
                $this->name = $student->name;
                $this->email = $student->email;
                $this->phone = $student->phone;
                $this->address = $student->address;
                $this->date_of_birth = $student->date_of_birth;
                $this->school_class_id = $student->school_class_id;
                $this->isEditMode = true;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => $this->isEditMode 
                ? 'required|email|unique:students,email,'.$this->student_id 
                : 'required|email|unique:students,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        if ($this->isEditMode) {
            $student = Student::findOrFail($this->student_id);
            $student->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'date_of_birth' => $this->date_of_birth,
                'school_class_id' => $this->school_class_id,
            ]);
            session()->flash('message', 'Student Updated Successfully.');
        } else {
            Student::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'date_of_birth' => $this->date_of_birth,
                'school_class_id' => $this->school_class_id,
            ]);
            session()->flash('message', 'Student Created Successfully.');
        }
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->date_of_birth = '';
        $this->school_class_id = '';
        $this->student_id = null;
        $this->isEditMode = false;
    }

    public function delete($id)
    {
        Student::findOrFail($id)->delete();
        session()->flash('message', 'Student Deleted Successfully.');
    }

    public function with(): array
    {
        return [
            'students' => Student::with('schoolClass')->latest()->get(),
            'schoolClasses' => SchoolClass::all()
        ];
    }
}; ?>

<div class="container">
    <div class="content">
        <h2>Student Manager</h2>
        @if (session()->has('message'))
            <div class="alert">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save" class="student-form">
            <div>
                <label for="name">Name:</label>
                <input type="text" wire:model="name" id="name">
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" wire:model="email" id="email">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone">Phone:</label>
                <input type="text" wire:model="phone" id="phone">
                @error('phone') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="address">Address:</label>
                <input type="text" wire:model="address" id="address">
                @error('address') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" wire:model="date_of_birth" id="date_of_birth">
                @error('date_of_birth') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="school_class_id">Class:</label>
                <select wire:model="school_class_id" id="school_class_id">
                    <option value="">Select Class</option>
                    @foreach($schoolClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('school_class_id') <span class="error">{{ $message }}</span> @enderror
            </div>

            <button type="submit">{{ $isEditMode ? 'Update' : 'Create' }} Student</button>
            @if($isEditMode)
                <button type="button" wire:click="resetFields">Cancel</button>
            @endif
        </form>

        <table class="students-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->schoolClass?->name }}</td>
                        <td>
                            <button wire:click="mount({{ $student->id }})">Edit</button>
                            <button wire:click="delete({{ $student->id }})" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .student-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .student-form div {
            margin-bottom: 15px;
        }

        .student-form label {
            display: block;
            margin-bottom: 5px;
        }

        .student-form input,
        .student-form select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .students-table th,
        .students-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .students-table th {
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