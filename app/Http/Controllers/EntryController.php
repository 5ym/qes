<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Bit flags packed into the `status` column.
     */
    private const STATUS_ENTRY = 1;

    private const STATUS_PAID = 2;

    /**
     * Render a status value as the human readable label the UI shows.
     */
    private static function label(int $status): string
    {
        return match ($status) {
            0 => 'unpaid, unentry',
            1 => 'unpaid, entry',
            2 => 'paid, unentry',
            3 => 'paid, entry',
            default => 'unknown',
        };
    }

    /**
     * Register a new entry and hand back its freshly generated secret.
     */
    public function setentry(Request $request): Entry
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        do {
            $randum = random_int(100000000, 999999999);
        } while (Entry::where('randum', $randum)->exists());

        return Entry::create($validated + [
            'randum' => $randum,
            'status' => 0,
        ]);
    }

    /**
     * Confirm that a secret belongs to an existing entry.
     *
     * @return array<string, string>
     */
    public function getentry(Request $request): array
    {
        $secret = $request->query('secret');

        abort_unless($secret !== null && Entry::where('randum', $secret)->exists(), 403);

        return ['status' => 'success'];
    }

    /**
     * Toggle the paid / entered flags for an entry.
     *
     * @return array<string, string>
     */
    public function upstatus(Request $request): array
    {
        $validated = $request->validate([
            'secret' => ['required'],
            'status' => ['required', 'in:pay,entry,pe'],
        ]);

        $entry = Entry::where('randum', $validated['secret'])->firstOrFail();

        $entry->status = match ($validated['status']) {
            'pay' => $entry->status ^ self::STATUS_PAID,
            'entry' => $entry->status ^ self::STATUS_ENTRY,
            'pe' => self::STATUS_PAID | self::STATUS_ENTRY,
        };

        $entry->save();

        return ['status' => self::label($entry->status)];
    }

    /**
     * Show the detail page for a single entry.
     */
    public function getstatus(Request $request): View
    {
        $secret = $request->query('secret');

        abort_if($secret === null, 403);

        $entry = Entry::where('randum', $secret)->firstOrFail();

        return view('status')->with([
            'name' => $entry->name,
            'contact' => $entry->contact,
            'address' => $entry->address,
            'status' => self::label($entry->status),
            'secret' => $entry->randum,
        ]);
    }

    /**
     * Show every entry.
     */
    public function list(): View
    {
        return view('list')->with(['list' => Entry::all()]);
    }

    /**
     * Status labels for every entry, keyed by secret.
     *
     * @return array<string, string>
     */
    public function liststatus(): array
    {
        return Entry::get(['status', 'randum'])
            ->mapWithKeys(fn (Entry $entry) => [$entry->randum => self::label($entry->status)])
            ->all();
    }
}
