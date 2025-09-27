<?php

namespace App\Console\Commands;

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
                '$limit'  => $batchSize,
                '$offset' => $offset,
            ]);

            if (! $response->ok()) {
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
                        'legal_name'        => $r['legal_name'] ?? '',
                        'dba_name'          => $r['dba_name'] ?? null,
                        'business_org_desc' => $r['business_org_desc'] ?? null,
                        'status_code'       => $r['status_code'] ?? null,
                        'phone'             => $r['phone'] ?? null,
                        'fax'               => $r['fax'] ?? null,
                        'cell_phone'        => $r['cell_phone'] ?? null,
                        'email_address'     => $r['email_address'] ?? null,
                        'safety_rating'     => $r['safety_rating'] ?? null,
                        'safety_rating_date'=> $r['safety_rating_date'] ?? null,
                        'review_type'       => $r['review_type'] ?? null,
                        'review_date'       => $r['review_date'] ?? null,
                        'add_date'          => $r['add_date'] ?? null,
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
