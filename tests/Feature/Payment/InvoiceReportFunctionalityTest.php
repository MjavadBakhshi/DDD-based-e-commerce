<?php

namespace Tests\Feature\Product;

use Domain\Payment\Enums\InvoiceStatus;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Domain\Payment\Models\Invoice;

class InvoiceReportFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    function test_generate_all_invoice()
    {
        $invoices = Invoice::factory(3)->create();

        $response = $this->get(route('api.invoice.report'));
        
        $response->assertSuccessful()
            ->assertStatus(200)
            ->assertJson(['ok' => true]);

        $reportItems = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertEquals(
            $invoices->pluck('id')->toArray(),
            $reportItems
        );
               
    }

    function test_generate_invoice_by_status_filter()
    {

        foreach(InvoiceStatus::cases() as $status)
        {
            $invoices = Invoice::factory(3)->create([
                'status' => $status
            ]);

            $reportInvoices = collect($this->get(
                route('api.invoice.report', ['status' => $status])
            )->json('data'));
            
            $this->assertEquals(
                $invoices->pluck('id')->toArray(),
                $reportInvoices->pluck('id')->toArray()
            );

        }



    }
}