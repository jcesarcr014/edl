<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * PBKDF2params
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
 * PBKDF2params
 *
 * from https://tools.ietf.org/html/rfc2898#appendix-A.3
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class PBKDF2params
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('salt' => array('type' => ASN1::TYPE_OCTET_STRING), 'iterationCount' => array('type' => ASN1::TYPE_INTEGER), 'keyLength' => array('type' => ASN1::TYPE_INTEGER, 'optional' => true), 'prf' => AlgorithmIdentifier::MAP + array('optional' => true)));
}