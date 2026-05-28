<?php
session_start();

// Si el usuario ya tiene una sesión activa, lo mandamos directo al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroNova System - Ingreso</title>
    
    <!-- Tipografía Premium Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración personalizada de Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        agro: {
                            50: '#f2f9f5',
                            100: '#e2f3ea',
                            600: '#15803d', // Verde AgroNova
                            700: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Iconos Minimalistas Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f6f8] text-[#1e293b] min-h-screen flex items-center justify-center p-4">

    <!-- Tarjeta del Formulario de Login Estilo SaaS Minimalista -->
    <div class="w-full max-w-md bg-white border border-slate-100 rounded-3xl shadow-sm p-8 md:p-10 transition-all">
        
        <!-- Bloque del Icono de la Hojita Verde (Identidad AgroNova) -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-agro-100 flex items-center justify-center text-agro-600 shadow-sm mb-4">
                <!-- Icono de hojita verde de agronomía profesional -->
                <i data-lucide="sprout" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">AgroNova System</h1>
            <p class="text-xs text-slate-400 font-medium mt-1">Plataforma de Control e Inventario de Insumos</p>
        </div>

        <!-- Alerta Mensaje de Error en bruto por si falla la clave (Opcional del controlador) -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="mb-5 p-3.5 bg-red-50 border border-red-100 rounded-xl flex items-center gap-2.5 text-xs text-red-600 font-semibold">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                Código de credenciales incorrecto o cuenta inválida.
            </div>
        <?php } ?>

        <!-- Formulario Comercial conectado al AuthController -->
        <form action="/AgronovaMVC/controllers/AuthController.php?action=login" method="POST" class="space-y-5">
            
            <!-- Campo: Nombre de Usuario -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nombre de Usuario</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center px-4 text-slate-400 pointer-events-none">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="username" placeholder="Ej. juan.gerente" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <!-- Campo: Contraseña -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Contraseña de Acceso</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center px-4 text-slate-400 pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" required 
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <!-- Botón de Envío de credenciales -->
            <div class="pt-2">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-agro-600 to-agro-700 text-white text-sm font-bold rounded-xl shadow-sm hover:from-agro-700 hover:to-agro-900 transition-all">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Iniciar Sesión en el Panel
                </button>
            </div>
            
        </form>

        <!-- Pie de página del Formulario -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-center">
            <span class="text-[10px] font-mono text-slate-400 uppercase tracking-wider">&copy; <?php echo date('Y'); ?> AgroNova S.R.L. - Cochabamba</span>
        </div>

    </div>

    <!-- Inicialización Mandatoria de los Iconos Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>