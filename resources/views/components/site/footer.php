<?php $locale = \Core\I18n::getLocale(); ?>

<footer class="relative bg-black border-t border-white/5 overflow-hidden">
    <!-- Glow decorativo -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-1 bg-gradient-to-r from-transparent via-purple-600 to-transparent"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-32 bg-purple-600/5 blur-3xl rounded-full"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">L</span>
                    </div>
                    <span class="text-xl font-bold text-white">LRV<span class="text-purple-400">Web</span></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">Transformamos ideias em soluções digitais de alto impacto. Hospedagem premium, desenvolvimento web e sistemas sob medida.</p>
                <!-- Social -->
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="GitHub">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Serviços -->
            <div>
                <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Serviços</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="/<?= $locale ?>/hospedagem" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Hospedagem de Sites</a></li>
                    <li><a href="/<?= $locale ?>/servicos" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Criação de Sites</a></li>
                    <li><a href="/<?= $locale ?>/servicos" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>E-commerce</a></li>
                    <li><a href="/<?= $locale ?>/servicos" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Sistemas Sob Medida</a></li>
                    <li><a href="/<?= $locale ?>/servicos" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Automação WhatsApp</a></li>
                </ul>
            </div>

            <!-- Empresa -->
            <div>
                <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Empresa</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="/<?= $locale ?>/sobre" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Sobre Nós</a></li>
                    <li><a href="/<?= $locale ?>/portfolio" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Portfólio</a></li>
                    <li><a href="/<?= $locale ?>/blog" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Blog</a></li>
                    <li><a href="/<?= $locale ?>/contato" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Contato</a></li>
                    <li><a href="/login" class="text-gray-400 hover:text-purple-400 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-purple-600 rounded-full"></span>Área do Cliente</a></li>
                </ul>
            </div>

            <!-- Contato -->
            <div>
                <h4 class="font-semibold text-white mb-5 text-sm uppercase tracking-wider">Contato</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-center gap-3"><svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>contato@lrvweb.com.br</li>
                </ul>

                <!-- Newsletter mini -->
                <div class="mt-6">
                    <p class="text-xs text-gray-500 mb-2">Receba novidades</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Seu e-mail" class="flex-1 px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:border-purple-500">
                        <button class="px-3 py-2 bg-purple-600 hover:bg-purple-500 rounded-lg transition-colors"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="border-t border-white/5 mt-12 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">&copy; <?= date('Y') ?> LRV Web. <?= \Core\I18n::get('all_rights_reserved') ?></p>
            <div class="flex gap-6 text-sm text-gray-500">
                <a href="/<?= $locale ?>/pagina/politica-de-privacidade" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('privacy_policy') ?></a>
                <a href="/<?= $locale ?>/pagina/termos-de-uso" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('terms_of_use') ?></a>
                <a href="/<?= $locale ?>/pagina/politica-de-cookies" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('cookie_policy') ?></a>
            </div>
        </div>
    </div>
</footer>
