<?php

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\NameDocument;
use Illuminate\Support\Facades\Cache;

if (!function_exists('func_str_find_url')) {
    function func_str_find_url($text){
        return preg_replace_callback(
            '/(https?:\/\/[^\s]+)/',
            function ($matches) {
                return '<a href="' . $matches[0] . '" target="_blank">' . $matches[0] . '</a>';
            },
            $text
        );
    }

}
if (!function_exists('func_str_to_upper_utf8')) {
    function func_str_to_upper_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtoupper($text, 'utf-8');
    }
}

if (!function_exists('func_str_to_lower_utf8')) {
    function func_str_to_lower_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtolower($text, 'utf-8');
    }
}

if (!function_exists('func_filter_items')) {
    function func_filter_items($query, $text)
    {
        $text_array = explode(' ', $text);
        foreach ($text_array as $txt) {
            $trim_txt = trim($txt);
            $query->where('text_filter', 'like', "%$trim_txt%");
        }

        return $query;
    }
}
if (!function_exists('get_document_name')) {
    function get_document_name($document, $default)
    {
        $name_document = NameDocument::first();
        if (isset($name_document->{$document})) {
            if (empty($name_document->{$document})) {
                return $default;
            }
            return mb_strtoupper($name_document->{$document});
        } else {
            return $default;
        }
    }
}
if (!function_exists('symbol_or_code')) {
    function symbol_or_code($id)
    {

        $unit_type = UnitType::find($id);
        if ($unit_type) {
            if ($unit_type->show_symbol) {
                return $unit_type->symbol;
            }
            return $unit_type->id;
        }
        return $id;
    }
}
if (!function_exists('func_get_locations')) {
    function func_get_locations()
    {
        // if (Cache::has('locations')) {
        //     return Cache::get('locations');
        // }

        $locations = [];
        $departments = Department::query()
            ->with('provinces', 'provinces.districts')
            ->get();
        foreach ($departments as $department) {
            $children_provinces = [];
            foreach ($department->provinces as $province) {
                $children_districts = [];
                foreach ($province->districts as $district) {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => func_str_to_upper_utf8($district->id . " - " . $district->description)
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => func_str_to_upper_utf8($province->description),
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => func_str_to_upper_utf8($department->description),
                'children' => $children_provinces
            ];
        }

        // Cache::put('locations', $locations, 1440);

        return $locations;
    }
}

if (!function_exists('func_get_countries')) {
    function func_get_countries()
    {
        if (Cache::has('countries')) {
            return Cache::get('countries');
        }

        $countries = Country::query()
            ->get();

        Cache::put('countries', $countries, 1440);

        return $countries;
    }
}

if (!function_exists('func_get_operation_types')) {
    function func_get_operation_types()
    {
        if (Cache::has('operation_types')) {
            return Cache::get('operation_types');
        }

        $operation_types = OperationType::query()
            ->where('active', true)
            ->get();

        Cache::put('operation_types', $operation_types, 1440);

        return $operation_types;
    }
}

if (!function_exists('func_get_affectation_igv_types')) {
    function func_get_affectation_igv_types()
    {
        if (Cache::has('affectation_igv_types')) {
            return Cache::get('affectation_igv_types');
        }

        $affectation_igv_types = AffectationIgvType::query()
            ->where('active', true)
            ->get();

        Cache::put('affectation_igv_types', $affectation_igv_types, 1440);

        return $affectation_igv_types;
    }
}

if (!function_exists('func_get_identity_document_types')) {
    function func_get_identity_document_types()
    {
        if (Cache::has('identity_document_types')) {
            return Cache::get('identity_document_types');
        }

        $identity_document_types = IdentityDocumentType::query()
            ->where('active', true)
            ->get();

        Cache::put('identity_document_types', $identity_document_types, 1440);

        return $identity_document_types;
    }
}

if (!function_exists('func_get_currency_types')) {
    function func_get_currency_types()
    {
        if (Cache::has('currency_types')) {
            return Cache::get('currency_types');
        }

        $currency_types = CurrencyType::query()
            ->where('active', true)
            ->get();

        Cache::put('currency_types', $currency_types, 1440);

        return $currency_types;
    }
}

if (!function_exists('func_is_windows')) {
    function func_is_windows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
