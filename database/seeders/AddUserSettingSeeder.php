<?php

namespace Database\Seeders;

use App\Models\UserSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddUserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserSetting::create(
            [
                'company_ad_title'          => null,
                'company_ad_thumbnail'      => null,
                'company_ad_description'    => null,
                'company_ad_action_btn'     => null
            ]
        );
    }
}
