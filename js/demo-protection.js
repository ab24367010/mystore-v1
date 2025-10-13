// ========================================
// Demo Protection - MyStore
// Хэрэглэгч demo хуулбарлахаас хамгаалах
// ========================================

(function() {
    'use strict';
    
    // Тохиргоо
    const config = {
        disableRightClick: true,      // Right-click хориглох
        disableF12: true,              // F12 хориглох
        disableCtrlShiftI: true,       // Ctrl+Shift+I хориглох
        disableCtrlU: true,            // Ctrl+U (view source) хориглох
        disableCopy: true,             // Text хуулах хориглох
        showWarning: true,             // Анхааруулга харуулах
        watermark: true                // Watermark нэмэх
    };
    
    // Right-click хориглох
    if (config.disableRightClick) {
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            if (config.showWarning) {
                showWarning('Right-click хориглогдсон байна');
            }
            return false;
        });
    }
    
    // F12 болон DevTools хориглох
    if (config.disableF12) {
        document.addEventListener('keydown', function(e) {
            // F12
            if (e.key === 'F12') {
                e.preventDefault();
                if (config.showWarning) {
                    showWarning('Developer tools хориглогдсон байна');
                }
                return false;
            }
            
            // Ctrl+Shift+I (DevTools)
            if (config.disableCtrlShiftI && e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
                if (config.showWarning) {
                    showWarning('Developer tools хориглогдсон байна');
                }
                return false;
            }
            
            // Ctrl+Shift+J (Console)
            if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                e.preventDefault();
                return false;
            }
            
            // Ctrl+U (View Source)
            if (config.disableCtrlU && e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                if (config.showWarning) {
                    showWarning('Source харах хориглогдсон байна');
                }
                return false;
            }
        });
    }
    
    // Text хуулах хориглох
    if (config.disableCopy) {
        document.addEventListener('copy', function(e) {
            e.preventDefault();
            if (config.showWarning) {
                showWarning('Хуулах хориглогдсон байна');
            }
            return false;
        });
        
        // Selection хориглох
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
            return false;
        });
    }
    
    // Watermark нэмэх
    if (config.watermark) {
        const watermark = document.createElement('div');
        watermark.innerHTML = 'DEMO - MyStore';
        watermark.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 99999;
            pointer-events: none;
            font-family: Arial, sans-serif;
        `;
        document.body.appendChild(watermark);
    }
    
    // Анхааруулга харуулах функц
    function showWarning(message) {
        const warning = document.createElement('div');
        warning.innerHTML = `⚠️ ${message}`;
        warning.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 14px;
            z-index: 99999;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
            font-family: Arial, sans-serif;
        `;
        
        document.body.appendChild(warning);
        
        // 3 секундын дараа арилгах
        setTimeout(() => {
            warning.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => warning.remove(), 300);
        }, 3000);
    }
    
    // Animation CSS нэмэх
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // DevTools илрүүлэх (advanced)
    let devtoolsOpen = false;
    const threshold = 160;
    
    setInterval(() => {
        if (window.outerWidth - window.innerWidth > threshold || 
            window.outerHeight - window.innerHeight > threshold) {
            if (!devtoolsOpen) {
                devtoolsOpen = true;
                if (config.showWarning) {
                    showWarning('Developer tools хориглогдсон байна');
                }
            }
        } else {
            devtoolsOpen = false;
        }
    }, 1000);
    
    console.log('%c⚠️ STOP!', 'color: red; font-size: 60px; font-weight: bold;');
    console.log('%cЭнэ бол demo хувилбар. Хуулах, засах хориглогдоно.', 'font-size: 16px;');
    console.log('%cTemplate худалдаж авахыг хүсвэл: https://yoursite.com', 'font-size: 14px; color: blue;');
    
})();