<?php
return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '
          [289]\\d{7,9}|
          [3-7]\\d{7}
        ',
    'PossibleNumberPattern' => '\\d{7,10}',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '24099\\d{3}|(?:3[2-79]|[479][2-689]|6[235-9])\\d{6}',
    'PossibleNumberPattern' => '\\d{7,8}',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '2(?:[027]\\d{7}|9\\d{6,7}|1(?:0\\d{5,7}|[12]\\d{5,6}|[3-9]\\d{5})|4[1-9]\\d{6}|8\\d{7,8})',
    'PossibleNumberPattern' => '\\d{8,10}',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '800\\d{6,7}',
    'PossibleNumberPattern' => '\\d{9,10}',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '900\\d{6,7}',
    'PossibleNumberPattern' => '\\d{9,10}',
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
  'id' => 'NZ',
  'countryCode' => 64,
  'internationalPrefix' => '00',
  'nationalPrefix' => '0',
  'nationalPrefixForParsing' => '0',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
    0 => 
    array (
      'pattern' => '(\\d)(\\d{3})(\\d{4})',
      'format' => '$1-$2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '24|[34679]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    1 => 
    array (
      'pattern' => '(\\d)(\\d{3})(\\d{3,5})',
      'format' => '$1-$2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '2[179]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
    2 => 
    array (
      'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
      'format' => '$1 $2 $3',
      'leadingDigitsPatterns' => 
      array (
        0 => '[89]',
      ),
      'nationalPrefixFormattingRule' => '0$1',
      'domesticCarrierCodeFormattingRule' => '',
    ),
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);
