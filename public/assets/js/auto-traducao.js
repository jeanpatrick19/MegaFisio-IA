/**
 * Sistema de Auto-Tradução - MegaFisio IA
 * Adiciona automaticamente traduções que faltam
 */

// Função para adicionar traduções em runtime
window.adicionarTraducoesEmFalta = function() {
    if (!window.TRADUCOES_COMPLETAS) {
        console.error('Dicionário de traduções não carregado!');
        return;
    }
    
    // Traduções adicionais comuns que podem estar faltando
    const traducoesAdicionais = {
        'pt-BR': {
            // Status e Estados
            'Ativo': 'Ativo',
            'Inativo': 'Inativo',
            'Ativa': 'Ativa',
            'Inativa': 'Inativa',
            'Online': 'Online',
            'Offline': 'Offline',
            'Disponível': 'Disponível',
            'Indisponível': 'Indisponível',
            'Pendente': 'Pendente',
            'Aprovado': 'Aprovado',
            'Rejeitado': 'Rejeitado',
            'Em andamento': 'Em andamento',
            'Concluído': 'Concluído',
            'Cancelado': 'Cancelado',
            
            // Tabelas
            'Nenhum registro encontrado': 'Nenhum registro encontrado',
            'Mostrando de': 'Mostrando de',
            'até': 'até',
            'de um total de': 'de um total de',
            'registros': 'registros',
            'Primeira': 'Primeira',
            'Última': 'Última',
            'Próxima': 'Próxima',
            'Anterior': 'Anterior',
            
            // Formulários
            'Campo obrigatório': 'Campo obrigatório',
            'Selecione uma opção': 'Selecione uma opção',
            'Digite aqui': 'Digite aqui',
            'Escolha um arquivo': 'Escolha um arquivo',
            'Nenhum arquivo selecionado': 'Nenhum arquivo selecionado',
            
            // Ações
            'Ver detalhes': 'Ver detalhes',
            'Ver mais': 'Ver mais',
            'Ver menos': 'Ver menos',
            'Mostrar mais': 'Mostrar mais',
            'Mostrar menos': 'Mostrar menos',
            'Expandir': 'Expandir',
            'Recolher': 'Recolher',
            'Ordenar': 'Ordenar',
            'Resetar': 'Resetar',
            'Aplicar filtros': 'Aplicar filtros',
            'Limpar filtros': 'Limpar filtros',
            
            // Tempo
            'Hoje': 'Hoje',
            'Ontem': 'Ontem',
            'Esta semana': 'Esta semana',
            'Este mês': 'Este mês',
            'Este ano': 'Este ano',
            'Último acesso': 'Último acesso',
            'Nunca': 'Nunca',
            
            // Confirmações
            'Tem certeza?': 'Tem certeza?',
            'Esta ação não pode ser desfeita': 'Esta ação não pode ser desfeita',
            'Sim, continuar': 'Sim, continuar',
            'Não, cancelar': 'Não, cancelar',
            
            // Login/Autenticação
            'Fazer login': 'Fazer login',
            'Fazer logout': 'Fazer logout',
            'Lembrar de mim': 'Lembrar de mim',
            'Esqueci a senha': 'Esqueci a senha',
            'Criar conta': 'Criar conta',
            'Já tem uma conta?': 'Já tem uma conta?',
            'Ainda não tem conta?': 'Ainda não tem conta?',
            
            // Números e Quantidades
            'Total': 'Total',
            'Subtotal': 'Subtotal',
            'Quantidade': 'Quantidade',
            'Valor': 'Valor',
            'Preço': 'Preço',
            'Desconto': 'Desconto',
            'Taxa': 'Taxa',
            'Imposto': 'Imposto'
        },
        'en-US': {
            // Status e Estados
            'Ativo': 'Active',
            'Inativo': 'Inactive',
            'Ativa': 'Active',
            'Inativa': 'Inactive',
            'Online': 'Online',
            'Offline': 'Offline',
            'Disponível': 'Available',
            'Indisponível': 'Unavailable',
            'Pendente': 'Pending',
            'Aprovado': 'Approved',
            'Rejeitado': 'Rejected',
            'Em andamento': 'In progress',
            'Concluído': 'Completed',
            'Cancelado': 'Canceled',
            
            // Tabelas
            'Nenhum registro encontrado': 'No records found',
            'Mostrando de': 'Showing from',
            'até': 'to',
            'de um total de': 'of a total of',
            'registros': 'records',
            'Primeira': 'First',
            'Última': 'Last',
            'Próxima': 'Next',
            'Anterior': 'Previous',
            
            // Formulários
            'Campo obrigatório': 'Required field',
            'Selecione uma opção': 'Select an option',
            'Digite aqui': 'Type here',
            'Escolha um arquivo': 'Choose a file',
            'Nenhum arquivo selecionado': 'No file selected',
            
            // Ações
            'Ver detalhes': 'View details',
            'Ver mais': 'See more',
            'Ver menos': 'See less',
            'Mostrar mais': 'Show more',
            'Mostrar menos': 'Show less',
            'Expandir': 'Expand',
            'Recolher': 'Collapse',
            'Ordenar': 'Sort',
            'Resetar': 'Reset',
            'Aplicar filtros': 'Apply filters',
            'Limpar filtros': 'Clear filters',
            
            // Tempo
            'Hoje': 'Today',
            'Ontem': 'Yesterday',
            'Esta semana': 'This week',
            'Este mês': 'This month',
            'Este ano': 'This year',
            'Último acesso': 'Last access',
            'Nunca': 'Never',
            
            // Confirmações
            'Tem certeza?': 'Are you sure?',
            'Esta ação não pode ser desfeita': 'This action cannot be undone',
            'Sim, continuar': 'Yes, continue',
            'Não, cancelar': 'No, cancel',
            
            // Login/Autenticação
            'Fazer login': 'Log in',
            'Fazer logout': 'Log out',
            'Lembrar de mim': 'Remember me',
            'Esqueci a senha': 'Forgot password',
            'Criar conta': 'Create account',
            'Já tem uma conta?': 'Already have an account?',
            'Ainda não tem conta?': "Don't have an account yet?",
            
            // Números e Quantidades
            'Total': 'Total',
            'Subtotal': 'Subtotal',
            'Quantidade': 'Quantity',
            'Valor': 'Value',
            'Preço': 'Price',
            'Desconto': 'Discount',
            'Taxa': 'Fee',
            'Imposto': 'Tax'
        },
        'es-ES': {
            // Status e Estados
            'Ativo': 'Activo',
            'Inativo': 'Inactivo',
            'Ativa': 'Activa',
            'Inativa': 'Inactiva',
            'Online': 'En línea',
            'Offline': 'Fuera de línea',
            'Disponível': 'Disponible',
            'Indisponível': 'No disponible',
            'Pendente': 'Pendiente',
            'Aprovado': 'Aprobado',
            'Rejeitado': 'Rechazado',
            'Em andamento': 'En progreso',
            'Concluído': 'Completado',
            'Cancelado': 'Cancelado',
            
            // Tabelas
            'Nenhum registro encontrado': 'No se encontraron registros',
            'Mostrando de': 'Mostrando de',
            'até': 'hasta',
            'de um total de': 'de un total de',
            'registros': 'registros',
            'Primeira': 'Primera',
            'Última': 'Última',
            'Próxima': 'Siguiente',
            'Anterior': 'Anterior',
            
            // Formulários
            'Campo obrigatório': 'Campo obligatorio',
            'Selecione uma opção': 'Seleccione una opción',
            'Digite aqui': 'Escriba aquí',
            'Escolha um arquivo': 'Elija un archivo',
            'Nenhum arquivo selecionado': 'Ningún archivo seleccionado',
            
            // Ações
            'Ver detalhes': 'Ver detalles',
            'Ver mais': 'Ver más',
            'Ver menos': 'Ver menos',
            'Mostrar mais': 'Mostrar más',
            'Mostrar menos': 'Mostrar menos',
            'Expandir': 'Expandir',
            'Recolher': 'Contraer',
            'Ordenar': 'Ordenar',
            'Resetar': 'Restablecer',
            'Aplicar filtros': 'Aplicar filtros',
            'Limpar filtros': 'Limpiar filtros',
            
            // Tempo
            'Hoje': 'Hoy',
            'Ontem': 'Ayer',
            'Esta semana': 'Esta semana',
            'Este mês': 'Este mes',
            'Este ano': 'Este año',
            'Último acesso': 'Último acceso',
            'Nunca': 'Nunca',
            
            // Confirmações
            'Tem certeza?': '¿Está seguro?',
            'Esta ação não pode ser desfeita': 'Esta acción no se puede deshacer',
            'Sim, continuar': 'Sí, continuar',
            'Não, cancelar': 'No, cancelar',
            
            // Login/Autenticação
            'Fazer login': 'Iniciar sesión',
            'Fazer logout': 'Cerrar sesión',
            'Lembrar de mim': 'Recordarme',
            'Esqueci a senha': 'Olvidé mi contraseña',
            'Criar conta': 'Crear cuenta',
            'Já tem uma conta?': '¿Ya tiene una cuenta?',
            'Ainda não tem conta?': '¿Todavía no tiene cuenta?',
            
            // Números e Quantidades
            'Total': 'Total',
            'Subtotal': 'Subtotal',
            'Quantidade': 'Cantidad',
            'Valor': 'Valor',
            'Preço': 'Precio',
            'Desconto': 'Descuento',
            'Taxa': 'Tasa',
            'Imposto': 'Impuesto'
        }
    };
    
    // Mesclar traduções adicionais com as existentes
    Object.keys(traducoesAdicionais).forEach(idioma => {
        if (!window.TRADUCOES_COMPLETAS[idioma]) {
            window.TRADUCOES_COMPLETAS[idioma] = {};
        }
        
        Object.assign(window.TRADUCOES_COMPLETAS[idioma], traducoesAdicionais[idioma]);
    });
    
    console.log('✅ Traduções adicionais carregadas!');
    
    // Reaplicar idioma atual se não for português
    if (window.temaGlobal && window.temaGlobal.obterIdiomaAtual() !== 'pt-BR') {
        window.temaGlobal.aplicarIdioma(window.temaGlobal.obterIdiomaAtual());
    }
};

// Carregar traduções adicionais automaticamente
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        window.adicionarTraducoesEmFalta();
    }, 500);
});

console.log('🔧 Sistema de Auto-Tradução carregado!');