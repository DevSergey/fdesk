<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[268]\\d{8}',
    'PossibleNumberPattern' => '\\d{9}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '262\\d{6}',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '262161234',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '6(?:9[23]|47)\\d{6}',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '692123456',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '80\\d{7}',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '801234567',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '8(?:1[01]|2[0156]|84|9[0-37-9])\\d{6}',
    'PossibleNumberPattern' => '\\d{9}',
    'ExampleNumber' => '810123456',
  ),
  'sharedCost' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'personalNumber' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voip' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'shortCode' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'standardRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'carrierSpecific' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'noInternationalDialling' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'id' => 'RE',
  'countryCode' => 262,
  'internationalPrefix' => '00',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '([268]\\d{2})(\\d{2})(\\d{2})(\\d{2})',
      'format' => '$1 $2 $3 $4',
      'leadingDigitsPatterns' => 
      array (
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingDigits' => '262|6(?:9[23]|47)|8',
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);