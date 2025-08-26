<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * Pure-PHP implementation of Rijndael.
 *
 * Uses mcrypt, if available/possible, and an internal implementation, otherwise.
 *
 * PHP version 5
 *
 * If {@link self::setBlockLength() setBlockLength()} isn't called, it'll be assumed to be 128 bits.  If
 * {@link self::setKeyLength() setKeyLength()} isn't called, it'll be calculated from
 * {@link self::setKey() setKey()}.  ie. if the key is 128-bits, the key length will be 128-bits.  If it's
 * 136-bits it'll be null-padded to 192-bits and 192 bits will be the key length until
 * {@link self::setKey() setKey()} is called, again, at which point, it'll be recalculated.
 *
 * Not all Rijndael implementations may support 160-bits or 224-bits as the block length / key length.  mcrypt, for example,
 * does not.  AES, itself, only supports block lengths of 128 and key lengths of 128, 192, and 256.
 * {@link http://csrc.nist.gov/archive/aes/rijndael/Rijndael-ammended.pdf#page=10 Rijndael-ammended.pdf#page=10} defines the
 * algorithm for block lengths of 192 and 256 but not for block lengths / key lengths of 160 and 224.  Indeed, 160 and 224
 * are first defined as valid key / block lengths in
 * {@link http://csrc.nist.gov/archive/aes/rijndael/Rijndael-ammended.pdf#page=44 Rijndael-ammended.pdf#page=44}:
 * Extensions: Other block and Cipher Key lengths.
 * Note: Use of 160/224-bit Keys must be explicitly set by setKeyLength(160) respectively setKeyLength(224).
 *
 * {@internal The variable names are the same as those in
 * {@link http://www.csrc.nist.gov/publications/fips/fips197/fips-197.pdf#page=10 fips-197.pdf#page=10}.}}
 *
 * Here's a short example of how to use this library:
 * <code>
 * <?php
 *    include 'vendor/autoload.php';
 *
 *    $rijndael = new \phpseclib\Crypt\Rijndael();
 *
 *    $rijndael->setKey('abcdefghijklmnop');
 *
 *    $size = 10 * 1024;
 *    $plaintext = '';
 *    for ($i = 0; $i < $size; $i++) {
 *        $plaintext.= 'a';
 *    }
 *
 *    echo $rijndael->decrypt($rijndael->encrypt($plaintext));
 * ?>
 * </code>
 *
 * @category  Crypt
 * @package   Rijndael
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2008 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace phpseclib\Crypt;

use phpseclib\Crypt\Common\BlockCipher;
/**
 * Pure-PHP implementation of Rijndael.
 *
 * @package Rijndael
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class Rijndael extends BlockCipher
{
    /**
     * The mcrypt specific name of the cipher
     *
     * Mcrypt is useable for 128/192/256-bit $block_size/$key_length. For 160/224 not.
     * \phpseclib\Crypt\Rijndael determines automatically whether mcrypt is useable
     * or not for the current $block_size/$key_length.
     * In case of, $cipher_name_mcrypt will be set dynamically at run time accordingly.
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::cipher_name_mcrypt
     * @see \phpseclib\Crypt\Common\SymmetricKey::engine
     * @see self::isValidEngine()
     * @var string
     * @access private
     */
    var $cipher_name_mcrypt = 'rijndael-128';
    /**
     * The default salt used by setPassword()
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::password_default_salt
     * @see \phpseclib\Crypt\Common\SymmetricKey::setPassword()
     * @var string
     * @access private
     */
    var $password_default_salt = 'phpseclib';
    /**
     * The Key Schedule
     *
     * @see self::_setup()
     * @var array
     * @access private
     */
    var $w;
    /**
     * The Inverse Key Schedule
     *
     * @see self::_setup()
     * @var array
     * @access private
     */
    var $dw;
    /**
     * The Block Length divided by 32
     *
     * @see self::setBlockLength()
     * @var int
     * @access private
     * @internal The max value is 256 / 32 = 8, the min value is 128 / 32 = 4.  Exists in conjunction with $block_size
     *    because the encryption / decryption / key schedule creation requires this number and not $block_size.  We could
     *    derive this from $block_size or vice versa, but that'd mean we'd have to do multiple shift operations, so in lieu
     *    of that, we'll just precompute it once.
     */
    var $Nb = 4;
    /**
     * The Key Length (in bytes)
     *
     * @see self::setKeyLength()
     * @var int
     * @access private
     * @internal The max value is 256 / 8 = 32, the min value is 128 / 8 = 16.  Exists in conjunction with $Nk
     *    because the encryption / decryption / key schedule creation requires this number and not $key_length.  We could
     *    derive this from $key_length or vice versa, but that'd mean we'd have to do multiple shift operations, so in lieu
     *    of that, we'll just precompute it once.
     */
    var $key_length = 16;
    /**
     * The Key Length divided by 32
     *
     * @see self::setKeyLength()
     * @var int
     * @access private
     * @internal The max value is 256 / 32 = 8, the min value is 128 / 32 = 4
     */
    var $Nk = 4;
    /**
     * The Number of Rounds
     *
     * @var int
     * @access private
     * @internal The max value is 14, the min value is 10.
     */
    var $Nr;
    /**
     * Shift offsets
     *
     * @var array
     * @access private
     */
    var $c;
    /**
     * Holds the last used key- and block_size information
     *
     * @var array
     * @access private
     */
    var $kl;
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
     * Sets the key length.
     *
     * Valid key lengths are 128, 160, 192, 224, and 256.
     *
     * Note: phpseclib extends Rijndael (and AES) for using 160- and 224-bit keys but they are officially not defined
     *       and the most (if not all) implementations are not able using 160/224-bit keys but round/pad them up to
     *       192/256 bits as, for example, mcrypt will do.
     *
     *       That said, if you want be compatible with other Rijndael and AES implementations,
     *       you should not setKeyLength(160) or setKeyLength(224).
     *
     * Additional: In case of 160- and 224-bit keys, phpseclib will/can, for that reason, not use
     *             the mcrypt php extension, even if available.
     *             This results then in slower encryption.
     *
     * @access public
     * @throws \LengthException if the key length is invalid
     * @param int $length
     */
    function setKeyLength($length)
    {
        switch ($length) {
            case 128:
            case 160:
            case 192:
            case 224:
            case 256:
                $this->key_length = $length >> 3;
                break;
            default:
                throw new \LengthException('Key size of ' . $length . ' bits is not supported by this algorithm. Only keys of sizes 128, 160, 192, 224 or 256 bits are supported');
        }
        parent::setKeyLength($length);
    }
    /**
     * Sets the key.
     *
     * Rijndael supports five different key lengths
     *
     * @see setKeyLength()
     * @access public
     * @param string $key
     * @throws \LengthException if the key length isn't supported
     */
    function setKey($key)
    {
        switch (strlen($key)) {
            case 16:
            case 20:
            case 24:
            case 28:
            case 32:
                break;
            default:
                throw new \LengthException('Key of size ' . strlen($key) . ' not supported by this algorithm. Only keys of sizes 16, 20, 24, 28 or 32 are supported');
        }
        parent::setKey($key);
    }
    /**
     * Sets the block length
     *
     * Valid block lengths are 128, 160, 192, 224, and 256.
     *
     * @access public
     * @param int $length
     */
    function setBlockLength($length)
    {
        switch ($length) {
            case 128:
            case 160:
            case 192:
            case 224:
            case 256:
                break;
            default:
                throw new \LengthException('Key size of ' . $length . ' bits is not supported by this algorithm. Only keys of sizes 128, 160, 192, 224 or 256 bits are supported');
        }
        $this->Nb = $length >> 5;
        $this->block_size = $length >> 3;
        $this->changed = true;
        $this->_setEngine();
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
                if ($this->block_size != 16) {
                    return false;
                }
                $this->cipher_name_openssl_ecb = 'aes-' . ($this->key_length << 3) . '-ecb';
                $this->cipher_name_openssl = 'aes-' . ($this->key_length << 3) . '-' . $this->_openssl_translate_mode();
                break;
            case self::ENGINE_MCRYPT:
                $this->cipher_name_mcrypt = 'rijndael-' . ($this->block_size << 3);
                if ($this->key_length % 8) {
                    // is it a 160/224-bit key?
                    // mcrypt is not usable for them, only for 128/192/256-bit keys
                    return false;
                }
        }
        return parent::isValidEngine($engine);
    }
    /**
     * Encrypts a block
     *
     * @access private
     * @param string $in
     * @return string
     */
    function _encryptBlock($in)
    {
        static $tables;
        if (empty($tables)) {
            $tables =& $this->_getTables();
        }
        $t0 = $tables[0];
        $t1 = $tables[1];
        $t2 = $tables[2];
        $t3 = $tables[3];
        $sbox = $tables[4];
        $state = array();
        $words = unpack('N*', $in);
        $c = $this->c;
        $w = $this->w;
        $Nb = $this->Nb;
        $Nr = $this->Nr;
        // addRoundKey
        $wc = $Nb - 1;
        foreach ($words as $word) {
            $state[] = $word ^ $w[++$wc];
        }
        // fips-197.pdf#page=19, "Figure 5. Pseudo Code for the Cipher", states that this loop has four components -
        // subBytes, shiftRows, mixColumns, and addRoundKey. fips-197.pdf#page=30, "Implementation Suggestions Regarding
        // Various Platforms" suggests that performs enhanced implementations are described in Rijndael-ammended.pdf.
        // Rijndael-ammended.pdf#page=20, "Implementation aspects / 32-bit processor", discusses such an optimization.
        // Unfortunately, the description given there is not quite correct.  Per aes.spec.v316.pdf#page=19 [1],
        // equation (7.4.7) is supposed to use addition instead of subtraction, so we'll do that here, as well.
        // [1] http://fp.gladman.plus.com/cryptography_technology/rijndael/aes.spec.v316.pdf
        $temp = array();
        for ($round = 1; $round < $Nr; ++$round) {
            $i = 0;
            // $c[0] == 0
            $j = $c[1];
            $k = $c[2];
            $l = $c[3];
            while ($i < $Nb) {
                $temp[$i] = $t0[$state[$i] >> 24 & 255] ^ $t1[$state[$j] >> 16 & 255] ^ $t2[$state[$k] >> 8 & 255] ^ $t3[$state[$l] & 255] ^ $w[++$wc];
                ++$i;
                $j = ($j + 1) % $Nb;
                $k = ($k + 1) % $Nb;
                $l = ($l + 1) % $Nb;
            }
            $state = $temp;
        }
        // subWord
        for ($i = 0; $i < $Nb; ++$i) {
            $state[$i] = $sbox[$state[$i] & 255] | $sbox[$state[$i] >> 8 & 255] << 8 | $sbox[$state[$i] >> 16 & 255] << 16 | $sbox[$state[$i] >> 24 & 255] << 24;
        }
        // shiftRows + addRoundKey
        $i = 0;
        // $c[0] == 0
        $j = $c[1];
        $k = $c[2];
        $l = $c[3];
        while ($i < $Nb) {
            $temp[$i] = $state[$i] & 4278190080 ^ $state[$j] & 16711680 ^ $state[$k] & 65280 ^ $state[$l] & 255 ^ $w[$i];
            ++$i;
            $j = ($j + 1) % $Nb;
            $k = ($k + 1) % $Nb;
            $l = ($l + 1) % $Nb;
        }
        switch ($Nb) {
            case 8:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5], $temp[6], $temp[7]);
            case 7:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5], $temp[6]);
            case 6:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5]);
            case 5:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4]);
            default:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3]);
        }
    }
    /**
     * Decrypts a block
     *
     * @access private
     * @param string $in
     * @return string
     */
    function _decryptBlock($in)
    {
        static $invtables;
        if (empty($invtables)) {
            $invtables =& $this->_getInvTables();
        }
        $dt0 = $invtables[0];
        $dt1 = $invtables[1];
        $dt2 = $invtables[2];
        $dt3 = $invtables[3];
        $isbox = $invtables[4];
        $state = array();
        $words = unpack('N*', $in);
        $c = $this->c;
        $dw = $this->dw;
        $Nb = $this->Nb;
        $Nr = $this->Nr;
        // addRoundKey
        $wc = $Nb - 1;
        foreach ($words as $word) {
            $state[] = $word ^ $dw[++$wc];
        }
        $temp = array();
        for ($round = $Nr - 1; $round > 0; --$round) {
            $i = 0;
            // $c[0] == 0
            $j = $Nb - $c[1];
            $k = $Nb - $c[2];
            $l = $Nb - $c[3];
            while ($i < $Nb) {
                $temp[$i] = $dt0[$state[$i] >> 24 & 255] ^ $dt1[$state[$j] >> 16 & 255] ^ $dt2[$state[$k] >> 8 & 255] ^ $dt3[$state[$l] & 255] ^ $dw[++$wc];
                ++$i;
                $j = ($j + 1) % $Nb;
                $k = ($k + 1) % $Nb;
                $l = ($l + 1) % $Nb;
            }
            $state = $temp;
        }
        // invShiftRows + invSubWord + addRoundKey
        $i = 0;
        // $c[0] == 0
        $j = $Nb - $c[1];
        $k = $Nb - $c[2];
        $l = $Nb - $c[3];
        while ($i < $Nb) {
            $word = $state[$i] & 4278190080 | $state[$j] & 16711680 | $state[$k] & 65280 | $state[$l] & 255;
            $temp[$i] = $dw[$i] ^ ($isbox[$word & 255] | $isbox[$word >> 8 & 255] << 8 | $isbox[$word >> 16 & 255] << 16 | $isbox[$word >> 24 & 255] << 24);
            ++$i;
            $j = ($j + 1) % $Nb;
            $k = ($k + 1) % $Nb;
            $l = ($l + 1) % $Nb;
        }
        switch ($Nb) {
            case 8:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5], $temp[6], $temp[7]);
            case 7:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5], $temp[6]);
            case 6:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4], $temp[5]);
            case 5:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3], $temp[4]);
            default:
                return pack('N*', $temp[0], $temp[1], $temp[2], $temp[3]);
        }
    }
    /**
     * Setup the key (expansion)
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_setupKey()
     * @access private
     */
    function _setupKey()
    {
        // Each number in $rcon is equal to the previous number multiplied by two in Rijndael's finite field.
        // See http://en.wikipedia.org/wiki/Finite_field_arithmetic#Multiplicative_inverse
        static $rcon = array(0, 16777216, 33554432, 67108864, 134217728, 268435456, 536870912, 1073741824, 2147483648, 452984832, 905969664, 1811939328, 3623878656, 2868903936, 1291845632, 2583691264, 788529152, 1577058304, 3154116608, 1660944384, 3321888768, 2533359616, 889192448, 1778384896, 3556769792, 3003121664, 2097152000, 4194304000, 4009754624, 3305111552, 2432696320);
        if (isset($this->kl['key']) && $this->key === $this->kl['key'] && $this->key_length === $this->kl['key_length'] && $this->block_size === $this->kl['block_size']) {
            // already expanded
            return;
        }
        $this->kl = array('key' => $this->key, 'key_length' => $this->key_length, 'block_size' => $this->block_size);
        $this->Nk = $this->key_length >> 2;
        // see Rijndael-ammended.pdf#page=44
        $this->Nr = max($this->Nk, $this->Nb) + 6;
        // shift offsets for Nb = 5, 7 are defined in Rijndael-ammended.pdf#page=44,
        //     "Table 8: Shift offsets in Shiftrow for the alternative block lengths"
        // shift offsets for Nb = 4, 6, 8 are defined in Rijndael-ammended.pdf#page=14,
        //     "Table 2: Shift offsets for different block lengths"
        switch ($this->Nb) {
            case 4:
            case 5:
            case 6:
                $this->c = array(0, 1, 2, 3);
                break;
            case 7:
                $this->c = array(0, 1, 2, 4);
                break;
            case 8:
                $this->c = array(0, 1, 3, 4);
        }
        $w = array_values(unpack('N*words', $this->key));
        $length = $this->Nb * ($this->Nr + 1);
        for ($i = $this->Nk; $i < $length; $i++) {
            $temp = $w[$i - 1];
            if ($i % $this->Nk == 0) {
                // according to <http://php.net/language.types.integer>, "the size of an integer is platform-dependent".
                // on a 32-bit machine, it's 32-bits, and on a 64-bit machine, it's 64-bits. on a 32-bit machine,
                // 0xFFFFFFFF << 8 == 0xFFFFFF00, but on a 64-bit machine, it equals 0xFFFFFFFF00. as such, doing 'and'
                // with 0xFFFFFFFF (or 0xFFFFFF00) on a 32-bit machine is unnecessary, but on a 64-bit machine, it is.
                $temp = $temp << 8 & 4294967040 | $temp >> 24 & 255;
                // rotWord
                $temp = $this->_subWord($temp) ^ $rcon[$i / $this->Nk];
            } elseif ($this->Nk > 6 && $i % $this->Nk == 4) {
                $temp = $this->_subWord($temp);
            }
            $w[$i] = $w[$i - $this->Nk] ^ $temp;
        }
        // convert the key schedule from a vector of $Nb * ($Nr + 1) length to a matrix with $Nr + 1 rows and $Nb columns
        // and generate the inverse key schedule.  more specifically,
        // according to <http://csrc.nist.gov/archive/aes/rijndael/Rijndael-ammended.pdf#page=23> (section 5.3.3),
        // "The key expansion for the Inverse Cipher is defined as follows:
        //        1. Apply the Key Expansion.
        //        2. Apply InvMixColumn to all Round Keys except the first and the last one."
        // also, see fips-197.pdf#page=27, "5.3.5 Equivalent Inverse Cipher"
        list($dt0, $dt1, $dt2, $dt3) = $this->_getInvTables();
        $temp = $this->w = $this->dw = array();
        for ($i = $row = $col = 0; $i < $length; $i++, $col++) {
            if ($col == $this->Nb) {
                if ($row == 0) {
                    $this->dw[0] = $this->w[0];
                } else {
                    // subWord + invMixColumn + invSubWord = invMixColumn
                    $j = 0;
                    while ($j < $this->Nb) {
                        $dw = $this->_subWord($this->w[$row][$j]);
                        $temp[$j] = $dt0[$dw >> 24 & 255] ^ $dt1[$dw >> 16 & 255] ^ $dt2[$dw >> 8 & 255] ^ $dt3[$dw & 255];
                        $j++;
                    }
                    $this->dw[$row] = $temp;
                }
                $col = 0;
                $row++;
            }
            $this->w[$row][$col] = $w[$i];
        }
        $this->dw[$row] = $this->w[$row];
        // Converting to 1-dim key arrays (both ascending)
        $this->dw = array_reverse($this->dw);
        $w = array_pop($this->w);
        $dw = array_pop($this->dw);
        foreach ($this->w as $r => $wr) {
            foreach ($wr as $c => $wc) {
                $w[] = $wc;
                $dw[] = $this->dw[$r][$c];
            }
        }
        $this->w = $w;
        $this->dw = $dw;
    }
    /**
     * Performs S-Box substitutions
     *
     * @access private
     * @param int $word
     */
    function _subWord($word)
    {
        static $sbox;
        if (empty($sbox)) {
            list(, , , , $sbox) = $this->_getTables();
        }
        return $sbox[$word & 255] | $sbox[$word >> 8 & 255] << 8 | $sbox[$word >> 16 & 255] << 16 | $sbox[$word >> 24 & 255] << 24;
    }
    /**
     * Provides the mixColumns and sboxes tables
     *
     * @see self::_encryptBlock()
     * @see self::_setupInlineCrypt()
     * @see self::_subWord()
     * @access private
     * @return array &$tables
     */
    function &_getTables()
    {
        static $tables;
        if (empty($tables)) {
            // according to <http://csrc.nist.gov/archive/aes/rijndael/Rijndael-ammended.pdf#page=19> (section 5.2.1),
            // precomputed tables can be used in the mixColumns phase. in that example, they're assigned t0...t3, so
            // those are the names we'll use.
            $t3 = array_map('intval', array(1667474886, 2088535288, 2004326894, 2071694838, 4075949567, 1802223062, 1869591006, 3318043793, 808472672, 16843522, 1734846926, 724270422, 4278065639, 3621216949, 2880169549, 1987484396, 3402253711, 2189597983, 3385409673, 2105378810, 4210693615, 1499065266, 1195886990, 4042263547, 2913856577, 3570689971, 2728590687, 2947541573, 2627518243, 2762274643, 1920112356, 3233831835, 3082273397, 4261223649, 2475929149, 640051788, 909531756, 1061110142, 4160160501, 3435941763, 875846760, 2779116625, 3857003729, 4059105529, 1903268834, 3638064043, 825316194, 353713962, 67374088, 3351728789, 589522246, 3284360861, 404236336, 2526454071, 84217610, 2593830191, 117901582, 303183396, 2155911963, 3806477791, 3958056653, 656894286, 2998062463, 1970642922, 151591698, 2206440989, 741110872, 437923380, 454765878, 1852748508, 1515908788, 2694904667, 1381168804, 993742198, 3604373943, 3014905469, 690584402, 3823320797, 791638366, 2223281939, 1398011302, 3520161977, 0, 3991743681, 538992704, 4244381667, 2981218425, 1532751286, 1785380564, 3419096717, 3200178535, 960056178, 1246420628, 1280103576, 1482221744, 3486468741, 3503319995, 4025428677, 2863326543, 4227536621, 1128514950, 1296947098, 859002214, 2240123921, 1162203018, 4193849577, 33687044, 2139062782, 1347481760, 1010582648, 2678045221, 2829640523, 1364325282, 2745433693, 1077985408, 2408548869, 2459086143, 2644360225, 943212656, 4126475505, 3166494563, 3065430391, 3671750063, 555836226, 269496352, 4294908645, 4092792573, 3537006015, 3452783745, 202118168, 320025894, 3974901699, 1600119230, 2543297077, 1145359496, 387397934, 3301201811, 2812801621, 2122220284, 1027426170, 1684319432, 1566435258, 421079858, 1936954854, 1616945344, 2172753945, 1330631070, 3705438115, 572679748, 707427924, 2425400123, 2290647819, 1179044492, 4008585671, 3099120491, 336870440, 3739122087, 1583276732, 185277718, 3688593069, 3772791771, 842159716, 976899700, 168435220, 1229577106, 101059084, 606366792, 1549591736, 3267517855, 3553849021, 2897014595, 1650632388, 2442242105, 2509612081, 3840161747, 2038008818, 3890688725, 3368567691, 926374254, 1835907034, 2374863873, 3587531953, 1313788572, 2846482505, 1819063512, 1448540844, 4109633523, 3941213647, 1701162954, 2054852340, 2930698567, 134748176, 3132806511, 2021165296, 623210314, 774795868, 471606328, 2795958615, 3031746419, 3334885783, 3907527627, 3722280097, 1953799400, 522133822, 1263263126, 3183336545, 2341176845, 2324333839, 1886425312, 1044267644, 3048588401, 1718004428, 1212733584, 50529542, 4143317495, 235803164, 1633788866, 892690282, 1465383342, 3115962473, 2256965911, 3250673817, 488449850, 2661202215, 3789633753, 4177007595, 2560144171, 286339874, 1768537042, 3654906025, 2391705863, 2492770099, 2610673197, 505291324, 2273808917, 3924369609, 3469625735, 1431699370, 673740880, 3755965093, 2358021891, 2711746649, 2307489801, 218961690, 3217021541, 3873845719, 1111672452, 1751693520, 1094828930, 2576986153, 757954394, 252645662, 2964376443, 1414855848, 3149649517, 370555436));
            foreach ($t3 as $t3i) {
                $t0[] = $t3i << 24 & 4278190080 | $t3i >> 8 & 16777215;
                $t1[] = $t3i << 16 & 4294901760 | $t3i >> 16 & 65535;
                $t2[] = $t3i << 8 & 4294967040 | $t3i >> 24 & 255;
            }
            $tables = array($t0, $t1, $t2, $t3, array(99, 124, 119, 123, 242, 107, 111, 197, 48, 1, 103, 43, 254, 215, 171, 118, 202, 130, 201, 125, 250, 89, 71, 240, 173, 212, 162, 175, 156, 164, 114, 192, 183, 253, 147, 38, 54, 63, 247, 204, 52, 165, 229, 241, 113, 216, 49, 21, 4, 199, 35, 195, 24, 150, 5, 154, 7, 18, 128, 226, 235, 39, 178, 117, 9, 131, 44, 26, 27, 110, 90, 160, 82, 59, 214, 179, 41, 227, 47, 132, 83, 209, 0, 237, 32, 252, 177, 91, 106, 203, 190, 57, 74, 76, 88, 207, 208, 239, 170, 251, 67, 77, 51, 133, 69, 249, 2, 127, 80, 60, 159, 168, 81, 163, 64, 143, 146, 157, 56, 245, 188, 182, 218, 33, 16, 255, 243, 210, 205, 12, 19, 236, 95, 151, 68, 23, 196, 167, 126, 61, 100, 93, 25, 115, 96, 129, 79, 220, 34, 42, 144, 136, 70, 238, 184, 20, 222, 94, 11, 219, 224, 50, 58, 10, 73, 6, 36, 92, 194, 211, 172, 98, 145, 149, 228, 121, 231, 200, 55, 109, 141, 213, 78, 169, 108, 86, 244, 234, 101, 122, 174, 8, 186, 120, 37, 46, 28, 166, 180, 198, 232, 221, 116, 31, 75, 189, 139, 138, 112, 62, 181, 102, 72, 3, 246, 14, 97, 53, 87, 185, 134, 193, 29, 158, 225, 248, 152, 17, 105, 217, 142, 148, 155, 30, 135, 233, 206, 85, 40, 223, 140, 161, 137, 13, 191, 230, 66, 104, 65, 153, 45, 15, 176, 84, 187, 22));
        }
        return $tables;
    }
    /**
     * Provides the inverse mixColumns and inverse sboxes tables
     *
     * @see self::_decryptBlock()
     * @see self::_setupInlineCrypt()
     * @see self::_setupKey()
     * @access private
     * @return array &$tables
     */
    function &_getInvTables()
    {
        static $tables;
        if (empty($tables)) {
            $dt3 = array_map('intval', array(4104605777, 1097159550, 396673818, 660510266, 2875968315, 2638606623, 4200115116, 3808662347, 821712160, 1986918061, 3430322568, 38544885, 3856137295, 718002117, 893681702, 1654886325, 2975484382, 3122358053, 3926825029, 4274053469, 796197571, 1290801793, 1184342925, 3556361835, 2405426947, 2459735317, 1836772287, 1381620373, 3196267988, 1948373848, 3764988233, 3385345166, 3263785589, 2390325492, 1480485785, 3111247143, 3780097726, 2293045232, 548169417, 3459953789, 3746175075, 439452389, 1362321559, 1400849762, 1685577905, 1806599355, 2174754046, 137073913, 1214797936, 1174215055, 3731654548, 2079897426, 1943217067, 1258480242, 529487843, 1437280870, 3945269170, 3049390895, 3313212038, 923313619, 679998000, 3215307299, 57326082, 377642221, 3474729866, 2041877159, 133361907, 1776460110, 3673476453, 96392454, 878845905, 2801699524, 777231668, 4082475170, 2330014213, 4142626212, 2213296395, 1626319424, 1906247262, 1846563261, 562755902, 3708173718, 1040559837, 3871163981, 1418573201, 3294430577, 114585348, 1343618912, 2566595609, 3186202582, 1078185097, 3651041127, 3896688048, 2307622919, 425408743, 3371096953, 2081048481, 1108339068, 2216610296, 0, 2156299017, 736970802, 292596766, 1517440620, 251657213, 2235061775, 2933202493, 758720310, 265905162, 1554391400, 1532285339, 908999204, 174567692, 1474760595, 4002861748, 2610011675, 3234156416, 3693126241, 2001430874, 303699484, 2478443234, 2687165888, 585122620, 454499602, 151849742, 2345119218, 3064510765, 514443284, 4044981591, 1963412655, 2581445614, 2137062819, 19308535, 1928707164, 1715193156, 4219352155, 1126790795, 600235211, 3992742070, 3841024952, 836553431, 1669664834, 2535604243, 3323011204, 1243905413, 3141400786, 4180808110, 698445255, 2653899549, 2989552604, 2253581325, 3252932727, 3004591147, 1891211689, 2487810577, 3915653703, 4237083816, 4030667424, 2100090966, 865136418, 1229899655, 953270745, 3399679628, 3557504664, 4118925222, 2061379749, 3079546586, 2915017791, 983426092, 2022837584, 1607244650, 2118541908, 2366882550, 3635996816, 972512814, 3283088770, 1568718495, 3499326569, 3576539503, 621982671, 2895723464, 410887952, 2623762152, 1002142683, 645401037, 1494807662, 2595684844, 1335535747, 2507040230, 4293295786, 3167684641, 367585007, 3885750714, 1865862730, 2668221674, 2960971305, 2763173681, 1059270954, 2777952454, 2724642869, 1320957812, 2194319100, 2429595872, 2815956275, 77089521, 3973773121, 3444575871, 2448830231, 1305906550, 4021308739, 2857194700, 2516901860, 3518358430, 1787304780, 740276417, 1699839814, 1592394909, 2352307457, 2272556026, 188821243, 1729977011, 3687994002, 274084841, 3594982253, 3613494426, 2701949495, 4162096729, 322734571, 2837966542, 1640576439, 484830689, 1202797690, 3537852828, 4067639125, 349075736, 3342319475, 4157467219, 4255800159, 1030690015, 1155237496, 2951971274, 1757691577, 607398968, 2738905026, 499347990, 3794078908, 1011452712, 227885567, 2818666809, 213114376, 3034881240, 1455525988, 3414450555, 850817237, 1817998408, 3092726480));
            foreach ($dt3 as $dt3i) {
                $dt0[] = $dt3i << 24 & 4278190080 | $dt3i >> 8 & 16777215;
                $dt1[] = $dt3i << 16 & 4294901760 | $dt3i >> 16 & 65535;
                $dt2[] = $dt3i << 8 & 4294967040 | $dt3i >> 24 & 255;
            }
            $tables = array($dt0, $dt1, $dt2, $dt3, array(82, 9, 106, 213, 48, 54, 165, 56, 191, 64, 163, 158, 129, 243, 215, 251, 124, 227, 57, 130, 155, 47, 255, 135, 52, 142, 67, 68, 196, 222, 233, 203, 84, 123, 148, 50, 166, 194, 35, 61, 238, 76, 149, 11, 66, 250, 195, 78, 8, 46, 161, 102, 40, 217, 36, 178, 118, 91, 162, 73, 109, 139, 209, 37, 114, 248, 246, 100, 134, 104, 152, 22, 212, 164, 92, 204, 93, 101, 182, 146, 108, 112, 72, 80, 253, 237, 185, 218, 94, 21, 70, 87, 167, 141, 157, 132, 144, 216, 171, 0, 140, 188, 211, 10, 247, 228, 88, 5, 184, 179, 69, 6, 208, 44, 30, 143, 202, 63, 15, 2, 193, 175, 189, 3, 1, 19, 138, 107, 58, 145, 17, 65, 79, 103, 220, 234, 151, 242, 207, 206, 240, 180, 230, 115, 150, 172, 116, 34, 231, 173, 53, 133, 226, 249, 55, 232, 28, 117, 223, 110, 71, 241, 26, 113, 29, 41, 197, 137, 111, 183, 98, 14, 170, 24, 190, 27, 252, 86, 62, 75, 198, 210, 121, 32, 154, 219, 192, 254, 120, 205, 90, 244, 31, 221, 168, 51, 136, 7, 199, 49, 177, 18, 16, 89, 39, 128, 236, 95, 96, 81, 127, 169, 25, 181, 74, 13, 45, 229, 122, 159, 147, 201, 156, 239, 160, 224, 59, 77, 174, 42, 245, 176, 200, 235, 187, 60, 131, 83, 153, 97, 23, 43, 4, 126, 186, 119, 214, 38, 225, 105, 20, 99, 85, 33, 12, 125));
        }
        return $tables;
    }
    /**
     * Setup the performance-optimized function for de/encrypt()
     *
     * @see \phpseclib\Crypt\Common\SymmetricKey::_setupInlineCrypt()
     * @access private
     */
    function _setupInlineCrypt()
    {
        // Note: _setupInlineCrypt() will be called only if $this->changed === true
        // So here we are'nt under the same heavy timing-stress as we are in _de/encryptBlock() or de/encrypt().
        // However...the here generated function- $code, stored as php callback in $this->inline_crypt, must work as fast as even possible.
        $lambda_functions =& self::_getLambdaFunctions();
        // We create max. 10 hi-optimized code for memory reason. Means: For each $key one ultra fast inline-crypt function.
        // (Currently, for Crypt_Rijndael/AES, one generated $lambda_function cost on php5.5@32bit ~80kb unfreeable mem and ~130kb on php5.5@64bit)
        // After that, we'll still create very fast optimized code but not the hi-ultimative code, for each $mode one.
        $gen_hi_opt_code = (bool) (count($lambda_functions) < 10);
        // Generation of a uniqe hash for our generated code
        $code_hash = "Crypt_Rijndael, {$this->mode}, {$this->Nr}, {$this->Nb}";
        if ($gen_hi_opt_code) {
            $code_hash = str_pad($code_hash, 32) . $this->_hashInlineCryptFunction($this->key);
        }
        if (!isset($lambda_functions[$code_hash])) {
            switch (true) {
                case $gen_hi_opt_code:
                    // The hi-optimized $lambda_functions will use the key-words hardcoded for better performance.
                    $w = $this->w;
                    $dw = $this->dw;
                    $init_encrypt = '';
                    $init_decrypt = '';
                    break;
                default:
                    for ($i = 0, $cw = count($this->w); $i < $cw; ++$i) {
                        $w[] = '$w[' . $i . ']';
                        $dw[] = '$dw[' . $i . ']';
                    }
                    $init_encrypt = '$w  = $self->w;';
                    $init_decrypt = '$dw = $self->dw;';
            }
            $Nr = $this->Nr;
            $Nb = $this->Nb;
            $c = $this->c;
            // Generating encrypt code:
            $init_encrypt .= '
                static $tables;
                if (empty($tables)) {
                    $tables = &$self->_getTables();
                }
                $t0   = $tables[0];
                $t1   = $tables[1];
                $t2   = $tables[2];
                $t3   = $tables[3];
                $sbox = $tables[4];
            ';
            $s = 'e';
            $e = 's';
            $wc = $Nb - 1;
            // Preround: addRoundKey
            $encrypt_block = '$in = unpack("N*", $in);' . '
';
            for ($i = 0; $i < $Nb; ++$i) {
                $encrypt_block .= '$s' . $i . ' = $in[' . ($i + 1) . '] ^ ' . $w[++$wc] . ';
';
            }
            // Mainrounds: shiftRows + subWord + mixColumns + addRoundKey
            for ($round = 1; $round < $Nr; ++$round) {
                list($s, $e) = array($e, $s);
                for ($i = 0; $i < $Nb; ++$i) {
                    $encrypt_block .= '$' . $e . $i . ' =
                        $t0[($' . $s . $i . ' >> 24) & 0xff] ^
                        $t1[($' . $s . ($i + $c[1]) % $Nb . ' >> 16) & 0xff] ^
                        $t2[($' . $s . ($i + $c[2]) % $Nb . ' >>  8) & 0xff] ^
                        $t3[ $' . $s . ($i + $c[3]) % $Nb . '        & 0xff] ^
                        ' . $w[++$wc] . ';
';
                }
            }
            // Finalround: subWord + shiftRows + addRoundKey
            for ($i = 0; $i < $Nb; ++$i) {
                $encrypt_block .= '$' . $e . $i . ' =
                     $sbox[ $' . $e . $i . '        & 0xff]        |
                    ($sbox[($' . $e . $i . ' >>  8) & 0xff] <<  8) |
                    ($sbox[($' . $e . $i . ' >> 16) & 0xff] << 16) |
                    ($sbox[($' . $e . $i . ' >> 24) & 0xff] << 24);' . '
';
            }
            $encrypt_block .= '$in = pack("N*"' . '
';
            for ($i = 0; $i < $Nb; ++$i) {
                $encrypt_block .= ',
                    ($' . $e . $i . ' & ' . (int) 4278190080 . ') ^
                    ($' . $e . ($i + $c[1]) % $Nb . ' &         0x00FF0000   ) ^
                    ($' . $e . ($i + $c[2]) % $Nb . ' &         0x0000FF00   ) ^
                    ($' . $e . ($i + $c[3]) % $Nb . ' &         0x000000FF   ) ^
                    ' . $w[$i] . '
';
            }
            $encrypt_block .= ');';
            // Generating decrypt code:
            $init_decrypt .= '
                static $invtables;
                if (empty($invtables)) {
                    $invtables = &$self->_getInvTables();
                }
                $dt0   = $invtables[0];
                $dt1   = $invtables[1];
                $dt2   = $invtables[2];
                $dt3   = $invtables[3];
                $isbox = $invtables[4];
            ';
            $s = 'e';
            $e = 's';
            $wc = $Nb - 1;
            // Preround: addRoundKey
            $decrypt_block = '$in = unpack("N*", $in);' . '
';
            for ($i = 0; $i < $Nb; ++$i) {
                $decrypt_block .= '$s' . $i . ' = $in[' . ($i + 1) . '] ^ ' . $dw[++$wc] . ';' . '
';
            }
            // Mainrounds: shiftRows + subWord + mixColumns + addRoundKey
            for ($round = 1; $round < $Nr; ++$round) {
                list($s, $e) = array($e, $s);
                for ($i = 0; $i < $Nb; ++$i) {
                    $decrypt_block .= '$' . $e . $i . ' =
                        $dt0[($' . $s . $i . ' >> 24) & 0xff] ^
                        $dt1[($' . $s . ($Nb + $i - $c[1]) % $Nb . ' >> 16) & 0xff] ^
                        $dt2[($' . $s . ($Nb + $i - $c[2]) % $Nb . ' >>  8) & 0xff] ^
                        $dt3[ $' . $s . ($Nb + $i - $c[3]) % $Nb . '        & 0xff] ^
                        ' . $dw[++$wc] . ';
';
                }
            }
            // Finalround: subWord + shiftRows + addRoundKey
            for ($i = 0; $i < $Nb; ++$i) {
                $decrypt_block .= '$' . $e . $i . ' =
                     $isbox[ $' . $e . $i . '        & 0xff]        |
                    ($isbox[($' . $e . $i . ' >>  8) & 0xff] <<  8) |
                    ($isbox[($' . $e . $i . ' >> 16) & 0xff] << 16) |
                    ($isbox[($' . $e . $i . ' >> 24) & 0xff] << 24);' . '
';
            }
            $decrypt_block .= '$in = pack("N*"' . '
';
            for ($i = 0; $i < $Nb; ++$i) {
                $decrypt_block .= ',
                    ($' . $e . $i . ' & ' . (int) 4278190080 . ') ^
                    ($' . $e . ($Nb + $i - $c[1]) % $Nb . ' &         0x00FF0000   ) ^
                    ($' . $e . ($Nb + $i - $c[2]) % $Nb . ' &         0x0000FF00   ) ^
                    ($' . $e . ($Nb + $i - $c[3]) % $Nb . ' &         0x000000FF   ) ^
                    ' . $dw[$i] . '
';
            }
            $decrypt_block .= ');';
            $lambda_functions[$code_hash] = $this->_createInlineCryptFunction(array('init_crypt' => '', 'init_encrypt' => $init_encrypt, 'init_decrypt' => $init_decrypt, 'encrypt_block' => $encrypt_block, 'decrypt_block' => $decrypt_block));
        }
        $this->inline_crypt = $lambda_functions[$code_hash];
    }
}