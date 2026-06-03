<div class="flex flex-col h-[calc(100vh-130px)]">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <div>
            <h3 class="text-lg font-semibold text-white">Assistente IA</h3>
            <p class="text-xs text-gray-500">Com contexto dos seus projetos, clientes e orçamentos</p>
        </div>
        <div class="flex items-center gap-3">
            <select id="ai-model" class="px-3 py-1.5 bg-gray-900 border border-gray-700 rounded-lg text-xs text-white">
                <option value="gpt-4o">GPT-4o</option>
                <option value="gpt-4">GPT-4</option>
                <option value="gpt-4o-mini">GPT-4o Mini</option>
                <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
            </select>
            <button onclick="clearChat()" class="px-3 py-1.5 border border-gray-700 text-gray-400 text-xs rounded-lg hover:bg-gray-700 hover:text-white transition">Nova conversa</button>
        </div>
    </div>

    <!-- Chat Messages -->
    <div id="chat-messages" class="flex-1 overflow-y-auto rounded-xl bg-gray-900/50 border border-gray-800 p-4 space-y-4 mb-4">
        <!-- Mensagem inicial -->
        <div class="flex gap-3" id="welcome-msg">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-sm">🤖</span></div>
            <div class="bg-gray-800 rounded-xl rounded-tl-none p-4 max-w-[80%]">
                <p class="text-sm text-gray-300">Olá! Sou o assistente IA da LRV Web. Tenho acesso ao contexto dos seus clientes, projetos e orçamentos. Como posso ajudar?</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button onclick="quickPrompt('Gere um texto de proposta comercial para um novo cliente')" class="px-3 py-1.5 bg-purple-600/20 border border-purple-500/30 rounded-lg text-xs text-purple-300 hover:bg-purple-600/30 transition">📝 Texto de proposta</button>
                    <button onclick="quickPrompt('Sugira ideias de posts para o blog sobre hospedagem')" class="px-3 py-1.5 bg-purple-600/20 border border-purple-500/30 rounded-lg text-xs text-purple-300 hover:bg-purple-600/30 transition">💡 Ideias de blog</button>
                    <button onclick="quickPrompt('Me ajude a responder um cliente que pediu desconto')" class="px-3 py-1.5 bg-purple-600/20 border border-purple-500/30 rounded-lg text-xs text-purple-300 hover:bg-purple-600/30 transition">💬 Responder cliente</button>
                    <button onclick="quickPrompt('Resuma os projetos ativos e pendências')" class="px-3 py-1.5 bg-purple-600/20 border border-purple-500/30 rounded-lg text-xs text-purple-300 hover:bg-purple-600/30 transition">📊 Resumo projetos</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Input -->
    <div class="flex-shrink-0 bg-gray-900 border border-gray-700 rounded-xl p-3">
        <div class="flex items-end gap-3">
            <div class="flex-1 relative">
                <textarea id="chat-input" rows="1" placeholder="Pergunte qualquer coisa..." class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white text-sm placeholder-gray-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 resize-none" onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage()}" oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px'"></textarea>
            </div>
            <!-- Mic button -->
            <button id="btn-mic" onclick="toggleMic()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-800 border border-gray-700 text-gray-400 hover:text-white hover:bg-gray-700 transition" title="Gravar áudio">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
            </button>
            <!-- Send button -->
            <button onclick="sendMessage()" id="btn-send" class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
let chatHistory = [];
let isRecording = false;
let mediaRecorder = null;
let audioChunks = [];

function quickPrompt(text) {
    document.getElementById('chat-input').value = text;
    sendMessage();
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    input.value = '';
    input.style.height = 'auto';

    // Remove welcome
    const welcome = document.getElementById('welcome-msg');
    if (welcome) welcome.remove();

    // Adiciona mensagem do usuário
    appendMessage('user', message);
    chatHistory.push({ role: 'user', content: message });

    // Loading
    const loadingId = 'loading-' + Date.now();
    appendLoading(loadingId);

    try {
        const model = document.getElementById('ai-model').value;
        const csrfToken = '<?= \Core\Session::getInstance()->getCsrfToken() ?>';

        const res = await fetch('/admin/ia/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': csrfToken },
            body: JSON.stringify({ message, model, history: JSON.stringify(chatHistory.slice(-20)) })
        });

        const data = await res.json();
        removeLoading(loadingId);

        if (data.success && data.data.reply) {
            appendMessage('assistant', data.data.reply);
            chatHistory.push({ role: 'assistant', content: data.data.reply });
        } else {
            appendMessage('assistant', '❌ Erro: ' + (data.message || 'Sem resposta'));
        }
    } catch (err) {
        removeLoading(loadingId);
        appendMessage('assistant', '❌ Erro de conexão: ' + err.message);
    }
}

function appendMessage(role, content) {
    const container = document.getElementById('chat-messages');
    const isUser = role === 'user';

    // Converte markdown básico para HTML
    let html = content;
    if (!isUser) {
        html = html.replace(/```(\w*)\n([\s\S]*?)```/g, '<pre class="bg-gray-900 rounded-lg p-3 mt-2 mb-2 overflow-x-auto text-xs"><code>$2</code></pre>');
        html = html.replace(/`([^`]+)`/g, '<code class="bg-gray-900 px-1.5 py-0.5 rounded text-purple-300 text-xs">$1</code>');
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong class="text-white">$1</strong>');
        html = html.replace(/\n/g, '<br>');
    }

    const msgHtml = isUser
        ? `<div class="flex gap-3 justify-end"><div class="bg-purple-600/30 border border-purple-500/30 rounded-xl rounded-tr-none p-4 max-w-[80%]"><p class="text-sm text-white whitespace-pre-wrap">${escapeHtml(content)}</p></div><div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-xs font-bold">${'<?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>'}</span></div></div>`
        : `<div class="flex gap-3"><div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-sm">🤖</span></div><div class="bg-gray-800 rounded-xl rounded-tl-none p-4 max-w-[80%]"><div class="text-sm text-gray-300 leading-relaxed">${html}</div></div></div>`;

    container.insertAdjacentHTML('beforeend', msgHtml);
    container.scrollTop = container.scrollHeight;
}

function appendLoading(id) {
    const container = document.getElementById('chat-messages');
    container.insertAdjacentHTML('beforeend', `<div id="${id}" class="flex gap-3"><div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-sm">🤖</span></div><div class="bg-gray-800 rounded-xl rounded-tl-none p-4"><div class="flex items-center gap-2"><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"></div><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay:0.1s"></div><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay:0.2s"></div></div></div></div>`);
    container.scrollTop = container.scrollHeight;
}

function removeLoading(id) {
    document.getElementById(id)?.remove();
}

function clearChat() {
    chatHistory = [];
    document.getElementById('chat-messages').innerHTML = `<div class="flex gap-3" id="welcome-msg"><div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-sm">🤖</span></div><div class="bg-gray-800 rounded-xl rounded-tl-none p-4"><p class="text-sm text-gray-300">Conversa limpa! Como posso ajudar?</p></div></div>`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// === MICROFONE ===
async function toggleMic() {
    if (isRecording) {
        stopMic();
    } else {
        startMic();
    }
}

async function startMic() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) audioChunks.push(e.data); };
        mediaRecorder.onstop = () => stream.getTracks().forEach(t => t.stop());
        mediaRecorder.start();
        isRecording = true;
        document.getElementById('btn-mic').classList.add('bg-red-600', 'border-red-600', 'text-white');
        document.getElementById('btn-mic').classList.remove('bg-gray-800', 'border-gray-700', 'text-gray-400');
    } catch (err) {
        alert('Microfone não disponível: ' + err.message);
    }
}

async function stopMic() {
    if (mediaRecorder) mediaRecorder.stop();
    isRecording = false;
    document.getElementById('btn-mic').classList.remove('bg-red-600', 'border-red-600', 'text-white');
    document.getElementById('btn-mic').classList.add('bg-gray-800', 'border-gray-700', 'text-gray-400');

    // Espera o stop terminar
    await new Promise(r => setTimeout(r, 300));

    if (audioChunks.length === 0) return;

    const blob = new Blob(audioChunks, { type: 'audio/webm' });
    const formData = new FormData();
    formData.append('audio', blob, 'voice.webm');
    formData.append('_token', '<?= \Core\Session::getInstance()->getCsrfToken() ?>');

    try {
        const res = await fetch('/admin/ia/transcribe', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData });
        const data = await res.json();
        if (data.success && data.data.text) {
            document.getElementById('chat-input').value = data.data.text;
            document.getElementById('chat-input').focus();
        }
    } catch (err) {
        console.error('Transcrição falhou:', err);
    }
}
</script>
