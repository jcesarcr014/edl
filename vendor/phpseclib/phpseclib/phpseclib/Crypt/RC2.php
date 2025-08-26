<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * Pure-PHP implementation of RC2.
 *
 * Uses mcrypt, if available, and an internal implementation, otherwise.
 *
 * PHP version 5
 *
 * Useful resources are as follows:
 *
 *  - {@link http://tools.ietf.org/html/rfc2268}
 *
 * Here's a short example of how to use this library:
 * <code>
 * <?php
 *    include 'vendor/autoload.php';
 *
 *    $rc2 = new \phpseclib\Crypt\RC2();
 *
 *    $rc2->setKey('abcdefgh');
 *
 *    $plaintext = str_repeat('a', 1024);
 *
 *    echo $rc2->decrypt($rc2->encrypt($plaintext));
 * ?>
 * </code>
 *
 * @category Crypt
 * @package  RC2
 * @author   Patrick Monnerat <pm@datasphere.ch>
 * @license  http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link     http://phpseclib.sourceforge.net
 */
namespace phpseclib\Crypt;

use phpseclib\Crypt\Common\BlockCipher;
/**
 * Pure-PHP implementation of RC2.
 *
 * @package RC2
 * @access  public
 */
class RC2 extends BlockCipher
{
    /**
     * Block Length of the cipher
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::block_size
     * @var int
     * @access private
     */
    var $block_size = 8;
    /**
     * The Key
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::key
     * @see self::setKey()
     * @var string
     * @access private
     */
    var $key;
    /**
     * The Original (unpadded) Key
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::key
     * @see self::setKey()
     * @see self::encrypt()
     * @see self::decrypt()
     * @var string
     * @access private
     */
    var $orig_key;
    /**
     * Don't truncate / null pad key
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_clearBuffers()
     * @var bool
     * @access private
     */
    var $skip_key_adjustment = true;
    /**
     * Key Length (in bytes)
     *
     * @see \phpseclib\Crypt\RC2::setKeyLength()
     * @var int
     * @access private
     */
    var $key_length = 16;
    // = 128 bits
    /**
     * The mcrypt specific name of the cipher
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::cipher_name_mcrypt
     * @var string
     * @access private
     */
    var $cipher_name_mcrypt = 'rc2';
    /**
     * Optimizing value while CFB-encrypting
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::cfb_init_len
     * @var int
     * @access private
     */
    var $cfb_init_len = 500;
    /**
     * The key length in bits.
     *
     * @see self::setKeyLength()
     * @see self::setKey()
     * @var int
     * @access private
     * @internal Should be in range [1..1024].
     * @internal Changing this value after setting the key has no effect.
     */
    var $default_key_length = 1024;
    /**
     * The key length in bits.
     *
     * @see self::isValidEnine()
     * @see self::setKey()
     * @var int
     * @access private
     * @internal Should be in range [1..1024].
     */
    var $current_key_length;
    /**
     * The Key Schedule
     *
     * @see self::_setupKey()
     * @var array
     * @access private
     */
    var $keys;
    /**
     * Key expansion randomization table.
     * Twice the same 256-value sequence to save a modulus in key expansion.
     *
     * @see self::setKey()
     * @var array
     * @access private
     */
    var $pitable = array(217, 120, 249, 196, 25, 221, 181, 237, 40, 233, 253, 121, 74, 160, 216, 157, 198, 126, 55, 131, 43, 118, 83, 142, 98, 76, 100, 136, 68, 139, 251, 162, 23, 154, 89, 245, 135, 179, 79, 19, 97, 69, 109, 141, 9, 129, 125, 50, 189, 143, 64, 235, 134, 183, 123, 11, 240, 149, 33, 34, 92, 107, 78, 130, 84, 214, 101, 147, 206, 96, 178, 28, 115, 86, 192, 20, 167, 140, 241, 220, 18, 117, 202, 31, 59, 190, 228, 209, 66, 61, 212, 48, 163, 60, 182, 38, 111, 191, 14, 218, 70, 105, 7, 87, 39, 242, 29, 155, 188, 148, 67, 3, 248, 17, 199, 246, 144, 239, 62, 231, 6, 195, 213, 47, 200, 102, 30, 215, 8, 232, 234, 222, 128, 82, 238, 247, 132, 170, 114, 172, 53, 77, 106, 42, 150, 26, 210, 113, 90, 21, 73, 116, 75, 159, 208, 94, 4, 24, 164, 236, 194, 224, 65, 110, 15, 81, 203, 204, 36, 145, 175, 80, 161, 244, 112, 57, 153, 124, 58, 133, 35, 184, 180, 122, 252, 2, 54, 91, 37, 85, 151, 49, 45, 93, 250, 152, 227, 138, 146, 174, 5, 223, 41, 16, 103, 108, 186, 201, 211, 0, 230, 207, 225, 158, 168, 44, 99, 22, 1, 63, 88, 226, 137, 169, 13, 56, 52, 27, 171, 51, 255, 176, 187, 72, 12, 95, 185, 177, 205, 46, 197, 243, 219, 71, 229, 165, 156, 119, 10, 166, 32, 104, 254, 127, 193, 173, 217, 120, 249, 196, 25, 221, 181, 237, 40, 233, 253, 121, 74, 160, 216, 157, 198, 126, 55, 131, 43, 118, 83, 142, 98, 76, 100, 136, 68, 139, 251, 162, 23, 154, 89, 245, 135, 179, 79, 19, 97, 69, 109, 141, 9, 129, 125, 50, 189, 143, 64, 235, 134, 183, 123, 11, 240, 149, 33, 34, 92, 107, 78, 130, 84, 214, 101, 147, 206, 96, 178, 28, 115, 86, 192, 20, 167, 140, 241, 220, 18, 117, 202, 31, 59, 190, 228, 209, 66, 61, 212, 48, 163, 60, 182, 38, 111, 191, 14, 218, 70, 105, 7, 87, 39, 242, 29, 155, 188, 148, 67, 3, 248, 17, 199, 246, 144, 239, 62, 231, 6, 195, 213, 47, 200, 102, 30, 215, 8, 232, 234, 222, 128, 82, 238, 247, 132, 170, 114, 172, 53, 77, 106, 42, 150, 26, 210, 113, 90, 21, 73, 116, 75, 159, 208, 94, 4, 24, 164, 236, 194, 224, 65, 110, 15, 81, 203, 204, 36, 145, 175, 80, 161, 244, 112, 57, 153, 124, 58, 133, 35, 184, 180, 122, 252, 2, 54, 91, 37, 85, 151, 49, 45, 93, 250, 152, 227, 138, 146, 174, 5, 223, 41, 16, 103, 108, 186, 201, 211, 0, 230, 207, 225, 158, 168, 44, 99, 22, 1, 63, 88, 226, 137, 169, 13, 56, 52, 27, 171, 51, 255, 176, 187, 72, 12, 95, 185, 177, 205, 46, 197, 243, 219, 71, 229, 165, 156, 119, 10, 166, 32, 104, 254, 127, 193, 173);
    /**
     * Inverse key expansion randomization table.
     *
     * @see self::setKey()
     * @var array
     * @access private
     */
    var $invpitable = array(209, 218, 185, 111, 156, 200, 120, 102, 128, 44, 248, 55, 234, 224, 98, 164, 203, 113, 80, 39, 75, 149, 217, 32, 157, 4, 145, 227, 71, 106, 126, 83, 250, 58, 59, 180, 168, 188, 95, 104, 8, 202, 143, 20, 215, 192, 239, 123, 91, 191, 47, 229, 226, 140, 186, 18, 225, 175, 178, 84, 93, 89, 118, 219, 50, 162, 88, 110, 28, 41, 100, 243, 233, 150, 12, 152, 25, 141, 62, 38, 171, 165, 133, 22, 64, 189, 73, 103, 220, 34, 148, 187, 60, 193, 155, 235, 69, 40, 24, 216, 26, 66, 125, 204, 251, 101, 142, 61, 205, 42, 163, 96, 174, 147, 138, 72, 151, 81, 21, 247, 1, 11, 183, 54, 177, 46, 17, 253, 132, 45, 63, 19, 136, 179, 52, 36, 27, 222, 197, 29, 77, 43, 23, 49, 116, 169, 198, 67, 109, 57, 144, 190, 195, 176, 33, 107, 246, 15, 213, 153, 13, 172, 31, 92, 158, 245, 249, 76, 214, 223, 137, 228, 139, 255, 199, 170, 231, 237, 70, 37, 182, 6, 94, 53, 181, 236, 206, 232, 108, 48, 85, 97, 74, 254, 160, 121, 3, 240, 16, 114, 124, 207, 82, 166, 167, 238, 68, 211, 154, 87, 146, 208, 90, 122, 65, 127, 14, 0, 99, 242, 79, 5, 131, 201, 161, 212, 221, 196, 86, 244, 210, 119, 129, 9, 130, 51, 159, 7, 134, 117, 56, 78, 105, 241, 173, 35, 115, 135, 112, 2, 194, 30, 184, 10, 252, 230);
    /**
     * Default Constructor.
     *
     * @param int $mode
     * @access public
     * @throws \InvalidArgumentException if an invalid / unsupported mode is provided
     */
    function __construct($mode)
    {
        if ($mode == self::MODE_STREAM) {
            throw new \InvalidArgumentException('Block ciphers cannot be ran in stream mode');
        }
        parent::__construct($mode);
    }
    /**
     * Test for engine validity
     *
     * This is mainly just a wrapper to set things up for \phpseclib\Crypt\Common\SymmetricKey::isValidEngine()
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::__construct()
     * @param int $engine
     * @access public
     * @return bool
     */
    function isValidEngine($engine)
    {
        switch ($engine) {
            case self::ENGINE_OPENSSL:
                if ($this->current_key_length != 128 || strlen($this->orig_key) < 16) {
                    return false;
                }
                $this->cipher_name_openssl_ecb = 'rc2-ecb';
                $this->cipher_name_openssl = 'rc2-' . $this->_openssl_translate_mode();
        }
        return parent::isValidEngine($engine);
    }
    /**
     * Sets the key length.
     *
     * Valid key lengths are 8 to 1024.
     * Calling this function after setting the key has no effect until the next
     *  \phpseclib\Crypt\RC2::setKey() call.
     *
     * @access public
     * @param int $length in bits
     * @throws \LengthException if the key length isn't supported
     */
    function setKeyLength($length)
    {
        if ($length < 8 || $length > 1024) {
            throw new \LengthException('Key size of ' . $length . ' bits is not supported by this algorithm. Only keys between 1 and 1024 bits, inclusive, are supported');
        }
        $this->default_key_length = $this->current_key_length = $length;
        $this->explicit_key_length = $length >> 3;
    }
    /**
     * Returns the current key length
     *
     * @access public
     * @return int
     */
    function getKeyLength()
    {
        return $this->current_key_length;
    }
    /**
     * Sets the key.
     *
     * Keys can be of any length. RC2, itself, uses 8 to 1024 bit keys (eg.
     * strlen($key) <= 128), however, we only use the first 128 bytes if $key
     * has more then 128 bytes in it, and set $key to a single null byte if
     * it is empty.
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::setKey()
     * @access public
     * @param string $key
     * @param int $t1 optional Effective key length in bits.
     * @throws \LengthException if the key length isn't supported
     */
    function setKey($key, $t1 = false)
    {
        $this->orig_key = $key;
        if ($t1 === false) {
            $t1 = $this->default_key_length;
        }
        if ($t1 < 1 || $t1 > 1024) {
            throw new \LengthException('Key size of ' . $length . ' bits is not supported by this algorithm. Only keys between 1 and 1024 bits, inclusive, are supported');
        }
        $this->current_key_length = $t1;
        if (strlen($key) < 1 || strlen($key) > 128) {
            throw new \LengthException('Key of size ' . strlen($key) . ' not supported by this algorithm. Only keys of sizes between 8 and 1024 bits, inclusive, are supported');
        }
        $t = strlen($key);
        // The mcrypt RC2 implementation only supports effective key length
        // of 1024 bits. It is however possible to handle effective key
        // lengths in range 1..1024 by expanding the key and applying
        // inverse pitable mapping to the first byte before submitting it
        // to mcrypt.
        // Key expansion.
        $l = array_values(unpack('C*', $key));
        $t8 = $t1 + 7 >> 3;
        $tm = 255 >> 8 * $t8 - $t1;
        // Expand key.
        $pitable = $this->pitable;
        for ($i = $t; $i < 128; $i++) {
            $l[$i] = $pitable[$l[$i - 1] + $l[$i - $t]];
        }
        $i = 128 - $t8;
        $l[$i] = $pitable[$l[$i] & $tm];
        while ($i--) {
            $l[$i] = $pitable[$l[$i + 1] ^ $l[$i + $t8]];
        }
        // Prepare the key for mcrypt.
        $l[0] = $this->invpitable[$l[0]];
        array_unshift($l, 'C*');
        $this->key = call_user_func_array('pack', $l);
        $this->key_length = strlen($this->key);
        $this->changed = true;
        $this->_setEngine();
    }
    /**
     * Encrypts a message.
     *
     * Mostly a wrapper for \phpseclib\Crypt\Common\SymmetricKey::encrypt, with some additional OpenSSL handling code
     *
     * @see self::decrypt()
     * @access public
     * @param string $plaintext
     * @return string $ciphertext
     */
    function encrypt($plaintext)
    {
        if ($this->engine == self::ENGINE_OPENSSL) {
            $temp = $this->key;
            $this->key = $this->orig_key;
            $result = parent::encrypt($plaintext);
            $this->key = $temp;
            return $result;
        }
        return parent::encrypt($plaintext);
    }
    /**
     * Decrypts a message.
     *
     * Mostly a wrapper for \phpseclib\Crypt\Common\SymmetricKey::decrypt, with some additional OpenSSL handling code
     *
     * @see self::encrypt()
     * @access public
     * @param string $ciphertext
     * @return string $plaintext
     */
    function decrypt($ciphertext)
    {
        if ($this->engine == self::ENGINE_OPENSSL) {
            $temp = $this->key;
            $this->key = $this->orig_key;
            $result = parent::decrypt($ciphertext);
            $this->key = $temp;
            return $result;
        }
        return parent::decrypt($ciphertext);
    }
    /**
     * Encrypts a block
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_encryptBlock()
     * @see \phpseclib\Crypt\Common\SymmetricKey::encrypt()
     * @access private
     * @param string $in
     * @return string
     */
    function _encryptBlock($in)
    {
        list($r0, $r1, $r2, $r3) = array_values(unpack('v*', $in));
        $keys = $this->keys;
        $limit = 20;
        $actions = array($limit => 44, 44 => 64);
        $j = 0;
        for (;;) {
            // Mixing round.
            $r0 = ($r0 + $keys[$j++] + (($r1 ^ $r2) & $r3 ^ $r1) & 65535) << 1;
            $r0 |= $r0 >> 16;
            $r1 = ($r1 + $keys[$j++] + (($r2 ^ $r3) & $r0 ^ $r2) & 65535) << 2;
            $r1 |= $r1 >> 16;
            $r2 = ($r2 + $keys[$j++] + (($r3 ^ $r0) & $r1 ^ $r3) & 65535) << 3;
            $r2 |= $r2 >> 16;
            $r3 = ($r3 + $keys[$j++] + (($r0 ^ $r1) & $r2 ^ $r0) & 65535) << 5;
            $r3 |= $r3 >> 16;
            if ($j === $limit) {
                if ($limit === 64) {
                    break;
                }
                // Mashing round.
                $r0 += $keys[$r3 & 63];
                $r1 += $keys[$r0 & 63];
                $r2 += $keys[$r1 & 63];
                $r3 += $keys[$r2 & 63];
                $limit = $actions[$limit];
            }
        }
        return pack('vvvv', $r0, $r1, $r2, $r3);
    }
    /**
     * Decrypts a block
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_decryptBlock()
     * @see \phpseclib\Crypt\Common\SymmetricKey::decrypt()
     * @access private
     * @param string $in
     * @return string
     */
    function _decryptBlock($in)
    {
        list($r0, $r1, $r2, $r3) = array_values(unpack('v*', $in));
        $keys = $this->keys;
        $limit = 44;
        $actions = array($limit => 20, 20 => 0);
        $j = 64;
        for (;;) {
            // R-mixing round.
            $r3 = ($r3 | $r3 << 16) >> 5;
            $r3 = $r3 - $keys[--$j] - (($r0 ^ $r1) & $r2 ^ $r0) & 65535;
            $r2 = ($r2 | $r2 << 16) >> 3;
            $r2 = $r2 - $keys[--$j] - (($r3 ^ $r0) & $r1 ^ $r3) & 65535;
            $r1 = ($r1 | $r1 << 16) >> 2;
            $r1 = $r1 - $keys[--$j] - (($r2 ^ $r3) & $r0 ^ $r2) & 65535;
            $r0 = ($r0 | $r0 << 16) >> 1;
            $r0 = $r0 - $keys[--$j] - (($r1 ^ $r2) & $r3 ^ $r1) & 65535;
            if ($j === $limit) {
                if ($limit === 0) {
                    break;
                }
                // R-mashing round.
                $r3 = $r3 - $keys[$r2 & 63] & 65535;
                $r2 = $r2 - $keys[$r1 & 63] & 65535;
                $r1 = $r1 - $keys[$r0 & 63] & 65535;
                $r0 = $r0 - $keys[$r3 & 63] & 65535;
                $limit = $actions[$limit];
            }
        }
        return pack('vvvv', $r0, $r1, $r2, $r3);
    }
    /**
     * Setup the \phpseclib\Crypt\Common\SymmetricKey::ENGINE_MCRYPT $engine
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_setupMcrypt()
     * @access private
     */
    function _setupMcrypt()
    {
        if (!isset($this->key)) {
            $this->setKey('');
        }
        parent::_setupMcrypt();
    }
    /**
     * Creates the key schedule
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_setupKey()
     * @access private
     */
    function _setupKey()
    {
        if (!isset($this->key)) {
            $this->setKey('');
        }
        // Key has already been expanded in \phpseclib\Crypt\RC2::setKey():
        // Only the first value must be altered.
        $l = unpack('Ca/Cb/v*', $this->key);
        array_unshift($l, $this->pitable[$l['a']] | $l['b'] << 8);
        unset($l['a']);
        unset($l['b']);
        $this->keys = $l;
    }
    /**
     * Setup the performance-optimized function for de/encrypt()
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_setupInlineCrypt()
     * @access private
     */
    function _setupInlineCrypt()
    {
        $lambda_functions =& self::_getLambdaFunctions();
        // The first 10 generated $lambda_functions will use the $keys hardcoded as integers
        // for the mixing rounds, for better inline crypt performance [~20% faster].
        // But for memory reason we have to limit those ultra-optimized $lambda_functions to an amount of 10.
        // (Currently, for Crypt_RC2, one generated $lambda_function cost on php5.5@32bit ~60kb unfreeable mem and ~100kb on php5.5@64bit)
        $gen_hi_opt_code = (bool) (count($lambda_functions) < 10);
        // Generation of a unique hash for our generated code
        $code_hash = "Crypt_RC2, {$this->mode}";
        if ($gen_hi_opt_code) {
            $code_hash = str_pad($code_hash, 32) . $this->_hashInlineCryptFunction($this->key);
        }
        // Is there a re-usable $lambda_functions in there?
        // If not, we have to create it.
        if (!isset($lambda_functions[$code_hash])) {
            // Init code for both, encrypt and decrypt.
            $init_crypt = '$keys = $self->keys;';
            switch (true) {
                case $gen_hi_opt_code:
                    $keys = $this->keys;
                default:
                    $keys = array();
                    foreach ($this->keys as $k => $v) {
                        $keys[$k] = '$keys[' . $k . ']';
                    }
            }
            // $in is the current 8 bytes block which has to be en/decrypt
            $encrypt_block = $decrypt_block = '
                $in = unpack("v4", $in);
                $r0 = $in[1];
                $r1 = $in[2];
                $r2 = $in[3];
                $r3 = $in[4];
            ';
            // Create code for encryption.
            $limit = 20;
            $actions = array($limit => 44, 44 => 64);
            $j = 0;
            for (;;) {
                // Mixing round.
                $encrypt_block .= '
                    $r0 = (($r0 + ' . $keys[$j++] . ' +
                           ((($r1 ^ $r2) & $r3) ^ $r1)) & 0xFFFF) << 1;
                    $r0 |= $r0 >> 16;
                    $r1 = (($r1 + ' . $keys[$j++] . ' +
                           ((($r2 ^ $r3) & $r0) ^ $r2)) & 0xFFFF) << 2;
                    $r1 |= $r1 >> 16;
                    $r2 = (($r2 + ' . $keys[$j++] . ' +
                           ((($r3 ^ $r0) & $r1) ^ $r3)) & 0xFFFF) << 3;
                    $r2 |= $r2 >> 16;
                    $r3 = (($r3 + ' . $keys[$j++] . ' +
                           ((($r0 ^ $r1) & $r2) ^ $r0)) & 0xFFFF) << 5;
                    $r3 |= $r3 >> 16;';
                if ($j === $limit) {
                    if ($limit === 64) {
                        break;
                    }
                    // Mashing round.
                    $encrypt_block .= '
                        $r0 += $keys[$r3 & 0x3F];
                        $r1 += $keys[$r0 & 0x3F];
                        $r2 += $keys[$r1 & 0x3F];
                        $r3 += $keys[$r2 & 0x3F];';
                    $limit = $actions[$limit];
                }
            }
            $encrypt_block .= '$in = pack("v4", $r0, $r1, $r2, $r3);';
            // Create code for decryption.
            $limit = 44;
            $actions = array($limit => 20, 20 => 0);
            $j = 64;
            for (;;) {
                // R-mixing round.
                $decrypt_block .= '
                    $r3 = ($r3 | ($r3 << 16)) >> 5;
                    $r3 = ($r3 - ' . $keys[--$j] . ' -
                           ((($r0 ^ $r1) & $r2) ^ $r0)) & 0xFFFF;
                    $r2 = ($r2 | ($r2 << 16)) >> 3;
                    $r2 = ($r2 - ' . $keys[--$j] . ' -
                           ((($r3 ^ $r0) & $r1) ^ $r3)) & 0xFFFF;
                    $r1 = ($r1 | ($r1 << 16)) >> 2;
                    $r1 = ($r1 - ' . $keys[--$j] . ' -
                           ((($r2 ^ $r3) & $r0) ^ $r2)) & 0xFFFF;
                    $r0 = ($r0 | ($r0 << 16)) >> 1;
                    $r0 = ($r0 - ' . $keys[--$j] . ' -
                           ((($r1 ^ $r2) & $r3) ^ $r1)) & 0xFFFF;';
                if ($j === $limit) {
                    if ($limit === 0) {
                        break;
                    }
                    // R-mashing round.
                    $decrypt_block .= '
                        $r3 = ($r3 - $keys[$r2 & 0x3F]) & 0xFFFF;
                        $r2 = ($r2 - $keys[$r1 & 0x3F]) & 0xFFFF;
                        $r1 = ($r1 - $keys[$r0 & 0x3F]) & 0xFFFF;
                        $r0 = ($r0 - $keys[$r3 & 0x3F]) & 0xFFFF;';
                    $limit = $actions[$limit];
                }
            }
            $decrypt_block .= '$in = pack("v4", $r0, $r1, $r2, $r3);';
            // Creates the inline-crypt function
            $lambda_functions[$code_hash] = $this->_createInlineCryptFunction(array('init_crypt' => $init_crypt, 'encrypt_block' => $encrypt_block, 'decrypt_block' => $decrypt_block));
        }
        // Set the inline-crypt function as callback in: $this->inline_crypt
        $this->inline_crypt = $lambda_functions[$code_hash];
    }
}