<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * TBSCertificate
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
 * TBSCertificate
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class TBSCertificate
{
    // assert($TBSCertificate['children']['signature'] == $Certificate['children']['signatureAlgorithm'])
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('version' => array('type' => ASN1::TYPE_INTEGER, 'constant' => 0, 'optional' => true, 'explicit' => true, 'mapping' => array('v1', 'v2', 'v3'), 'default' => 'v1'), 'serialNumber' => CertificateSerialNumber::MAP, 'signature' => AlgorithmIdentifier::MAP, 'issuer' => Name::MAP, 'validity' => Validity::MAP, 'subject' => Name::MAP, 'subjectPublicKeyInfo' => SubjectPublicKeyInfo::MAP, 'issuerUniqueID' => array('constant' => 1, 'optional' => true, 'implicit' => true) + UniqueIdentifier::MAP, 'subjectUniqueID' => array('constant' => 2, 'optional' => true, 'implicit' => true) + UniqueIdentifier::MAP, 'extensions' => array('constant' => 3, 'optional' => true, 'explicit' => true) + Extensions::MAP));
}