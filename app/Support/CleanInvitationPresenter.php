<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class CleanInvitationPresenter
{
    public static function prepare(
        array $data = [],
        string $templateKey = 'clean',
        string $eventType = 'wedding'
    ): array {
        $dflt = fn(string $key) => TemplateRegistry::defaultUrl(
            TemplateRegistry::CLEAN_DEFAULT_IMAGES[$key] ?? $key
        );

        $defaults = [
            'groom' => [
                'name' => 'កូន កំឡោះ',
                'parents' => "ពូ ចាន់\nមីង សុភា",
                'photo' => $dflt('groom.photo'),
            ],
            'bride' => [
                'name' => 'កូនក្រមុំ',
                'parents' => "ពូ វិបុល\nមីង ដាវី",
                'photo' => $dflt('bride.photo'),
            ],
            'date' => '2026-12-20T08:00:00+07:00',
            'dateText' => 'ថ្ងៃអាទិត្យ ទី២០ ខែធ្នូ ឆ្នាំ២០២៦',
            'location' => 'វេលាម៉ោង ៥:០០ ព្រឹក នៅគេហដ្ឋានខាងស្រី ភូមិ..., ឃុំ..., ស្រុក..., ខេត្ត...',
            'intro' => 'សូមគោរពអញ្ជើញ ចូលរួមជាភ្ញៀវកិត្តិយស ក្នុងពិធីអាពាហ៍ពិពាហ៍របស់យើងខ្ញុំ',
            'quote' => 'ស្នេហាពិតមិនមានទីបញ្ចប់ឡើយ',
            'cover' => $dflt('cover'),

            'events' => [
                [
                    'time' => '07:00',
                    'title' => 'ពិធីហែជំនូន',
                    'place' => 'គេហដ្ឋានខាងកូនប្រុស',
                    'mapUrl' => 'https://maps.google.com',
                ],
                [
                    'time' => '11:00',
                    'title' => 'ពិធីមង្គលការ',
                    'place' => 'សណ្ឋាគារ ភ្នំពេញ',
                    'mapUrl' => 'https://maps.google.com',
                ],
                [
                    'time' => '18:00',
                    'title' => 'ពិធីជប់លៀង',
                    'place' => 'មជ្ឈមណ្ឌលសន្និបាត',
                    'mapUrl' => 'https://maps.google.com',
                ],
            ],

            'gallery' => array_map(
                $dflt,
                TemplateRegistry::DEFAULT_GALLERY
            ),

            'story' => [
                [
                    'year' => '២០១៩',
                    'title' => 'ជួបគ្នាលើកដំបូង',
                    'text' => 'យើងបានជួបគ្នាជាលើកដំបូងនៅសាកលវិទ្យាល័យ ហើយមិត្តភាពដ៏ស្រស់ស្អាតក៏ចាប់ផ្តើម។',
                ],
                [
                    'year' => '២០២២',
                    'title' => 'ក្លាយជាគូស្នេហ៍',
                    'text' => 'ពីមិត្តភាព ក្លាយជាស្នេហា ដែលពោរពេញដោយសេចក្តីសុខ និងស្នាមញញឹម។',
                ],
                [
                    'year' => '២០២៥',
                    'title' => 'ពាក្យសន្យា',
                    'text' => 'ក្នុងរាត្រីដ៏ស្ងប់ស្ងាត់មួយ គាត់បានសុំនាងរៀបការ ហើយនាងក៏ឆ្លើយថា យល់ព្រម។',
                ],
                [
                    'year' => '២០២៦',
                    'title' => 'ថ្ងៃមង្គលការ',
                    'text' => 'ហើយឥឡូវនេះ យើងសូមអញ្ជើញអ្នកមកចូលរួមសាក្សីស្នេហារបស់យើង។',
                ],
            ],

            'dressCode' => [
                'title' => 'ឈុតសម្លៀកបំពាក់',
                'note' => 'សូមពាក់ពណ៌ទន់ភ្លន់ ឬសម្លៀកបំពាក់ប្រពៃណីខ្មែរ',
                'colors' => ['#7a1f2b', '#e6b84a', '#fbf6ea', '#2f855a', '#d6336c'],
            ],

            'gift' => [
                'bank' => 'ABA Bank',
                'accountName' => 'SOKHA & SREY NEANG',
                'accountNumber' => '000 123 456',
                'qr' => $dflt('gift.qr'),
            ],

            'apology' => 'ប្រសិនបើយើងខ្ញុំមិនបានអញ្ជើញដល់ផ្ទាល់ ឬមានកំហុសឆ្គងអ្វីមួយ សូមលោក លោកស្រី អភ័យទោសដោយក្តីមេត្តា។ ការមកចូលរួម និងការយោគយល់របស់អ្នក គឺមានតម្លៃបំផុតសម្រាប់យើងខ្ញុំ។',

            'thanks' => 'ការអញ្ជើញចូលរួមរបស់អ្នក គឺជាកិត្តិយស និងសេចក្តីសុខដ៏ធំបំផុតសម្រាប់យើងខ្ញុំ។ សូមអរគុណយ៉ាងជ្រាលជ្រៅ!',

            'music' => '',
            'backgrounds' => [],
        ];

        $isEmpty = fn($v) => $v === null || $v === '' || $v === [];

        $merge = function (array $def, array $in) use (&$merge, $isEmpty) {
            $out = [];

            foreach ($def as $k => $dv) {
                $iv = $in[$k] ?? null;

                if ($isEmpty($iv)) {
                    $out[$k] = $dv;
                } elseif (is_array($dv) && $dv !== [] && !array_is_list($dv) && is_array($iv)) {
                    $out[$k] = $merge($dv, $iv);
                } else {
                    $out[$k] = $iv;
                }
            }

            foreach ($in as $k => $v) {
                if (!array_key_exists($k, $out)) {
                    $out[$k] = $v;
                }
            }

            return $out;
        };

        $raw = is_array($data) ? $data : [];
        $w = $merge($defaults, $raw);

        if (is_string($w['cover'] ?? null) && preg_match('#(^|/)default/cover\.jpg$#i', $w['cover'])) {
            $w['cover'] = $defaults['cover'];
        }

        $img = function ($p) {
            if (!is_string($p) || $p === '') {
                return '';
            }

            if (preg_match('#^(https?:)?//|^data:|^/#i', $p)) {
                return $p; // full URL, /storage/... or /assets/... (default images)
            }

            if (str_starts_with($p, 'storage/') || str_starts_with($p, 'assets/')) {
                return asset($p);
            }

            return asset('storage/' . ltrim($p, '/')); // bare path from the "public" disk
        };

        $safeLink = fn($u) => is_string($u) && preg_match('#^https?://#i', $u) ? $u : null;

        $groom = $w['groom'];
        $bride = $w['bride'];

        $gallery = array_values(array_filter(array_map($img, (array) $w['gallery'])));
        $events = array_values((array) $w['events']);
        $story = array_values((array) $w['story']);

        $colors = array_values(
            array_filter(
                (array) ($w['dressCode']['colors'] ?? []),
                fn($c) => is_string($c) && preg_match('/^#[0-9a-fA-F]{3,8}$/', $c),
            ),
        );

        $gift = $w['gift'];
        $giftQr = $img($gift['qr'] ?? '') ?: $dflt('gift.qr');

        $music = $img($w['music'] ?? '');

        $own = [];

        foreach ((array) $w['backgrounds'] as $k => $v) {
            if (is_string($v) && $v !== '') {
                $own[$k] = $img($v);
            }
        }

        $library = class_exists(\App\Support\InvitationBackgrounds::class)
            ? \App\Support\InvitationBackgrounds::library($templateKey, $eventType)
            : [];

        $bg = function (string $k) use ($own, $library, $img, $dflt) {
            foreach ([$own[$k] ?? null, $own['all'] ?? null, $library[$k] ?? null, $library['all'] ?? null] as $c) {
                if (is_string($c) && $c !== '') {
                    return $img($c);
                }
            }

            return file_exists(public_path("images/bg/gold/$k.jpg"))
                ? asset("images/bg/gold/$k.jpg")
                : $dflt('backgrounds.all');
        };

        // Hero: card bg -> "all" -> library -> cover -> default file
        $heroBg =
            ($own['card'] ?? '') ?: ($own['all'] ?? '') ?:
            $img($library['card'] ?? '') ?:
            $img($library['all'] ?? '') ?:
            $img($w['cover']) ?: (is_file(public_path("images/{$templateKey}/bg.jpg"))
                ? asset("images/{$templateKey}/bg.jpg")
                : $dflt('backgrounds.all'));

        // Parents: split on new lines or commas (not inside brackets)
        $parents = fn($v) => array_values(
            array_filter(array_map('trim', preg_split('/\r?\n|,(?![^()]*\))/u', (string) $v))),
        );

        // Khmer UI text (edit here)
        $K = [
            'groom' => 'កូនកំលោះ',
            'bride' => 'កូនក្រមុំ',
            'parentsGroom' => 'ឪពុកម្តាយខាងកូនកំលោះ',
            'parentsBride' => 'ឪពុកម្តាយខាងកូនក្រមុំ',
            'invite' => 'សូមអញ្ជើញចូលរួមជាភ្ញៀវកិត្តិយស',
            'date' => 'ថ្ងៃមង្គលការ',
            'countdown' => ['ថ្ងៃ', 'ម៉ោង', 'នាទី', 'វិនាទី'],
            'addCal' => 'បន្ថែមទៅប្រតិទិន',
            'story' => 'រឿងស្នេហារបស់យើង',
            'schedule' => 'កម្មវិធី',
            'gallery' => 'កម្រងរូបភាព',
            'rsvp' => 'ការឆ្លើយតប',
            'rsvpSub' => 'សូមជួយបញ្ជាក់ការចូលរួមរបស់អ្នក',
            'yourName' => 'ឈ្មោះរបស់អ្នក',
            'attend' => 'ខ្ញុំនឹងចូលរួម',
            'decline' => 'ខ្ញុំមិនអាចចូលរួមបាន',
            'guests' => 'ចំនួនភ្ញៀវ',
            'wish' => 'សារជូនពរ',
            'send' => 'ផ្ញើ',
            'sent' => 'អរគុណ! យើងបានទទួលការឆ្លើយតបរបស់អ្នកហើយ',
            'required' => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក',
            'failed' => 'ផ្ញើមិនបានសម្រេច សូមព្យាយាមម្តងទៀត',
            'wishes' => 'ពាក្យជូនពរ',
            'noWishes' => 'មិនទាន់មានពាក្យជូនពរនៅឡើយ',
            'more' => 'មើលបន្ថែម',
            'less' => 'បង្ហាញតិចជាងនេះ',
            'gift' => 'អំណោយ',
            'giftSub' => 'ការចូលរួមរបស់អ្នកគឺជាកាដូដ៏ធំបំផុតសម្រាប់យើង ប៉ុន្តែបើអ្នកចង់ផ្តល់អំណោយ សូមស្កេនខាងក្រោម',
            'scan' => 'ស្កេនដើម្បីផ្ញើកាដូ',
            'accName' => 'ឈ្មោះគណនី',
            'accNo' => 'លេខគណនី',
            'copy' => 'ចម្លង',
            'copied' => 'បានចម្លង',
            'apology' => 'សូមអភ័យទោស',
            'thanks' => 'អរគុណ',
            'share' => 'ចែករំលែក',
            'linkCopied' => 'បានចម្លងតំណ',
            'map' => 'ផែនទី',
            'close' => 'បិទ',
            'prev' => 'មុន',
            'next' => 'បន្ទាប់',
            'wedding' => 'មង្គលការ',
            'celebrate' => 'សិរីសួស្តីអាពាហ៍ពិពាហ៍',
            'scroll' => 'អូសចុះក្រោម',
            'coverTitle' => 'សិរីមង្គលអាពាហ៍ពិពាហ៍',
            'coverInvite' => 'សូមគោរពអញ្ជើញ',
            'coverSub' => 'សូមអញ្ជើញចូលរួមជាសាក្សីក្នុងថ្ងៃដ៏មានអត្ថន័យ',
            'coverTo' => 'គោរពអញ្ជើញ',
            'coverDear' => 'លោកអ្នកជាទីគោរព',
            'coverGuest' => 'ភ្ញៀវកិត្តិយស',
            'coverOpen' => 'បើកលិខិតអញ្ជើញ',
            'coverBye' => 'ដោយក្តីគោរព និងសេចក្តីស្រឡាញ់',
            'and' => 'និង',
            'countdowns' => 'ថ្ងៃកម្មវីធី',
        ];

        /* ---------- small SVG helpers ---------- */
        $GOLD = '#e3bd5a';

        // Lotus / mandala: $petals outline petals + half as many filled ones
        $lotus = function (int $petals, int $size, string $cls, float $sw = 1.2) use ($GOLD) {
            $o =
                '<svg aria-hidden="true" viewBox="-50 -50 100 100" width="' .
                $size .
                '" height="' .
                $size .
                '" class="' .
                $cls .
                '">';

            for ($i = 0; $i < $petals; $i++) {
                $o .=
                    '<path d="M0 -8 C6 -20 6 -34 0 -44 C-6 -34 -6 -20 0 -8 Z" fill="none" stroke="' .
                    $GOLD .
                    '" stroke-width="' .
                    $sw .
                    '" transform="rotate(' .
                    $i * (360 / $petals) .
                    ')"/>';
            }

            for ($i = 0; $i < intdiv($petals, 2); $i++) {
                $o .=
                    '<path d="M0 -6 C4 -14 4 -22 0 -28 C-4 -22 -4 -14 0 -6 Z" fill="' .
                    $GOLD .
                    '" opacity=".6" transform="rotate(' .
                    ($i * (720 / $petals) + 360 / $petals / 2) .
                    ')"/>';
            }

            return $o . '<circle r="4" fill="' . $GOLD . '"/></svg>';
        };

        $ic = function (string $name, int $s = 16) {
            $p =
                [
                    'heart' =>
                    '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',

                    'gift' =>
                    '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',

                    'sparkles' =>
                    '<path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/>',

                    'star' => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8-6.2-3.3-6.2 3.3L8 14.2 3 9.3l6.9-1Z"/>',

                    'pin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',

                    'cal' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',

                    'calplus' =>
                    '<path d="M8 2v4M16 2v4M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8M3 10h18M16 19h6M19 16v6"/>',

                    'check' => '<path d="M20 6 9 17l-5-5"/>',

                    'copy' =>
                    '<rect x="8" y="8" width="14" height="14" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>',

                    'send' => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',

                    'share' =>
                    '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/>',

                    'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',

                    'x' => '<path d="M18 6 6 18M6 6l12 12"/>',

                    'left' => '<path d="m15 18-6-6 6-6"/>',

                    'right' => '<path d="m9 18 6-6-6-6"/>',

                    'down' => '<path d="M6 9l6 6 6-6"/>',

                    'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
                ][$name] ?? '';

            return '<svg aria-hidden="true" width="' .
                $s .
                '" height="' .
                $s .
                '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' .
                $p .
                '</svg>';
        };

        $title = function (string $text, string $tone = 'light') use ($lotus) {
            return new HtmlString(
                '<div class="st-wrap rv-title st-' .
                    $tone .
                    '"><h2 class="st-title font-display">' .
                    e($text) .
                    '</h2>' .
                    '<div class="st-orn" aria-hidden="true"><span></span>' .
                    $lotus(12, 40, 'g-spin', 1.4) .
                    '<span></span></div></div>',
            );
        };

        $corner = function (string $pos, string $cls, int $size = 46) use ($GOLD) {
            $rot = ['tl' => 0, 'tr' => 90, 'br' => 180, 'bl' => 270][$pos];

            return new HtmlString(
                '<svg aria-hidden="true" viewBox="0 0 60 60" width="' .
                    $size .
                    '" height="' .
                    $size .
                    '" class="g-corner ' .
                    $cls .
                    ' ' .
                    $cls .
                    '-' .
                    $pos .
                    '" style="transform:rotate(' .
                    $rot .
                    'deg)">' .
                    '<path d="M3 57 V10 C3 5 5 3 10 3 H57" fill="none" stroke="' .
                    $GOLD .
                    '" stroke-width="1.6"/>' .
                    '<path d="M10 40 C10 26 16 20 30 20 C22 26 22 34 28 40 C20 42 14 42 10 40 Z" fill="' .
                    $GOLD .
                    '" opacity=".75"/>' .
                    '<path d="M12 12 C16 16 16 22 12 26 C8 22 8 16 12 12 Z" fill="none" stroke="' .
                    $GOLD .
                    '" stroke-width="1.2"/>' .
                    '<circle cx="8" cy="8" r="2.2" fill="' .
                    $GOLD .
                    '"/></svg>',
            );
        };

        // Kbach-style curl strip
        $kbach = function (string $id, bool $flip = false) use ($GOLD) {
            return new HtmlString(
                '<svg aria-hidden="true" class="kc-kanok" width="100%" height="30"' .
                    ($flip ? ' style="transform:scaleY(-1)"' : '') .
                    '><defs>' .
                    '<pattern id="' .
                    $id .
                    '" width="30" height="30" patternUnits="userSpaceOnUse">' .
                    '<path d="M15 27 C6 25 4 15 10 8 C10 13 13 15 16 13 C15 8 18 4 22 3 C21 9 25 12 24 18 C23 23 20 26 15 27 Z" fill="none" stroke="' .
                    $GOLD .
                    '" stroke-width="1.2"/>' .
                    '<path d="M15 22 C11 20 11 15 14 12 C14 15 16 16 18 15 C19 18 18 21 15 22 Z" fill="' .
                    $GOLD .
                    '" opacity=".6"/></pattern></defs>' .
                    '<rect width="100%" height="30" fill="url(#' .
                    $id .
                    ')"/></svg>',
            );
        };

        // Small lotus (hero emblem / thanks bloom base)
        $bloom = function (string $cls) use ($GOLD) {
            $o =
                '<svg aria-hidden="true" viewBox="-60 -60 120 90" class="' .
                $cls .
                '" fill="none" stroke="currentColor" stroke-width="1.6">';

            foreach ([[-62, 0.0], [-34, 0.12], [0, 0.24], [34, 0.12], [62, 0.0]] as $i => [$rot, $d]) {
                $o .=
                    '<path class="g-petal" style="--i:' .
                    $i .
                    '" d="M0 20 C-16 4 -14 -24 0 -46 C14 -24 16 4 0 20 Z" transform="rotate(' .
                    $rot .
                    ' 0 20)"/>';
            }

            return new HtmlString($o . '<path d="M-30 24 H30" stroke-linecap="round"/></svg>');
        };

        // Section opener / closer: $op = [bg-url, overlay, tone]
        $edge = fn(string $pos, string $color) => new HtmlString(
            '<div class="g-edge ' . $pos . '" style="--ec:' . e($color) . '" aria-hidden="true"></div>',
        );

        $overlay = function (float $o) {
            $strong = min(1, $o + 0.1);
            $soft = max(0, $o - 0.15);

            return 'background:linear-gradient(to bottom,rgb(22 18 12/' .
                $strong .
                '),rgb(22 18 12/' .
                $soft .
                ') 45%,rgb(22 18 12/' .
                $strong .
                '))';
        };

        $icons = ['gift', 'heart', 'star', 'sparkles'];

        $icsTitle = $groom['name'] . ' ' . $K['and'] . ' ' . $bride['name'];

        $icsEvents = array_map(function ($e) {
            return [
                'time' => $e['time'] ?? '',
                'title' => $e['title'] ?? '',
                'place' => $e['place'] ?? '',
            ];
        }, $events);

        return compact(
            'w',
            'img',
            'safeLink',
            'groom',
            'bride',
            'gallery',
            'events',
            'story',
            'colors',
            'gift',
            'giftQr',
            'music',
            'own',
            'library',
            'bg',
            'heroBg',
            'parents',
            'K',
            'GOLD',
            'lotus',
            'ic',
            'title',
            'corner',
            'kbach',
            'bloom',
            'edge',
            'overlay',
            'icons',
            'icsTitle',
            'icsEvents'
        );
    }
}
