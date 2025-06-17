// SOLUCIÓN: Contenedor GIF tamaño completo para igualar REGISTRO
document.addEventListener('DOMContentLoaded', () => {
    const cardFlipper = document.getElementById('authCardFlipper');
    const toggleBtn = document.getElementById('authToggleBtn');
    const loginCard = document.querySelector('.auth-card-front');
    
    let isFlipped = false;
    let gifContainer = null;
    
    // Crear contenedor blanco GRANDE para igualar el formulario de REGISTRO
    function createGifContainer() {
        if (gifContainer) return gifContainer;
        
        gifContainer = document.createElement('div');
        gifContainer.className = 'login-gif-container';
        gifContainer.style.cssText = `
            background: white;
            text-align: center;
            min-height: 165px;
            align-items: center;
        `;
        
        // RUTAS POSIBLES PARA LARAVEL + XAMPP
        const gifPaths = [
            'assets/gif/calendario.gif',                           // Sin barra inicial
            '/assets/gif/calendario.gif',                          // Con barra inicial  
            './assets/gif/calendario.gif',                         // Relativa actual
            '../assets/gif/calendario.gif',                        // Relativa padre
            '/calendario/laravel-google-login/public/assets/gif/calendario.gif'  // Completa XAMPP
        ];
        
        let currentPath = 0;
        
        // Contenido del contenedor MÁS GRANDE
        gifContainer.innerHTML = `
            <div style="margin-bottom: 20px; width: 100%;">
                <img id="loginGifImage" 
                     src="${gifPaths[currentPath]}" 
                     alt="Calendario Animado" 
                     style="width: 266px; height: 125px; border-radius: 8px;"
                     onerror="window.tryNextGifPath && window.tryNextGifPath(this);">
            </div>
        `;
        
        // Función global para probar diferentes rutas
        window.tryNextGifPath = function(imgElement) {
            currentPath++;
            
            if (currentPath < gifPaths.length) {
                console.log(`🔍 Probando ruta ${currentPath + 1}/${gifPaths.length}: ${gifPaths[currentPath]}`);
                imgElement.src = gifPaths[currentPath];
            } else {
                console.error('❌ Ninguna ruta de GIF funcionó');
                // Mostrar placeholder atractivo cuando no encuentra el GIF
                imgElement.style.display = 'none';
                imgElement.parentNode.innerHTML = `
                    <div style="width: 120px; height: 100px; margin: 0 auto; background: linear-gradient(135deg, #8B5CF6, #EC4899); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
                        📅
                    </div>
                `;
            }
        };
        
        return gifContainer;
    }
    
    // Función para manejar el contenedor GIF
    function manageGifContainer() {
        if (!loginCard) return;
        
        if (isFlipped) {
            // REGISTRO: Quitar contenedor GIF
            if (gifContainer && gifContainer.parentNode) {
                gifContainer.style.animation = 'fadeOut 0.3s ease-in';
                setTimeout(() => {
                    if (gifContainer.parentNode) {
                        gifContainer.parentNode.removeChild(gifContainer);
                    }
                }, 300);
                console.log('✅ REGISTRO: Contenedor GIF removido');
            }
        } else {
            // LOGIN: Añadir contenedor GIF GRANDE
            if (!gifContainer || !gifContainer.parentNode) {
                const container = createGifContainer();
                loginCard.appendChild(container);
                console.log('✅ LOGIN: Contenedor GIF grande añadido');
            }
        }
    }
    
    if (cardFlipper && toggleBtn) {
        setTimeout(() => {
            manageGifContainer();
        }, 200);
        
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            
            isFlipped = !isFlipped;
            
            if (isFlipped) {
                cardFlipper.classList.add('flipped');
                toggleBtn.textContent = '¿Ya tienes cuenta? Inicia sesión';
            } else {
                cardFlipper.classList.remove('flipped');
                toggleBtn.textContent = '¿No tienes cuenta? Regístrate';
            }
            
            setTimeout(() => {
                manageGifContainer();
            }, 100);
        });
        
        console.log('✅ Contenedor GIF tamaño completo inicializado');
        console.log('📁 Buscando: calendario.gif');
        console.log('📍 En: public/assets/gif/');
    }
    
    // Estilos de animación
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
        .login-gif-container { 
            transition: all 0.3s ease; 
        }
        .login-gif-container h4 {
            animation: slideInLeft 0.6s ease-out 0.2s both;
        }
        .login-gif-container p {
            animation: slideInLeft 0.6s ease-out 0.4s both;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    `;
    document.head.appendChild(style);
});