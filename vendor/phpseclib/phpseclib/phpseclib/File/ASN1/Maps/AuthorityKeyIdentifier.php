<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * AuthorityKeyIdentifier
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
 * AuthorityKeyIdentifier
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class AuthorityKeyIdentifier
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('keyIdentifier' => array('constant' => 0, 'optional' => true, 'implicit' => true) + KeyIdentifier::MAP, 'authorityCertIssuer' => array('constant' => 1, 'optional' => true, 'implicit' => true) + GeneralNames::MAP, 'authorityCertSerialNumber' => array('constant' => 2, 'optional' => true, 'implicit' => true) + CertificateSerialNumber::MAP));
}