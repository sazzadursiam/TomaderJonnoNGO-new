<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Quick Give Tiers
    |--------------------------------------------------------------------------
    |
    | Suggested gift amounts (in BDT) shown in the Quick Give box, grouped by
    | frequency. The "impact" text is an English translation key; its Bangla
    | version lives in lang/bn.json.
    |
    */

    'minimum' => 50,

    'tiers' => [
        'monthly' => [
            ['amount' => 500, 'impact' => 'sponsors school supplies for a child.'],
            ['amount' => 1000, 'impact' => 'sponsors educational materials and nutritional support for an underprivileged child.'],
            ['amount' => 2500, 'impact' => 'funds a woman\'s vocational training seat for a month.'],
        ],
        'once' => [
            ['amount' => 1000, 'impact' => 'provides learning materials for three children.'],
            ['amount' => 2500, 'impact' => 'equips an artisan with tools and raw materials.'],
            ['amount' => 5000, 'impact' => 'supports a community therapy and inclusion camp.'],
        ],
    ],

];
