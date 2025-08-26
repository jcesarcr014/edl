<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * RDNSequence
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
 * RDNSequence
 *
 * In practice, RDNs containing multiple name-value pairs (called "multivalued RDNs") are rare,
 * but they can be useful at times when either there is no unique attribute in the entry or you
 * want to ensure that the entry's DN contains some useful identifying information.
 *
 * - https://www.opends.org/wiki/page/DefinitionRelativeDistinguishedName
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class RDNSequence
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'min' => 0, 'max' => -1, 'children' => RelativeDistinguishedName::MAP);
}