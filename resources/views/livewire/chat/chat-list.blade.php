<div class="tyn-content tyn-content-full-height tyn-chat has-aside-base">
    <div class="tyn-aside tyn-aside-base">
        <div class="tyn-aside-head">
            <div class="tyn-aside-head-text">
                <h3 class="tyn-aside-title">Chats</h3>
            </div>
            <div class="tyn-aside-head-tools">
                <ul class="link-group gap gx-3">
                    <li class="dropdown">
                        <!-- Blade (Livewire Component or Blade view) -->
                        <button wire:click="openNewChatModal" class="link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-plus" viewBox="0 0 16 16">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                            <span>New</span>
                        </button>

                    </li>
                </ul>
            </div>
        </div>
        <div class="tyn-aside-body" data-simplebar>
            <div class="tyn-aside-search">
                <div class="form-group tyn-pill">
                    <div class="form-control-wrap">
                        <div class="form-control-icon start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div>
                        <input type="text" class="form-control form-control-solid" id="search"
                            placeholder="Search contact / chat" wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane show active" id="all-chats" tabindex="0" role="tabpanel">
                    <ul class="tyn-aside-list">
                        @forelse ($users as $user)
                            <li class="tyn-aside-item js-toggle-main {{ $receiverId === $user->id ? 'active' : '' }}"
                                wire:click="selectUser({{ $user->id }})" style="cursor: pointer;" wire:navigate>
                                <div class="tyn-media-group">
                                    <div class="tyn-media tyn-size-lg">
                                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random"
                                            alt="">
                                    </div>
                                    <div class="tyn-media-col">
                                        <div class="tyn-media-row">
                                            <h6 class="name">{{ $user->name }}</h6>
                                            <span class="typing" id="typing-{{ $user->id }}"></span>
                                        </div>
                                        <div class="tyn-media-row has-dot-sap">
                                            <p class="content">
                                                {{ $user->latestMessage?->message ?? 'No messages yet' }}
                                            </p>
                                            <span
                                                class="meta">{{ $user->latestMessage?->created_at ? $user->latestMessage->created_at->diffForHumans() : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted py-3">
                                No users found
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>



        <div class="modal fade" id="newChat" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Search Contact</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <div class="form-control-wrap">
                                <div class="form-control-icon start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                </div>

                                <input type="text" class="form-control form-control-solid"
                                    placeholder="Search contact..." wire:model.live.debounce.300ms="search">
                            </div>
                        </div>

                        <ul class="tyn-media-list gap gap-3">

                            @forelse ($newUsers as $user)
                                <li wire:click="selectUser({{ $user->id }})" style="cursor:pointer;">

                                    <div class="tyn-media-group">

                                        <div class="tyn-media">
                                            <img
                                                src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random">
                                        </div>

                                        <div class="tyn-media-col">

                                            <div class="tyn-media-row">
                                                <h6 class="name">{{ $user->name }}</h6>
                                            </div>

                                            <div class="tyn-media-row">
                                                <p class="content">
                                                    @ {{ $user->username }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                </li>

                            @empty

                                <li class="text-center text-muted py-3">
                                    No users found
                                </li>
                            @endforelse

                        </ul>

                    </div>

                </div>
            </div>
        </div>


    </div>


    @if ($selectedUser)
        <div class="tyn-main tyn-chat-content" id="tynMain">
            <div class="tyn-chat-head">
                <ul class="tyn-list-inline d-md-none ms-n1">
                    <li>
                        <button class="btn btn-icon btn-md btn-pill btn-transparent js-toggle-main">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                            </svg>
                        </button>
                    </li>
                </ul>
                <div class="tyn-media-group">
                    <div class="tyn-media tyn-size-lg d-none d-sm-inline-flex">
                        <img src="https://ui-avatars.com/api/?name={{ $selectedUser->name }}&background=random"
                            alt="">
                    </div>
                    <div class="tyn-media tyn-size-rg d-sm-none">
                        <img src="https://ui-avatars.com/api/?name={{ $selectedUser->name }}&background=random"
                            alt="">
                    </div>
                    <div class="tyn-media-col">
                        <div class="tyn-media-row">
                            <h6 class="name">{{ $selectedUser->name }}
                                {{-- <span class="d-none d-sm-inline-block">Thompson</span> --}}
                            </h6>
                        </div>
                        <div class="tyn-media-row has-dot-sap">
                            <span class="meta">Active</span>
                        </div>
                    </div>
                </div>

                <div class="tyn-chat-search" id="tynChatSearch">
                    <div class="flex-grow-1">
                        <div class="form-group">
                            <div class="form-control-wrap form-control-plaintext-wrap">
                                <div class="form-control-icon start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                </div>
                                <input type="text" class="form-control form-control-plaintext"
                                    id="searchInThisChat" placeholder="Search in this chat">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap gap-3">
                        <ul class="tyn-list-inline ">
                            <li>
                                <button class="btn btn-icon btn-sm btn-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn-icon btn-sm btn-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
                        <ul class="tyn-list-inline ">
                            <li>
                                <button class="btn btn-icon btn-md btn-light js-toggle-chat-search">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path
                                            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tyn-chat-body js-scroll-to-end" id="tynChatBody">
                {{-- <div class="tyn-chat-body js-scroll-to-end" id="tynChatBody" > --}}
                <div class="tyn-reply" id="tynReply">
                    @foreach ($chatMessages as $msg)
                        <div class="tyn-reply-item {{ $msg->sender_id === auth()->id() ? 'outgoing' : 'incoming' }}">
                            @if ($msg->sender_id !== auth()->id())
                                <div class="tyn-reply-avatar">
                                    <div class="tyn-media tyn-size-md tyn-circle">
                                        <img src="https://ui-avatars.com/api/?name={{ $msg->sender->name }}&background=random"
                                            alt="">
                                    </div>
                                </div>
                            @endif
                            <div class="tyn-reply-group">
                                <div class="tyn-reply-bubble">
                                    <div class="tyn-reply-text">{{ $msg->message }} <span style="margin-bottom: -10px;">{{ $msg->created_at->format('g:i A') }}</span> </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div id="scroll-bottom"></div>
                </div>
            </div>

            <div class="tyn-chat-form">

                <div class="tyn-chat-form-enter">
                    <input type="text" class="form-control me-2" id="typing-indicator"
                        placeholder="Type your message..." wire:model.defer="message"
                        wire:keydown.enter="sendMessage" wire:keydown="userTyping" />
                </div>
            </div>
        </div>
    @else
        <div class="tyn-main tyn-chat-content" id="tynMain">
            <div class="text-center p-5">
                <h4>Select a user to start chatting</h4>
            </div>
        </div>
    @endif

</div>
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            let typingTimeout = null
            window.Echo.private(`chat-channel.{{ $senderId }}`)
                .listen('UserTyping', (e) => {
                    const typingIndicator = document.getElementById('typing-indicator');
                    const typingIndicatorChatList = document.getElementById(`typing-${e.senderId}`);
                    if (typingIndicator) {
                        typingIndicator.placeholder = 'Typing...';
                    }
                    if (typingIndicatorChatList) {
                        typingIndicatorChatList.innerText = 'typing...';
                    }
                    clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(() => {
                        if (typingIndicator) {
                            typingIndicator.placeholder = 'Type your message...';
                        }
                        if (typingIndicatorChatList) {
                            typingIndicatorChatList.innerText = '';
                        }
                    }, 2000);

                });
            Livewire.on('clearChatInput', () => {
                const input = document.getElementById('tynChatInput');
                if (input) input.innerText = '';
                window.livewireMessageContent = '';
            });
            Livewire.on('scrollToBottom', () => {
                const el = document.getElementById('scroll-bottom');
                if (el) {
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

    <script>
        let newChatModalInstance;

        window.addEventListener('show-new-chat-modal', () => {
            const modalEl = document.getElementById('newChat');

            // Destroy any previous modal instance if it exists
            if (newChatModalInstance) {
                newChatModalInstance.hide();
                newChatModalInstance.dispose();
            }

            // Create a new instance and show it
            newChatModalInstance = new bootstrap.Modal(modalEl, {
                backdrop: 'static',
                keyboard: false
            });

            newChatModalInstance.show();

            // Clean up redundant backdrops if any
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 0) {
                    // Remove all except the last
                    for (let i = 0; i < backdrops.length - 0; i++) {
                        backdrops[i].remove();
                    }
                }
            }, 300); // Give Bootstrap time to create the backdrop
        });
    </script>
@endpush
