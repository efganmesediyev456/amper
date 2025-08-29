<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $cities = [
            // Main cities
            ['az' => 'Bakı', 'en' => 'Baku', 'ru' => 'Баку', 'order' => 1],
            ['az' => 'Gəncə', 'en' => 'Ganja', 'ru' => 'Гянджа', 'order' => 2],
            ['az' => 'Sumqayıt', 'en' => 'Sumgait', 'ru' => 'Сумгаит', 'order' => 3],
            ['az' => 'Mingəçevir', 'en' => 'Mingachevir', 'ru' => 'Мингячевир', 'order' => 4],
            ['az' => 'Şirvan', 'en' => 'Shirvan', 'ru' => 'Ширван', 'order' => 5],
            ['az' => 'Şəki', 'en' => 'Sheki', 'ru' => 'Шеки', 'order' => 6],
            ['az' => 'Lənkəran', 'en' => 'Lankaran', 'ru' => 'Ленкорань', 'order' => 7],
            ['az' => 'Yevlax', 'en' => 'Yevlakh', 'ru' => 'Евлах', 'order' => 8],
            ['az' => 'Naftalan', 'en' => 'Naftalan', 'ru' => 'Нафталан', 'order' => 9],
            ['az' => 'Xankəndi', 'en' => 'Khankendi', 'ru' => 'Ханкенди', 'order' => 10],
            ['az' => 'Naxçıvan', 'en' => 'Nakhchivan', 'ru' => 'Нахчыван', 'order' => 11],
            
            // Districts/Regions
            ['az' => 'Abşeron', 'en' => 'Absheron', 'ru' => 'Апшерон', 'order' => 12],
            ['az' => 'Ağcabədi', 'en' => 'Agjabadi', 'ru' => 'Агджабеди', 'order' => 13],
            ['az' => 'Ağdam', 'en' => 'Agdam', 'ru' => 'Агдам', 'order' => 14],
            ['az' => 'Ağdaş', 'en' => 'Agdash', 'ru' => 'Агдаш', 'order' => 15],
            ['az' => 'Ağstafa', 'en' => 'Agstafa', 'ru' => 'Агстафа', 'order' => 16],
            ['az' => 'Ağsu', 'en' => 'Agsu', 'ru' => 'Агсу', 'order' => 17],
            ['az' => 'Astara', 'en' => 'Astara', 'ru' => 'Астара', 'order' => 18],
            ['az' => 'Balakən', 'en' => 'Balakan', 'ru' => 'Балакен', 'order' => 19],
            ['az' => 'Beyləqan', 'en' => 'Beylagan', 'ru' => 'Бейлаган', 'order' => 20],
            ['az' => 'Biləsuvar', 'en' => 'Bilasuvar', 'ru' => 'Билясувар', 'order' => 21],
            ['az' => 'Cəbrayıl', 'en' => 'Jabrayil', 'ru' => 'Джебраил', 'order' => 22],
            ['az' => 'Cəlilabad', 'en' => 'Jalilabad', 'ru' => 'Джалилабад', 'order' => 23],
            ['az' => 'Daşkəsən', 'en' => 'Dashkasan', 'ru' => 'Дашкесан', 'order' => 24],
            ['az' => 'Füzuli', 'en' => 'Fuzuli', 'ru' => 'Физули', 'order' => 25],
            ['az' => 'Gədəbəy', 'en' => 'Gadabay', 'ru' => 'Гедабек', 'order' => 26],
            ['az' => 'Goranboy', 'en' => 'Goranboy', 'ru' => 'Горанбой', 'order' => 27],
            ['az' => 'Göyçay', 'en' => 'Goychay', 'ru' => 'Гёйчай', 'order' => 28],
            ['az' => 'Göygöl', 'en' => 'Goygol', 'ru' => 'Гёйгёль', 'order' => 29],
            ['az' => 'Hacıqabul', 'en' => 'Hajigabul', 'ru' => 'Гаджигабул', 'order' => 30],
            ['az' => 'İmişli', 'en' => 'Imishli', 'ru' => 'Имишли', 'order' => 31],
            ['az' => 'İsmayıllı', 'en' => 'Ismayilli', 'ru' => 'Исмаиллы', 'order' => 32],
            ['az' => 'Kəlbəcər', 'en' => 'Kalbajar', 'ru' => 'Кельбаджар', 'order' => 33],
            ['az' => 'Kürdəmir', 'en' => 'Kurdamir', 'ru' => 'Кюрдамир', 'order' => 34],
            ['az' => 'Qax', 'en' => 'Gakh', 'ru' => 'Гах', 'order' => 35],
            ['az' => 'Qazax', 'en' => 'Gazakh', 'ru' => 'Газах', 'order' => 36],
            ['az' => 'Qəbələ', 'en' => 'Gabala', 'ru' => 'Габала', 'order' => 37],
            ['az' => 'Qobustan', 'en' => 'Gobustan', 'ru' => 'Гобустан', 'order' => 38],
            ['az' => 'Quba', 'en' => 'Guba', 'ru' => 'Губа', 'order' => 39],
            ['az' => 'Qubadlı', 'en' => 'Gubadli', 'ru' => 'Губадлы', 'order' => 40],
            ['az' => 'Qusar', 'en' => 'Gusar', 'ru' => 'Гусар', 'order' => 41],
            ['az' => 'Laçın', 'en' => 'Lachin', 'ru' => 'Лачин', 'order' => 42],
            ['az' => 'Lerik', 'en' => 'Lerik', 'ru' => 'Лерик', 'order' => 43],
            ['az' => 'Masallı', 'en' => 'Masalli', 'ru' => 'Масаллы', 'order' => 44],
            ['az' => 'Neftçala', 'en' => 'Neftchala', 'ru' => 'Нефтчала', 'order' => 45],
            ['az' => 'Oğuz', 'en' => 'Oguz', 'ru' => 'Огуз', 'order' => 46],
            ['az' => 'Saatlı', 'en' => 'Saatli', 'ru' => 'Саатлы', 'order' => 47],
            ['az' => 'Sabirabad', 'en' => 'Sabirabad', 'ru' => 'Сабирабад', 'order' => 48],
            ['az' => 'Şabran', 'en' => 'Shabran', 'ru' => 'Шабран', 'order' => 49],
            ['az' => 'Salyan', 'en' => 'Salyan', 'ru' => 'Сальян', 'order' => 50],
            ['az' => 'Şamaxı', 'en' => 'Shamakhi', 'ru' => 'Шемаха', 'order' => 51],
            ['az' => 'Samux', 'en' => 'Samukh', 'ru' => 'Самух', 'order' => 52],
            ['az' => 'Şəmkir', 'en' => 'Shamkir', 'ru' => 'Шамкир', 'order' => 53],
            ['az' => 'Siyəzən', 'en' => 'Siyazan', 'ru' => 'Сиазань', 'order' => 54],
            ['az' => 'Şuşa', 'en' => 'Shusha', 'ru' => 'Шуша', 'order' => 55],
            ['az' => 'Tərtər', 'en' => 'Tartar', 'ru' => 'Тертер', 'order' => 56],
            ['az' => 'Tovuz', 'en' => 'Tovuz', 'ru' => 'Товуз', 'order' => 57],
            ['az' => 'Ucar', 'en' => 'Ujar', 'ru' => 'Уджар', 'order' => 58],
            ['az' => 'Xaçmaz', 'en' => 'Khachmaz', 'ru' => 'Хачмаз', 'order' => 59],
            ['az' => 'Xızı', 'en' => 'Khizi', 'ru' => 'Хызы', 'order' => 60],
            ['az' => 'Xocalı', 'en' => 'Khojaly', 'ru' => 'Ходжалы', 'order' => 61],
            ['az' => 'Xocavənd', 'en' => 'Khojavend', 'ru' => 'Ходжавенд', 'order' => 62],
            ['az' => 'Yardımlı', 'en' => 'Yardimli', 'ru' => 'Ярдымлы', 'order' => 63],
            ['az' => 'Zaqatala', 'en' => 'Zagatala', 'ru' => 'Загатала', 'order' => 64],
            ['az' => 'Zəngilan', 'en' => 'Zangilan', 'ru' => 'Зангилан', 'order' => 65],
            ['az' => 'Zərdab', 'en' => 'Zardab', 'ru' => 'Зардаб', 'order' => 66],
        ];

        foreach ($cities as $index => $cityData) {
            $city = new City();
            $city->status = 1;
            $city->order = $cityData['order'];
            $city->save();
            
            // Save translations
            foreach (['az', 'en', 'ru'] as $lang) {
                DB::table('field_translations')->insert([
                    'model_id' => $city->id,
                    'model_type' => City::class,
                    'value' => $cityData[$lang],
                    'key' => 'title',
                    'locale' => $lang,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}