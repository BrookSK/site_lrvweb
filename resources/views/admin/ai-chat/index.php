<div class="flex h-[calc(100vh-130px)] gap-4">
    <!-- SIDEBAR DE CONVERSAS -->
    <div class="w-72 flex-shrink-0 flex flex-col bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <!-- Novo chat -->
        <div class="p-3 border-b border-gray-800">
            <button onclick="document.getElementById('modal-new-chat').classList.remove('hidden')" class="w-full px-3 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Nova Conversa
            </button>
        </div>

        <!-- Lista de chats -->
        <div class="flex-1 overflow-y-auto p-2 space-y-1">
            <?php foreach ($chats as $chat): ?>
            <a href="/admin/ia?chat=<?= $chat['id'] ?>" class="block px-3 py-2.5 rounded-lg text-sm transition truncate <?= ($chatId ?? 0) == $chat['id'] ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
                <div class="flex items-center gap-2">
                    <?php if ($chat['project_id']): ?>
                        <i data-lucide="folder" class="w-3.5 h-3.5 flex-shrink-0 text-purple-400"></i>
                    <?php else: ?>
                        <i data-lucide="message-circle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    <?php endif; ?>
                    <span class="truncate"><?= htmlspecialchars($chat['title']) ?></span>
                </div>
                <p class="text-[10px] text-gray-600 mt-0.5 ml-5"><?= date('d/m H:i', strtotime($chat['updated_at'])) ?></p>
            </a>
            <?php endforeach; ?>
            <?php if (empty($chats)): ?>
                <p class="text-xs text-gray-600 text-center py-8">Nenhuma conversa.<br>Crie uma nova!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ÁREA DO CHAT -->
    <div class="flex-1 flex flex-col">
        <!-- Header do chat -->
        <?php if ($currentChat): ?>
        <div class="flex items-center justify-between mb-3 flex-shrink-0">
            <div>
                <h3 class="text-sm font-semibold text-white"><?= htmlspecialchars($currentChat['title']) ?></h3>
                <p class="text-[10px] text-gray-500"><?= $currentChat['project_id'] ? '📁 Vinculado a projeto' : '💬 Conversa geral' ?> · <?= $currentChat['model'] ?></p>
            </div>
            <div class="flex items-center gap-2">
                <select id="ai-model" class="px-2 py-1 bg-gray-900 border border-gray-700 rounded-lg text-[11px] text-white">
                    <?php foreach (['gpt-4o'=>'GPT-4o','gpt-4'=>'GPT-4','gpt-4o-mini'=>'GPT-4o Mini','gpt-3.5-turbo'=>'GPT-3.5'] as $k=>$v): ?>
                    <option value="<?= $k ?>" <?= ($currentChat['model'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="confirmDelete('/admin/ia/<?= $currentChat['id'] ?>')" class="p-1.5 text-gray-500 hover:text-red-400 transition" title="Excluir conversa"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto rounded-xl bg-gray-900/50 border border-gray-800 p-4 space-y-4 mb-3">
            <?php if ($currentChat && !empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <?php if ($msg['role'] === 'user'): ?>
                    <div class="flex gap-3 justify-end">
                        <div class="bg-purple-600/20 border border-purple-500/30 rounded-xl rounded-tr-none p-3 max-w-[80%]">
                            <p class="text-sm text-white whitespace-pre-wrap"><?= htmlspecialchars($msg['content']) ?></p>
                        </div>
                    </div>
                    <?php elseif ($msg['role'] === 'assistant'): ?>
                    <div class="flex gap-3">
                        <div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-xs">🤖</span></div>
                        <div class="bg-gray-800 rounded-xl rounded-tl-none p-3 max-w-[80%]">
                            <div class="text-sm text-gray-300 leading-relaxed prose-sm"><?= nl2br(htmlspecialchars($msg['content'])) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php elseif (!$currentChat): ?>
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <span class="text-5xl block mb-4">🤖</span>
                        <p class="text-gray-400 text-sm">Selecione uma conversa ou crie uma nova.</p>
                        <p class="text-gray-600 text-xs mt-2">Chats vinculados a projetos têm contexto completo do projeto.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex gap-3" id="welcome-msg">
                    <div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-white text-xs">🤖</span></div>
                    <div class="bg-gray-800 rounded-xl rounded-tl-none p-3">
                        <p class="text-sm text-gray-300">Olá! <?= $currentChat['project_id'] ? 'Estou com todo o contexto do projeto <strong>' . htmlspecialchars($currentChat['title']) . '</strong>. ' : '' ?>Como posso ajudar?</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Input -->
        <?php if ($currentChat): ?>
        <div class="flex-shrink-0 bg-gray-900 border border-gray-700 rounded-xl p-2.5">
            <div class="flex items-end gap-2">
                <textarea id="chat-input" rows="1" placeholder="Pergunte qualquer coisa..." class="flex-1 px-3 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white text-sm placeholder-gray-500 focus:border-purple-500 resize-none" onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage()}" oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,100)+'px'"></textarea>
                <button id="btn-mic" onclick="toggleMic()" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-800 border border-gray-700 text-gray-400 hover:text-white transition" title="Gravar"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg></button>
                <button onclick="sendMessage()" class="w-9 h-9 flex items-center justify-center rounded-lg bg-purple-600 hover:bg-purple-700 text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL: Nova Conversa -->
<div id="modal-new-chat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-md mx-4 border border-gray-700 shadow-2xl">
        <h3 class="text-lg font-semibold text-white mb-4">Nova Conversa</h3>
        <form action="/admin/ia/criar" method="POST" class="space-y-4">
            <?= \Core\View::csrf() ?>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tipo</label>
                <select name="project_id" class="w-full px-3 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                    <option value="">💬 Conversa Geral (contexto de tudo)</option>
                    <optgroup label="📁 Vinculado a Projeto">
                        <?php foreach ($projects as $p): ?>
                        <option value="<?= $p['id'] ?>">📁 <?= htmlspecialchars($p['name']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
                <p class="text-xs text-gray-500 mt-1">Chats de projeto têm acesso a: tarefas, orçamentos, documentos, financeiro e dados do cliente.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Modelo</label>
                <select name="model" class="w-full px-3 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white text-sm">
                    <option value="gpt-4o">GPT-4o (recomendado)</option>
                    <option value="gpt-4">GPT-4</option>
                    <option value="gpt-4o-mini">GPT-4o Mini (mais rápido)</option>
                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo (mais barato)</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-new-chat').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Criar</button>
            </div>
        </form>
    </div>
</div>

<?php if ($currentChat): ?>
<script>
const chatId = <?= $chatId ?>;
const csrfToken = '<?= \Core\Session::getInstance()->getCsrfToken() ?>';
let isRecording = false, mediaRecorder = null, audioChunks = [];

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;
    input.value = ''; input.style.height = 'auto';

    appendMsg('user', msg);
    const lid = 'l-' + Date.now();
    appendLoading(lid);

    try {
        const res = await fetch('/admin/ia/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': csrfToken },
            body: JSON.stringify({ message: msg, chat_id: chatId, model: document.getElementById('ai-model').value })
        });
        const data = await res.json();
        document.getElementById(lid)?.remove();
        if (data.success) appendMsg('assistant', data.data.reply);
        else appendMsg('assistant', '❌ ' + (data.message || 'Erro'));
    } catch (e) { document.getElementById(lid)?.remove(); appendMsg('assistant', '❌ ' + e.message); }
}

function appendMsg(role, content) {
    const c = document.getElementById('chat-messages');
    document.getElementById('welcome-msg')?.remove();
    let html = content.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>').replace(/`([^`]+)`/g, '<code class="bg-gray-900 px-1 rounded text-purple-300 text-xs">$1</code>').replace(/\n/g, '<br>');
    const el = role === 'user'
        ? `<div class="flex gap-3 justify-end"><div class="bg-purple-600/20 border border-purple-500/30 rounded-xl rounded-tr-none p-3 max-w-[80%]"><p class="text-sm text-white whitespace-pre-wrap">${content.replace(/</g,'&lt;')}</p></div></div>`
        : `<div class="flex gap-3"><div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0"><span class="text-xs">🤖</span></div><div class="bg-gray-800 rounded-xl rounded-tl-none p-3 max-w-[80%]"><div class="text-sm text-gray-300 leading-relaxed">${html}</div></div></div>`;
    c.insertAdjacentHTML('beforeend', el);
    c.scrollTop = c.scrollHeight;
}

function appendLoading(id) {
    const c = document.getElementById('chat-messages');
    c.insertAdjacentHTML('beforeend', `<div id="${id}" class="flex gap-3"><div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center"><span class="text-xs">🤖</span></div><div class="bg-gray-800 rounded-xl rounded-tl-none p-3"><div class="flex gap-1"><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce"></div><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay:.1s"></div><div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay:.2s"></div></div></div></div>`);
    c.scrollTop = c.scrollHeight;
}

async function toggleMic() {
    const micBtn = document.getElementById('btn-mic');
    const input = document.getElementById('chat-input');
    const sendBtn = document.querySelector('[onclick="sendMessage()"]');

    if (isRecording) {
        // Para gravação
        mediaRecorder?.stop();
        isRecording = false;

        // Visual: transcrevendo
        micBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
        micBtn.classList.remove('bg-red-600','text-white');
        micBtn.classList.add('bg-yellow-600','text-white');
        micBtn.disabled = true;
        input.placeholder = 'Transcrevendo áudio...';
        input.disabled = true;
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50');

        await new Promise(r => setTimeout(r, 300));

        if (!audioChunks.length) {
            resetMicUI();
            return;
        }

        // Envia para transcrição
        const blob = new Blob(audioChunks, { type: 'audio/webm' });
        const fd = new FormData();
        fd.append('audio', blob, 'v.webm');
        fd.append('_token', csrfToken);

        try {
            const res = await fetch('/admin/ia/transcribe', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
            const data = await res.json();
            if (data.success && data.data.text) {
                // Acrescenta texto (não substitui)
                const current = input.value.trim();
                input.value = current ? current + ' ' + data.data.text : data.data.text;
            }
        } catch (e) {
            console.error('Transcrição falhou:', e);
        }

        // Libera tudo
        resetMicUI();

    } else {
        // Inicia gravação
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = e => { if (e.data.size > 0) audioChunks.push(e.data); };
            mediaRecorder.onstop = () => stream.getTracks().forEach(t => t.stop());
            mediaRecorder.start();
            isRecording = true;

            // Visual: gravando
            micBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>';
            micBtn.classList.add('bg-red-600', 'text-white');
            micBtn.classList.remove('bg-gray-800', 'text-gray-400');
            input.placeholder = '🔴 Gravando... Clique no botão para parar';
            input.disabled = true;
            sendBtn.disabled = true;
            sendBtn.classList.add('opacity-50');
        } catch (e) {
            alert('Microfone não disponível: ' + e.message);
        }
    }
}

function resetMicUI() {
    const micBtn = document.getElementById('btn-mic');
    const input = document.getElementById('chat-input');
    const sendBtn = document.querySelector('[onclick="sendMessage()"]');

    micBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>';
    micBtn.classList.remove('bg-red-600', 'bg-yellow-600', 'text-white');
    micBtn.classList.add('bg-gray-800', 'text-gray-400');
    micBtn.disabled = false;
    input.disabled = false;
    input.placeholder = 'Pergunte qualquer coisa...';
    input.focus();
    sendBtn.disabled = false;
    sendBtn.classList.remove('opacity-50');
}

// Scroll to bottom on load
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
</script>
<?php endif; ?>
