<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Lee Hayward Enterprises',
        'product' => 'Total Fitness Bodybuilding',
        'street' => 'P.O. Box 13175',
        'location' => 'Conception Bay South, Newfoundland A1W 2K1, Canada',
        'phone' => '1-877-892-7435',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = 'LeeHayward.com@gmail.com';

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'lee@LeeHayward.com'
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        Spark::noAdditionalTeams();

        Spark::useRoles([
            'member' => 'Member',
            'vip' => 'VIP'
        ]);

        Spark::useDefaultRole('member');

        Spark::useStripe()->needsCardUpFront();

        Spark::plan('Monthly', 'monthly-plan')
            ->price(20)
            ->features($this->subscriberFeatures());

        Spark::plan('Yearly', 'yearly-plan')
            ->price(120)
            ->yearly()
            ->features($this->subscriberFeatures());
    }

    protected function subscriberFeatures()
    {
        return [
            'New workouts every month',
            'Exclusive training videos',
            'Personal answers to your questions',
            'Bodybuilding meal plans',
            'Live video chat every week',
            '24/7 Inner circle community support'
        ];
    }
}
