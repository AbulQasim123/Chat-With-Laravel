<?php

namespace App\Livewire\Components\User\Partials;

use Livewire\Component;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;

class ChatBox extends Component
{
    use WithFileUploads;

    public ?User $selectedUser = null;
    public string $message = '';
    public $attachment;
    public ?int $replyToId = null;
    public $chatMessages = [];

    protected $listeners = ['userSelected' => 'setUser'];

    public function setUser($userId)
    {
        $this->selectedUser = User::find($userId);
    }

    public function mount()
    {
        $this->loadMessages();
    }
    public function sendMessage()
    {

        if (!$this->selectedUser || (empty($this->message) && !$this->attachment)) {
            return;
        }

        $this->validate([
            'message' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $type = 'text';
        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('attachments', 'public');

            $mime = $this->attachment->getMimeType();
            if (str_contains($mime, 'image')) {
                $type = 'image';
            } elseif (str_contains($mime, 'video')) {
                $type = 'video';
            } else {
                $type = 'file';
            }
        }

        Chat::create([
            'sender_id'     => Auth::id(),
            'receiver_id'   => $this->selectedUser->id,
            'message'       => $this->message,
            'attachment'    => $attachmentPath,
            'message_type'  => $type,
            'reply_to_id'   => $this->replyToId,
            'status'        => 'sent',
            'sent_at'       => Carbon::now(),
            'meta'          => null,
        ]);

        $this->reset(['message', 'attachment', 'replyToId']);

        // Clear contenteditable input
        $this->loadMessages();
        $this->dispatch('clearChatInput');
    }

    public function loadMessages()
    {
        if ($this->selectedUser) {
            $this->chatMessages = Chat::where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', Auth::id());
            })
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.components.user.partials.chat-box');
    }
}
