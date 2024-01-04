<?php

use App\Models\Country;
use App\Models\Distribution;
use App\Models\DistributionCompany;
use App\Models\Film;
use App\Services\KinopoiskApiUnofficial\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $films = Film::get();
    $client = new Client();

    foreach ($films as $film) {
        $distributions = $client->getDistributions($film->id);

        $film->distributions()->delete();
        foreach ($distributions->items as $distribution_data) {
            /**
             * @var Distribution $distribution
             */
            $distribution = Distribution::make([
                'type' => $distribution_data->type,
                'sub_type' => $distribution_data->subType,
                'date' => $distribution_data->date,
                're_release' => $distribution_data->reRelease,
            ]);
            $distribution->film()->associate($film->id);

            if ($distribution_data->country) {
                $country = Country::where('name', $distribution_data->country->country)->first();

                if (!$country) {
                    $country = Country::create(['name' => $distribution_data->country->country]);
                }

                $distribution->country()->associate($country->id);
            }

            $distribution->save();

            $company_ids = [];
            foreach ($distribution_data->companies as $company_data) {
                $company = DistributionCompany::where('name', $company_data->name)->first();

                if (!$company) {
                    $company = DistributionCompany::create(['name' => $company_data->name]);
                }

                $company_ids[] = $company->id;
            }
            $distribution->distributionCompanies()->sync($company_ids);
        }
        dump($film->getName());
    }
})->purpose('Display an inspiring quote');
