<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Tecnolabs</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { margin: 0; overflow: hidden; font-family: 'Montserrat', sans-serif; }
        /* Animação Pulsante */
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 rgba(255,255,255,0); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,255,255,0.3); }
        }
        .animate-pulse-custom { animation: heartbeat 2s infinite ease-in-out; }
        .animate-fade-in { animation: fadeIn 1s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-900 h-screen w-screen flex flex-col items-center justify-center bg-cover bg-center"
      style="background-image: url('{{ $project->intro_image ? asset('storage/' . $project->intro_image) : '/intro-bg.jpg' }}');"> 
    
    <div class="absolute inset-0 bg-black/30"></div>

    <div class="relative z-10 flex flex-col items-center animate-fade-in mt-[350px]">
        
        <a href="/{{ $project->slug }}/app" 
           class="group relative px-10 py-5 bg-white/10 border border-white/40 backdrop-blur-md rounded-full text-white text-xl tracking-[0.2em] font-light hover:bg-white/20 transition-all animate-pulse-custom decoration-transparent">
            INICIAR EXPERIÊNCIA
            <div class="absolute inset-0 rounded-full border border-white/50 opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500"></div>
        </a>

    </div>

    <footer class="absolute bottom-6 w-full text-center z-20 text-white/40 text-xs">
        Desenvolvido por <span class="font-bold">Tecnolabs</span>
    </footer>

</body>
</html>