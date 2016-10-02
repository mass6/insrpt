<?php
use Aws\CloudFront\Exception\Exception;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Config;
use Insight\Companies\Company;

/**
 * Created by:
 * User: sam
 * Date: 7/27/14
 * Time: 11:06 AM
 */

function getLayout()
{
    $company = Session::get('company', '36s');
    $layout = 'layouts.' . Config::get('view.layout.customer.' . $company, Config::get('view.layout.default', 'layouts.default'));
    return $layout;
}

/**
 * @param $path
 * @param bool $parent
 * @return string
 */
function isPath($path, $parent = false)
{
    if (Request::is($path)){
        if ($parent == true){
            return 'opened active';
        }
        else return 'active';
    }
    return '';
}

function statusLabel($status)
{
    switch ($status) {
    case "SRC":
        return 'label label-info';
        break;
    case "PRI":
        return 'label label-success';
        break;
    case "CLS":
        return 'label label-danger';
        break;
    default:
        return null;
    }
}

/**
 * Converts and object to an array
 *
 * @param $data
 * @return array
 */
function object_to_array($data)
{
    if(is_array($data) || is_object($data))
    {
        $result = array();

        foreach($data as $key => $value) {
            $result[$key] = object_to_array($value);
        }

        return $result;
    }

    return $data;
}

function jsonToArray($jsonArray)
{
    if(isset($jsonArray))
    {
        $jsonArray = json_decode($jsonArray);

        // final array to be returned
        $attributes = array();

        foreach ($jsonArray as $attribute)
        {
            // if only 1 index, add second blank index
            if ( ! is_object($attribute)  ) {
                $attribute = array($attribute => "");
            }

            foreach($attribute as $key => $val)
            {
                $attributes[] = array($key, $val);
            }

        }
        return json_encode($attributes);
    }
    else
        return false;
}

/**
 * Converts a price to integer for persisting to db
 *
 * @param $price
 * @return mixed
 */
function priceToInteger($price)
{
    return $price * 100;
}

function removeCommasFromPriceFields($price)
{
    if (!isset($price)) {
        throw new \InvalidArgumentException('Price variable must contain a value.');
    }

    if (! is_array($price)) {

        return str_replace(',','',$price);

    }

    $prices = [];
    foreach ($price as $key => $value) {
        $prices[$key] = removeCommasFromPriceFields($value);
    }

    return $prices;

}

function formatComment($comment)
{
    return strpos($comment,'||') ? substr($comment,0,strpos($comment,'||')) . '<blockquote>' . substr($comment,strpos($comment,'||') + 2) . '</blockquote>' : $comment;
}

function download_csv_results($data, $name = NULL)
{
    if( ! $name)
    {
        $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='. $name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "w");

    foreach($data as $data)
    {
        fputcsv($outstream, $data);
    }

    fclose($outstream);
}

function getCountries()
{
    return ["" => ""] + [
    "Afghanistan" => "Afghanistan",
    "Albania" => "Albania",
    "Algeria" => "Algeria",
    "Andorra" => "Andorra",
    "Angola" => "Angola",
    "Antigua & Deps" => "Antigua & Deps",
    "Argentina" => "Argentina",
    "Armenia" => "Armenia",
    "Australia" => "Australia",
    "Austria" => "Austria",
    "Azerbaijan" => "Azerbaijan",
    "Bahamas" => "Bahamas",
    "Bahrain" => "Bahrain",
    "Bangladesh" => "Bangladesh",
    "Barbados" => "Barbados",
    "Belarus" => "Belarus",
    "Belgium" => "Belgium",
    "Belize" => "Belize",
    "Benin" => "Benin",
    "Bhutan" => "Bhutan",
    "Bolivia" => "Bolivia",
    "Bosnia Herzegovina" => "Bosnia Herzegovina",
    "Botswana" => "Botswana",
    "Brazil" => "Brazil",
    "Brunei" => "Brunei",
    "Bulgaria" => "Bulgaria",
    "Burkina" => "Burkina",
    "Burundi" => "Burundi",
    "Cambodia" => "Cambodia",
    "Cameroon" => "Cameroon",
    "Canada" => "Canada",
    "Cape Verde" => "Cape Verde",
    "Central African Rep" => "Central African Rep",
    "Chad" => "Chad",
    "Chile" => "Chile",
    "China" => "China",
    "Colombia" => "Colombia",
    "Comoros" => "Comoros",
    "Congo" => "Congo",
    "Congo {Democratic Rep}" => "Congo {Democratic Rep}",
    "Costa Rica" => "Costa Rica",
    "Croatia" => "Croatia",
    "Cuba" => "Cuba",
    "Cyprus" => "Cyprus",
    "Czech Republic" => "Czech Republic",
    "Denmark" => "Denmark",
    "Djibouti" => "Djibouti",
    "Dominica" => "Dominica",
    "Dominican Republic" => "Dominican Republic",
    "East Timor" => "East Timor",
    "Ecuador" => "Ecuador",
    "Egypt" => "Egypt",
    "El Salvador" => "El Salvador",
    "Equatorial Guinea" => "Equatorial Guinea",
    "Eritrea" => "Eritrea",
    "Estonia" => "Estonia",
    "Ethiopia" => "Ethiopia",
    "Fiji" => "Fiji",
    "Finland" => "Finland",
    "France" => "France",
    "Gabon" => "Gabon",
    "Gambia" => "Gambia",
    "Georgia" => "Georgia",
    "Germany" => "Germany",
    "Ghana" => "Ghana",
    "Greece" => "Greece",
    "Grenada" => "Grenada",
    "Guatemala" => "Guatemala",
    "Guinea" => "Guinea",
    "Guinea-Bissau" => "Guinea-Bissau",
    "Guyana" => "Guyana",
    "Haiti" => "Haiti",
    "Honduras" => "Honduras",
    "Hungary" => "Hungary",
    "Iceland" => "Iceland",
    "India" => "India",
    "Indonesia" => "Indonesia",
    "Iran" => "Iran",
    "Iraq" => "Iraq",
    "Ireland {Republic}" => "Ireland {Republic}",
    "Israel" => "Israel",
    "Italy" => "Italy",
    "Ivory Coast" => "Ivory Coast",
    "Jamaica" => "Jamaica",
    "Japan" => "Japan",
    "Jordan" => "Jordan",
    "Kazakhstan" => "Kazakhstan",
    "Kenya" => "Kenya",
    "Kiribati" => "Kiribati",
    "Korea North" => "Korea North",
    "Korea South" => "Korea South",
    "Kosovo" => "Kosovo",
    "Kuwait" => "Kuwait",
    "Kyrgyzstan" => "Kyrgyzstan",
    "Laos" => "Laos",
    "Latvia" => "Latvia",
    "Lebanon" => "Lebanon",
    "Lesotho" => "Lesotho",
    "Liberia" => "Liberia",
    "Libya" => "Libya",
    "Liechtenstein" => "Liechtenstein",
    "Lithuania" => "Lithuania",
    "Luxembourg" => "Luxembourg",
    "Macedonia" => "Macedonia",
    "Madagascar" => "Madagascar",
    "Malawi" => "Malawi",
    "Malaysia" => "Malaysia",
    "Maldives" => "Maldives",
    "Mali" => "Mali",
    "Malta" => "Malta",
    "Marshall Islands" => "Marshall Islands",
    "Mauritania" => "Mauritania",
    "Mauritius" => "Mauritius",
    "Mexico" => "Mexico",
    "Micronesia" => "Micronesia",
    "Moldova" => "Moldova",
    "Monaco" => "Monaco",
    "Mongolia" => "Mongolia",
    "Montenegro" => "Montenegro",
    "Morocco" => "Morocco",
    "Mozambique" => "Mozambique",
    "Myanmar, {Burma}" => "Myanmar, {Burma}",
    "Namibia" => "Namibia",
    "Nauru" => "Nauru",
    "Nepal" => "Nepal",
    "Netherlands" => "Netherlands",
    "New Zealand" => "New Zealand",
    "Nicaragua" => "Nicaragua",
    "Niger" => "Niger",
    "Nigeria" => "Nigeria",
    "Norway" => "Norway",
    "Oman" => "Oman",
    "Pakistan" => "Pakistan",
    "Palau" => "Palau",
    "Panama" => "Panama",
    "Papua New Guinea" => "Papua New Guinea",
    "Paraguay" => "Paraguay",
    "Peru" => "Peru",
    "Philippines" => "Philippines",
    "Poland" => "Poland",
    "Portugal" => "Portugal",
    "Qatar" => "Qatar",
    "Romania" => "Romania",
    "Russian Federation" => "Russian Federation",
    "Rwanda" => "Rwanda",
    "St Kitts & Nevis" => "St Kitts & Nevis",
    "St Lucia" => "St Lucia",
    "Saint Vincent & the Grenadines" => "Saint Vincent & the Grenadines",
    "Samoa" => "Samoa",
    "San Marino" => "San Marino",
    "Sao Tome & Principe" => "Sao Tome & Principe",
    "Saudi Arabia" => "Saudi Arabia",
    "Senegal" => "Senegal",
    "Serbia" => "Serbia",
    "Seychelles" => "Seychelles",
    "Sierra Leone" => "Sierra Leone",
    "Singapore" => "Singapore",
    "Slovakia" => "Slovakia",
    "Slovenia" => "Slovenia",
    "Solomon Islands" => "Solomon Islands",
    "Somalia" => "Somalia",
    "South Africa" => "South Africa",
    "South Sudan" => "South Sudan",
    "Spain" => "Spain",
    "Sri Lanka" => "Sri Lanka",
    "Sudan" => "Sudan",
    "Suriname" => "Suriname",
    "Swaziland" => "Swaziland",
    "Sweden" => "Sweden",
    "Switzerland" => "Switzerland",
    "Syria" => "Syria",
    "Taiwan" => "Taiwan",
    "Tajikistan" => "Tajikistan",
    "Tanzania" => "Tanzania",
    "Thailand" => "Thailand",
    "Togo" => "Togo",
    "Tonga" => "Tonga",
    "Trinidad & Tobago" => "Trinidad & Tobago",
    "Tunisia" => "Tunisia",
    "Turkey" => "Turkey",
    "Turkmenistan" => "Turkmenistan",
    "Tuvalu" => "Tuvalu",
    "Uganda" => "Uganda",
    "Ukraine" => "Ukraine",
    "United Arab Emirates" => "United Arab Emirates",
    "United Kingdom" => "United Kingdom",
    "United States" => "United States",
    "Uruguay" => "Uruguay",
    "Uzbekistan" => "Uzbekistan",
    "Vanuatu" => "Vanuatu",
    "Vatican City" => "Vatican City",
    "Venezuela" => "Venezuela",
    "Vietnam" => "Vietnam",
    "Yemen" => "Yemen",
    "Zambia" => "Zambia",
    "Zimbabwe" => "Zimbabwe"
    ];

}

function settings($key = null)
{
    $settings = app('SystemSettings');

    return $key ? $settings->get($key) : $settings;
}

function getConfiguredPublicHolidays()
{
    $holidays = settings()->get('default_public_holidays');

    return $holidays ? explode(',', $holidays) : [ ];
}


/** Return an HMTL formatted link to the provided users's profile page.
 * @param $user
 * @param bool $span
 * @return string
 */
function profileLink($user, $span = true)
{
    if ($span) {
        return '<a href="' . route('profiles.show', $user->id) . '"><span class="text-info">' . $user->name() . '</span></a>';
    }

    return '<a href="' . route('profiles.show', $user->id) . '">' . $user->name() . '</a>';
}

/**
 * Returns a list of users formatted for use in a select field.
 * Optionally sets the first element to a blank/null value.
 *
 * @param $users
 * @param bool $emptyFirstElement
 * @return array
 */
function userList($users, $emptyFirstElement = false)
{
    $userList = [];
    foreach ($users as $user) {
        $userList[$user->id] = $user->name();
    }

    if ($emptyFirstElement)
        return [null => $emptyFirstElement] + $userList;

    return $userList;

}

if (!function_exists('column_names')) {
    /**
     * @param  string $table
     * @param  string $connectionName Database connection name
     *
     * @return array
     */
    function column_names($table, $connectionName = null)
    {
        $schema = \DB::connection($connectionName)->getDoctrineSchemaManager();

        return array_map(function ($var) {
            return str_replace('"', '', $var); // PostgreSQL need this replacement
        }, array_keys($schema->listTableColumns($table)));
    }
}

function parseMultilineStringIntoArray($string)
{
    $lines = explode("\r\n", $string);
    $lines = array_map('trim',$lines);

    $array = [];
    foreach ($lines as $line) {
        $lineIndexes = explode(',',$line);
        foreach ($lineIndexes as $index)
        {
            // strip any remaining punctuation
            $index = preg_replace('/^\PL+|\PL\z/', '', $index);
            if(!empty(trim($index))) {
                $array[] = trim($index);
            }
        }
    }

    return $array;
}

function siteOwner()
{
    return Company::findOrFail(settings('site_owner'));
}

function siteOwnerId()
{
    return (int)settings('site_owner');
}

function isSiteOwner($user)
{
    return $user->company_id === (int)settings('site_owner');
}

function isNotSiteOwner($user)
{
    return ! isSiteOwner($user);
}

function fullName($first, $last)
{
    if ($last && strlen($last) > 0) {
        return $first . ' ' . $last;
    }

    return $first;
}
function getViewVariables()
{
    $vars = [];
    $vars['currentUser'] = $user = Sentry::getUser();
    $vars['company'] = $user->company;
    $vars['layoutDirectory'] = $layoutDirectory = 'layouts.' . Config::get('view.layout.default');
    $vars['layout'] = $layoutDirectory . '.layout';

    return $vars;
}

function orderArrayBy($data, $field)
{
    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
    usort($data, create_function('$a,$b', $code));
    return $data;
}