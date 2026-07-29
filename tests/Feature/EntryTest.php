<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_entry_form_is_public(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_registering_an_entry_returns_a_secret(): void
    {
        $response = $this->postJson('/entry', [
            'name' => 'Taro',
            'contact' => 'taro@example.com',
            'address' => 'Tokyo',
        ]);

        $response->assertCreated()->assertJsonPath('name', 'Taro');

        $entry = Entry::sole();

        $this->assertSame(0, $entry->status);
        $this->assertGreaterThanOrEqual(100000000, (int) $entry->randum);
        $this->assertLessThanOrEqual(999999999, (int) $entry->randum);
    }

    public function test_registering_an_entry_requires_every_field(): void
    {
        $this->postJson('/entry', ['name' => 'Taro'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['contact', 'address']);
    }

    public function test_a_known_secret_can_be_looked_up(): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 0,
        ]);

        $this->getJson('/entry?secret='.$entry->randum)
            ->assertOk()
            ->assertExactJson(['status' => 'success']);
    }

    public function test_an_unknown_secret_is_rejected(): void
    {
        $this->getJson('/entry?secret=000000000')->assertForbidden();
        $this->getJson('/entry')->assertForbidden();
    }

    public function test_staff_endpoints_require_authentication(): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 0,
        ]);

        $this->get('/list')->assertRedirect('/login');
        $this->post('/list')->assertRedirect('/login');
        $this->get('/status?secret='.$entry->randum)->assertRedirect('/login');
        $this->post('/status', ['secret' => $entry->randum, 'status' => 'pe'])
            ->assertRedirect('/login');

        $this->assertSame(0, $entry->fresh()->status);
    }

    public function test_staff_can_read_the_entry_list(): void
    {
        Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 2,
        ]);

        $this->actingAs(User::factory()->create());

        $this->get('/list')->assertOk()->assertSee('Taro');
        $this->postJson('/list')->assertOk()
            ->assertExactJson(['123456789' => 'paid, unentry']);
    }

    public function test_staff_can_read_a_single_status(): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 3,
        ]);

        $this->actingAs(User::factory()->create());

        $this->get('/status?secret='.$entry->randum)
            ->assertOk()
            ->assertSee('paid, entry');
    }

    /**
     * The pre-upgrade implementation added or subtracted the flag instead of
     * toggling it, so marking a paid-but-unentered guest as entered silently
     * cleared the paid flag (2 -> 1) rather than reaching "paid, entry" (3).
     */
    public function test_toggling_flags_never_clears_the_other_flag(): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 2,
        ]);

        $this->actingAs(User::factory()->create());

        $this->postJson('/status', ['secret' => $entry->randum, 'status' => 'entry'])
            ->assertOk()
            ->assertExactJson(['status' => 'paid, entry']);

        $this->assertSame(3, $entry->fresh()->status);
    }

    /**
     * @param  0|1|2|3  $from
     * @param  0|1|2|3  $to
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('statusTransitions')]
    public function test_status_transitions(int $from, string $action, int $to): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => $from,
        ]);

        $this->actingAs(User::factory()->create());

        $this->postJson('/status', ['secret' => $entry->randum, 'status' => $action])
            ->assertOk();

        $this->assertSame($to, $entry->fresh()->status);
    }

    /**
     * @return array<string, array{int, string, int}>
     */
    public static function statusTransitions(): array
    {
        return [
            'pay on unpaid' => [0, 'pay', 2],
            'pay off paid' => [2, 'pay', 0],
            'entry on unentered' => [0, 'entry', 1],
            'entry off entered' => [1, 'entry', 0],
            'entry keeps paid' => [2, 'entry', 3],
            'pay keeps entered' => [1, 'pay', 3],
            'pe forces both' => [0, 'pe', 3],
        ];
    }

    public function test_an_unknown_action_is_rejected(): void
    {
        $entry = Entry::create([
            'name' => 'Taro', 'contact' => 'c', 'address' => 'a',
            'randum' => '123456789', 'status' => 0,
        ]);

        $this->actingAs(User::factory()->create());

        $this->postJson('/status', ['secret' => $entry->randum, 'status' => 'nope'])
            ->assertUnprocessable();

        $this->assertSame(0, $entry->fresh()->status);
    }
}
