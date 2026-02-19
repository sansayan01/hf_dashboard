<!-- AI Assistant UI -->
<style>
    #ai-assistant-container {
        position: fixed !important;
        bottom: 32px !important;
        right: 48px !important;
        z-index: 999999 !important;
        display: block !important;
        width: 68px !important;
        height: 68px !important;
    }

    @media (max-width: 640px) {
        #ai-assistant-container {
            bottom: 24px !important;
            right: 32px !important;
            width: 60px !important;
            height: 60px !important;
        }
    }

    #ai-toggle {
        width: 100% !important;
        height: 100% !important;
        background: linear-gradient(135deg, #2b3bb3 0%, #172033 100%) !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2.5px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 12px 30px rgba(43, 59, 179, 0.4) !important;
        cursor: pointer !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        padding: 0 !important;
        color: white !important;
    }

    #ai-toggle:hover {
        transform: scale(1.1) rotate(5deg) !important;
        box-shadow: 0 12px 25px rgba(43, 59, 179, 0.5) !important;
    }

    #ai-toggle:active {
        transform: scale(0.9) !important;
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
        opacity: 0.25;
        animation: ai-pulse-anim 2.5s infinite;
        z-index: -1;
    }

    .ai-pulse-2 {
        position: absolute;
        inset: -8px;
        background: #2b3bb3;
        border-radius: 50%;
        opacity: 0.15;
        animation: ai-pulse-anim 2.5s infinite 1.2s;
        z-index: -2;
    }

    @keyframes ai-pulse-anim {
        0% {
            transform: scale(1);
            opacity: 0.4;
        }

        100% {
            transform: scale(1.7);
            opacity: 0;
        }
    }

    #ai-messages {
        flex: 1 !important;
        padding: 16px !important;
        overflow-y: auto !important;
        background-color: #F8FAFC !important;
        transition: background-color 0.3s ease !important;
    }

    #ai-messages::-webkit-scrollbar {
        width: 5px;
    }

    #ai-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    #ai-messages::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 10px;
    }

    .dark #ai-messages {
        background-color: #0F172A !important;
    }

    .dark #ai-messages::-webkit-scrollbar-thumb {
        background: #334155;
    }

    .ai-chat-footer {
        padding: 16px !important;
        background: white !important;
        border-top: 1px solid #E2E8F0 !important;
        transition: all 0.3s ease !important;
    }

    .dark .ai-chat-footer {
        background: #1C2434 !important;
        border-color: #2d3a4f !important;
    }

    #ai-input {
        width: 100% !important;
        border-radius: 10px !important;
        border: 1px solid #E2E8F0 !important;
        padding: 12px 85px 12px 16px !important;
        font-size: 14px !important;
        outline: none !important;
        background: white !important;
        color: #1C2434 !important;
        transition: all 0.3s ease !important;
    }

    .dark #ai-input {
        background: #0F172A !important;
        border-color: #334155 !important;
        color: #F8FAFC !important;
    }

    .dark #ai-input::placeholder {
        color: #64748B !important;
    }

    /* Voice Typing Styles */
    #ai-voice-btn {
        position: absolute !important;
        right: 45px !important;
        top: 5px !important;
        width: 36px !important;
        height: 36px !important;
        background: transparent !important;
        color: #64748B !important;
        border: none !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
        z-index: 10 !important;
    }

    #ai-voice-btn:hover {
        background: #F1F5F9 !important;
        color: #2b3bb3 !important;
    }

    .dark #ai-voice-btn:hover {
        background: #1E293B !important;
        color: #3B82F6 !important;
    }

    #ai-voice-btn.is-active {
        color: #EF4444 !important;
        background: #FEE2E2 !important;
        animation: ai-voice-pulse 1.5s infinite !important;
    }

    .dark #ai-voice-btn.is-active {
        background: rgba(239, 68, 68, 0.2) !important;
    }

    @keyframes ai-voice-pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    #ai-label {
        position: absolute !important;
        right: 80px !important;
        bottom: 18px !important;
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

        0%,
        100% {
            transform: translateY(0) translateX(0);
        }

        50% {
            transform: translateY(-5px) translateX(-2px);
        }
    }

    .ai-attention-wiggle {
        animation: ai-wiggle 5s ease-in-out infinite !important;
    }

    @keyframes ai-wiggle {

        0%,
        90%,
        100% {
            transform: rotate(0);
        }

        92% {
            transform: rotate(-10deg);
        }

        94% {
            transform: rotate(10deg);
        }

        96% {
            transform: rotate(-10deg);
        }

        98% {
            transform: rotate(10deg);
        }
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

    /* Message Bubbles Dark Mode */
    .bot-msg-bubble {
        background-color: white !important;
        color: #1C2434 !important;
    }

    .dark .bot-msg-bubble {
        background-color: #1E293B !important;
        color: #F1F5F9 !important;
        border-color: #334155 !important;
    }

    .user-msg-bubble {
        background-color: #2b3bb3 !important;
        color: white !important;
    }

    .dark .user-msg-bubble {
        background-color: #3B82F6 !important;
    }

    /* Training Panel Styles */
    #ai-training-panel {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        z-index: 10;
        display: none;
        flex-direction: column;
        transition: transform 0.3s ease;
    }

    .dark #ai-training-panel {
        background: #1C2434;
        color: #F1F5F9;
    }

    #ai-training-panel.active {
        display: flex !important;
    }

    .training-header {
        padding: 14px 16px;
        background: #1C2434;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .training-header h3 {
        margin: 0;
        font-size: 14px;
        font-weight: bold;
    }

    .training-body {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }

    .training-form {
        background: #F8FAFC;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid #E2E8F0;
    }

    .dark .training-form {
        background: #0F172A;
        border-color: #334155;
    }

    .training-form input,
    .training-form textarea {
        width: 100%;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        margin-bottom: 8px;
        background: white;
        color: #1C2434;
        box-sizing: border-box;
        outline: none;
        font-family: inherit;
    }

    .dark .training-form input,
    .dark .training-form textarea {
        background: #1E293B;
        border-color: #334155;
        color: #F1F5F9;
    }

    .training-form textarea {
        resize: vertical;
        min-height: 60px;
    }

    .training-form button {
        width: 100%;
        padding: 8px;
        background: #2b3bb3;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .training-form button:hover {
        background: #1e2d8f;
    }

    .training-entry {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 8px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .dark .training-entry {
        background: #1E293B;
        border-color: #334155;
    }

    .training-entry.inactive {
        opacity: 0.5;
    }

    .training-entry-q {
        font-weight: 600;
        color: #2b3bb3;
        margin-bottom: 4px;
    }

    .dark .training-entry-q {
        color: #60A5FA;
    }

    .training-entry-a {
        color: #64748B;
        line-height: 1.4;
    }

    .dark .training-entry-a {
        color: #94A3B8;
    }

    .training-entry-actions {
        display: flex;
        gap: 6px;
        margin-top: 8px;
    }

    .training-entry-actions button {
        padding: 4px 10px;
        border: none;
        border-radius: 6px;
        font-size: 11px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .train-btn-toggle {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .dark .train-btn-toggle {
        background: rgba(59, 130, 246, 0.2);
        color: #93C5FD;
    }

    .train-btn-edit {
        background: #FEF3C7;
        color: #92400E;
    }

    .dark .train-btn-edit {
        background: rgba(245, 158, 11, 0.2);
        color: #FCD34D;
    }

    .train-btn-delete {
        background: #FEE2E2;
        color: #991B1B;
    }

    .dark .train-btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #FCA5A5;
    }

    .training-empty {
        text-align: center;
        padding: 24px;
        color: #94A3B8;
        font-size: 13px;
    }
</style>

<div id="ai-assistant-container">
    <div id="ai-label">Ask me anything! ✨</div>
    <div class="ai-pulse"></div>
    <div class="ai-pulse-2"></div>
    <button id="ai-toggle" class="ai-attention-wiggle">
        <span class="ai-status-dot" style="width: 12px; height: 12px; border-width: 2.5px;"></span>
        <!-- Cute Bot Icon -->
        <svg id="ai-icon-open" style="width: 65%; height: 65%;" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <!-- Bot Face -->
            <rect x="4" y="6" width="16" height="12" rx="5" fill="white" fill-opacity="0.1" stroke="white"
                stroke-width="1.8" />
            <!-- Blush -->
            <circle cx="7" cy="14" r="1.5" fill="#FF80AB" fill-opacity="0.6" />
            <circle cx="17" cy="14" r="1.5" fill="#FF80AB" fill-opacity="0.6" />
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
            <div style="flex: 1;">
                <h3 style="margin: 0; font-size: 14px; font-weight: bold; color: white;">HF Assistant</h3>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 8px; height: 8px; background: #10B981; border-radius: 50%;"></span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 11px; font-style: italic;">Powered by
                        Humanity Foundation</span>
                </div>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <button id="ai-train-btn" title="Train Bot"
                    style="background: none; border: none; color: rgba(255,255,255,0.7); cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center; transition: all 0.2s;"
                    onmouseover="this.style.color='white';this.style.background='rgba(255,255,255,0.1)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.7)';this.style.background='none'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </button>
            @endif
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
                <div class="bot-msg-bubble"
                    style="padding: 12px; border-radius: 12px; border-top-left-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #E2E8F0; max-width: 85%; transition: all 0.3s ease;">
                    <p style="margin:0; font-size: 13px; line-height: 1.5;">Hello
                        {{ explode(' ', auth()->user()->profile->full_name ?? 'there')[0] }}! I am your HF Assistant.
                        How
                        can I help you today?
                    </p>
                </div>
            </div>
        </div>

        <div class="ai-chat-footer">
            <form id="ai-chat-form" style="position: relative; display: flex;">
                <input type="text" id="ai-input" placeholder="Type your message..." autocomplete="off"
                    style="padding-right: 85px !important;">
                <button type="button" id="ai-voice-btn" title="Voice Typing">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                        </path>
                    </svg>
                </button>
                <button type="submit" id="ai-send-btn"
                    style="position: absolute; right: 5px; top: 5px; width: 36px; height: 36px; background: #2b3bb3; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.3s ease-in-out;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7">
                        </path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Training Panel (Super Admin Only) -->
        @if(auth()->user()->isSuperAdmin())
            <div id="ai-training-panel">
                <div class="training-header">
                    <h3>🧠 Train Bot</h3>
                    <button id="ai-train-close"
                        style="background:none;border:none;color:white;cursor:pointer;font-size:18px;">&times;</button>
                </div>
                <div class="training-body">
                    <div class="training-form">
                        <input type="text" id="train-question"
                            placeholder="Question or topic (e.g., What is the office address?)">
                        <textarea id="train-answer" placeholder="Answer the bot should give..."></textarea>
                        <button id="train-save-btn">➕ Add Training Entry</button>
                    </div>
                    <div id="training-list"></div>
                </div>
            </div>
        @endif
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
        const aiVoiceBtn = document.getElementById('ai-voice-btn');

        // Voice Typing Logic - Enhanced Version
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            let recognition = null;
            let isRecognizing = false;

            const initRecognition = () => {
                recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.lang = 'en-US';
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                recognition.onstart = () => {
                    isRecognizing = true;
                    aiVoiceBtn.classList.add('is-active');
                    aiInput.placeholder = "Listening... Speak now";
                    console.log('AI Voice: Recording started');
                };

                recognition.onend = () => {
                    isRecognizing = false;
                    aiVoiceBtn.classList.remove('is-active');
                    console.log('AI Voice: Recording ended');
                };

                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript;
                    if (transcript) {
                        aiInput.value = transcript;
                        console.log('AI Voice Result:', transcript);
                        // AUTO-SUBMIT after voice result for a seamless experience
                        setTimeout(() => {
                            if (aiInput.value.trim()) {
                                aiForm.dispatchEvent(new Event('submit'));
                            }
                        }, 600);
                    }
                };

                recognition.onerror = (event) => {
                    isRecognizing = false;
                    aiVoiceBtn.classList.remove('is-active');
                    console.error('AI Voice Error:', event.error);

                    let errorMsg = "Voice error: " + event.error;
                    if (event.error === 'not-allowed') errorMsg = "Microphone access denied";
                    if (event.error === 'no-speech') errorMsg = "No speech detected. Try again.";

                    aiInput.placeholder = errorMsg;
                    setTimeout(() => aiInput.placeholder = "Type your message...", 3000);
                };
            };

            aiVoiceBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                if (isRecognizing) {
                    if (recognition) recognition.stop();
                } else {
                    try {
                        // Explicitly request permission to ensure browser prompt appears
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        stream.getTracks().forEach(track => track.stop()); // Close stream immediately

                        if (!recognition) initRecognition();
                        recognition.start();
                    } catch (err) {
                        console.error('Mic Permission/Start failed:', err);
                        aiInput.placeholder = "Microphone permission required";
                        setTimeout(() => aiInput.placeholder = "Type your message...", 3000);
                    }
                }
            });
        } else {
            console.warn('AI Voice: Not supported in this browser');
            aiVoiceBtn.style.display = 'none';
        }

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
                <div class="bot-msg-bubble" style="padding: 12px; border-radius: 12px; border-top-left-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #E2E8F0; max-width: 85%; transition: all 0.3s ease;">
                    <p class="bot-text" style="margin:0; font-size: 13px; line-height: 1.5; white-space: pre-wrap;"></p>
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
            ${isBot ? '<div style="width: 32px; height: 32px; border-radius: 50%; background: #2b3bb3; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="7" width="16" height="10" rx="3" stroke="currentColor" stroke-width="2"/><circle cx="9" cy="11.5" r="1" fill="currentColor"/><circle cx="15" cy="11.5" r="1" fill="currentColor"/><path d="M10 14C10.5 14.5 11.2 14.8 12 14.8C12.8 14.8 13.5 14.5 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>' : ''}
            <div class="${isBot ? 'bot-msg-bubble' : 'user-msg-bubble'}" style="padding: 12px; border-radius: 12px; border-top-${isBot ? 'left' : 'right'}-radius: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid ${isBot ? '#E2E8F0' : '#2b3bb3'}; max-width: 85%; transition: all 0.3s ease;">
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
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #2b3bb3; color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="7" width="16" height="10" rx="3" stroke="currentColor" stroke-width="2"/>
                    <circle cx="9" cy="11.5" r="1" fill="currentColor"/>
                    <circle cx="15" cy="11.5" r="1" fill="currentColor"/>
                    <path d="M10 14C10.5 14.5 11.2 14.8 12 14.8C12.8 14.8 13.5 14.5 14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="bot-msg-bubble" style="padding: 12px; border-radius: 12px; border: 1px solid #E2E8F0; transition: all 0.3s ease;">
                <div style="display: flex; gap: 4px;">
                    <div style="width:4px; height:4px; border-radius:50%; background:currentColor; animation: ai-bounce 1s infinite"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:currentColor; animation: ai-bounce 1s infinite 0.2s"></div>
                    <div style="width:4px; height:4px; border-radius:50%; background:currentColor; animation: ai-bounce 1s infinite 0.4s"></div>
                </div>
            </div>
        `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
            return div;
        }

        // ========== TRAINING PANEL LOGIC (Super Admin) ==========
        const trainBtn = document.getElementById('ai-train-btn');
        const trainPanel = document.getElementById('ai-training-panel');
        const trainClose = document.getElementById('ai-train-close');
        const trainSaveBtn = document.getElementById('train-save-btn');
        const trainList = document.getElementById('training-list');
        const csrfToken = '{{ csrf_token() }}';

        if (trainBtn && trainPanel) {
            trainBtn.addEventListener('click', () => {
                trainPanel.classList.add('active');
                loadTrainingEntries();
            });

            trainClose.addEventListener('click', () => {
                trainPanel.classList.remove('active');
            });

            trainSaveBtn.addEventListener('click', async () => {
                const q = document.getElementById('train-question').value.trim();
                const a = document.getElementById('train-answer').value.trim();
                if (!q || !a) return alert('Both question and answer are required.');

                trainSaveBtn.disabled = true;
                trainSaveBtn.textContent = 'Saving...';

                try {
                    const res = await fetch("{{ route('ai.training.store') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ question: q, answer: a })
                    });
                    const data = await res.json();
                    if (data.success) {
                        document.getElementById('train-question').value = '';
                        document.getElementById('train-answer').value = '';
                        loadTrainingEntries();
                    } else {
                        alert(data.error || 'Failed to save.');
                    }
                } catch (err) {
                    alert('Error saving training entry.');
                } finally {
                    trainSaveBtn.disabled = false;
                    trainSaveBtn.textContent = '➕ Add Training Entry';
                }
            });

            async function loadTrainingEntries() {
                try {
                    const res = await fetch("{{ route('ai.training.index') }}");
                    const entries = await res.json();

                    if (entries.length === 0) {
                        trainList.innerHTML = '<div class="training-empty">No training entries yet.<br>Add Q&A pairs above to teach the bot!</div>';
                        return;
                    }

                    trainList.innerHTML = entries.map(e => `
                        <div class="training-entry ${e.is_active ? '' : 'inactive'}" data-id="${e.id}">
                            <div class="training-entry-q">Q: ${escapeHtml(e.question)}</div>
                            <div class="training-entry-a">A: ${escapeHtml(e.answer)}</div>
                            <div class="training-entry-actions">
                                <button class="train-btn-toggle" onclick="toggleTraining(${e.id}, ${e.is_active ? 'false' : 'true'})">${e.is_active ? '⏸ Disable' : '▶ Enable'}</button>
                                <button class="train-btn-edit" onclick="editTraining(${e.id})">✏️ Edit</button>
                                <button class="train-btn-delete" onclick="deleteTraining(${e.id})">🗑 Delete</button>
                            </div>
                        </div>
                    `).join('');
                } catch (err) {
                    trainList.innerHTML = '<div class="training-empty">Error loading entries.</div>';
                }
            }

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            window.toggleTraining = async function (id, newState) {
                try {
                    await fetch(`/ai/training/${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ is_active: newState })
                    });
                    loadTrainingEntries();
                } catch (err) {
                    alert('Error toggling entry.');
                }
            };

            window.editTraining = async function (id) {
                const entry = trainList.querySelector(`[data-id="${id}"]`);
                if (!entry) return;

                const currentQ = entry.querySelector('.training-entry-q').textContent.replace('Q: ', '');
                const currentA = entry.querySelector('.training-entry-a').textContent.replace('A: ', '');

                const newQ = prompt('Edit Question:', currentQ);
                if (newQ === null) return;
                const newA = prompt('Edit Answer:', currentA);
                if (newA === null) return;

                try {
                    await fetch(`/ai/training/${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ question: newQ, answer: newA })
                    });
                    loadTrainingEntries();
                } catch (err) {
                    alert('Error updating entry.');
                }
            };

            window.deleteTraining = async function (id) {
                if (!confirm('Delete this training entry?')) return;

                try {
                    await fetch(`/ai/training/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    loadTrainingEntries();
                } catch (err) {
                    alert('Error deleting entry.');
                }
            };
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