<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Home Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div id="app">
        <!-- HEADER -->
        <header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- LOGO -->
                    <div class="flex-shrink-0">
                        <a href="/" class="text-2xl font-bold text-blue-600">
                            📝 Blog
                        </a>
                    </div>

                    <!-- MENU DESKTOP -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            Home
                        </a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            Posts
                        </a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            Categorias
                        </a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            Sobre
                        </a>
                        <a href="#" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            Contato
                        </a>
                    </nav>

                    <!-- BOTÕES MOBILE -->
                    <div class="md:hidden flex items-center gap-2">
                        <button 
                            data-toggle-search
                            class="p-2 text-gray-700 hover:text-blue-600 transition-colors hover:bg-gray-100 rounded-lg"
                            title="Buscar"
                        >
                            <i class="fas fa-search text-lg"></i>
                        </button>

                        <button 
                            data-toggle-menu
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        >
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- BOTÕES DESKTOP -->
                    <div class="hidden md:flex items-center gap-4">
                        <button 
                            data-toggle-search
                            class="p-2 text-gray-700 hover:text-blue-600 transition-colors hover:bg-gray-100 rounded-lg"
                            title="Buscar"
                        >
                            <i class="fas fa-search text-lg"></i>
                        </button>
                        
                        <!-- USUÁRIO LOGADO -->
                        <div data-user-logged-in class="hidden md:flex relative">
                            <button 
                                data-toggle-user-menu
                                class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors"
                            >
                                <span class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    U
                                </span>
                                <span class="text-sm font-medium text-gray-700">Usuário</span>
                            </button>
                            
                            <!-- USER DROPDOWN -->
                            <div 
                                data-user-dropdown
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200"
                            >
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">João Silva</p>
                                    <p class="text-xs text-gray-500">joao@example.com</p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Meu Perfil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Configurações
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    Meus Posts
                                </a>
                                <div class="border-t border-gray-200">
                                    <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        Sair
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- USUÁRIO NÃO LOGADO -->
                        <a data-user-not-logged-in class="hidden px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors font-medium" href="/login">
                            Login
                        </a>
                    </div>
                </div>

                <!-- MENU MOBILE -->
                <div 
                    data-mobile-menu
                    class="hidden md:hidden border-t border-gray-200 py-4 space-y-3"
                >
                    <a href="#" class="block px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Home
                    </a>
                    <a href="#" class="block px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Posts
                    </a>
                    <a href="#" class="block px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Categorias
                    </a>
                    <a href="#" class="block px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Sobre
                    </a>
                    <a href="#" class="block px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                        Contato
                    </a>

                    <!-- DIVISOR -->
                    <div class="my-3 border-t border-gray-200"></div>

                    <!-- SEÇÃO DE USUÁRIO MOBILE (LOGADO) -->
                    <div data-user-logged-in-mobile class="px-4 py-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                U
                            </span>
                            <div>
                                <p class="font-medium text-gray-900">João Silva</p>
                                <p class="text-xs text-gray-500">joao@example.com</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <a href="#" class="block text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                Meu Perfil
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                Configurações
                            </a>
                            <a href="#" class="block text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                Meus Posts
                            </a>
                            <button class="w-full text-left text-sm text-red-600 hover:text-red-700 transition-colors font-medium mt-2 pt-2 border-t border-gray-200">
                                Sair
                            </button>
                        </div>
                    </div>

                    <!-- LOGIN MOBILE (NÃO LOGADO) -->
                    <a data-user-not-logged-in-mobile class="hidden block px-4 py-3 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium" href="/login">
                        Fazer Login
                    </a>
                </div>
            </div>

            <!-- SEARCH MODAL -->
            <div 
                data-search-modal
                class="hidden fixed top-16 left-0 right-0 bg-white border-b border-gray-200 shadow-lg z-40"
            >
                <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl py-6">
                    <div class="flex gap-3">
                        <input 
                            data-search-input
                            type="text" 
                            placeholder="Buscar posts..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <button 
                            data-search-button
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2"
                        >
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Buscar</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Pressione Enter ou clique no botão para buscar</p>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="py-0 bg-gray-50">
            <div class="mx-auto px-2 py-12 sm:py-8 sm:px-4 lg:px-8 max-w-6xl bg-white border-l-2 border-r-2 border-gray-100 shadow-sm">
                <!-- HERO SECTION -->
                <section class="mb-12">
                    <div class="rounded-lg bg-gradient-to-r from-blue-600 to-blue-800 p-8 sm:p-12 text-white">
                        <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                            Bem-vindo ao Meu Blog
                        </h1>
                        <p class="text-lg sm:text-xl text-blue-100 mb-6">
                            Compartilhando conhecimento, experiências e insights sobre desenvolvimento web
                        </p>
                        <button class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                            Explorar Posts
                        </button>
                    </div>
                </section>

                <!-- POSTS GRID -->
                <section>
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Últimas Postagens</h2>
                    
                    <div class="grid gap-6 sm:grid-cols-2">
                        <!-- POST CARD 1 -->
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden border border-gray-200">
                            <div class="h-48 bg-gradient-to-r from-purple-400 to-pink-400"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                        Desenvolvimento
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Introdução ao Tailwind CSS 4
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    Aprenda como usar o Tailwind CSS 4 para criar interfaces modernas e responsivas...
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>12 de Março, 2026</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">
                                        Ler mais →
                                    </a>
                                </div>
                            </div>
                        </article>

                        <!-- POST CARD 2 -->
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden border border-gray-200">
                            <div class="h-48 bg-gradient-to-r from-green-400 to-blue-400"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Tutorial
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Responsividade com Mobile First
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    Descubra as melhores práticas para criar designs responsivos desde o início...
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>10 de Março, 2026</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">
                                        Ler mais →
                                    </a>
                                </div>
                            </div>
                        </article>

                        <!-- POST CARD 3 -->
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden border border-gray-200">
                            <div class="h-48 bg-gradient-to-r from-orange-400 to-red-400"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                        Performance
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Otimização de Imagens para Web
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    Técnicas para otimizar imagens e melhorar o desempenho do seu site...
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>8 de Março, 2026</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">
                                        Ler mais →
                                    </a>
                                </div>
                            </div>
                        </article>

                        <!-- POST CARD 4 -->
                        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden border border-gray-200">
                            <div class="h-48 bg-gradient-to-r from-indigo-400 to-purple-400"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                        SEO
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    SEO para Frontend Developers
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    Entenda como otimizar sua aplicação para mecanismos de busca...
                                </p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>5 de Março, 2026</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">
                                        Ler mais →
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-gray-900 text-gray-300 py-8 sm:py-12 border-t border-gray-800">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
                <div class="grid gap-8 sm:grid-cols-3 mb-8">
                    <div>
                        <h4 class="text-white font-semibold mb-4">Sobre</h4>
                        <p class="text-sm">
                            Um espaço para compartilhar conhecimento sobre desenvolvimento web e tecnologia.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Home</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Posts</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Categorias</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Redes Sociais</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Twitter</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">GitHub</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">LinkedIn</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 pt-8 text-center text-sm">
                    <p>&copy; 2026 Meu Blog. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // State management
        const state = {
            mobileMenuOpen: false,
            userMenuOpen: false,
            searchModalOpen: false,
            searchQuery: '',
            isLoggedIn: false
        };

        // DOM Elements
        const mobileMenuBtn = document.querySelector('[data-toggle-menu]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        const searchBtns = document.querySelectorAll('[data-toggle-search]');
        const searchModal = document.querySelector('[data-search-modal]');
        const searchInput = document.querySelector('[data-search-input]');
        const searchButton = document.querySelector('[data-search-button]');
        const userMenuBtn = document.querySelector('[data-toggle-user-menu]');
        const userDropdown = document.querySelector('[data-user-dropdown]');
        const userLoggedInDesktop = document.querySelector('[data-user-logged-in]');
        const userNotLoggedInDesktop = document.querySelector('[data-user-not-logged-in]');
        const userLoggedInMobile = document.querySelector('[data-user-logged-in-mobile]');
        const userNotLoggedInMobile = document.querySelector('[data-user-not-logged-in-mobile]');

        // Toggle mobile menu
        mobileMenuBtn?.addEventListener('click', () => {
            state.mobileMenuOpen = !state.mobileMenuOpen;
            mobileMenu.classList.toggle('hidden', !state.mobileMenuOpen);
        });

        // Toggle search modal
        searchBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                state.searchModalOpen = !state.searchModalOpen;
                searchModal.classList.toggle('hidden', !state.searchModalOpen);
                if (state.searchModalOpen) {
                    setTimeout(() => searchInput?.focus(), 0);
                }
            });
        });

        // Search functionality
        searchButton?.addEventListener('click', performSearch);
        searchInput?.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') performSearch();
        });

        function performSearch() {
            const query = searchInput?.value.trim();
            if (query) {
                alert('Buscando por: ' + query);
                state.searchModalOpen = false;
                searchModal?.classList.add('hidden');
                searchInput.value = '';
            }
        }

        // Toggle user dropdown
        userMenuBtn?.addEventListener('click', () => {
            state.userMenuOpen = !state.userMenuOpen;
            userDropdown?.classList.toggle('hidden', !state.userMenuOpen);
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            // Close user dropdown
            if (!userMenuBtn?.contains(e.target) && !userDropdown?.contains(e.target)) {
                state.userMenuOpen = false;
                userDropdown?.classList.add('hidden');
            }

            // Close search modal
            if (!searchModal?.contains(e.target) && !Array.from(searchBtns).some(btn => btn.contains(e.target))) {
                state.searchModalOpen = false;
                searchModal?.classList.add('hidden');
            }

            // Close mobile menu
            if (!mobileMenuBtn?.contains(e.target) && !mobileMenu?.contains(e.target)) {
                state.mobileMenuOpen = false;
                mobileMenu?.classList.add('hidden');
            }
        });

        // Initialize user state display
        function updateUserDisplay() {
            if (state.isLoggedIn) {
                userLoggedInDesktop?.classList.remove('hidden');
                userNotLoggedInDesktop?.classList.add('hidden');
                userLoggedInMobile?.classList.remove('hidden');
                userNotLoggedInMobile?.classList.add('hidden');
            } else {
                userLoggedInDesktop?.classList.add('hidden');
                userNotLoggedInDesktop?.classList.remove('hidden');
                userLoggedInMobile?.classList.add('hidden');
                userNotLoggedInMobile?.classList.remove('hidden');
            }
        }

        // Initialize on load
        updateUserDisplay();
    </script>
</body>
</html>