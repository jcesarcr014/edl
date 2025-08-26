<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * IssuingDistributionPoint
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
 * IssuingDistributionPoint
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class IssuingDistributionPoint
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('distributionPoint' => array('constant' => 0, 'optional' => true, 'explicit' => true) + DistributionPointName::MAP, 'onlyContainsUserCerts' => array('type' => ASN1::TYPE_BOOLEAN, 'constant' => 1, 'optional' => true, 'default' => false, 'implicit' => true), 'onlyContainsCACerts' => array('type' => ASN1::TYPE_BOOLEAN, 'constant' => 2, 'optional' => true, 'default' => false, 'implicit' => true), 'onlySomeReasons' => array('constant' => 3, 'optional' => true, 'implicit' => true) + ReasonFlags::MAP, 'indirectCRL' => array('type' => ASN1::TYPE_BOOLEAN, 'constant' => 4, 'optional' => true, 'default' => false, 'implicit' => true), 'onlyContainsAttributeCerts' => array('type' => ASN1::TYPE_BOOLEAN, 'constant' => 5, 'optional' => true, 'default' => false, 'implicit' => true)));
}