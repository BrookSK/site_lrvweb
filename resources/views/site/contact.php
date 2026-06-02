<?php $locale = \Core\I18n::getLocale(); ?>

<!-- HERO -->
<section class="hero-gradient py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-purple-400 text-sm font-semibold uppercase tracking-wider animate-fade-up"><?= \Core\I18n::get('contact_badge') ?></span>
        <h1 class="text-4xl md:text-5xl font-bold mt-4 animate-fade-up delay-100"><?= \Core\I18n::get('contact_title') ?></h1>
        <p class="text-lg text-gray-300 mt-4 max-w-xl mx-auto animate-fade-up delay-200"><?= \Core\I18n::get('contact_subtitle') ?></p>
    </div>
</section>

<section class="section-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Formulário -->
            <div class="lg:col-span-2" data-animate="fade-left">
                <?php if ($success = \Core\Session::getInstance()->getFlash('success')): ?>
                    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <div class="card-premium">
                    <form action="/<?= $locale ?>/contato" method="POST" class="space-y-6">
                        <?= \Core\View::csrf() ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2"><?= \Core\I18n::get('your_name') ?> *</label>
                                <input type="text" name="name" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" placeholder="Seu nome completo">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2"><?= \Core\I18n::get('your_email') ?> *</label>
                                <input type="email" name="email" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" placeholder="seu@email.com">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2"><?= \Core\I18n::get('your_phone') ?></label>
                                <input type="tel" name="phone" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition" placeholder="(11) 99999-9999">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2"><?= \Core\I18n::get('subject') ?> *</label>
                                <select name="subject" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                                    <option value="" class="bg-gray-900"><?= \Core\I18n::get('contact_select') ?></option>
                                    <option value="Orçamento - Site" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_site') ?></option>
                                    <option value="Orçamento - Sistema" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_system') ?></option>
                                    <option value="Orçamento - E-commerce" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_ecommerce') ?></option>
                                    <option value="Hospedagem" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_hosting') ?></option>
                                    <option value="Suporte" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_support') ?></option>
                                    <option value="Parceria" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_partnership') ?></option>
                                    <option value="Outro" class="bg-gray-900"><?= \Core\I18n::get('contact_subject_other') ?></option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2"><?= \Core\I18n::get('message') ?> *</label>
                            <textarea name="message" rows="5" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition resize-none" placeholder="<?= \Core\I18n::get('contact_placeholder_msg') ?>"></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full md:w-auto justify-center">
                            <?= \Core\I18n::get('send_message') ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info lateral -->
            <div class="space-y-6" data-animate="fade-right">
                <div class="card-premium">
                    <h3 class="font-semibold text-white mb-4"><?= \Core\I18n::get('contact_channels') ?></h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-600/20 rounded-xl flex items-center justify-center"><span class="text-lg">📧</span></div>
                            <div>
                                <p class="text-xs text-gray-500">E-mail</p>
                                <p class="text-sm text-white">contato@lrvweb.com.br</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600/20 rounded-xl flex items-center justify-center"><span class="text-lg">💬</span></div>
                            <div>
                                <p class="text-xs text-gray-500">WhatsApp</p>
                                <a href="https://wa.me/5517988093160" target="_blank" class="text-sm text-white hover:text-purple-400 transition">(17) 98809-3160</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h3 class="font-semibold text-white mb-4"><?= \Core\I18n::get('contact_hours') ?></h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-400"><?= \Core\I18n::get('contact_hours_weekday') ?></span><span class="text-white">09h — 18h</span></div>
                        <div class="flex justify-between"><span class="text-gray-400"><?= \Core\I18n::get('contact_hours_saturday') ?></span><span class="text-white">09h — 13h</span></div>
                        <div class="flex justify-between"><span class="text-gray-400"><?= \Core\I18n::get('contact_hours_sunday') ?></span><span class="text-gray-500"><?= \Core\I18n::get('contact_hours_closed') ?></span></div>
                    </div>
                </div>

                <div class="card-premium">
                    <h3 class="font-semibold text-white mb-3"><?= \Core\I18n::get('contact_fast_reply') ?></h3>
                    <p class="text-sm text-gray-400"><?= \Core\I18n::get('contact_fast_reply_desc') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
