<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
/**
 * BuiltInStandardAttributes
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
 * BuiltInStandardAttributes
 *
 * @package ASN1
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class BuiltInStandardAttributes
{
    const MAP = array('type' => ASN1::TYPE_SEQUENCE, 'children' => array('country-name' => array('optional' => true) + CountryName::MAP, 'administration-domain-name' => array('optional' => true) + AdministrationDomainName::MAP, 'network-address' => array('constant' => 0, 'optional' => true, 'implicit' => true) + NetworkAddress::MAP, 'terminal-identifier' => array('constant' => 1, 'optional' => true, 'implicit' => true) + TerminalIdentifier::MAP, 'private-domain-name' => array('constant' => 2, 'optional' => true, 'explicit' => true) + PrivateDomainName::MAP, 'organization-name' => array('constant' => 3, 'optional' => true, 'implicit' => true) + OrganizationName::MAP, 'numeric-user-identifier' => array('constant' => 4, 'optional' => true, 'implicit' => true) + NumericUserIdentifier::MAP, 'personal-name' => array('constant' => 5, 'optional' => true, 'implicit' => true) + PersonalName::MAP, 'organizational-unit-names' => array('constant' => 6, 'optional' => true, 'implicit' => true) + OrganizationalUnitNames::MAP));
}