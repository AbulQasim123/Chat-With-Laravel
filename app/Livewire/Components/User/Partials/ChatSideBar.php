<?php

namespace App\Livewire\Components\User\Partials;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatSideBar extends Component
{
    public $users;
    public $newUsers = [];
    public $search = '';
    public $selectedUserId = null;
    public function mount()
    {
        $this->loadUsers();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }


    public function loadUsers()
    {
        $authId = Auth::id();

        $this->users = User::where('id', '!=', $authId)
            ->where(function ($query) use ($authId) {
                $query->whereIn('id', function ($q) use ($authId) {
                    $q->select('receiver_id')->from('chats')->where('sender_id', $authId);
                })->orWhereIn('id', function ($q) use ($authId) {
                    $q->select('sender_id')->from('chats')->where('receiver_id', $authId);
                });
            })
            ->with(['latestMessage' => function ($q) use ($authId) {
                $q->where(function ($q2) use ($authId) {
                    $q2->where('sender_id', $authId)->orWhere('receiver_id', $authId);
                })->latest('created_at');
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%');
                });
            })
            ->get();
    }

    public function openNewChatModal()
    {
        $this->newUserLoad();
        $this->selectedUserId = null;
        $this->search = '';
        $this->dispatch('show-new-chat-modal');
    }

    public function newUserLoad()
    {
        $authId = Auth::id();

        $this->newUsers = User::where('id', '!=', $authId)
            // ->where(function ($query) use ($authId) {
            //     $query->whereIn('id', function ($q) use ($authId) {
            //         $q->select('receiver_id')
            //             ->from('chats')
            //             ->where('sender_id', $authId);
            //     })->orWhereIn('id', function ($q) use ($authId) {
            //         $q->select('sender_id')
            //             ->from('chats')
            //             ->where('receiver_id', $authId);
            //     });
            // })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            })
            ->get();
    }
    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->dispatch('hide-new-chat-modal');
        $this->dispatch('userSelected', $userId);
    }

    public function render()
    {
        return view('livewire.components.user.partials.chat-side-bar');
    }
}
