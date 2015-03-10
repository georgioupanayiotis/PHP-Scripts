/**
  * Return an SEO-friendly URL-Friendly
  * @param string $string
  * @return string
  */

function seoFriendlyUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}
