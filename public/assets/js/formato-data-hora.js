/**
 * Sistema de Formatação de Data e Hora - MegaFisio IA
 * Aplica timezone e formato de data baseado nas preferências do usuário
 */

class FormatoDataHora {
    constructor() {
        this.timezone = 'America/Sao_Paulo';
        this.formatoData = 'dd/MM/yyyy';
        this.formatoHora = 'HH:mm:ss';
        this.init();
    }

    async init() {
        await this.carregarPreferencias();
        this.aplicarFormatos();
        this.iniciarRelogios();
    }

    async carregarPreferencias() {
        try {
            const response = await fetch('/profile/get-preferences', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.preferences) {
                    this.timezone = data.preferences.timezone || 'America/Sao_Paulo';
                    this.formatoData = data.preferences.date_format || 'dd/MM/yyyy';
                    console.log('Preferências de data/hora carregadas:', {
                        timezone: this.timezone,
                        formatoData: this.formatoData
                    });
                }
            }
        } catch (e) {
            console.log('Usando configurações padrão de data/hora');
        }
    }

    formatarData(data, incluirHora = true) {
        if (!data) return '';
        
        const dataObj = typeof data === 'string' ? new Date(data) : data;
        
        const opcoes = {
            timeZone: this.timezone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        };

        if (incluirHora) {
            opcoes.hour = '2-digit';
            opcoes.minute = '2-digit';
            opcoes.second = '2-digit';
            opcoes.hour12 = false;
        }

        // Converter formato para padrão JavaScript
        let formato = 'pt-BR';
        switch (this.formatoData) {
            case 'MM/dd/yyyy':
                formato = 'en-US';
                break;
            case 'yyyy-MM-dd':
                formato = 'sv-SE';
                break;
            case 'dd-MM-yyyy':
            case 'dd/MM/yyyy':
            default:
                formato = 'pt-BR';
        }

        let resultado = dataObj.toLocaleString(formato, opcoes);
        
        // Ajustar separadores se necessário
        if (this.formatoData === 'dd-MM-yyyy') {
            resultado = resultado.replace(/\//g, '-');
        } else if (this.formatoData === 'yyyy-MM-dd') {
            // Para formato ISO, reorganizar
            const partes = resultado.split(/[\s,]/);
            if (partes.length >= 2) {
                const [dataParte, horaParte] = partes;
                const [dia, mes, ano] = dataParte.split(/[-\/]/);
                resultado = `${ano}-${mes}-${dia}`;
                if (incluirHora && horaParte) {
                    resultado += ` ${horaParte}`;
                }
            }
        }

        return resultado;
    }

    formatarHoraAtual() {
        const agora = new Date();
        return this.formatarData(agora, true);
    }

    aplicarFormatos() {
        // Aplicar formatação em elementos com atributo data-format-date
        const elementosData = document.querySelectorAll('[data-format-date]');
        elementosData.forEach(elemento => {
            const dataISO = elemento.getAttribute('data-format-date');
            const incluirHora = elemento.hasAttribute('data-include-time');
            
            if (dataISO) {
                elemento.textContent = this.formatarData(dataISO, incluirHora);
            }
        });

        // Aplicar formatação em elementos com classe de data
        const elementosDataClasse = document.querySelectorAll('.data-formatada, .datetime-formatted');
        elementosDataClasse.forEach(elemento => {
            const dataTexto = elemento.textContent.trim();
            if (dataTexto && dataTexto !== '') {
                try {
                    const data = new Date(dataTexto);
                    if (!isNaN(data.getTime())) {
                        const incluirHora = elemento.classList.contains('datetime-formatted') || 
                                          elemento.textContent.includes(':');
                        elemento.textContent = this.formatarData(data, incluirHora);
                    }
                } catch (e) {
                    console.log('Erro ao formatar data:', dataTexto);
                }
            }
        });
    }

    iniciarRelogios() {
        // Atualizar relógios em tempo real
        const relogios = document.querySelectorAll('#dataHora, #dataHoraTexto, .relogio-tempo-real');
        
        const atualizarRelogios = () => {
            const horaAtual = this.formatarHoraAtual();
            relogios.forEach(relogio => {
                relogio.textContent = horaAtual;
            });
        };

        // Atualizar imediatamente
        atualizarRelogios();
        
        // Atualizar a cada segundo
        setInterval(atualizarRelogios, 1000);
    }

    // Método público para formatar datas dinamicamente
    formatarDataPublica(data, incluirHora = true) {
        return this.formatarData(data, incluirHora);
    }

    // Método para obter timezone atual
    obterTimezone() {
        return this.timezone;
    }

    // Método para obter formato de data atual
    obterFormatoData() {
        return this.formatoData;
    }
}

// Inicializar sistema de formatação quando DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    window.formatoDataHora = new FormatoDataHora();
});

// Disponibilizar globalmente
window.FormatoDataHora = FormatoDataHora;