<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearch extends Component
{
    public $search = '';
    public $selectedUsers = [];
    public $name = 'users'; // This is the name of the input field that will be sent to the server
    public $id = 'users'; // This is the id of the input field that will be sent to the server
    public $exclude = []; // users to exclude


    // Add this property to accept initial values
    public $initialUsers = [];

    public function mount($initialUsers = [], $inputName = 'additional_users', $inputId = 'additional_users', $exclude = [])
    {
        // Set the initial selected users if provided

        $this->name = $inputName;
        $this->id = $inputId;

        $this->selectedUsers = $initialUsers;
        $this->exclude = $exclude;

    }

    public function render()
    {

        if ($this->search === '') {
            $users = User::whereIn('id', $this->selectedUsers)->get();
        } else {

            // Fetch users based on the search input
            $users = User::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('netid', 'like', '%' . $this->search . '%');
            })
            ->whereNotIn('id', $this->exclude)
            ->get();
        }

        return view('livewire.user-search', ['users' => $users]);
    }

    public function removeUser($userId)
    {
        // remove the user from the selected users array
        $this->selectedUsers = array_values(array_filter($this->selectedUsers, function ($id) use ($userId) {
            return $id !== $userId;
        }));
    }

    public function addUser($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->removeUser($userId);
        } else {
            $this->selectedUsers[] = $userId;
        }
    }
}
