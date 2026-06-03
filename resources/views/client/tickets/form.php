<div class="max-w-2xl">
    <a href="/cliente/chamados" class="text-sm text-purple-600 hover:underline mb-4 inline-block">← Voltar</a>
    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Novo Chamado</h3>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
        <form action="/cliente/chamados" method="POST" class="space-y-5">
            <?= \Core\View::csrf() ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assunto *</label>
                <input type="text" name="subject" required placeholder="Resumo do problema ou solicitação" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="">Geral</option>
                        <option value="bug">Bug / Problema</option>
                        <option value="duvida">Dúvida</option>
                        <option value="melhoria">Melhoria / Sugestão</option>
                        <option value="financeiro">Financeiro</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm">
                        <option value="low">Baixa</option>
                        <option value="medium" selected>Média</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição *</label>
                <textarea name="description" rows="6" required placeholder="Descreva detalhadamente o que está acontecendo..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 dark:text-white text-sm"></textarea>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">Enviar Chamado</button>
        </form>
    </div>
</div>
