/**
 * AionResponder - assets/js/main.js
 * Universal utilities for theme switching, input masks, and password visibility toggles.
 */

// Immediate Theme Sync (prevents flash of wrong theme on load)
(function applyInitialTheme() {
    try {
        const tema = localStorage.getItem('tema') || 'dark';
        document.documentElement.setAttribute('data-theme', tema);
    } catch(e) {}
})();

document.addEventListener('DOMContentLoaded', () => {
    initThemeSystem();
    initPasswordToggles();
    initInputMasks();
});

/**
 * Global Theme System - handles theme toggle button and localStorage persistence
 */
function initThemeSystem() {
    const btnTema = document.getElementById('btnTema');
    const currentTheme = localStorage.getItem('tema') || 'dark';
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    if (btnTema) {
        btnTema.innerHTML = currentTheme === 'light' ? '<i class="fa fa-sun"></i>' : '<i class="fa fa-moon"></i>';
        
        // Clone button to ensure fresh listener
        const newBtn = btnTema.cloneNode(true);
        if (btnTema.parentNode) {
            btnTema.parentNode.replaceChild(newBtn, btnTema);
        }
        
        newBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const temaAtual = document.documentElement.getAttribute('data-theme') || 'dark';
            const novoTema = temaAtual === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', novoTema);
            localStorage.setItem('tema', novoTema);
            newBtn.innerHTML = novoTema === 'light' ? '<i class="fa fa-sun"></i>' : '<i class="fa fa-moon"></i>';
        });
    }
}

/**
 * Wraps every password input inside a container with a relative position
 * and appends an interactive eye icon to toggle visibility.
 */
function initPasswordToggles() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        if (input.parentElement.classList.contains('password-wrapper')) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'password-wrapper';
        
        if (input.style.width) {
            wrapper.style.width = input.style.width;
            input.style.width = '100%';
        }
        
        wrapper.style.position = 'relative';
        if (input.style.display === 'none') {
            wrapper.style.display = 'none';
        } else {
            wrapper.style.display = 'block';
        }
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const toggleIcon = document.createElement('i');
        toggleIcon.className = 'fa fa-eye password-toggle-icon';
        
        toggleIcon.addEventListener('click', () => {
            if (input.type === 'password') {
                input.type = 'text';
                toggleIcon.className = 'fa fa-eye-slash password-toggle-icon';
            } else {
                input.type = 'password';
                toggleIcon.className = 'fa fa-eye password-toggle-icon';
            }
        });

        wrapper.appendChild(toggleIcon);
        input.style.paddingRight = '40px';
    });
}

/**
 * Formats raw digits to Brazilian phone format:
 * - With DDI: +55 (99) 99999-9999 or +55 (99) 9999-9999
 * - Without DDI: (99) 99999-9999 or (99) 9999-9999
 */
function formatarTelefone(value) {
    if (!value) return "";
    value = value.replace(/\D/g, "");
    
    if (value.startsWith("55") && value.length > 10) {
        let ddi = value.substring(0, 2);
        let resto = value.substring(2);
        if (resto.length > 9) {
            return `+${ddi} (${resto.substring(0, 2)}) ${resto.substring(2, 7)}-${resto.substring(7, 11)}`;
        } else if (resto.length > 5) {
            return `+${ddi} (${resto.substring(0, 2)}) ${resto.substring(2, 6)}-${resto.substring(6, 10)}`;
        } else if (resto.length > 2) {
            return `+${ddi} (${resto.substring(0, 2)}) ${resto.substring(2)}`;
        } else if (resto.length > 0) {
            return `+${ddi} (${resto}`;
        }
        return `+${ddi}`;
    }
    
    if (value.length > 10) {
        return `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7, 11)}`;
    } else if (value.length > 6) {
        return `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6, 10)}`;
    } else if (value.length > 2) {
        return `(${value.substring(0, 2)}) ${value.substring(2)}`;
    } else if (value.length > 0) {
        return `(${value}`;
    }
    return value;
}

/**
 * Initializes listeners for phone, zona and seção inputs.
 */
function initInputMasks() {
    const phoneInputs = document.querySelectorAll('input[name="telefone"]');
    phoneInputs.forEach(input => {
        if (input.value) {
            input.value = formatarTelefone(input.value);
        }
        
        input.addEventListener('input', (e) => {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const formatted = formatarTelefone(oldValue);
            e.target.value = formatted;
            
            if (cursorPosition !== null && oldValue.length !== formatted.length) {
                const diff = formatted.length - oldValue.length;
                e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            }
        });
    });

    const zonaInputs = document.querySelectorAll('input[name="zona"]');
    zonaInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 3) val = val.substring(0, 3);
            e.target.value = val;
        });
    });

    const secaoInputs = document.querySelectorAll('input[name="secao"]');
    secaoInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 4) val = val.substring(0, 4);
            e.target.value = val;
        });
    });

    const modal = document.getElementById('modalNovoContato');
    if (modal) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'style' && modal.style.display === 'flex') {
                    const phoneInput = modal.querySelector('input[name="telefone"]');
                    if (phoneInput) {
                        phoneInput.value = formatarTelefone(phoneInput.value);
                    }
                }
            });
        });
        observer.observe(modal, { attributes: true });
    }
}
