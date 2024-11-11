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

        foreach ($initialUsers as $user) {
            $user = User::where('id', $user)->first();
            $this->selectedUsers[] = $user;
        }

        $this->exclude = $exclude;

    }

    public function render()
    {


            if($this->search) {
            // Fetch users based on the search input
                $users = User::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('netid', 'like', '%' . $this->search . '%');
                })
                ->whereNotIn('id', $this->exclude)
                ->take(10)
                ->get();
            } else {
                $users = collect();
            }

        return view('livewire.user-search', ['users' => $users]);
    }

    public function removeUser($userId)
    {
        // remove the user from the selected users array which is an array of user objects
        $user = User::where('id', $userId)->first();
        $this->selectedUsers = array_filter($this->selectedUsers, function ($selectedUser) use ($user) {
            return $selectedUser->id !== $user->id;
        });

        $this->search = "";

        
        
    }

    public function addUser($userId)
    {
        // add the user to the selected users array, but only if it doesn't already exist
        // it's an array of user objects

        $user = User::where('id', $userId)->first();
        if (!in_array($user, $this->selectedUsers)) {
            $this->selectedUsers[] = $user;
        }

        $this->search = "";
    }
}