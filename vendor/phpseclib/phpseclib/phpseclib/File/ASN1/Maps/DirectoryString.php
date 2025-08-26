<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * DirectoryString
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
 * DirectoryString
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class DirectoryString
{
    const MAP = array('type' => ASN1::TYPE_CHOICE, 'children' => array('teletexString' => array('type' => ASN1::TYPE_TELETEX_STRING), 'printableString' => array('type' => ASN1::TYPE_PRINTABLE_STRING), 'universalString' => array('type' => ASN1::TYPE_UNIVERSAL_STRING), 'utf8String' => array('type' => ASN1::TYPE_UTF8_STRING), 'bmpString' => array('type' => ASN1::TYPE_BMP_STRING)));
}