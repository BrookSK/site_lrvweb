<!-- Chatbot FAQ -->
<div id="chatbot-widget" class="fixed bottom-8 right-8 z-50">
    <!-- Botão de abrir -->
    <button id="chatbot-toggle" onclick="toggleChatbot()" class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:scale-110 transition-all duration-300 relative">
        <svg id="chatbot-icon-open" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg id="chatbot-icon-close" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-black animate-pulse"></span>
    </button>

    <!-- Janela do chat -->
    <div id="chatbot-window" class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-gray-900 border border-white/10 rounded-2xl shadow-2xl shadow-purple-900/20 overflow-hidden" style="max-height: 500px;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-700 to-purple-900 p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                <span class="text-lg">🤖</span>
            </div>
            <div>
                <p class="text-white font-semibold text-sm">LRV Web</p>
                <p class="text-purple-200 text-xs">Assistente Virtual</p>
            </div>
        </div>

        <!-- Chat body -->
        <div id="chatbot-body" class="p-4 overflow-y-auto" style="max-height: 340px;">
            <!-- Mensagem inicial -->
            <div class="mb-4">
                <div class="bg-white/5 border border-white/10 rounded-xl rounded-tl-none p-3 max-w-[85%]">
                    <p class="text-gray-300 text-sm" id="chatbot-greeting">Olá! 👋 Como posso ajudar você? Selecione uma opção:</p>
                </div>
            </div>

            <!-- Opções -->
            <div id="chatbot-options" class="space-y-2">
            </div>

            <!-- Respostas dinâmicas -->
            <div id="chatbot-responses"></div>
        </div>
    </div>
</div>

<script>
const chatbotData = {
    pt: {
        greeting: 'Olá! 👋 Como posso ajudar você? Selecione uma opção:',
        options: [
            { q: 'Quanto custa um site?', a: 'Cada projeto é único! O valor depende da complexidade, funcionalidades e prazo. Solicite um orçamento personalizado sem compromisso e respondemos em até 2 horas. 💬' },
            { q: 'Quais serviços vocês oferecem?', a: 'Oferecemos: ☁️ Hospedagem Cloud/VPS, 🌐 Criação de Sites, ⚙️ Sistemas Sob Medida, 🛒 E-commerce, 💬 Automação WhatsApp, 📱 Social Media e mais!' },
            { q: 'Como funciona a hospedagem?', a: 'Nossa hospedagem VPS tem servidores dedicados com NVMe SSD, proteção DDoS, 99.9% uptime e suporte técnico. Planos a partir de R$ 90/mês. Acesse cloud.lrvweb.com.br para mais detalhes!' },
            { q: 'Qual o prazo de entrega?', a: 'Depende do projeto: Sites simples de 7 a 15 dias, sistemas de 30 a 90 dias. Sempre definimos o prazo na proposta antes de iniciar. ⏱️' },
            { q: 'Como solicitar um orçamento?', a: 'É simples! Clique em "Solicitar Orçamento" ou vá na página de Contato. Preencha o formulário e respondemos em até 2 horas no horário comercial. 🚀' },
            { q: 'Vocês dão suporte após a entrega?', a: 'Sim! Todos os projetos incluem suporte pós-entrega. Também oferecemos planos de manutenção contínua para quem quer acompanhamento permanente. 🤝' },
            { q: 'Falar com alguém pelo WhatsApp', a: 'whatsapp' }
        ],
        back: '← Voltar às opções'
    },
    en: {
        greeting: 'Hello! 👋 How can I help you? Select an option:',
        options: [
            { q: 'How much does a website cost?', a: 'Every project is unique! The price depends on complexity, features and timeline. Request a personalized quote with no commitment and we respond within 2 hours. 💬' },
            { q: 'What services do you offer?', a: 'We offer: ☁️ Cloud/VPS Hosting, 🌐 Website Creation, ⚙️ Custom Systems, 🛒 E-commerce, 💬 WhatsApp Automation, 📱 Social Media and more!' },
            { q: 'How does the hosting work?', a: 'Our VPS hosting has dedicated servers with NVMe SSD, DDoS protection, 99.9% uptime and technical support. Plans starting at R$ 90/month. Visit cloud.lrvweb.com.br for details!' },
            { q: 'What is the delivery time?', a: 'Depends on the project: Simple websites 7 to 15 days, systems 30 to 90 days. We always define the deadline in the proposal before starting. ⏱️' },
            { q: 'How to request a quote?', a: "Easy! Click 'Request a Quote' or go to the Contact page. Fill the form and we respond within 2 hours during business hours. 🚀" },
            { q: 'Do you provide post-delivery support?', a: 'Yes! All projects include post-delivery support. We also offer ongoing maintenance plans for those who want permanent monitoring. 🤝' },
            { q: 'Talk to someone on WhatsApp', a: 'whatsapp' }
        ],
        back: '← Back to options'
    },
    es: {
        greeting: '¡Hola! 👋 ¿Cómo puedo ayudarte? Selecciona una opción:',
        options: [
            { q: '¿Cuánto cuesta un sitio web?', a: '¡Cada proyecto es único! El precio depende de la complejidad, funcionalidades y plazo. Solicita un presupuesto personalizado sin compromiso y respondemos en hasta 2 horas. 💬' },
            { q: '¿Qué servicios ofrecen?', a: 'Ofrecemos: ☁️ Hospedaje Cloud/VPS, 🌐 Creación de Sitios, ⚙️ Sistemas a Medida, 🛒 E-commerce, 💬 Automatización WhatsApp, 📱 Social Media y más!' },
            { q: '¿Cómo funciona el hospedaje?', a: 'Nuestro hospedaje VPS tiene servidores dedicados con NVMe SSD, protección DDoS, 99.9% uptime y soporte técnico. Planes desde R$ 90/mes. ¡Visita cloud.lrvweb.com.br!' },
            { q: '¿Cuál es el plazo de entrega?', a: 'Depende del proyecto: Sitios simples de 7 a 15 días, sistemas de 30 a 90 días. Siempre definimos el plazo en la propuesta. ⏱️' },
            { q: '¿Cómo solicitar un presupuesto?', a: '¡Simple! Haz clic en "Solicitar Presupuesto" o ve a la página de Contacto. Completa el formulario y respondemos en hasta 2 horas. 🚀' },
            { q: '¿Dan soporte después de la entrega?', a: '¡Sí! Todos los proyectos incluyen soporte post-entrega. También ofrecemos planes de mantenimiento continuo. 🤝' },
            { q: 'Hablar por WhatsApp', a: 'whatsapp' }
        ],
        back: '← Volver a opciones'
    }
};

const locale = document.documentElement.lang || 'pt';
const lang = locale.substring(0, 2);
const data = chatbotData[lang] || chatbotData.pt;

function toggleChatbot() {
    const win = document.getElementById('chatbot-window');
    const iconOpen = document.getElementById('chatbot-icon-open');
    const iconClose = document.getElementById('chatbot-icon-close');
    const isHidden = win.classList.contains('hidden');

    win.classList.toggle('hidden');
    iconOpen.classList.toggle('hidden', isHidden);
    iconClose.classList.toggle('hidden', !isHidden);

    if (isHidden) renderOptions();
}

function renderOptions() {
    const container = document.getElementById('chatbot-options');
    const greeting = document.getElementById('chatbot-greeting');
    const responses = document.getElementById('chatbot-responses');

    greeting.textContent = data.greeting;
    responses.innerHTML = '';
    container.innerHTML = '';

    data.options.forEach((opt, i) => {
        const btn = document.createElement('button');
        btn.className = 'w-full text-left px-3 py-2.5 bg-purple-600/10 border border-purple-500/20 rounded-xl text-sm text-purple-300 hover:bg-purple-600/20 hover:border-purple-500/40 transition-all duration-200';
        btn.textContent = opt.q;
        btn.onclick = () => handleAnswer(opt, i);
        container.appendChild(btn);
    });
}

function handleAnswer(opt, index) {
    if (opt.a === 'whatsapp') {
        window.open('https://wa.me/5517988093160?text=Olá, vim pelo site e gostaria de mais informações', '_blank');
        return;
    }

    const container = document.getElementById('chatbot-options');
    const responses = document.getElementById('chatbot-responses');

    container.innerHTML = '';
    responses.innerHTML = `
        <div class="mb-3 flex justify-end">
            <div class="bg-purple-600/30 border border-purple-500/30 rounded-xl rounded-tr-none p-3 max-w-[85%]">
                <p class="text-white text-sm">${opt.q}</p>
            </div>
        </div>
        <div class="mb-4">
            <div class="bg-white/5 border border-white/10 rounded-xl rounded-tl-none p-3 max-w-[85%]">
                <p class="text-gray-300 text-sm">${opt.a}</p>
            </div>
        </div>
        <button onclick="renderOptions()" class="text-purple-400 text-xs hover:text-purple-300 transition">${data.back}</button>
    `;

    document.getElementById('chatbot-body').scrollTop = 9999;
}

// Inicializa
document.addEventListener('DOMContentLoaded', renderOptions);
</script>
