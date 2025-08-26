<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * GeneralName
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
 * GeneralName
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class GeneralName
{
    const MAP = array('type' => ASN1::TYPE_CHOICE, 'children' => array('otherName' => array('constant' => 0, 'optional' => true, 'implicit' => true) + AnotherName::MAP, 'rfc822Name' => array('type' => ASN1::TYPE_IA5_STRING, 'constant' => 1, 'optional' => true, 'implicit' => true), 'dNSName' => array('type' => ASN1::TYPE_IA5_STRING, 'constant' => 2, 'optional' => true, 'implicit' => true), 'x400Address' => array('constant' => 3, 'optional' => true, 'implicit' => true) + ORAddress::MAP, 'directoryName' => array('constant' => 4, 'optional' => true, 'explicit' => true) + Name::MAP, 'ediPartyName' => array('constant' => 5, 'optional' => true, 'implicit' => true) + EDIPartyName::MAP, 'uniformResourceIdentifier' => array('type' => ASN1::TYPE_IA5_STRING, 'constant' => 6, 'optional' => true, 'implicit' => true), 'iPAddress' => array('type' => ASN1::TYPE_OCTET_STRING, 'constant' => 7, 'optional' => true, 'implicit' => true), 'registeredID' => array('type' => ASN1::TYPE_OBJECT_IDENTIFIER, 'constant' => 8, 'optional' => true, 'implicit' => true)));
}