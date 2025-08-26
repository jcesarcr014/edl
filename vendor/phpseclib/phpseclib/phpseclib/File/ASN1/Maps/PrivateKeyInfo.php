<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * PrivateKeyInfo
 *
 * PHP version 5
 *
 * @category  File
 * @package   ASN1
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace phpseclib\File\ASN1\Maps;

use phpseclib\File\ASN1;
/**
 * PrivateKeyInfo
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class PrivateKeyInfo
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('version' => array('type' => ASN1::TYPE_INTEGER, 'mapping' => array('v1')), 'privateKeyAlgorithm' => AlgorithmIdentifier::MAP, 'privateKey' => PrivateKey::MAP, 'attributes' => array('constant' => 0, 'optional' => true, 'implicit' => true) + Attributes::MAP));
}