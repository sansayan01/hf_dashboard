<!-- AI Assistant UI -->
<style>
    #ai-assistant-container {
        position: fixed !important;
        bottom: 24px !important;
        right: 24px !important;
        z-index: 999999 !important;
        display: block !important;
    }

    #ai-toggle {
        width: 64px !important;
        height: 64px !important;
        background-color: #1C2434 !important;
        /* Fixed primary color */
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 10px 25px rgba(28, 36, 52, 0.4) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        padding: 0 !important;
        color: white !important;
    }

    #ai-toggle:hover {
        transform: scale(1.1) !important;
        background-color: #2b3bb3 !important;
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
        background: #1C2434;
        border-radius: 50%;
        opacity: 0.2;
        animation: ai-pulse-anim 2s infinite;
        z-index: -1;
    }

    @keyframes ai-pulse-anim {
        0% {
            transform: scale(1);
            opacity: 0.2;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.1;
        }

        100% {
            transform: scale(1);
            opacity: 0.2;
        }
    }

    #ai-messages {
        flex: 1 !important;
        padding: 16px !important;
        overflow-y: auto !important;
        background-color: #F8FAFC !important;
    }

    .dark #ai-messages {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
</style>

<div id="ai-assistant-container">
    <div class="ai-pulse"></div>
    <button id="ai-toggle">
        <!-- Bot Icon -->
        <svg id="ai-icon-open" style="width: 32px; height: 32px;" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z"
                stroke="currentColor" stroke-width="2" />
            <path d="M8 12H8.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
            <path d="M16 12H16.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
            <path d="M9 16C9.85002 16.6341 10.882 17 12 17C13.118 17 14.15 16.6341 15 16" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" />
            <path d="M12 8V7M12 7C12 5.89543 11.1046 5 10 5" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" />
        </svg>
        <!-- Close Icon -->
        <svg id="ai-icon-close" style="width: 32px; height: 32px; display: none;" fill="none" stroke="currentColor"
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
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
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
                    style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; flex-shrink: 0;">
                    AI</div>
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
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; flex-shrink: 0;">AI</div>
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
            ${isBot ? '<div style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; flex-shrink: 0;">AI</div>' : ''}
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
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #1C2434; color: white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; flex-shrink: 0;">AI</div>
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