<!-- AI Assistant UI -->
<style>
    #ai-assistant-container {
        position: fixed !important;
        bottom: 24px !important;
        right: 24px !important;
        z-index: 999999 !important;
        display: block !important;
        width: 56px !important;
        height: 56px !important;
    }

    #ai-toggle {
        width: 56px !important;
        height: 56px !important;
        background: linear-gradient(135deg, #2b3bb3 0%, #1C2434 100%) !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 10px 25px rgba(43, 59, 179, 0.4) !important;
        cursor: pointer !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        padding: 0 !important;
        color: white !important;
    }

    #ai-toggle:hover {
        transform: scale(1.15) rotate(5deg) !important;
        box-shadow: 0 15px 35px rgba(43, 59, 179, 0.6) !important;
    }

    #ai-toggle:active {
        transform: scale(0.95) !important;
    }

    #ai-chat-window {
        position: absolute !important;
        bottom: 80px !important;
        right: 0 !important;
        width: 380px !important;
        max-width: 90vw !important;
        height: 500px !important;
        background-color: white !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
        display: none;
        flex-direction: column !important;
        overflow: hidden !important;
        border: 1px solid #E2E8F0 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform-origin: bottom right !important;
        transform: scale(0);
        opacity: 0;
    }

    .dark #ai-chat-window {
        background-color: #1C2434 !important;
        border-color: #2d3a4f !important;
        color: white !important;
    }

    #ai-chat-window.active {
        display: flex !important;
        transform: scale(1) !important;
        opacity: 1 !important;
    }

    .ai-pulse {
        position: absolute;
        inset: -4px;
        background: #2b3bb3;
        border-radius: 50%;
        opacity: 0.3;
        animation: ai-pulse-anim 2s infinite;
        z-index: -1;
    }

    .ai-pulse-2 {
        position: absolute;
        inset: -8px;
        background: #2b3bb3;
        border-radius: 50%;
        opacity: 0.15;
        animation: ai-pulse-anim 2s infinite 0.5s;
        z-index: -2;
    }

    @keyframes ai-pulse-anim {
        0% {
            transform: scale(1);
            opacity: 0.4;
        }

        100% {
            transform: scale(1.6);
            opacity: 0;
        }
    }

    #ai-messages {
        flex: 1 !important;
        padding: 16px !important;
        overflow-y: auto !important;
        background-color: #F8FAFC !important;
    }

    #ai-label {
        position: absolute !important;
        right: 70px !important;
        bottom: 10px !important;
        background: white !important;
        color: #1C2434 !important;
        padding: 8px 14px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #E2E8F0 !important;
        pointer-events: none !important;
        animation: ai-float 3s ease-in-out infinite !important;
        opacity: 0;
        transition: opacity 0.5s ease-in-out !important;
    }

    #ai-assistant-container:hover #ai-label {
        opacity: 1;
    }

    @keyframes ai-float {
        0%, 100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(-5px) translateX(-2px); }
    }

    .ai-attention-wiggle {
        animation: ai-wiggle 5s ease-in-out infinite !important;
    }

    @keyframes ai-wiggle {
        0%, 90%, 100% { transform: rotate(0); }
        92% { transform: rotate(-10deg); }
        94% { transform: rotate(10deg); }
        96% { transform: rotate(-10deg); }
        98% { transform: rotate(10deg); }
    }

    .ai-status-dot {
        position: absolute !important;
        top: 2px !important;
        right: 2px !important;
        width: 12px !important;
        height: 12px !important;
        background: #10B981 !important;
        border: 2px solid #1C2434 !important;
        border-radius: 50% !important;
        z-index: 1 !important;
    }

    .dark #ai-label {
        background-color: #1C2434 !important;
        color: white !important;
        border-color: #2d3a4f !important;
    }
</style>

<div id="ai-assistant-container">
    <div id="ai-label">Ask me anything! ✨</div>
    <div class="ai-pulse"></div>
    <div class="ai-pulse-2"></div>
    <button id="ai-toggle" class="ai-attention-wiggle">
        <span class="ai-status-dot"></span>
        <!-- Cute Bot Icon -->
        <svg id="ai-icon-open" style="width: 32px; height: 32px;" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <!-- Bot Face -->
            <rect x="4" y="6" width="16" height="12" rx="5" fill="white" fill-opacity="0.1" stroke="white"
                stroke-width="1.5" />
            <!-- Blush -->
            <circle cx="7" cy="14" r="1.5" fill="#FF80AB" fill-opacity="0.6"/>
            <circle cx="17" cy="14" r="1.5" fill="#FF80AB" fill-opacity="0.6"/>
            <!-- Eyes -->
            <circle cx="9" cy="11" r="1.5" fill="white">
                <animate attributeName="r" values="1.5;0.5;1.5" dur="3s" repeatCount="indefinite" />
            </circle>
            <circle cx="15" cy="11" r="1.5" fill="white">
                <animate attributeName="r" values="1.5;0.5;1.5" dur="3s" repeatCount="indefinite" />
            </circle>
            <!-- Mouth -->
            <path d="M10 14.5C10.5 15.2 11.2 15.5 12 15.5C12.8 15.5 13.5 15.2 14 14.5" stroke="white" stroke-width="1.5"
                stroke-linecap="round" />
            <!-- Antennas -->
            <path d="M12 6V3M12 3C12 3 13 2 13 2M12 3C12 3 11 2 11 2" stroke="white" stroke-width="1.5"
                stroke-linecap="round" />
        </svg>
        <!-- Close Icon -->
        <svg id="ai-icon-close" style="width: 24px; height: 24px; display: none;" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div id="ai-chat-window">
        <!-- Header -->
        <div
            style="background-color: #1C2434; padding: 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div
                style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white;">
                <svg style="width: 24px; height: 24px;" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="6" width="16" height="12" rx="4" stroke="currentColor" stroke-width="2" />
                    <circle cx="9" cy="11.5" r="1.2" fill="currentColor" />
                    <circle cx="15" cy="11.5" r="1.2" fill="currentColor" />
                    <path d="M10 14.5C10.5 15.2 11.2 15.5 12 15.5C12.8 15.5 13.5 15.2 14 14.5" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 14px; font-weight: bold; color: white;">Humanity AI Assistant</h3>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 8px; height: 8px; background: #10B981; border-radius: 50%;"></span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 11px; font-style: italic;">Powered by
                        Devstral</span>
                </div>
            </div>
        </div>

        <div id="ai-messages">
            <!-- Initial Message -->
            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 16px;">
                <div
                    style="width: 32px; height: 32px; border-radius: 50%; background: #2b3bb3; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="7" width="16" height="10" rx="3" stroke="currentColor" stroke-width="2" />
                        <circle cx="9" cy="11.5" r="1" fill="currentColor" />
                        <circle cx="15" cy="11.5" r="1" fill="currentColor" />
                        <path d="M10 14C10.5 14.5 11.2 14.8 12 14.8C12.8 14.8 13.5 14.5 14 14" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div
                    style="background: white; padding: 12px; border-radius: 12px; border-top-left-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #E2E8F0; max-width: 85%;">
                    <p style="margin:0; font-size: 13px; color: #1C2434; line-height: 1.5;">Hello! I am your Humanity
                        Foundation Assistant. How can I help you today?</p>
                </div>
            </div>
        </div>

        <div style="padding: 16px; background: white; border-top: 1px solid #E2E8F0;">
            <form id="ai-chat-form" style="position: relative; display: flex;">
                <input type="text" id="ai-input" placeholder="Type your message..."
                    style="width: 100%; border-radius: 10px; border: 1px solid #E2E8F0; padding: 12px 45px 12px 16px; font-size: 14px; outline: none;"
                    autocomplete="off">
                <button type="submit" id="ai-send-btn"
                    style="position: absolute; right: 5px; top: 5px; width: 36px; height: 36px; background: #1C2434; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const aiToggle = document.getElementById('ai-toggle');
        const aiWindow = document.getElementById('ai-chat-window');
        const aiOpenIcon = document.getElementById('ai-icon-open');
        const aiCloseIcon = document.getElementById('ai-icon-close');
        const aiForm = document.getElementById('ai-chat-form');
        const aiInput = document.getElementById('ai-input');
        const aiMessages = document.getElementById('ai-messages');
        const aiSendBtn = document.getElementById('ai-send-btn');

        let chatContext = [];

        aiToggle.addEventListener('click', () => {
            const isActive = aiWindow.classList.toggle('active');
            aiOpenIcon.style.display = isActive ? 'none' : 'block';
            aiCloseIcon.style.display = isActive ? 'block' : 'none';
            
            // Hide label when active
            const label = document.getElementById('ai-label');
            if (label) label.style.display = isActive ? 'none' : 'block';
            
            if (isActive) aiInput.focus();
        });

        aiForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = aiInput.value.trim();
            if (!msg) return;

            appendMessage('user', msg);
            aiInput.value = '';
            aiSendBtn.disabled = true;

            const typingDiv = appendTyping();

            // Create a bot message container for streaming
            const botMsgDiv = document.createElement('div');
            botMsgDiv.style.display = 'flex';
            botMsgDiv.style.gap = '10px';
            botMsgDiv.style.marginBottom = '16px';
            botMsgDiv.innerHTML = `
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #2b3bb3; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="7" width="16" height="10" rx="3" stroke="currentColor" stroke-width="2"/>
                        <circle cx="9" cy="11.5" r="1" fill="currentColor"/>
                        <circle cx="15" cy="11.5" r="1" fill="currentColor"/>
                        <path d="M10 14C10.5 14.5 11.2 14.8 12 14.8C12.8 14.8 13.5 14.5 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div style="background: white; padding: 12px; border-radius: 12px; border-top-left-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #E2E8F0; max-width: 85%;">
                    <p class="bot-text" style="margin:0; font-size: 13px; color: #1C2434; line-height: 1.5; white-space: pre-wrap;"></p>
                </div>
            `;
            const botTextEl = botMsgDiv.querySelector('.bot-text');

            try {
                const response = await fetch("{{ route('ai.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'text/event-stream'
                    },
                    body: JSON.stringify({ message: msg, context: chatContext })
                });

                typingDiv.remove();
                aiMessages.appendChild(botMsgDiv);

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let fullResponse = '';

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value);
                    fullResponse += chunk;

                    // --- HANDLE ACTION COMMANDS ---
                    if (fullResponse.includes('[ACTION:THEME:')) {
                        const match = fullResponse.match(/\[ACTION:THEME:(dark|light|toggle)\]/);
                        if (match) {
                            const themeAction = match[1];

                            if (themeAction === 'dark') {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('color-theme', 'dark');
                            } else if (themeAction === 'light') {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('color-theme', 'light');
                            } else if (themeAction === 'toggle') {
                                document.documentElement.classList.toggle('dark');
                                const newTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                                localStorage.setItem('color-theme', newTheme);
                            }

                            // Update theme toggle icons
                            const darkIcon = document.getElementById('theme-toggle-dark-icon');
                            const lightIcon = document.getElementById('theme-toggle-light-icon');
                            if (darkIcon && lightIcon) {
                                const isDark = document.documentElement.classList.contains('dark');
                                darkIcon.classList.toggle('hidden', isDark);
                                lightIcon.classList.toggle('hidden', !isDark);
                            }

                            // Remove action prefix from display
                            fullResponse = fullResponse.replace(/\[ACTION:THEME:(dark|light|toggle)\]\s*/g, '');

                            window.dispatchEvent(new Event('theme-changed'));
                        }
                    }

                    botTextEl.innerHTML = formatMessage(fullResponse);
                    aiMessages.scrollTop = aiMessages.scrollHeight;
                }

                chatContext.push({ role: 'user', content: msg });
                chatContext.push({ role: 'assistant', content: fullResponse });
                if (chatContext.length > 10) chatContext.splice(0, 2);

            } catch (err) {
                if (typingDiv) typingDiv.remove();
                appendMessage('bot', "Error connecting to AI service.");
            } finally {
                aiSendBtn.disabled = false;
            }
        });

        function formatMessage(text) {
            // Parse markdown tables
            const tableRegex = /\|(.+)\|\n\|[-|\s]+\|\n((?:\|.+\|\n?)+)/g;
            text = text.replace(tableRegex, function (match, header, rows) {
                const headers = header.split('|').map(h => h.trim()).filter(h => h);
                const rowsArr = rows.trim().split('\n').map(row =>
                    row.split('|').map(c => c.trim()).filter(c => c)
                );

                let table = '<table style="width:100%; border-collapse:collapse; margin:8px 0; font-size:11px;">';
                table += '<thead><tr style="background:#1C2434; color:white;">';
                headers.forEach(h => {
                    table += `<th style="padding:6px 8px; text-align:left; border:1px solid #ddd;">${h}</th>`;
                });
                table += '</tr></thead><tbody>';
                rowsArr.forEach((row, i) => {
                    const bg = i % 2 === 0 ? '#f8f9fa' : '#fff';
                    table += `<tr style="background:${bg};">`;
                    row.forEach(cell => {
                        table += `<td style="padding:5px 8px; border:1px solid #ddd;">${cell}</td>`;
                    });
                    table += '</tr>';
                });
                table += '</tbody></table>';
                return table;
            });

            // Simple markdown-like formatting for bold and lists
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/^\s*[\-\*]\s+(.*)/gm, '• $1')
                .replace(/\n/g, '<br>');
        }

        function appendMessage(role, text) {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.gap = '10px';
            div.style.marginBottom = '16px';
            div.style.justifyContent = role === 'user' ? 'flex-end' : 'flex-start';

            const isBot = role === 'bot';
            div.innerHTML = `
            ${isBot ? '<div style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg></div>' : ''}
            <div style="background: ${isBot ? 'white' : '#1C2434'}; color: ${isBot ? '#1C2434' : 'white'}; padding: 12px; border-radius: 12px; border-top-${isBot ? 'left' : 'right'}-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid ${isBot ? '#E2E8F0' : '#1C2434'}; max-width: 85%;">
                <p style="margin:0; font-size: 13px; line-height: 1.5; white-space: pre-wrap;">${isBot ? formatMessage(text) : text}</p>
            </div>
        `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        }

        function appendTyping() {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.gap = '10px';
            div.style.marginBottom = '16px';
            div.innerHTML = `
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                </svg>
            </div>
            <div style="background: white; padding: 12px; border-radius: 12px; border: 1px solid #E2E8F0;">
                <div style="display: flex; gap: 4px;">
                    <div style="width:4px; height:4px; border-radius:50%; background:#1C2434; animation: ai-bounce 1s infinite"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:#1C2434; animation: ai-bounce 1s infinite 0.2s"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:#1C2434; animation: ai-bounce 1s infinite 0.4s"></div>
                </div>
            </div>
        `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
            return div;
        }
        // Show label temporarily on load
        const label = document.getElementById('ai-label');
        if (label) {
            setTimeout(() => {
                label.style.opacity = '1';
                setTimeout(() => {
                    if (!aiWindow.classList.contains('active')) {
                        label.style.opacity = '0';
                    }
                }, 10000);
            }, 2000);
        }
    });
</script>

<style>
    @keyframes ai-bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }
</style>