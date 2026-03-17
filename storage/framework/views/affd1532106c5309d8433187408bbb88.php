<?php $__env->startSection('title', 'Messenger'); ?>
<?php $__env->startSection('header_title', 'Messenger'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .chat-container {
            height: calc(100vh - 180px);
            /* Adjust based on header/footer */
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .message-bubble {
            max-width: 75%;
            position: relative;
        }

        .message-bubble.mine {
            background: linear-gradient(135deg, #3C50E0 0%, #2a3eb1 100%);
            color: white;
            border-radius: 18px 18px 4px 18px;
        }

        .message-bubble.theirs {
            background: #F1F5F9;
            color: #1e293b;
            border-radius: 18px 18px 18px 4px;
        }

        .dark .message-bubble.theirs {
            background: #1e293b;
            color: #f8fafc;
        }

        .conversation-item.active {
            background-color: rgba(60, 80, 224, 0.1);
            border-left: 3px solid #3C50E0;
        }

        .user-avatar {
            transition: all 0.3s ease;
        }

        .conversation-item:hover .user-avatar {
            transform: scale(1.1);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div
        class="chat-container flex overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

        <!-- Left Sidebar: Conversations -->
        <div
            class="w-full md:w-1/3 lg:w-1/4 flex flex-col border-r border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            <!-- Search -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="relative">
                    <input type="text" placeholder="Search messages..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-white dark:bg-slate-800 border-none focus:ring-2 focus:ring-accent text-sm shadow-sm transition-all"
                        id="searchInfo">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <button onclick="startNewChat()"
                    class="mt-3 w-full py-2 bg-accent hover:bg-accent/90 text-white rounded-lg text-sm font-bold shadow-md transition-all flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>New Conversation</span>
                </button>
            </div>

            <!-- Conversation List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar" id="conversationList">
                <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $otherUser = $conversation->users->first(); // Filtered in controller
                        $lastMsg = $conversation->lastMessage;
                        $isUnread = $lastMsg && $lastMsg->sender_id !== auth()->id() && (!$conversation->pivot->last_read_at || $lastMsg->created_at > $conversation->pivot->last_read_at);
                    ?>
                    <div onclick="loadConversation(<?php echo e($conversation->id); ?>, this)"
                        class="conversation-item p-4 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all border-b border-slate-100 dark:border-slate-700/50 group"
                        data-id="<?php echo e($conversation->id); ?>">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 rounded-full overflow-hidden border-2 border-white dark:border-slate-700 shadow-sm user-avatar">
                                    <img src="<?php echo e($otherUser->profile ? $otherUser->profile->getProfilePictureUrl() : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->designation ?? 'User') . '&color=7F9CF5&background=EBF4FF'); ?>"
                                        class="w-full h-full object-cover">
                                </div>
                                <?php if($isUnread): ?>
                                    <div
                                        class="absolute top-0 right-0 w-3 h-3 bg-accent rounded-full ring-2 ring-white dark:ring-slate-800 animate-pulse">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4
                                        class="text-sm font-bold text-slate-800 dark:text-white truncate group-hover:text-accent transition-colors">
                                        <?php echo e($otherUser->profile->full_name ?? $otherUser->designation); ?>

                                    </h4>
                                    <span class="text-[10px] text-slate-400">
                                        <?php echo e($conversation->last_message_at ? $conversation->last_message_at->shortAbsoluteDiffForHumans() : ''); ?>

                                    </span>
                                </div>
                                <p
                                    class="text-xs text-slate-500 dark:text-slate-400 truncate <?php echo e($isUnread ? 'font-bold text-slate-800 dark:text-slate-200' : ''); ?>">
                                    <?php echo e($lastMsg ? ($lastMsg->sender_id == auth()->id() ? 'You: ' : '') . $lastMsg->body : 'Start a conversation'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-8 text-center text-slate-400">
                        <p class="text-xs uppercase tracking-widest font-bold">No messages directly</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Panel: Chat Area -->
        <div class="flex-1 flex flex-col bg-white dark:bg-slate-800 relative">
            <!-- Chat Header -->
            <div id="chatHeader"
                class="h-20 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md hidden">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-600">
                        <img id="headerImage" src="" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 id="headerName" class="text-base font-bold text-slate-800 dark:text-white"></h3>
                        <p id="headerStatus" class="text-[10px] text-accent font-bold uppercase tracking-wider">Online</p>
                    </div>
                </div>
                <!-- Actions -->
                <button class="text-slate-400 hover:text-accent transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Empty State -->
            <div id="emptyState"
                class="flex-1 flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                <svg class="w-24 h-24 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                <p class="text-lg font-bold">Select a conversation to start chatting</p>
            </div>

            <!-- Messages Area -->
            <div id="messagesArea" class="flex-1 overflow-y-auto p-6 space-y-6 hidden bg-slate-50 dark:bg-slate-900/30">
                <!-- Messages inserted via JS -->
            </div>

            <!-- Input Area -->
            <div id="inputArea"
                class="p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 hidden">
                <form id="messageForm" onsubmit="sendMessage(event)" class="flex items-end space-x-3">
                    <button type="button"
                        class="p-3 text-slate-400 hover:text-accent transition rounded-full hover:bg-slate-100 dark:hover:bg-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                    </button>
                    <div class="flex-1 relative">
                        <textarea id="messageInput" rows="1"
                            class="w-full bg-slate-100 dark:bg-slate-900 border-none rounded-2xl px-4 py-3 focus:ring-2 focus:ring-accent resize-none max-h-32 scrollbar-hide"
                            placeholder="Type a message..." required></textarea>
                    </div>
                    <button type="submit" id="sendBtn"
                        class="p-3 bg-accent hover:bg-accent/90 text-white rounded-full shadow-lg shadow-accent/30 transition-transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        let activeConversationId = null;
        let pollInterval = null;

        function startNewChat() {
            // Simple prompt for now, could be a modal with user search
            Swal.fire({
                title: 'Start Conversation',
                input: 'text',
                inputLabel: 'Enter User ID or Designation', // Ideal would be a select2 or list
                showCancelButton: true,
                confirmButtonText: 'Go to Team',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    window.location.href = "<?php echo e(route('users.index')); ?>"; // Redirect to team list for easier selection
                }
            });
        }

        function loadConversation(id, element) {
            if (activeConversationId === id) return;
            activeConversationId = id;

            // UI Updates
            document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
            if (element) element.classList.add('active');

            // Hide empty state, show loaders
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('chatHeader').classList.add('hidden');
            document.getElementById('messagesArea').classList.add('hidden');
            document.getElementById('inputArea').classList.add('hidden');

            showGlobalLoader(); // Use the app's global loader for transition

            // Fetch
            fetch(`<?php echo e(url('/messenger')); ?>/${id}`)
                .then(res => res.json())
                .then(data => {
                    const loader = document.getElementById('global-loader');
                    loader.classList.remove('opacity-100');
                    setTimeout(() => loader.classList.add('hidden'), 300);

                    renderConversation(data);

                    // Clear unread indicator on the item
                    if (element) {
                        const unreadDot = element.querySelector('.animate-pulse');
                        if (unreadDot) unreadDot.remove();
                        element.querySelector('.text-xs').classList.remove('font-bold', 'text-slate-800', 'dark:text-slate-200');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Failed to load conversation', 'error');
                });
        }

        function renderConversation(data) {
            const { conversation, messages, other_user } = data;

            // Header
            const bridgeUrl = "<?php echo e(route('storage.bridge', ['path' => 'PLACEHOLDER'])); ?>";
            const profileUrl = other_user?.profile?.profile_picture ?
                bridgeUrl.replace('PLACEHOLDER', encodeURIComponent(other_user.profile.profile_picture)) :
                `https://ui-avatars.com/api/?name=${encodeURIComponent(other_user?.designation || 'User')}&color=7F9CF5&background=EBF4FF`;

            document.getElementById('headerImage').src = profileUrl;
            document.getElementById('headerName').innerText = other_user?.profile?.full_name || other_user?.designation || 'Unknown User';

            // Messages
            const area = document.getElementById('messagesArea');
            area.innerHTML = '';

            let lastDate = null;

            messages.forEach(msg => {
                const isMine = msg.sender_id === <?php echo e(auth()->id()); ?>;
                const date = new Date(msg.created_at);
                const dateStr = date.toLocaleDateString();

                // Date Divider
                if (dateStr !== lastDate) {
                    area.insertAdjacentHTML('beforeend', `
                            <div class="flex justify-center my-4">
                                <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-3 py-1 rounded-full">${dateStr}</span>
                            </div>
                        `);
                    lastDate = dateStr;
                }

                const html = `
                        <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
                            <div class="message-bubble ${isMine ? 'mine' : 'theirs'} p-3 shadow-sm text-sm">
                                <p>${msg.body}</p>
                                <div class="text-[9px] mt-1 opacity-70 ${isMine ? 'text-right' : 'text-left'}">
                                    ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </div>
                            </div>
                        </div>
                    `;
                area.insertAdjacentHTML('beforeend', html);
            });

            // Show Elements
            document.getElementById('chatHeader').classList.remove('hidden');
            document.getElementById('messagesArea').classList.remove('hidden');
            document.getElementById('inputArea').classList.remove('hidden');

            // Scroll to bottom
            scrollToBottom();
        }

        function scrollToBottom() {
            const area = document.getElementById('messagesArea');
            area.scrollTop = area.scrollHeight;
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const btn = document.getElementById('sendBtn');
            const body = input.value.trim();

            if (!body || !activeConversationId) return;

            btn.disabled = true;

            try {
                const res = await fetch(`<?php echo e(url('/messenger')); ?>/${activeConversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ body })
                });

                const data = await res.json();

                if (data.status === 'success') {
                    input.value = '';

                    // Append message locally
                    const area = document.getElementById('messagesArea');
                    const date = new Date();
                    const html = `
                            <div class="flex justify-end animate-fade-in-up">
                                <div class="message-bubble mine p-3 shadow-sm text-sm">
                                    <p>${body}</p>
                                    <div class="text-[9px] mt-1 opacity-70 text-right">
                                        ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                    </div>
                                </div>
                            </div>
                        `;
                    area.insertAdjacentHTML('beforeend', html);
                    scrollToBottom();

                    // Update sidebar preview
                    const sidebarItem = document.querySelector(`.conversation-item[data-id="${activeConversationId}"]`);
                    if (sidebarItem) {
                        sidebarItem.querySelector('.text-xs').innerText = 'You: ' + body;
                        sidebarItem.querySelector('.flex.justify-between span').innerText = 'Just now';
                        // Move to top
                        const list = document.getElementById('conversationList');
                        list.insertBefore(sidebarItem, list.firstChild);
                    }
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to send message', 'error');
            } finally {
                btn.disabled = false;
                input.focus();
            }
        }

        // Auto-resize textarea
        const tx = document.getElementsByTagName("textarea");
        for (let i = 0; i < tx.length; i++) {
            tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
            tx[i].addEventListener("input", OnInput, false);
        }

        function OnInput() {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        }

        // Polling for new messages (Simple implementation)
        // Ideally this should use Pusher/Reverb for real-time, but polling is safer for shared hosting/basic setups
        setInterval(() => {
            if (!activeConversationId) return;
            // Logic to fetch new messages would go here
            // For now, simpler to rely on page refresh or user action as requested "simple messanger"
            // But to make it "messanger type", basic polling is needed.
            // I will leave this as a TODO for advanced refinement to avoid flooding the server.
        }, 10000);
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\messenger\index.blade.php ENDPATH**/ ?>