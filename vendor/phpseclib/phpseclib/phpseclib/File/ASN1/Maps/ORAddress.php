<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * ORAddress
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
 * ORAddress
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class ORAddress
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('built-in-standard-attributes' => BuiltInStandardAttributes::MAP, 'built-in-domain-defined-attributes' => array('optional' => true) + BuiltInDomainDefinedAttributes::MAP, 'extension-attributes' => array('optional' => true) + ExtensionAttributes::MAP));
}