/**
 * Sistema de Temas Globais - MegaFisio IA
 * Gerencia temas e preferências em todo o sistema
 */

class TemaGlobal {
    constructor() {
        this.temaAtual = 'claro';
        this.idiomaAtual = 'pt-BR';
        this.init();
    }

    init() {
        // Carregar preferências do usuário
        this.carregarPreferencias();
        
        // Aplicar tema inicial
        this.aplicarTema(this.temaAtual);
        
        // Escutar mudanças no tema do sistema
        this.escutarMudancasSistema();
    }

    async carregarPreferencias() {
        try {
            // Buscar preferências do servidor se usuário logado
            const response = await fetch('/profile/get-preferences', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.temaAtual = data.preferences.theme || 'claro';
                    this.idiomaAtual = data.preferences.language || 'pt-BR';
                    
                    // Aplicar preferências carregadas
                    this.aplicarTema(this.temaAtual);
                    this.aplicarIdioma(this.idiomaAtual);
                    
                    console.log('Preferências carregadas:', data.preferences);
                    return;
                }
            }
        } catch (e) {
            console.log('Usuário não logado ou erro ao carregar preferências:', e.message);
        }
        
        // Fallback: usar localStorage ou padrão
        this.temaAtual = localStorage.getItem('tema-megafisio') || 'claro';
        this.idiomaAtual = localStorage.getItem('idioma-megafisio') || 'pt-BR';
        
        this.aplicarTema(this.temaAtual);
        this.aplicarIdioma(this.idiomaAtual);
    }

    aplicarTema(tema) {
        const html = document.documentElement;
        const body = document.body;
        
        // Remover classes de tema existentes em ambos os elementos
        html.classList.remove('tema-claro', 'tema-escuro', 'tema-auto');
        body.classList.remove('tema-claro', 'tema-escuro', 'tema-auto');
        
        // Aplicar novo tema em ambos os elementos
        switch(tema) {
            case 'escuro':
                html.classList.add('tema-escuro');
                body.classList.add('tema-escuro');
                break;
            case 'auto':
                html.classList.add('tema-auto');
                body.classList.add('tema-auto');
                // Detectar preferência do sistema
                const temaSistema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'escuro' : 'claro';
                if (temaSistema === 'escuro') {
                    html.classList.add('tema-escuro');
                    body.classList.add('tema-escuro');
                } else {
                    html.classList.add('tema-claro');
                    body.classList.add('tema-claro');
                }
                break;
            default:
                html.classList.add('tema-claro');
                body.classList.add('tema-claro');
        }
        
        this.temaAtual = tema;
        
        // Salvar no localStorage como backup
        localStorage.setItem('tema-megafisio', tema);
        
        console.log('Tema aplicado globalmente:', tema);
    }

    aplicarIdioma(idioma) {
        // Usar dicionário completo de traduções
        if (!window.TRADUCOES_COMPLETAS) {
            console.warn('Dicionário de traduções não carregado');
            return;
        }
        
        const traducao = window.TRADUCOES_COMPLETAS[idioma];
        if (!traducao) {
            console.warn('Idioma não suportado:', idioma);
            return;
        }

        // 1. Traduzir elementos com atributo data-translate
        const elementosComTraducao = document.querySelectorAll('[data-translate]');
        elementosComTraducao.forEach(elemento => {
            const chave = elemento.getAttribute('data-translate');
            if (traducao[chave]) {
                elemento.textContent = traducao[chave];
            }
        });

        // 2. Traduzir TODOS os elementos do sistema automaticamente
        this.traduzirTodoSistema(traducao);

        this.idiomaAtual = idioma;
        
        // Salvar no localStorage como backup
        localStorage.setItem('idioma-megafisio', idioma);
        
        console.log('Idioma aplicado globalmente:', idioma);
    }


    traduzirTodoSistema(traducao) {
        try {
            console.log('🌐 Traduzindo TODO o sistema...');
            
            // Usar TreeWalker para percorrer TODOS os nós de texto
            const walker = document.createTreeWalker(
                document.body,
                NodeFilter.SHOW_TEXT,
                {
                    acceptNode: function(node) {
                        // Ignorar scripts, styles e elementos vazios
                        if (node.parentElement && 
                            (node.parentElement.tagName === 'SCRIPT' || 
                             node.parentElement.tagName === 'STYLE' ||
                             node.parentElement.hasAttribute('data-no-translate'))) {
                            return NodeFilter.FILTER_REJECT;
                        }
                        
                        const texto = node.textContent.trim();
                        if (texto.length > 0) {
                            return NodeFilter.FILTER_ACCEPT;
                        }
                        return NodeFilter.FILTER_REJECT;
                    }
                }
            );
            
            let node;
            let contadorTraducoes = 0;
            
            // Percorrer todos os nós de texto e traduzir
            while (node = walker.nextNode()) {
                const textoOriginal = node.textContent.trim();
                
                if (textoOriginal && traducao[textoOriginal]) {
                    node.textContent = node.textContent.replace(textoOriginal, traducao[textoOriginal]);
                    contadorTraducoes++;
                }
            }
            
            // Traduzir TODOS os tipos de elementos HTML
            
            // 1. Placeholders
            document.querySelectorAll('[placeholder]').forEach(el => {
                const placeholder = el.getAttribute('placeholder');
                if (placeholder && traducao[placeholder]) {
                    el.setAttribute('placeholder', traducao[placeholder]);
                    contadorTraducoes++;
                }
            });
            
            // 2. Títulos
            document.querySelectorAll('[title]').forEach(el => {
                const title = el.getAttribute('title');
                if (title && traducao[title]) {
                    el.setAttribute('title', traducao[title]);
                    contadorTraducoes++;
                }
            });
            
            // 3. Valores de inputs
            document.querySelectorAll('input[value]').forEach(el => {
                if (el.type === 'button' || el.type === 'submit' || el.type === 'reset') {
                    const value = el.value;
                    if (value && traducao[value]) {
                        el.value = traducao[value];
                        contadorTraducoes++;
                    }
                }
            });
            
            // 4. Alt de imagens
            document.querySelectorAll('img[alt]').forEach(el => {
                const alt = el.getAttribute('alt');
                if (alt && traducao[alt]) {
                    el.setAttribute('alt', traducao[alt]);
                    contadorTraducoes++;
                }
            });
            
            // 5. Aria-labels
            document.querySelectorAll('[aria-label]').forEach(el => {
                const label = el.getAttribute('aria-label');
                if (label && traducao[label]) {
                    el.setAttribute('aria-label', traducao[label]);
                    contadorTraducoes++;
                }
            });
            
            // 6. Data-tooltip
            document.querySelectorAll('[data-tooltip]').forEach(el => {
                const tooltip = el.getAttribute('data-tooltip');
                if (tooltip && traducao[tooltip]) {
                    el.setAttribute('data-tooltip', traducao[tooltip]);
                    contadorTraducoes++;
                }
            });
            
            console.log(`✅ Sistema traduzido: ${contadorTraducoes} elementos traduzidos`);
            
            // Se não traduziu muitos elementos, fazer uma varredura mais agressiva
            if (contadorTraducoes < 50) {
                this.traducaoAgressiva(traducao);
            }
            
        } catch (e) {
            console.warn('Erro na tradução automática:', e.message);
        }
    }
    
    traducaoAgressiva(traducao) {
        console.log('🔥 Executando tradução agressiva...');
        
        // Pegar TODOS os elementos que podem conter texto
        const todosElementos = document.querySelectorAll('*');
        let contadorExtra = 0;
        
        todosElementos.forEach(elemento => {
            // Ignorar scripts e styles
            if (elemento.tagName === 'SCRIPT' || 
                elemento.tagName === 'STYLE' || 
                elemento.hasAttribute('data-no-translate')) {
                return;
            }
            
            // Se o elemento tem apenas texto (sem filhos HTML)
            if (elemento.childNodes.length === 1 && elemento.childNodes[0].nodeType === Node.TEXT_NODE) {
                const texto = elemento.textContent.trim();
                if (texto && traducao[texto]) {
                    elemento.textContent = traducao[texto];
                    contadorExtra++;
                }
            }
        });
        
        console.log(`✅ Tradução agressiva: +${contadorExtra} elementos traduzidos`);
    }

    traduzirPlaceholders(traducao) {
        // Mantido por compatibilidade, mas agora é chamado dentro de traduzirTodoSistema
    }

    escutarMudancasSistema() {
        // Detectar mudanças no tema do sistema para tema automático
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (this.temaAtual === 'auto') {
                    this.aplicarTema('auto');
                }
            });
        }
        
        // Observar mudanças no DOM para traduzir conteúdo dinâmico
        this.iniciarObservadorDOM();
    }
    
    iniciarObservadorDOM() {
        if (this.observador) {
            this.observador.disconnect();
        }
        
        this.observador = new MutationObserver((mutacoes) => {
            // Se não estiver em português, aplicar traduções
            if (this.idiomaAtual && this.idiomaAtual !== 'pt-BR') {
                clearTimeout(this.timeoutTraducao);
                this.timeoutTraducao = setTimeout(() => {
                    const traducao = window.TRADUCOES_COMPLETAS?.[this.idiomaAtual];
                    if (traducao) {
                        this.traduzirTodoSistema(traducao);
                    }
                }, 300);
            }
        });
        
        this.observador.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: false,
            attributes: false
        });
    }


    // Métodos públicos para usar em outras páginas
    mudarTema(novoTema) {
        this.aplicarTema(novoTema);
    }

    mudarIdioma(novoIdioma) {
        this.aplicarIdioma(novoIdioma);
        
        // Forçar retradução após mudança de idioma
        setTimeout(() => {
            const traducao = window.TRADUCOES_COMPLETAS?.[novoIdioma];
            if (traducao) {
                this.traduzirTodoSistema(traducao);
            }
        }, 100);
    }

    obterTemaAtual() {
        return this.temaAtual;
    }

    obterIdiomaAtual() {
        return this.idiomaAtual;
    }
    
    // Método para forçar tradução completa
    forcarTraducaoCompleta() {
        if (this.idiomaAtual && window.TRADUCOES_COMPLETAS?.[this.idiomaAtual]) {
            console.log('🔄 Forçando tradução completa do sistema...');
            this.traduzirTodoSistema(window.TRADUCOES_COMPLETAS[this.idiomaAtual]);
            this.traducaoAgressiva(window.TRADUCOES_COMPLETAS[this.idiomaAtual]);
        }
    }
}

// Inicializar sistema de temas global quando DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    window.temaGlobal = new TemaGlobal();
});

// Disponibilizar globalmente
window.TemaGlobal = TemaGlobal;