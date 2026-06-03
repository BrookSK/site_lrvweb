<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="<?= $portfolio ? '/admin/portfolio/' . $portfolio['id'] : '/admin/portfolio' ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= \Core\View::csrf() ?>
            <?php if ($portfolio): ?><?= \Core\View::method('PUT') ?><?php endif; ?>

            <!-- ASSISTENTE DE VOZ -->
            <div class="p-5 bg-purple-900/20 border border-purple-700 rounded-xl mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-white font-semibold text-sm flex items-center gap-2"><span class="text-lg">🎙️</span> Assistente de Voz com IA</h4>
                        <p class="text-xs text-purple-300 mt-0.5">Descreva o projeto falando. A IA preenche tudo pra você.</p>
                    </div>
                    <div id="voice-status" class="text-xs text-gray-500">Pronto</div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" id="btn-record" onclick="toggleRecording()" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
                        <svg id="mic-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        <span id="btn-record-text">Gravar</span>
                    </button>
                    <button type="button" id="btn-ai-fill" onclick="sendToAI()" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2 hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Preencher com IA
                    </button>
                </div>

                <div id="voice-transcript" class="hidden mt-3 p-3 bg-gray-900 border border-gray-700 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Transcrição:</p>
                    <p id="transcript-text" class="text-sm text-gray-200 leading-relaxed"></p>
                </div>

                <div id="ai-loading" class="hidden mt-3 flex items-center gap-2 text-purple-300 text-sm">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    A IA está preenchendo os campos...
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nome do Projeto *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($portfolio['name'] ?? '') ?>" required placeholder="Ex: E-commerce Braziliana Shop" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Categoria</label>
                    <select name="category_id" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500">
                        <option value="">Selecione...</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($portfolio['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Cliente vinculado</label>
                    <select name="client_id" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500">
                        <option value="">Nenhum</option>
                        <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($portfolio['client_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">URL do Projeto</label>
                    <input type="url" name="url" value="<?= htmlspecialchars($portfolio['url'] ?? '') ?>" placeholder="https://exemplo.com.br" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Descrição</label>
                <textarea name="description" rows="4" placeholder="Descreva o projeto, o que foi feito, resultado entregue..." class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500"><?= htmlspecialchars($portfolio['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tecnologias utilizadas</label>
                <input type="text" name="technologies" value="<?= htmlspecialchars($portfolio['technologies'] ?? '') ?>" placeholder="PHP, MySQL, Tailwind, JavaScript, API REST..." class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500">
                <p class="text-xs text-gray-500 mt-1">Separe por vírgula. Aparece como tags no site.</p>
            </div>

            <!-- Imagem -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Imagem de Capa</label>
                <?php if (!empty($portfolio['image_cover'])): ?>
                    <div class="mb-3">
                        <img src="<?= htmlspecialchars($portfolio['image_cover']) ?>" alt="Capa atual" class="h-32 rounded-lg object-cover border border-gray-700">
                    </div>
                <?php endif; ?>
                <input type="file" name="image_cover" accept="image/*" class="text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:text-sm file:font-medium hover:file:bg-purple-700 file:cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Recomendado: 1200x800px. JPG, PNG ou WebP.</p>
            </div>

            <!-- Opções -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Data de conclusão</label>
                    <input type="date" name="completed_at" value="<?= $portfolio['completed_at'] ?? '' ?>" class="w-full px-3 py-2.5 border border-gray-700 rounded-lg bg-gray-900 text-white text-sm focus:border-purple-500">
                </div>
                <div class="flex items-end gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" <?= ($portfolio['is_featured'] ?? 0) ? 'checked' : '' ?> class="rounded border-gray-600 bg-gray-900 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm text-gray-300">Destaque</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?= ($portfolio['is_active'] ?? 1) ? 'checked' : '' ?> class="rounded border-gray-600 bg-gray-900 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm text-gray-300">Ativo no site</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Salvar Projeto</button>
                <a href="/admin/portfolio" class="px-6 py-2.5 border border-gray-700 text-gray-300 text-sm rounded-lg hover:bg-gray-700 transition">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Info para o site -->
    <div class="mt-6 p-4 bg-blue-900/20 border border-blue-800 rounded-xl">
        <p class="text-xs text-blue-300"><strong>💡 Dica:</strong> Este projeto aparece no site institucional na seção de Portfólio e também pode ser vinculado aos orçamentos como "Projetos Recentes".</p>
    </div>
</div>

<!-- JavaScript: Gravação de voz + IA -->
<script>
let recognition = null;
let isRecording = false;
let transcript = '';

function toggleRecording() {
    if (isRecording) {
        stopRecording();
    } else {
        startRecording();
    }
}

function startRecording() {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        alert('Seu navegador não suporta gravação de voz. Use o Chrome.');
        return;
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'pt-BR';
    recognition.continuous = true;
    recognition.interimResults = true;

    transcript = '';

    recognition.onresult = (event) => {
        let finalTranscript = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                finalTranscript += event.results[i][0].transcript + ' ';
            }
        }
        if (finalTranscript) transcript += finalTranscript;

        document.getElementById('transcript-text').textContent = transcript || '(ouvindo...)';
        document.getElementById('voice-transcript').classList.remove('hidden');
    };

    recognition.onerror = (event) => {
        console.error('Erro no reconhecimento:', event.error);
        stopRecording();
    };

    recognition.onend = () => {
        if (isRecording) recognition.start(); // Continua gravando
    };

    recognition.start();
    isRecording = true;

    document.getElementById('btn-record').classList.remove('bg-purple-600', 'hover:bg-purple-700');
    document.getElementById('btn-record').classList.add('bg-red-600', 'hover:bg-red-700');
    document.getElementById('btn-record-text').textContent = 'Parar';
    document.getElementById('voice-status').textContent = '🔴 Gravando...';
    document.getElementById('voice-status').classList.add('text-red-400');
}

function stopRecording() {
    if (recognition) {
        isRecording = false;
        recognition.stop();
        recognition = null;
    }

    document.getElementById('btn-record').classList.add('bg-purple-600', 'hover:bg-purple-700');
    document.getElementById('btn-record').classList.remove('bg-red-600', 'hover:bg-red-700');
    document.getElementById('btn-record-text').textContent = 'Gravar';
    document.getElementById('voice-status').textContent = '✓ Gravação finalizada';
    document.getElementById('voice-status').classList.remove('text-red-400');
    document.getElementById('voice-status').classList.add('text-green-400');

    if (transcript.trim()) {
        document.getElementById('btn-ai-fill').classList.remove('hidden');
    }
}

async function sendToAI() {
    if (!transcript.trim()) return;

    const loading = document.getElementById('ai-loading');
    const btn = document.getElementById('btn-ai-fill');
    loading.classList.remove('hidden');
    btn.classList.add('hidden');

    try {
        const response = await fetch('/admin/portfolio/ia-preencher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({ transcript: transcript.trim() })
        });

        const data = await response.json();

        if (data.success && data.data) {
            const d = data.data;
            if (d.name) document.querySelector('input[name="name"]').value = d.name;
            if (d.description) document.querySelector('textarea[name="description"]').value = d.description;
            if (d.technologies) document.querySelector('input[name="technologies"]').value = d.technologies;
            if (d.url) document.querySelector('input[name="url"]').value = d.url;

            loading.classList.add('hidden');
            document.getElementById('voice-status').textContent = '✅ Campos preenchidos pela IA!';
            document.getElementById('voice-status').className = 'text-xs text-green-400';
        } else {
            throw new Error(data.message || 'Erro ao processar');
        }
    } catch (err) {
        loading.classList.add('hidden');
        btn.classList.remove('hidden');
        document.getElementById('voice-status').textContent = '❌ Erro: ' + err.message;
        document.getElementById('voice-status').className = 'text-xs text-red-400';
    }
}
</script>
