<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * CountryName
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
 * CountryName
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class CountryName
{
    const MAP = array('type' => ASN1::TYPE_CHOICE, 'class' => ASN1::CLASS_APPLICATION, 'cast' => 1, 'children' => array('x121-dcc-code' => array('type' => ASN1::TYPE_NUMERIC_STRING), 'iso-3166-alpha2-code' => array('type' => ASN1::TYPE_PRINTABLE_STRING)));
}