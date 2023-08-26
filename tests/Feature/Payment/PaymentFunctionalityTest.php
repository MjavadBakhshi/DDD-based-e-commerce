<?php

namespace Tests\Feature\Product;

use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Domain\Payment\Enums\{IPGType, InvoiceStatus};
use Domain\Payment\Models\Invoice;
use Domain\Payment\Notifications\InvoicePaid;
use Domain\Product\Models\Inventory;
use Domain\Shared\Models\User;

class PaymentFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    function test_purchase_payment_when_items_are_available()
    {
        # purchase items
        $inventories = Inventory::factory(3)->create();

        $purchasedItems = $inventories->map(fn($item) => [
            'product_id' => $item->product->id,
            'quantity' => $item->quantity,
        ])->toArray();

        $user = User::factory()->create();
        # process payment
        $response = $this->actingAs($user)
                    ->post(route('api.payment.start'), [
                        'data' =>  $purchasedItems,
                    ]);
        # check process payment
        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson(['ok' => true]);

        /** Check the payment redirect uri has been generated? */
        $this->assertTrue(str::startsWith($response->json('data')['payment_uri'] , 'https://paypal.com/'));

        # check the inventory has been reduced?
        $inventories->each(fn($item) => $item->refresh());
        # test inventory
        $this->assertEquals([0, 0, 0], $inventories->pluck('quantity')->toArray());
        
        # Check invoice has been generated?
        $userInvoice = $user->invoices()->first();
        $this->assertModelExists($userInvoice);
     
        # Check invoice items has been saved correctly ?
        $this->assertEquals(
                $purchasedItems,  
                $userInvoice->items()->get()->map(fn($model) => $model->only('product_id', 'quantity'))
                    ->toArray()
        );

        # check payment record has been created ?
        $userpayment = $userInvoice->payments()->first();
        $this->assertModelExists($userpayment);
    }

    function test_products_payment_when_some_items_unavailable()
    {
        $inventories = Inventory::factory(3)->create();
       
        $data = $inventories->map(fn($item) => [
            'product_id' => $item->product->id,
            'quantity' => $item->quantity,
        ])->toArray();

        # we want produt 1 and 2 one item more than available in the inventory.
        $data[0]['quantity']++;
        $data[1]['quantity']++;

        $response = $this->post(route('api.payment.start'), [
            'data' => $data
        ]);

        $response
            ->assertSuccessful()
            ->assertStatus(205)
            # we expect the output result includes the product ids which are unavailable.
            ->assertJson(['ok' => false, 'data' => [
                $data[0]['product_id'], $data[1]['product_id']
            ]]);

        # test the inventory untouched in this case.
        $this->assertEquals(
            $inventories->pluck('quantity')->toArray(), 
            $inventories->map(fn($model) => $model->refresh())->pluck('quantity')->toArray()
        );
    }

    function test_payemnt_callback_when_payment_is_success() {
        # Moch invoice paid notification.
        Notification::fake();

        $invoice = Invoice::factory()->create([
            'status' => InvoiceStatus::Pending
        ]);

        $response = $this->get(route('payment.verify', ['invoice_id' => $invoice->id]));

        $response->assertSuccessful()
            ->assertStatus(200);
        
        /** Test invoice staus has been changed to paid? */
        $this->assertEquals(InvoiceStatus::Paid, $invoice->refresh()->status);

        
        # Test the notification has been sent to admin after invoice has been paid?
        Notification::assertSentTo(
            new AnonymousNotifiable(),
            InvoicePaid::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] == config('notification.email.recipients');
            }
        );
    }

    function test_payemnt_callback_when_payment_is_failed() {
        /** Setting the visacard IPG as default. */
        config(['ipg.default' => IPGType::VisaCard]);

        $inventories = Inventory::factory(5)->create();
        $purchasedProducts = $inventories->take(3)->only('product_id', 'quantity')->toArray();

        $user = User::factory()->create();
        /** Start payment. */
        $response = $this->actingAs($user)
            ->post(route('api.payment.start'), [
                'data' =>  $purchasedProducts
            ]);

        $response->assertSuccessful()
            ->assertStatus(200)
            ->assertJson(['ok' => true]);
        $this->assertTrue(Str::startsWith($response->json('data')['payment_uri'], 'https://visa'));

        /** Validate payment */
        $invoice = $user->invoices()->first();
        $response = $this->get(route('payment.verify', ['invoice_id' => $invoice->id]));

        $response->assertSuccessful()
            ->assertStatus(200);

        # Test the status of invoice that should be failed.
        $this->assertEquals(InvoiceStatus::Failed, $invoice->refresh()->status);        

        /** Test inventory is untouched and the reserved items has been returned to it? */
        $this->assertEquals(
            $inventories->pluck('quantity')->toArray(),
            $inventories->map(fn($model) => $model->refresh())->pluck('quantity')->toArray()
        );
    }

}