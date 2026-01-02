<?php

declare(strict_types=1);

namespace App\Validation;

final readonly class RegexValidations
{
    public const string REGEX_GENERIC_STRING = '^\S(?:.*\S)?$';

    public const string REGEX_LETTERS_ONLY = '^[a-zA-Z]';

    public const string REGEX_LETTER_AND_DIGITS = '^[a-zA-Z\d]';

    public const string REGEX_NUMERIC = '\d+';

    public const string REGEX_NUMERIC_ONLY = '^\d+$';

    // phpcs:ignore
    public const string REGEX_EMAIL = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

    public const string REGEX_ZIPCODE = '^\d{4}[A-Z]{2}$';

    public const string REGEX_UUID = '^[a-fA-F\d]{8}(?:\-[a-fA-F\d]{4}){3}\-[a-fA-F\d]{12}$';

    // phpcs:ignore
    public const string REGEX_DATE = '^((\d\d[2468][048]|\d\d[13579][26]|\d\d0[48]|[02468][048]00|[13579][26]00)-02-29|\d{4}-((0[13578]|1[02])-(0[1-9]|[12]\d|3[01])|(0[469]|11)-(0[1-9]|[12]\d|30)|(02)-(0[1-9]|1\d|2[0-8])))$';

    // phpcs:ignore
    public const string REGEX_DATETIME = '^((\d\d[2468][048]|\d\d[13579][26]|\d\d0[48]|[02468][048]00|[13579][26]00)-02-29|\d{4}-((0[13578]|1[02])-(0[1-9]|[12]\d|3[01])|(0[469]|11)-(0[1-9]|[12]\d|30)|(02)-(0[1-9]|1\d|2[0-8])))T\d{2}:\d{2}:\d{2}.\d{3}Z$';

    public const string REGEX_IBAN = '^NL[0-9]{2}[A-z0-9]{4}[0-9]{10}$';

    // phpcs:ignore
    public const string REGEX_URL = '^https?(?:\:\/\/)?[\w-]+(?:\.[\w-]+)+(?:[\w.,@?^!=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])+$';

    // phpcs:ignore
    public const string REGEX_IRI = '^\/api\/([a-zA-Z0-9_\.~-]+)\/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})$';



    public const string GENERIC_STRING = '/' . self::REGEX_GENERIC_STRING . '/';

    public const string LETTERS_ONLY = '/' . self::REGEX_LETTERS_ONLY . '/';

    public const string LETTER_AND_DIGITS = '/' . self::REGEX_LETTER_AND_DIGITS . '/';

    public const string NUMERIC = '/' . self::REGEX_NUMERIC . '/';

    public const string NUMERIC_ONLY = '/' . self::REGEX_NUMERIC_ONLY . '/';

    public const string EMAIL = '/' . self::REGEX_EMAIL . '/';

    public const string ZIPCODE = '/' . self::REGEX_ZIPCODE . '/';

    public const string UUID = '/' . self::REGEX_UUID . '/';

    public const string DATE = '/' . self::REGEX_DATE . '/';

    public const string DATETIME = '/' . self::REGEX_DATETIME . '/';

    public const string IBAN = '/' . self::REGEX_IBAN . '/';

    public const string URL = '/' . self::REGEX_URL . '/';

    public const string IRI = '/' . self::REGEX_IRI . '/';
}
