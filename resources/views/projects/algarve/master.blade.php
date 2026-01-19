<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algarve Residence - Painel Interativo</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/css/index.css">

    <style>
        /* Bloqueios de Sistema (Totem) */
        body { 
            overflow: hidden; 
            user-select: none; 
            -webkit-user-select: none;
            font-family: 'Montserrat', sans-serif; 
            cursor: default;
        }
        img { pointer-events: none; -webkit-user-drag: none; }
        :root { --gold: #C5A065; }

        /* Loader */
        .loader { border-top-color: var(--gold); animation: spinner 1.5s linear infinite; }
        @keyframes spinner { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Scrollbar Oculta */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        select option { background-color: #111827; color: white; padding: 10px; }

        /* Estilização do Teclado Virtual (Dark Mode) */
        .simple-keyboard {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border-top: 1px solid #C5A065 !important;
            color: white !important;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
            display: none; /* Oculto por padrão */
            padding-bottom: 20px;
        }
        .hg-button { background: #334155 !important; color: white !important; border-bottom: 2px solid #1e293b !important; }
        .hg-button:active { background: #C5A065 !important; color: black !important; }
    </style>
</head>

<body class="bg-gray-900 text-white h-screen w-screen flex flex-col overflow-hidden bg-cover bg-center" 
      style="background-image: url('/storage/algarve/bg-texture.jpg');" 
      id="mainBody" oncontextmenu="return false;">

    <div class="absolute inset-0 bg-gradient-to-b from-gray-950/80 via-blue-950/40 to-blue-900/10 z-0 pointer-events-none"></div>
    <div class="absolute inset-x-0 bottom-0 h-68 bg-gradient-to-t from-black/90 to-transparent z-0 pointer-events-none"></div>

    <div id="loading" class="absolute inset-0 z-[100] flex flex-col items-center justify-center bg-gray-900 transition-opacity duration-500">
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-700 h-24 w-24 mb-4"></div>
        <h2 class="text-xl font-light animate-pulse text-[#C5A065]">Carregando...</h2>
        <p id="loadingStatus" class="text-xs text-gray-500 mt-2">Iniciando Sistema</p>
    </div>

    <div id="app" class="relative z-10 w-full h-full flex flex-col opacity-0 transition-opacity duration-1000">
        
        <header class="w-full pt-6 pb-2 flex flex-col items-center justify-center z-20 shrink-0 select-none">
            <div class="h-24 flex items-center justify-center relative animate-fade-in">
                <img src="/storage/algarve/logo.png" class="h-full object-contain drop-shadow-2xl pointer-events-none" alt="Logo">
            </div>
            <p class="mt-2 text-[#C5A065] tracking-[0.4em] uppercase text-[10px] font-bold shadow-black drop-shadow-md">Painel Interativo</p>
        </header>

        <main class="flex-grow flex items-center justify-center p-4 w-full max-w-[1920px] mx-auto z-10 overflow-hidden">
            <div id="screenMenu" class="w-full flex flex-col items-center animate-fade-in px-12">
                <div class="w-full max-w-4xl border-b border-[#C5A065]/30 mb-8 pb-4 text-center">
                    <span class="text-lg font-light tracking-[0.2em] text-white/90">SELECIONE UMA EXPERIÊNCIA</span>
                </div>
                <div id="menuGrid" class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl"></div>
            </div>
            <div id="screenContent" class="hidden w-full h-full flex-col items-center justify-center">
                <div id="mediaContainer" class="w-full h-full relative shadow-2xl bg-gray-900/90 backdrop-blur-sm rounded-lg overflow-hidden border border-[#C5A065]/20"></div>
            </div>
        </main>

        <footer class="w-full relative z-20 shrink-0 select-none">
            <div class="w-full h-10 bg-cover bg-center bg-no-repeat shadow-[0_-10px_40px_rgba(0,0,0,0.5)]" style="background-image: url('/storage/algarve/footer-strip.png');"></div>
            <div class="bg-[#0F172A] text-center py-2 text-[#C5A065]/50 text-[10px] uppercase tracking-[0.2em] border-t border-[#C5A065]/10">
                Desenvolvido por <span class="font-bold text-[#C5A065]">Tecnolabs</span>
            </div>
        </footer>
    </div>

    <div id="modalUnitDetail" class="absolute inset-0 bg-black/95 z-[60] hidden flex items-center justify-center p-8 backdrop-blur-md">
        <div class="bg-gray-900 border border-[#C5A065] w-full max-w-5xl h-[80vh] grid grid-cols-1 md:grid-cols-2 rounded-xl overflow-hidden shadow-[0_0_60px_rgba(197,160,101,0.2)] animate-fade-in relative">
            <button onclick="toggleUnitModal(false)" class="absolute top-8 right-8 z-50 bg-black/50 text-white hover:text-[#C5A065] p-2 rounded-full transition-colors"><i data-lucide="x" class="w-8 h-8"></i></button>
            
            <div class="p-8 border-r border-[#C5A065]/20 flex flex-col bg-black/20">
                <div class="mb-6">
                    <span class="text-[#C5A065] text-xs uppercase tracking-widest block mb-1">Unidade Selecionada</span>
                    <h2 id="modalUnitNumber" class="text-5xl font-serif text-white mb-2">Apt ---</h2>
                    <div class="flex gap-4 text-sm text-white/70">
                        <span id="modalUnitTypology" class="bg-white/10 px-3 py-1 rounded">---</span>
                        <span id="modalUnitArea" class="bg-white/10 px-3 py-1 rounded">--- m²</span>
                        <span id="modalUnitPrice" class="bg-[#C5A065]/20 text-[#C5A065] px-3 py-1 rounded font-bold">R$ ---</span>
                    </div>
                </div>
                <div class="flex-grow bg-white/5 rounded-lg border border-white/10 flex items-center justify-center relative overflow-hidden group">
                    <img id="modalFloorplan" src="" class="max-h-full max-w-full object-contain p-4" alt="Planta">
                    <div id="modalFloorplanPlaceholder" class="hidden text-white/30 text-sm uppercase">Planta não disponível</div>
                </div>
            </div>

            <div class="p-8 flex flex-col justify-center bg-gray-900">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-2">Tenho Interesse</h3>
                    <p class="text-white/60 text-sm">Receba a tabela e a planta desta unidade.</p>
                </div>
                <form id="leadForm" onsubmit="submitLead(event)" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#C5A065] mb-1 tracking-widest">Nome Completo</label>
                        <input type="text" id="leadName" class="input-keyboard w-full bg-black/40 border border-[#C5A065]/30 text-white p-4 rounded focus:border-[#C5A065] outline-none" required placeholder="Toque para digitar">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#C5A065] mb-1 tracking-widest">WhatsApp</label>
                        <input type="tel" id="leadPhone" class="input-keyboard w-full bg-black/40 border border-[#C5A065]/30 text-white p-4 rounded focus:border-[#C5A065] outline-none" required placeholder="(XX) XXXXX-XXXX" maxlength="15">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-[#C5A065] mb-1 tracking-widest">E-mail</label>
                        <input type="email" id="leadEmail" class="input-keyboard w-full bg-black/40 border border-[#C5A065]/30 text-white p-4 rounded focus:border-[#C5A065] outline-none" required placeholder="seu@email.com">
                    </div>

                    <input type="hidden" id="leadUnit">
                    
                    <button type="submit" id="btnSubmitLead" class="w-full bg-[#C5A065] text-black font-bold py-4 rounded hover:bg-white transition-all tracking-widest text-sm shadow-lg shadow-[#C5A065]/20 flex items-center justify-center gap-2 mt-4">
                        <span>SOLICITAR MATERIAL</span> <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="lightbox" class="fixed inset-0 z-[70] hidden bg-black/95 backdrop-blur-xl flex items-center justify-center p-8 opacity-0 transition-opacity duration-300 select-none">
        <button onclick="closeLightbox()" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors z-50 bg-black/50 p-2 rounded-full border border-white/10 hover:border-[#C5A065]"><i data-lucide="x" class="w-8 h-8"></i></button>
        <button onclick="navigateLightbox(-1)" class="absolute left-8 top-1/2 -translate-y-1/2 text-white/30 hover:text-[#C5A065] transition-all z-50 p-4 hover:scale-110"><i data-lucide="chevron-left" class="w-16 h-16"></i></button>
        <button onclick="navigateLightbox(1)" class="absolute right-8 top-1/2 -translate-y-1/2 text-white/30 hover:text-[#C5A065] transition-all z-50 p-4 hover:scale-110"><i data-lucide="chevron-right" class="w-16 h-16"></i></button>
        <div id="touchArea" class="absolute inset-0 z-40" onmousedown="handleTouchStart(event)" onmouseup="handleTouchEnd(event)" ontouchstart="handleTouchStart(event)" ontouchend="handleTouchEnd(event)"></div>
        <img id="lightboxImage" src="" class="hidden max-w-full max-h-full border border-[#C5A065]/50 shadow-[0_0_100px_rgba(0,0,0,0.8)] rounded-sm scale-95 transition-transform duration-300 relative z-30 pointer-events-none">
        <video id="lightboxVideo" controls class="hidden max-w-full max-h-full border border-[#C5A065]/50 shadow-[0_0_100px_rgba(0,0,0,0.8)] rounded-sm relative z-50"><source id="lightboxVideoSource" src="" type="video/mp4"></video>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 text-xs tracking-widest uppercase z-50"><span id="lbCurrent">1</span> / <span id="lbTotal">10</span></div>
    </div>

    <div class="simple-keyboard"></div>

    <script src="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/index.js"></script>

    <script>
        const projectSlug = 'algarve'; 
        let globalData = null; 
        let filterStatus = 'all'; let filterBlock = 'all'; let filterFloor = 'all';
        let currentGalleryList = []; let currentImageIndex = 0; let filterDate = 'all';
        
        // --- TECLADO VIRTUAL ---
        let keyboard;
        let selectedInput;

        function initKeyboard() {
            const Keyboard = window.SimpleKeyboard.default;
            keyboard = new Keyboard({
                onChange: input => onChange(input),
                onKeyPress: button => onKeyPress(button),
                theme: "hg-theme-default hg-layout-default myTheme",
                layout: {
                    'default': [
                        '1 2 3 4 5 6 7 8 9 0 {bksp}',
                        'q w e r t y u i o p',
                        'a s d f g h j k l',
                        '{shift} z x c v b n m @ .com',
                        '{space}'
                    ],
                    'shift': [
                        '1 2 3 4 5 6 7 8 9 0 {bksp}',
                        'Q W E R T Y U I O P',
                        'A S D F G H J K L',
                        '{shift} Z X C V B N M @ .com',
                        '{space}'
                    ]
                }
            });

            // Ativa o teclado ao focar no input
            document.querySelectorAll(".input-keyboard").forEach(input => {
                input.addEventListener("focus", onInputFocus);
                // Previne zoom em mobile se necessário
                input.addEventListener("click", (e) => {
                    e.target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });

            // Oculta teclado se clicar fora
            document.addEventListener("click", (event) => {
                if (
                    !event.target.classList.contains("input-keyboard") &&
                    !event.target.closest(".simple-keyboard") && 
                    !event.target.classList.contains("hg-button")
                ) {
                    hideKeyboard();
                }
            });
        }

        function onInputFocus(event) {
            selectedInput = event.target;
            keyboard.setOptions({ inputName: event.target.id });
            keyboard.setInput(event.target.value);
            showKeyboard();
        }

        function onChange(input) {
            if(selectedInput) {
                selectedInput.value = input;
                // Dispara evento de input para a máscara funcionar
                selectedInput.dispatchEvent(new Event('input'));
            }
        }

        function onKeyPress(button) {
            if (button === "{shift}" || button === "{lock}") handleShift();
        }

        function handleShift() {
            let currentLayout = keyboard.options.layoutName;
            let shiftToggle = currentLayout === "default" ? "shift" : "default";
            keyboard.setOptions({ layoutName: shiftToggle });
        }

        function showKeyboard() { document.querySelector(".simple-keyboard").style.display = "block"; }
        function hideKeyboard() { document.querySelector(".simple-keyboard").style.display = "none"; }

        // --- MÁSCARA DE TELEFONE ---
        document.getElementById('leadPhone').addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });

        // --- BLOQUEIOS E ATALHOS ---
        document.addEventListener('contextmenu', event => event.preventDefault()); 
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I') || (e.ctrlKey && e.key === 'u') || (e.ctrlKey && e.key === 'p') || (e.ctrlKey && e.key === 's')) { e.preventDefault(); return false; }
            if(document.getElementById('lightbox') && !document.getElementById('lightbox').classList.contains('hidden')) {
                if(e.key === "ArrowLeft") navigateLightbox(-1);
                if(e.key === "ArrowRight") navigateLightbox(1);
                if(e.key === "Escape") closeLightbox();
            }
        });

        // --- SISTEMA ---
        async function initSystem() {
            const statusEl = document.getElementById('loadingStatus');
            try {
                statusEl.innerText = "Conectando...";
                let response = await fetch(`/api/project/${projectSlug}`);
                if(!response.ok) throw new Error(`Erro API: ${response.status}`);
                globalData = await response.json();
                
                renderMenu(globalData.categories);
                if(typeof lucide !== 'undefined') lucide.createIcons();
                initKeyboard(); // Inicia o teclado

                document.getElementById('loading').classList.add('opacity-0', 'pointer-events-none');
                document.getElementById('app').classList.remove('opacity-0');
            } catch (error) {
                console.error(error);
                statusEl.innerText = "Erro de Conexão";
                statusEl.classList.add('text-red-500');
            }
        }

        // Funções de Renderização (Mantidas e Ajustadas)
        function renderMenu(categories) {
            const grid = document.getElementById('menuGrid'); grid.innerHTML = ''; 
            if(!categories || categories.length === 0) { grid.innerHTML = '<p class="text-white col-span-3 text-center">Vazio.</p>'; return; }
            categories.forEach(cat => {
                // ... (Lógica de ícones igual ao anterior) ...
                const btn = document.createElement('div');
                let iconName = 'image'; const name = cat.name.toLowerCase();
                if(name.includes('obra')) iconName = 'hard-hat';
                else if(name.includes('planta')) iconName = 'ruler';
                else if(name.includes('tour')) iconName = 'glasses';
                else if(name.includes('disponibilidade')) iconName = 'list-checks';
                else if(name.includes('vídeo')) iconName = 'play-circle';
                else if(cat.type === 'masterplan') iconName = 'map';

                btn.className = "group relative h-48 cursor-pointer bg-black/40 backdrop-blur-sm border border-[#C5A065]/30 hover:border-[#C5A065] hover:bg-black/60 transition-all duration-500 rounded-sm flex flex-col items-center justify-center gap-4 overflow-hidden shadow-lg";
                btn.innerHTML = `<div class="absolute inset-0 bg-gradient-to-tr from-[#C5A065]/0 to-[#C5A065]/0 group-hover:to-[#C5A065]/10 transition-all duration-700"></div><div class="p-4 rounded-full border border-[#C5A065]/20 bg-[#C5A065]/5 group-hover:scale-110 group-hover:bg-[#C5A065] group-hover:text-black transition-all duration-500 text-[#C5A065]"><i data-lucide="${iconName}" class="w-8 h-8"></i></div><div class="text-center z-10"><h3 class="text-xl font-bold uppercase tracking-[0.15em] text-white group-hover:text-[#C5A065] transition-colors">${cat.name}</h3><p class="text-[10px] text-[#C5A065]/60 uppercase tracking-widest mt-1 group-hover:text-[#C5A065]/80">Clique para acessar</p></div><div class="absolute bottom-0 left-0 w-0 h-[2px] bg-[#C5A065] group-hover:w-full transition-all duration-700 ease-out"></div>`;
                btn.onclick = () => openCategory(cat);
                grid.appendChild(btn);
            });
        }

        function openCategory(cat) {
            hideKeyboard(); // Fecha teclado ao mudar tela
            if(cat.type === '360') { window.location.href = `/storage/tours/${cat.id}/index.html`; return; }
            document.getElementById('screenMenu').classList.add('hidden');
            document.getElementById('screenContent').classList.remove('hidden');
            const container = document.getElementById('mediaContainer'); container.innerHTML = ''; 
            container.className = "w-full h-full relative shadow-2xl border border-[#C5A065]/20 bg-gray-900/90 backdrop-blur-sm rounded-sm overflow-hidden";
            
            const name = cat.name.toLowerCase();
            if (name.includes('disponibilidade')) {
                filterStatus = 'all'; filterBlock = 'all'; filterFloor = 'all'; renderAvailabilityTable(container);
            } else if(cat.type === 'masterplan') { renderMasterplan(container); } 
            else { if(name.includes('obra')) { filterDate = 'all'; renderGallery(container, cat, true); } else { renderGallery(container, cat, false); } }
            lucide.createIcons();
        }

        function renderAvailabilityTable(container) {
            container.innerHTML = '';
            container.className = "w-full h-full bg-gray-900 flex flex-row overflow-hidden"; 

            // 1. ESQUERDA: FOTO DO PRÉDIO
            const buildingImage = globalData.project.facade_image ? '/storage/' + globalData.project.facade_image : '';
            const leftCol = document.createElement('div');
            // Mudei para w-[40%] para dar um pouco mais de destaque à imagem e reduzir distorção
            leftCol.className = "w-[40%] h-full relative border-r border-[#C5A065]/20 hidden lg:block bg-black"; 
            
            // AQUI ESTÁ A MUDANÇA: 'bg-cover' (Preenche tudo) em vez de 'bg-contain'
            leftCol.innerHTML = `
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('${buildingImage}');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div> <div class="absolute bottom-8 left-8">
                    <h3 class="text-white text-3xl font-serif drop-shadow-lg">Algarve Residence</h3>
                    <p class="text-[#C5A065] text-xs uppercase tracking-widest mt-1">Fachada Principal</p>
                </div>
            `;
            container.appendChild(leftCol);

            // 2. DIREITA: LISTA E FILTROS
            const rightCol = document.createElement('div');
            rightCol.className = "w-full lg:w-[60%] h-full flex flex-col bg-gray-900 relative"; // Ajustei para 60%

            // Header da Lista
            const uniqueBlocks = [...new Set(globalData.units.map(u => u.block || 'Torre Única'))].sort();
            const uniqueFloors = [...new Set(globalData.units.map(u => u.floor))].sort((a,b) => a - b);

            const header = document.createElement('div');
            header.className = "p-6 border-b border-[#C5A065]/30 flex flex-wrap justify-between items-center shrink-0 bg-gray-900 z-20 gap-4 shadow-lg";
            header.innerHTML = `
                <div class="flex items-center gap-4">
                    <button onclick="goHome()" class="bg-[#C5A065] text-black px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white transition-all shadow-lg">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> <span class="text-xs font-bold tracking-widest">MENU</span>
                    </button>
                    <h2 class="text-xl text-white font-serif hidden md:block">Espelho de Vendas</h2>
                </div>
                <div class="flex items-center gap-2 flex-wrap justify-end">
                    <select id="filterBlock" onchange="updateFilters('block', this.value)" class="bg-black/50 border border-[#C5A065]/40 text-white text-[10px] uppercase p-2 rounded focus:border-[#C5A065] outline-none">
                        <option value="all">Todas as Torres</option>
                        ${uniqueBlocks.map(b => `<option value="${b}" ${filterBlock === b ? 'selected' : ''}>${b}</option>`).join('')}
                    </select>
                    <select id="filterFloor" onchange="updateFilters('floor', this.value)" class="bg-black/50 border border-[#C5A065]/40 text-white text-[10px] uppercase p-2 rounded focus:border-[#C5A065] outline-none">
                        <option value="all">Todos Andares</option>
                        ${uniqueFloors.map(f => `<option value="${f}" ${filterFloor == f ? 'selected' : ''}>${f}º Andar</option>`).join('')}
                    </select>
                    <button onclick="updateFilters('status', '${filterStatus === 'all' ? 'available' : 'all'}')" 
                            class="border px-4 py-2 rounded uppercase text-[10px] font-bold tracking-widest transition-all ${filterStatus === 'available' ? 'bg-[#C5A065] text-black border-[#C5A065]' : 'border-[#C5A065]/50 text-[#C5A065] hover:bg-[#C5A065] hover:text-black'}">
                        ${filterStatus === 'available' ? 'SÓ DISPONÍVEIS' : 'TODOS'}
                    </button>
                </div>
            `;
            rightCol.appendChild(header);

            // Scroll Area
            const scrollArea = document.createElement('div');
            scrollArea.id = "unitsScrollArea";
            scrollArea.className = "flex-grow overflow-y-auto p-8 hide-scrollbar scroll-smooth relative";
            
            const floors = {};
            let hasUnits = false;
            
            globalData.units.forEach(u => {
                if(filterStatus === 'available' && u.status !== 'available') return;
                if(filterBlock !== 'all' && (u.block || 'Torre Única') !== filterBlock) return;
                if(filterFloor !== 'all' && u.floor != filterFloor) return;

                const f = u.floor || 'Térreo';
                if(!floors[f]) floors[f] = [];
                floors[f].push(u);
                hasUnits = true;
            });

            if(!hasUnits) {
                scrollArea.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-white/50"><i data-lucide="search-x" class="w-16 h-16 mb-4 opacity-50"></i><p>Nenhuma unidade encontrada.</p></div>`;
            } else {
                const sortedFloors = Object.keys(floors).sort((a,b) => b - a);
                let contentHtml = `<div class="space-y-12 pb-24">`;

                sortedFloors.forEach(floor => {
                    contentHtml += `<div class="animate-fade-in"><div class="flex items-center gap-3 mb-4"><span class="bg-[#C5A065] text-black px-3 py-1 rounded text-xs font-bold shadow-lg">${floor}º ANDAR</span><div class="h-[1px] bg-[#C5A065]/20 flex-grow"></div></div><div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">`;
                    floors[floor].forEach(u => {
                        // CORES DOS CARDS
                        let cardBg = "bg-green-900/20 border-green-500/30"; 
                        let statusText = "Disponível";
                        let statusDot = "bg-green-500 shadow-[0_0_8px_#22c55e]";
                        let isClickable = true;

                        if(u.status === 'sold') { 
                            cardBg = "bg-red-900/20 border-red-500/30 opacity-60 grayscale-[0.5]"; // Vermelho escuro
                            statusText = "Vendido"; 
                            statusDot = "bg-red-600";
                            isClickable = false; 
                        }
                        if(u.status === 'reserved') { 
                            cardBg = "bg-yellow-900/20 border-yellow-500/30"; 
                            statusText = "Reservado"; 
                            statusDot = "bg-yellow-500";
                        }
                        if(u.status === 'blocked') { 
                            cardBg = "bg-gray-800 border-gray-600/30 opacity-50"; 
                            statusText = "Bloqueado"; 
                            statusDot = "bg-gray-500";
                            isClickable = false; 
                        }

                        const clickAction = isClickable ? `onclick="openUnitDetails(${u.id})"` : '';
                        const cursorClass = isClickable ? 'cursor-pointer hover:scale-105 hover:bg-black/60 hover:border-[#C5A065]' : 'cursor-not-allowed';

                        contentHtml += `
                            <div ${clickAction} class="relative ${cardBg} border p-4 rounded ${cursorClass} flex flex-col items-center text-center transition-all group shadow-lg duration-300 backdrop-blur-sm">
                                <span class="text-3xl font-bold text-white mb-2 group-hover:text-[#C5A065] transition-colors">${u.unit_number}</span>
                                
                                <div class="text-[10px] text-white/70 mb-4 flex flex-col gap-0.5">
                                    <span class="uppercase tracking-wide font-bold">${u.typology || '-'}</span>
                                    <span class="font-mono text-white/50">${u.area ? u.area + 'm²' : ''}</span>
                                </div>

                                <div class="flex items-center gap-2 bg-black/40 px-3 py-1.5 rounded-full border border-white/5 w-full justify-center mb-4">
                                    <div class="w-2 h-2 rounded-full ${statusDot}"></div>
                                    <span class="text-[9px] uppercase tracking-wider text-white font-bold">${statusText}</span>
                                </div>

                                ${u.floorplan_image ? `
                                <button onclick="event.stopPropagation(); openLightbox('/storage/${u.floorplan_image}', 'image')" class="text-[10px] text-[#C5A065] border border-[#C5A065]/30 hover:bg-[#C5A065] hover:text-black px-4 py-2 rounded-full transition-colors flex items-center gap-2 w-full justify-center font-bold tracking-wider">
                                    <i data-lucide="map" class="w-4 h-4"></i> VER PLANTA
                                </button>
                                ` : ''}
                            </div>
                        `;
                    });
                    contentHtml += `</div></div>`;
                });
                contentHtml += `</div>`;
                scrollArea.innerHTML = contentHtml;
            }
            rightCol.appendChild(scrollArea);

            if(hasUnits) {
                const controls = document.createElement('div');
                controls.className = "absolute right-6 bottom-8 flex flex-col gap-4 z-30";
                controls.innerHTML = `<button onmousedown="startScroll(-20)" onmouseup="stopScroll()" onmouseleave="stopScroll()" class="w-12 h-12 bg-[#C5A065] text-black rounded-full flex items-center justify-center shadow-lg hover:bg-white hover:scale-110 transition-all active:scale-90 border-2 border-transparent hover:border-[#C5A065]"><i data-lucide="chevron-up" class="w-6 h-6"></i></button><button onmousedown="startScroll(20)" onmouseup="stopScroll()" onmouseleave="stopScroll()" class="w-12 h-12 bg-[#C5A065] text-black rounded-full flex items-center justify-center shadow-lg hover:bg-white hover:scale-110 transition-all active:scale-90 border-2 border-transparent hover:border-[#C5A065]"><i data-lucide="chevron-down" class="w-6 h-6"></i></button>`;
                rightCol.appendChild(controls);
            }
            container.appendChild(rightCol);
            lucide.createIcons();
        }

        // ... (Funções updateFilters, openUnitDetails, toggleUnitModal, renderMasterplan, renderGallery, renderPins, scroll, home, lightbox iguais ao anterior) ...
        function updateFilters(type, value) { if(type === 'block') filterBlock = value; if(type === 'floor') filterFloor = value; if(type === 'status') filterStatus = value; const container = document.getElementById('mediaContainer'); renderAvailabilityTable(container); }
        function openUnitDetails(unitId) {
            const unit = globalData.units.find(u => u.id === unitId); if(!unit) return;
            document.getElementById('modalUnitNumber').innerText = "Apt " + unit.unit_number; document.getElementById('modalUnitTypology').innerText = unit.typology || 'Padrão'; document.getElementById('modalUnitArea').innerText = unit.area ? unit.area + 'm²' : '-'; document.getElementById('modalUnitPrice').innerText = unit.price ? 'R$ ' + parseFloat(unit.price).toLocaleString('pt-BR') : 'Sob Consulta'; document.getElementById('leadUnit').value = unit.unit_number; 
            const imgEl = document.getElementById('modalFloorplan'); const placeholder = document.getElementById('modalFloorplanPlaceholder');
            if(unit.floorplan_image) { imgEl.src = '/storage/' + unit.floorplan_image; imgEl.classList.remove('hidden'); placeholder.classList.add('hidden'); } else { imgEl.classList.add('hidden'); placeholder.classList.remove('hidden'); }
            toggleUnitModal(true);
        }
        function toggleUnitModal(show) { const el = document.getElementById('modalUnitDetail'); if(show) { el.classList.remove('hidden'); el.classList.add('flex'); } else { el.classList.add('hidden'); el.classList.remove('flex'); hideKeyboard(); } }
        function renderMasterplan(container) { const facadeUrl = globalData.project.facade_image ? '/storage/' + globalData.project.facade_image : ''; container.innerHTML = `<button onclick="goHome()" class="absolute top-6 left-6 z-50 bg-black/60 border border-[#C5A065]/50 text-white px-6 py-3 rounded-full flex items-center gap-2 hover:bg-[#C5A065] hover:text-black transition-all backdrop-blur-md shadow-lg"><i data-lucide="arrow-left" class="w-4 h-4"></i> <span class="text-xs font-bold tracking-widest">MENU</span></button><div class="absolute inset-0 z-0 select-none"><img src="${facadeUrl}" class="w-full h-full object-cover" alt="Fachada"><div id="pinsLayer" class="absolute inset-0 z-10 w-full h-full"></div></div><div class="absolute bottom-8 left-8 z-20 pointer-events-none"><div class="pointer-events-auto bg-black/80 border-l-2 border-[#C5A065] p-6 backdrop-blur-md shadow-2xl animate-fade-in"><h3 class="text-2xl font-bold text-white mb-1 font-serif">IMPLANTAÇÃO</h3><p class="text-[#C5A065] text-xs uppercase tracking-widest mb-4">Selecione uma unidade</p><div class="flex gap-4 text-[10px] uppercase tracking-wider text-white/60"><div class="flex items-center gap-2"><div class="w-2 h-2 bg-green-500 rounded-full shadow-[0_0_5px_green]"></div> Disp.</div><div class="flex items-center gap-2"><div class="w-2 h-2 bg-red-500 rounded-full shadow-[0_0_5px_red]"></div> Vendido</div></div></div></div>`; renderPins(); }
        function renderGallery(container, cat, showFilter = false) { container.innerHTML = ''; container.className = "w-full h-full flex flex-col relative bg-gray-900/90 backdrop-blur-sm rounded-lg overflow-hidden border border-[#C5A065]/20"; const headerContainer = document.createElement('div'); headerContainer.className = "w-full p-6 border-b border-[#C5A065]/20 bg-gray-900/95 z-30 shrink-0 flex items-center justify-center relative"; const leftElements = document.createElement('div'); leftElements.className = "flex items-center gap-4 absolute left-6"; leftElements.innerHTML = `<button onclick="goHome()" class="bg-[#C5A065] text-black px-4 py-2 rounded-full flex items-center gap-2 hover:bg-white transition-all shadow-lg"><i data-lucide="arrow-left" class="w-4 h-4"></i> <span class="text-xs font-bold tracking-widest">MENU</span></button><h2 class="text-xl text-[#C5A065] font-serif uppercase tracking-widest hidden md:block">${cat.name}</h2>`; headerContainer.appendChild(leftElements); if(showFilter) { const dates = new Set(); const rawMedia = globalData.media.filter(m => m.media_category_id === cat.id); rawMedia.forEach(m => { if(m.created_at) { const date = new Date(m.created_at); const label = date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' }); dates.add(label); } }); if(dates.size > 0) { let options = `<option value="all">Todo o Período</option>`; dates.forEach(d => { const label = d.charAt(0).toUpperCase() + d.slice(1); options += `<option value="${d}" ${filterDate === d ? 'selected' : ''}>${label}</option>`; }); const filterWrapper = document.createElement('div'); filterWrapper.innerHTML = `<select id="galleryDateFilter" onchange="updateGalleryFilter('${cat.id}', this.value)" class="bg-black/50 border border-[#C5A065]/40 text-white text-[10px] uppercase p-2 rounded focus:border-[#C5A065] outline-none shadow-lg cursor-pointer">${options}</select>`; headerContainer.appendChild(filterWrapper); } } container.appendChild(headerContainer); const scrollArea = document.createElement('div'); scrollArea.className = "flex-grow overflow-y-auto p-8 hide-scrollbar"; const grid = document.createElement('div'); grid.className = "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"; let filteredMedia = globalData.media.filter(m => m.media_category_id === cat.id); if(showFilter && filterDate !== 'all') { filteredMedia = filteredMedia.filter(m => { if(!m.created_at) return false; const date = new Date(m.created_at); const label = date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' }); return label === filterDate; }); } currentGalleryList = filteredMedia; if(filteredMedia.length === 0) { grid.innerHTML = `<div class="col-span-3 flex flex-col items-center justify-center text-[#C5A065]/40 h-64 gap-4"><i data-lucide="image-off" class="w-12 h-12"></i><p>Nenhuma imagem encontrada.</p></div>`; } else { filteredMedia.forEach((m, index) => { const url = '/storage/' + m.path; const item = document.createElement('div'); item.className = "relative group aspect-video bg-black/50 border border-[#C5A065]/20 hover:border-[#C5A065] rounded-sm overflow-hidden cursor-pointer shadow-lg transition-all hover:-translate-y-1"; if(m.file_type === 'video' || url.endsWith('.mp4')) { item.innerHTML = `<video src="${url}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video><div class="absolute inset-0 flex items-center justify-center bg-black/20 pointer-events-none"><i data-lucide="play-circle" class="w-16 h-16 text-[#C5A065] drop-shadow-lg group-hover:scale-110 transition-transform"></i></div>`; item.onclick = () => openLightbox(index); } else { item.innerHTML = `<img src="${url}" loading="lazy" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-80 group-hover:opacity-100">`; item.onclick = () => openLightbox(index); } grid.appendChild(item); }); } scrollArea.appendChild(grid); container.appendChild(scrollArea); }
        function updateGalleryFilter(catId, value) { filterDate = value; const cat = globalData.categories.find(c => c.id == catId); const container = document.getElementById('mediaContainer'); renderGallery(container, cat, true); lucide.createIcons(); }
        function openLightbox(index) { currentImageIndex = index; updateLightboxContent(); const lb = document.getElementById('lightbox'); lb.classList.remove('hidden'); setTimeout(() => lb.classList.remove('opacity-0'), 10); }
        function updateLightboxContent() { const m = currentGalleryList[currentImageIndex]; const url = '/storage/' + m.path; const isVideo = m.file_type === 'video' || url.endsWith('.mp4'); const img = document.getElementById('lightboxImage'); const vid = document.getElementById('lightboxVideo'); const vidSrc = document.getElementById('lightboxVideoSource'); const counter = document.getElementById('lbCurrent'); const total = document.getElementById('lbTotal'); counter.innerText = currentImageIndex + 1; total.innerText = currentGalleryList.length; img.classList.add('hidden'); vid.classList.add('hidden'); vid.pause(); if (isVideo) { vidSrc.src = url; vid.load(); vid.classList.remove('hidden'); setTimeout(() => vid.play(), 300); } else { img.src = url; img.classList.remove('hidden'); img.classList.remove('scale-100'); img.classList.add('scale-95'); setTimeout(() => { img.classList.remove('scale-95'); img.classList.add('scale-100'); }, 50); } }
        function navigateLightbox(direction) { let newIndex = currentImageIndex + direction; if(newIndex >= currentGalleryList.length) newIndex = 0; if(newIndex < 0) newIndex = currentGalleryList.length - 1; currentImageIndex = newIndex; updateLightboxContent(); }
        function closeLightbox() { const lb = document.getElementById('lightbox'); const vid = document.getElementById('lightboxVideo'); lb.classList.add('opacity-0'); setTimeout(() => { lb.classList.add('hidden'); vid.pause(); vid.src = ''; }, 300); }
        let touchStartX = 0; let touchEndX = 0; function handleTouchStart(e) { touchStartX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX; } function handleTouchEnd(e) { touchEndX = e.changedTouches ? e.changedTouches[0].screenX : e.screenX; handleSwipe(); } function handleSwipe() { const threshold = 50; if (touchEndX < touchStartX - threshold) navigateLightbox(1); if (touchEndX > touchStartX + threshold) navigateLightbox(-1); }
        function renderPins() { const pinsLayer = document.getElementById('pinsLayer'); globalData.units.forEach(u => { if(u.map_x && u.map_y) { const pin = document.createElement('div'); pin.className = "absolute cursor-pointer group hover:z-50 flex items-center justify-center"; pin.style.width = "10%"; pin.style.height = "7%"; pin.style.left = (u.map_x.toString().includes('%') ? u.map_x : u.map_x + '%'); pin.style.top = (u.map_y.toString().includes('%') ? u.map_y : u.map_y + '%'); pin.style.transform = "translate(-50%, -50%)"; pin.innerHTML = `<div class="absolute inset-0 transition-all duration-200 opacity-0 group-hover:opacity-100 border border-[#C5A065] bg-[#C5A065]/20 shadow-[0_0_15px_rgba(197,160,101,0.4)]"></div>`; pin.onclick = () => openUnitDetails(u.id); pinsLayer.appendChild(pin); } }); }
        let scrollInterval; function startScroll(speed) { const area = document.getElementById('unitsScrollArea'); if(area) scrollInterval = setInterval(() => { area.scrollBy({ top: speed * 5, behavior: 'auto' }); }, 16); } function stopScroll() { clearInterval(scrollInterval); } function goHome() { document.getElementById('screenContent').classList.add('hidden'); document.getElementById('screenMenu').classList.remove('hidden'); }
        async function submitLead(e) { e.preventDefault(); const btn = document.getElementById('btnSubmitLead'); btn.innerHTML = "ENVIANDO..."; const data = { project_slug: projectSlug, name: document.getElementById('leadName').value, phone: document.getElementById('leadPhone').value, email: document.getElementById('leadEmail').value, message: 'Interesse: ' + document.getElementById('leadUnit').value }; try { const response = await fetch('/api/leads', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) }); if(response.ok) { alert("Sucesso! Entraremos em contato."); toggleUnitModal(false); } } catch(err) { alert("Erro ao enviar."); } btn.innerHTML = "SOLICITAR MATERIAL"; }

        window.addEventListener('load', initSystem);
    </script>
</body>
</html>