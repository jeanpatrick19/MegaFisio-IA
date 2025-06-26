<?php

class DateTimeHelper {
    
    private static $userTimezone = 'America/Sao_Paulo';
    private static $userDateFormat = 'dd/MM/yyyy';
    
    /**
     * Definir timezone e formato do usuário
     */
    public static function setUserPreferences($timezone = null, $dateFormat = null) {
        if ($timezone) {
            self::$userTimezone = $timezone;
        }
        if ($dateFormat) {
            self::$userDateFormat = $dateFormat;
        }
    }
    
    /**
     * Carregar preferências do usuário do banco de dados
     */
    public static function loadUserPreferences($userId, $db) {
        try {
            $stmt = $db->prepare("
                SELECT timezone, date_format 
                FROM user_profiles_extended 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $prefs = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prefs) {
                self::$userTimezone = $prefs['timezone'] ?: 'America/Sao_Paulo';
                self::$userDateFormat = $prefs['date_format'] ?: 'dd/MM/yyyy';
            }
        } catch (Exception $e) {
            // Usar valores padrão se houver erro
            error_log("Erro ao carregar preferências de data/hora: " . $e->getMessage());
        }
    }
    
    /**
     * Formatar data/hora para o usuário
     */
    public static function formatDateTime($datetime, $includeTime = true) {
        if (!$datetime) return '';
        
        // Converter para objeto DateTime se necessário
        if (is_string($datetime)) {
            $datetime = new DateTime($datetime);
        }
        
        // Aplicar timezone do usuário
        $datetime->setTimezone(new DateTimeZone(self::$userTimezone));
        
        // Determinar formato baseado na preferência do usuário
        $format = self::getPhpDateFormat($includeTime);
        
        return $datetime->format($format);
    }
    
    /**
     * Formatar apenas data (sem hora)
     */
    public static function formatDate($date) {
        return self::formatDateTime($date, false);
    }
    
    /**
     * Formatar apenas hora
     */
    public static function formatTime($datetime) {
        if (!$datetime) return '';
        
        if (is_string($datetime)) {
            $datetime = new DateTime($datetime);
        }
        
        $datetime->setTimezone(new DateTimeZone(self::$userTimezone));
        
        return $datetime->format('H:i:s');
    }
    
    /**
     * Obter data/hora atual formatada
     */
    public static function now($includeTime = true) {
        $now = new DateTime('now', new DateTimeZone(self::$userTimezone));
        $format = self::getPhpDateFormat($includeTime);
        return $now->format($format);
    }
    
    /**
     * Converter formato de data do usuário para formato PHP
     */
    private static function getPhpDateFormat($includeTime = true) {
        $format = '';
        
        switch (self::$userDateFormat) {
            case 'MM/dd/yyyy':
                $format = 'm/d/Y';
                break;
            case 'yyyy-MM-dd':
                $format = 'Y-m-d';
                break;
            case 'dd-MM-yyyy':
                $format = 'd-m-Y';
                break;
            case 'dd/MM/yyyy':
            default:
                $format = 'd/m/Y';
        }
        
        if ($includeTime) {
            $format .= ' H:i:s';
        }
        
        return $format;
    }
    
    /**
     * Obter timezone atual do usuário
     */
    public static function getUserTimezone() {
        return self::$userTimezone;
    }
    
    /**
     * Obter formato de data atual do usuário
     */
    public static function getUserDateFormat() {
        return self::$userDateFormat;
    }
    
    /**
     * Converter data/hora para UTC (para salvar no banco)
     */
    public static function toUTC($datetime) {
        if (!$datetime) return null;
        
        if (is_string($datetime)) {
            $datetime = new DateTime($datetime, new DateTimeZone(self::$userTimezone));
        }
        
        $datetime->setTimezone(new DateTimeZone('UTC'));
        return $datetime->format('Y-m-d H:i:s');
    }
    
    /**
     * Converter data/hora de UTC para timezone do usuário
     */
    public static function fromUTC($utcDatetime) {
        if (!$utcDatetime) return null;
        
        $datetime = new DateTime($utcDatetime, new DateTimeZone('UTC'));
        $datetime->setTimezone(new DateTimeZone(self::$userTimezone));
        
        return $datetime;
    }
    
    /**
     * Gerar atributos HTML para formatação JavaScript
     */
    public static function htmlAttributes($datetime, $includeTime = true) {
        if (!$datetime) return '';
        
        $isoString = '';
        if (is_string($datetime)) {
            $dt = new DateTime($datetime);
            $isoString = $dt->format('c'); // ISO 8601
        } else {
            $isoString = $datetime->format('c');
        }
        
        $attrs = 'data-format-date="' . htmlspecialchars($isoString) . '"';
        if ($includeTime) {
            $attrs .= ' data-include-time="true"';
        }
        
        return $attrs;
    }
}