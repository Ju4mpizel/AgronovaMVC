<?php
// views/layout/header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroNova System</title>
    
    <!-- Tipografía Premium Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración personalizada de Tailwind para igualar el Mockup -->
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
                            600: '#15803d', // Verde principal
                            700: '#166534', // Verde oscuro académico
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
        /* Ocultar barra de scroll horrible por defecto en el menú pero permitir deslizamiento */
        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<!-- CONGELAMOS EL MONITOR: Se elimina el scroll general de toda la página -->
<body class="bg-[#f4f6f8] text-[#1e293b] h-screen overflow-hidden flex m-0 p-0">
    
    <!-- CONTENEDOR MAESTRO: Forzado a la altura máxima de la pantalla sin desbordamientos globales -->
    <div class="flex w-full h-screen max-h-screen p-4 gap-4 box-border overflow-hidden">