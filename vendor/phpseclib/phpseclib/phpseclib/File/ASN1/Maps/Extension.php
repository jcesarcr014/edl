<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * Extension
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
 * Extension
 *
 * A certificate using system MUST reject the certificate if it encounters
 * a critical extension it does not recognize; however, a non-critical
 * extension may be ignored if it is not recognized.
 *
 * http://tools.ietf.org/html/rfc5280#section-4.2
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class Extension
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('extnId' => array('type' => ASN1::TYPE_OBJECT_IDENTIFIER), 'critical' => array('type' => ASN1::TYPE_BOOLEAN, 'optional' => true, 'default' => false), 'extnValue' => array('type' => ASN1::TYPE_OCTET_STRING)));
}