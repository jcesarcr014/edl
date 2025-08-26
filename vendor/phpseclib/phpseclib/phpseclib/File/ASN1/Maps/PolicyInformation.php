<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * PolicyInformation
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
 * PolicyInformation
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class PolicyInformation
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('policyIdentifier' => CertPolicyId::MAP, 'policyQualifiers' => array('type' => ASN1::TYPE_SEQUENCE, 'min' => 0, 'max' => -1, 'optional' => true, 'children' => PolicyQualifierInfo::MAP)));
}