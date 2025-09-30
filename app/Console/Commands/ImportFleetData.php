<?php

namespace App\Console\Commands;

use App\Models\Address;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Company;

class ImportFleetData extends Command
{
    protected $signature = 'fleet:import-all {--batch=1000}';
    protected $description = 'Import ALL fleet/company records from FMCSA dataset';

    protected string $apiUrl = 'https://data.transportation.gov/resource/az4n-8mr2.json';

    public function handle()
    {
        $batchSize = (int) $this->option('batch');
        $offset = 0;
        $totalImported = 0;

        $this->info("Starting full import with batch size = {$batchSize}");

        while (true) {
            $this->line("Fetching records: offset={$offset}, limit={$batchSize}");

            $response = Http::get($this->apiUrl, [
                '$limit' => $batchSize,
                '$offset' => $offset,
                'phy_state'=> 'PA',
            ]);

            if (!$response->ok()) {
                $this->error("API request failed with status {$response->status()}");
                break;
            }

            $records = $response->json();

            if (empty($records)) {
                $this->info("No more records found. Import finished.");
                break;
            }

            foreach ($records as $r) {
                $company = Company::updateOrCreate(
                    ['dot_number' => $r['dot_number'] ?? null],
                    [
                        'legal_name' => $r['legal_name'] ?? '',
                        'dba_name' => $r['dba_name'] ?? null,
                        'business_org_desc' => $r['business_org_desc'] ?? null,
                        'status_code' => $r['status_code'] ?? null,
                        'phone' => $r['phone'] ?? null,
                        'fax' => $r['fax'] ?? null,
                        'cell_phone' => $r['cell_phone'] ?? null,
                        'email_address' => $r['email_address'] ?? null,
                        'safety_rating' => $r['safety_rating'] ?? null,
                        'safety_rating_date' => $r['safety_rating_date'] ?? null,
                        'review_type' => $r['review_type'] ?? null,
                        'review_date' => $r['review_date'] ?? null,
                        'add_date' => $r['add_date'] ?? null,
                    ]
                );

                // Addresses
                Address::updateOrCreate(
                    ['company_id' => $company->id, 'type' => 'physical'],
                    [
                        'street' => $r['phy_street'] ?? null,
                        'city' => $r['phy_city'] ?? null,
                        'state' => $r['phy_state'] ?? null,
                        'zip' => $r['phy_zip'] ?? null,
                        'country' => $r['phy_country'] ?? null,
                        'county' => $r['phy_cnty'] ?? null,
                    ]
                );

                Address::updateOrCreate(
                    ['company_id' => $company->id, 'type' => 'mailing'],
                    [
                        'street' => $r['carrier_mailing_street'] ?? null,
                        'city' => $r['carrier_mailing_city'] ?? null,
                        'state' => $r['carrier_mailing_state'] ?? null,
                        'zip' => $r['carrier_mailing_zip'] ?? null,
                        'country' => $r['carrier_mailing_country'] ?? null,
                        'county' => $r['carrier_mailing_cnty'] ?? null,
                    ]
                );


            }

            $totalImported += count($records);
            $this->info("Imported so far: {$totalImported}");

            $offset += $batchSize;

        }

        $this->info("✅ Import complete. Total records imported: {$totalImported}");
    }
}
