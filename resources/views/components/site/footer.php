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
                    <a href="https://www.instagram.com/lrvweb.com.br" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/company/lrv-web/" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61553861960826" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-purple-600 hover:border-purple-600 transition-all duration-300" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://youtube.com/@LRVWeb" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-red-600 hover:border-red-600 transition-all duration-300" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://wa.me/5517988093160" target="_blank" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-green-600 hover:border-green-600 transition-all duration-300" aria-label="WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
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
                    <li class="flex items-center gap-3"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg><a href="https://wa.me/5517988093160" target="_blank" class="hover:text-green-400 transition">(17) 98809-3160</a></li>
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
                <?php
                $policyLinks = [
                    'pt' => ['politica-de-privacidade', 'termos-de-uso', 'politica-de-cookies'],
                    'en' => ['en-privacy-policy', 'en-terms-of-use', 'en-cookie-policy'],
                    'es' => ['es-politica-de-privacidad', 'es-terminos-de-uso', 'es-politica-de-cookies'],
                ];
                $currentLinks = $policyLinks[$locale] ?? $policyLinks['pt'];
                ?>
                <a href="/<?= $locale ?>/pagina/<?= $currentLinks[0] ?>" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('privacy_policy') ?></a>
                <a href="/<?= $locale ?>/pagina/<?= $currentLinks[1] ?>" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('terms_of_use') ?></a>
                <a href="/<?= $locale ?>/pagina/<?= $currentLinks[2] ?>" class="hover:text-purple-400 transition-colors"><?= \Core\I18n::get('cookie_policy') ?></a>
            </div>
        </div>
    </div>
</footer>
