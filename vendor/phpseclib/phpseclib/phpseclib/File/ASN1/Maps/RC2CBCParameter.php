<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * RC2CBCParameter
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
 * RC2CBCParameter
 *
 * from https://tools.ietf.org/html/rfc2898#appendix-A.3
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class RC2CBCParameter
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('rc2ParametersVersion' => array('type' => ASN1::TYPE_INTEGER, 'optional' => true), 'iv' => array('type' => ASN1::TYPE_OCTET_STRING)));
}