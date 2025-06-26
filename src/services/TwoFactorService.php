<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

/**
 * Serviço de Autenticação de Dois Fatores (2FA)
 * Implementa TOTP (Time-based One-Time Password) compatível com Google Authenticator
 */
class TwoFactorService {
    
    /**
     * Gera um segredo aleatório para 2FA
     */
    public static function generateSecret($length = 16) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        
        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        
        return $secret;
    }
    
    /**
     * Gera QR Code URL para configuração no app
     */
    public static function getQRCodeURL($secret, $userEmail, $issuer = 'MegaFisio IA') {
        $label = urlencode($issuer . ':' . $userEmail);
        $qrCodeURL = 'otpauth://totp/' . $label . '?secret=' . $secret . '&issuer=' . urlencode($issuer);
        
        // Usar QR Server para gerar a imagem
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrCodeURL);
    }
    
    /**
     * Verifica se um código TOTP é válido
     */
    public static function verifyCode($secret, $code, $tolerance = 1) {
        $timeSlice = floor(time() / 30);
        
        // Verifica o código atual e os códigos adjacentes (tolerância)
        for ($i = -$tolerance; $i <= $tolerance; $i++) {
            $calculatedCode = self::getTOTP($secret, $timeSlice + $i);
            if ($calculatedCode === $code) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Gera um código TOTP para um determinado time slice
     */
    private static function getTOTP($secret, $timeSlice) {
        $secretkey = self::base32ToStr($secret);
        
        // Converte time slice para binário
        $time = str_pad(pack('N', $timeSlice), 8, "\0", STR_PAD_LEFT);
        
        // Gera HMAC-SHA1
        $hash = hash_hmac('sha1', $time, $secretkey, true);
        
        // Extrai 4 bytes do hash
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        ) % 1000000;
        
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Converte Base32 para string
     */
    private static function base32ToStr($base32) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $v = 0;
        $vbits = 0;
        
        for ($i = 0; $i < strlen($base32); $i++) {
            $char = $base32[$i];
            if ($char === '=') {
                break;
            }
            
            $val = strpos($alphabet, $char);
            if ($val === false) {
                continue;
            }
            
            $v <<= 5;
            $v += $val;
            $vbits += 5;
            
            if ($vbits >= 8) {
                $output .= chr(($v >> ($vbits - 8)) & 255);
                $vbits -= 8;
            }
        }
        
        return $output;
    }
    
    /**
     * Gera códigos de backup
     */
    public static function generateBackupCodes($count = 8) {
        $codes = [];
        
        for ($i = 0; $i < $count; $i++) {
            // Gera código de 8 dígitos
            $code = '';
            for ($j = 0; $j < 8; $j++) {
                $code .= random_int(0, 9);
            }
            
            // Formata como XXXX-XXXX
            $formattedCode = substr($code, 0, 4) . '-' . substr($code, 4, 4);
            $codes[] = $formattedCode;
        }
        
        return $codes;
    }
    
    /**
     * Verifica se um código de backup é válido
     */
    public static function verifyBackupCode($code, $backupCodes) {
        // Remove formatação
        $cleanCode = str_replace('-', '', $code);
        
        foreach ($backupCodes as $backupCode) {
            $cleanBackupCode = str_replace('-', '', $backupCode);
            if ($cleanCode === $cleanBackupCode) {
                return $backupCode; // Retorna o código original para remoção
            }
        }
        
        return false;
    }
    
    /**
     * Gera código atual para testes
     */
    public static function getCurrentCode($secret) {
        return self::getTOTP($secret, floor(time() / 30));
    }
    
    /**
     * Valida formato do segredo
     */
    public static function isValidSecret($secret) {
        return preg_match('/^[A-Z2-7]{16,}$/', $secret);
    }
    
    /**
     * Remove um código de backup usado
     */
    public static function removeUsedBackupCode($backupCodes, $usedCode) {
        return array_values(array_filter($backupCodes, function($code) use ($usedCode) {
            return $code !== $usedCode;
        }));
    }
}