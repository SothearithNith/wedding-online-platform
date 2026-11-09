<?php

namespace App\Support;

class InvitationPresenter
{
    public static function prepare(
        array $input,
        string $template = 'gold',
        string $eventType = 'wedding'
    ): array {
        $data = self::merge(self::defaults(), $input);

        $image = fn ($path) => self::image($path);

        $groom = $data['groom'];
        $bride = $data['bride'];
        $gift = $data['gift'];

        $library = InvitationBackgrounds::library($template, $eventType);

        $own = [];
        foreach ((array) $data['backgrounds'] as $key => $value) {
            if (is_string($value) && trim($value) !== '') {
                $own[$key] = $image($value);
            }
        }

        $background = function (string $section) use ($own, $library, $image) {
            foreach ([
                $own[$section] ?? null,
                $own['all'] ?? null,
                $library[$section] ?? null,
                $library['all'] ?? null,
            ] as $candidate) {
                if (is_string($candidate) && $candidate !== '') {
                    return $candidate;
                }
            }

            $file = "images/bg/gold/{$section}.jpg";

            return is_file(public_path($file))
                ? asset($file)
                : TemplateRegistry::defaultUrl('background.jpg');
        };

        $heroBackground = self::firstImage([
            $own['card'] ?? null,
            $own['all'] ?? null,
            $image($library['card'] ?? ''),
            $image($library['all'] ?? ''),
            $image($data['cover'] ?? ''),
            asset('images/gold/bg.jpg'),
        ]);

        $colors = array_values(array_filter(
            (array) ($data['dressCode']['colors'] ?? []),
            fn ($color) => is_string($color)
                && preg_match('/^#[0-9a-fA-F]{3,8}$/', $color)
        ));

        return [
            'w' => $data,
            'groom' => $groom,
            'bride' => $bride,
            'gift' => $gift,

            'img' => $image,
            'gallery' => array_values(array_filter(
                array_map($image, (array) $data['gallery'])
            )),
            'events' => array_values((array) $data['events']),
            'story' => array_values((array) $data['story']),

            'giftQr' => $image($gift['qr'] ?? '')
                ?: TemplateRegistry::defaultUrl('qr.jpg'),

            'music' => $image($data['music'] ?? ''),
            'bg' => $background,
            'heroBg' => $heroBackground,
            'colors' => $colors,
            'parents' => fn ($value) => self::parents($value),

            'icsTitle' => $groom['name'] . ' ' . self::labels()['and'] . ' ' . $bride['name'],
            'icsEvents' => array_map(
                fn ($event) => [
                    'time' => $event['time'] ?? '',
                    'title' => $event['title'] ?? '',
                    'place' => $event['place'] ?? '',
                ],
                (array) $data['events']
            ),

            'K' => self::labels(),
            'GOLD' => '#e3bd5a',
            'icons' => ['gift', 'heart', 'star', 'sparkles'],
        ];
    }

    private static function defaults(): array
    {
        $d = fn (string $file) => TemplateRegistry::defaultUrl($file);

        return [
            'groom' => [
                'name' => 'ស៊ន់ សុវណ្ណ',
                'parents' => "ពូ ចាន់\nមីង សុភា",
                'photo' => $d('groom.jpg'),
            ],
            'bride' => [
                'name' => 'ភួង សិលា',
                'parents' => "ពូ វិបុល\nមីង ដាវី",
                'photo' => $d('bride.jpg'),
            ],
            'date' => '2026-12-20T08:00:00+07:00',
            'dateText' => 'ថ្ងៃអាទិត្យ ទី២០ ខែធ្នូ ឆ្នាំ២០២៦',
            'intro' => 'សូមគោរពអញ្ជើញ ចូលរួមជាភ្ញៀវកិត្តិយស ក្នុងពិធីអាពាហ៍ពិពាហ៍របស់យើងខ្ញុំ',
            'quote' => 'ស្នេហាពិតមិនមានទីបញ្ចប់ឡើយ',
            'cover' => $d('cover.jpg'),

            'events' => [
                ['time' => '07:00', 'title' => 'ពិធីហែជំនូន', 'place' => 'គេហដ្ឋានខាងកូនប្រុស', 'mapUrl' => 'https://maps.google.com'],
                ['time' => '11:00', 'title' => 'ពិធីមង្គលការ', 'place' => 'សណ្ឋាគារ ភ្នំពេញ', 'mapUrl' => 'https://maps.google.com'],
                ['time' => '18:00', 'title' => 'ពិធីជប់លៀង', 'place' => 'មជ្ឈមណ្ឌលសន្និបាត', 'mapUrl' => 'https://maps.google.com'],
            ],

            'gallery' => array_map($d, TemplateRegistry::DEFAULT_GALLERY),

            'story' => [
                ['year' => '២០១៩', 'title' => 'ជួបគ្នាលើកដំបូង', 'text' => 'យើងបានជួបគ្នាជាលើកដំបូងនៅសាកលវិទ្យាល័យ ហើយមិត្តភាពដ៏ស្រស់ស្អាតក៏ចាប់ផ្តើម។'],
                ['year' => '២០២២', 'title' => 'ក្លាយជាគូស្នេហ៍', 'text' => 'ពីមិត្តភាព ក្លាយជាស្នេហា ដែលពោរពេញដោយសេចក្តីសុខ និងស្នាមញញឹម។'],
                ['year' => '២០២៥', 'title' => 'ពាក្យសន្យា', 'text' => 'ក្នុងរាត្រីដ៏ស្ងប់ស្ងាត់មួយ គាត់បានសុំនាងរៀបការ ហើយនាងក៏ឆ្លើយថា យល់ព្រម។'],
                ['year' => '២០២៦', 'title' => 'ថ្ងៃមង្គលការ', 'text' => 'ហើយឥឡូវនេះ យើងសូមអញ្ជើញអ្នកមកចូលរួមសាក្សីស្នេហារបស់យើង។'],
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
                'qr' => $d('qr.jpg'),
            ],

            'apology' => 'ប្រសិនបើយើងខ្ញុំមិនបានអញ្ជើញដល់ផ្ទាល់ ឬមានកំហុសឆ្គងអ្វីមួយ សូមលោក លោកស្រី អភ័យទោសដោយក្តីមេត្តា។ ការមកចូលរួម និងការយោគយល់របស់អ្នក គឺមានតម្លៃបំផុតសម្រាប់យើងខ្ញុំ។',
            'thanks' => 'ការអញ្ជើញចូលរួមរបស់អ្នក គឺជាកិត្តិយស និងសេចក្តីសុខដ៏ធំបំផុតសម្រាប់យើងខ្ញុំ។ សូមអរគុណយ៉ាងជ្រាលជ្រៅ!',
            'music' => '',
            'backgrounds' => [],
        ];
    }

    private static function merge(array $defaults, array $input): array
    {
        $output = $defaults;

        foreach ($input as $key => $value) {
            if (! array_key_exists($key, $defaults)) {
                $output[$key] = $value;
                continue;
            }

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $default = $defaults[$key];

            if (
                is_array($default)
                && ! array_is_list($default)
                && is_array($value)
            ) {
                $output[$key] = self::merge($default, $value);
            } else {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    private static function image($path): string
    {
        if (! is_string($path) || trim($path) === '') {
            return '';
        }

        $path = trim($path);

        if (str_starts_with($path, 'data:')) {
            return '';
        }

        if (preg_match('#^(https?://|//|/)#i', $path)) {
            return $path;
        }

        if (
            str_starts_with($path, 'storage/')
            || str_starts_with($path, 'assets/')
        ) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private static function firstImage(array $candidates): string
    {
        foreach ($candidates as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                return $candidate;
            }
        }

        return '';
    }

    private static function parents($value): array
    {
        return array_values(array_filter(
            array_map(
                'trim',
                preg_split('/\r\n|\r|\n|,(?![^()]*\))/u', (string) $value)
            ),
            fn ($item) => $item !== ''
        ));
    }

    private static function labels(): array
    {
        return [
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
            'celebrate' => 'ពិធីអបអរសាទរនៃក្តីស្រឡាញ់',
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
        ];
    }
}
