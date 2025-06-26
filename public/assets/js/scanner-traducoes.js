/**
 * Scanner de Traduções - MegaFisio IA
 * Identifica automaticamente todos os textos que precisam ser traduzidos
 */

class ScannerTraducoes {
    constructor() {
        this.textosEncontrados = new Set();
        this.textosIgnorados = new Set([
            '', ' ', '\n', '\t', '|', '-', '/', '\\', ':', ';', ',', '.', '!', '?',
            '(', ')', '[', ']', '{', '}', '<', '>', '=', '+', '*', '&', '%', '$',
            '#', '@', '~', '`', '"', "'", '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ]);
    }

    escanearPagina() {
        console.log('🔍 Iniciando escaneamento de textos não traduzidos...');
        
        this.textosEncontrados.clear();
        
        // Elementos que contêm texto
        const seletores = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'span', 'div', 'td', 'th',
            'label', 'button', 'a', 'li',
            'option', 'small', 'strong', 'em', 'b', 'i',
            '.menu-texto', '.titulo-pagina', '.stat-label-escuro',
            '.card-title', '.card-text', '.btn', '.badge'
        ];
        
        seletores.forEach(seletor => {
            const elementos = document.querySelectorAll(seletor);
            elementos.forEach(elemento => {
                this.extrairTexto(elemento);
            });
        });
        
        // Atributos importantes
        this.escanearAtributos();
        
        // Gerar relatório
        return this.gerarRelatorio();
    }
    
    extrairTexto(elemento) {
        // Pular elementos de script, style
        if (elemento.closest('script, style')) return;
        
        // Se tem muitos filhos, provavelmente não é um texto simples
        if (elemento.children.length > 2) return;
        
        // Pegar apenas o texto direto do elemento
        const textoCompleto = elemento.textContent.trim();
        
        // Se não tem filhos, é um texto puro
        if (elemento.children.length === 0 && textoCompleto) {
            this.adicionarTexto(textoCompleto);
        } else {
            // Tentar pegar apenas o texto direto (não dos filhos)
            const clone = elemento.cloneNode(true);
            // Remover todos os filhos do clone
            while (clone.firstChild) {
                if (clone.firstChild.nodeType === Node.TEXT_NODE) {
                    const texto = clone.firstChild.textContent.trim();
                    if (texto) {
                        this.adicionarTexto(texto);
                    }
                }
                clone.removeChild(clone.firstChild);
            }
        }
    }
    
    escanearAtributos() {
        // Placeholders
        document.querySelectorAll('[placeholder]').forEach(el => {
            const placeholder = el.getAttribute('placeholder');
            if (placeholder) this.adicionarTexto(placeholder);
        });
        
        // Títulos
        document.querySelectorAll('[title]').forEach(el => {
            const title = el.getAttribute('title');
            if (title) this.adicionarTexto(title);
        });
        
        // Alt de imagens
        document.querySelectorAll('img[alt]').forEach(el => {
            const alt = el.getAttribute('alt');
            if (alt) this.adicionarTexto(alt);
        });
        
        // Valores de botões
        document.querySelectorAll('input[type="button"], input[type="submit"]').forEach(el => {
            if (el.value) this.adicionarTexto(el.value);
        });
    }
    
    adicionarTexto(texto) {
        texto = texto.trim();
        
        // Ignorar textos vazios ou muito curtos
        if (!texto || texto.length < 2) return;
        
        // Ignorar se for apenas números ou caracteres especiais
        if (this.textosIgnorados.has(texto)) return;
        
        // Ignorar se for apenas números
        if (/^\d+$/.test(texto)) return;
        
        // Ignorar se for uma data
        if (/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}$/.test(texto)) return;
        
        // Ignorar emails
        if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(texto)) return;
        
        // Ignorar URLs
        if (/^https?:\/\//.test(texto)) return;
        
        // Adicionar à lista
        this.textosEncontrados.add(texto);
    }
    
    gerarRelatorio() {
        const textos = Array.from(this.textosEncontrados).sort();
        const traducoes = window.TRADUCOES_COMPLETAS || {};
        
        const naoTraduzidos = {
            'pt-BR': [],
            'en-US': [],
            'es-ES': []
        };
        
        textos.forEach(texto => {
            // Verificar se existe no dicionário pt-BR
            if (!traducoes['pt-BR'] || !traducoes['pt-BR'][texto]) {
                naoTraduzidos['pt-BR'].push(texto);
            }
            
            // Verificar se existe tradução para inglês
            if (!traducoes['en-US'] || !traducoes['en-US'][texto]) {
                naoTraduzidos['en-US'].push(texto);
            }
            
            // Verificar se existe tradução para espanhol
            if (!traducoes['es-ES'] || !traducoes['es-ES'][texto]) {
                naoTraduzidos['es-ES'].push(texto);
            }
        });
        
        console.log('📊 RELATÓRIO DE TEXTOS ENCONTRADOS');
        console.log('==================================');
        console.log(`Total de textos únicos: ${textos.length}`);
        console.log(`Sem tradução pt-BR: ${naoTraduzidos['pt-BR'].length}`);
        console.log(`Sem tradução en-US: ${naoTraduzidos['en-US'].length}`);
        console.log(`Sem tradução es-ES: ${naoTraduzidos['es-ES'].length}`);
        
        if (naoTraduzidos['en-US'].length > 0) {
            console.log('\n❌ TEXTOS SEM TRADUÇÃO PARA INGLÊS:');
            naoTraduzidos['en-US'].forEach(texto => {
                console.log(`  "${texto}"`);
            });
        }
        
        return {
            totalTextos: textos.length,
            todosTextos: textos,
            naoTraduzidos: naoTraduzidos
        };
    }
    
    gerarCodigoTraducoes() {
        const relatorio = this.gerarRelatorio();
        
        if (relatorio.naoTraduzidos['en-US'].length === 0) {
            console.log('✅ Todas as traduções já existem!');
            return;
        }
        
        console.log('\n📝 CÓDIGO PARA ADICIONAR AO DICIONÁRIO:');
        console.log('=====================================');
        
        // Gerar código para en-US
        console.log('\n// Adicionar em en-US:');
        relatorio.naoTraduzidos['en-US'].forEach(texto => {
            console.log(`        '${texto}': '${this.traduzirAutomatico(texto, 'en')}',`);
        });
        
        // Gerar código para es-ES
        console.log('\n// Adicionar em es-ES:');
        relatorio.naoTraduzidos['es-ES'].forEach(texto => {
            console.log(`        '${texto}': '${this.traduzirAutomatico(texto, 'es')}',`);
        });
    }
    
    traduzirAutomatico(texto, idioma) {
        // Traduções básicas automáticas (podem precisar de ajustes)
        const traducoes = {
            en: {
                'Ativo': 'Active',
                'Inativo': 'Inactive',
                'Online': 'Online',
                'Offline': 'Offline',
                'Sim': 'Yes',
                'Não': 'No',
                'Todos': 'All',
                'Todas': 'All',
                'Nenhum': 'None',
                'Nenhuma': 'None',
                'Ver mais': 'See more',
                'Ver menos': 'See less',
                'Carregando': 'Loading',
                'Erro': 'Error',
                'Sucesso': 'Success',
                'Aviso': 'Warning',
                'Informação': 'Information',
                'Confirmação': 'Confirmation',
                'Pergunta': 'Question'
            },
            es: {
                'Ativo': 'Activo',
                'Inativo': 'Inactivo',
                'Online': 'En línea',
                'Offline': 'Fuera de línea',
                'Sim': 'Sí',
                'Não': 'No',
                'Todos': 'Todos',
                'Todas': 'Todas',
                'Nenhum': 'Ninguno',
                'Nenhuma': 'Ninguna',
                'Ver mais': 'Ver más',
                'Ver menos': 'Ver menos',
                'Carregando': 'Cargando',
                'Erro': 'Error',
                'Sucesso': 'Éxito',
                'Aviso': 'Aviso',
                'Informação': 'Información',
                'Confirmação': 'Confirmación',
                'Pergunta': 'Pregunta'
            }
        };
        
        return traducoes[idioma]?.[texto] || texto;
    }
}

// Disponibilizar globalmente
window.ScannerTraducoes = ScannerTraducoes;

// Comandos úteis
window.escanearTextos = function() {
    const scanner = new ScannerTraducoes();
    return scanner.escanearPagina();
};

window.gerarTraducoes = function() {
    const scanner = new ScannerTraducoes();
    scanner.escanearPagina();
    scanner.gerarCodigoTraducoes();
};

console.log('🔧 Scanner de Traduções carregado!');
console.log('📝 Use: escanearTextos() ou gerarTraducoes()');